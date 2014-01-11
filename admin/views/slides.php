<h1>Slides</h1>

<div style='padding: 10px 0px 10px 0px; width: 80%'>

	<a href='/slide'>Add Slide</a>

</div>

<? if( count( $controller->results ) ): ?>

<table id='default-table' class="table table-striped table-condensed">

	<thead>
    	<tr>
    		<th>&nbsp;</th>
        	<th>Title</th>
            <th>Teaser</th>
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
        	<td><?=$r['title'] ?></td>
            <td><?=$r['teaser'] ?></td>
            <td><a href='/slide?id=<?=$r['id'] ?>'>edit</a> - <a href='/delete?id=<?=$r['id'] ?>&model=slides' onclick="return confirm( 'Are you sure?' )">delete</a></td>
        </td>
        
        <? endforeach; ?>
        
    </tbody>

</table>

<? else: ?>

	<p>No slides are in the system.</p>
    
<? endif; ?>