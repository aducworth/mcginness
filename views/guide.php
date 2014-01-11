<?

	$db = new DB;
		
	$db->table = 'slides';
	
	$slides = $db->retrieve('all','*',' where is_active=1 order by display_order');
		
?>	
<div class='welcome'>
			
	<h1>Inspiration Gallery</h1>
	
	<hr>
	
	<h2>View Our Gallery to Get Inspiration and Help Answer Questions</h2>

</div>

<section>
	
	<div class='container'>
	
		<? if( count( $slides ) ): ?>
	
		<ul class='gallery'>
		
				<? foreach( $slides as $slide ): ?>
				
				<li>
					<a href='/_gallery_detail?ajax=true&id=<?=$slide['id'] ?>' class='gallery-item'>
						<span class='gallery-title'><?=$slide['title'] ?></span>
						<img src='/images/uploads/thumbnails/<?=$slide['image'] ?>'>
						<span class='gallery-teaser'><?=$slide['teaser'] ?></span>
					</a>
				</li>
				
				<? endforeach; ?>
			
		</ul>
		
		<? else: ?>
		
			<p>Nothing has been added to the gallery yet.</p>
		
		<? endif; ?>
		
	</div>
		
</section>
