<?

class Store {

	var $db = null;	
	
	var $product_types = array( 1 => 'Base Cabinets', 2 => 'Upper Cabinets', 3 => 'Specialty', 4 => 'Accessories' );
	
	var $product_subcategories = array( 1 => array(
											1 => '1 Door',
											2 => '2 Door',
											3 => '1 Door / 1 Drawer',
											4 => '2 Door / 1 Drawer',
											5 => '2 Door / 2 Drawer',
											6 => '3 Drawer',
											7 => '4 Drawer',
											8 => '1 Door Blind',
											9 => '1 Door / 1 Drawer Blind',
											10 => 'Lazy Susan' ),
								   2 => array(
											1 => '1 Door',
											2 => '2 Door',
											3 => '1 Door Blind',
											4 => '45 Degree' ),
								   3 => array(
											1 => 'Oven Cabinet',
											2 => 'Pantry' ),
								   4 => array(
											1 => 'Crown Molding',
											2 => 'Toe Kick Skins',
											3 => 'Filler Strips',
											4 => 'Coping Strips',
											5 => 'End Panels',
											6 => 'Pull Out Shelves' ) );
								
	
	function __construct( $db ) {
	
		$this->db = $db;
		
		if( !is_array( $_SESSION['cart'] ) ) {
		
			$_SESSION['cart'] = array();
			
		}
		
		$this->db->table = 'colors';
				
		$this->color_list = $this->db->retrieve('pair','id,name',' order by display_order, name'); 
		
		$this->db->table = 'wood_types';
			
		$this->wood_type_list = $this->db->retrieve('pair','id,name',' order by display_order, name'); 
		
		$this->db->table = 'profiles';
			
		$this->profile_list = $this->db->retrieve('pair','id,name',' order by display_order, name'); 
			
	}
	
	public function calculatePrice( $id, $width, $height, $depth, $length, $product_price, $profile_price, $wood_price, $stain_price ) {
		
		$this->db->table = 'products';
		
		$product = $this->db->retrieve('one','*',' where id = '. $id);
		
		$cuin_to_cuft = 0.000578704;
		$sqin_to_sqft = 0.00694444;
		$in_to_ft = 0.0833333;
		
		// if this is an accessory, pricing is calculated differently
		if( $product['product_type'] == 4 ) {
		
			// if length is chosen, this is some kind of molding
			if( $product['choose_length'] ) {
			
				if( $width && $length ) {
				
					$area = ( $width * $in_to_ft ) * $length;
					
					$product_calc = $area * $product_price;
					$wood_calc = $area * $wood_price;
					$stain_calc = $area * $stain_price;
					
					return $product_calc + $wood_calc + $stain_calc + $product['base_price'];
					
				}
			
			} elseif( $product['product_subcategory'] == 6 ) { // if this is a pull out shelf
			
				if( $width && $product['product_height'] && $depth ) {
				
					$volume = ( $width * $product['product_height'] * $depth ) * $cuin_to_cuft;
					$front_area = ( $width * $product['product_height'] ) * $sqin_to_sqft;
					
					$product_calc = $volume * $product_price;
					$stain_calc = $front_area * $stain_price;
					
					return $product_calc + $stain_calc + $product['base_price'];
					
				}				
				
			} else { // otherwise, this is a door end panel
			
				if( $width && $height ) {
				
					$area = ( $width * $height ) * $sqin_to_sqft;
					
					$product_calc = $area * $product_price;
					$wood_calc = $area * $wood_price;
					$stain_calc = $area * $stain_price;
					
					return $product_calc + $wood_calc + $stain_calc + $product['base_price'];
					
				}
				
			}
			
		} else {
		
			if( $width && $height && $depth ) {
			
				// (w*h)+2(w*d)+2(h*d)= total cabinet box sqft for a lower cabinet
                // (w*h)+4(w*d)+2(h*d)= total cabinet box sqft for an upper cabinet
                // (w*h)+6(w*d)+2(h*d)= total cabinet box sqft for a specialty cabinet
			
				// if this is a base cabinet
				if( $product['product_type'] == 1 ) {
				
					$product_calc = ( ( $width * $height ) * $sqin_to_sqft ) + ( 2 * ( ( $width * $depth ) * $sqin_to_sqft ) ) + ( 2 * ( ( $height * $depth ) * $sqin_to_sqft ) );
				
				} elseif( $product['product_type'] == 2 ) {
				
					$product_calc = ( ( $width * $height ) * $sqin_to_sqft ) + ( 4 * ( ( $width * $depth ) * $sqin_to_sqft ) ) + ( 2 * ( ( $height * $depth ) * $sqin_to_sqft ) );
				
				} elseif( $product['product_type'] == 3 ) {
				
					$product_calc = ( ( $width * $height ) * $sqin_to_sqft ) + ( 6 * ( ( $width * $depth ) * $sqin_to_sqft ) ) + ( 2 * ( ( $height * $depth ) * $sqin_to_sqft ) );
				
				}

				
				//$volume = ( $width * $height * $depth ) * $cuin_to_cuft;
				$front_area = ( $width * $height ) * $sqin_to_sqft;
				
				//$product_calc = $volume * $product_price;
				$wood_calc = $front_area * $wood_price;
				$stain_calc = $front_area * $stain_price;
				$profile_calc = $front_area * $stain_price;
				
				return $product_calc + $wood_calc + $stain_calc + $profile_calc + $product['base_price'];
			
			}
			
		}
		
		return 0;
		
	}
	
	public function addToCart() {
	
		$this->db->table = 'products';
		
		$product = $this->db->retrieve('one','*',' where id = '. $_POST['id']);
		
		$_POST['price'] = $this->calculatePrice( $_POST['id'], $_POST['width'], $_POST['height'], $_POST['depth'], $_POST['length'], $_POST['product_price'], $_POST['profile_price'], $_POST['wood_price'], $_POST['stain_price'] );
		
		if( $_POST['quantity'] > 0 && $_POST['price'] > 0 ) {
		
			$_SESSION['cart'][ $product['product_type'] ][] = $_POST;
			
		}		
		
	}

}