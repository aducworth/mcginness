<?

	$db = new DB;
		
	$db->table = 'slides';
	
	$slide = $db->retrieve('one','*',' where id = ' . $_GET['id'] );	
	
?>	
<div class='gallery-detail'>

	<img src='/images/uploads/resize/<?=$slide['image'] ?>'/>
	
	<div class='gallery-detail-description'>
		
		<h1><?=$slide['title'] ?></h1>
		
		<p><?=$slide['description'] ?></p>

		<? $bullet_points = explode( "\n", $slide['bullet_points'] ); ?>
		
		<ul>
		
			<? foreach( $bullet_points as $bp ): ?>
							
				<li><?=$bp ?></li>
				
			<? endforeach; ?>

		</ul>
		
	</div>
	
</div>