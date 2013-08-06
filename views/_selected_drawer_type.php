<? if( isset( $_POST['id'] ) ) {
		
		$_SESSION['drawer-and-door'] = $_POST['id'];
		
	}
	
	if( isset( $_SESSION['drawer-and-door'] ) ): ?>
	
		<?

			$db = new DB;
			
			$db->table = 'profiles';
			
			$profile = $db->retrieve('one','*',' where id = ' . $_SESSION['drawer-and-door'] );
			
		?>	

		<img src='/images/base-cabinet.jpg'/>
		
		<p><?=$profile['name'] ?></p>
								
		<p><a href='#' class='edit-drawer-and-door'>Edit</a></p>
		
<?  else: ?>

	<p>Select Door and Drawer Profile</p>

<?  endif; ?>

<script>

	$(document).ready(function(){
	
		$('.edit-drawer-and-door').click(function(e){
		
			e.preventDefault();
			
			$('html, body').animate({scrollTop: 375}, 'slow');
		
		});
	
	});

</script>