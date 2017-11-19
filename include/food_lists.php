<?php

	$action = 'food_lists';

	if($_POST) {
		$type = $_POST['type'][0];
		if($type =='veg') {

			redirect('?action=printed_list_people');

		} else if ($type=='drynew') {
				
			redirect('/pdf/dryfood.php?title=dryfood');

		} else if ($type=='breadnew') {
				
			redirect('/pdf/dryfood.php?title=bread');

		} else if ($type=='dry') {
				
			redirect('?action=food-distribution');

		} else if ($type=='drydiapers') {
				
			redirect('?action=food-distribution&diapers=true');

		} else {

			redirect('?action=printed_list_containers');

		}

	} else {
		addfield('custom','','<div class="noprint tipofday"><h3>💡 Best printing result</h3><p>Print it out using Google Chrome, choose A4, Portrait and switch off the printing of headers and footers in the Print dialog screen.</p></div>');
		addfield('line');
		addfield('select', 'Which List', 'type', array('options'=>array(
			array('value'=>'veg', 'label'=>'Vegetables'),
			array('value'=>'drynew', 'label'=>'Dry Food'),
			array('value'=>'breadnew', 'label'=>'Bread'),
			array('value'=>'dry', 'label'=>'Dry Food (old style)'),
			array('value'=>'con', 'label'=>'Container'))));
		// open the template
		$cmsmain->assign('title','Sales overview');
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
