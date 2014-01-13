<?

	$db = new DB;
	$store = new Store( $db );
	
	$db->table = 'orders';
	
	$order = $db->retrieve( 'one', '*', " where md5( id ) = '" . $_GET['order'] . "'" );
	
	$db->table = 'order_items';
	
	$order_items = $db->retrieve( 'all', '*', " where order_id = '" . $order['id'] . "'" );
	
	$db->table = 'products';
	
	$product_list = $db->retrieve('pair','id,name');

?>

<section>

	<div class='container order-review'>

<? if( $order['id'] ): ?>
    
	    <div style='float: right;'>
	    
	    	<? if( $order['shipping'] > 0 ): ?>
	        
	        	<? if( $order['tracking_number'] ): ?>
	            
	            	<a href='http://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=<?=$order['tracking_number'] ?>' title='Track Shipment' target='_blank'>
	                
	                	<img src='http://<?=$_SERVER['SERVER_NAME'] . ( $_SERVER['SERVER_PORT']?':'.$_SERVER['SERVER_PORT']:'') ?>/images/ups_logo.png' alt='Fedex' border='0'/>
	                
	                </a>
	            
	            <? else: ?>
	        
	        		<img src='http://<?=$_SERVER['SERVER_NAME'] . ( $_SERVER['SERVER_PORT']?':'.$_SERVER['SERVER_PORT']:'') ?>/images/ups_logo.png' alt='UPS'/>
	                
	            <? endif; ?>
	        
	        <? endif; ?>
	    
	    </div>
	    
	    <h2>Order Details</h2>
        
        <div>Order Id: <a href='http://<?=$_SERVER['SERVER_NAME'] . ( $_SERVER['SERVER_PORT']?':'.$_SERVER['SERVER_PORT']:'') ?>/review?order=<?=$_GET['order'] ?>'><?=$_GET['order'] ?></a></div>
        <div>Order Date: <?=date( 'M d, Y', strtotime( $order['created'] ) ) ?></div>
        <div>Status: <?=$order['status'] ?></div>       

		<h2>Billing</h2>
		<div><?=$order['billing_name'] ?></div>
		<div><?=$order['billing_address1'] ?></div>
		<div><?=$order['billing_address2'] ?></div>
		<div><?=$order['billing_city'] ?>, <?=$order['billing_state'] ?> <?=$order['billing_zipcode'] ?></div>
		<div><?=$order['billing_phone'] ?></div>
		<div><?=$order['billing_email'] ?></div>
		
		<h2>Shipping</h2>
		<div><?=$order['shipping_name'] ?></div>
		<div><?=$order['shipping_address1'] ?></div>
		<div><?=$order['shipping_address2'] ?></div>
		<div><?=$order['shipping_city'] ?>, <?=$order['shipping_state'] ?> <?=$order['shipping_zipcode'] ?></div>
		<div><?=$order['shipping_phone'] ?></div>
		
		<h2>Purchased Items</h2>
		
		<div class='cart-contents'>

			<table>
			
				<tr>
					<th>Product</th>
					<th class='last'>Quantity</th>
					<th class='last'>Unit Price</th>
					<th class='last'>Total</th>
				</tr>
			
				<? foreach( $order_items as $item ): ?>
				
					<tr>
						<td>
							<p>
							
								<strong><?=$store->product_types[ $item['product_type'] ] ?>:</strong> <?=$product_list[ $item['product'] ] ?>
															 	
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
							
							<p>Shipping Weight: <?=$store->calculateWeight( $item['product'], $item['width'], $item['height'], $item['depth'], $item['length'] ); ?></p>
							
							<p><? // print_r( $item ) ?></p>
							
							
						</td>
						<td class='last'><?=$item['quantity'] ?></td>
						<td class='last'>$<?=number_format( $item['price'] , 2 ) ?></td>
						<td class='last'>$<?=number_format( $item['subtotal'], 2 ) ?></td>
					</tr>
				
				<? endforeach; ?>
			        
			    <tr>
			    
			    	<th>&nbsp;</th>
			        <th>&nbsp;</th>
			        <th>Total Price</th>
			        <th class='last'>$<?=number_format( $order['product_total'], 2 ) ?></th>
			    
			    </tr>
			    <tr>
			    
			    	<th>&nbsp;</th>
			        <th>&nbsp;</th>
			        <th>Shipping</th>
			        <th class='last'>$<?=number_format( $order['shipping'], 2 ) ?></th>
			    
			    </tr>
			    <tr>
			    
			    	<th>&nbsp;</th>
			        <th>&nbsp;</th>
			        <th>Tax</th>
			        <th class='last'>$<?=number_format( $order['taxes'], 2 ) ?></th>
			    
			    </tr>
			    <? if( $order['promo_amount'] > 0 ): ?>
			    <tr>
			    	<th>&nbsp;</th>
			        <th>&nbsp;</th>
			        <th>Promo Code ( <?=$order['promo_code'] ?> ) </th>
			        <th class='last'>
			        	$<?=number_format( $order['promo_amount'], 2 ) ?>
			        </th>
			    
			    </tr>
			    <? endif; ?>
			    <tr>
			    
			    	<th>&nbsp;</th>
			        <th>&nbsp;</th>
			        <th>Grand Total</th>
			        <th class='last'>$<?=number_format( $order['total'], 2 )?></th>
			    
			    </tr>
			
			</table>
			
		</div>

<? else: ?>

	Invalid Order Number
    
<? endif; ?>

	</div>
	
</section>