/*! reach - v1.1.3 - 2019-08-19 */

/*--------------------------------------------------------
 * REACH is the core object containing all components.
---------------------------------------------------------*/
var REACH = {};

;/*--------------------------------------------------------
 * Accordion
---------------------------------------------------------*/
REACH.Accordion = ( function( $ ){
	return {
		init : function() {
			if ( $.fn.accordion ) {
				$('.accordion').accordion({
					heightStyle: "content"
				});
			}
		}
	}
})( jQuery );



;/*--------------------------------------------------------
 * Campaign Barometer
---------------------------------------------------------*/
REACH.Barometer = ( function($) {

	// Barometers collection
	var $barometers = $('.barometer'), 

	// Check whether the element is in view
	isInView = function($el) {
	    var docViewTop = $(window).scrollTop(), 
	    	docViewBottom = docViewTop + $(window).height(), 
			elemTop = $el.offset().top,
			elemBottom = elemTop + $el.height();

	    return ((elemBottom >= docViewTop) && (elemTop <= docViewBottom)
  			&& (elemBottom <= docViewBottom) &&  (elemTop >= docViewTop) );
	}, 

	// A custom arc to apply to the barometer's paths.
	// @see http://stackoverflow.com/questions/5061318/drawing-centered-arcs-in-raphael-js
	customArc = function (xloc, yloc, value, total, R) {
		var alpha = 360 / total * value,
			a = (90 - alpha) * Math.PI / 180,
			x = xloc + R * Math.cos(a),
			y = yloc - R * Math.sin(a),
			path;

		if (total == value) {
			path = [
				["M", xloc, yloc - R],
				["A", R, R, 0, 1, 1, xloc - 0.01, yloc - R]
			];
		} else {
			path = [
				["M", xloc, yloc - R],
				["A", R, R, 0, +(alpha > 180), 1, x, y]
			];
		}
		return {
			path: path
		};
	}

	// Draws a barometer
	drawBarometer = function($barometer, r, width, height, progress_val) {			
		var progress;

		// Draw the percentage filled arc
		if ( progress_val > 0 ) {
			progress = r.path().attr({ 
				stroke: $barometer.data('progress-stroke'), 
				'stroke-width' : $barometer.data('strokewidth')+1, 
				arc: [width/2, height/2, 0, 100, (width/2)-8]
			});

			// Animate it
			progress.animate({
				arc: [width/2, height/2, progress_val, 100, (width/2)-8]
			}, 1500, "easeInOut", function() {
				$barometer.find('span').animate( { opacity: 1}, 300, 'linear');
			});
		}			
	}, 

	// Init barometer
	initBarometer = function($barometer) {
		var width = $barometer.data('width'), 
			height = $barometer.data('height'),					
			r = Raphael( $barometer[0], width, height),
			drawn = false,							
			progress_val = $barometer.data('progress') > 100 ? 100 : $barometer.data('progress'),
			circle;

		// @see http://stackoverflow.com/questions/5061318/drawing-centered-arcs-in-raphael-js
		r.customAttributes.arc = customArc;

		// Draw the main circle
		circle = r.path().attr({
			stroke: $barometer.data('stroke'), 
			'stroke-width' : $barometer.data('strokewidth'), 
			arc: [width/2, height/2, 0, 100, (width/2)-8]
		});

		// Fill the main circle
		$barometer.parent().addClass('barometer-added');
		circle.animate({ arc: [width/2, height/2, 100, 100, (width/2)-8] }, 1000, function() {
			if ( progress_val === 0 ) {
				$barometer.find('span').animate( { opacity: 1}, 500, 'linear' );
			}					
		});

		if ( isInView($barometer) ) {
			drawBarometer($barometer, r, width, height, progress_val);

			drawn = true;
		}
		else {
			$(window).scroll( function() {
				if ( drawn === false && isInView($barometer) ) {
					drawBarometer($barometer, r, width, height, progress_val);

					drawn = true;
				}
			});
		}
	};

	return {
		init : function() {
			$barometers.each( function() {
				initBarometer( $(this) );
			});					
		},
		getBarometers : function() {
			return $barometers;
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Campaign Countdown
---------------------------------------------------------*/
REACH.Countdown = ( function( $ ) {

	// Start the countdown script
	var startCountdown = function() {
		var $countdown = $('.countdown');

		if ($countdown.length) {
			
			$countdown.countdown({
				until: $.countdown.UTCDate( REACH_CROWDFUNDING.timezone_offset, new Date( $countdown.data().enddate ) ), 
				format: 'dHMS', 
				labels : [REACH_CROWDFUNDING.years, REACH_CROWDFUNDING.months, REACH_CROWDFUNDING.weeks, REACH_CROWDFUNDING.days, REACH_CROWDFUNDING.hours, REACH_CROWDFUNDING.minutes, REACH_CROWDFUNDING.seconds],
				labels1 : [REACH_CROWDFUNDING.year, REACH_CROWDFUNDING.month, REACH_CROWDFUNDING.week, REACH_CROWDFUNDING.day, REACH_CROWDFUNDING.hour, REACH_CROWDFUNDING.minute, REACH_CROWDFUNDING.second]
			});
		}		

		return $countdown;
	}

	return {
		init : function() {
			startCountdown();
		}	
	};
})( jQuery );

;/*--------------------------------------------------------
 * Campaign Grid
---------------------------------------------------------*/
REACH.Grid = ( function( $ ) {

	var $grids = $('.campaign-grid');

	var initGrid = function($grid) {
		$grid.masonry({
			itemSelector: '.campaign',
			horizontalOrder: true,
			// fitWidth: true,
			// gutter: 28
		});
	};

	return {

		init : function() {

			if ( $(window).width() > 400 ) {
				$grids.each( function() {
					initGrid( $(this) );
				});
			}

		},

		getGrids : function() {
			return $grids;
		},

		resizeGrid : function() {
			$grids.each( function(){
				initGrid( $(this) );
			})
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Cross-Browser Placeholders
 *
 * Ensure that browsers which don't support the placeholder 
 * attribute will still display the placeholder value as a 
 * preset value inside the element.
---------------------------------------------------------*/
REACH.CrossBrowserPlaceholders = ( function( $ ) {	
	return {
		init : function() {
			var $form_elements = $(':text,textarea');

			// Make sure there are text inputs
			if ( $form_elements.length ) {

				// Only proceed if placeholder isn't supported
				if ( ! ( 'placeholder' in $form_elements.first()[0] ) ) {
					var active = document.activeElement;

					$form_elements.focus( function() {
						if ( $(this).attr('placeholder') != null ) {
							$(this).val('');
							if ( $(this).val() !== $(this).attr('placeholder') ) {
								$(this).removeClass('hasPlaceholder');
							}
						}
					}).blur( function() {
						if ( $(this).attr('placeholder') != null && ($(this).val() === '' || $(this).val() === $(this).attr('placeholder'))) {
							$(this).val($(this).attr('placeholder')).addClass('hasPlaceholder');
						}
					});
					$form_elements.blur();
					$(active).focus();
					$('form').submit(function () {
						$(this).find('.hasPlaceholder').each(function() { $(this).val(''); });
					});
				}
			}
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Dropdown Menus
---------------------------------------------------------*/
REACH.DropdownMenus = ( function( $ ){	
	return {
		init : function() {
			$('.menu li')
			.on( 'mouseover', function() {
				$(this).addClass( 'hovering' );
			})
			.on( 'mouseout', function() {
				$(this).removeClass( 'hovering' );
			});
		}
	};
})( jQuery );

;/*--------------------------------------------------------
 * Fancy Select Elements
---------------------------------------------------------*/
REACH.FancySelect = ( function( $ ){
	return {
		init : function() {
			var $select = $('select'), 
				toggleWrapper = function($el) {
					$el.parent().css('display', $el.css('display'))
				};

			if ( ($select).parent().hasClass('select-wrapper') ) {
				return toggleWrapper;
			}

			$select.wrap('<div class="select-wrapper" />')
			.on('change', function() {
				toggleWrapper($(this))
			});

			$select.each( function() {
				toggleWrapper($(this)); 
			});

			return toggleWrapper;
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Fitvids
---------------------------------------------------------*/
REACH.Fitvids = ( function( $ ){
	return {
		init : function() {
			$( '.fit-video, .video-player' ).fitVids();
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Header Layout
---------------------------------------------------------*/
REACH.HeaderLayout = ( function( $ ) {

    return {
        init : function() {
            if ( REACH_VARS.primary_navigation_offset.length ) {            
                return;
            }

            // We can't calculate the navigation width when on a small screen
            if ( $( window ).width() < 800 ) {
                return;
            }

            var offset_t = $('.site-branding').outerHeight() - 45, // 45px is the height of the nav when it's a single row
                offset_l = $('.site-branding').outerWidth() + 1, 
                stylesheet = ( function(){
                    var style = document.createElement("style");
                    style.appendChild(document.createTextNode(""));
                    document.head.appendChild(style);
                    return style.sheet;
                })();

            stylesheet.insertRule('@media screen and (min-width: 50em) { .site-navigation { margin-top:' + offset_t + 'px; max-width: -webkit-calc(100% - ' + offset_l + + 'px); max-width: -moz-calc(100% - ' + offset_l + 'px); max-width: calc(100% - ' + offset_l + 'px);) } }', 0);

            $.ajax({
                type: "POST",
                data: {
                    action : 'set_primary_navigation_offset', 
                    offset_t : offset_t, 
                    offset_l : offset_l
                },
                dataType: "json",
                url: REACH_VARS.ajaxurl,
                xhrFields: {
                    withCredentials: true
                },
                success: function ( response ) {
                    if ( window.console && window.console.log ) {
                        console.log( response );
                    }
                },
                error: function( error ) {
                    if ( window.console && window.console.log ) {
                        console.log( error );
                    }
                }
            }).fail(function ( response ) {
                if ( window.console && window.console.log ) {
                    console.log( response );
                }
            });
        }
    }
})( jQuery );

;/*--------------------------------------------------------
 * Image Hovers
---------------------------------------------------------*/
REACH.ImageHovers = ( function( $ ) {
	return {
		init : function() {
			$('.on-hover').each( function() {
				var $parent = $(this).parent(), 
					$image = $parent.find('img');

				// Set the width and offset of the hover to match the image
				$(this).css({ width : $image.width(), left : $image.position().left });
				
				// Set up the parent, along with its event handlers
				$parent
				.addClass('hover-parent')
				.on( 'mouseover', function() {
					$(this).addClass('hovering');
				})
				.on('mouseout', function() {
					$(this).removeClass('hovering');
				});
			});
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Lean Modal
---------------------------------------------------------*/
REACH.LeanModal = ( function( $ ){
	return {
		init : function() {
            if ( $.fn.leanModal ) {
                $('[data-trigger-modal]').leanModal({
                    closeButton : ".close-modal"
                });
            }			
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Responsive Menus
---------------------------------------------------------*/
REACH.ResponsiveMenu = ( function( $ ) {
	return {
		init : function() {
			var $container = $( '.site-navigation' ), 
				$account_links = $( '.account-links' ),
				$button, 
				$menu;

			if ( ! $container ) {
				return;
			}

			$button = $container.find( '.menu-toggle' );

			if ( ! $button ) {
				return;
			}

			// Append account-links div
			if ( $account_links.length ) {
				$container.find( '.menu-site' ).append( '<div class="account-links">' + $account_links.html() + '</div>' );
			}			

			$menu = $container.find( 'ul' );

			// Hide menu toggle button if menu is empty and return early.
			if ( ! $menu ) {
				$button.hide();
				return;
			}

			$menu.attr( 'aria-expanded', 'false' );

			if ( ! $menu.hasClass( 'nav-menu' ) ) {
				$menu.addClass( 'nav-menu' );
			}
		 
			$button.on( 'click', function() {
				if ( $container.hasClass( 'toggled' ) ) {
					$container.removeClass( 'toggled' );
					$button.attr( 'aria-expanded', 'false' );
					$menu.attr( 'aria-expanded', 'false' );
				} else {
					$container.addClass( 'toggled' );
					$button.attr( 'aria-expanded', 'true' );
					$menu.attr( 'aria-expanded', 'true' );
				}
			});
		}
	}
})( jQuery );

;/*--------------------------------------------------------
 * Sidebar Positioning
---------------------------------------------------------*/
REACH.SidebarPositioning = ( function( $ ) {
    return {
        init : function() {
            var body_width = $('body').outerWidth(),
                $banner = $( '.banner' ), 
                $sidebar = $( '#secondary' ),
                margin;            

            if ( ! $banner.length || ! $sidebar.length ) {
                return;
            }

            if ( body_width < 785 ) {
                $sidebar[0].style.marginTop = "";
                return;
            }

            $sidebar.css( 'marginTop', $banner.outerHeight() );            
        }
    }
})( jQuery );

;/*--------------------------------------------------------
 * Skip Link Focus Fix 
 *
 * Credit: https://github.com/Automattic/_s
---------------------------------------------------------*/
REACH.SkipLinkFocusFix = ( function() {
	var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var element = document.getElementById( location.hash.substring( 1 ) );

			if ( element ) {
				if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) )
					element.tabIndex = -1;

				element.focus();
			}
		}, false );
	}
})();