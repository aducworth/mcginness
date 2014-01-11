	<footer>

		<div class='container'>
	
			<ul>
				<li><a href='/refunds'>Refund Policy</a></li>
				<li><a href='/privacy'>Privacy Policy</a></li>
				<li><a href='/about'>About Us</a></li>
			</ul>
			
			<div class='find-us'>
			
				<span>Find Us</span>
				
				<a href='#' class='facebook'></a>
				<a href='#' class='twitter'></a>
				<a href='#' class='youtube'></a>
				<a href='#' class='instagram'></a>
			
			</div>
			
			<div class='email-signup'>
			
				<span>Be in the know about new products & special updates.</span>
				
				<a href='/_email_signup?ajax=true' class='colorbox'>Email Signup</a>
			
			</div>
			
			<p class='copyright'>© 2013 Boxwork Cabinets. All rights reserved.</p>
		
		</div>
			
	</footer>
	
	<script src="/js/jquery.min.js"></script>
	<script src="/js/jquery.colorbox-min.js"></script>
	
	<script>
	
		var verified_shipping = '';
	
		$(document).ready(function(){
		
			$(".colorbox").colorbox();
			
			$(".gallery-item").colorbox({rel:'gallery-item'});
			
			$(document).bind('cbox_closed', function() {
			
				//$('.side-panel-cart').html( '<p>Loading...</p>' );
			
				// see if we should load the mini cart or the full
				if( $('.side-panel-cart').length > 0 ) {
					
					$.ajax({
					  url: '/_mini_cart?ajax=true',
					  type: "POST"
					}).done(function(data) {
					  $('.side-panel-cart').html( data );
					});
					
				} else {
				
					$.ajax({
					  url: '/_cart_contents?ajax=true&edit=true',
					  type: "POST"
					}).done(function(data) {
					  $('.cart-contents').html( data );
					});					
					
				}
				
			  
			});
		
			$('.prev').click(function(e){
			
				e.preventDefault();
			
				var invisible_options = $(this).parent().find('li.invisible-option');
				
				if( invisible_options.length > 0 ) {
				
					var last_invisible = $(this).parent().find('li.invisible-option').eq( invisible_options.length - 1 );
					
					last_invisible.show('slide');
					last_invisible.addClass('visible-option');
					last_invisible.removeClass('invisible-option');
					
				}
				
			});
			
			$('.next').click(function(e){
			
				e.preventDefault();
			
				var visible_options = $(this).parent().find('li.visible-option');
				
				if( visible_options.length > 3 ) {
				
					var first_option = $(this).parent().find('li.visible-option').eq(0);
					
					first_option.hide('slide');
					first_option.addClass('invisible-option');
					first_option.removeClass('visible-option');
					
				}
				
				
			});
			
			$('.option-selector ul li a').click(function(e){
			
				if( !$(this).hasClass('colorbox') ) {
				
					e.preventDefault();
				
					$(this).parent().parent().find('.selected').removeClass('selected');
					$(this).addClass('selected');
					
					var position = $(this).offset();
					
					// find out where to update the document with the new value
					var postData = 'id=' + $(this).attr('data-value');
					
					if( $(this).hasClass('door-and-drawer') ) {
						
						$.ajax({
						  url: '/_selected_drawer_type?ajax=true',
						  data: postData,
						  type: "POST"
						}).done(function(data) {												  
							$('.selected-door-and-drawer').html( data );
							$('html, body').animate({scrollTop: position.top + 70}, 'slow');
						});
						
					} else if( $(this).hasClass('wood-type') ) {
						
						$.ajax({
						  url: '/_selected_wood_type?ajax=true',
						  data: postData,
						  type: "POST"
						}).done(function(data) {												  
							$('.selected-wood-type').html( data );
							$('html, body').animate({scrollTop: position.top + 250}, 'slow');
						});
						
					} else if( $(this).hasClass('stain-color') ) {
						
						$.ajax({
						  url: '/_selected_stain_color?ajax=true',
						  data: postData,
						  type: "POST"
						}).done(function(data) {												  
							$('.selected-stain-color').html( data );
							$('html, body').animate({scrollTop: position.top + 300}, 'slow');
						});
						
					}
				
				} 
			
			});
			
			$('.back-to-top').click(function(e){
			
				e.preventDefault();
				
				$('html, body').animate({scrollTop: 375}, 'slow');
			
			});
			
			$('#apply-discount').click(function(e) {
				
				e.preventDefault();
				
				if( $('#discount-code').val() != '' ) {
				
					var postData = 'discount_code=' + $('#discount-code').val();
					
					$.ajax({
					  url: '/_cart_contents?ajax=true',
					  data: postData,
					  type: "POST"
					}).done(function(data) {
					  $('.cart-contents').html( data );
					});	
				
				} else {
					
					alert( 'Please enter a discount code' );
					
				}
				
			});
			
			<? if( $action == 'build' ): ?>
			
				$('.selected-door-and-drawer').load('/_selected_drawer_type?ajax=true');
				$('.selected-wood-type').load('/_selected_wood_type?ajax=true');
				$('.selected-stain-color').load('/_selected_stain_color?ajax=true');
				$('.side-panel-cart').load('/_mini_cart?ajax=true');
			
			<? endif; ?>
			
			<? if( $action == 'cart' ): ?>
			
				$('.cart-contents').load('/_cart_contents?ajax=true&edit=true');
				
			<? endif; ?>
			
			<? if( $action == 'checkout' ): ?>
			
				$('.cart-contents').load('/_cart_contents?ajax=true');
				
				$('.shipping-info').change(function(){
					
					console.log( $(this).attr('name') + ' changed' );
					
					if( validateShipping( false ) === true ) {
						
						getRates();
						
					}
					
				});
				
				$('#same-as-billing').click(function() {
				
					var ischecked = $(this).is(':checked');
						
					$('.billing-info').each(function( index ) {
		
						var tochange = $(this).attr('id').replace('billing','shipping');
						
						if( ischecked ) {
						
							$('#'+tochange).val( $(this).val() );
							
						} else {
						
							$('#'+tochange).val( '' );
												    
						}
												  
					});	
					
					getRates();
							
					
				});
				
				$('#checkout-form').submit(function(e) {
					
					if( validateCheckout() ) {
						
						return;
						
					} else {
						
						e.preventDefault();
						
					}
					
				});
				
				$('.input-error').on( 'blur', function() {
					
					if( $(this).val() != '' ) {
						
						$(this).removeClass('.input-error');
						
					}
					
				});
				
			<? endif; ?>
		
		});
		
		function validateCheckout() {
		
			var errors = 0;
			$('.input-error').removeClass('input-error');
			
			// validate required fields
			$('.required').each(function( index ) {
			
				if( $(this).val() == '' ) {
					$(this).addClass('input-error');
					errors++;
				}
				
			    console.log( index + ": " + $( this ).attr('name') );
			    
			});
			
			if( $('#shipping').length == 0 ) {
				
				$('.cart-update-notice').html("<span class='errors'>*** Shipping rates must be calculated to checkout. Please click the refresh link in the cart to calculate rates.</span>");

				errors++;
				
			}
			
			if( errors == 0 ) {
				
				return true;
				
			} else {
				
				return false;
				
			}
			
		}
		
		function validateShipping( bypass_verify ) {
			
			var errors = 0;
			
			var verified_shipping_try = '';
			
			$('.shipping-info').each(function( index ) {
			
				if( $(this).val() == '' ) {
					errors++;
				}
				
				verified_shipping_try += $(this).val();
				
			    console.log( index + ": " + $( this ).val() );
			    
			});
			
			if( errors > 0 ) {
				
				return false;
				
			} else {
			
				if( bypass_verify ) {
					
					console.log( 'bypassing verify' );
					
					return true;
					
				// doing this to try to keep it from firing multiple times
				} else if( verified_shipping_try == verified_shipping ) {
					
					console.log( 'already verified this string and got rates' );
					
					return false;
					
				} else {
				
					verified_shipping = verified_shipping_try;
					
					return true;
					
				}
				
			}
			
		}
		
		function getRates() {
		
			console.log( 'getRates()' );
		
			var postData = $('.shipping-info').serialize();
						
			$.ajax({
			  url: '/_cart_contents?ajax=true',
			  data: postData,
			  type: "POST"
			}).done(function(data) {
			
			    $('.cart-contents').html( data );
			    
			    $('.cart-update-notice').html('*** Shipping rates have been updated. Please review the cart to see any added costs.');
			    
			});	
			
		}
	
	</script>
	
	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	
	  ga('create', 'UA-46980026-1', 'boxworkcabinets.com');
	  ga('send', 'pageview');
	
	</script>

</body>
</html>