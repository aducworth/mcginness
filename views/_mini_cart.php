<?

	$db = new DB;
	$store = new Store( $db );
	
	$db->table = 'products';
	$product_list = $db->retrieve('pair','id,name');
	
	//unset( $_SESSION['cart'] );
			
?>	

<h2>Your Cart</h2>

<? if( count( $_SESSION['cart'] ) > 0  ): ?>

	<table>
	
		<? $total = 0; ?>
	
		<? foreach( $store->product_types as $id => $type ): ?>
		
			<? if( count( $_SESSION['cart'][ $id ] ) > 0 ): ?>
		
				<tr>
					<th colspan="3"><?=$type ?></th>
				</tr>
				
				<? foreach( $_SESSION['cart'][ $id ] as $index => $item ): ?>
				
					<tr>
						<td><?=$item['quantity'] ?></td>
						<td><?=$product_list[ $item['id'] ] ?> - <a href='/select_cabinet?ajax=true&product=<?=$item['id'] ?>&edit=<?=$index ?>&product_type=<?=$id ?>' class='colorbox'>edit</a></td>
						<td>$<?=number_format( ( $item['price'] * $item['quantity'] ), 2 ) ?></td>
					</tr>
					
					<? $total += ( $item['price'] * $item['quantity'] ); ?>
				
				<? endforeach; ?>
			
			<? endif; ?>
		
		<? endforeach; ?>
									
		<tr>
			<td>Total</td>
			<td>&nbsp;</td>
			<td>$<?=number_format( $total, 2 ) ?></td>
		</tr>
	</table>
	
	<a href='/cart' class='red-button'>Checkout</a>
	
	<script>
	
		$(document).ready(function(){
		
			$(".colorbox").colorbox();
		
		});
		
	</script>
	
<? else: ?>

	<p>There are no items in your cart.</p>

<? endif; ?>