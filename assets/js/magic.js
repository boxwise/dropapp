$(function() {
	// Noty, for more info: http://ned.im/noty/
    $.noty.defaults = {
	    layout: 'topCenter',
	    theme: 'relax', // or 'relax'
	    type: 'alert',
	    template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
	    animation: {
	        open: 'animated flipInX', // or Animate.css class names like: 'animated bounceInLeft'
	        close: 'animated flipOutX', // or Animate.css class names like: 'animated bounceOutLeft'
	        easing: 'swing',
	        speed: 500 // opening & closing animation speed
	    },
	    timeout: 5000, // delay for closing event. Set false for sticky notifications
	    closeWith: ['click'],
	    callback: {
	        onShow: function() {},
	        afterShow: function() {},
	        onClose: function() {},
	        afterClose: function() {},
	        onCloseClick: function() {},
	    },
	    buttons: false // an array of buttons
	};



	$(".popupfilemanager").click(function(e) {
		var el = $(this);
		$.fancybox.open({
			href: el.attr('href'),
			type: 'iframe',
			title: 'Filemanager <a class="fancy-close" aria-hidden="true" type="button" href="javascript:$.fancybox.close();">×</a>',
			closeBtn : false,
			padding: Array(40,0,0,0),
			autoSize: false,
			width: 860,
			height: 567,
			helpers : {
			  overlay : {closeClick: false}
			}
		});
		e.preventDefault()
	});

	$('.file_name').each(function(e){
		el = $(this);
		//Creates globally usable var window.field_image_val
		eval('window.' + el.attr('id') + '_val = "'+el.val()+'"');
	});

    setInterval(checkFileChanged, 0.5);

    function checkFileChanged() {
    	$('.file_name').each(function(e){
			var el = $(this);
			var elp = el.closest('div');
	        curVal = el.val();
	        curId = el.attr('id');
	        prevVal = eval(curId+'_val');
	        if (curVal != prevVal) {
	            fileChanged(curId,curVal);
	            eval('window.' + curId + '_val = "'+curVal+'"');
	        }
    	});
    }

	function fileChanged(curId,curVal) {

		var el = $('#'+curId);
		type = $('input[name='+curId.replace('field','_')+']').val();
		type = type.replace('fileselect ','');
		var type = $.trim(type);
		var elp = el.closest('div');
		var thisRemove = elp.find('.file-remove');
		var thisAdd = elp.find('.popupfilemanager');
		var prevVal = elp.find('.file_prev').val();
		var icons = {'doc':'word-o','docx':'word-o','mp3':'sound-o','pdf':'pdf-o','ppt':'powerpoint-o','txt':'text-o','xls':'excel-o','xlsx':'excel-o','zip':'zip-o','jpg':'image-o','jpeg':'image-o','gif':'image-o','png':'image-o'};

		$('.file-remove').removeData('confirmed');

		if (curVal) {
			elp.find('.file-remove').addClass('active');
			elp.find('.file-preview').addClass('active');
			if (type=='image') {
				elp.find('.file-preview img').attr('src',curVal);
			} else {
				elp.find('.file-preview a').attr('href',curVal);
				elp.find('.file-preview span').html(basename(curVal));
				elp.find('.file-preview a i').attr('class','');
				ext = fileExt(curVal);
				icon = icons[ext];
				if (!icon) icon = 'o';
				elp.find('.file-preview a i').addClass('fa glyphicon fa-file-'+icon);
			}
			thisAdd.html(thisAdd.data('btn-change-label'));
			if (prevVal!=curVal) {
				thisRemove.removeClass('confirm');
				thisRemove.html(thisRemove.data('btn-erase-label'));
				var n = noty({
					text: thisAdd.data('btn-change-msg'),
					type: 'success'
				});
			} else {
				thisRemove.addClass('confirm');
			}
		} else {
			if (prevVal!='') {
				elp.children('.file_name').val(prevVal);
				elp.find('.file-preview').addClass('active');
				thisRemove.addClass('confirm');
				thisRemove.html(thisRemove.data('btn-delete-label'));
			} else {
				elp.find('.file-remove').removeClass('active');
				elp.find('.file-preview').removeClass('active');
				if (type=='image') {
					elp.find('.file-preview img').attr('src','');
				}
				thisAdd.html(thisAdd.data('btn-choose-label'));
			}
		}
	}

	$('.zortable tbody').zortable()

	// group select
	$('.group-select').change(function(){
		var parent = $(this).closest('.table-parent')
		if($(this).is(':checked')){
			parent.find('.item-select:visible:not(:checked)').prop('checked', true).trigger('change')
		} else {
			parent.find('.item-select:visible:checked').prop('checked', false).trigger('change')
		}
	})
	// if group-select is checked on load, toggle single-selects
	$('.group-select').trigger('change')

	// single select

	// if item selected on load, toggle parent class
	$('.item-select:checked').closest('tr').toggleClass('selected')

	// make lists items clickable
	$(document).on("click", ".item-clickable", function(e) {
		var el = $(this);
		if(!$(e.target).is('input, label, .stay') && !$(e.target).parent().is('.stay')){
			if(el.closest('.table-parent').data('inside-form') && el.closest('.table-parent').data('modal')){
				el.addClass('opened-fancybox');
				$.fancybox.open({
					href: el.find('a:first').attr('href'),
					type: 'iframe',
					padding: Array(0,0,0,0),
					speedIn: 100,
					speedOut: 100,
					autoSize: true,
					width: 860,
					afterClose: function(){
						$('.opened-fancybox').removeClass('opened-fancybox');
					}
				});
				e.preventDefault();
			} else {
				if(e.which == 1 && !e.metaKey) {
					location = el.find('a:first').attr('href');
				} else if(e.which == 2 || (e.which == 1 && e.metaKey)){
					window.open(el.find('a:first').attr('href'));
				} else {
					location = el.find('a:first').attr('href');
				}
			}
		}
	});

	$('.table-parent[data-modal=true] .item-add').click(function(e){
		var el = $(this);
		$.fancybox.open({
			href: el.attr('href'),
			type: 'iframe',
			padding: Array(0,0,0,0),
			speedIn: 100,
			speedOut: 100,
			autoSize: true,
			width: 860
		});
		e.preventDefault();
	})

	$('.modal-form .aside-content .btn-cancel').click(function(e){
	    parent.postMessage("close", "*");
		e.preventDefault();
	})

	function closeModal(event){
		if(event.data == 'close'){
			$.fancybox.close();
		} else {
			$.fancybox.close();
			data = $.parseJSON(event.data);
			var el = $('.opened-fancybox');
			if(el.length){
			}
			for (var property in data) {
			    if (data.hasOwnProperty(property)) {
				    if(el.find('.data-field-'+property).length){
					    el.find('.data-field-'+property).html(data[property])
				    }
			    }
			}
			if(data.visible == 1){
				el.removeClass('item-hidden')
			} else if(data.visible == 0){
				el.addClass('item-hidden');
			}
		}
		$('.opened-fancybox').removeClass('opened-fancybox');
	}
	window.addEventListener("message", closeModal, false);

	$('body').on('click', '.text-show-original, .text-hide-original', function(e){
		var el = $(this);
		el.parent().addClass('hide').siblings('.hide').removeClass('hide');
		e.preventDefault();
	})

	// make the close 'x' on de dropdowns work
	$('.dropdown-toggle').click(function(e){
		if($(e.target).is('.form-control-feedback')){
			e.stopPropagation();
		}
	})

	$('.dropdown-toggle').on('click', function (event) {
		if($(this).data('toggle') != 'dropdown'){
		    $(this).parent().toggleClass('open');
		}
	});
	
	$('body').on('click', function (e) {
	    if (!$('.dropdown-menu a.confirm').is(e.target) 
	        && $('.dropdown-menu a.confirm').has(e.target).length === 0 
	        && $('.open').has(e.target).length === 0
	    ) {
	        $('.dropdown-toggle').parent().removeClass('open');
	    }
	});

	$('form :input:visible:enabled:first').focus()

	// add tiny tooltip, uses the title attr, more info: http://qtip2.com/
	$('.tooltip-this').qtip({
		style: {
			classes: 'qtip-tipsy',
			tip: {
				width: 10
			}
		},
		show: {
			effect: false
		},
		position: {
			my: 'bottom center',  // Position my top left...
			at: 'top center', // at the bottom right of...
			effect: false
		}
	})

	// datetimepicker, more info: http://eonasdan.github.io/bootstrap-datetimepicker/
	$('.date').each(function(){
		var el = $(this);
		var options = $.extend( {
		}, el.data());
		el.datetimepicker(options).on('dp.change dp.show', function(){
			$(this).find('input').trigger('keyup')
		});
	})

	// select2, more info: http://ivaynberg.github.io/select2/
	$('.select2').each(function(){
		var el = $(this);
		var options = $.extend( {
			allowClear: true,
			formatResult: window[el.data('formatList')]
		}, el.data());
		el.select2(options).on('change', function(){
			// trigger keyup to make the validation plugin validate the field again
			$(this).trigger('keyup')
		})
		el.prev().find('.select2-input').on('focus', function(e, b, c){
			if(e.relatedTarget){
				el.select2('open')			
			}
		})
		
	})

	// simplyCountable, counting characters, more info: https://github.com/aaronrussell/jquery-simply-countable
	$("[data-max-count]").each(function(){
		var el = $(this);
		var options = $.extend( {
			counter: el.next().find('.counter'),
			onOverCount: function(count, countable, counter){
				$(countable).addClass('warning')
			},
			onSafeCount: function(count, countable, counter){
				$(countable).removeClass('warning')
			}
		}, el.data());
		el.simplyCountable(options);
	})

	$('.unlock').click(function(){
		var el = $(this);
		var parent = el.closest('.input-group');
		if(parent.is('.locked')){
			parent.removeClass('locked').find('.form-control').removeAttr('readonly').focus()
		} else {
			parent.addClass('locked').find('.form-control').attr('readonly', 'readonly')
		}
	})


	$('.form').each(function(){
		var el = $(this);
		$('.form').validate({
			ignore: ".no-validate",
			submitHandler: function(form){
				$('#form-submit').prop('disabled', true);
				$('body').addClass('loading');
				if($(form).data('ajax')){
					$.ajax({
						type: 'post',
						url: 'ajax.php?file='+$(form).data('action'),
						data: $(form).serialize(),
						dataType: 'json',
						success: function(result){
							$('body').removeClass('loading');
							$('#form-submit').prop('disabled', false);
							if(result.message){
								var n = noty({
									text: result.message,
									type: (result.success ? 'success' : 'error')
								});
							}
							if(result.redirect){
								if(result.message){
									setTimeout( function() { execReload(result.redirect); }, 1500);
								} else {
									execReload(result.redirect)
								}
							}
						},
						error: function(result){
							$('body').removeClass('loading');
							var n = noty({
								text: 'This file cannot be found or what\'s being returned is not json.',
								type: 'error'
							});
						}
					})
				} else {
					form.submit();
				}
			},
			errorPlacement: function(error, element) {
	        	if($(error).text() != '' ){
	        		if(!('object' === typeof $(element).data('qtip'))){
	        			if($(element).parent().is('.input-group')){
	        				errorTarget = $(element).parent();
	        			} else if($(element).is('.select2')){
	        				errorTarget = $(element).prev();
	        			} else {
	        				errorTarget = $(element);
	        			}
	        			if($(element).closest('#aside-container').length){
							myPo = 'bottom center';
							atPo = 'top center';
	        			} else {
							myPo = 'center left';
							atPo = 'center right';
	        			}
			        	$(element).not('.valid').qtip({
			        		content: {
			        			text: $(error).text()
			        		},
			        		show: {
			        			ready: true,
			        			effect: false
			        		},
			        		hide: false,
			        		position: {
     							my: myPo,
								at: atPo,
								target: errorTarget,
								container: errorTarget.closest('.form-group'),
								effect: false,
								viewport: $('#container')
    						},
    						style: {
    							classes: 'qtip-red',
								tip: {
									width: 8
								}
    						}
			        	});
	        		}
	        	} else {
	        		$(element).qtip('destroy', true)
	        	}
	    	},
	    	showErrors: function(errorMap, errorList){
	    		this.defaultShowErrors();
	    		el.find('.nav-tabs a').removeClass('error')
	    		el.find('.tab-pane:has(.form-control.error)').each(function(){
	    			$('a[href=#'+$(this).attr('id')+']').addClass('error');
	    		})
	    	},
	    	success: function(error){
            	el.find('.valid').qtip('destroy', true);
	    	},
	    	ignoreTitle: true
		})
	})

	// Dirty forms check, voor meer info: https://github.com/codedance/jquery.AreYouSure
	// $('.areyousure').areYouSure();

	if($('.aside-content').length && ($('.aside-content').height() > ($(window).height() - $('.aside-content').offset().top))){
		$('.aside-content').removeClass('affix')
	}

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var target = $($(e.target).attr('href'));
		if(target.has('.qtip')){
			target.find('.error').qtip('reposition')
		}
        localStorage.setItem('lastTab', $(this).attr('href'));
	})

    // go to the latest tab, if it exists:
    var lastTab = localStorage.getItem('lastTab');
    if (lastTab) {
        $('[href="' + lastTab + '"]').tab('show');
    }

	$('.table-parent').each(function(){
		var el = $(this);
		var table = el.find('.table');
		var maxheight = el.data('maxheight');
		if(maxheight){
			setTableHeight(el, table, maxheight)
			$(window).resize(function(){
				setTableHeight(el, table, maxheight)
			})
		}
	})

	function setTableHeight(el, table, maxheight){
		if(maxheight == 'window'){
			maxheight = $(window).height() - el.find('.table').offset().top;
		}
		if(maxheight < table.height()){
			el.find('.sticky-header-container').height(maxheight);
		} else if(el.find('.sticky-header-container').height() < table.height()){
			el.find('.sticky-header-container').height('');
		}
	}

	// Tablesorter, for more info: http://mottie.github.io/tablesorter/docs/
	$('.table-parent:not(.sortable) table').each(function(){
		var el = $(this);
		el.find('th').data("sorter", false)
	})

	// format text fields in forms according to formatting functions on load
	$('.form-control').each(function(){
		var el = $(this);
		var classes = $(this).attr('class').split(/\s+/);
		$.each(classes, function(index, item){
			if(item.substring(0, 9) == 'cms-form-') {
				var format = 'cms_form_'+item.substring(9);
				if (typeof window[format] === "function") window[format](el.attr('name'));
			}
		});
	})

	// set the form title
	$('.setformtitle').keyup(function(){
		$('#form-title').text($(this).val())
	})

	// Fancybox, for more info: http://fancyapps.com/fancybox/
	if ( $.isFunction($.fn.fancybox) ) {
		$(".fancybox").fancybox({
			maxWidth	: 800,
			maxHeight	: 600,
			fitToView	: false,
			width		: '70%',
			height		: '70%',
			autoSize	: false,
			closeClick	: false,
			openEffect	: 'none',
			closeEffect	: 'none'
		});
	}

	initiateList();
	
	$(window).trigger('resize');

})

$(window).resize(function(){
	if($(window).height() > ($('.nav-aside ul:first').offset().top + $('.nav-aside').height())){
		$('.nav-aside').addClass('fixed');
	} else {
		$('.nav-aside').removeClass('fixed');
	}
})

function initiateList(){
	if($('.table:not(.initialized)').length){
		$('.table').addClass('initialized');
		if($('.table-parent.sortable:not(.zortable) table').length){
			$('.table-parent.sortable:not(.zortable) table').each(function(){
				var el = $(this);
				var options = $.extend( {
		    		widgets: ['stickyHeaders', 'saveSort'],
					widgetOptions: {
						stickyHeaders_attachTo : el.closest('.sticky-header-container')
					},
					initialized: function(){
						el.closest('.table-parent').addClass('sortable-initialized');
						$('body').removeClass('loading');
					}
				}, el.data());
				el.tablesorter(options)
			})
		} else {
			$('body').removeClass('loading');
		}
		$('.table').on('change', '.item-select', function(e){
			var el = $(this)
			var parent = el.closest('.table-parent')

			// toggle parent class
			el.closest('tr').toggleClass('selected')

			var selected = parent.find('.selected');

			// uncheck group-select when item within the group is becoming unchecked
			if(!el.is(':checked') && $('.group-select:checked').length){
				$('.group-select').prop('checked', false);
			}

			// toggle the action panel
			if(selected.length && !parent.find('.actions').is('.items-selected')){
				parent.find('.actions').addClass('items-selected')
			} else if(!selected.length && parent.find('.actions').is('.items-selected')){
				parent.find('.actions').removeClass('items-selected')
			} else if(selected.length > 1){
				parent.find('.actions').find('.one-item-only').attr('disabled', 'disabled')
			} else if(selected.length < 2){
				parent.find('.actions').find('.one-item-only').removeAttr('disabled')
			}

			if(selected.is('.item-nondeletable')){
				parent.find('.action-delete').prop('disabled', true);
			} else {
				parent.find('.action-delete').prop('disabled', false);
			}

		})
		// list operations
		$('.start-operation').on('click', function(e){
			var el = $(this);
			e.preventDefault();

			var parent = el.closest('.table-parent')

			if(el.is('.confirm') && !el.data('confirmed')){
				el.confirmation('show')
			} else if(el.data('operation')!='none') {
				el.data('confirmed', false);
				if(parent.find('.table .item-select:checked').length){
					// define the target
					selectedTargets = parent.find('.item-select:checked').closest('tr');

					$.ajax({
						type: 'post',
						url: parent.data('action'),
						// option is the selected option in a button with pulldown
						data: 'do='+ el.data('operation') + '&option=' + el.data('option') + '&ids=' + selectedTargets.map(function(){return $(this).data('id')}).get().join(),
						dataType: 'json',
						success: function(result){
							if(result.success){
								var allTargets = $();
								selectedTargets.each(function(){
									target = $(this)
									allTargets = allTargets.add(target)
										.add(target.prev('.inbetween'))
										.add(target.nextUntil('.level-' + target.data('level')))
								})
								switch(el.data('operation')) {
								    case 'delete':
										allTargets.fadeOut(200, function(){
											$(this).remove();
										});
								        break;
								    case 'hide':
								    	if(parent.data('inheritvisibility')){
											allTargets.addClass('item-hidden')								    	
								    	} else {
											selectedTargets.addClass('item-hidden');								    	
								    	}
								        break;
								    case 'show':
								    	if(parent.data('inheritvisibility')){
											allTargets.removeClass('item-hidden');
										} else {
											selectedTargets.removeClass('item-hidden');										
										}
								        break;
								    default:
								    	// nothing
								}
								// deselect items
								selectedTargets.find('.item-select:visible:checked').prop('checked', false).trigger('change')
							}
							if(result.message){
								var n = noty({
									text: result.message,
									type: (result.success ? 'success' : 'error')
								});
							}
							if(result.redirect){
								if(result.message){
									setTimeout( function() { execReload(result.redirect); }, 1500);
								} else {
									execReload(result.redirect)
								}
							}
							if(result.action){
								eval(result.action);
							}
						},
						error: function(result){
							var n = noty({
								text: 'This file cannot be found or what\'s being returned is not json.',
								type: 'error'
							});
						}
					})

				} else {
					var n = noty({
						text: 'No item selected',
						type: 'error'
					});
				}
			} else {
				selectedTargets = parent.find('.item-select:checked').closest('tr');
				window.location = el.attr('href')+'?ids='+selectedTargets.map(function(){return $(this).data('id')}).get().join();
			}

		});

		$('.inside-list-start-operation').click(function(e){
			var el = $(this);
			var parent = el.closest('.table-parent')
			var target = el.closest('tr');

			$.ajax({
				type: 'post',
				url: parent.data('action'),
				data: 'do='+ el.data('operation') + '&id=' + target.data('id'),
				dataType: 'json',
				success: function(result){
					if(result.success){
						if(result.newvalue == 1){
							el.addClass('active')
						} else {
							el.removeClass('active')
						}
						el.prev('.list-toggle-value').text(result.newvalue)
					}
					if(result.message){
						var n = noty({
							text: result.message,
							type: (result.success ? 'success' : 'error')
						});
					}
					if(result.redirect){
						if(result.message){
							setTimeout( function() { execReload(result.redirect); }, 1500);
						} else {
							execReload(result.redirect)
						}
					}
				},
				error: function(result){
					var n = noty({
						text: 'This file cannot be found or what\'s being returned is not json.',
						type: 'error'
					});
				}
			})
			e.preventDefault();
		})

		// Bootstrap Confirmation, more info: https://github.com/tavicu/bs-confirmation
		$('.confirm').each(function(e){
			var el = $(this);
			var options = $.extend( {
				container: 'body',
				singleton: true,
				popout: true,
				btnOkLabel: 'OK',
				trigger: 'manual',
				onConfirm: function(e, element){
					element.data('confirmed', true).trigger('click')
					e.preventDefault();
				}
	    	}, el.data());
	    	el.confirmation(options);
		})
		// change the count of list items
		$('.change-count').click(function(){
			row = $(this).closest('tr');
			value = $('#field_people_id').val();
			$('#form-submit').prop('disabled', true);
			$('#field_people_id').prop('disabled', true);
			$('body').addClass('loading');
			$.ajax({
				type: 'post',
				url: 'include/purchase_quick_edit.php',
				data:
				{
					people_id: value,
					updown: $(this).data('val'),
					transaction_id: row.data('id')
				},
				dataType: 'json',
				success: function(result){
					if(result.success){
						$('#ajax-aside').html(result.htmlaside);
						$('.not_enough_coins').removeClass('not_enough_coins');
						if($('#field_product_id').val()){
							calcCosts('count');
						}
						row.find('.data-field-countupdown-value').html(result.amount);
						row.find('.data-field-drops2').html(result.drops);
					}
					$('#field_people_id').prop('disabled', false);
					$('body').removeClass('loading');
					if(result.message){
						var n = noty({
							text: result.message,
							type: (result.success ? 'success' : 'error')
						});
					}
				},
				error: function(result){
					var n = noty({
						text: 'Something went wrong, maybe the internet connection is a bit choppy',
						type: 'error'
					});
				}
			});
		})
	} else {
		$('body').removeClass('loading');
	}
}

// format select2 for the parent select
function formatparent(listItem){
	var originalOption = listItem.element;
    return "<span class='select2-level select2-level-" + $(originalOption).data('level') + "'>" + listItem.text + '</span>';
}

// standard functions
function basename(path) {
	if (path) {
		return path.split('/').pop();
	} else {
		return false;
	}
}
function fileExt(path) {
	if (path) {
		ext = path.split('.').pop();
		return ext.toLowerCase();
	} else {
		return false;
	}
}
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
	var el = $(el);
	var target = $(target);
	if(target.val() == ''){
		target.val(el.val()).trigger('blur')
	}
}

function execReload(v){
	if(typeof v == 'string'){
		window.location.href = v;
	} else {
		location.reload();
	}
}