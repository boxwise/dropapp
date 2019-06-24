<?php

function db_connect($dsn, $username, $password)
{
	global $defaultdbid;

	$defaultdbid = new PDO($dsn, $username, $password);
	$defaultdbid->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$defaultdbid->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);

	db_query("SET CHARACTER SET utf8");
	db_query("SET NAMES 'utf8'");
	db_query("SET SESSION sql_mode = 'NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

	return $defaultdbid;
}

function db_numrows($query, $array = array(), $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;
	$result = db_query($query, $array, $dbid);
	return $result->rowCount();
}

function db_row($query, $array = array(), $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	$result = db_query($query, $array, $dbid);
	$row = db_fetch($result);

	return $row;
}

function db_value($query, $array = array(), $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	$result = db_query($query, $array, $dbid);
	$row = db_fetch($result, PDO::FETCH_NUM);

	return $row[0];
}

function db_array($query, $array = array(), $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	$result = db_query($query, $array, $dbid);
	while ($row = db_fetch($result)) $resultarray[] = $row;

	return $resultarray;
}

function db_simplearray($query, $array = array(), $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	$result = db_query($query, $array, $dbid);
	while ($row = db_fetch($result, PDO::FETCH_NUM)) $resultarray[$row[0]] = $row[1];

	return $resultarray;
}

function db_fetch($result, $mode = PDO::FETCH_ASSOC)
{
	if (!is_object($result))
		throw new Exception('db_fetch() expects an object');
	return $result->fetch($mode);
}

function db_query($query, $array = array(), $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	try {
		$ex = $dbid->prepare($query);
		$ex->execute($array);
		return $ex;
	} catch (PDOException $e) {
		throw new Exception('db_query() failed: ' . $query . '<br>' . $e->getMessage(), $e->getCode(), $e);
	}
}

function db_insertid($dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	return $dbid->lastInsertId();
}

function db_prepare($name, $query, $dbid = false)
{
	global $defaultdbid, $$name;
	if (!$dbid) $dbid = $defaultdbid;

	$$name = $dbid->prepare($query);
}

function db_use($name, $array)
{
	global $$name;

	foreach ($array as $key => $value) {
		$$name->bindParam(':' . $key, $value, PDO::PARAM_INT);
	}
	$$name->execute();
	return db_fetch($$name);
}

function db_escape($string, $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;
	return $dbid->quote($string);
}

function db_listfields($table, $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	$result = db_query('DESCRIBE ' . $table);
	while ($field = db_fetch($result)) {
		$fields[] = $field['Field'];
	}

	return $fields;
}

function db_defaults($table, $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;
	$r = db_query('SHOW FULL COLUMNS FROM ' . $table);
	while ($row = db_fetch($r)) {
		$result[$row['Field']] = $row['Default'];
	}
	return $result;
}
function db_fieldexists($table, $field, $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	$fields = db_listfields($table, $dbid);
	return in_array($field, $fields);
}

function db_listtables($dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	$result = db_query('SHOW TABLES');
	while ($field = db_fetch($result, PDO::FETCH_NUM)) {
		$fields[] = $field[0];
	}

	return $fields;
}

function db_tableexists($table, $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;

	$tables = db_listtables($table, $dbid);
	return in_array($table, $tables);
}

function db_touch($table, $id)
{
	if (db_fieldexists($table, 'modified')) {
		db_query('UPDATE ' . $table . ' SET modified = NOW(), modified_by = :user WHERE id = :id', array('user' => $_SESSION['user']['id'], 'id' => $id));
	}
}

function db_simulate($query, $array = array(), $dbid = false)
{
	global $defaultdbid;
	if (!$dbid) $dbid = $defaultdbid;
	$dbid->beginTransaction();

	try {
		$ex = $dbid->exec($query);
		$dbid->rollBack();
		return $ex;
	} catch (PDOException $e) {
		$dbid->rollBack();
		throw new Exception('db_simulate() failed: ' . $query . '<br>' . $e->getMessage(), $e->getCode(), $e);
	}
}
