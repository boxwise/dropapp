<?php

require_once 'library/lib/tools.php';

if ('' != $_GET['boxid']) {
    redirect($settings['v2_base_url'].'/boxes/'.$_GET['boxid']);
} elseif ('' != $_GET['newbox']) {
    redirect($settings['v2_base_url'].'/boxes/create/'.$_GET['newbox']);
} elseif ('' != $_GET['barcode']) {
    redirect($settings['v2_base_url'].'/qrreader/'.$_GET['barcode']);
} else {
    redirect($settings['v2_base_url'].'/qrreader');
}
