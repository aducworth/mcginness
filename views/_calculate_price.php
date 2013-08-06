<?

	$db = new DB;
	$store = new Store( $db );
	
	$price = $store->calculatePrice( $_POST['id'], $_POST['width'], $_POST['height'], $_POST['depth'], $_POST['length'], $_POST['product_price'], $_POST['profile_price'], $_POST['wood_price'], $_POST['stain_price'] );
	
	echo( '$' . number_format( $price, 2 ) );
	
?>	