<?php

	function db_connect($host,$dbidr,$pass,$db) {
		global $defaultdbid;

		try {
			$defaultdbid = new PDO('mysql:host='.$host.';dbname='.$db, $dbidr, $pass);
			$defaultdbid->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$defaultdbid->setAttribute(PDO::ATTR_EMULATE_PREPARES,true);
		}
		catch(PDOException $e) {
			echo "db_connect() niet gelukt: ".$e->getMessage();
			die();
		}

		db_query("SET CHARACTER SET utf8");
		db_query("SET NAMES 'utf8'");

		return $defaultdbid;
	}

	function db_numrows($query, $array = array(), $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;
		try {
			$result = db_query($query, $array, $dbid);
			return $result->rowCount();
		}
		catch(PDOException $e) {
			trigger_error('db_numrows(): '.$e->getMessage());
			die();
		}
	}

	function db_row($query, $array = array(), $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		$result = db_query($query, $array, $dbid);
		$row = db_fetch($result);

		return $row;
	}

	function db_value($query, $array = array(), $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		$result = db_query($query, $array, $dbid);
		$row = db_fetch($result, PDO::FETCH_NUM);

		return $row[0];
	}

	function db_array($query, $array = array(), $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		$result = db_query($query, $array, $dbid);
		while($row = db_fetch($result)) $resultarray[] = $row;

		return $resultarray;
	}

	function db_simplearray($query, $array = array(), $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		$result = db_query($query, $array, $dbid);
		while($row = db_fetch($result, PDO::FETCH_NUM)) $resultarray[$row[0]] = $row[1];

		return $resultarray;
	}

	function db_fetch($result, $mode = PDO::FETCH_ASSOC) {
		try {
			if(!is_object($result)) trigger_error('db_fetch() verwacht een object');
			return $result->fetch($mode);
		}
		catch(PDOException $e) {
			trigger_error('db_fetch(): '.$e->getMessage());
			die();
		}
	}

	function db_query($query, $array = array(), $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		try {
			$ex = $dbid->prepare($query);
			$ex->execute($array);
			return $ex;
		}
		catch(PDOException $e) {
			trigger_error('db_query():<br />'.$query.'<br /><br />'.$e->getMessage());
			die();
		}
	}

	function db_insertid($dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		try {
			return $dbid->lastInsertId();
		}
		catch(PDOException $e) {
			trigger_error('db_insertid(): '.$e->getMessage());
			die();
		}
	}

	function db_prepare($name, $query, $dbid = false) {
		global $defaultdbid, $$name;
		if(!$dbid) $dbid = $defaultdbid;

		try {
			$$name = $dbid->prepare($query);
		}
		catch(PDOException $e) {
			trigger_error('db_prepare(): '.$e->getMessage());
			die();
		}
	}

	function db_use($name, $array) {
		global $$name;

		try {
			foreach($array as $key=>$value) {
				$$name->bindParam(':'.$key, $value, PDO::PARAM_INT);
			}
			$$name->execute();
			return db_fetch($$name);
		}
		catch(PDOException $e) {
			trigger_error('db_use(): '.$e->getMessage());
			die();
		}
	}

	function db_escape($string, $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;
		return $dbid->quote($string);
	}

	function db_listfields($table, $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		$result = db_query('DESCRIBE '.$table);
		while($field = db_fetch($result)) {
			$fields[] = $field['Field'];
		}

		return $fields;
	}

	function db_fieldexists($table, $field, $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		$fields = db_listfields($table, $dbid);
		return in_array($field, $fields);
	}

	function db_listtables($dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		$result = db_query('SHOW TABLES');
		while($field = db_fetch($result, PDO::FETCH_NUM)) {
			$fields[] = $field[0];
		}

		return $fields;
	}

	function db_tableexists($table, $dbid = false) {
		global $defaultdbid;
		if(!$dbid) $dbid = $defaultdbid;

		$tables = db_listtables($table, $dbid);
		return in_array($table, $tables);
	}
