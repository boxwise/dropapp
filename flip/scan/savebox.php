<?

	if(!$_POST['box_id']) {
		do {
			$_POST['box_id'] = generateBoxID();			
		} while(db_value('SELECT COUNT(id) FROM stock WHERE box_id = :box_id',array('box_id'=>$_POST['box_id'])));
	}
	$handler = new formHandler('stock');

	$savekeys = array('box_id', 'product_id', 'size_id', 'items', 'location_id', 'comments' ,'qr_id');
	if($_POST['id']) $savekeys[] = 'id';
	$id = $handler->savePost($savekeys);
	
	
	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE NOT s.deleted AND s.id = :id',array('id'=>$id));

	if($_POST['id']) {
		$message = 'Box '.$box['box_id'].' modified with '.$box['product'].' ('.$box['items'].'x) in '.$box['location'];
	} else {
		$message = 'New box created with box ID <strong>'.$box['box_id'].'</strong> (write this number in the top right of the box label). This box contains '.$box['items'].' '.$box['product'].' and is located in '.$box['location'].'.';			
	}
	
	$data['barcode'] = db_value('SELECT code FROM qr WHERE id = :id',array('id'=>$box['qr_id']));

	redirect('?barcode='.$data['barcode'].'&message='.$message);
	
