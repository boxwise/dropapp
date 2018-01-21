<?php

	$action = 'food_lists';

	if($_POST) {
		$double = $_POST['double'];
		$diapers = $_POST['diapers'];
		redirect('/pdf/dryfood.php?diapers='.$diapers.'&double='.$double);


	} else {
/*
		addfield('custom','','<div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>');
		addfield('line');
*/
/*
		addfield('select', 'Which List', 'type', array('options'=>array(
			array('value'=>'veg', 'label'=>'Vegetables'),
// 			array('value'=>'drynew', 'label'=>'Dry Food'),
			array('value'=>'breadnew', 'label'=>'Bread')
// 			array('value'=>'con', 'label'=>'Container')
			)));
*/
		addfield('checkbox','Double portion (for vegetables)','double');
		addfield('checkbox','Include diapers in the list','diapers');
		
		// open the template
		$cmsmain->assign('title','Distribution list');
		$cmsmain->assign('include','cms_form.tpl');
		// Title
		// Form Button
		$translate['cms_form_submit'] = 'Create list';
		$cmsmain->assign('translate',$translate);
		// place the form elements and data in the template
		$cmsmain->assign('data',$data);
		$cmsmain->assign('formelements',$formdata);
		$cmsmain->assign('formbuttons',$formbuttons);

	}
