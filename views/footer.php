	<footer>

		<div class='container'>
	
			<ul>
				<li><a href='/contact'>Contact Us</a></li>
				<li><a href='/refunds'>Refund Policy</a></li>
				<li><a href='/about'>About Us</a></li>
				<li><a href='/privacy'>Privacy</a></li>
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
				
				<a href='#'>Email Signup</a>
			
			</div>
			
			<p class='copyright'>© 2013 Boxwork Cabinets. All rights reserved.</p>
		
		</div>
			
	</footer>
	
	<script src="/js/jquery.min.js"></script>
	<script src="/js/jquery.colorbox-min.js"></script>
	
	<script>
	
		$(document).ready(function(){
		
			$(".colorbox").colorbox();
			
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
					
					if( validateShipping() === true ) {
						
						getRates();
						
					}
					
				});
				
			<? endif; ?>
		
		});
		
		function validateShipping() {
			
			var errors = 0;
			
			$('.shipping-info').each(function( index ) {
				if( $(this).val() == '' ) {
					errors++;
				}
			    console.log( index + ": " + $( this ).val() );
			});
			
			if( errors > 0 ) {
				
				return false;
				
			} else {
				
				return true;
				
			}
			
		}
		
		function getRates() {
		
			var postData = $('.shipping-info').serialize();
						
			$.ajax({
			  url: '/_cart_contents?ajax=true',
			  data: postData,
			  type: "POST"
			}).done(function(data) {
			    $('.cart-contents').html( data );
			});	
			
		}
	
	</script>

</body>
</html>