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
						<td><?=$product_list[ $item['id'] ] ?> - <a href='/select_cabinet?ajax=true&product=<?=$item['id'] ?>&edit=<?=$index ?>&product_type=<?=$id ?>' class='colorbox'>edit</a> ( <a href='/_remove_from_cart?ajax=true&remove=<?=$index ?>&product_type=<?=$id ?>' class='remove-from-cart'>X</a> )</td>
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
			
			$('.remove-from-cart').click(function(e) {
				
				e.preventDefault();
				
				$.ajax({
				  url: $(this).attr('href'),
				  type: "POST"
				}).done(function(data) {
				
				    $.ajax({
					  url: '/_mini_cart?ajax=true',
					  type: "POST"
					}).done(function(data) {
					  $('.side-panel-cart').html( data );
					});
				});
				
				//alert( $(this).attr('href') );
				
			});
		
		});
		
	</script>
	
<? else: ?>

	<p>There are no items in your cart.</p>

<? endif; ?>