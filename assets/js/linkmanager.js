$(function() {	
	$('.linkmanager-link').click(function(e){
		var el = $(this);
		var sUrl = el.data('url');
		top.tinymce.activeEditor.windowManager.getParams().oninsert(sUrl);
		top.tinymce.activeEditor.windowManager.close();				
		e.preventDefault();
	});
});