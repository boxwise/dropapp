<?
	include('flip.php');

	if(!defined('FLIP')) {
		trigger_error('FLIP is niet beschikbaar');
		die();
	}

	$query = 'SELECT * FROM faq_cats WHERE visible AND NOT deleted ORDER BY title';
	$queryVars = array();
	$aCategories = db_array($query,$queryVars);


	$content = '<p>Kies een onderwerp uit onderstaande lijst.</p>';
	$cmsmain = new Zmarty;
	$cmsmain->assign('content',$content);
 	$cmsmain->assign('categories',$aCategories);

	$cmsmain->display('cms_insertfaq_index.tpl');



/*
	function pagetreeItems($parent = 0,$level = 0) {

		$query = 'SELECT menutitle AS title, url, id, '.$level.' AS level FROM pagetree WHERE visible AND NOT deleted AND parent_id = '.$parent.' ORDER BY seq ASC';
		$result = db_query($query);

		while($row = db_fetch($result)) {
			$row['level'] = $level;
			$row['sub'] = '';
			$sub = pagetreeItems($row['id'],$level+1);
			foreach($sub as $field) {
				$row['sub'][] = $field;
			}
			$data[] = $row;
		}
		return $data;
	}


	function productItems($parent = 0,$level = 0) {

		$query = 'SELECT menutitle AS title, url, id FROM products WHERE visible AND NOT deleted ORDER BY seq ASC';
		$result = db_query($query);

		while($row = db_fetch($result)) {
			$data[] = $row;
		}
		return $data;
	}

	function peopleItems() {

		$query = 'SELECT CONCAT(t.firstname," ",if(t.inbetween="","",CONCAT(t.inbetween," ")),t.lastname) AS title, t.url, t.id FROM people AS t LEFT OUTER JOIN x_people_peoplecat AS x ON x.people_id = t.id LEFT OUTER JOIN people_cats AS c ON c.id = x.cat_id WHERE t.visible AND NOT t.deleted GROUP BY t.id';
		$result = db_query($query);

		while($row = db_fetch($result)) {
			$data[] = $row;
		}
		return $data;
	}
*/
