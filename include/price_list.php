<?php

    $cmsmain->assign('include', 'price_list.tpl');

    $containers = db_array('SELECT people.container, count(*) AS count FROM people WHERE NOT deleted GROUP BY container ORDER BY container');
    $cmsmain->assign('containers', $containers);

    // place the form elements and data in the template
    $cmsmain->assign('data', $data);
    $cmsmain->assign('formelements', $formdata);
    $cmsmain->assign('formbuttons', $formbuttons);
