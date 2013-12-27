<?

	$db = new DB;
	$store = new Store( $db );
	
	$db->table = 'products';
	$product_list = $db->retrieve('pair','id,name');
	
	//unset( $_SESSION['cart'] );
			
?>	

<? if( count( $_SESSION['cart'] ) > 0  ): ?>

	<table>
	
		<tr>
			<th>Product</th>
			<th>Quantity</th>
			<th>Unit Price</th>
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
							<p><strong><?=$type ?>:</strong> <?=$product_list[ $item['id'] ] ?></p>
							
							<p>Details: <?=$store->wood_type_list[ $item['wood_type'] ] ?>, <?=$store->color_list[ $item['stain'] ] ?>, <?=$store->profile_list[ $item['profile'] ] ?></p>
							
							<p>Dimensions: Width <?=$item['width'] ?>", Height <?=$item['height'] ?>", Depth <?=$item['depth'] ?>"</p>
							
							<? if( $item['hinge-side'] ): ?>
							
								<p>Hinge Side: <?=$item['hinge-side'] ?></p>
								
							<? endif; ?>
							
							<p><? print_r( $item ) ?></p>
							
							
						</td>
						<td><?=$item['quantity'] ?></td>
						<td>$<?=number_format( $item['price'] , 2 ) ?></td>
						<td class='last'>$<?=number_format( ( $item['price'] * $item['quantity'] ), 2 ) ?></td>
					</tr>
					
					<? $total += ( $item['price'] * $item['quantity'] ); ?>
				
				<? endforeach; ?>
			
			<? endif; ?>
		
		<? endforeach; ?>
			
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
			<th class='last'>$<?=number_format( $total, 2 ) ?></th>
		</tr>
	</table>
	
	<a href='/checkout' class='red-button'>Checkout</a>
	
	<script>
	
		$(document).ready(function(){
		
			$(".colorbox").colorbox();
		
		});
		
	</script>
	
<? else: ?>

	<p>There are no items in your cart.</p>

<? endif; ?>