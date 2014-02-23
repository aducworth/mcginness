<form id='newsletter-signup' action='_send_email' method='post'>

	<h1>Newsletter Signup</h1>
	
	<span class='result errors' style='font-family: Helvetica'></span>
	
	<p><input type='text' name='email' placeholder='Email'></p>
	
	<button id='submit' class='red-button'>Signup</button>
	
</form>

<script>

	$(document).ready(function(){

		$('#newsletter-signup').submit(function(e){
		
			e.preventDefault();
			
			var postData = $(this).serialize();
			
			$.ajax({
			  url: '/_send_email?ajax=true',
			  data: postData,
			  type: "POST"
			}).done(function(data) {
			
			  if( data == 'success' ) {
				  
				  $('.result').html('Thanks for signing up!');
				  
			  } else {
				  
				  $('.result').html('Please provide a valid email.');
			  }
			  
			  $.colorbox.resize();
			  
			});
			
		
		});
			
	});

</script>