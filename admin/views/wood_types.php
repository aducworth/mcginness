<h1>Wood Types</h1>

<div style='padding: 10px 0px 10px 0px; width: 80%'>

	<a href='/wood_type'>Add Wood Types</a>

</div>

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
	        		<img src='<?=$controller->site_url ?>/images/uploads/thumbnails/<?=$r['image'] ?>' class='swatch'/>
	        	<? endif; ?>
        	</td>
        	<td><?=$r['name'] ?></td>
            <td><?=$r['price'] ?></td>
            <td><a href='/wood_type?id=<?=$r['id'] ?>'>edit</a> - <a href='/delete?id=<?=$r['id'] ?>&model=wood_types' onclick="return confirm( 'Are you sure?' )">delete</a></td>
        </td>
        
        <? endforeach; ?>
        
    </tbody>

</table>

<? else: ?>

	<p>No wood types are in the system.</p>
    
<? endif; ?>