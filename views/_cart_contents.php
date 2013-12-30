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
	
	$db->table = 'products';
	$product_list = $db->retrieve('pair','id,name');
	
	//unset( $_SESSION['cart'] );
			
?>	

<? if( count( $_SESSION['cart'] ) > 0  ): ?>

	<? if( $code_message ): ?>
	
		<div class='notification'><?=$code_message ?></div>
	
	<? endif; ?>

	<table>
	
		<tr>
			<th>Product</th>
			<th class='last'>Quantity</th>
			<th class='last'>Unit Price</th>
			<th class='last'>Total</th>
		</tr>
	
		<? 
			$total = 0; 
			$tax = 0;
			$shipping = 0;
			
		?>
	
		<? foreach( $store->product_types as $id => $type ): ?>
		
			<? if( count( $_SESSION['cart'][ $id ] ) > 0 ): ?>
				
				<? foreach( $_SESSION['cart'][ $id ] as $index => $item ): ?>
				
					<tr>
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
							
							 </p>
							
							<? if( $item['hinge_side'] ): ?>
							
								<p>Hinge Side: <?=$item['hinge_side'] ?></p>
								
							<? endif; ?>
							
							<p><? //print_r( $item ) ?></p>
							
							
						</td>
						<td class='last'><?=$item['quantity'] ?></td>
						<td class='last'>$<?=number_format( $item['price'] , 2 ) ?></td>
						<td class='last'>$<?=number_format( ( $item['price'] * $item['quantity'] ), 2 ) ?></td>
					</tr>
					
					<? $total += ( $item['price'] * $item['quantity'] ); ?>
				
				<? endforeach; ?>
			
			<? endif; ?>
		
		<? endforeach; ?>
		
		<tr>
			<th>Subtotal</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th class='last'>$<?=number_format( $total, 2 ) ?></th>
		</tr>
		
		<? if( $_SESSION['discount_code']['id'] ): ?>
		
			<? $discount = $store->calculateDiscount( $total ); ?>
		
			<tr>
				<th>Discount ( <?=$_SESSION['discount_code']['code'] ?>:
				
					<? if( $_SESSION['discount_code']['type'] == 'percentage' ): ?>
					
						<?=$_SESSION['discount_code']['amount'] ?>%
						
					<? else: ?>
					
						$<?=$_SESSION['discount_code']['amount'] ?>
					
					<? endif; ?>
				- <a href='#' id='reset-discount'>reset</a> )</th>
				<th>&nbsp;</th>
				<th>&nbsp;</th>
				<th class='last'>$<?=number_format( $discount, 2 ) ?></th>
			</tr>
		
		<? endif; ?>
			
		<tr>
			<th>Tax</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th class='last'>$<?=number_format( $tax, 2 ) ?></th>
		</tr>
			
		<tr>
			<th>Shipping</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th class='last'>$<?=number_format( $shipping, 2 ) ?></th>
		</tr>
							
		<tr>
			<th>Total</th>
			<th>&nbsp;</th>
			<th>&nbsp;</th>
			<th class='last'>$<?=number_format( ( ( $total + $tax + $shipping ) - $discount ), 2 ) ?></th>
		</tr>
	</table>
	
	<? if( $_GET['edit'] == 'true' ): ?>
	
		<a href='/checkout' class='red-button'>Checkout</a>
			
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
					  url: '/_cart_contents?ajax=true',
					  type: "POST"
					}).done(function(data) {
					  $('.cart-contents').html( data );
					});
				});
				
				//alert( $(this).attr('href') );
				
			});
		
		});
		
	</script>
	
<? else: ?>

	<p>There are no items in your cart.</p>

<? endif; ?>