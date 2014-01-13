<?
	
	$db = new DB;
	$store = new Store( $db );

	$processed = $store->handleSuccess( $url_parts[1] );
		
?>
<div class='welcome'>
			
	<h1>Thanks For Your Order!</h1>
	
	<hr>
	
	<h2>Here is the rest.</h2>

</div>

<section class='build-graphics'>

	<div class='container'>
				
		<p>You should receive an email shortly for your records, or you can review your purchase at any time by checking <a href='http://<?=$_SERVER['SERVER_NAME'] ?>/review?order=<?=$url_parts[1] ?>'>here</a>.</p>
		
	</div>
	
</section>