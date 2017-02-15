<?

	$table = 'stock';

	initlist();

	$cmsmain->assign('title','Container stock');
	listsetting('search', array('p.name'));

	$data = getlistdata('
SELECT * 
FROM 
	stock AS s 
LEFT OUTER JOIN products AS p ON p.id = s.product_id 
WHERE 
	NOT s.deleted AND 
	s.location_id IN (2,3) AND 
	NOT p.stockincontainer');
	
	foreach($data as $key=>$d) {
		$totalboxes += $d['boxes'];
		$totalitems += $d['stock'];
	}

	listsetting('allowcopy', false);
	listsetting('allowadd', false);
	listsetting('allowdelete', false);
	listsetting('allowselect', false);
	listsetting('allowselectall', false);
	listsetting('allowsort', true);
	listsetting('maxheight', false);

	addcolumn('text','Product','name');
	addcolumn('text','Gender','gender');
	addcolumn('text','Size','size');
	addcolumn('text','Boxes','boxes');
	addcolumn('text','Items','stock');
	addcolumn('text','Boxes elsewhere','totalboxes');

	$cmsmain->assign('listfooter',array('Total boxes/items','','',$totalboxes,$totalitems));

	$cmsmain->assign('data',$data);		
	$cmsmain->assign('listconfig',$listconfig);
	$cmsmain->assign('listdata',$listdata);
	$cmsmain->assign('include','cms_list.tpl');
	$cmsmain->assign('include2','cms_list.tpl');
	
