<?php

$login = login($_POST['email'], $_POST['pass'], isset($_POST['autologin']), $mobile = true);

if ($login['success']) {
    // WARNING, this is an open redirect (security issue)
    redirect($_POST['destination']);
} else {
    trigger_error($login['message']);
    redirect('?warning=true&message='.$login['message']);
}
