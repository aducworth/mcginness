<h1>Colors</h1>

<div style='padding: 10px 0px 10px 0px; width: 80%'>

	<a href='/color'>Add Color</a>

</div>

<? if( count( $controller->results ) ): ?>

<table id='default-table' class="table table-striped table-condensed">

	<thead>
    	<tr>
    		<th>&nbsp;</th>
        	<th>Name</th>
            <th>Price</th>
            <th>Price</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    
    <tbody>
    
    	<? foreach( $controller->results as $r ): ?>
                
        <tr>
        	<td>
        	
        		<div style='border-radius: 50px; width: 100px; height: 100px; background: 
	        	<? if( $r['image'] ): ?>
	        		url(<?=$controller->site_url ?>/images/uploads/thumbnails/<?=$r['image'] ?>);
	        	<? elseif( $r['hex_index'] ): ?>
        			<?=$r['hex_index'] ?>
	        	<? endif; ?>
	        		'>
	        	</div>
        	</td>
        	<td><?=$r['name'] ?></td>
            <td><?=$r['price'] ?></td>
            <td><?=$r['is_active']?'Yes':'No' ?></td>
            <td><a href='/color?id=<?=$r['id'] ?>'>edit</a> - <a href='/delete?id=<?=$r['id'] ?>&model=colors' onclick="return confirm( 'Are you sure?' )">delete</a></td>
        </td>
        
        <? endforeach; ?>
        
    </tbody>

</table>

<? else: ?>

	<p>No colors are in the system.</p>
    
<? endif; ?>