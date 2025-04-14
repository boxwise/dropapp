<?php

$result = db_query('
    SELECT 
        s.label,
        sr.created as used_on,
        u.naam as registered_by,
        p.container,
        p.firstname,
        p.lastname,
        p.gender,
        DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), p.date_of_birth)), "%Y")+0 AS age,
        (
            SELECT GROUP_CONCAT(t.label) 
            FROM tags t 
            INNER JOIN tags_relations tr ON t.id = tr.tag_id 
            WHERE tr.object_type = "People" AND tr.object_id = p.id AND tr.deleted_on IS NULL
        ) AS tags,
        p.comments,
        IF(ISNULL(p.parent_id), p.id, p.parent_id) AS boxtribute_family_id, 
        IF(ISNULL(p.parent_id), TRUE, FALSE) AS familyhead
    FROM 
        services_relations sr
    LEFT JOIN
        services s ON sr.service_id = s.id
    LEFT JOIN 
        people p ON sr.people_id = p.id AND (NOT p.deleted OR p.deleted IS NULL)
    LEFT JOIN
        cms_users u ON sr.created_by = u.id
    WHERE sr.service_id = :id AND s.camp_id = :camp_id
    ORDER BY sr.created DESC', ['camp_id'=>$_SESSION['camp']['id'],'id' => $id]);

$keys = [
    'label' => 'Service',
    'used_on' => 'Used on',
    'registered_by' => 'Registered by',
    'container' => $_SESSION['camp']['familyidentifier'],
    'firstname' => 'Firstname',
    'lastname' => 'Surname',
    'gender' => 'Gender',
    'age' => 'Age',
    'tags' => 'Tags',
    'comments' => 'Comments',
    'boxtribute_family_id' => 'Boxtribute Family ID',
    'familyhead' => 'Head of Family',
];

csvexport($result, 'Service_'.$id, $keys);
