<?php

// Generate random box id
function generateBoxID($length = 6, $possible = '0123456789')
{
    $randomString = '';
    $i = 0;
    while ($i < $length) {
        $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        if (!strstr($randomString, $char)) {
            $randomString .= $char;
            ++$i;
        }
    }

    return $randomString;
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
    $L1 = 0.2126 * pow($R1 / 255, 2.2) +
               0.7152 * pow($G1 / 255, 2.2) +
               0.0722 * pow($B1 / 255, 2.2);

    $L2 = 0.2126 * pow($R2BlackColor / 255, 2.2) +
              0.7152 * pow($G2BlackColor / 255, 2.2) +
              0.0722 * pow($B2BlackColor / 255, 2.2);

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
