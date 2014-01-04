<h1>Orders</h1>

<? if( count( $controller->results ) ): ?>

<table id='default-table' class="table table-striped table-condensed">

	<thead>
    	<tr>
        	<th>Name</th>
        	<th>Status</th>
        	<th>Date</th>
        	<th>Tax</th>
        	<th>Shipping</th>
            <th>Total</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    
    <tbody>
    
    	<? 
    		$taxes = 0; 
			$shipping = 0; 
    		$total = 0; 
    	?>
    
    	<? foreach( $controller->results as $r ): ?>
                
        <tr>
        	<td><?=$r['billing_name'] ?></td>
        	<td><?=$r['status'] ?></td>
        	<td><?=date( 'm/d/Y g:ia', strtotime( $r['created'] ) ) ?></td>
        	<td>$<?=number_format( $r['taxes'], 2 ) ?></td>
        	<td>$<?=number_format( $r['shipping'], 2 ) ?></td>
            <td>$<?=number_format( $r['total'], 2 ) ?></td>
            <td>
            	<a href='/order?id=<?=$r['id'] ?>'>edit</a> - 
            	<a href='<?=$controller->site_url ?>/review?order=<?=md5( $r['id'] ) ?>' target='_blank'>view</a>
            </td>
        </td>
        
        <? 
        	$taxes += $r['taxes']; 
        	$shipping += $r['shipping']; 
        	$total += $r['total']; 
        	
        ?>
        
        <? endforeach; ?>
        
        <tr>
        	<td>Total</td>
        	<td>&nbsp;</td>
        	<td>&nbsp;</td>
        	<td>$<?=number_format( $taxes, 2 ) ?></td>
        	<td>$<?=number_format( $shipping, 2 ) ?></td>
            <td>$<?=number_format( $total, 2 ) ?></td>
            <td>&nbsp;</td>
        </td>
        
    </tbody>

</table>

<? else: ?>

	<p>No orders are in the system.</p>
    
<? endif; ?>