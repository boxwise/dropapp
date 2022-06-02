<?php

// Show warning message if tag already applied to object
// related trello https://trello.com/c/XjNwO3sL
$id = $_POST['id'];
$selectedType = $_POST['type'];
$success = true;

if ($id && $selectedType) {
    $result = db_row("SELECT 
                        tags.type AS origin_type,
                        SUM(IF(tags_relations.object_type = 'Stock',
                            1,
                            0)) AS boxes_count,
                        SUM(IF(tags_relations.object_type = 'People',
                            1,
                            0)) AS beneficiaries_count
                    FROM
                        tags
                    LEFT JOIN
                        tags_relations ON tags_relations.tag_id = tags.id
                    WHERE
                        tags.deleted IS NULL
                            AND tags.camp_id = :campId
                            AND tags.id = :tagId
                    GROUP BY tags.id", ['campId' => $_SESSION['camp']['id'], 'tagId' => $id]);

    $originType = $result['origin_type'];
    $boxesCount = intval($result['boxes_count']);
    $beneficiariesCount = intval($result['beneficiaries_count']);

    define('BOX_WARNING', 'WARNING: Changing the tag type will remove '.$boxesCount.' existing tags from boxes.');
    define('PEOPLE_WARNING', 'WARNING: Changing the tag type will remove '.$beneficiariesCount.' existing tags that have been applied to beneficiaries.');

    if ('All' === $originType) {
        if ('Stock' === $selectedType && $beneficiariesCount > 0) {
            $message = PEOPLE_WARNING;
            $success = false;
        } elseif ('People' === $selectedType && $boxesCount > 0) {
            $message = BOX_WARNING;
            $success = false;
        }
    } elseif ('Stock' === $originType) {
        if ('People' === $selectedType && $boxesCount > 0) {
            $message = BOX_WARNING;
            $success = false;
        }
    } elseif ('People' === $originType) {
        if ('Stock' === $selectedType && $beneficiariesCount > 0) {
            $message = PEOPLE_WARNING;
            $success = false;
        }
    }

    $redirect = false;
}

$return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

echo json_encode($return);
