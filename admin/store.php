<?

class Store {

	var $db = null;	
	
	var $tax_rate = 7.5;
	
	var $product_types = array( 1 => 'Base Cabinets', 2 => 'Upper Cabinets', 3 => 'Specialty', 4 => 'Accessories' );
	
	var $overweight_packages = 0;
	
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
		
		$this->db->table = 'store_options';
				
		$this->store_options = $this->db->retrieve('pair','option_name,option_value'); 
		
		$this->db->table = 'colors';
				
		$this->color_list = $this->db->retrieve('pair','id,name',' order by display_order, name'); 
		
		$this->db->table = 'wood_types';
			
		$this->wood_type_list = $this->db->retrieve('pair','id,name',' order by display_order, name'); 
		
		$this->db->table = 'profiles';
			
		$this->profile_list = $this->db->retrieve('pair','id,name',' order by display_order, name');
		
		// set the tax rate
		$this->tax_rate = $this->store_options['tax_rate']; 
			
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
					
					return round( ( $product_calc + $wood_calc + $stain_calc + $product['base_price'] ), 2 );
					
				}
			
			} elseif( $product['product_subcategory'] == 6 ) { // if this is a pull out shelf
			
				if( $width && $product['product_height'] && $depth ) {
				
					$volume = ( $width * $product['product_height'] * $depth ) * $cuin_to_cuft;
					$front_area = ( $width * $product['product_height'] ) * $sqin_to_sqft;
					
					$product_calc = $volume * $product_price;
					$stain_calc = $front_area * $stain_price;
					
					return round( ( $product_calc + $stain_calc + $product['base_price'] ), 2 );
					
				}				
				
			} else { // otherwise, this is a door end panel
			
				if( $width && $height ) {
				
					$area = ( $width * $height ) * $sqin_to_sqft;
					
					$product_calc = $area * $product_price;
					$wood_calc = $area * $wood_price;
					$stain_calc = $area * $stain_price;
					
					return round( ( $product_calc + $wood_calc + $stain_calc + $product['base_price'] ), 2 );
					
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
				
				return round( ( $product_calc + $wood_calc + $stain_calc + $profile_calc + $product['base_price'] ), 2 );
			
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
      $request['Request'] = "ALL";

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
	  
	  $this->overweight_packages = 0;
	  
	  foreach( $this->product_types as $id => $type ) {
		
	  	if( count( $_SESSION['cart'][ $id ] ) > 0 ) {
				
			foreach( $_SESSION['cart'][ $id ] as $index => $item ) {
	  
		  	  $weight = $this->calculateWeight( $item['id'], $item['width'], $item['height'], $item['depth'], $item['length'] );
		  	  
		  	  for( $i=0;$i<$item['quantity'];$i++ ) {
		  	  
		  	  	  // do not add packages over 150 lbs because rates aren't available
		  	  	  // we'll use flat rates for these
		  	  	  if( $weight < 150 ) {
			  	  	  
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
			  	  	  
		  	  	  } else {
			  	  	  
			  	  	  $this->overweight_packages++;
			  	  	  
		  	  	  }				  
		      
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
  
	function getFedexRates( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential ) {
	
		$wsdl_path = str_replace( '/admin', '', getcwd() ) . "/fedex/wsdl/RateService_v13.wsdl";
		include( str_replace( '/admin', '', getcwd() ) . "/fedex/library/fedex-common.php" );
		
		$client = new SoapClient($wsdl_path, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
		
		ini_set("soap.wsdl_cache_enabled", "0");
		
		$shipping = array( 'error_message'	=> NULL );
				 		
		/******************************************* ASSEMBLE REQUEST & SEND *********************************/
		
		$request['WebAuthenticationDetail'] = array(
			'UserCredential' => array(
				'Key' 		=> 'Jhjgu8nMtXpgEpA5', 
				'Password' 	=> 'jZE90qC7BUYVAu93bzqMMkfVR'
			)
		); 
					
		$request['ClientDetail'] = array(
			'AccountNumber' 	=> '423827874', 
			'MeterNumber' 		=> '106219765'
		);
		
		
		$request['TransactionDetail'] = array(
			'CustomerTransactionId' => ' *** Rate Request v13 using PHP *** '
		);
		
		$request['Version'] = array(
			'ServiceId' => 'crs', 
			'Major' => '13', 
			'Intermediate' => '0', 
			'Minor' => '0'
		);
		
		$request['ReturnTransitAndCommit'] = true;
		
		$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
		
		$request['RequestedShipment']['ShipTimestamp'] = date('c');
		
		$request['RequestedShipment']['TotalInsuredValue']=array(
			'Amount' => '100',
			'Currency' => 'USD'
		); 
		
		$request['RequestedShipment']['Shipper'] = array(
			'Address' => array(
					'StreetLines' => array( '8193 Citation Trl.' ),
		            'City' => 'Evergreen',
		            'StateOrProvinceCode' => 'CO',
		            'PostalCode' =>  '80439',
		            'CountryCode' => 'US',
		            'Residential' => 0
				)
			);
		
//		if ($option_value == "SMART_POST")
//		{
//			$request['RequestedShipment']['SmartPostDetail'] = array( 'Indicia' => $this->plugin_settings('sp_indicia'),
//			                                                          'AncillaryEndorsement' => $this->plugin_settings('sp_ancillary_services'),
//			                                                          'HubId' => $this->plugin_settings('hubid'),
//			                                                          'CustomerManifestId' => date("md0B"));
//		}

		$request['RequestedShipment']['Recipient'] = array(
			'Address' => array(
					'StreetLines' => array( $street ),
		            'City' => $city,
		            'StateOrProvinceCode' => $state,
		            'PostalCode' => $zipcode,
		            'CountryCode' => $country,
		            'Residential' => ($residential?1:0)
					)
				);
				
		$request['RequestedShipment']['ShippingChargesPayment'] = array(
			'PaymentType' => 'SENDER',
			'Payor' => array(
				'AccountNumber' => '423827874',
				'CountryCode' => 'US'
				)
		);
		$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
		
		$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
		
		$request['RequestedShipment']['PackageCount'] = $this->getPackageCount();
		
		$request['RequestedShipment']['PackageDetail'] = 'INDIVIDUAL_PACKAGES';  //  Or PACKAGE_SUMMARY
				
	  $count = 1;
	  
	  foreach( $this->product_types as $id => $type ) {
		
	  	if( count( $_SESSION['cart'][ $id ] ) > 0 ) {
				
			foreach( $_SESSION['cart'][ $id ] as $index => $item ) {
	  
		  	  $weight = $this->calculateWeight( $item['id'], $item['width'], $item['height'], $item['depth'], $item['length'] );
		  	  
		  	  for( $i=0;$i<$item['quantity'];$i++ ) {

					$request['RequestedShipment']['RequestedPackageLineItems'][$count-1] = 
							array(
							'SequenceNumber'=>$count,
							'GroupPackageCount'=>1,
							'Weight' => array(
								'Value' 	=> $weight,
								'Units' 	=> 'LB'
								),
							'Dimensions' => array(
								'Length' 	=> 36,
								'Width' 	=> 6,
								'Height' 	=> 42,
								'Units' 	=> 'IN'
								)
							); 
					$count ++; 
				
				}
				
			}
			
		  }
		  
		 }
		
		//print_r( $request );
		
			try 
		{
			if(setEndpoint('changeEndpoint'))
			{
				$newLocation = $client->__setLocation(setEndpoint('endpoint'));
			}
		
			$response = $client->getRates($request);
			
			print_r( $response );
			
			    if ($response->HighestSeverity != 'FAILURE' && $response->HighestSeverity != 'ERROR' && !empty($response->RateReplyDetails))
		    {
			 
			        foreach ($response->RateReplyDetails as $rateReply)
		        {
		        		        	
		        	$rateReply = $response -> RateReplyDetails;
			
			    	foreach( $rateReply as $indiv_reply ) {
			    	
			    		if( $indiv_reply -> ServiceType == 'GROUND_HOME_DELIVERY' || $indiv_reply -> ServiceType == 'FEDEX_GROUND' )
			    		return array( 'rate' => $indiv_reply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount );
			        
			        }	
		
				}
			    }
		    else
		    {
				
				return array( 'error' => 'Fedex Live Rates Error' );
		    } 
		} 
		catch (SoapFault $exception) 
		{
		
			return array( 'error' => $exception->faultcode ." " . $exception->faultstring );			
			
		}
		
		if ($shipping['error_message'])
		{
		
			return array( 'error' => $shipping['error_message'] );

		}
		
		// if we got to this point and nothing has been return, something must be wrong
		return array( 'error' => 'Fedex Live Rates Error' );
	
	}
	
  public function getPackageCount( ) {
  
  	  $total_count = 0;
	  
	  foreach( $this->product_types as $id => $type ) {
		
	  	if( count( $_SESSION['cart'][ $id ] ) > 0 ) {
	  	
		  foreach( $_SESSION['cart'][ $id ] as $index => $item ) {
		  
		  	$total_count += $item['quantity'];
		  
		  }
		  
		}
		
	 }
	 
	 return $total_count;
	  
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
	    
	    //print_r( $resp );
	    
	    foreach( $resp->RatedShipment as $option ) {
	    
	    	$rate = $option->TotalCharges->MonetaryValue;
	    	
	    	// check for any overweight packages that weren't included
	    	if( $this->overweight_packages > 0 ) {
		    	
		    	$rate += ( $this->overweight_packages * $this->store_options['overweight_flat_rate'] );
		    	
	    	}
	    
	    	$data = array( 
	    						'name' => $shipping_codes[ $option->Service->Code ],
	    						'rate' => $rate
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
	  	
	  	// check to make sure it's not only overweight packages
	    if( !isset( $toreturn['rates']['Ground'] ) && $this->overweight_packages > 0 ) {
		    
		    $toreturn['rates']['Ground'] = array( 'name' => 'Ground', 'rate' => ( $this->overweight_packages * $this->store_options['overweight_flat_rate'] ) );
		    
	    }
	    
	  	return $toreturn;
	  	
	  }
	  
	  return $toreturn;
  
  }
  
  public function processCard( $card_no, $order_id ) {
	  
	  // error simulation
	  //header( 'Location: /checkout/error/' . md5( $order_id ) . '/?errorMsg=We could not process your card.' );
	  //exit;
	  
	  // success simulation
	  //header( 'Location: /thanks/' . md5( $order_id ) . '/' );
	  //exit;
	  
	  	$billing_name = $_POST['billing_name'];
	  	$billing_first = trim( substr($billing_name, 0, strpos($billing_name,' ')) );
	  	$billing_last = trim( substr($billing_name, strpos($billing_name,' ')) );
	  	
	  	$shipping_name = $_POST['shipping_name'];
	  	$shipping_first = trim( substr($shipping_name, 0, strpos($shipping_name,' ')) );
	  	$shipping_last = trim( substr($shipping_name, strpos($shipping_name,' ')) );
	  
	    $url = 'https://www.myvirtualmerchant.com/VirtualMerchantDemo/process.do';

		//Modify the values from xxx to your own account ID, user ID, and PIN
		
		//Additional fields can be added as necessary to support custom fields
		
		//or required fields configured in the Virtual Merchant terminal
		
		$fields = array(
		
			'ssl_merchant_id'=>'004763',
			
			//Vi rtualMerchant Developer's Guide.docx Page 138 of 152
			
			'ssl_user_id'=>'webpage',
			
			'ssl_pin'=>'W1T3CQ',
			
			'ssl_show_form'=>'false',
			
			'ssl_result_format'=>'html',
			
			'ssl_test_mode'=>'false',
			
			'ssl_receipt_apprvl_method'=>'redg',
			
			//modify the value below from xxx to the location of your error script
			
			//'ssl_error_url' => 'http://localhost:8888/checkout/error/' . md5( $order_id ) . '/',
			'ssl_error_url' => 'https://www.boxworkcabinets.com/checkout/error/' . md5( $order_id ) . '/',
			
			//modify the value below from xxx to the location of your receipt script
			
			//'ssl_receipt_apprvl_get_url' => 'http://localhost:8888/thanks/' . md5( $order_id ) . '/',
			'ssl_receipt_apprvl_get_url' => 'http://www.boxworkcabinets.com/thanks/' . md5( $order_id ) . '/',
			
			'ssl_transaction_type'=>urlencode('ccsale'),
			
			//'ssl_amount'=>urlencode( $_POST['total'] ),
			'ssl_amount'=>urlencode( '1.00' ),
			
			'ssl_card_number'=>urlencode( $card_no ),
			
			'ssl_exp_date'=>urlencode( $_POST['exp_date'] ),
			
			'ssl_cvv2cvc2_indicator'=>urlencode($ssl_cvv2cvc2_indicator),
			
			'ssl_cvv2cvc2'=>urlencode('123'),
			
			'ssl_customer_code'=>urlencode( $_POST['billing_email'] ),
			
			'ssl_invoice_number'=>urlencode( $order_id ),
			
			'ssl_first_name'=>urlencode( $billing_first ),
			
			'ssl_last_name'=>urlencode( $billing_last ),
			
			'ssl_avs_address'=>urlencode( $_POST['billing_address1'] ),
			
			'ssl_avs_zip'=>urlencode( $_POST['billing_zipcode'] ),
			
			'ssl_city'=>urlencode( $_POST['billing_city'] ),
			
			'ssl_state'=>urlencode( $_POST['billing_state'] ),
			
			'ssl_country'=>'USA',
			
			'ssl_phone'=>urlencode( $_POST['billing_phone'] ),
			
			'ssl_email'=>urlencode( $_POST['billing_email'] ),
			
			'ssl_ship_to_first_name'=>urlencode( $shipping_first ),
			
			'ssl_ship_to_last_name'=>urlencode( $shipping_last ),
			
			'ssl_ship_to_address1'=>urlencode( $_POST['shipping_address1'] ),
			
			'ssl_ship_to_zip'=>urlencode( $_POST['shipping_zipcode'] ),
			
			'ssl_ship_to_city'=>urlencode( $_POST['shipping_city'] ),
			
			'ssl_ship_to_state'=>urlencode( $_POST['shipping_state'] ),
			
			'ssl_ship_to_country'=>'USA'
		
		);
		
		//print_r( $fields );
		//exit;
		
		//initialize the post string variable
		
		$fields_string = '';
		
		//build the post string
		
		foreach($fields as $key=>$value) { $fields_string .=$key.'='.$value.'&'; }
		
		rtrim($fields_string, "&");
		
		//open curl session
		
		$ch = curl_init();
		
		//begin seting curl options
		
		//set URL
		
		curl_setopt($ch, CURLOPT_URL, $url);
		
		//set method
		
		curl_setopt($ch, CURLOPT_POST, 1);
		
		//Vi rtualMerchant Developer's Guide.docx Page 139 of 152
		
		//set post data string
		
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		
		//these two options are frequently necessary to avoid SSL errors with PHP
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		
		//curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		//perform the curl post and store the result
		
		$result = curl_exec($ch);
		
		//close the curl session
		
		curl_close($ch);
		
		//a nice message to prevent people from seeing a blank screen
		
		echo "Processing, please wait...";
	  
  }
  
  public function handleSuccess( $order_id ) {  	
  	
  	$this->confirmationEmail( $order_id );
  	
  	// reset cart
  
  }
  
  public function deleteOrder( $order_id ) {
	  
	  $this->db->query( "delete from orders where md5( id ) = '" . $order_id . "'" );
	  $this->db->query( "delete from order_items where md5( order_id ) = '" . $order_id . "'" );
	  
  }
  
  public function processOrder( ) {
	
		// try to process the card
		if( $_POST['total'] <= 0 ) {
			
			// redirect if trying to checkout with nothing in the cart
			header( 'Location: /checkout/error/?errorMsg=Your cart total is 0.' );
			exit;
			
		}
		
		// format a few items
		$order_items = $_POST['order_items'];
		$_POST['exp_date'] = $_POST['exp_mo'] . substr( $_POST['exp_year'], 2 );
		
		$card_no = $_POST['card_no'];
		
		unset( $_POST['order_items'] );
		unset( $_POST['card_no'] );
		unset( $_POST['exp_mo'] );
		unset( $_POST['exp_year'] );
	
		$this->db->table = 'orders';
		
		$this->db->save( $_POST );
		
		$order_id = mysql_insert_id();
		
		$this->db->table = 'order_items';
		
		foreach( $order_items as $item ) {
			
			$item['order_id'] = $order_id;
			 
			$this->db->save( $item );
		
		}
		
		// now try to process the card
		$this->processCard( $card_no, $order_id );
			
	}
	
	public function confirmationEmail( $order_id ) {
	
		$this->db->table = 'orders';
		$order_info = $this->db->retrieve('one','*'," where md5( id ) = '" . $order_id . "'");
	
		// send confirmation emails
		$to = $order_info['billing_email'];
		$subject = 'Order: ' . $order_id;
		$body = file_get_contents('http://' . $_SERVER['SERVER_NAME'] . '/review?order=' . $order_id . '&ajax=true' );
		$this->send_mail( $to, $body, $subject, 'store@boxworkcabinets.com', 'Boxwork Store' );
		
	}
	
	public function send_mail($to, $body, $subject, $fromaddress, $fromname, $attachments=false)

	{

	  $eol="\r\n";

	  $mime_boundary=md5(time());

	

	  # Common Headers

	  $headers .= "From: ".$fromname."<".$fromaddress.">".$eol;
	  
	  //if( !strstr( $subject, 'configured' ) ) {
	  
	  	$headers .= 'Bcc: aducworth@gmail.com'.$eol;
		
	  //}

	  $headers .= "Reply-To: ".$fromname."<".$fromaddress.">".$eol;

	  $headers .= "Return-Path: ".$fromname."<".$fromaddress.">".$eol;    // these two to set reply address

	  $headers .= "Message-ID: <".time()."-".$fromaddress.">".$eol;

	  $headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters

	

	  # Boundry for marking the split & Multitype Headers

	  $headers .= "MIME-Version: 1.0".$eol;

	  $headers .= "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"".$eol.$eol;

	

	  # Open the first part of the mail

	  $msg = "--".$mime_boundary.$eol;

	  

	  $htmlalt_mime_boundary = $mime_boundary."_htmlalt"; //we must define a different MIME boundary for this section

	  # Setup for text OR html -

	  $msg .= "Content-Type: multipart/alternative; boundary=\"".$htmlalt_mime_boundary."\"".$eol.$eol;

	

	  # Text Version

	  $msg .= "--".$htmlalt_mime_boundary.$eol;

	  $msg .= "Content-Type: text/plain; charset=iso-8859-1".$eol;

	  $msg .= "Content-Transfer-Encoding: 8bit".$eol.$eol;

	  $msg .= strip_tags(str_replace("<br>", "\n", substr($body, (strpos($body, "<body>")+6)))).$eol.$eol;

	

	  # HTML Version

	  $msg .= "--".$htmlalt_mime_boundary.$eol;

	  $msg .= "Content-Type: text/html; charset=iso-8859-1".$eol;

	  $msg .= "Content-Transfer-Encoding: 8bit".$eol.$eol;

	  $msg .= $body.$eol.$eol;

	

	  //close the html/plain text alternate portion

	  $msg .= "--".$htmlalt_mime_boundary."--".$eol.$eol;

	

	  if ($attachments !== false)

	  {

		for($i=0; $i < count($attachments); $i++)

		{



			$f_contents = chunk_split(base64_encode($attachments[$i]["file"]));

			

			# Attachment

			$msg .= "--".$mime_boundary.$eol;

			$msg .= "Content-Type: ".$attachments[$i]["content_type"]."; name=\"".$attachments[$i]["name"]."\"".$eol;  // sometimes i have to send MS Word, use 'msword' instead of 'pdf'

			$msg .= "Content-Transfer-Encoding: base64".$eol;

			$msg .= "Content-Description: ".$attachments[$i]["name"].$eol;

			$msg .= "Content-Disposition: attachment; filename=\"".$attachments[$i]["name"]."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!

			$msg .= $f_contents.$eol.$eol;

		  //}

		}

	  }

	  # Finished
	  $msg .= "--".$mime_boundary."--".$eol.$eol;  // finish with two eol's for better security. see Injection.

	  # SEND THE EMAIL
	  
	  ini_set(sendmail_from,$fromaddress);  // the INI lines are to force the From Address to be used !

	  //$mail_sent = mail($to, $subject, $msg, $headers);	  
	  $mail_sent = mail($to, $subject, $msg, $headers,"-f$to");	  

	  ini_restore(sendmail_from);

	  return $mail_sent;

	} // send_mail

}