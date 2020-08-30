<?php

    $ajax = checkajax();

    $table = 'transactions';
    $action = 'check_out';

    if (!$ajax) {
        $data = db_row('SELECT * FROM '.$table.' WHERE id = :id', ['id' => $id]);

        if (!$id) {
            $data['visible'] = 1;
            $data['count'] = 1;
            $data['people_id'] = intval($_GET['people_id']);
        }

        verify_campaccess_people($data['people_id']);
        verify_deletedrecord('people', $data['people_id']);

        $data['hidesubmit'] = true;
        $data['hidecancel'] = true;

        $translate['cms_form_submit'] = 'Save & next purchase';
        $cmsmain->assign('translate', $translate);

        // open the template
        $cmsmain->assign('include', 'cms_form.tpl');
        addfield('hidden', '', 'id');

        // put a title above the form
        $cmsmain->assign('title', 'New purchase');

        addfield('select', 'Family/Beneficiary', 'people_id', ['testid' => 'familyDropdown', 'onchange' => 'selectFamily("people_id",false,"check_out")', 'required' => true, 'multiple' => false, 'query' => 'SELECT p.id AS value, CONCAT(p.container, " ",p.firstname, " ", p.lastname) AS label, NOT visible AS disabled FROM people AS p WHERE parent_id IS NULL AND NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' GROUP BY p.id ORDER BY SUBSTRING(REPLACE(container,"PK","Z"),1,1), SUBSTRING(REPLACE(container,"PK","Z"), 2, 10)*1']);
        addfield('select', 'Product', 'product_id', ['required' => true, 'multiple' => false, 'query' => 'SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,""), " (",p.value," '.$_SESSION['camp']['currencyname'].')") AS label, p.value as price FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE (NOT p.deleted OR p.deleted IS NULL) AND p.camp_id = '.$_SESSION['camp']['id'].' ORDER BY name']);
        addfield('number', 'Number', 'count', ['required' => true, 'width' => 2, 'min' => 1, 'testid' => 'productQuantityInput']);
        addfield('custom', '', '<button id="add-to-cart-button" type="button" class="btn" data-testid="add-to-cart-button" disabled>Add to cart</button>');
        //addfield('text','Note','description');
        addfield('line');

        addfield('custom', '', '<button type="button" id="submitShoppingCart" data-testid="submitShoppingCart" value="" class="btn btn-submit" disabled>Save & next purchase</button>', ['aside' => true, 'asidetop' => true]);
        addfield('ajaxstart', '', '', ['id' => 'ajax-content']);
        addfield('ajaxend');

        addfield('ajaxstart', '', '', ['aside' => true, 'asidetop' => true, 'id' => 'ajax-aside']);
        addfield('ajaxend', '', '', ['aside' => true]);

        addfield('created', 'Created', 'created', ['aside' => true]);

        // place the form elements and data in the template
        $cmsmain->assign('data', $data);
        $cmsmain->assign('formelements', $formdata);
        $cmsmain->assign('formbuttons', $formbuttons);
    } else {
        if ('delete' == $_POST['do']) {
            $ids = explode(',', $_POST['ids']);
            // check if person is allowed to delete transaction
            foreach ($ids as $id) {
                verify_campaccess_people(db_value('SELECT people_id FROM transactions WHERE id=:id', ['id' => $id]));
            }
            list($success, $message, $redirect) = listDelete($table, $ids);
            $return = ['success' => $success, 'message' => $message, 'redirect' => false, 'action' => "$('#field_people_id').trigger('change')"];

            echo json_encode($return);
            die();
        }

        // verify acces if data of a person is requested
        verify_campaccess_people($_POST['people_id']);
        verify_deletedrecord('people', $_POST['people_id']);

        // set camp depending on people_id
        $camp = db_row('
            SELECT c.* FROM camps c 
            WHERE id = (
                SELECT p.camp_id FROM people p
                WHERE p.id = :people_id)', ['people_id' => $_POST['people_id']]);

        // Ajax POST request of shopping cart
        if ($_POST['cart']) {
            $_POST['transaction_date'] = strftime('%Y-%m-%d %H:%M:%S');
            $_POST['user_id'] = $_SESSION['user']['id'];

            $cart = json_decode($_POST['cart'], true);
            $savekeys = ['people_id', 'product_id', 'count', 'drops', 'transaction_date', 'user_id'];
            $notificationText = '<b>Shopping cart successfully submitted!</b> </br><p>Items bought:</p> <ul>';
            foreach ($cart as $item) {
                $_POST['product_id'] = intval($item['id']);
                $_POST['count'] = intval($item['count']);
                $_POST['drops'] = $item['price'] * intval($item['count']) * (-1);

                $notificationText = $notificationText.'<li>'.$item['count'].'x '.$item['nameWithoutPrice'].' - '.$_POST['drops'] * (-1).' '.$camp['currencyname'].'</li>';

                $handler = new formHandler($table);
                $id = $handler->savePost($savekeys);
            }
            $notificationText = $notificationText.'</ul> </br> <b>Thanks for helping!</b>';
            $return = ['success' => true, 'message' => $notificationText, 'redirect' => '?action=check_out'];

            echo json_encode($return);
            die();
        }

        $ajaxform = new Zmarty();

        // vanaf hier

        $data['people_id'] = intval($_POST['people_id']);
        $data['allowdrops'] = allowGiveDrops();

        // This can be a warning that is given based on certain shopping actions in the past.
        // 		$data['shoeswarning'] = db_value('SELECT COUNT(id) FROM transactions WHERE people_id = :id AND product_id IN (63,709) AND transaction_date >= "2017-11-13 00:00"', array('id'=>$data['people_id']));

        // Shopping cart
        addfield('shopping_cart', '', '', ['columns' => ['product' => ['name' => 'Product'], 'count' => ['name' => 'Amount', 'width' => '25%'], 'drops2' => ['name' => 'Price', 'width' => '15%'], 'drops3' => ['name' => 'Total Price', 'width' => '15%'], 'delete' => ['name' => '']]]);

        $table = 'transactions';
        addfield('title', 'Last Purchases', '', ['labelindent' => true, 'id' => 'LastPurchaseTitle']);
        addfield('list', '', 'purch', ['width' => 10, 'query' => 'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, count /*AS countupdown*/, DATE_FORMAT(transaction_date,"%d-%m-%Y %H:%i") AS tdate, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS product 
            FROM transactions AS t 
            LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id 
            LEFT OUTER JOIN products AS p ON p.id = t.product_id 
            LEFT OUTER JOIN genders AS g ON p.gender_id = g.id 
            WHERE people_id = '.$data['people_id'].' 
            AND t.product_id IS NOT NULL 
            AND CAST(transaction_date as Date)=(SELECT CAST(MAX(transaction_date) as Date) FROM transactions WHERE people_id = '.$data['people_id'].' AND product_id IS NOT NULL)
            ORDER BY t.transaction_date DESC', 'columns' => ['product' => 'Product', 'count' => 'Amount', 'drops2' => ucwords($camp['currencyname']), 'tdate' => 'Date'],
            'allowedit' => false, 'allowadd' => false, 'allowselect' => true, 'allowselectall' => false, 'action' => 'check_out', 'redirect' => false, 'allowsort' => false, 'listid' => $data['people_id'], ]);

        $ajaxform->assign('data', $data);
        $ajaxform->assign('formelements', $formdata);
        $ajaxform->assign('formbuttons', $formbuttons);
        $htmlcontent = $ajaxform->fetch('cms_form_ajax.tpl');

        // the aside
        $ajaxaside = new Zmarty();

        $data['people'] = db_array('
            SELECT 
                people.*, 
                DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), people.date_of_birth)), "%Y")+0 AS age, 
                GROUP_CONCAT(tags.label) AS taglabels,
                GROUP_CONCAT(tags.color) AS tagcolors
            FROM 
                people 
            LEFT JOIN
                people_tags ON people_tags.people_id = people.id
            LEFT JOIN
                tags ON tags.id = people_tags.tag_id 
            WHERE 
                (people.parent_id = :id OR people.id = :id) AND 
                NOT people.deleted 
            GROUP BY
                people.id
            ORDER BY 
                people.parent_id, people.seq', ['id' => $data['people_id']]);
        foreach ($data['people'] as $key => $person) {
            if ($data['people'][$key]['taglabels']) {
                $taglabels = explode(',', $data['people'][$key]['taglabels']);
                $tagcolors = explode(',', $data['people'][$key]['tagcolors']);
                foreach ($taglabels as $tagkey => $taglabel) {
                    $data['people'][$key]['tags'][$tagkey] = ['label' => $taglabel, 'color' => $tagcolors[$tagkey], 'textcolor' => get_text_color($tagcolors[$tagkey])];
                }
            }
        }

        $adults = $camp['maxfooddrops_adult'] * db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < '.$camp['adult_age'].', 0, 1)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ', ['id' => $data['people_id']]);
        $children = $camp['maxfooddrops_child'] * db_value('SELECT SUM(IF((DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0) < '.$camp['adult_age'].', 1, 0)) AS adults FROM people WHERE id = :id OR parent_id = :id AND NOT deleted ', ['id' => $data['people_id']]);

        $data['fooddrops'] = intval($adults) + intval($children);
        $data['foodspent'] = db_value('SELECT SUM(drops) FROM transactions AS t, products AS p WHERE t.product_id = p.id AND p.category_id = 11 AND t.people_id = :id AND t.transaction_date > (SELECT cyclestart FROM camps WHERE id = 1)', ['id' => $data['people_id']]);
        $data['fooddrops'] += $data['foodspent'];

        $data['dropcoins'] = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id', ['id' => $data['people_id']]);

        $data['givedropsurl'] = '?action=give&ids='.$data['people_id'];
        $data['person'] = $data['people_id'];
        $data['lasttransaction'] = displaydate(db_value('SELECT transaction_date FROM transactions WHERE product_id > 0 AND people_id = :id ORDER BY transaction_date DESC LIMIT 1', ['id' => $data['people_id']]), true);

        $ajaxaside->assign('data', $data);
        $ajaxaside->assign('currency', $camp['currencyname']);
        $htmlaside = $ajaxaside->fetch('info_aside_purchase.tpl');

        $success = true;
        $return = ['success' => $success, 'htmlcontent' => $htmlcontent, 'htmlaside' => $htmlaside, 'message' => $message];
        echo json_encode($return);
    }
