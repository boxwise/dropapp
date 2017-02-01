// NOTE by Remco: at some places in this script a value of "nil" is used for an empty string so it can be an index of an array

var state = null;

$(function(){
		
$('.first-field').focus();

var input = $('input');
var default_value = input.data('default');

	/**added by WD, edited by Remco **/
	/* onfocus for inputfields with selector moustrap */
	var input = $('.mousetrap');
	var default_value = input.val();
	$(input).focus(function() {
		if($(this).val() == default_value)
		{
				 $(this).val("");
		}
	}).blur(function(){
		if($(this).val().length == 0);
		{
				$(this).val(default_value);
		}
	});

	/* Added by RN: simulate form submit (get) on Filter button click */
	$('div.filters input.set_filter').bind('click', function() {
		var filters = $(this).parents('div.filters');
		var str = '';
		var loc = ''
		$('input, select', filters).each(function() {
			str += this.name + ' (' + this.id + ')=' + this.value + '\n';
			if (this.name > '') {
				if (loc > '') loc += '&';
				loc += escape(this.name) + '=' + escape(this.value);
			}
		});
//		alert(str);
		document.location = '?' + loc;
	});
	/* Added by RN: simulate form submit (get) on Filter reset button click */
	$('div.filters input.reset_filter').bind('click', function() {
		var filters = $(this).parents('div.filters');
		var str = '';
		var loc = ''
		$('input, select', filters).each(function() {
			str += this.name + ' (' + this.id + ')=' + this.value + '\n';
			if (this.name > '') {
				if (loc > '') loc += '&';
				value = this.value;
				if (this.name != 'action') {
					value = '';
				}
				loc += escape(this.name) + '=' + escape(value);
			}
		});
//		alert(str);
		document.location = '?' + loc;
	});
	
	// noty, notifications
	// more info: http://needim.github.com/noty/
	// default settings
	$.noty.defaultOptions = {
		layout : "top",
		animateOpen : {opacity: 'toggle'},
		animateClose : {opacity: 'toggle'},
		timeout : 2000,
		closable : true,
		closeOnSelfClick : true,
		modalCss : {'opacity': 0.6}
	};

	// save initial form and validate
	$('form#content-form').validate({
		highlight: function(label) {
			$(label).closest('.control-group').addClass('error');
		},
		unhighlight: function(label){
			$(label).closest('.control-group').removeClass('error');
		},
		submitHandler: function(form){
			var submitButton = this.submitButton;
			if($(form).data('ajax')){
				$('body').append('<div class="overlay hide"></div>').find('.overlay').fadeTo(500, 0.8);
				var doparam = getParameterByName('do');
				if (doparam == null) doparam = '';
				$.ajax({
					type: 'POST',
					data: '&do=' + doparam + '&function=save&ajax=true&'+$(form).serialize(),
					success: function(result){
						var res = null;
						$('.overlay').remove();
						noty({
							text:"Opslaan is gelukt!",
							type:"success"
						});
						$(form).find('#content-form-submit .save.disabled').button('reset')
						console.log(result);
						try {
							res = JSON.parse(result);
						} catch(e) {
//							alert(e);
//							document.location.replace(document.location.href+'&loginreason=expired');
						}
						
						if ($(submitButton).hasClass('close')) {
							document.location = $(submitButton).data('close');
							return;
						}
						
						// reset the operations
						$(form).find('#hidden-operation').val('');
						
//						console.log(res['newrec']);
						if (res && res['newrec'] && res['newrec'][1] && submitButton != null) {
							document.location.replace(document.location.href.replace('do=new', 'do=edit&id=' + res['newrec'][1]));
						} else {
//							location.reload();
						}
					}
				});
			} else {
				form.submit()
			}
		}
	});
	
	$('.start-operation').click(function(e){
		var el = $(this);
		$('#hidden-operation').val(el.data('operation'));
		el.closest('#content-form').submit();
		e.preventDefault();
	})
	
	// by Remco
	$("input[type=button].btn.save").bind("click", function() {
		$(this).parents("#content-form").submit();
	});
	
	// mousetrap, keyboard shortcuts
	// more info: http://craig.is/killing/mice
	
	Mousetrap.bind(['ctrl+s', 'meta+s'], function(e) {
	    if (e.preventDefault) {
	        e.preventDefault();
	    } else {
	        // internet explorer
	        e.returnValue = false;
	    }
	    $('form#content-form').trigger("submit", {submitButton: this});
	});
	
	// by remco
	Mousetrap.bind(['enter'], function(e) {
//			alert('enter');
	    if (e.preventDefault) {
	        e.preventDefault();
	    } else {
	        // internet explorer
	        e.returnValue = false;
	    }
	    if ($('form input[name=keyvalue]').val() == 'new_1') {
	    	$('input[type=button].save.newitem').trigger('click');
	    } else {
	    	$('input[type=submit].save').trigger('click');
	    }
			return false;
	});
	
	// creating the zortables

	$('.zortable tbody').zortable({
//		startlevel: 2
	})
	
	// fixing the height
	$('.table-parent').each(function(){
		var el = $(this);
		var maxheight = el.data('maxheight');
		var table = el.find('.table');
		if(maxheight){
			if(maxheight == 'window'){
				maxheight = $(window).height() - el.find('.table').offset().top;
			}
			if(maxheight < table.height()){
				$('.table').fixedHeaderTable({height: maxheight});
			}
		}
	})


	// loosen sticky right column when the content in it is to height
	if($('.affix-container').offset() && ($('.affix-container').outerHeight() + $('.affix-container').offset().top) < $(window).height()){
		$('.affix-container').affix({
			offset: $('.affix-container').position().top
		})
	}

	// list delete
	$('.item-delete').on('click', function(e){
		var el = $(this);
		var parent = el.closest('.table-parent')
		if(parent.find('.table .item-select:checked').length){
			noty({
				text: 'Weet u zeker dat u dit wilt verwijderen?',
				type: 'confirm',
				buttons: [
					{
						type: 'btn btn-mini', text: 'Ja', click: function() {
							// define the target
							target = parent.find('.item-select:checked').closest('tr')
							
							// RN: ajax call to delete the target
							var doparam = getParameterByName('do');
							if (doparam == null) doparam = '';
							var data = '&do=' + doparam + '&function=save&ajax=true&'+'';
							target.each(function() {
								data += $(this).data('id') + '__delete=deleted&';
							});
							alert(data);
							$.ajax({
								type: 'POST',
								data: data,
								success: function(result){
							}});

							// add inbetween and children to the target
							/* RN disabled: for the time being, we reload after a delete
							var collection = target
								.add(target.prev())
								.add(target.nextUntil('.level-' + target.data('level')))

							collection.fadeOut(200, function(){
								$(this).remove();
							});
							*/
							location.reload();
						}
					},
					{type: 'btn btn-mini', text: 'Annuleren', click: function() {  } }
				],
				modal: true,
				closable: false,
				timeout: false
			})
		} else {
			noty({
				text: 'Geen item geselecteerd',
				type: 'error',
				layout: 'top'
			})
		}
		e.preventDefault()
	});

	// group select
	$('.group-select').change(function(){
		var parent = $(this).closest('.table-parent')
		if($(this).is(':checked')){
			parent.find('.item-select:not(:checked)').trigger('click')			
		} else {
			parent.find('.item-select:checked').trigger('click')
		}
	})
	// if group-select is checked on load, toggle single-selects
	$('.group-select').trigger('change')	

	// single select
	
	// if item selected on load, toggle parent class
	$('.item-select:checked').closest('tr').toggleClass('selected')
	
	$('.table').on('change', '.item-select', function(){
		var el = $(this)
		var parent = el.closest('.table-parent')
	
		// toggle parent class
		el.closest('tr').toggleClass('selected')
		
		// uncheck group-select when item within the group is becoming unchecked
		if(!el.is(':checked') && $('.group-select:checked').length){
			$('.group-select').attr('checked', false);
		}
		
		// toggle the action panel
		if(parent.find('.item-select:checked').length && parent.find('.actions').not('.items-selected')){
			parent.find('.actions').addClass('items-selected')
		} else if(!parent.find('.item-select:checked').length && parent.find('.actions').is('.items-selected')){
			parent.find('.actions').removeClass('items-selected')
		}
	})

	
	/* *** Van Remco, 3-12-2012: is deze code nog in gebruik? ajax/sort.php bestaat niet *** */
	/* *** Van Maarten, 8-8-2013: ik laat hem voor nu nog even staan, indien we sorteren via de backend willen doen *** */
	// sorting table
/*
	$('.sort').click(function(){
		var el = $(this);
		var parent = el.closest('.table-parent');
		
		// make table loading
		parent.addClass('loading')
		
		// ajax call
		$.ajax({
			type: 'post',
			url: 'ajax/sort.php',
			data: $.param(parent.data()) + '&' + $.param(el.data()),
			dataType: 'json',
			success: function(result){
				var res = null;
				
				try {
					res = JSON.parse(result);
				} catch(e) {
					alert("AJAX error (294): " + result + "\nUploading data: " + this.data + "\nCalled from magic.js");
				}
				// there is a result and it's successful
				if(result != null && result.success){
					
					// remove and add classes to th.sort
					parent.find('.sort-'+parent.data('sortedDirection')).removeClass('sort-'+parent.data('sortedDirection'))
					el.addClass('sort-'+result.data.sortedDirection)
					
					// add html
					parent.find('.table tbody').html(result.html)
					
					// reset data
					parent.data(result.data);
					
				}
				
				// the result is empty or not successful
				if(result == null || !result.success) {
					noty({
						text: (result != null && result.message != null) ? result.message : 'Oeps, er ging iets mis.',
						type: 'error'
					})
				}
				
				// all back to normal
				parent.removeClass('loading')
			},
			error: function(result){
			
				// the file is not found or the result isn't json.
				noty({
					text: 'Oeps, er ging iets mis: '+result.statusText,
					type: 'error'
				})
				
				// all back to normal
				parent.removeClass('loading')
			}
		})
	})
*/
	


/*
	// voorbeelden van noty
	// notification success
	noty({
		text:"Wow, het is gelukt!",
		type:"success",
		timeout: false,
	});

	// notification error
	noty({
		text:"Oeps, d'r ging iets mis!",
		type:"error"
	});

	// notification confirm
	noty({
		text:"Deze notificatie wordt gesloten.",
		layout: "center",
		type:"confirm",
		closable: false,
		timeout: false,
		buttons: [
			{type: 'button green', text: 'Ok', click: function() {  } },
			{type: 'button pink', text: 'Cancel', click: function() {  } }
		],
		modal: true
	});
*/

	// datepicker, more info: http://jqueryui.com/demos/datepicker/
	// timepicker, more info: http://trentrichardson.com/examples/timepicker/
	// inputmask, more info: https://github.com/RobinHerbots/jquery.inputmask#aliases-option

	if(!Modernizr.inputtypes.date){
		$('[type="date"]').each(function(){
			var el = $(this)
			var options = $.extend( {
				showOtherMonths: true,
				selectOtherMonths: true,
				firstDay: 1,
				beforeShow: function(input, inst){
					// debug.log(input)
					// debug.log(inst)
				}
	    	}, el.data());

			el.datepicker(options)
			.inputmask(el.data('dateFormat'))
		})
	}
	if(!Modernizr.inputtypes.datetime){
		$('[type="datetime"]').each(function(){
			var el = $(this);
			var options = $.extend( {
				showOtherMonths: true,
				selectOtherMonths: true,
				beforeShow: function(input, inst){
					// debug.log(input)
					// debug.log(inst)
				}
	    	}, el.data());

			el.datetimepicker(options)
			.inputmask(el.data('dateFormat')+' '+el.data('timeFormat'))
		})
	}
	if(!Modernizr.inputtypes.time){
		$('[type="time"]').each(function(index) {
			var el = $(this);
			var options = $.extend( {
				showOtherMonths: true,
				selectOtherMonths: true
	    	}, el.data());

   			el.timepicker(options)
			.inputmask(el.data('timeFormat'));
		});
	}

	// colorpicker
	// more info: http://www.eyecon.ro/bootstrap-colorpicker/
	
	$('.color').colorpicker().on('changeColor', function(ev){
		var el = $(this);
		var args = {};
		args[el.data('targetCss')] = ev.color.toHex();
		$(el.data('targetElement')).css(
			args
		);
	}).find('input').blur(function(){
		var el = $(this)
		if(el.val() != ''){
			el.parent().colorpicker('setValue')
		}
	})
	
	// lock and unlock input field
	$('.toggle-lock').toggle(function(){
		var el = $(this);
		
		// toggeling tipsy
		$('.tipsy').remove()
		el.attr('title', el.data('toggle-title')).addClass('unlocked')
		
		// toggeling the input field
		el.prev().removeAttr('disabled').focus()

	}, function(){
		var el = $(this)
		// toggeling tipsy
		el.attr('title', el.data('original-title')).removeClass('unlocked')

		// toggeling the input field
		el.prev().attr('disabled', 'disabled')
	})
	$('.toggle-lock').tipsy({
		gravity: 's'
	})
	
	// Tipsy: http://onehackoranother.com/projects/jquery/tipsy/
	// general tipsies
	$('.tipsy-this').each(function(){
		el = $(this);
		var g = (el.data('tipsyGravity') ? el.data('tipsyGravity') : 's');
		el.tipsy({
			gravity: g
		})
	})
	
	// chosen, single & multiple select
	// more info: http://harvesthq.github.com/chosen/
	
	$('.chosen').each(function(){
		var el = $(this)
		// OPTIONS
		// data-search-on-letters="false"
		// data-add-items="false"
		el.chosen({
			allow_single_deselect: !el.is('.required')
		});
	})
	
	$('textarea.tinymce').each(function(){
		var el = $(this);
		el.tinymce({
			// Location of TinyMCE script
			script_url : '/global4/js/tiny_mce/tiny_mce.js',
		
			// General options
			theme : "advanced",
			skin: "zinnebeeld",
			width: '100%',
			height: (el.height() - 7),
			plugins : "inlinepopups,advimage,paste,media,pdw",
	/*         plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template", */
	
			// Theme options
	        theme_advanced_buttons1 : "bold,italic,strikethrough,|,link,unlink,|,bullist,numlist,blockquote,|,image,media,|,pdw_toggle",
			theme_advanced_buttons2 : "charmap,styleselect,|,undo,redo,|,code",
			theme_advanced_buttons3 : "",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "bottom",
			theme_advanced_resizing : true,

			// paste
			paste_text_sticky : true,
			setup : function(ed) {
				ed.onInit.add(function(ed) {
					ed.pasteAsPlainText = true;
				});
			},

			// toggle toolbars
			pdw_toggle_on : 1,
	    pdw_toggle_toolbars : "2",	
	
			// Example content CSS (should be your site CSS)
			content_css : "/global4/css/tinymce-content.css"
			
		});
	
	});

	$('.clickable').click(function(e){
		var el = $(this);
		if(!$(e.target).is('input, label')){
			location = el.find('a:first').attr('href');
		}
	})
	
	// quick edit
	$('.quick-edit').on('click', function(e){
		var el = $(this);
		var parent = el.closest('tr');
		var quickedit = $('<tr class="quick-edit-wrap"></tr>').append(parent.find('.quick-edit-content').clone().removeClass('hide'))
		
		// remove existing quick-edits, data is not saved
		parent.siblings('.quick-edit-wrap').find('.cancel').trigger('click')
		
		// change data-name to name attributes, to be able to post form
		quickedit.find('input').each(function(){
			var el = $(this)
			el.attr('name', el.data('name'))
		})
		
		// validate & submit form
		.end().find('form').validate({
			submitHandler: function(form){
				$(form).find('.save').button('loading')
				$.ajax({
					type: 'POST',
					data: 'do=save&ajax=true&'+$(form).serialize(),
					success: function(result){
						var res = null;
				
						noty({
							text:"Opslaan is gelukt!",
							type:"success"
						});
						
						// currently triggering the cancel but new data should be loaded here
						$(form).find('.cancel').trigger('click');
						try {
							res = JSON.parse(result);
						} catch(e) {
							alert("AJAX error (558): " + result + "\nUploading data: " + this.data + "\nCalled from magic.js");
						}
						
						// Remco 2012-07-11: @Maarten Ik heb debug.log naar console.log gezet omdat dat veel beter samenwerkt met firebug
//						console.log(result)
					}
				})
			}
		})
		
		// insert form
		parent.hide().after(quickedit);
		
		e.preventDefault()
	})
	
	$('.table tbody').on('click', '.quick-edit-content .cancel', function(e){
		$(this).closest('tr').prev().show().end().remove();
		e.preventDefault();
	})

	// by remco
	manageState();
	
	//By wd 
	//provide responsive-table-setup for devices with a viewport lower then 480px
/* 	$("#stacktable").stacktable({class:'stacktable small-only'});	 */
	$("#responsive-table").stacktable({class:'stacktable small-only'});
	
	
	//By wd
	//toggle for the filter
	$('.trigger a').click(function(e){
		$('.filters').animate(
			{height: 'toggle'}, 
			{
			duration: 250,
			complete: function() {
				complete()
			}
		});
		this.blur(); // added by RN
		e.preventDefault();
	});
	function complete(){
		$(".trigger a").toggleClass('active');
	}
	
	/* Added by RN: sort columns on click header text */
	$('th.sort').click(function(e) {
		var el = $(this).find('a:first');
		console.log(this.id)
		console.log(el.attr('id'));
		var parent = el.closest('.table-parent').addClass('loading');
		var fieldname = 'field-' + el.attr('id').replace('sort_', '');
		var table = el.parents('table');
		var asc = !el.hasClass('desc');
		
		// make table loading
		parent.addClass('loading')

		$('tr.item td div.' + fieldname).each(function() {
			reorder(fieldname, $(this), table, asc);
		});
		
		parent.removeClass('loading').find('.sort').removeClass('asc desc');
		el.toggleClass('asc', !asc).toggleClass('desc', asc);
		el.blur();
		e.preventDefault();
	});	
});

// move a single row in a table to the right position
function reorder(fieldname, $el, $container, ascending)
{
	var row = $el.parents('tr');
	var value = $el.get(0).getAttribute('data-sort');
	var id = $el.get(0).id;
	if (value !== 'summary') {
		var afterthis = 2;
		$('tr.item td div.' + fieldname).each(function() {
			if (this.id == id) {
				// same row, do nothing
				afterthis = 0;
			} else if (value < this.getAttribute('data-sort') && ascending) {
				// smaller and ascending, move before this element
				if (afterthis > 0) {
					row.insertBefore($(this).parents('tr'));
					return false;
				}
			} else if (value > this.getAttribute('data-sort') && !ascending) {
				// bigger and descending, move before this element
				if (afterthis > 0) {
					row.insertBefore($(this).parents('tr'));
					return false;
				}
			}
			afterthis++;
		});
	}
	return;
}

// standard functions
function getUrl(el){
	// hier moet ik met ajax de url vanuit php halen
	var el = $(el);
	el.val(el.val().replace(/ /g, '-').toLowerCase())
}
function setExternalText(el, target){
	var el = $(el)
	$(target).text( (el.val() != '' ? el.val() : $('label[for='+el.attr('id')+']:visible').text()) )
}
function setExternalInput(el, target){
	var el = $(el).find('input[type=text]');
	var target = $(target).find('input[type=text]');
	target.each(function(){
		if(target.val() == ''){
			target.val(el.val()).trigger('blur')
		}
	})
}

// validator methods toegevoegd, deze moeten uiteindelijk ergens anders komen te staan

jQuery.validator.addMethod("date", function(value, element) {
	return this.optional(element) || /^\d\d?[\.\/-]\d\d?[\.\/-]\d\d\d?\d?$/.test(value);
});

jQuery.validator.addMethod("datetime", function(value, element) {
	return this.optional(element) || /^\d\d?[\.\/-]\d\d?[\.\/-]\d\d\d?\d?\s([0-1][0-9]|2[0-3]):(([0-5][0-9])|([0-5][0-9]):([0-5][0-9]))$/.test(value);
});

jQuery.validator.addMethod("time", function(value, element) {
	return this.optional(element) || /^([0-1][0-9]|2[0-3]):(([0-5][0-9])|([0-5][0-9]):([0-5][0-9]))$/.test(value);
});

// helper functions, by Remco
function getParameterByName(name, location) {
	var search = location == null ? window.location : location;
	var match = RegExp('[?&]' + name + '=.*&' + name + '=([^&]*)').exec(search);
	if (match) {
		return decodeURIComponent(match[1].replace(/\+/g, ' '));
	}
	var match = RegExp('[?&]' + name + '=([^&]*)').exec(search);
	if (match) {
		return decodeURIComponent(match[1].replace(/\+/g, ' '));
	}
	var result = null;
	var i = 0;
	do {
//		console.log(name);
			search = decodeURIComponent(search);
			match = RegExp('[?&]' + name + '\\[\\]' + '=([^&]*)(.*)').exec(search);
//			console.log('[?&]' + name + '\[\]' + '=([^&]*)(.*)');
//			console.log(search);
			if (match) {
//				console.log('match');
//				console.log(match);
				if (!result) result = [];
				result[i] = decodeURIComponent(match[1].replace(/\+/g, ' '));
				i++;
				search = match[2];
			} else {
//				console.log('no match');
			}
	} while (match);
//	console.log('array object');
//	console.log(result);
	return result;
}

function setCookie(name, value) {
	document.cookie = name + "=" + encodeURIComponent(value) + ";expires=" + new Date(new Date().setFullYear(new Date().getFullYear() + 1)).toUTCString();
}

function getCookie(name) {
	var re = "(?:; )?" + name + "=([^;]*);?";
	var regexp = new RegExp(re);
	if(regexp.test(document.cookie)) {
		return decodeURIComponent(RegExp["$1"]);
	} 
	else {
		return null;
	}
}
	
function listCookies(filter) {
	var re = "(?:; )?\s*([^= ]*)=([^;]*);?(.*)";
	var regexp = new RegExp(re);
	var search = document.cookie;
	var result = [];
	var i = 0;
	while (search) {
		match = regexp.exec(search);
		if (!filter || match[1].indexOf(filter) !== -1) {
			result[i++] = match[1];
		}
		search = match[3];
	}
//	console.log(result);
	return result;
}
	
function removeCookie(name) {
	var re = "(?:; )?" + name + "=([^;]*);?";
	var regexp = new RegExp(re);
	document.cookie = name + "=0;expires=0;max-age=0";
//	console.log("document.cookie = " + name + "=0;expires=0;max-age=0");
}

function toObject(arr) {
  var rv = {};
  for (var i = 0; i < arr.length; ++i)
    if (arr[i] !== undefined) rv[i] = arr[i];
  return rv;
}

/* By RN
 * removeOldCookies() removes cookies by age (expiration) and by number (maximum)
 * When expiration equals zero, no age removal is done, 
 * oldest cookies exceeding maximum are removed.
 * I choose to do this by code rather than automatically by expiration date,
 * because I want to have the chance to retrieve state information and store
 * it elsewhere before the cookie expires.
 * Also, I want to be able to set and maintain a maximum number of cms4-cookies.
 */
 
function removeOldCookies(expiration, maximum)
{
	var cookies = listCookies("cms4-");
	// console.log(cookies);
	var toclean = cookies.length - maximum;
	// console.log(toclean);
	if (expiration > 0) {
		for (i in cookies) {
			var age = new Date().getTime() - parseInt(cookies[i].replace(/^cms4-/, ''));
			if (age > expiration) {
				toclean--;
	//			console.log("removeCookie("+cookies[i]+") " + age);
				removeCookie(cookies[i]);
			}
		}
	}
	for (i in cookies) {
		if (toclean > 0) {
//			console.log("removeCookie("+cookies[i]+") " + i + " <= " + toclean);
			removeCookie(cookies[i]);
			toclean--;
		}
	}
}

function urlAddState(url)
{
	if (url && url.indexOf("//" + document.location.host) == -1) {
		return url;
	} else {
		var hash = url.match(/#.*/);
		if (hash) {
			hash = hash[0];
		} else {
			hash = '';
		}
		url = url.replace(hash, "");
		for (var j in state) {
			var thisaction = j;
			for (var k in state[j]) {
				var thisdo = k;
	//				console.log(els[i]);
				if (url && 
					url.match(RegExp('[?&]action=' + thisaction)) != null && 
					(url.match(RegExp('[?&]do=' + thisdo)) != null ||
						(thisdo == "nil"))) {
					for (var m in state[j][k]) {
						if (url.match(RegExp('[?&]' + m + '(\[\])?=[^&]*')) == null) {
							if (typeof(state[j][k][m]) == 'object') {
								for (n in state[j][k][m]) {
									url += "&" + m + "[]=" + state[j][k][m][n];
//									console.log("(" + url + ") url.href += " + "&" + m + "[]=" + state[j][k][m][n]);
								}
							} else {
								url += "&" + m + "=" + state[j][k][m];
//								console.log("(" + url + ") url.href += " + "&" + m + "=" + state[j][k][m]);
							}
						}
					}
				}
			}
		}
	
		var stateparam = window.name.replace('cms4-', '');
		if (url.indexOf("&state=") == -1) {
			url.replace(/&state=[^&]*/, '');
			if (url.indexOf("?") == -1) {
				url += "?";
			} else {
				url += "&";
			}
			url += "state=" + stateparam;
		}
	}
	return url + hash;
}

function manageState()
{
	// initialize
	//	console.log(window.name);
	var stateparam = getParameterByName("state");
	var second = 1000;
	var minute = 60 * second;
	var hour = 60 * minute;
	var expiration = 2 * hour; // in milliseconds
	var recent = new Date().getTime() - parseInt(window.name.replace(/^cms4-/, '')) < expiration; 
	// window name expires after this period of time, state name changes but state data is preserved

	// get cookie by window name and parse json
	if (window.name.match(/^cms4-/)) {
		json = getCookie(window.name);
//		alert(json);
//		console.log("json (16)");
//		console.log(json);
		if (json) {
			state = JSON.parse(json);
		}
	}
	// if cookie is old or window name doesn't match state parameter, make a new cookie
	if (recent && (stateparam == null || window.name == "cms4-" + stateparam)) {
		// NOOP
	} else {
		var oldwindowname = window.name;
		window.name = 'cms4-' + new Date().getTime();
		if (stateparam && state == null) {
//			console.log('cms4-' + stateparam);
			json = getCookie('cms4-' + stateparam);
//			console.log("json (29)");
//			console.log(json);
	//		alert(json);
			if (json) {
				state = JSON.parse(json);
			}
		}
		// the old cookie has been abandoned, throw it away now
		if (!recent) {
			removeCookie(oldwindowname);
		}
	}
//	console.log("state (37)");
//	console.log(state);
//	console.log(window.name);

	// if there's no state parameter, move to a new location to include one
	// this is a last resort, these situations should be covered when writing the url.
	var newlocation = urlAddState(document.location.href);
	if (newlocation !== document.location.href) {
		document.location.href = newlocation;
	}
	
	// rewrite url's on this page that go to the cms4 server
	var action = getParameterByName('action');
	var doparam = getParameterByName('do');
	if (!action) action = "nil";
	if (!doparam || doparam == '') doparam = "nil";

//	console.log("document.location (494)");
//	console.log(document.location);
	var els = document.getElementsByClassName('state');
//	console.log("els");
//	console.log(els);
	for (var i in els) {
		var el = els[i];
		var id = el.id;
//		alert(id + '=' + getParameterByName(id));
		if (id) { 
			if (!state) state = {};
			if (!state[action]) state[action] = {};
			if (!state[action][doparam]) state[action][doparam] = {};
			var value = getParameterByName(id);
			if (value !== null) {
				state[action][doparam][id] = value;
			}
//			console.log("state[" + action + "][" + doparam + "][" + id + "] = " + value);
		}
	}
//	console.log(state);
	
	els = document.getElementsByTagName('a');
	for (var i in els) {
		if (els[i].href) {
			els[i].href = urlAddState(els[i].href);
		}
	}

	els = document.getElementsByTagName('form');
	for (var i in els) {
		for (var j in els[i].attributes) {
			if (els[i].attributes[j].name == "action" && (els[i].attributes[j].value.indexOf("//") == -1 || els[i].attributes[j].value.indexOf(document.location.host) !== -1)) {
//				els[i].attributes[j].value = urlAddState(els[i].attributes[j].value);
//				els[i].attributes[j].value += "&state=" + window.name.replace('cms4-', '');
			}
		}
	}

//	console.log(state);
	// save state and manage cookies
	var json = JSON.stringify((state));
//	console.log(json);
//	console.log(JSON.parse(json));
	removeOldCookies(0, 20);
	setCookie(window.name, json);
}

function overlay(el)
{
	var overlay_id = "overlay_" + getParameterByName("do", el.href) + "_" + getParameterByName("id", el.href);
	var overlay_el = document.getElementById(overlay_id);
	try {
		if (overlay_el) {
			$(overlay_el).css({display: 'block'});
		} else {
			$("form#content-form").append("<div id='" + overlay_id + "' class='form_overlay' />");
			overlay_el = document.getElementById(overlay_id);
			$.ajax({
				type: 'GET',
				data: el.href + '&ajax=true&function=overlay',
				success: function(result){
					overlay_el.innerHTML = result;
					$('input', overlay_el).bind('change', function(el) {
						$('*[data-qfield="' + (this.id.replace("form__", "")) + '"]').text($(this).val());
					});
				}
			});
		}
	} catch(e) {
		alert("caught exception " + e);
	}
	return false;
}

function overlay_close(el)
{
	$(el).parents(".form_overlay").css({display: 'none'});
	return false;
}

function switchVisible(el)
{
	var doparam = getParameterByName('do');
	if (doparam == null) doparam = '';
	var data = el.id + '=' + (el.checked ? '1' : '0');
	$.ajax({
		type: 'POST',
		data: '&do=' + doparam + '&function=save&ajax=true&'+data,
		success: function(result){
//				location.reload();
		}
	});
	if (el.checked) {
		$('*', $(el).parents('tr')).removeClass('invisible-indicator');
	} else {
		$('*', $(el).parents('tr')).addClass('invisible-indicator');
	}
}