<?

	$db = new DB;
	$store = new Store( $db );
	
	$toreturn = array();
	
	if( is_array( $_POST['option'] ) ) {
	
		$db->table = 'products';
		$product_list = $db->retrieve('pair','id,name');
		$product_prices = $db->retrieve('pair','id,price');
	
		foreach( $_POST['option'] as $o ) {
		
			$price = $store->calculatePrice( $o, $_POST['width'], $_POST['height'], $_POST['depth'], $_POST['length'], $product_prices[ $o ], $_POST['profile_price'], $_POST['wood_price'], $_POST['stain_price'] );
			
			if( $price ) {
				
				$toreturn[ $o ] = $product_list[ $o ] . ' ( $' . number_format( $price, 2 ) . ' )';
				
			} else {
				
				$toreturn[ $o ] = $product_list[ $o ] . ' ( Enter dimensions for pricing )';
				
			}
			
			
		}
		
	}
	
	echo( json_encode( $toreturn ) );
	
?>	