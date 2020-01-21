<?php

    $table = 'genders';
    $action = 'stock_overview';

    initlist();

    $cmsmain->assign('title', 'General stock');

    listsetting('allowcopy', false);
    listsetting('allowadd', false);
    listsetting('allowdelete', false);
    listsetting('allowselect', false);
    listsetting('allowselectall', false);
    listsetting('allowsort', false);
    listsetting('allowedit', false);
    listsetting('allowmove', false);
    listsetting('allowcollapse', true);

    //addcolumn('text', 'Label', 'label');
    addcolumn('text', 'Category', 'category');
    addcolumn('text', 'product', 'Product');

    $data = db_array('SELECT 
                            id, 
                            label, 
                            IF(id=1,0,IF(id=2,1,IF(id=3,2,IF(id=4,2,IF(id=6,1,0))))) as level, 
                            IF(id=1,0,IF(id=2,1,IF(id=3,2,IF(id=4,2,IF(id=6,5,0))))) as parent_id 
                        FROM genders');

    $realdata = db_array("select IF(ISNULL(category),'Allcategories',category),product,gender,size,location,isnull(product)+isnull(gender)+isnull(size)+isnull(category)+isnull(location) as 'level',sum(N_boxes), sum(N_items) from (select pc.label as 'category', p.name as 'product', sizes.label as 'size', g.label as 'gender', locations.label as 'location', count(stock.id) as 'N_Boxes', sum(stock.items) as 'N_items', concat(pc.id,'-',p.id,'-',sizes.id,'-',g.id,'-',locations.id) as 'C-P-S-G-L' from product_categories as pc inner join products as p ON pc.id = p.category_id inner join genders as g on p.gender_id = g.id inner join stock on p.id = stock.product_id inner join sizes on stock.size_id = sizes.id inner join locations on stock.location_id = locations.id GROUP BY p.id, locations.id, sizes.id,g.id,pc.id) as overview group by category,product,gender,size,location with rollup;");
    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
