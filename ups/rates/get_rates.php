<?php

  function processRate( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential, $packages )
  {
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
      
      
      $shipfrom['Name'] = '{e}house studio';
      $addressFrom['AddressLine'] = array
      (
          '602 Rutledge Ave. Suite B'
      );
      $addressFrom['City'] = 'Charleston';
      $addressFrom['StateProvinceCode'] = 'SC';
      $addressFrom['PostalCode'] = '29403';
      $addressFrom['CountryCode'] = 'US';
      $shipfrom['Address'] = $addressFrom;
      $shipment['ShipFrom'] = $shipfrom;

      $service['Code'] = '03';
      $service['Description'] = 'Service Code';
      $shipment['Service'] = $service;

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
      $packageweight1['Weight'] = 8;
      $packageweight1['UnitOfMeasurement'] = $punit1;
      $package1['PackageWeight'] = $packageweight1;

//      $packaging2['Code'] = '02';
//      $packaging2['Description'] = 'Rate';
//      $package2['PackagingType'] = $packaging2;
//      $dunit2['Code'] = 'IN';
//      $dunit2['Description'] = 'inches';
//      $dimensions2['Length'] = '3';
//      $dimensions2['Width'] = '5';
//      $dimensions2['Height'] = '8';
//      $dimensions2['UnitOfMeasurement'] = $dunit2;
//      $package2['Dimensions'] = $dimensions2;
//      $punit2['Code'] = 'LBS';
//      $punit2['Description'] = 'Pounds';
//      $packageweight2['Weight'] = '2';
//      $packageweight2['UnitOfMeasurement'] = $punit2;
//      $package2['PackageWeight'] = $packageweight2;

//      $shipment['Package'] = array(	$package1 , $package2 );
      $shipment['Package'] = array(	$package1 );
      $shipment['ShipmentServiceOptions'] = '';
      $shipment['LargePackageIndicator'] = '';
      $request['Shipment'] = $shipment;

      return $request;
  }
  
  function getUPSRates( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential, $packages )
  {
  
  	  $toreturn = array();
  
  	  //Configuration
	  $access = "0CB86D74B0911026";
	  $userid = "austinehouse";
	  $passwd = "TigerLover1";
	  $wsdl = getcwd() . "/RateWS.wsdl";
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
	  	$resp = $client->__soapCall($operation ,array(processRate( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential, $packages )));
	  	
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
	  	print_r ($ex);
	  }
	  
	  return $toreturn;
  
  }
  
  print_r( getUPSRates( 'Austin Ducworth', 'Austin Ducworth', '864.642.5163', '2903 Rambling Path', 'Anderson', 'SC', '29621', 'US', '', array() ) );

?>
