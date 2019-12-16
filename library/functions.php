<?php

// Generate random box id
function generateBoxID($length = 6, $possible = '0123456789')
{
    $password = '';
    $i = 0;
    while ($i < $length) {
        $char = substr($possible, mt_rand(0, strlen($possible) - 1), 1);
        if (!strstr($password, $char)) {
            $password .= $char;
            ++$i;
        }
    }

    return $password;
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

// return all organisations a Boxwise God user has access to
function organisationlist($short = false)
{
    if ($_SESSION['user']['is_admin']) {
        return  db_array('SELECT * FROM organisations 
            WHERE (NOT organisations.deleted OR organisations.deleted IS NULL) 
            ORDER BY label');
    } else {
        throw new Exception('A non Boxwise God tries to load a list of all organisations!');
    }
}

// return all camps a user has access to
function camplist($short = false)
{
    $parameters = [];
    $whereclause = '';
    if (!$_SESSION['user']['is_admin']) { // normal user (no Boxwise God)
        $parameters['organisation_id'] = $_SESSION['organisation']['id'];
        $parameters['usergroup_id'] = $_SESSION['usergroup']['id'];
        $whereclause = ' AND c.organisation_id = :organisation_id AND x.camp_id = c.id AND x.cms_usergroups_id = :usergroup_id';
    } elseif (isset($_SESSION['organisation']['id'])) { // Boxwise God and a organisation is specified
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
        trigger_error("You don't have access to this record");
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
        trigger_error("You don't have access to this record");
    }
}

function verify_deletedrecord($table, $id)
{
    if (db_value('SELECT IF(NOT deleted OR deleted IS NULL,0,1) FROM '.$table.' WHERE id = :id', ['id' => $id])) {
        trigger_error('This record does not exist');
    }
}
