<h1>FAQ's</h1>

<div style='padding: 10px 0px 10px 0px; width: 80%'>

	<a href='/faq'>Add FAQ</a>

</div>

<? if( count( $controller->results ) ): ?>

<table id='default-table' class="table table-striped table-condensed">

	<thead>
    	<tr>
        	<th>Question</th>
            <th>Answer</th>
            <th>&nbsp;</th>
        </tr>
    </thead>
    
    <tbody>
    
    	<? foreach( $controller->results as $r ): ?>
                
        <tr>
        	<td><?=$r['question'] ?></td>
            <td><?=$r['answer'] ?></td>
            <td><a href='/faq?id=<?=$r['id'] ?>'>edit</a> - <a href='/delete?id=<?=$r['id'] ?>&model=faqs' onclick="return confirm( 'Are you sure?' )">delete</a></td>
        </td>
        
        <? endforeach; ?>
        
    </tbody>

</table>

<? else: ?>

	<p>No FAQ's are in the system.</p>
    
<? endif; ?>