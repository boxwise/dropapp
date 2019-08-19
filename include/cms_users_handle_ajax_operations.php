<?php 

if ($ajax) {
    switch ($_POST['do']) {
        case 'move':
            $ids = json_decode($_POST['ids']);
            list($success, $message, $redirect) = listMove($table, $ids);
            break;

        case 'delete':
            $ids = explode(',',$_POST['ids']);
            list($success, $message, $redirect) = listDelete($table, $ids);
            if($success) {
                foreach ($ids as $id) {
                    db_query('UPDATE cms_users SET email = CONCAT(email,".deleted.",id) WHERE id = :id', array('id'=>$id));
                }
            }
            break;

        case 'copy':
            $ids = explode(',',$_POST['ids']);
            list($success, $message, $redirect) = listCopy($table, $ids, 'code');
            break;

        case 'hide':
            $ids = explode(',',$_POST['ids']);
            list($success, $message, $redirect) = listShowHide($table, $ids, 0);
            break;

        case 'show':
            $ids = explode(',',$_POST['ids']);
            list($success, $message, $redirect) = listShowHide($table, $ids, 1);
            break;

        case 'sendlogindata':
            $ids = explode(',',$_POST['ids']);
            list($success, $message, $redirect) = sendlogindata($table, $ids);
            break;

        case 'loginasuser':
            $ids = explode(',',$_POST['ids']);
            list($success, $message, $redirect) = loginasuser($table,$ids);
            break;
    }

    $return = array("success" => $success, 'message'=> $message, 'redirect'=>$redirect);

    echo json_encode($return);
    die();
}