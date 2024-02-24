<?php

if ($_GET['return']) {
    db_query('INSERT INTO library_transactions (transaction_date, book_id, people_id, status) VALUES (NOW(), :id, :people_id, "in")', ['id' => $_GET['return'], 'people_id' => $_GET['user']]);
    redirect('?action=library');
}

if ($_POST) {
    verify_campaccess_people($_POST['people_id'][0]);

    $table = 'library_transactions';
    $keys = ['transaction_date', 'book_id', 'people_id', 'status', 'comment'];

    $handler = new formHandler($table);
    $handler->savePost($keys);

    redirect('?action='.$_POST['_origin']);
}

// open the template

$data = db_row('
        SELECT 
            b.*, 
            TIME_TO_SEC(TIMEDIFF(NOW(),transaction_date))>610000 AS toolate, 
            TIME_TO_SEC(TIMEDIFF(NOW(),transaction_date)) AS duration, 
            bt.transaction_date,
            bt.people_id, 
            bt.status, 
            bt.comment AS btcomment, 
            CONCAT(firstname," ",lastname," (",container,")") AS user 
        FROM 
            library AS b 
        LEFT OUTER JOIN 
            library_transactions AS bt ON bt.book_id = b.id 
        LEFT OUTER JOIN 
            people AS p ON bt.people_id = p.id 
        WHERE 
            b.id = :id 
        ORDER BY 
            transaction_date DESC ', ['id' => $id]);

verify_campaccess_people($data['people_id']);
verify_deletedrecord('people', $data['people_id']);

if ('out' == $data['status']) {
    $data['duration'] = ceil($data['duration'] / 86400).' days';
    $cmsmain->assign('title', $data['user'].' is returning '.$data['code'].($data['booktitle_en'] ? ' - '.$data['booktitle_en'] : ''));
    $cmsmain->assign('data', $data);
    $cmsmain->assign('include', 'library_return.tpl');
} else {
    $data['status'] = 'out';
    $data['transaction_date'] = strftime('%Y-%m-%d %H:%M:%S');

    $translate['cms_form_submit'] = 'Start borrowing';
    $cmsmain->assign('translate', $translate);

    $cmsmain->assign('title', 'Borrow out a book');

    addfield('select', 'Find book', 'book_id', ['required' => true, 'multiple' => false, 'query' => 'SELECT id AS value, CONCAT(code," - ",booktitle_en,IF(booktitle_ar!="",CONCAT(" - ",booktitle_ar),""),IF(author!="",CONCAT(" (",author,")"),"")) AS label FROM library WHERE visible AND camp_id = 
		'.intval($_SESSION['camp']['id'])]);

    $people = db_array('SELECT p.id AS value, CONCAT(p.firstname, " ", p.lastname, " (", p.container, ")") AS label FROM people AS p WHERE NOT p.deleted AND camp_id = '.$_SESSION['camp']['id'].' GROUP BY p.id');

    addfield('select', 'Find person', 'people_id', ['required' => true, 'multiple' => false, 'options' => $people]);
    addfield('text', 'Name of person', 'comment');

    addfield('hidden', '', 'status');
    addfield('hidden', '', 'transaction_date');

    addfield('line', '', '');

    $cmsmain->assign('include', 'cms_form.tpl');
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
}
