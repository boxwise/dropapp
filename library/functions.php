<?php

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
use Endroid\QrCode\Writer\PngWriter;
use OpenCensus\Trace\Tracer;

// Generate QR-png
function generateQrPng($hash, $legacy = false)
{
    Tracer::inSpan(
        ['name' => 'QR png generation'],
        function () use ($hash, $legacy, &$return) {
            try {
                // related to this trello https://trello.com/c/5H7ByALh
                $writer = new PngWriter();

                // Create QR code
                $qrCode = QrCode::create('https://'.$_SERVER['HTTP_HOST'].'/mobile.php?barcode='.$hash.($legacy ? '&qrlegacy=1' : ''))
                    ->setEncoding(new Encoding('UTF-8'))
                    ->setErrorCorrectionLevel(new ErrorCorrectionLevelLow())
                    ->setSize(150)
                    ->setMargin(0)
                    ->setRoundBlockSizeMode(new RoundBlockSizeModeMargin())
                    ->setForegroundColor(new Color(0, 0, 0))
                    ->setBackgroundColor(new Color(255, 255, 255))
                    ;

                $result = $writer->write($qrCode);

                $testUrl = '/mobile.php?'.explode('/mobile.php?', $qrCode->getData())[1];

                $return = [$result->getDataUri(), $testUrl];
            } catch (Exception) {
                trigger_error('QR-code png generation error.');

                $return = ['QR-CODE ERROR', 'QR-CODE ERROR'];
            }
        }
    );

    return $return;
}

// Generate random box id
function generateBoxID($length = 8, $possible = '0123456789')
{
    $randomString = '';
    $i = 0;
    while ($i < $length) {
        $possible = (0 === $i) ? substr($possible, 1, strlen($possible) - 1) : $possible;

        $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        if (!strstr($randomString, $char)) {
            $randomString .= $char;
            ++$i;
        }
    }

    return $randomString;
}

function generateQRIDForDB()
{
    $id = db_value('SELECT id FROM qr ORDER BY id DESC LIMIT 1') + 1;
    $qr_id_stock = db_value('SELECT qr_id FROM stock ORDER BY qr_id DESC LIMIT 1');
    if ($qr_id_stock >= $id) {
        trigger_error('There are QR IDs in the stock table bigger than the largest id in the qr-table.');
        $id = $qr_id_stock + 1;
    }
    $hash = md5($id);
    db_query('INSERT INTO qr (id, code, created) VALUES ('.$id.',"'.$hash.'",NOW())');

    //test if generated qr-code is already connected to a box
    if (db_value('SELECT id FROM stock WHERE qr_id = :id', ['id' => $id])) {
        throw new Exception('QR-Generation error! Please report to the Boxtribute team!');
    }

    return [$id, $hash];
}

function generateSecureRandomString(
    int $length = 64,
    string $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
): string {
    if ($length < 1) {
        throw new \RangeException('Length must be a positive integer');
    }
    $pieces = [];
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $pieces[] = $keyspace[random_int(0, $max)];
    }

    return implode('', $pieces);
}

function shouldIdentifyUserToAnalytics()
{
    if (!isset($_SESSION['hasIdentifiedUserSessionToAnalytics'])) {
        $_SESSION['hasIdentifiedUserSessionToAnalytics'] = true;

        return true;
    }

    return false;
}

// returns true if the current user is allowed to give drops (he is allowed when his usergroup has access to Give Drops to all function)
function allowgivedrops()
{
    return $_SESSION['user']['is_admin'] || db_value('SELECT id FROM cms_functions AS f, cms_usergroups_functions AS uf WHERE uf.cms_functions_id = f.id AND f.include = "give2all" AND uf.cms_usergroups_id = :usergroup', ['usergroup' => $_SESSION['usergroup']['id']]);
}

// return all organisations a Boxtribute God user has access to
function organisationlist($short = false)
{
    if ($_SESSION['user']['is_admin']) {
        return db_array('SELECT * FROM organisations 
            WHERE (NOT organisations.deleted OR organisations.deleted IS NULL) 
            ORDER BY label', [], false, true);
    }

    throw new Exception('A non Boxtribute God tries to load a list of all organisations!');
}

// return all camps a user has access to
function camplist($short = false)
{
    $parameters = [];
    $whereclause = '';
    if (!$_SESSION['user']['is_admin']) { // normal user (no Boxtribute God)
        $parameters['organisation_id'] = $_SESSION['organisation']['id'];
        $parameters['usergroup_id'] = $_SESSION['usergroup']['id'];
        $whereclause = ' AND c.organisation_id = :organisation_id AND x.camp_id = c.id AND x.cms_usergroups_id = :usergroup_id';
    } elseif (isset($_SESSION['organisation']['id'])) { // Boxtribute God and a organisation is specified
        $parameters['organisation_id'] = $_SESSION['organisation']['id'];
        $whereclause = ' AND c.organisation_id = :organisation_id';
    }
    $camplist = db_array('
		SELECT c.* 
		FROM camps AS c'.($_SESSION['user']['is_admin'] ? '' : ', cms_usergroups_camps AS x').'
        WHERE (NOT c.deleted OR c.deleted IS NULL)'.$whereclause.'
		ORDER BY c.seq', $parameters, false, true);
    if ($short) {
        foreach ($camplist as $c) {
            $list[] = $c['id'];
        }

        return $list;
    }

    return $camplist;
}

function getcampdata($id)
{
    return db_row('
		SELECT c.* 
		FROM camps AS c'.($_SESSION['user']['is_admin'] ? '' : ', cms_usergroups_camps AS x').'
		WHERE c.id = :camp AND (NOT c.deleted OR c.deleted IS NULL) AND c.organisation_id = :organisation_id'.($_SESSION['user']['is_admin'] ? '' : ' AND x.camp_id = c.id AND x.cms_usergroups_id = '.$_SESSION['usergroup']['id']).'
		ORDER BY c.seq', ['camp' => $id, 'organisation_id' => $_SESSION['organisation']['id']]);
}

//this function verifies if a people id belongs to a camp that the current user has access to.
function verify_campaccess_people($id)
{
    if (!$id) {
        return;
    }
    $camp_id = db_value('SELECT camp_id FROM people WHERE id = :id', ['id' => $id]);
    $camps = camplist(true);
    if (!in_array($camp_id, $camps)) {
        throw new Exception("You don't have access to this record");
    }
}

//this function verifies if a location id belongs to a camp that the current user has access to.
function verify_campaccess_location($id)
{
    if (!$id) {
        return;
    }
    $camp_id = db_value('SELECT camp_id FROM locations WHERE id = :id', ['id' => $id]);
    $camps = camplist(true);
    if (!in_array($camp_id, $camps)) {
        throw new Exception("You don't have access to this record");
    }
}

function verify_deletedrecord($table, $id)
{
    if (db_value('SELECT IF(NOT deleted OR deleted IS NULL,0,1) FROM '.$table.' WHERE id = :id', ['id' => $id])) {
        throw new Exception('This record does not exist');
    }
}

// get text color based on background color
function get_text_color($hexColor)
{
    if (!$hexColor) {
        return '#FFFFFF';
    }

    // hexColor RGB
    $R1 = hexdec(substr($hexColor, 1, 2));
    $G1 = hexdec(substr($hexColor, 3, 2));
    $B1 = hexdec(substr($hexColor, 5, 2));

    // Black RGB
    $blackColor = '#000000';
    $R2BlackColor = hexdec(substr($blackColor, 1, 2));
    $G2BlackColor = hexdec(substr($blackColor, 3, 2));
    $B2BlackColor = hexdec(substr($blackColor, 5, 2));

    // Calc contrast ratio
    $L1 = 0.2126 * ($R1 / 255) ** 2.2 +
               0.7152 * ($G1 / 255) ** 2.2 +
               0.0722 * ($B1 / 255) ** 2.2;

    $L2 = 0.2126 * ($R2BlackColor / 255) ** 2.2 +
              0.7152 * ($G2BlackColor / 255) ** 2.2 +
              0.0722 * ($B2BlackColor / 255) ** 2.2;

    $contrastRatio = 0;
    if ($L1 > $L2) {
        $contrastRatio = (int) (($L1 + 0.05) / ($L2 + 0.05));
    } else {
        $contrastRatio = (int) (($L2 + 0.05) / ($L1 + 0.05));
    }

    // If contrast is more than 5, return black color
    if ($contrastRatio > 5) {
        return '#000000';
    }
    // if not, return white color.
    return '#FFFFFF';
}

function mobile_distro_check($locationType, $mobile = true)
{
    if ('Warehouse' !== $locationType) {
        $userMessage = 'You cannot access this box. Please report this to your coordinator.';
        $sentryMessage = 'The user tries to edit a box belonging to a distribution event through dropapp.';
        if ($mobile) {
            trigger_error($sentryMessage, E_USER_ERROR);
            redirect('?warning=1&message='.$userMessage);
        } else {
            throw new Exception($userMessage, 403);
        }
    }
}

function v2_forward($base_url, $route)
{
    if ($_SESSION['v2_forward']) {
        redirect($base_url.'/bases/'.$_SESSION['camp']['id'].$route);
    } else {
        // I add a link to the same REQUEST_URI just with a changed view preference so that the SESSION is changed, too.
        $url = str_replace(['?&', '&&'], ['?', '&'], str_replace('preference=classic', '', $_SERVER['REQUEST_URI']).'&preference=v2');

        return '<div data-testid="v2-link">Try out the new Boxtribute.</div><a href="'.$url.'" data-testid="v2-link-url">Switch here to the NEW Version!</a>';
    }
}

function move_boxes($ids, $newlocationid, $mobile = false)
{
    [$count, $action_label, $mobile_message] = db_transaction(function () use ($ids, $newlocationid) {
        $count = 0;
        foreach ($ids as $id) {
            $box = db_row('
                SELECT 
                    stock.*, 
                    bs.id as box_state_id, 
                    bs.label as box_state_name,
                    l.type as location_type
                FROM stock 
                INNER JOIN box_state bs ON bs.id = stock.box_state_id
                LEFT JOIN locations l ON stock.location_id=l.id
                WHERE stock.id = :id', ['id' => $id]);

            mobile_distro_check($box['location_type']);

            $mobile_message = 'Box '.$box['box_id'].' contains '.$box['items'].'x '.$box['product'];

            // Getting the new box state id based on the location
            $newlocation = db_row('
                SELECT 
                    l.label,
                    bs.id as box_state_id, 
                    bs.label as box_state_name 
                FROM locations l 
                INNER JOIN box_state bs ON bs.id = l.box_state_id 
                WHERE l.id = :id', ['id' => $newlocationid]);

            $action_label = ' moved';

            // Boxes should not be relocated to virtual locations
            // related to https://trello.com/c/Ci74t1Wj
            if ('Lost' == $newlocation['box_state_name']) {
                $action_label = ' state changed to Lost';
            } elseif ('Scrap' == $newlocation['box_state_name']) {
                $action_label = ' state changed to Scrap';
            } elseif ($box['location_id'] != $newlocationid) {
                db_query(
                    '
                    UPDATE stock 
                    SET 
                        modified = NOW(), 
                        modified_by = :user_id , 
                        location_id = :location 
                    WHERE id = :id',
                    ['location' => $newlocationid, 'id' => $id, 'user_id' => $_SESSION['user']['id']]
                );

                $from['int'] = $box['location_id'];
                $to['int'] = $newlocationid;
                simpleSaveChangeHistory('stock', $id, 'location_id', $from, $to);
                db_query(
                    '
                    INSERT INTO itemsout (product_id, size_id, count, movedate, from_location, to_location) 
                        VALUES (:product_id, :size_id, :count, NOW(), :from_location, :to_location)',
                    ['product_id' => $box['product_id'],
                        'size_id' => $box['size_id'],
                        'count' => $box['items'],
                        'from_location' => $box['location_id'],
                        'to_location' => $newlocationid, ]
                );
                $mobile_message .= ' is moved to '.$newlocation['label'];
            }

            // Update the box state if the state changes
            if ($newlocation['box_state_id'] != $box['box_state_id']) {
                $from['int'] = $box['box_state_id'];
                $to['int'] = $newlocation['box_state_id'];
                db_query(
                    '
                    UPDATE stock 
                    SET 
                        box_state_id = :box_state_id, 
                        modified = NOW(), 
                        modified_by = :user_id 
                    WHERE id = :id',
                    ['box_state_id' => $newlocation['box_state_id'],  'id' => $id, 'user_id' => $_SESSION['user']['id']]
                );
                simpleSaveChangeHistory('stock', $id, 'box_state_id', $from, $to);
                $mobile_message .= ' and its state changed to '.$newlocation['box_state_name'];
            }

            ++$count;
        }

        return [$count, $action_label, $mobile_message];
    });

    $message = (1 == $count ? '1 box is' : $count.' boxes are').$action_label;

    return [$count, $message, $mobile_message];
}
