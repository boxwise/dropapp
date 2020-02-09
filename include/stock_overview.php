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

    addcolumn('text', 'Category', 'label');
    addcolumn('text', 'Types', 'size');
    addcolumn('text', 'items', 'N_items');
    addcolumn('text', 'Loctions', 'location');

    $data = db_array('SELECT 
                            id, 
                            label, 
                            IF(id=1,0,IF(id=2,1,IF(id=3,2,IF(id=4,2,IF(id=6,1,0))))) as level, 
                            IF(id=1,0,IF(id=2,1,IF(id=3,2,IF(id=4,2,IF(id=6,5,0))))) as parent_id 
                        FROM genders');

 $data = db_array('
    SELECT DISTINCTROW
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
        (SELECT
            a.*,
            IF(isnull(a.location),IF(isnull(a.Gender),IF(isnull(a.prod_id),"Category","Product"),"Gender"),"") as labelname,
            IF(isnull(a.location),IF(isnull(a.Gender),IF(isnull(a.prod_id),a.Category,a.Product),a.Gender),"") as label,
            TRIM(trailing "-" from concat(IF(isnull(a.cat_id),"",a.cat_id),"-",if(isnull(a.prod_id),"",a.prod_id),"-",if(isnull(a.g_id),"",a.g_id),"-",if(isnull(a.size_id),"",a.size_id),"-",if(isnull(a.loc_id),"",a.loc_id))) as new_id
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
                sum(stock.items) as "N_items",
                grouping(pc.id)+grouping(p.group_id)+grouping(g.id)+grouping(locations.id) as level 
            FROM 
                product_categories as pc
            INNER JOIN
                (SELECT 
                    prod_a.group_id as group_id, 
                    prod_a.name as group_name,
                    prod_b.id as id,prod_b.name as name,
                    prod_b.category_id as category_id,
                    prod_b.gender_id as gender_id
                FROM
                    (SELECT 
                        min(a.id) as group_id,
                        upper(a.name) as name 
                    FROM 
                        products as a 
                    INNER JOIN
                        products as b ON upper(a.name)=upper(b.name) 
                    WHERE 
                        a.id != b.id and a.sizegroup_id = b.sizegroup_id and a.camp_id = 1 and b.camp_id = 1 and a.id<b.id 
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
            WHERE 
                locations.camp_id = 1  -- fixed for now
            GROUP BY 
                pc.label,pc.id,p.name,p.group_id,g.label,g.id,sizes.label,sizes.id,locations.label,locations.id WITH ROLLUP 
            HAVING 
                grouping(size)=grouping(loc_id)
            ) as a 
        WHERE 
            isnull(a.Category)=isnull(a.cat_id) and isnull(a.Product)=isnull(a.prod_id) and isnull(a.Gender)=isnull(a.g_id) and isnull(size)=isnull(size_id) and isnull(location)=isnull(loc_id)
        ) as a1
    INNER JOIN
    (SELECT
    a.*,
    IF(isnull(a.location),IF(isnull(a.Gender),IF(isnull(a.prod_id),"Category","Product"),"Gender"),"") as labelname,
    IF(isnull(a.location),IF(isnull(a.Gender),IF(isnull(a.prod_id),a.Category,a.Product),a.Gender),"") as label,
    TRIM(trailing "-" from concat(IF(isnull(a.cat_id),"",a.cat_id),"-",if(isnull(a.prod_id),"",a.prod_id),"-",if(isnull(a.g_id),"",a.g_id),"-",if(isnull(a.size_id),"",a.size_id),"-",if(isnull(a.loc_id),"",a.loc_id))) as new_id
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
        sum(stock.items) as "N_items",
        grouping(pc.id)+grouping(p.group_id)+grouping(g.id)+grouping(locations.id) as level 
    FROM 
        product_categories as pc
    INNER JOIN
        (SELECT 
            prod_a.group_id as group_id, 
            prod_a.name as group_name,
            prod_b.id as id,prod_b.name as name,
            prod_b.category_id as category_id,
            prod_b.gender_id as gender_id
        FROM
            (SELECT 
                min(a.id) as group_id,
                upper(a.name) as name 
            FROM 
                products as a 
            INNER JOIN
                products as b ON upper(a.name)=upper(b.name) 
            WHERE 
                a.id != b.id and a.sizegroup_id = b.sizegroup_id and a.camp_id = 1 and b.camp_id = 1 and a.id<b.id 
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
    WHERE 
        locations.camp_id = 1  -- fixed for now
    GROUP BY 
        pc.label,pc.id,p.name,p.group_id,g.label,g.id,sizes.label,sizes.id,locations.label,locations.id WITH ROLLUP 
    HAVING 
        grouping(size)=grouping(loc_id)
    ) as a 
WHERE 
    isnull(a.Category)=isnull(a.cat_id) and isnull(a.Product)=isnull(a.prod_id) and isnull(a.Gender)=isnull(a.g_id) and isnull(size)=isnull(size_id) and isnull(location)=isnull(loc_id)
) as a2 ON (INSTR(a1.new_id,concat(a2.new_id,"-"))=1 OR a2.new_id = "") and a1.level+1 = a2.level and (a2.level != 5)
ORDER BY id;');
    $cmsmain->assign('data', $data);
    $cmsmain->assign('listconfig', $listconfig);
    $cmsmain->assign('listdata', $listdata);
    $cmsmain->assign('include', 'cms_list.tpl');
