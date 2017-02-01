<?

	ini_set('display_errors',true);
	error_reporting(E_ALL);
	
	require_once('flip.php');

	include($_SERVER['DOCUMENT_ROOT'].'/flip/lib/functions.php');

	$ajax = checkajax();

	$table = 'transactions';
	$action = 'transactions_edit';

	if(!$ajax) {
	
		if($_POST) {
	
			$_POST['transaction_date'] = strftime('%Y-%m-%d %H:%M:%S');
			$_POST['user_id'] = $_SESSION['user']['id'];
			$_POST['drops'] = -intval($_POST['count']) * db_value('SELECT value FROM products WHERE id = :id', array('id'=>$_POST['product_id'][0]));
			
			$handler = new formHandler($table);
			
			$savekeys = array('people_id', 'product_id', 'count', 'description', 'drops', 'transaction_date', 'user_id','size_id');
			$id = $handler->savePost($savekeys);
	
			redirect('?action=purchase&people_id='.$_POST['people_id'][0]);
		}
	
		$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));
	
		if (!$id) {
			$data['visible'] = 1;
			$data['count'] = 1;
			$data['people_id'] = intval($_GET['people_id']);
		}
		
		$translate['cms_form_submit'] = 'Save & next purchase';
		$cmsmain->assign('translate',$translate);
	
		// open the template
		$cmsmain->assign('include','cms_form.tpl');
		addfield('hidden','','id');
	
		// put a title above the form
		$cmsmain->assign('title','New purchase');
	
		addfield('select','Family','people_id',array('onchange'=>'selectFamily("people_id")', 'required'=>true, 'multiple'=>false, 'query'=>'SELECT p.id AS value, CONCAT(p.container, " ",p.firstname, " ", p.lastname) AS label, NOT visible AS disabled FROM people AS p WHERE parent_id = 0 AND NOT p.deleted GROUP BY p.id ORDER BY p.lastname'));
		#commented getSizes() in onchange because we removed the size field...
		addfield('select','Product','product_id',array('onchange'=>'getProductValue("product_id");','required'=>true,'multiple'=>false,'query'=>'SELECT p.id AS value, CONCAT(p.name, " " ,IFNULL(g.label,""), " (",p.value," drop coins)") AS label FROM products AS p LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE NOT p.deleted ORDER BY name'));
// 		addfield('select', 'Size', 'size_id', array('required'=>false,'width'=>2, 'multiple'=>false, 'query'=>'SELECT *, id AS value FROM sizes ORDER BY seq'));
		addfield('number', 'Number', 'count', array('onchange'=>"calcCosts('count')", 'onkeyup'=>"calcCosts('count')", 'required'=>true,'width'=>2));
		addfield('text','Note','description');
		addfield('line');		
		
		addfield('ajaxstart','', '', array('id'=>'ajax-content'));
		addfield('ajaxend');

		addfield('ajaxstart','', '', array('aside'=>true, 'asidetop'=>true, 'id'=>'ajax-aside'));
		addfield('ajaxend','', '', array('aside'=>true));
	
		addfield('created','Created','created',array('aside'=>true));
	
		
		// place the form elements and data in the template
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);

	} else {

		if($_POST['do']){
			switch ($_POST['do']) {
	
			    case 'delete':
					$ids = explode(',',$_POST['ids']);
			    	list($success, $message, $redirect) = listDelete($table, $ids);
			        break;
	
			    case 'hide':
					$ids = explode(',',$_POST['ids']);
			    	list($success, $message, $redirect) = listShowHide($table, $ids, 0);
			        break;
	
			    case 'show':
					$ids = explode(',',$_POST['ids']);
			    	list($success, $message, $redirect) = listShowHide($table, $ids, 1);
			        break;
			}
	
			$return = array("success" => $success, 'message'=> $message, 'redirect'=>false, 'action'=>"$('#field_people_id').trigger('change')");
	
			echo json_encode($return);
			die();
		}
				
		$ajaxform = new Zmarty;

		/* vanaf hier */

		$data['people_id'] = intval($_POST['people_id']);
		$data['allowdrops'] = $_SESSION['user']['is_admin']||db_value('SELECT id FROM cms_functions AS f, cms_access AS a WHERE a.cms_functions_id = f.id AND f.include = "give2all" AND a.cms_users_id = :user_id',array('user_id'=>$_SESSION['user']['id']));

		$table = 'transactions';
		addfield('list','Today\'s Purchases','purch', array('width'=>10,'query'=>'SELECT t.*, u.naam AS user, CONCAT(IF(drops>0,"+",""),drops) AS drops2, DATE_FORMAT(transaction_date,"%d-%m-%Y %H:%i") AS tdate, CONCAT(p.name, " " ,IFNULL(g.label,"")) AS product FROM transactions AS t LEFT OUTER JOIN cms_users AS u ON u.id = t.user_id LEFT OUTER JOIN products AS p ON p.id = t.product_id LEFT OUTER JOIN genders AS g ON p.gender_id = g.id WHERE people_id = '.$data['people_id']. ' AND t.product_id != 0 AND DATE_FORMAT(t.transaction_date, "%Y-%m-%d") = CURDATE() ORDER BY t.transaction_date DESC', 'columns'=>array('product'=>'Product', 'count'=>'Amount', 'drops2'=>'Drop Coins', 'tdate'=>'Date'),
			'allowedit'=>false,'allowadd'=>false,'allowselect'=>true,'allowselectall'=>true, 'action'=>'purchase', 'redirect'=>false, 'allowsort'=>false, 'listid'=>$data['people_id']));
	
		$ajaxform->assign('data',$data);
		$ajaxform->assign('formelements',$formdata);
		$ajaxform->assign('formbuttons',$formbuttons);
		$htmlcontent = $ajaxform->fetch('cms_form_ajax.tpl');

		// the aside
		$ajaxaside = new Zmarty;

		$formdata = $formbuttons = '';

		$data['name'] = db_row('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age FROM people WHERE id = '. $data['people_id']); 
		
		$data['children'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$data['people_id']));
		$data['children'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) < '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$data['people_id']));
		$data['adults'] = db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE parent_id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$data['people_id']));
		$data['adults'] += db_numrows('SELECT *, TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) AS age FROM people WHERE id = :id AND TIMESTAMPDIFF(YEAR,date_of_birth,CURDATE()) >= '.$settings['adult-age'].' AND visible AND NOT deleted',array('id'=>$data['people_id']));

		$data['people'] = db_array('SELECT *, DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), date_of_birth)), "%Y")+0 AS age FROM people WHERE parent_id = :id OR id = :id AND visible AND NOT deleted ORDER BY parent_id, seq',array('id'=>$data['people_id']));
	
		$data['dropcoins'] = db_value('SELECT SUM(drops) FROM transactions AS t WHERE people_id = :id',array('id'=>$data['people_id']));
		$data['givedropsurl'] = '?action=give&ids='.$data['people_id'];
		
		
		$ajaxaside->assign('data',$data);
		$htmlaside = $ajaxaside->fetch('info_aside_purchase.tpl');

		$success = true;		
		$return = array("success" => $success, 'htmlcontent' => $htmlcontent, 'htmlaside' => $htmlaside, 'drops' => $drops, 'people' => $familyhead, 'adults' => $adults, 'children' => $children, 'message'=> $message);
		echo json_encode($return);	
	}	
