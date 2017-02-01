$('.tinymce').each(function(){

	var el = $(this)

	//Defaults
	var lan = el.data('lan');
	var width = '100%';
	var height = 500;
	var toolbarOptions = 'bold italic bullist numlist link unlink anchor | formatselect styleselect | responsivefilemanager image insertembed insertfaq | table charmap | paste undo redo | removeformat code';
	var stylesheet = '';
	//Typekit kit ID
	var typekitId = 'fpa7ifp';

	var elements = 'Paragraph=p;Heading=h4;Quote=blockquote';
	var validElements = '';
	var invalidElements = 'section,div';
	var statusbar = true;
	var pasteAsText = false;
	var pasteRemoveStyles = true;
	var styles = [
				 	{title: 'Introductie', selector: 'p', classes: 'intro'},
				 	{title: 'Highlight', inline: 'span', classes: 'highlight'},
				 	{title: 'Bijschrift', selector: 'p', classes: 'caption'},
				 	{title: 'Beeld (100%)', selector: 'p', classes: 'image'},
				 	{title: 'Beeld (orig. formaat)', selector: 'p', classes: 'image-original'},
				 	{title: 'Beeld rechts (50%)', selector: 'p', classes: 'image-right'},
				 	{title: 'Beeld links (50%)', selector: 'p', classes: 'image-left'},
				 	{title: 'Beeld rechts (30%)', selector: 'p', classes: 'image-right30'},
				 	{title: 'Beeld links (30%)', selector: 'p', classes: 'image-left30'},
				 	{title: 'Tabel zonder lijnen', selector: 'table', classes: 'nolines'},
				 	{title: 'Knop', selector: 'a', classes: 'btn'},
				 	{title: 'Knop: zwart outline', selector: 'a', classes: 'btn btn-inverted'},
				 	{title: 'Knop: blauw', selector: 'a', classes: 'btn btn-blue'},
				 	{title: 'Knop: blauw outline', selector: 'a', classes: 'btn btn-blue btn-inverted'}
				 ];

	if(el.data('tinymceToolbarType') == 'extended'){
		//Specifics go here
		//extended is the default type
		stylesheet += 'content.css';
	} else if (el.data('tinymceToolbarType') == 'intro'){
		//Specifics go here
		toolbarOptions = 'bold italic link unlink anchor | formatselect styleselect | responsivefilemanager image | paste undo redo | removeformat code';;
		stylesheet += 'content.css';
		var height = 220;
	}

	var options = $.extend( {
		selector: '.tinymce',
		skin: 'zinnebeeld',
		language: lan,
/*
		relative_urls: false,
		remove_script_host: true,
*/
		convert_urls: false,
		toolbar_items_size: 'small',
		statusbar: statusbar,
		entity_encoding: 'numeric',
		resize: true,
		width: width,
		height: height,
		min_height: 3,
		plugins: [
			'autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker',
			'searchreplace wordcount visualblocks visualchars code insertdatetime nonbreaking',
			'table contextmenu directionality template textcolor paste insertembed insertfaq responsivefilemanager typekit'
		],
		toolbar: toolbarOptions,
		menubar: false,
		content_css: '/flip/lib/'+stylesheet+'?' + new Date().getTime(),
		style_formats: styles,
		block_formats: elements,

		paste_as_text: pasteAsText,
		paste_remove_styles: true,
		paste_strip_class_attributes : 'mso',
		paste_auto_cleanup_on_paste : true,
		paste_text_use_dialog : true,
		paste_force_cleanup_paste : false,
		paste_remove_spans: true,

		valid_elements: validElements,
		invalid_elements: invalidElements,
		paste_word_valid_elements: 'b,strong,i,em,h1,h2,h3,h4',
		typekitId: typekitId,

	   	//settings for image plugin
		image_dimensions: false,

		external_filemanager_path: '/flip/filemanager/',
	   	filemanager_title: 'Filemanager',
	    file_browser_callback: function(field_name, url, type, win) {
			if (type == 'file') {
		 		diaTitle = 'Insert link';
		 		browser = '/flip/linkmanager/index.php'+'?field_id='+field_name;
		 	} else if (type == 'image') {
		 		diaTitle = 'Insert image';
		 		browser = '/flip/filemanager/dialog.php?type=1&field_id='+field_name;
		 	}
		 	tinymce.activeEditor.windowManager.open({
		 		title: diaTitle,
		 		url: browser,
		 		width: 860,
		 		height: 600
		 	},{
		 		oninsert: function(url) {
		 			win.document.getElementById(field_name).value = url;
		 		}
			});
	    }
	}, el.data());

	el.tinymce(options);

})

