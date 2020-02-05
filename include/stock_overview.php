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

    addcolumn('text', 'Labelname', 'labelname');
    addcolumn('text', 'Label', 'label');
    addcolumn('text', 'Level', 'level');
    addcolumn('text', 'ID', 'id');
    addcolumn('text', 'Size', 'size');
    addcolumn('text', 'Loctions', 'location');

    $data = db_array('SELECT 
                            id, 
                            label, 
                            IF(id=1,0,IF(id=2,1,IF(id=3,2,IF(id=4,2,IF(id=6,1,0))))) as level, 
                            IF(id=1,0,IF(id=2,1,IF(id=3,2,IF(id=4,2,IF(id=6,5,0))))) as parent_id 
                        FROM genders');

$data = db_array('select distinct
IF(isnull(a.location),IF(isnull(a.Gender),IF(isnull(a.Product),"Category","Product"),"Gender"),"Location") as labelname,
IF(isnull(a.location),IF(isnull(a.Gender),IF(isnull(a.Product),a.Category,a.Product),a.Gender),a.Location) as label,
b.new_id as parent_id,
3 - a.level as level,a.new_id as id, a.size as size ,a.location as location
from 
(Select Pc.label As "Category",pc.id as "cat_id", P.name As "Product",p.id as "prod_id", G.label As "Gender",g.id as "g_id", Sizes.label as "size", sizes.id as "size_id",locations.label as "location",locations.id as "loc_id", count(stock.id) as "N_Boxes", sum(stock.items) as "N_items", TRIM(trailing "-" from 
concat(IF(isnull(pc.id),"",pc.id),"-",if(isnull(p.id),"",p.id),"-",if(isnull(g.id),"",g.id),"-",if(isnull(sizes.id),"",sizes.id),"-",if(isnull(locations.id),"",locations.id))) 
as new_id,
grouping(pc.id)+grouping(p.id)+grouping(g.id)+grouping(locations.id) as level from product_categories as pc inner join products as p ON pc.id = p.category_id inner join genders as g on p.gender_id = g.id inner join stock on p.id = stock.product_id inner join sizes on stock.size_id = sizes.id inner join locations on stock.location_id = locations.id where locations.camp_id = 1 GROUP BY pc.label,pc.id,p.name,p.id,g.label,g.id,sizes.label,sizes.id,locations.label,locations.id With Rollup having grouping(size)=grouping(loc_id)) as a
inner join 
(Select Pc.label As "Category",pc.id as "cat_id", P.name As "Product",p.id as "prod_id", G.label As "Gender",g.id as "g_id", Sizes.label as "size",sizes.id as "size_id",locations.label as "location",locations.id as "loc_id", count(stock.id) as "N_Boxes", sum(stock.items) as "N_items", TRIM(trailing "-" from concat(IF(isnull(pc.id),"",pc.id),"-",if(isnull(p.id),"",p.id),"-",if(isnull(g.id),"",g.id),"-",if(isnull(sizes.id),"",sizes.id),"-",if(isnull(locations.id),"",locations.id))) as new_id, grouping(pc.id)+grouping(p.id)+grouping(g.id)+grouping(locations.id) as level from product_categories as pc inner join products as p ON pc.id = p.category_id inner join genders as g on p.gender_id = g.id inner join stock on p.id = stock.product_id inner join sizes on stock.size_id = sizes.id inner join locations on stock.location_id = locations.id where locations.camp_id = 1 GROUP BY pc.label,pc.id,p.name,p.id,g.label,g.id,sizes.label,sizes.id,Locations.label,locations.id With Rollup having grouping(size)=grouping(loc_id))
as b on INSTR(a.new_id,b.new_id)=1 and a.level+1 = b.level and (b.level != 5) where isnull(a.Gender)=isnull(a.g_id) and isnull(b.Gender)=isnull(b.g_id) and isnull(a.prod_id)=isnull(a.product) and isnull(b.prod_id)=isnull(b.product)ORDER BY id;');

    $realdata = db_array("select IF(ISNULL(category),'Allcategories',category),product,gender,size,location,isnull(product)+isnull(gender)+isnull(size)+isnull(category)+isnull(location) as 'level',sum(N_boxes), sum(N_items) from (select pc.label as 'category', p.name as 'product', sizes.label as 'size', g.label as 'gender', locations.label as 'location', count(stock.id) as 'N_Boxes', sum(stock.items) as 'N_items', concat(pc.id,'-',p.id,'-',sizes.id,'-',g.id,'-',locations.id) as 'C-P-S-G-L' from product_categories as pc inner join products as p ON pc.id = p.category_id inner join genders as g on p.gender_id = g.id inner join stock on p.id = stock.product_id inner join sizes on stock.size_id = sizes.id inner join locations on stock.location_id = locations.id GROUP BY p.id, locations.id, sizes.id,g.id,pc.id) as overview group by category,product,gender,size,location with rollup;");
    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
