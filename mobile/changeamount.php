<?php

	$box = db_row('SELECT s.*, CONCAT(p.name," ",g.label) AS product, l.label AS location FROM stock AS s LEFT OUTER JOIN products AS p ON p.id = s.product_id LEFT OUTER JOIN genders AS g ON g.id = p.gender_id LEFT OUTER JOIN locations AS l ON l.id = s.location_id WHERE s.id = :id',array('id'=>$_GET['changeamount']));

	$tpl->assign('box',$box);
	$tpl->assign('include','mobile_amount.tpl');
