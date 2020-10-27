<?php

    $table = 'library_transactions';
    $ajax = checkajax();

    if (!$ajax) {
        initlist();

        $cmsmain->assign('title', 'Library');

        $data = getlistdata('
		SELECT
			l.id,
			CONCAT(code," - ",booktitle_en,IF(booktitle_ar!="",CONCAT(" - ",booktitle_ar),""),IF(author!="",CONCAT(" (",author,")"),"")) AS title, 
			(SELECT IF(lt.people_id = -1,lt.comment,CONCAT(firstname," ",lastname," (",container,")")) FROM library_transactions AS lt LEFT OUTER JOIN people AS p ON lt.people_id = p.id WHERE lt.book_id = l.id ORDER BY lt.transaction_date DESC LIMIT 1) AS name,
			(SELECT TIME_TO_SEC(TIMEDIFF(NOW(),transaction_date)) FROM library_transactions AS lt WHERE lt.book_id = l.id ORDER BY lt.transaction_date DESC LIMIT 1) AS duration
		FROM library AS l WHERE 
			camp_id = '.intval($_SESSION['camp']['id']).' AND
			(SELECT status FROM library_transactions AS lt WHERE lt.book_id = l.id ORDER BY lt.transaction_date DESC LIMIT 1) = "out"');

        foreach ($data as $key => $d) {
            $data[$key]['duration'] = ceil($data[$key]['duration'] / 86400).' days ';
        }
        addcolumn('text', 'Book', 'title');
        addcolumn('html', 'Rented out to', 'name');
        addcolumn('html', 'Duration', 'duration');

        listsetting('allowsort', true);
        listsetting('allowdelete', false);
        listsetting('allowshowhide', false);
        listsetting('allowadd', true);
        listsetting('allowselect', false);
        listsetting('allowselectall', false);

        listsetting('add', 'Borrow out a new book');

        $cmsmain->assign('data', $data);
        $cmsmain->assign('listconfig', $listconfig);
        $cmsmain->assign('listdata', $listdata);
        $cmsmain->assign('include', 'cms_list.tpl');
    } else {
        switch ($_POST['do']) {
            case 'move':
                $ids = json_decode($_POST['ids']);
                list($success, $message, $redirect) = listMove($table, $ids);

                break;
            case 'delete':
                $ids = explode(',', $_POST['ids']);
                foreach ($ids as $id) {
                    if ($id) {
                        db_query('DELETE FROM library_transactions WHERE id = :id', ['id' => $id]);
                    }
                }
                $message = 'Transactions cancelled';
                $success = true;
                $redirect = true;

                break;
            case 'copy':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listCopy($table, $ids, 'menutitle');

                break;
            case 'hide':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 0);
                $message = $_POST['ids'];

                break;
            case 'show':
                $ids = explode(',', $_POST['ids']);
                list($success, $message, $redirect) = listShowHide($table, $ids, 1);

                break;
        }

        $return = ['success' => $success, 'message' => $message, 'redirect' => $redirect];

        echo json_encode($return);
        die();
    }
