<?php

$table = 'stock';
$action = 'stock_overview';
$ajax = checkajax();

if (!$ajax) {
    initlist();

    $cmsmain->assign('title', 'Stock Planning');

    listsetting('allowcopy', false);
    listsetting('allowadd', false);
    listsetting('allowdelete', false);
    listsetting('allowselect', false);
    listsetting('allowselectall', false);
    listsetting('allowsort', false);
    listsetting('allowmove', false);
    listsetting('allowcollapse', true);
    listsetting('listrownotclickable', false);
    listsetting('customhrefinrow', true);

    $statusarray = ['in_stock' => 'In Stock', 'all' => 'All Box States', 'donated' => 'Donated', 'lost' => 'Lost', 'scrap' => 'Scrap', 'untouched' => 'Untouched for 3 months'];
    listfilter(['label' => 'Boxes', 'options' => $statusarray]);

    function box_state_id_from_filter($applied_filter)
    {
        return match ($applied_filter) {
            'in_stock' => 1,
            'donated' => 5,
            'lost' => 2,
            'scrap' => 6,
            'marked_for_shipment' => 3,
            default => 1,
        };
    }

    // Set filter to InStock by default
    if (!isset($listconfig['filtervalue'])) {
        $listconfig['filtervalue'] = 'in_stock';
    }

    $genders = db_simplearray('SELECT id AS value, label FROM genders ORDER BY seq');
    listfilter2(['label' => 'Gender', 'options' => $genders]);
    listsetting('filter2cssclass', 'overview-filter-gender');

    listfilter3(['label' => 'By location', 'query' => 'SELECT id AS value, label FROM locations WHERE deleted IS NULL AND locations.box_state_id IN (1,5) AND camp_id = '.$_SESSION['camp']['id'].' AND type = "Warehouse" ORDER BY seq']);
    listsetting('filter3cssclass', 'overview-filter-locations');

    // Tag Filter
    $tags = db_simplearray('SELECT id, label FROM tags WHERE camp_id = :camp_id AND deleted IS NULL AND `type` IN ("All", "Stock") ORDER BY seq', ['camp_id' => $_SESSION['camp']['id']]);
    $tagfilter = ['id' => 'tagfilter', 'placeholder' => 'Tag filter', 'options' => db_array('SELECT id, id AS value, label, color FROM tags WHERE camp_id = :camp_id AND deleted IS NULL AND `type` in ("All","Stock") ORDER BY seq', ['camp_id' => $_SESSION['camp']['id']])];
    listsetting('multiplefilter', $tagfilter);

    addcolumn('text', 'Category', 'label');
    addcolumn('text', 'Subtypes', 'subtypes');
    addcolumn('text', 'Items', 'N_items');
    addcolumn('text', 'Locations', 'num_locations');

    $joinquery = 'SELECT
                a.*,
                IF(isnull(a.location),IF(isnull(a.Gender),IF(isnull(a.prod_id),"Category","Product"),"Gender"),"Size") as labelname,
                IF(isnull(a.location),IF(isnull(a.Gender),IF(isnull(a.prod_id),a.Category,a.Product),a.Gender),a.size) as label,
                TRIM(trailing "-" from concat('
                .('untouched' == $_SESSION['filter']['stock_overview'] ? 1
                    : ('all' == $_SESSION['filter']['stock_overview'] ? 0
                        : box_state_id_from_filter($_SESSION['filter']['stock_overview'])))
                .',"-",'
                .($_SESSION['filter3']['stock_overview'] ? intval($_SESSION['filter3']['stock_overview']) : 0)
                .',"-",
                    IF(isnull(a.cat_id),"",a.cat_id),
                    "-",
                    if(isnull(a.prod_id),"",a.prod_id),
                    "-",
                    if(isnull(a.g_id),"",a.g_id),
                    "-",
                    if(isnull(a.size_id),"",a.size_id),
                    "-",
                    if(isnull(a.loc_id),"",a.loc_id)
                    )) as new_id
            FROM
                (SELECT 
                    agrouping.*,
                    IF(ISNULL(agrouping.cat_id),1,0) + IF(ISNULL(agrouping.prod_id),1,0) + IF(ISNULL(agrouping.g_id),1,0) + IF(ISNULL(agrouping.loc_id),1,0) AS "level" 
                FROM
                    (SELECT 
                        pc.label As "Category",
                        pc.id as "cat_id", 
                        p.name As "Product",
                        p.group_id as "prod_id", 
                        g.label As "Gender",
                        g.id as "g_id", 
                        sizes.label as "size", 
                        sizes.id as "size_id",
                        locations.label as "location",
                        locations.id as "loc_id", 
                        count(stock.id) as "N_Boxes", 
                        sum(stock.items) as "N_items"
                    FROM 
                        product_categories as pc
                    INNER JOIN
                        (SELECT 
                            prod_a.group_id as group_id, 
                            prod_a.name as group_name,
                            prod_b.id as id,
                            prod_b.name as name,
                            prod_b.category_id as category_id,
                            prod_b.gender_id as gender_id
                        FROM
                            -- get a list of unique product names (case-insensitive) for a given camp,
                            -- each with the minimum product id serving as the group identifier
                            (SELECT 
                                min(a.id) as group_id,
                                upper(a.name) as name 
                            FROM 
                                products as a 
                            INNER JOIN
                                products as b ON upper(a.name)=upper(b.name) 
                            WHERE 
                                a.camp_id = :camp_id and b.camp_id = :camp_id and a.id<=b.id 
                            GROUP BY 
                                upper(a.name)
                            ) prod_a 
                        LEFT JOIN 
                            products as prod_b ON prod_a.name = upper(prod_b.name)
                        ) as p ON pc.id = p.category_id
                    INNER JOIN
                        genders as g ON p.gender_id = g.id 
                    INNER JOIN 
                        stock ON p.id = stock.product_id 
                    INNER JOIN
                        sizes ON stock.size_id = sizes.id 
                    INNER JOIN
                        locations on stock.location_id = locations.id 
                    INNER JOIN 
                        box_state bs on bs.id = stock.box_state_id'
                // Join tags here only if a tag filter is selected and only boxes with a certain tag should be returned
                .($listconfig['multiplefilter_selected'] ? '
                        INNER JOIN
                            tags_relations ON tags_relations.object_id = stock.id AND tags_relations.object_type = "Stock" AND tags_relations.deleted_on IS NULL
                        INNER JOIN
                            tags ON tags.id = tags_relations.tag_id AND tags.deleted IS NULL AND tags.camp_id = '.$_SESSION['camp']['id'] : '').' 
                    WHERE 
                        locations.camp_id = :camp_id 
                        AND locations.type = "Warehouse"
                        AND (NOT stock.deleted OR stock.deleted IS NULL)'
                    .($_SESSION['filter2']['stock_overview'] ? ' AND (g.id = '.intval($_SESSION['filter2']['stock_overview']).')' : '')
                    .($_SESSION['filter3']['stock_overview'] ? ' AND (locations.id = '.intval($_SESSION['filter3']['stock_overview']).')' : '')
                    .('untouched' == $_SESSION['filter']['stock_overview'] ? ' AND DATEDIFF(now(),stock.modified) > 90 AND stock.box_state_id = 1'
                        : ('all' == $_SESSION['filter']['stock_overview'] ? ''
                            : ($_SESSION['filter']['stock_overview'] ? ' AND (stock.box_state_id = '.box_state_id_from_filter($_SESSION['filter']['stock_overview']).') '
                                : ' AND (stock.box_state_id = 1) ')))
                    .($listconfig['multiplefilter_selected'] ? ' AND tags.id IN ('.implode(',', $listconfig['multiplefilter_selected'] ?? []).') ' : '')
                .' GROUP BY 
                        pc.label,pc.id,p.name,p.group_id,g.label,g.id,sizes.label,sizes.id,locations.label,locations.id WITH ROLLUP 
                    ) as agrouping
                WHERE
                    IF(ISNULL(agrouping.size_id),1,0) = IF(ISNULL(agrouping.loc_id),1,0)
                ) as a 
            WHERE 
                isnull(a.Category)=isnull(a.cat_id) and isnull(a.Product)=isnull(a.prod_id) and isnull(a.Gender)=isnull(a.g_id) and isnull(size)=isnull(size_id) and isnull(location)=isnull(loc_id)
            ';

    $rawdata = 'SELECT DISTINCTROW
                a1.labelname,
                a1.label,
                a1.new_id as id,
                a1.N_boxes,
                a1.N_items, 
                3-a1.level as level,
                a2.new_id as parent_id, 
                a1.location as location, 
                a1.size as size
            FROM
                ('.$joinquery.') AS a1
            INNER JOIN 
                ('.$joinquery.') AS a2
            ON 
                (INSTR(a1.new_id,concat(a2.new_id,"-"))=1 OR a2.new_id = "") and a1.level+1 = a2.level and (a2.level != 5)
            ORDER BY 
                id';

    $subtypes = 'SELECT 
                IF(isnull(raw_a.size),CONCAT(COUNT(DISTINCT raw_b.label)," ",raw_b.labelname,IF(COUNT( DISTINCT raw_b.label)>1,"s","")),raw_b.size) as subtypes, 
                raw_a.id
            FROM
                ('.$rawdata.') AS raw_a 
            LEFT JOIN ('.$rawdata.') AS raw_b ON (raw_a.id=raw_b.parent_id)
            WHERE 
                raw_a.id = raw_b.parent_id OR raw_a.level = 3 
            GROUP BY 
                raw_a.id';

    $locations = 'SELECT 
                raw_a.id as id,
                IF(count(distinct raw_b.location)=1,raw_b.location,concat(count(distinct raw_b.location)," locations")) as num_locations
            FROM 
                ('.$rawdata.') AS raw_a 
            INNER JOIN 
                ('.$rawdata.') AS raw_b 
            ON 
                INSTR(raw_b.id,concat(raw_a.id,"-"))=1 AND raw_b.location IS NOT NULL 
            GROUP BY 
                raw_a.id';

    $data = db_array('SELECT 
                IF((counts.subtypes=0),counts.subtypes,counts.subtypes) as subtypes, 
                IF(ISNULL(complete.location),num_locations.num_locations,complete.location) as num_locations, 
                complete.*  
            FROM 
                ('.$rawdata.')as complete 
            LEFT JOIN
                ('.$subtypes.') AS counts ON complete.id=counts.id 
            LEFT JOIN
                ('.$locations.') AS num_locations
            ON 
                complete.id=num_locations.id
            ORDER BY 
                CAST(SUBSTRING_INDEX(complete.id, "-",1) AS SIGNED), 
                complete.id;', ['camp_id' => $_SESSION['camp']['id']]);

    // Add what rows are expanded and collapsed
    foreach ($data as &$row) {
        if (isset($_SESSION['stock_overview']) && is_array($_SESSION['stock_overview'])) {
            if (in_array($row['id'], $_SESSION['stock_overview'])) {
                $row['notCollapsed'] = true;
            }
        }

        // Build v2 URL with filter parameters
        [$boxstate, $locationfromfilter, $category, $product, $gender, $size, $location] = explode('-', (string) $row['id']);

        $filterParams = [];

        if ($boxstate) {
            $filterParams[] = 'state_ids='.$boxstate;
        } else {
            $stateIds = db_simplearray('SELECT id FROM box_state', null, false, false);
            $filterParams[] = 'state_ids='.implode(',', $stateIds);
        }
        if ($category) {
            $filterParams[] = 'product_category_ids='.$category;
        }
        if ($product) {
            // Product in the ID is the group_id, need to get all IDs of non-deleted products in this base matching its name
            $productIds = db_simplearray('SELECT id FROM products WHERE UPPER(name) = (SELECT UPPER(name) FROM products WHERE id = :group_id) AND camp_id = :camp_id AND (NOT deleted OR deleted IS NULL)', ['group_id' => $product, 'camp_id' => $_SESSION['camp']['id']], false, false);
            if ($productIds) {
                $filterParams[] = 'product_ids='.implode(',', $productIds);
            }
        }
        if ($gender) {
            $filterParams[] = 'gender_ids='.$gender;
        }
        if ($size) {
            $filterParams[] = 'size_ids='.$size;
        }
        // Use location if present, otherwise use locationfromfilter
        if ($location) {
            $filterParams[] = 'location_ids='.$location;
        } elseif ($locationfromfilter) {
            $filterParams[] = 'location_ids='.$locationfromfilter;
        }

        // Add tag filters if present
        if ($listconfig['multiplefilter_selected']) {
            $filterParams[] = 'tag_ids='.implode(',', $listconfig['multiplefilter_selected']);
        }

        $queryString = $filterParams ? '?'.implode('&', $filterParams) : '';
        $row['href'] = $settings['v2_base_url'].'/bases/'.$_SESSION['camp']['id'].'/boxes'.$queryString;
    }

    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
} else {
    switch ($_POST['do']) {
        case 'collapse':
            $_SESSION['stock_overview'] = $_POST['ids'];

            break;

        case 'collapseall':
            unset($_SESSION['stock_overview']);

            break;
    }

    echo json_encode($return);

    exit;
}
