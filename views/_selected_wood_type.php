<h3>Wood Type</h3>

<? if( isset( $_POST['id'] ) ) {
		
		$_SESSION['wood-type'] = $_POST['id'];
		
	}
	
	if( isset( $_SESSION['wood-type'] ) ): ?>
	
		<?

			$db = new DB;
			
			$db->table = 'wood_types';
			
			$wood_type = $db->retrieve('one','*',' where id = ' . $_SESSION['wood-type'] );
			
		?>	

	
		<img src='/images/wood-grain-1.jpg' class='circle-option'/>
		
		<p><?=$wood_type['name'] ?></p>
		
		<p><a href='#' class='edit-wood-type'>Edit</a></p>

<?  else: ?>

	<p>Select Wood Type</p>

<?  endif; ?>

<script>

	$(document).ready(function(){
	
		$('.edit-wood-type').click(function(e){
		
			e.preventDefault();
			
			$('html, body').animate({scrollTop: 695}, 'slow');
		
		});
	
	});

</script>