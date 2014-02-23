<h3>Stain Color</h3>

<? if( isset( $_POST['id'] ) ) {
		
		$_SESSION['stain-color'] = $_POST['id'];
		
	}
	
	if( isset( $_SESSION['stain-color'] ) ): ?>
	
		<?

			$db = new DB;
			
			$db->table = 'colors';
			
			$color = $db->retrieve('one','*',' where id = ' . $_SESSION['stain-color'] );
			
			if( isset( $_SESSION['stain-color'] ) && isset( $_SESSION['wood-type'] ) ) {
			
				$db->table = 'color_images';
				
				$color_image = $db->retrieve('one','*',' where color = ' . $_SESSION['stain-color'] . " and wood_type = " . $_SESSION['wood-type'] );
			
			}
			
		?>	
		
		<div class='swatch' style='<?=$color_image['image']?('background-image: url(/images/uploads/thumbnails/'.$color_image['image'].');'):('background-color: ' . $color['hex_index']) ?>'></div>
		
		<p><?=$color['name'] ?></p>
		
		<p><a href='#' class='edit-stain-color'>Edit</a></p>

<?  else: ?>

	<p>Select Stain Color</p>

<?  endif; ?>

<script>

	$(document).ready(function(){
	
		$('.edit-stain-color').click(function(e){
		
			e.preventDefault();
			
			$('html, body').animate({scrollTop: 998}, 'slow');
		
		});
	
	});

</script>