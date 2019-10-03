<?php

    // TODO: Important!!! Need to review cross-org access to these photos
    // and people in general, as I don't believe there are any controls here yet
    $fileName = $settings['upload_dir'].'/people/'.$id.'.jpg';
    if (!file_exists($fileName)) {
        throw new Exception("Photo {$id} not found");
    }
    $fp = fopen($fileName, 'rb');
    header('Content-Type: image/jpg');
    fpassthru($fp);
