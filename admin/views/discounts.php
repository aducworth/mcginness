<h1>Discounts</h1>

<div style='padding: 10px 0px 10px 0px; width: 80%'>

	<a href='/discount'>Add Discount</a>

</div>

<? if( count( $controller->results ) ): ?>

<table id='default-table' class="table table-striped table-condensed">

	<thead>
    	<tr>
        	<th>Name</th>
        	<th>Type</th>
            <th>Price</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    
    <tbody>
    
    	<? foreach( $controller->results as $r ): ?>
                
        <tr>
        	<td><?=$r['code'] ?></td>
        	<td><?=ucwords( $r['type'] ) ?></td>
            <td><?=$r['amount'] ?></td>
            <td><a href='/discount?id=<?=$r['id'] ?>'>edit</a> - <a href='/delete?id=<?=$r['id'] ?>&model=discounts' onclick="return confirm( 'Are you sure?' )">delete</a></td>
        </td>
        
        <? endforeach; ?>
        
    </tbody>

</table>

<? else: ?>

	<p>No discounts are in the system.</p>
    
<? endif; ?>