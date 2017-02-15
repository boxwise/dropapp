// Zortable 0.2
// last edit 25-05-2012
// by @maartenhunink

// dependend on 
// - jquery ui

// These options can be set when initiating the zortable in js, or within the html on the .table-parent as data attributes.

// EXAMPLE
// js: $('.zortable').zortable({startlevel: 2})
// override in html: <div class="table-parent zortable" data-startlevel="1">...

// OPTIONS
// startlevel="0" (from which level on can you drag)
// maxlevel="false" (from which level on can you not drag anymore)
// saveonchange="false" (save everytime an item has changed)



(function($) {
	var methods = {
		init : function( options ) {
			
			// override js settings by data/html settings
			options = $.extend(options, $(this).closest('.table-parent').data());

			// overide default settings
			var settings = $.extend( {
				'startlevel': 0,
 		    	'maxlevel': false,
 		    	'saveonchange': false
	    	}, options);
	    	
			return this.each(function(){
				var i = 1;
				var $this = $(this),
             		data = $this.data('zortable')
		        	
             	if(!data){
             		// bind settings to element
             		$this.data('zortable', settings)
             		
             		// fix the markup
					methods.fixmarkup.apply($this)
					
					$this.on('mouseover', 'tr.item-zortable:not(.inbetween, .no-drag)', function(){
			    		$(this).filter(function(){
			    			// depending on the startlevel
							return $(this).data('level') >= $this.data('zortable').startlevel && $(this).data('level') <= $this.data('zortable').maxlevel
			    		}).draggable({
					        opacity: .3,
/* 					        containment: 'parent', */
					        refreshPositions: true,
					        appendTo: $this.parent(),
					        helper: function(e, ui){
					        	
					        	// add .inbetween and children to the target
					        	var draggedItems = $(e.currentTarget).prev()
					        		.add(e.currentTarget)
					        		.add($(e.currentTarget).nextUntil('.level-' + $(e.currentTarget).data('level')))
						        
						        // save it to be used when dropped
						        $(e.currentTarget).data('draggedItems', draggedItems)
						        // clone and embrace
					        	var helper = $('<table/>').append(draggedItems.clone())
					        	
					        	return helper;
					        },
					        zIndex: 100,
					        revert: 'invalid',
					        start: function(e, ui) {
					        	var currentTarget = $(e.currentTarget);
					        	draggedItems = currentTarget.data('draggedItems')
					        	
					        	// fix width of table
					        	$(ui.helper).width(currentTarget.width())
					        					        	
								// hide all dragged items and open space below
					        	draggedItems.addClass('cloned').filter(':last').next().find('td').css({height: ($(ui.helper).height() + 1)})
					        	
					        	// cancel drag/drop with ESC
					        	$(document).on('keyup', function(e){
					        		if(e.keyCode == 27){
					        			$this.find('.ui-droppable.hover').droppable('disable')
						        		draggedItems.trigger('mouseup')
						        		
						        		// close all dropzones
				       			        $('.inbetween .hover').removeClass('hover').css({height: 1})
				       			        $('tr.hover').removeClass('hover').find('.parent-indent').css({height: 1})
				       			        
				       			        // open dropzone of revert
						        		draggedItems.filter(':last').next().find('td').css({height: ($(ui.helper).height() + 1)}).addClass('revert-to-me')
						        		
						        		$(document).off('keyup')
					        		}
					        	})

								// disable dropping depending on the amount of levels
					        	if($this.data('zortable').maxlevel >= 0){
						        	var currentLevel = currentTarget.data('level');
						        	var levels = 0;

						        	// made this to prevent being able to drag items with children to levels beyond what's allowed, but buggy, for now disabled
						        	// draggedItems.each(function(){
						        	// 	var el = $(this)
						        	// 	if(el.data('level') > currentLevel){
						        	// 		currentLevel = el.data('level');
						        	// 		levels++;
						        	// 	}
						        	// })

						        	$this.find('tr').each(function(){
						        		var el = $(this);
						        		
						        		// disable childing
										if(el.data('level') + levels >= $this.data('zortable').maxlevel && el.is('.ui-droppable')){
											el.droppable('disable')
										}
										
										// disable siblinging
										if(el.data('level') + levels > $this.data('zortable').maxlevel){
											el.find('.ui-droppable').droppable('disable')
										}
						        	})					        		
					        	}
							},
							stop: function(e, ui){
								$(e.target).data('draggedItems').removeClass('cloned')
					        	$this.find('.ui-droppable').droppable('enable')
					        	
					        	// close revert dropzone
						        $(e.target).data('draggedItems').filter(':last').next().find('td').css({height: 1}).removeClass('revert-to-me')
							}
					    })
				    });
             	}
			});		
		},
		fixmarkup : function() {
			return this.each(function(){
				var $this = $(this);
				
				// remove useless inbelow
				$this.find('.inbelow').each(function(){
					var el = $(this)
					if(el.prev('.level-'+ (el.data('level') - 1)).length){
						el.remove();
					}
				})

				// add inbetweens
				$this.find('tr:not(.inbetween)').each(function(){
					var el = $(this)
					var colspan = el.find('td').length;
					var inbetween = $('<tr class="level-'+el.data('level')+' inbetween"><td colspan="'+colspan+'"><span></span></td></tr>').data('level', el.data('level'))
					
					// add inabove if there isn't one yet
					if(!el.prev('.inbetween.level-'+el.data('level')).length){
						el.before(inbetween)
					}
										
					// add inbelow
					if(el.next().data('level') < el.data('level') || !el.next().length){
						el.after(inbetween.clone().data('level', el.data('level')).addClass('inbelow'));
						
						// are we there yet? If the next elements are another level up, keep adding inbelows
						belowel = el.next()
						while(belowel.next().data('level') < (belowel.data('level') - 1) || (!belowel.next().length && belowel.data('level') != 0)){
							belowel.after(inbetween.clone().data('level', (belowel.data('level') - 1)).addClass('inbelow').removeClass('level-'+el.data('level')).addClass('level-'+(belowel.data('level') - 1)));
							belowel = belowel.next()
						}
					}
				})
				
				$this.closest('table').addClass('zortable-active');
				
				// make new elements droppable
				methods.enabledroppables.apply($this)
			})
		},
		enabledroppables : function( ) { 
			return this.each(function(){
				var $this = $(this);
				
				// enable inbetween droppable
				$this.find('.inbetween').filter(function(){
	    			// depending on the startlevel
					return $(this).data('level') >= $this.data('zortable').startlevel
				}).find('td:not(.ui-droppable)').droppable({
			        accept: '.zortable tr:not(.cancelled)',
			        tolerance: 'touch',
			        hoverClass: 'hover',
			        over: function(e, ui){
			        
			        	// close all other dropzones
       			        $('.inbetween .hover').not(this).removeClass('hover').css({height: 1})
       			        $('tr.hover').not(this).removeClass('hover').find('.parent-indent').css({height: 1})
       			        
       			        // open this dropzone
		            	$(this).css({ height: ($(ui.helper).height() + 1)})
			        },
			        out: function(e, ui){
		            	$(this).css({height: 1});
			        },
			        drop: function(e, ui) {
			        	var el = $(this);
			            var parent = el.closest('tr');
						
						// difference between the parent level en dropped items						
						var leveldiff = parent.data('level') - ui.draggable.data('draggedItems').filter(':first').data('level');
						
						// fix level diferences
						if(leveldiff != 0){
							methods.fixleveldiff.apply(ui.draggable.data('draggedItems'), [leveldiff]);
						}
						
						// insert elements
		                parent.before(ui.draggable.data('draggedItems'));

		                el.css({height: 1});
			            
			            // finish the drop
				        methods.finishdrop.apply($this, [ui.draggable.data('draggedItems')]);
			        }
			    });				
				
				
				// // enable parent droppables
				if($this.data('zortable').maxlevel > 0){
					$this.find('tr:not(.ui-droppable, .inbetween, .no-drop)').filter(function(){
		    			// depending on the startlevel
						return $(this).data('level') >= ($this.data('zortable').startlevel -1 )
					}).droppable({
				        accept: '.zortable tr:not(.cancelled)',
				        tolerance: 'pointer',
				        hoverClass: 'hover',
				        over: function(e, ui){
				        
				        	// close all other dropzones
	       			        $('.inbetween .hover').not(this).removeClass('hover').css({height: 1})
	       			        $('tr.hover').not(this).removeClass('hover').find('.parent-indent').css({height: 1})
							
							// open this dropzone
		     	 	      	$(this).find('.parent-indent').css({ height: $(ui.helper).height()})
				        },
				        out: function(e, ui){
		            		$(this).find('.parent-indent').css({height: 1})
				        },
				        drop: function(e, ui) {
				        
				        	var el = $(this);

							// difference between the parent level en dropped items						
							var leveldiff = el.data('level') - ui.draggable.data('draggedItems').filter(':first').data('level') + 1;

							// fix level differences
							methods.fixleveldiff.apply(ui.draggable.data('draggedItems'), [leveldiff]);

							// insert elements
							el.after(ui.draggable.data('draggedItems'))
				            
    			            el.find('.parent-indent').css({height: 1});
				            
				            // finish the drop
				            methods.finishdrop.apply($this, [ui.draggable.data('draggedItems')]);
				        }
				    });									
				}
			})
		},
		fixleveldiff: function(leveldiff){
			return this.each(function(){
	        	$(this).removeClass('level-'+$(this).data('level'))
	        		.addClass('level-'+ ($(this).data('level') + leveldiff))
	        		.data('level', ($(this).data('level') + leveldiff))
			})
		},
		finishdrop: function(el){
			 return this.each(function(){
			 	var $this = $(this);
			 	var tableparent = $(this).closest('.table-parent')
			 	
	        	$('.cloned').removeClass('cloned')
	        	
	            methods.fixmarkup.apply($this)
	            
			 	var list = new Array();
				$(this).find('tr:not(.inbetween)').each(function(){
					list.push([$(this).data('id'), $(this).data('level')]);
				})			 					
			 	
			 	input = '<input class="zortable-list" name="ids" type="hidden" value="'+JSON.stringify(list)+'">';
				tableparent.find('fieldset').html(input)
			 	
  		        if($this.data('zortable').saveonchange){
					methods.save.apply($this)
				} else {
				 	$this.closest('form').trigger('changeForm')
				}

			 })
		},
		save : function (){
			return this.each(function(){
				var $this = $(this);
				var tableparent = $this.closest('.table-parent');
				$.ajax({
					type: 'post',
					url: tableparent.data('action'),
					data: 'do=move&' + tableparent.find('fieldset').serialize(),
					dataType: 'json',
					success: function(result){
						if(result.success){
							$this.closest('.table-parent').find('fieldset').empty()
							$this.data('move', 0);
						}
						if(result.message){
							new PNotify({
								text: result.message,
								type: (result.success ? "success" : "error")
							});							
						}
						if(result.action){
							eval(result.action);
						}
					},
					error: function(result){
						new PNotify({
							text: 'This file cannot be found or what\'s being returned is not json.',
							type: 'error'
						});
					}
				})				
			})
		},
	};
	
	$.fn.zortable = function(method){
	
		if (methods[method]) {
	      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
	    } else if (typeof method === 'object' || !method) {
	      return methods.init.apply( this, arguments );
	    } else {
	      $.error( 'Method ' +  method + ' does not exist on jQuery.zortable' );
	    }

	};

})(jQuery);