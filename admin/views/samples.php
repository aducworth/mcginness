<h1>Products</h1>

<div style='padding: 10px 0px 10px 0px; width: 80%'>

	<a href='/product'>Add Product</a>

</div>

<? if( count( $controller->results ) ): ?>

<table id='default-table' class="table table-striped table-condensed">

	<thead>
    	<tr>
    		<th>&nbsp;</th>
        	<th>Name</th>
            <th>Category</th>
            <th>Sub Category</th>
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
            <td><?=$controller->product_types[ $r['product_type'] ] ?></td>
            <td><?=$controller->product_subcategories[ $r['product_type'] ][ $r['product_subcategory'] ] ?></td>
            <td><a href='/product?id=<?=$r['id'] ?>'>edit</a> - <a href='/delete?id=<?=$r['id'] ?>&model=products' onclick="return confirm( 'Are you sure?' )">delete</a></td>
        </td>
        
        <? endforeach; ?>
        
    </tbody>

</table>

<? else: ?>

	<p>No products are in the system.</p>
    
<? endif; ?>