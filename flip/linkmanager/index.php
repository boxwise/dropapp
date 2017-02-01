<?
	include('flip.php');

	if(!defined('FLIP')) { 
		trigger_error('FLIP is niet beschikbaar');
		die();
	}	
	
	//The array containing all linkmanager blocks (internal links for the website)
	$list = array();				
	
	$list['title'] = 'Producten';
	$list['prefix'] = '/product/';		
	$list['links'] = productItems();	
	$linkslist[] = $list;

	$list['title'] = 'Artikelen';
	$list['prefix'] = '/';		
	$list['links'] = pagetreeItems(333);	
	$linkslist[] = $list;	
	
/*
	$list['title'] = 'Pagina\'s';
	$list['prefix'] = '/';		
	$list['links'] = pagetreeItems(161);	
	$linkslist[] = $list;	
	
*/
	$list['title'] = 'Personen';
	$list['prefix'] = '/medewerkers/';		
	$list['links'] = peopleItems();	
	$linkslist[] = $list;		
	
	$content = '<p>Kies een interne link uit de onderstaande lijsten. Of <a href="/flip/filemanager/dialog.php?type=2&field_id='.$_GET['field_id'].'" style="text-decoration: underline;">klik hier</a> om een link naar een bestand te maken.</p>';	
	
	$cmsmain = new Zmarty;
	$cmsmain->assign('content',$content);	
	$cmsmain->assign('linksList',$linkslist);
	
	$cmsmain->display('cms_linkmanager_index.tpl');		

	
	
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

		$query = 'SELECT CONCAT(t.firstname," ",if(t.inbetween="","",CONCAT(t.inbetween," ")),t.lastname) AS title, t.url, t.id FROM people AS t LEFT OUTER JOIN x_people_peoplecat AS x ON x.people_id = t.id LEFT OUTER JOIN people_cats AS c ON c.id = x.cat_id WHERE t.visible AND NOT t.deleted GROUP BY t.id ORDER BY t.firstname';
		$result = db_query($query);
		
		while($row = db_fetch($result)) {
			$data[] = $row;			
		}
		return $data;
	}	
		