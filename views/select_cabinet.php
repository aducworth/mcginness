<?

	$db = new DB;
		
	$db->table = 'products';
	
	$product = $db->retrieve('one','*',' where id = ' . $_GET['product'] );
	
	$end_panels = $db->retrieve('all','*',' where product_type = 4 and product_subcategory = 5');
	
	$shelf = $db->retrieve('one','*',' where product_type = 4 and product_subcategory = 6');
	
	if( isset( $_GET['edit'] ) ) {
		
		$edit_info = $_SESSION['cart'][ $_GET['product_type'] ][ $_GET['edit'] ];
		
		//print_r( $edit_info );
	}
	
?>	

<div class='ordering-options'>

	<? if( $product['image'] ): ?>
								
		<img src='/images/uploads/resize/<?=$product['image'] ?>'/>
		
	<? else: ?>
	
		<img src="/images/cabinet-larger.jpg">
	
	<? endif; ?>
	
	<form action='/save_selection?ajax=true' method='post' class='select-cabinet-options'>
		
		<h1><?=$product['name'] ?></h1>
		
		<? if( $_SESSION['wood-type'] && $_SESSION['stain-color'] && $_SESSION['drawer-and-door'] ): ?>
		
			<? 
			
				$db->table = 'wood_types';
	
				$wood_type = $db->retrieve( 'one','*',' where id = ' . ($edit_info['wood_type']?$edit_info['wood_type']:$_SESSION['wood-type']) );
				
				$db->table = 'colors';
				
				$color = $db->retrieve('one','*',' where id = ' . ($edit_info['stain']?$edit_info['stain']:$_SESSION['stain-color']) );
				
				$db->table = 'profiles';
				
				$profile = $db->retrieve('one','*',' where id = ' . ($edit_info['profile']?$edit_info['profile']:$_SESSION['drawer-and-door']) );

			?>
			
			<input type='hidden' name='id' value='<?=$product['id'] ?>'/>
			
			<? if( isset( $_GET['edit'] ) ): ?>
			
				<input type='hidden' name='edit' value='<?=$_GET['edit'] ?>'/>
				
			<? endif; ?>
			
			<input type='hidden' name='product_price' class='product-price' value='<?=$product['price'] ?>'/>
			<input type='hidden' name='wood_type' value='<?=$wood_type['id'] ?>'/>
			<input type='hidden' name='wood_price' class='wood-price' value='<?=$wood_type['price'] ?>'/>
			<input type='hidden' name='stain' value='<?=$color['id'] ?>'/>
			<input type='hidden' name='stain_price' class='stain-price' value='<?=$color['price'] ?>'/>
			<input type='hidden' name='profile' value='<?=$profile['id'] ?>'/>
			<input type='hidden' name='profile_price' class='profile-price' value='<?=$profile['price'] ?>'/>
			
			<input type='hidden' class='width-limit' value='<?=$product['width_limit'] ?>'/>
			<input type='hidden' class='height-limit' value='<?=$product['height_limit'] ?>'/>
			<input type='hidden' class='depth-limit' value='<?=$product['depth_limit'] ?>'/>
			<input type='hidden' class='length-limit' value='<?=$product['length_limit'] ?>'/>
			
			<input type='hidden' class='width-lower' value='<?=$product['width_lower'] ?>'/>
			<input type='hidden' class='height-lower' value='<?=$product['height_lower'] ?>'/>
			<input type='hidden' class='depth-lower' value='<?=$product['depth_lower'] ?>'/>
			<input type='hidden' class='length-lower' value='<?=$product['length_lower'] ?>'/>
			
			<!-- <p>Product Price: <?=$product['price'] ?> / cubic ft</p> -->
			<p><strong>Wood Type:</strong> <?=$wood_type['name'] ?> ( $<?=$wood_type['price'] ?> / sqft )</p>
			<p><strong>Stain Color:</strong> <?=$color['name'] ?> ( $<?=$color['price'] ?> / sqft )</p>
			<!-- <p>Profile: <?=$profile['name'] ?> ( <?=$profile['price'] ?> / sqft of front )</p> -->
	
			<div class='selection-row'>
			
				<div class='errors'></div>
				
				<? if( !$product['choose_length'] ): ?>
			
					<div class='selection-row-half'>
					
						<label>Quantity</label>
						
						<div class='quantity-selector'>
							<input type='hidden' name='quantity' class='quantity-value quantity-selector-value' value='<?=$edit_info['quantity']?$edit_info['quantity']:1 ?>'/>
							<input type='hidden' class='quantity-increment' value='1'/>
							<div class='quantity-display'><?=$edit_info['quantity']?$edit_info['quantity']:1 ?></div>
							<a href='#' class='increase'>+</a>
							<a href='#' class='decrease'>-</a>
						</div>
					
					</div>
				
				<? else: ?>
			
					<div class='selection-row-half'>
						
						<label>* Length ( ft )</label>
						
						<div class='quantity-selector'>
							<input type='hidden' name='length' class='quantity-selector-value length-value calculate-new-price' value='<?=$edit_info['length']?$edit_info['length']:0 ?>'/>
							<input type='hidden' class='quantity-increment' value='4'/>
							<div class='quantity-display'><?=$edit_info['length']?$edit_info['length']:0 ?></div>
							<a href='#' class='increase'>+</a>
							<a href='#' class='decrease'>-</a>
						</div>
						
					
					</div>
					
					<p>* Sold in 4 and 8 ft sections.</p>
					
					<input type='hidden' name='width' class='width-value' value='<?=$product['product_width'] ?>'/>
					<input type='hidden' name='quantity' class='quantity-value' value='1'/>
				
				<? endif; ?>
				
			</div>
			
			<? if( $product['choose_dimensions'] ): ?>
			
			<div class='dimensions-row'>
			
				<p>Please select the <? if( !( $product['product_type'] == 4 && $product['product_subcategory'] == 5 )  ): ?>width, height and depth<? else: ?>width and height<?endif; ?> dimmensions of your cabinets in inches.</p>
			
				<label>Width</label>
				
				<input type='text' name='width' class='width-value calculate-new-price' value='<?=$edit_info['width']?$edit_info['width']:0 ?>'/>
				
				<label>Height</label>
				
				<? if( ( $product['product_type'] == 4 && $product['product_subcategory'] == 6 )  ): ?>
				
					<input type='text' name='height' class='height-value' value='<?=$product['product_height'] ?>' disabled='true'/>
					
					<input type='hidden' name='height' class='height-value' value='<?=$product['product_height'] ?>'/>				
				
				<? else: ?>
								
					<input type='text' name='height' class='height-value calculate-new-price' value='<?=$edit_info['height']?$edit_info['height']:0 ?>'/>
					
				<? endif; ?>
				
				<? if( !( $product['product_type'] == 4 && $product['product_subcategory'] == 5 )  ): ?>
				
				<label>Depth</label>
				
				<input type='text' name='depth' class='depth-value calculate-new-price' value='<?=$edit_info['depth']?$edit_info['depth']:0 ?>'/>
				
				<? endif; ?>
			
			</div>
			
			<? endif; ?>
			
			<div class='selection-row'>
			
				<? if( $product['choose_door_side'] ): ?>
				
				<div class='selection-row-half'>
				
					<p>Side of hinge:</p>
					
					<div class='eitheror-selector'>
						<input type='hidden' name='hinge_side' class='eitheror-value' value='<?=$edit_info['hinge_side']?$edit_info['hinge_side']:'Left'; ?>'/>
						<a href='#' class='either <?=( $edit_info['hinge_side'] == 'Right' )?'':'selected'; ?>' data-value='Left'>Left</a>
						<a href='#' class='or <?=( $edit_info['hinge_side'] == 'Right' )?'selected':''; ?>' data-value='Right'>Right</a>
						<span class='ordisplay'>Or</span>
					</div>
				
				</div>
				
				<? endif; ?>
				
				<div class='selection-row-half'>
				
					<? if( $product['choose_side_panels'] && count( $end_panels ) > 0 ): ?>
					
						<p>Add End Panel?</p>
						
						<select name='add_end_panel' class='add-end-panel'>
							
							<option value='0'> ( No End Panel ) </option>
							
							<? foreach( $end_panels as $ep ): ?>
							
								<option value='<?=$ep['id'] ?>'><?=$ep['name'] ?> ( Enter dimensions for price )</option>
							
							<? endforeach; ?>
							
						</select>
						
						<select name='end_panel_quantity' class='quantity end-panel-quantity'>
						
							<option value='0'>Qty</option>
							
							<? for( $i=1; $i < 3;$i++ ): ?>
							
								<option value='<?=$i ?>'><?=$i ?></option>
							
							<? endfor; ?>
							
						</select>			
					
				    <? endif; ?>
				    				
				</div>
				
			</div>
			
			 <? if( $product['choose_shelves'] && $shelf['id'] ): ?>
				    
		    	<div class='dimensions-row'>
		    	
		    		<input type='checkbox' name='add_shelves' class='add-shelves checkbox' value='1'/>
		    		
		    		<label class='checkbox'>Add <span class='shelf-info'><?=$shelf['name'] ?> ( Enter dimensions for pricing )</span> ?</label>
		    		
		    		<input type='hidden' name='shelf_value' class='shelf-value' value='<?=$shelf['id'] ?>'/>
		    		
		    		<select name='shelf_quantity' class='quantity shelf-quantity'>
						
						<option value='0'>Qty</option>
						
						<? for( $i=1; $i < 3;$i++ ): ?>
						
							<option value='<?=$i ?>'><?=$i ?></option>
						
						<? endfor; ?>
						
					</select>	
		    		
		    	</div>
		    	
			<? endif; ?>
			
			<div class='selection-row'>
			
				<hr>
				
				<div class='calc-results'>
				
				</div>
			
			</div>
		
			<div class='selection-row'>
			
				<button class='red-button'>Save</button>
				
			</div>
			
			<div class='calculated-price'>
			
				Unit Price: <span class='price'>$0.00</span>
				
				<span class='details'>* Enter dimensions to calculate pricing</span>
			
			</div>
		
		<? else: ?>
		
			<p>Please enter drawer and door profile, wood type, and stain color in Step 1 to add items to your cart.</p>
		
		<? endif; ?>
	
	</form>

</div>

<script>

	$(document).ready(function(){
	
		$('.increase').click(function(e){
			
			e.preventDefault();
			
			var quantity_value = $(this).parent().find('.quantity-selector-value');
			var increment_value = $(this).parent().find('.quantity-increment').val();
			var new_quantity = ( parseInt( quantity_value.val() ) + parseInt( increment_value ) );
			
			quantity_value.val( new_quantity );
			$(this).parent().find('.quantity-display').html( new_quantity );
			
			calculate_new_price();
			
		});
		
		
		$('.decrease').click(function(e){
			
			e.preventDefault();
			
			var quantity_value = $(this).parent().find('.quantity-selector-value');
			var increment_value = $(this).parent().find('.quantity-increment').val();
			var new_quantity = ( parseInt( quantity_value.val() ) - parseInt( increment_value ) );
			
			if( new_quantity >= 0 ) {
				
				quantity_value.val( new_quantity );
				$(this).parent().find('.quantity-display').html( new_quantity );
				
				calculate_new_price();
			
			}
			
		});
		
		$('.either, .or').click(function(e){
			
			e.preventDefault();
			
			if( !$(this).hasClass('selected') ) {
			
				var new_value = $(this).attr('data-value');
				
				$(this).parent().find('.selected').removeClass('selected');
				$(this).addClass('selected');
				
				$(this).parent().find('.eitheror-value').val( new_value );
				
			}
			
		});
		
		$('.calculate-new-price').change(function(){
		
			calculate_new_price();
			
		});
		
		$('.calculate-new-price-click').click(function(){
		
			calculate_new_price();
			
		});
		
		function calculate_new_price() {
		
			if( validatePriceCalc() ) {
			
				var postData = $('.select-cabinet-options').serialize();
				
				$.ajax({
				  url: '/_calculate_price?ajax=true',
				  data: postData,
				  type: "POST"
				}).done(function(data) {
				
				  $('.calculated-price span.price').html( data );
				  
				  // see if end panels are an option here and calculate the price if they are
				  if( $('.add-end-panel').length > 0 || $('.add-shelves').length > 0 ) {
				  
				  	
					  
					    var postData = 'width=' + $('.width-value').val() + '&height=' + $('.height-value').val() + '&depth=' + $('.depth-value').val() + '&wood_price=' + $('.wood-price').val() + '&stain_price=' + $('.stain-price').val();
					    
					     
					    $('.add-end-panel option').each(function(index){
					    
					    	if( $(this).attr('value') != 0 ) {
					    	
					    		postData += '&option[]=' + $(this).attr('value');
					    		
					    	}
					    
					    });
					    
					    var shelf = $('.shelf-value').val();
					    
					    if( shelf ) {
					    
						    // add in pull out shelf
						    postData += '&option[]=' + shelf;
						    
						}
				
						$.ajax({
						  url: '/_calculate_accessories_price?ajax=true',
						  data: postData,
						  type: "POST"
						}).done(function(data) {
							
							var option_prices = jQuery.parseJSON( data );
							
							$('.add-end-panel option').each(function(index){
							
								if( $(this).attr('value') != 0 ) {
								
									var info = option_prices[$(this).attr('value')];
							
									$(this).html( info );
									
								}
							
							});														
							
							if( shelf ) {
								
								$('.shelf-info').html( option_prices[ shelf ] );
								
							}
							
					    });
					  
				  }
				  
				});
			
			}
			
			//alert( 'calc price' );
			
		}
		
		function validateForm() {
			
			var tovalidate = new Array('width','height','depth','length');
			
			// validate dimensions
			for( var i=0; i<tovalidate.length; i++ ) {
				
				var size 	= parseInt( $('.'+tovalidate[i]+'-value').val() );
				var limit 	= parseInt( $('.'+tovalidate[i]+'-limit').val() );
				var lower 	= parseInt( $('.'+tovalidate[i]+'-lower').val() );
				
				if( $('.'+tovalidate[i]+'-value').length > 0 ) {
				
					//alert( )
				
					if( isNaN( size ) ) {
						
						$('.errors').html( '<p>' + ucfirst( tovalidate[i] ) + ' is not a valid number.</p>' );
						$.colorbox.resize();
						return false;
	
					} else if( limit > 0 && size > 0 && size > limit ) {
						
						$('.errors').html( '<p>' + ucfirst( tovalidate[i] ) + ' has a limit of ' + limit + ' for this product.</p>' );
						$.colorbox.resize();
						return false;
						
					} else if( lower > 0 && size > 0 && size < lower ) {
						
						$('.errors').html( '<p>' + ucfirst( tovalidate[i] ) + ' has a minimum of ' + lower + ' for this product.</p>' );
						$.colorbox.resize();
						return false;
						
					} else if( size <= 0 ) {
						
						$('.errors').html( '<p>Please enter a value for ' + ucfirst( tovalidate[i] ) + '.</p>' );
						$.colorbox.resize();
						return false;
						
					}
				
				}
				
			}
			
			// validate quantity if end panel is chosen
			if( $('.add-end-panel').length > 0 && $('.add-end-panel').val() > 0 && $('.end-panel-quantity').val() <= 0 ) {
			
				$('.errors').html( '<p>Please enter a quantity for end panels or choose no end panel.</p>' );
				$.colorbox.resize();
				return false;				
				
			}
			
			if( $('.add-shelves').is(':checked') && $('.shelf-quantity').val() <= 0 ) {
				
				$('.errors').html( '<p>Please enter a quantity for shelves or uncheck the box to add shelves.</p>' );
				$.colorbox.resize();
				return false;
			}
			
			// make sure a quantity has been chosen
			var quantity = parseInt( $('.quantity-value').val() );
			
			if( quantity <= 0 ) {
				
				$('.errors').html( '<p>Please choose a quantity.</p>' );
				$.colorbox.resize();
				return false;
				
			}
			
			$('.errors').html( '' );
			
			$.colorbox.resize();
			
			return true;

		}
		
		function validatePriceCalc() {
		
			var tovalidate = new Array('width','height','depth','length');
			
			for( var i=0; i<tovalidate.length; i++ ) {
				
				var size 	= parseInt( $('.'+tovalidate[i]+'-value').val() );
				var limit 	= parseInt( $('.'+tovalidate[i]+'-limit').val() );
				var lower 	= parseInt( $('.'+tovalidate[i]+'-lower').val() );
				
				if( size ) {
				
					if( isNaN( size ) ) {
						
						$('.errors').html( '<p>' + ucfirst( tovalidate[i] ) + ' is not a valid number.</p>' );
						return false;
	
					} else if( limit > 0 && size > 0 && size > limit ) {
						
						$('.errors').html( '<p>' + ucfirst( tovalidate[i] ) + ' has a limit of ' + limit + ' for this product.</p>' );
						return false;
						
					} else if( lower > 0 && size > 0 && size < lower ) {
						
						$('.errors').html( '<p>' + ucfirst( tovalidate[i] ) + ' has a minimum of ' + lower + ' for this product.</p>' );
						return false;
						
					}
				
				}
				
			}
			
			$('.errors').html( '' );
			
			$.fn.colorbox.resize({});
			
			return true;
			
		}
		
		function ucfirst(str) {
		  str += '';
		  var f = str.charAt(0).toUpperCase();
		  return f + str.substr(1);
		}
		
		$('.select-cabinet-options').submit(function(e){
		
			e.preventDefault();
			
			if( validateForm() ) {
			
				var postData = $(this).serialize();
				
				$.ajax({
				  url: '/_add_to_cart?ajax=true',
				  data: postData,
				  type: "POST"
				}).done(function(data) {
				
					//alert( data );
				    $.fn.colorbox.close();
				});
			
			}
					
		});
		
		<? if( isset( $_GET['edit'] ) ): ?>
			
			calculate_new_price();
		
		<? endif; ?>
	
	});

</script>