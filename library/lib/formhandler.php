<?php


class formHandler {

    public function __construct($table) {
		$this->post = $_POST;
		$this->id = intval($_POST['id']);
		$this->table = $table;
	}

	function savePost($keys) {

		$this->keys = $keys;
		$this->saveCreatedModified();

		$this->modifyData($this->keys);
		$this->buildQuery();

		return $this->id;

	}

	function modifyData(&$keys, $lan = false) {

		foreach ($keys as $key) {

			if($lan) {
				$value = $this->post[$lan][$key];
				$properties = explode(' ',$this->post['__'.$lan][$key]);
			} else {
				$value = $this->post[$key];
				$properties = explode(' ',$this->post['__'.$key]);
			}
			foreach($properties as $p) {
				switch($p) {
					case 'checkbox':
						$value = intval($value);
						break;
					case 'valuta':
						$value = str_replace('€','',$value);
						$value = str_replace('.','',$value); // remove thousands indicator
						$value = str_replace(',','.',$value); // change decimal into point
						break;
					case 'float':
						$value = str_replace('.','',$value); // remove thousands indicator
						$value = str_replace(',','.',$value); // change decimal into point
						break;
					case 'select':
						if(!in_array('multiple',$properties)) $value = $value[0];
						break;
					case 'date':
						if($value) $value = strftime('%Y-%m-%d %H:%M:%S',strtotime($value));
						break;
					case 'readonly':
						$keys = array_diff($keys, array($key));
						break;
					case 'fileselect':
						if(in_array('image',$properties) && $value) {
							$resizeproperties = ($lan ? $this->post['__'.$lan][$key.'resize'] : $this->post['__'.$key.'resize']);
							$aResize = unserialize(str_replace("'",'"',stripslashes($resizeproperties)));
							$imageResize = new imageResize();

							foreach($aResize as $aResizeItem) {
								$aResizeItem['source'] = $_SERVER['DOCUMENT_ROOT'].$value;
								$aResizeItem['reltarget'] =  $aResizeItem['target'].basename($value);
								$aResizeItem['target'] = $_SERVER['DOCUMENT_ROOT'].$aResizeItem['target'].basename($value);

								//If the file already exists, append the target filename with -1
								//This should be in a function that adds -1 as long as the filename exist. (Now only works once)
/*
								if (file_exists($aResizeItem['target'])) {
									$aPathinfo = pathinfo($aResizeItem['target']);
									$aResizeItem['target'] = $aPathinfo['dirname'].'/'.$aPathinfo['filename'].'-1.'.$aPathinfo['extension'];
									$value = $aResize[0]['target'].$aPathinfo['filename'].'-1.'.$aPathinfo['extension'];
								}
*/

								if (basename($aResizeItem['source']) != basename($this->post['file_prev'])) {
									$imageResize->setValue('source',$aResizeItem['source']);
									$imageResize->setValue('target',$aResizeItem['target']);
									$imageResize->setValue('resize',$aResizeItem['resize']);
									$imageResize->setValue('scaleup',$aResizeItem['scaleup']);
									$imageResize->setValue('quality',$aResizeItem['quality']);
									$imageResize->imageResize();
								}
							}
							break;
						}
				}
			}

			$value = stripslashes($value);

			if($lan) {
				$this->post[$lan][$key] = $value;
			} else {
				$this->post[$key] = $value;
			}

		}
	}

	function buildQuery() {

		$updatequery = "UPDATE ".$this->table." SET ";
		$insertquery = 'INSERT INTO '.$this->table;

		$hasTree = db_fieldexists($this->table, 'parent_id');
		$hasSeq = db_fieldexists($this->table, 'seq');

		if($hasSeq) {
			array_push($this->keys,'seq');
			if(!$this->post['seq']) {
				$this->post['seq'] = intval(db_value('SELECT MAX(seq)+1 FROM '.$this->table.($hasTree?' WHERE parent_id = '.intval($this->post['parent_id']):'')));
			}
		}

		foreach ($this->keys as $key) {

			$updatequeryfields[] = $key.' = :'.$key;
			$insertqueryfields[] = $key;
			$insertqueryfields2[] = ':'.$key;
			$queryvalues[$key] = $this->post[$key];


		}

		$updatequery .= join(', ',$updatequeryfields)." WHERE id = ".$this->id;
		$insertquery .= ' ('.join(', ',$insertqueryfields).') VALUES ('.join(', ',$insertqueryfields2).')';

		if ($this->id > 0) {
			$this->saveChangeHistory();
			if($this->debug) {
				dump($updatequery);
				dump(join(',',$queryvalues));
			} else {
				db_query($updatequery,$queryvalues);
			}
		} else {
			if($this->debug) {
				dump($insertquery);
				dump(join(',',$queryvalues));
			} else {
				db_query($insertquery,$queryvalues);
				$this->id = db_insertid();
				$this->saveNewrecordInHistory();
			}
		}
	}

	function saveNewrecordInHistory() {
		if(!db_tableexists('history')) return;

		db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate) VALUES (:table,:id,:change,:user_id,:ip,NOW())', array('table'=>$this->table,'id'=>$this->id,'change'=>'Record created','user_id'=>$_SESSION['user']['id'],'ip'=>$_SERVER['REMOTE_ADDR']));
		
	}

	function saveChangeHistory() {
		// if no history table is present, ignore this function
		if(!db_tableexists('history')) return;

		$result = db_query('SHOW COLUMNS FROM '.$this->table);
		while($field = db_fetch($result)) $fields[$field['Field']] = $field;

		$old = db_row('SELECT * FROM '.$this->table.' WHERE id = :id',array('id'=>$this->id));
		foreach ($this->keys as $key) {
			if(!in_array($key, array('created','created_by','modified','modified_by'))) {
				$new = $this->post[$key];
				$change = '';

				if($fields[$key]['Type']=='date') {
					$new = strftime('%Y-%m-%d',strtotime($new));
					if($new = '1970-01-01') $new = $old[$key];
				}
				if($fields[$key]['Type']=='float' && $fields[$key]['Null']=="NO") $new = floatval($new);
				if(substr($fields[$key]['Type'],0,3)=='int' && $fields[$key]['Null']=="NO") $new = intval($new);

				if($old[$key]!=$new) {
					if(substr($fields[$key]['Type'],0,3)=='int' || substr($fields[$key]['Type'],0,7)=='tinyint') {
						db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate, from_int, to_int) VALUES (:table,:id,:change,:user_id,:ip,NOW(), :old, :new)', array('table'=>$this->table,'id'=>$this->id,'change'=>$key,'user_id'=>$_SESSION['user']['id'],'ip'=>$_SERVER['REMOTE_ADDR'], 'old'=>$old[$key], 'new'=>$new));
					} else if($fields[$key]['Type']=='float') {
						db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate, from_float, to_float) VALUES (:table,:id,:change,:user_id,:ip,NOW(), :old, :new)', array('table'=>$this->table,'id'=>$this->id,'change'=>$key,'user_id'=>$_SESSION['user']['id'],'ip'=>$_SERVER['REMOTE_ADDR'], 'old'=>$old[$key], 'new'=>$new));
					} else {
						$change .= $key.' changed from "'.$old[$key].'" to "'.$new.'"'."; ";
						db_query('INSERT INTO history (tablename, record_id, changes, user_id, ip, changedate) VALUES (:table,:id,:change,:user_id,:ip,NOW())', array('table'=>$this->table,'id'=>$this->id,'change'=>$change,'user_id'=>$_SESSION['user']['id'],'ip'=>$_SERVER['REMOTE_ADDR']));
					}

				}
			}
		}
	}

	function saveMultilanguage($keys) {
		global $settings;

		array_unshift($keys,'table_id');
		array_unshift($keys,'lan');

		foreach($settings['languages'] as $language) {
			$this->post[$language['code']]['lan'] = db_value('SELECT id FROM languages WHERE code = :code',array('code'=>$language['code']));
			$this->modifyData($keys, $language['code']);

			$updatequery = "UPDATE ".$this->table."_content SET ";
			$insertquery = 'INSERT INTO '.$this->table."_content";

			$this->post[$language['code']]['table_id'] = $this->id;

			$subid = intval($this->post[$language['code']]['id']);

			unset($updatequeryfields);
			unset($insertqueryfields);
			unset($insertqueryfields2);
			unset($queryvalues);

			foreach ($keys as $key) {

				$updatequeryfields[] = $key.' = :'.$key;
				$insertqueryfields[] = $key;
				$insertqueryfields2[] = ':'.$key;
				$queryvalues[$key] = $this->post[$language['code']][$key];

			}

			$updatequery .= join(', ',$updatequeryfields)." WHERE id = ".$subid;
			$insertquery .= ' ('.join(', ',$insertqueryfields).') VALUES ('.join(', ',$insertqueryfields2).')';

			if ($subid > 0) {
				if($this->debug) {
					dump(join(',',$queryvalues));
					dump($updatequery);
				} else {
					db_query($updatequery,$queryvalues);
				}
			} else {
				if($this->debug) {
					dump(join(',',$queryvalues));
					dump($insertquery);
				} else {
					db_query($insertquery,$queryvalues);
				}
			}

		}

	}

	function saveMultiple($field, $table, $here, $there) {
		db_query('DELETE FROM '.$table.' WHERE '.$here.' = :'.$here,array($here=>$this->id));

		foreach($this->post[$field] as $value) {
			db_query('INSERT INTO '.$table.' SET '.$here.' = :'.$here.', '.$there.' = :'.$there, array($here=>$this->id, $there=>$value));
		}
	}

	function saveCreatedModified() {
		$fields = db_listfields($this->table);

		if(!db_fieldexists($this->table, 'created')) db_query('ALTER TABLE '.$this->table.' ADD `created` datetime');
		if(!db_fieldexists($this->table, 'created_by')) db_query('ALTER TABLE '.$this->table.' ADD `created_by` int');
		if(!db_fieldexists($this->table, 'modified')) db_query('ALTER TABLE '.$this->table.' ADD `modified` datetime');
		if(!db_fieldexists($this->table, 'modified_by')) db_query('ALTER TABLE '.$this->table.' ADD `modified_by` int');

		if (!$this->id) {
			array_push($this->keys,'created','created_by');
			$this->post['created'] = strftime('%Y-%m-%d %H:%M:%S');
			$this->post['created_by'] = $_SESSION['user']['id'];
		} else {
			array_push($this->keys,'modified','modified_by');
			$this->post['modified'] = strftime('%Y-%m-%d %H:%M:%S');
			$this->post['modified_by'] = $_SESSION['user']['id'];
		}

	}

	function makeURL($field) {
		global $settings;

		$value = $this->post[$field];
		$url = safestring(trim($value));
		while(strpos($url,'--')) $url = str_replace('--','-',$url);
		if(substr($url,-1)=='-') $url = substr($url,0,strlen($url)-1);

		$url = $this->makeURL_suffix($url);

		if(!$this->post['url']) {
			$this->post['url'] = $url;
		} else {
			$this->post['url'] = safestring($this->post['url']);
			while(strpos($this->post['url'],'--')) $this->post['url'] = str_replace('--','-',$this->post['url']);
			if(substr($url,-1)=='-') $url = substr($url,0,strlen($url)-1);
		}
	}

	function makeURLvalue($value) {
		global $settings;

		$url = safestring(trim($value));
		$url = $this->makeURL_suffix($url);

		if(!$this->post['url']) {
			$this->post['url'] = $url;
		} else {
			$this->post['url'] = safestring($this->post['url']);
		}
	}

	function makeURL_suffix($url) {
		$fields = db_listfields($this->table);
		if(in_array('deleted',$fields)) $deleted = true;

		preg_match_all('^([a-zA-Z0-9_-]+)\-([0-9]+)^', $url, $match);
		if($match[0]) {
			$nr = $match[2][0];
			$base = $match[1][0];
		} else {
			$base = $url;
		}

		$exists = db_numrows('SELECT url FROM '.$this->table.' AS p WHERE p.id != :id AND url = :url '.($deleted?' AND NOT deleted':''), array('id'=>$this->post['id'],'url'=>$url));
		if($exists) {
			$url = $base."-".($nr+1);
			$url = $this->makeURL_suffix($url);
		}

		return $url;

	}

	function makeURL_multilanguage($field) {
		global $settings;

		foreach($settings['languages'] as $language) {
			$value = $this->post[$language['code']][$field];
			if ($value) {
				$url = safestring(trim($value));
				$url = $this->makeURL_multilanguage_suffix($language['code'], $url);
				if(!$this->post[$language['code']]['url']) $this->post[$language['code']]['url'] = $url;
			}
		}

	}

	function makeURL_multilanguage_suffix($language, $url) {

		$lanid = db_value('SELECT id FROM languages WHERE code = :code',array('code'=>$language));
		$fields = db_listfields($this->table);
		if(in_array('deleted',$fields)) $deleted = true;

		$nr = intval(preg_replace("^([a-zA-Z0-9_-]*)-([0-9]*)^", "\\2", $url));
		$base = preg_replace("^([a-zA-Z0-9_-]*)-([0-9]*)^", "\\1", $url);
		if(!intval($nr)) {
			$nr = '';
			$base = $url;
		}

		$exists = db_numrows('SELECT url FROM '.$this->table.' AS p, '.$this->table.'_content AS p2 WHERE p2.table_id = p.id AND lan = :lanid AND p2.id != '.intval($this->post[$language]['id']).' AND url = "'.$url.'"'.($deleted?' AND NOT deleted':''),array('lanid'=>$lanid));
		if($exists) {
			$url = $base."-".($nr+1);
			$url = $this->makeURL_multilanguage_suffix($language,$url);
		}

		return $url;

	}


}
