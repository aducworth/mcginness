<?

	$db = new DB;
	$store = new Store( $db );
	
	print_r( $_POST );
	
	$store->addToCart();
		
?>	