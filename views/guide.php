<div class='welcome'>
			
	<h1>Inspiration Gallery</h1>
	
	<hr>
	
	<h2>View Our Gallery to Get Inspiration and Help Answer Questions</h2>

</div>

<section>
	
	<div class='container'>
	
		<ul class='gallery'>
		
			<? for( $i=0;$i<10;$i++ ): ?>
			
			<li>
				<a href='/_gallery_detail?ajax=true' class='gallery-item'>
					<span class='gallery-title'>Title of Gallery</span>
					<img src='/images/gallery_thumb.jpg'>
					<span class='gallery-teaser'>This is a brief description of the image and a teaser.</span>
				</a>
			</li>
			
			<? endfor; ?>
			
		</ul>
		
	</div>
		
</section>
