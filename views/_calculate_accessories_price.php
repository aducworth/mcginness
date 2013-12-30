<?

	$db = new DB;
	$store = new Store( $db );
	
	$toreturn = array();
	
	if( is_array( $_POST['option'] ) ) {
	
		$db->table = 'products';
		$product_list = $db->retrieve('pair','id,name');
		$product_prices = $db->retrieve('pair','id,price');
		$product_subcategories = $db->retrieve('pair','id,product_subcategory');
	
		foreach( $_POST['option'] as $o ) {
		
			// if this is an end panel, make the width of the panel the depth of the cabinet
			if( $product_subcategories[ $o ] == 5 ) {
				
				$width 		= $_POST['depth'];
				$height 	= $_POST['height'];
				$depth 		= '';
				
			} else { // if this is a pullout shelf, make the height 4.75
				
				$width 		= $_POST['width'];
				$height 	= '4.75';
				$depth 		= $_POST['depth'];
				
			}
		
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