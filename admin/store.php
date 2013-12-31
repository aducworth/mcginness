<?

class Store {

	var $db = null;	
	
	var $tax_rate = 7.5;
	
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
		
//		echo( '<br>id: ' . $id );
//		echo( '<br>width: ' . $width );
//		echo( '<br>height: ' . $height );
//		echo( '<br>depth: ' . $depth );
//		echo( '<br>length: ' . $length );
//		echo( '<br>product price: ' . $product_price );
//		echo( '<br>profile price: ' . $profile_price );
//		echo( '<br>wood price: ' . $wood_price );
//		echo( '<br>stain price: ' . $stain_price );
		
		
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
				
				$product_calc = $product_calc * $product['price'];
				$wood_calc = $front_area * $wood_price;
				$stain_calc = $front_area * $stain_price;
				$profile_calc = $front_area * $stain_price;
				
				return $product_calc + $wood_calc + $stain_calc + $profile_calc + $product['base_price'];
			
			}
			
		}
		
		return 0;
		
	}
	
	public function calculateWeight( $id, $width, $height, $depth, $length ) {
		
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
										
					return ( $area * $product['lb_per_sqft'] );
					
				}
			
			} elseif( $product['product_subcategory'] == 6 ) { // if this is a pull out shelf
			
				if( $width && $product['product_height'] && $depth ) {
				
					$volume = ( $width * $product['product_height'] * $depth ) * $cuin_to_cuft;
					$front_area = ( $width * $product['product_height'] ) * $sqin_to_sqft;
					
					return ( $front_area * $product['lb_per_sqft'] );
					
				}				
				
			} else { // otherwise, this is a door end panel
			
				if( $width && $height ) {
				
					$area = ( $width * $height ) * $sqin_to_sqft;
					
					return ( $area * $product['lb_per_sqft'] );
					
				}
				
			}
			
		} else {
		
			if( $width && $height && $depth ) {
			
				// (w*h)+2(w*d)+2(h*d)= total cabinet box sqft for a lower cabinet
                // (w*h)+4(w*d)+2(h*d)= total cabinet box sqft for an upper cabinet
                // (w*h)+6(w*d)+2(h*d)= total cabinet box sqft for a specialty cabinet
				
				// if this is a base cabinet
				if( $product['product_type'] == 1 ) {
				
					$area = ( ( $width * $height ) * $sqin_to_sqft ) + ( 2 * ( ( $width * $depth ) * $sqin_to_sqft ) ) + ( 2 * ( ( $height * $depth ) * $sqin_to_sqft ) );
				
				} elseif( $product['product_type'] == 2 ) {
				
					$area = ( ( $width * $height ) * $sqin_to_sqft ) + ( 4 * ( ( $width * $depth ) * $sqin_to_sqft ) ) + ( 2 * ( ( $height * $depth ) * $sqin_to_sqft ) );
				
				} elseif( $product['product_type'] == 3 ) {
				
					$area = ( ( $width * $height ) * $sqin_to_sqft ) + ( 6 * ( ( $width * $depth ) * $sqin_to_sqft ) ) + ( 2 * ( ( $height * $depth ) * $sqin_to_sqft ) );
				
				}
								
				return ( $area * $product['lb_per_sqft'] );
			
			}
			
		}
		
		return 0;
		
	}
	
	public function addToCart() {
	
		$this->db->table = 'products';
		
		$product = $this->db->retrieve('one','*',' where id = '. $_POST['id']);
		
		$_POST['price'] = $this->calculatePrice( $_POST['id'], $_POST['width'], $_POST['height'], $_POST['depth'], $_POST['length'], $_POST['product_price'], $_POST['profile_price'], $_POST['wood_price'], $_POST['stain_price'] );
		
		if( $_POST['quantity'] > 0 && $_POST['price'] > 0 ) {
		
			if( isset( $_POST['edit'] ) ) {
				
				$_SESSION['cart'][ $product['product_type'] ][ $_POST['edit'] ] = $_POST;
				
			} else {
				
				$_SESSION['cart'][ $product['product_type'] ][] = $_POST;
				
			}		
			
			// see if there is an additional end panel, and add it as an accessory
			if( $_POST['add_end_panel'] && $_POST['end_panel_quantity'] ) {
				
				
				$this->db->table = 'products';

				$end_panel_info = $this->db->retrieve('one','*',' where id = ' . $_POST['add_end_panel']);
		
				$end_panel = $_POST;
				
				// since this is an end panel, make the width of the panel the depth of the cabinet
				$width 		= $_POST['depth'];
				$height 	= $_POST['height'];
				$depth 		= '';
				
				$end_panel['id'] = $_POST['add_end_panel'];
				$end_panel['quantity'] = $_POST['end_panel_quantity'];
				$end_panel['width'] = $width;
				$end_panel['height'] = $height;
				$end_panel['depth'] = $depth;
				$end_panel['price'] = $this->calculatePrice( $_POST['add_end_panel'], $width, $height, $depth, $_POST['length'], $end_panel_info['price'], $_POST['profile_price'], $_POST['wood_price'], $_POST['stain_price'] );
				
				// unset unrelated fields
				unset( $end_panel['hinge_side'] );
				unset( $end_panel['add_end_panel'] );
				unset( $end_panel['end_panel_quantity'] );
				unset( $end_panel['add_shelves'] );
				unset( $end_panel['shelf_quantity'] );
				unset( $end_panel['shelf_value'] );
				
				$_SESSION['cart'][ 4 ][] = $end_panel;
				
			}	
			
			// see if there is an additional pull out shelf, and add it as an accessory
			if( $_POST['add_shelves'] && $_POST['shelf_quantity'] ) {
				
				
				$this->db->table = 'products';

				$shelf_info = $this->db->retrieve('one','*',' where id = ' . $_POST['shelf_value']);
		
				$shelf = $_POST;
				
				// since this is a pullout shelf, make the height 4.75				
				$width 		= $_POST['width'];
				$height 	= '4.75';
				$depth 		= $_POST['depth'];
				
				$shelf['id'] = $_POST['shelf_value'];
				$shelf['quantity'] = $_POST['shelf_quantity'];
				$shelf['width'] = $width;
				$shelf['height'] = $height;
				$shelf['depth'] = $depth;
				$shelf['price'] = $this->calculatePrice( $_POST['shelf_value'], $width, $height, $depth, $_POST['length'], $shelf_info['price'], $_POST['profile_price'], $_POST['wood_price'], $_POST['stain_price'] );
				
				// unset unrelated fields
				unset( $shelf['hinge_side'] );
				unset( $shelf['add_end_panel'] );
				unset( $shelf['end_panel_quantity'] );
				unset( $shelf['add_shelves'] );
				unset( $shelf['shelf_quantity'] );
				unset( $shelf['shelf_value'] );
				
				$_SESSION['cart'][ 4 ][] = $shelf;
				
			}	
			
		}	
		
		// reset shipping since the cart is different now
		unset( $_SESSION['shipping_rate'] );
		
		
	}
	
	public function removeFromCart() {
	
		if( $_GET['product_type'] && isset( $_GET['remove'] ) ) {
		
			unset( $_SESSION['cart'][ $_GET['product_type'] ][ $_GET['remove'] ] );
			
			// reset shipping since the cart is different now
			unset( $_SESSION['shipping_rate'] );
			
		}
	}
	
	public function applyDiscountCode( $code ) {
		
		$this->db->table = 'discounts';
		
		$code_info = $this->db->retrieve('one','*'," where code = '" . $code . "' and is_active=1");
		
		// if this is a valid code, set the info in session and return true
		if( $code_info['id'] ) {
			
			$_SESSION['discount_code'] = $code_info;
			
			return true;
			
		}
		
		return false;
		
	}
	
	public function resetDiscountCode( ) {
	
		unset( $_SESSION['discount_code'] );
	
	}
	
	public function calculateTaxes( $total ) {
	
		return round( ( $total * ( $this->tax_rate / 100 ) ), 2 );
	
	}
	
	public function calculateDiscount( $total ) {
		
		// calculate percentage off if it's that kind of discount
		if( $_SESSION['discount_code']['type'] == 'percentage' ) {
			
			return round( ( ( $_SESSION['discount_code']['amount'] / 100 ) * $total ), 2 );
			
		}
		
		// otherwise just return the amount ( flat )
		return $_SESSION['discount_code']['amount'];
		
	}
	
	public function processRate( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential ) {
      //create soap request
      $option['RequestOption'] = 'Shop';
      $request['Request'] = $option;

      $pickuptype['Code'] = '01';
      $pickuptype['Description'] = 'Daily Pickup';
      $request['PickupType'] = $pickuptype;

      $customerclassification['Code'] = '01';
      $customerclassification['Description'] = 'Classfication';
      $request['CustomerClassification'] = $customerclassification;

      $shipper['Name'] = 'Box Work Cabinets';
      $shipper['ShipperNumber'] = '';
      $address['AddressLine'] = array
      (
          '8193 Citation Trl.'
      );
      $address['City'] = 'Evergreen';
      $address['StateProvinceCode'] = 'CO';
      $address['PostalCode'] = '80439';
      $address['CountryCode'] = 'US';
      $shipper['Address'] = $address;
      $shipment['Shipper'] = $shipper;

	  $shipto['Name'] = $name;
      $addressTo['AddressLine'] = $street;
      $addressTo['City'] = $city;
      $addressTo['StateProvinceCode'] = $state;
      $addressTo['PostalCode'] = $zipcode;
      $addressTo['CountryCode'] = $country;
      $addressTo['ResidentialAddressIndicator'] = '';
      $shipto['Address'] = $addressTo;
      $shipment['ShipTo'] = $shipto;
      
      
      $shipfrom['Name'] = 'Box Work Cabinets';
      $addressFrom['AddressLine'] = array
      (
          '8193 Citation Trl.'
      );
      $addressFrom['City'] = 'Evergreen';
      $addressFrom['StateProvinceCode'] = 'CO';
      $addressFrom['PostalCode'] = '80439';
      $addressFrom['CountryCode'] = 'US';
      $shipfrom['Address'] = $addressFrom;
      $shipment['ShipFrom'] = $shipfrom;

      $service['Code'] = '03';
      $service['Description'] = 'Service Code';
      $shipment['Service'] = $service;

	  $packages = array();
	  
	  foreach( $this->product_types as $id => $type ) {
		
	  	if( count( $_SESSION['cart'][ $id ] ) > 0 ) {
				
			foreach( $_SESSION['cart'][ $id ] as $index => $item ) {
	  
		  	  $weight = $this->calculateWeight( $item['id'], $item['width'], $item['height'], $item['depth'], $item['length'] );
		  	  
		  	  for( $i=0;$i<$item['quantity'];$i++ ) {
		  	  
				  $packaging1['Code'] = '02';
			      $packaging1['Description'] = 'Rate';
			      $package1['PackagingType'] = $packaging1;
			      $dunit1['Code'] = 'IN';
			      $dunit1['Description'] = 'inches';
			      $dimensions1['Length'] = 36;
			      $dimensions1['Width'] = 6;
			      $dimensions1['Height'] = 42;
			      $dimensions1['UnitOfMeasurement'] = $dunit1;
			      $package1['Dimensions'] = $dimensions1;
			      $punit1['Code'] = 'LBS';
			      $punit1['Description'] = 'Pounds';
			      $packageweight1['Weight'] = $weight;
			      $packageweight1['UnitOfMeasurement'] = $punit1;
			      $package1['PackageWeight'] = $packageweight1;
			      
			      $packages[] = $package1;
		      
		      }
		      
		    }
		    
		 }
      
	  }
	  
      $shipment['Package'] = $packages;
      $shipment['ShipmentServiceOptions'] = '';
      $shipment['LargePackageIndicator'] = '';
      $request['Shipment'] = $shipment;

      return $request;
  }
  
  public function getUPSRates( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential )
  {
  
  	  $toreturn = array();
  
  	  //Configuration
	  $access = "0CB86D74B0911026";
	  $userid = "austinehouse";
	  $passwd = "TigerLover1";
	  $wsdl = getcwd() . "/ups/RateWS.wsdl";
	  $operation = "ProcessRate";
	  $endpointurl = 'http://' . $_SERVER['HTTP_HOST'];
	  $outputFileName = "XOLTResult.xml";
	  
	  $shipping_codes = array(  '11' => 'Standard',	
								'03' => 'Ground',	
								'12' => '3 Day Select',	
								'02' => '2nd Day Air',	
								'59' => '2nd Day Air AM',	
								'13' => 'Next Day Air Saver',	
								'01' => 'Next Day Air',	
								'14' => 'Next Day Air Early A.M.', 
								'07' => 'UPS Worldwide Express',	
								'54' => 'Worldwide Express Plus', 
								'08' => 'UPS Worldwide Expedited',	
								'65' => 'UPS World Wide Saver' );

	  try
	  {
	
	    $mode = array
	    (
	         'soap_version' => 'SOAP_1_1',  // use soap 1.1 client
	         'trace' => 1
	    );
	
	    // initialize soap client
	  	$client = new SoapClient($wsdl , $mode);
	
	    //create soap header
	    $usernameToken['Username'] = $userid;
	    $usernameToken['Password'] = $passwd;
	    $serviceAccessLicense['AccessLicenseNumber'] = $access;
	    $upss['UsernameToken'] = $usernameToken;
	    $upss['ServiceAccessToken'] = $serviceAccessLicense;
	
	    $header = new SoapHeader('http://www.ups.com/XMLSchema/XOLTWS/UPSS/v1.0','UPSSecurity',$upss);
	    $client->__setSoapHeaders($header);
	
	    //get response
	  	$resp = $client->__soapCall($operation ,array($this->processRate( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential )));
	  	
	  	//echo( '<h1>Here</h1>' );
	
	    //get status
	    //echo "<h1>Response Status: " . $resp->Response->ResponseStatus->Description . "</h1>";
	    
	    foreach( $resp->Response->Alert as $alert ) {
	    
	    	$toreturn['alerts'][$alert->Code] = $alert->Description;
	    	
	    }
	    
	    foreach( $resp->RatedShipment as $option ) {
	    
	    	$data = array( 
	    						'name' => $shipping_codes[ $option->Service->Code ],
	    						'rate' => $option->TotalCharges->MonetaryValue
	    						); 
		    
//		    echo( '<p>' );
//		    print_r( $option );
//		    echo( '</p>' );
		    
		    $toreturn['rates'][ $shipping_codes[ $option->Service->Code ] ] = $data;
		    
	    }
	
	  }
	  catch(Exception $ex)
	  {
	  	//print_r ($ex);
	  }
	  
	  return $toreturn;
  
  }

}