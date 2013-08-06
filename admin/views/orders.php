<h1>Orders</h1>

<? if( count( $controller->results ) ): ?>

<table id='default-table' class="table table-striped table-condensed">

	<thead>
    	<tr>
    		<th>&nbsp;</th>
        	<th>Name</th>
            <th>Price</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    
    <tbody>
    
    	<? foreach( $controller->results as $r ): ?>
                
        <tr>
        	<td>
	        	<? if( $r['image'] ): ?>
	        		<img src='/images/uploads/thumbnails/<?=$r['image'] ?>' class='swatch'/>
	        	<? endif; ?>
        	</td>
        	<td><?=$r['name'] ?></td>
            <td><?=$r['price'] ?></td>
            <td><a href='/color?id=<?=$r['id'] ?>'>edit</a> - <a href='/delete?id=<?=$r['id'] ?>&model=colors' onclick="return confirm( 'Are you sure?' )">delete</a></td>
        </td>
        
        <? endforeach; ?>
        
    </tbody>

</table>

<? else: ?>

	<p>No orders are in the system.</p>
    
<? endif; ?>