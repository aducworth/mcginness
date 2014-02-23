<?

	$db = new DB;
	$store = new Store( $db );
	
	// handle discount codes
	if( $_POST['discount_code'] ) {
		
		$code_message = '';
		
		if( $store->applyDiscountCode( $_POST['discount_code'] ) ) {
			
			$code_message = $_POST['discount_code'] . ' has been applied to your order.';
			
		} else {
			
			$code_message = $_POST['discount_code'] . ' is not a valid discount code.';
			
		}
		
	}
	
	// handle resetting discount code
	if( $_POST['reset_discount'] ) {
	
		$store->resetDiscountCode( );
		
		$code_message = 'The discount code has been removed from your order.';
	
	}
	
	// see if we need to refresh shipping
	if( $_POST['shipping_name'] 	&& 
		$_POST['shipping_name'] 	&& 
		$_POST['shipping_address1'] &&
		$_POST['shipping_city']		&&
		$_POST['shipping_state'] 	&& 
		$_POST['shipping_zipcode'] ) {
		
		$rate = $store->getFedexRates( $_POST['shipping_name'], $_POST['shipping_name'], '', $_POST['shipping_address1'], $_POST['shipping_city'], $_POST['shipping_state'], $_POST['shipping_zipcode'], 'US', false );	
		
		//$store->getFedexRates( 'Austin Ducworth', '', '864.642.5163', '534 King Street', 'Charleston', 'SC', '29403', 'US', true ) 
		
		if( isset( $rate['rate'] ) ) {
		
			$_SESSION['shipping_rate'] = $rate['rate'];
			
		} else {
			
			$code_message = 'No rates are available for this information. Please contact Boxwork Cabinets for further instruction.';
			
			unset( $_SESSION['shipping_rate'] );
			
		}		
			
	}
	
	$db->table = 'products';
	$product_list = $db->retrieve('pair','id,name');
	
	//unset( $_SESSION['cart'] );
	
	if( $_GET['errorMsg']  ) {
	
		$code_message .= $_GET['errorMsg'];
		
	}
			
?>	

<? if( count( $_SESSION['cart'] ) > 0  ): ?>

	<? if( $code_message ): ?>
	
		<div class='notification'><?=$code_message ?></div>
	
	<? endif; ?>

	<table>
	
		<tr class='border-lower'>
			<th>Product</th>
			<th class='last'>Quantity</th>
			<th class='last'>Unit Price</th>
			<th class='last'>Total</th>
		</tr>
	
		<? 
			$total = 0; 
			$tax = 0;
			$shipping = 0;			
			$cart_index = 0;
			
		?>
	
		<? foreach( $store->product_types as $id => $type ): ?>
		
			<? if( count( $_SESSION['cart'][ $id ] ) > 0 ): ?>
				
				<? foreach( $_SESSION['cart'][ $id ] as $index => $item ): ?>
				
					<tr class='<?=( $cart_index % 2 )?'even-row':'odd-row' ?>'>
						<td>
							<p>
							
								<strong><?=$type ?>:</strong> <?=$product_list[ $item['id'] ] ?>
							
								<? if( $_GET['edit'] == 'true' ): // only allow edits on the cart page ?>
								
								 	- <a href='/select_cabinet?ajax=true&product=<?=$item['id'] ?>&edit=<?=$index ?>&product_type=<?=$id ?>' class='colorbox'>edit</a> ( <a href='/_remove_from_cart?ajax=true&remove=<?=$index ?>&product_type=<?=$id ?>' class='remove-from-cart'>x</a> )
								 	
								<? endif; ?>
								 	
							</p>
							
							<p>Details: <?=$store->wood_type_list[ $item['wood_type'] ] ?>, <?=$store->color_list[ $item['stain'] ] ?>, <?=$store->profile_list[ $item['profile'] ] ?></p>
							
							<p>Dimensions:
								
								<? if( $item['length'] ): ?>
								
									Length <?=$item['length'] ?>'
									
								<? else: ?>
								
									Width <?=$item['width'] ?>", Height <?=$item['height'] ?>"
									
									<? if( $item['depth'] ): ?>
									
										, Depth <?=$item['depth'] ?>"
										
									<? endif; ?>
								
								<? endif; ?>
								
								<input type='hidden' name='order_items[<?=$cart_index ?>][product]' value='<?=$item['id'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][product_type]' value='<?=$id ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][product_price]' value='<?=$item['product_price'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][wood_type]' value='<?=$item['wood_type'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][wood_price]' value='<?=$item['wood_price'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][stain]' value='<?=$item['stain'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][stain_price]' value='<?=$item['stain_price'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][profile]' value='<?=$item['profile'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][profile_price]' value='<?=$item['profile_price'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][width]' value='<?=$item['width'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][height]' value='<?=$item['height'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][depth]' value='<?=$item['depth'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][length]' value='<?=$item['length'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][hinge_side]' value='<?=$item['hinge_side'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][quantity]' value='<?=$item['quantity'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][price]' value='<?=$item['price'] ?>'/>
								<input type='hidden' name='order_items[<?=$cart_index ?>][subtotal]' value='<?=round( ( $item['price'] * $item['quantity'] ), 2 ) ?>'/>
							
							 </p>
							
							<? if( $item['hinge_side'] ): ?>
							
								<p>Hinge Side: <?=$item['hinge_side'] ?></p>
								
							<? endif; ?>
							
							<p>Shipping Weight: <?=$store->calculateWeight( $item['id'], $item['width'], $item['height'], $item['depth'], $item['length'] ); ?></p>
							
							<p><? // print_r( $item ) ?></p>
							
							
						</td>
						<td class='last'><?=$item['quantity'] ?></td>
						<td class='last'>$<?=number_format( $item['price'] , 2 ) ?></td>
						<td class='last'>$<?=number_format( ( $item['price'] * $item['quantity'] ), 2 ) ?></td>
					</tr>
					
					<? $total += ( $item['price'] * $item['quantity'] ); ?>
					
					<? $cart_index++; ?>
				
				<? endforeach; ?>
			
			<? endif; ?>
		
		<? endforeach; ?>
		
		<tr class='border-upper'>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th class='last'>Subtotal</th>
			<th class='last'>
			
				$<?=number_format( $total, 2 ) ?>
				
				<input type='hidden' name='product_total' value='<?=round( $total, 2 ) ?>'/>
				
			</th>
		</tr>
		
		<? if( $_SESSION['discount_code']['id'] ): ?>
		
			<? $discount = $store->calculateDiscount( $total ); ?>
		
			<tr>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th class='last'>Discount ( <?=$_SESSION['discount_code']['code'] ?>:
				
					<? if( $_SESSION['discount_code']['type'] == 'percentage' ): ?>
					
						<?=$_SESSION['discount_code']['amount'] ?>%
						
					<? else: ?>
					
						$<?=$_SESSION['discount_code']['amount'] ?>
					
					<? endif; ?>
				- <a href='#' id='reset-discount'>reset</a> )</th>
				<th class='last'>
				
					$<?=number_format( $discount, 2 ) ?>
					
					<input type='hidden' name='promo_code' value="<?=$_SESSION['discount_code']['code'] ?>"/>
					
					<input type='hidden' name='promo_amount' value="<?=round( $discount, 2 ) ?>"/>
					
				</th>
			</tr>
		
		<? endif; ?>
		
		<? $tax = $store->calculateTaxes( $total - $discount ); ?>
			
		<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th class='last'>Tax
				<? if( $store->tax_rate > 0 ): ?>
				
					( <?=$store->tax_rate ?>% )
					
				<? endif; ?>
			</th>
			<th class='last'>
				$<?=number_format( $tax, 2 ) ?>
				<input type='hidden' name='taxes' value='<?=round( $tax, 2 ) ?>'/>
			</th>
		</tr>
			
		<tr>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th class='last'>Shipping</th>
			<th id='shipping-rates' class='last'>
			
				<? //echo( 'rate: ' . $_SESSION['shipping_rate'] ); ?>
			
				<? if( $_SESSION['shipping_rate'] > 0 ): ?>
				
					<? $shipping = $_SESSION['shipping_rate']; ?>
				
					$<?=number_format( $shipping, 2 ) ?>
					
					<input type='hidden' id='shipping' name='shipping' value='<?=round( $shipping, 2 ) ?>'/>
					
				<? else: ?>
				
					<? $shipping = 0; ?>
				
					TBD
					
				<? endif; ?>
				
			</th>
		</tr>
							
		<tr class='total-row'>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th class='last total-label'>Total</th>
			<th class='last total-value'>
				$<?=number_format( ( ( $total + $tax + $shipping ) - $discount ), 2 ) ?>
				<input type='hidden' name='total' value='<?=round( ( ( $total + $tax + $shipping ) - $discount ), 2 ) ?>'/>
			</th>
		</tr>
		<tr>
			<td colspan='4' class='last shipping-note'>
				* Shipping rates are determined after shipping address is entered 
					<? if( !$_GET['edit'] ): ?>
					( <a href='#' id='refresh-rates'>refresh</a> )
					<? endif; ?>.<br>
				* Shipping time is 5-10 days via UPS Ground.
			</td>
		</tr>
	</table>
	
	<? if( $_GET['edit'] == 'true' ): ?>
	
		<p style='display: block;'><a href='/checkout' class='red-button'>Checkout</a></p>
			
	<? endif; ?>
	
	<script>
	
		$(document).ready(function(){
		
			$(".colorbox").colorbox();
			
			$('#reset-discount').click(function(e) {
			
				e.preventDefault();
				
				var postData = 'reset_discount=true';
				
				$.ajax({
				  url: '/_cart_contents?ajax=true<?=$_GET['edit']?"&edit=true":'' ?>',
				  data: postData,
				  type: "POST"
				}).done(function(data) {
				  $('.cart-contents').html( data );
				});	
				
			});
			
			$('.remove-from-cart').click(function(e) {
				
				e.preventDefault();
				
				$.ajax({
				  url: $(this).attr('href'),
				  type: "POST"
				}).done(function(data) {
				
				    $.ajax({
					  url: '/_cart_contents?ajax=true<?=$_GET['edit']?"&edit=true":'' ?>',
					  type: "POST"
					}).done(function(data) {
					  $('.cart-contents').html( data );
					});
				});
				
				//alert( $(this).attr('href') );
				
			});
			
			$('#refresh-rates').click(function(e) {
				
				e.preventDefault();
				
				if( validateShipping( true ) === true ) {
						
					getRates();
					
				} else {
					
					alert( 'Please enter all shipping info to get rates.' );
					
				}
					
			});
		
		});
		
	</script>
	
<? else: ?>

	<p>There are no items in your cart.</p>

<? endif; ?>