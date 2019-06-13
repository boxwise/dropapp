<?php
	$table = 'cms_settings';

	if($_POST) {

		$hasBilanguage = db_fieldexists($table,'description_nl');

		//echo $_POST['value'];

		$handler = new formHandler($table);
		//$handler->debug = true;

		if($hasBilanguage) {
			$keys = array('description_nl','description_en','code','value','type','options');
		} else {
			$keys = array('description','code','value','type','options');
		}
		$handler->savePost($keys);

		if($_POST['__action']=='submitandedit') redirect('?action='.$action.'&origin='.$_POST['_origin'].'&id='.$id);
		redirect('?action='.$_POST['_origin']);
	}

	// collect data for the form
	$data = db_row('SELECT * FROM '.$table.' WHERE id = :id',array('id'=>$id));
	$data['descriptionlabel'] = $data['description'];

	// open the template
	$cmsmain->assign('include','cms_form.tpl');

	// put a title above the form
	$cmsmain->assign('title', ucfirst($translate['cms_setting']));

	// define tabs

	$hasBilanguage = db_fieldexists($table,'description_nl');

	if($_SESSION['user']['is_admin']) {

		if($hasBilanguage) {
			addfield('textarea',$translate['cms_settings_description'].' NL','description_nl');
			addfield('textarea',$translate['cms_settings_description'].' EN','description_en');
		} else {
			addfield('textarea',$translate['cms_settings_description'],'description');
		}
		addfield('text',$translate['cms_settings_code'],'code',array('required'=>true));
		addfield('select',$translate['cms_settings_type'], 'type',
			array('options'=>array(
				array('value'=>'text', 'label'=>$translate['cms_field_text']),
				array('value'=>'textarea', 'label'=>$translate['cms_field_textarea']),
				array('value'=>'checkbox', 'label'=>$translate['cms_field_checkbox']),
				array('value'=>'select', 'label'=>$translate['cms_field_select'])
			),'onchange'=>'if(this.options[this.selectedIndex].value=="select") $("#div_options").removeClass("hidden"); else $("#div_options").addClass("hidden");')
//console.log($("#field_type").html);
		);
		// alleen voor type = select
		addfield('text',$translate['cms_settings_options'],'options',array('tooltip'=>'Vul de mogelijke keuzes in als volgt: waarde=Label,waarde2=Label2,waarde3=Label3','hidden'=>$data['type']!='select'));

		addfield('line');

	} else {
		if($hasBilanguage) {
			if($lan=='nl') {
				addfield('text',$translate['cms_settings_description'],'description_nl',array('readonly'=>true));
				addfield('hidden','','description_en');
			} else {
				addfield('text',$translate['cms_settings_description'],'description_en',array('readonly'=>true));
				addfield('hidden','','description_nl');
			}
		} else {
			addfield('text',$translate['cms_settings_description'],'description',array('readonly'=>true));
		}
		//addfield('info','','descriptionlabel');
		//addfield('hidden','','description');
		addfield('hidden','','type');
		addfield('hidden','','code');
		addfield('hidden','','hidden');
		addfield('hidden','','options');
	}

	switch($data['type']) {
		case 'checkbox':
			addfield('checkbox',$translate['cms_settings_enabled'],'value');
			break;
		case 'select':
			foreach(explode(',',$data['options']) as $option) {
				list($value,$label) = explode('=',$option);
				$options[] = array('value'=>$value,'label'=>$label);
			}
			addfield('select',$translate['cms_settings_value'], 'value',array('options'=>$options));
			break;
		case 'text':
			addfield('text',$translate['cms_settings_value'],'value');
			break;
		case 'textarea':
		default:
			$data['value'] = str_replace("\n","&#10;",$data['value']);
			$data['value'] = str_replace("\r","&#13;",$data['value']);
			addfield('textarea',$translate['cms_settings_value'],'value',array('rows'=>10));
			break;
	}


	addfield('created','','',array('aside'=>true));

	if ($id) {
		addformbutton('submitandedit',$translate['cms_form_save']);
	}

	$cmsmain->assign('data',$data);
	$cmsmain->assign('formelements',$formdata);
	$cmsmain->assign('formbuttons',$formbuttons);
