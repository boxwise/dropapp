<?php

$table = 'transactions';
$action = 'sales_list';

if ($_POST) {
    // Save selected dates
    $_SESSION['salesstart'] = $_POST['startdate'];
    $_SESSION['salesend'] = $_POST['enddate'];

    $start = date('Y-m-d', strtotime((string) $_POST['startdate']));
    $end = date('Y-m-d', strtotime((string) $_POST['enddate']));

    $type = $_POST['type'][0];

    if ('graph' == $type) {
        $date = $start;

        while (strtotime($date) <= strtotime($end)) {
            $sales = db_value(
                'SELECT COUNT(t.id)
					FROM transactions AS t, people AS p
					WHERE t.people_id = p.id AND p.camp_id = :camp_id AND t.product_id > 0 AND DATE_FORMAT(t.transaction_date,"%Y-%m-%d") = :date',
                ['date' => $date, 'camp_id' => $_SESSION['camp']['id']]
            );

            if ($sales) {
                $test = db_simplearray('SELECT c.label AS gender, SUM(t.count)
						AS aantal FROM (transactions AS t, people AS pp)
						LEFT OUTER JOIN products AS p ON t.product_id = p.id
						LEFT OUTER JOIN product_categories AS c ON c.id = p.category_id
						WHERE t.people_id = pp.id AND pp.camp_id = :camp_id AND t.product_id > 0 AND t.transaction_date >= "'.$date.' 00:00" AND t.transaction_date <= "'.$date.' 23:59"
						GROUP BY p.category_id ORDER BY SUM(t.count)', ['camp_id' => $_SESSION['camp']['id']]);
                foreach ($test as $key => $value) {
                    $formattedDate = (new DateTime($date))->format('D j M');
                    $data[$formattedDate][$key] = $value;
                }
            }
            $date = date('Y-m-d', strtotime('+1 day', strtotime($date)));
        }

        // Open Template
        $cmsmain->assign('include', 'sales_graph.tpl');
        $cmsmain->assign('title', 'Sales overview');
        $cmsmain->assign('data', $data);
        $cmsmain->assign('formelements', $formdata);
        $cmsmain->assign('formbuttons', $formbuttons);
    } elseif ('export' == $type) {
        redirect('?action=sales_list_export');
    } else {
        // General statements for all lists
        initlist();

        // Total Sales and Drops added at each request at the bottom row
        $totalsales = db_value(
            'SELECT SUM(t.count) AS aantal
				FROM transactions AS t, people AS p
				WHERE t.people_id = p.id AND p.camp_id = :camp_id AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"',
            ['camp_id' => $_SESSION['camp']['id']]
        );
        $totaldrops = -1 * db_value(
            'SELECT SUM(t.drops) AS aantal
				FROM transactions AS t, people AS p
				WHERE t.people_id = p.id AND p.camp_id = :camp_id AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"',
            ['camp_id' => $_SESSION['camp']['id']]
        );

        if ('gender' == $type) {
            // Distribution of sales by gender
            $data = getlistdata('SELECT g.label AS gender, SUM(t.count) AS aantal
					FROM (transactions AS t, people AS pp)
					LEFT OUTER JOIN products AS p ON t.product_id = p.id
					LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
					WHERE t.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
					GROUP BY p.gender_id');

            addcolumn('text', 'Gender', 'gender');
            addcolumn('text', 'Amount', 'aantal');
            $cmsmain->assign('listfooter', ['Total sales', $totalsales.' items ('.$totaldrops.' '.$_SESSION['camp']['currencyname'].')']);
        } elseif ('byday' == $type) {
            // Distribution of sales by day
            $data = getlistdata('SELECT DATE_FORMAT(t.transaction_date,"%d-%m-%Y") AS salesdate, SUM(t.count) AS aantal
					FROM (transactions AS t, people AS pp)
					LEFT OUTER JOIN products AS p ON t.product_id = p.id
					LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
					WHERE t.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
					GROUP BY DATE_FORMAT(t.transaction_date,"%d-%m-%Y")
					ORDER BY t.transaction_date');

            foreach ($data as $key => $d) {
                $date = (new DateTime((string) $d['salesdate']))->format('Y-m-d');

                $data[$key]['people'] = db_value('SELECT COUNT(DISTINCT(p.id)) FROM people AS p, transactions AS t WHERE (p.id = t.people_id OR p.parent_id = t.people_id) AND t.transaction_date >= "'.$date.' 00:00" AND t.transaction_date <= "'.$date.' 23:59" AND t.product_id > 0 AND t.people_id > 0 AND camp_id = :campid', ['campid' => $_SESSION['camp']['id']]);
                $totalvisitors += $data[$key]['people'];
            }
            addcolumn('text', 'Sales', 'salesdate', ['headerClass' => 'sorter-shortDate dateFormat-ddmmyyyy']);    // tablesorter works with dates only if header has a proper class assigned - https://mottie.github.io/tablesorter/docs/example-option-date-format.html
            addcolumn('text', 'Amount', 'aantal');
            addcolumn('text', 'Beneficiaries', 'people');
            $cmsmain->assign('listfooter', ['Total sales', $totalsales.' items ('.$totaldrops.' '.$_SESSION['camp']['currencyname'].')', $totalvisitors]);
        } elseif ('category' == $type) {
            // Distribution of sales by products
            $data = getlistdata('SELECT pc.label AS name, SUM(t.count) AS aantal, -SUM(t.drops) AS drops
					FROM (transactions AS t, people AS pp)
					LEFT OUTER JOIN products AS p ON t.product_id = p.id
					LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
					LEFT OUTER JOIN product_categories AS pc ON p.category_id = pc.id
					WHERE t.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
					GROUP BY p.category_id');

            addcolumn('text', 'Product', 'name');
            addcolumn('text', 'Items', 'aantal');
            addcolumn('text', 'Drops', 'drops');
            $cmsmain->assign('listfooter', ['Total sales', $totalsales.' items', $totaldrops.' '.$_SESSION['camp']['currencyname']]);
        } elseif ('people' == $type) {
            // Get adult_age from camps table
            $adult_age = db_value('SELECT adult_age FROM camps WHERE id = :camp_id', ['camp_id' => $_SESSION['camp']['id']]);

            // Distribution of beneficiaries by gender and age group
            $data = getlistdata('WITH served_beneficiaries AS (
	-- CTE to obtain all beneficiaries involved in transactions in the specified camp and timeframe
	SELECT
		p.id,
		SUM(t.count) as total_items,
		COUNT(DISTINCT t.transaction_date) as total_visits
	FROM people p
	INNER JOIN transactions t
	ON t.people_id = p.id
	WHERE t.product_id > 0
	AND t.transaction_date >= "'.$start.' 00:00"
	AND t.transaction_date <= "'.$end.' 23:59"
	AND camp_id = '.$_SESSION['camp']['id'].'
	GROUP BY p.id
),
-- CTE to count number of family members per family head
family_members AS (
	SELECT parent_id, COUNT(DISTINCT p.id) as cnt
	FROM people p
	WHERE parent_id IS NOT NULL
	AND camp_id = '.$_SESSION['camp']['id'].'
	GROUP BY parent_id
)
-- Main query
SELECT
CASE
	WHEN (p.date_of_birth IS NULL OR NOT p.date_of_birth) AND p.gender = "M" THEN "Male (No DoB)"
	WHEN (p.date_of_birth IS NULL OR NOT p.date_of_birth) AND p.gender = "F" THEN "Female (No DoB)"
	WHEN (p.date_of_birth IS NULL OR NOT p.date_of_birth) THEN "No Gender (No DoB)"
	WHEN p.gender = "M" AND TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) >= '.$adult_age.' THEN "Male"
	WHEN p.gender = "F" AND TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) >= '.$adult_age.' THEN "Female"
	WHEN p.gender = "M" AND TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) < '.$adult_age.' THEN "Boy"
	WHEN p.gender = "F" AND TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) < '.$adult_age.' THEN "Girl"
	WHEN TIMESTAMPDIFF(YEAR, p.date_of_birth, CURDATE()) >= '.$adult_age.' THEN "No Gender"
	ELSE "No Gender (Child)"
END AS gender_category,
-- If a family member made the transaction, assign it with the family head to obtain number of benefitting families
COUNT(DISTINCT IFNULL(fm.parent_id, p.id)) AS total_families,
-- If a family consists of the head only, count 1 (i.e. fm.cnt is NULL); otherwise take the number
-- of family members plus 1 to account for the family head
SUM(IFNULL(fm.cnt+1, 1)) AS unique_recipients,
SUM(sb.total_items) AS total_items,
SUM(sb.total_visits) AS total_visits
FROM served_beneficiaries sb
INNER JOIN people AS p
ON p.id = sb.id
LEFT JOIN family_members fm
ON fm.parent_id = p.id
GROUP BY gender_category
ORDER BY FIELD(gender_category, "Male", "Female", "Boy", "Girl", "No Gender", "No Gender (Child)", "Male (No DoB)", "Female (No DoB)", "No Gender (No DoB)")');

            addcolumn('text', 'Gender/Age Group', 'gender_category');
            addcolumn('text', 'Total families served', 'total_families');
            addcolumn('text', 'Unique people reached', 'unique_recipients');
            addcolumn('text', 'Total items checked out', 'total_items');
            addcolumn('text', 'Total number of visits', 'total_visits');

            // Add informational text about adult age
            $cmsmain->assign('notification', 'Adults are beneficiaries of age '.$adult_age.' and older.');

            $total_families = array_sum(array_column($data, 'total_families'));
            $total_items = array_sum(array_column($data, 'total_items'));
            $cmsmain->assign('listfooter', ['Total', $total_families.' recipients', $total_items.' transactions']);
        } else {
            // Distribution of sales by products
            $data = getlistdata('SELECT p.name, g.label AS gender, SUM(t.count) AS aantal
					FROM (transactions AS t, people AS pp)
					LEFT OUTER JOIN products AS p ON t.product_id = p.id
					LEFT OUTER JOIN genders AS g ON p.gender_id = g.id
					WHERE t.people_id = pp.id AND pp.camp_id = '.$_SESSION['camp']['id'].' AND t.product_id > 0 AND t.transaction_date >= "'.$start.' 00:00" AND t.transaction_date <= "'.$end.' 23:59"
					GROUP BY t.product_id');

            addcolumn('text', 'Product', 'name');
            addcolumn('text', 'Gender', 'gender');
            addcolumn('text', 'Amount', 'aantal');
            $cmsmain->assign('listfooter', ['Total sales', '', $totalsales.' items ('.$totaldrops.' '.$_SESSION['camp']['currencyname'].')']);
        }

        listsetting('allowcopy', false);
        listsetting('allowedit', false);
        listsetting('allowadd', false);
        listsetting('allowdelete', false);
        listsetting('allowselect', false);
        listsetting('allowselectall', false);
        listsetting('allowsort', true);

        // Open Template
        $cmsmain->assign('include', 'cms_list.tpl');
        $cmsmain->assign('title', 'Sales between '.$_POST['startdate'].' and '.$_POST['enddate']);
        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
    }
} else {
    if ($_SESSION['salesstart']) {
        $data['startdate'] = $_SESSION['salesstart'];
    } else {
        $data['startdate'] = (new DateTime('-7 days'))->format('Y-m-d');
    }
    if ($_SESSION['salesend']) {
        $data['enddate'] = $_SESSION['salesend'];
    } else {
        $data['enddate'] = (new DateTime())->format('Y-m-d');
    }
    $data['type'] = 'product';

    addfield('date', 'Start date', 'startdate', ['date' => true, 'time' => false]);
    addfield('date', 'End date', 'enddate', ['date' => true, 'time' => false]);
    addfield('line');
    addfield('select', 'Type', 'type', ['options' => [
        ['value' => 'graph', 'label' => 'Sales graph'],
        ['value' => 'product', 'label' => 'By product'],
        ['value' => 'category', 'label' => 'By product category'],
        ['value' => 'gender', 'label' => 'By gender'],
        ['value' => 'byday', 'label' => 'Total sales by day'],
        ['value' => 'people', 'label' => 'By People'],
        ['value' => 'export', 'label' => 'All sales (csv)'], ]]);

    // open the template
    $cmsmain->assign('include', 'cms_form.tpl');
    // Title
    $cmsmain->assign('title', 'Sales overview');
    // Form Button
    $translate['cms_form_submit'] = 'Make sales list';
    $cmsmain->assign('translate', $translate);
    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
}
