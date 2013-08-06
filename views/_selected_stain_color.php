<h3>Stain Color</h3>

<? if( isset( $_POST['id'] ) ) {
		
		$_SESSION['stain-color'] = $_POST['id'];
		
	}
	
	if( isset( $_SESSION['stain-color'] ) ): ?>
	
		<?

			$db = new DB;
			
			$db->table = 'colors';
			
			$color = $db->retrieve('one','*',' where id = ' . $_SESSION['stain-color'] );
			
		?>	
	
		<img src='/images/wood-grain-1.jpg' class='circle-option'/>
		
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