<?

//echo( getcwd() . '/library/fedex-common.php' );

require_once( getcwd() . '/library/fedex-common.php' );

getFedexRates( 'Austin Ducworth', '', '864.642.5163', '2903 Rambling Path', 'Anderson', 'SC', '29621', 'US', true );

function getFedexRates( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential, $packages ) {

	$toreturn = array();
	
	// Copyright 2009, FedEx Corporation. All rights reserved.
	// Version 12.0.0
	
	$newline = "<br />";
	//The WSDL is not included with the sample code.
	//Please include and reference in $path_to_wsdl variable.
	$path_to_wsdl = getcwd() . "/wsdl/RateService_v13.wsdl";
	
	ini_set("soap.wsdl_cache_enabled", "0");
	 
	$client = new SoapClient($path_to_wsdl, array('trace' => 1)); // Refer to http://us3.php.net/manual/en/ref.soap.php for more information
	
	$request['WebAuthenticationDetail'] = array(
		'UserCredential' =>array(
			'Key' => getProperty('key'), 
			'Password' => getProperty('password')
		)
	); 
	$request['ClientDetail'] = array(
		'AccountNumber' => getProperty('shipaccount'), 
		'MeterNumber' => getProperty('meter')
	);
	$request['TransactionDetail'] = array('CustomerTransactionId' => ' *** Rate Request v13 using PHP ***');
	$request['Version'] = array(
		'ServiceId' => 'crs', 
		'Major' => '13', 
		'Intermediate' => '0', 
		'Minor' => '0'
	);
	$request['ReturnTransitAndCommit'] = true;
	$request['RequestedShipment']['DropoffType'] = 'REGULAR_PICKUP'; // valid values REGULAR_PICKUP, REQUEST_COURIER, ...
	$request['RequestedShipment']['ShipTimestamp'] = date('c');
	//$request['RequestedShipment']['ServiceType'] = 'FEDEX_GROUND'; // valid values STANDARD_OVERNIGHT, PRIORITY_OVERNIGHT, FEDEX_GROUND, ...
	$request['RequestedShipment']['PackagingType'] = 'YOUR_PACKAGING'; // valid values FEDEX_BOX, FEDEX_PAK, FEDEX_TUBE, YOUR_PACKAGING, ...
	$request['RequestedShipment']['TotalInsuredValue']=array('Ammount'=>100,'Currency'=>'USD');
	$request['RequestedShipment']['Shipper'] = addShipper();
	$request['RequestedShipment']['Recipient'] = addRecipient( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential );
	$request['RequestedShipment']['ShippingChargesPayment'] = addShippingChargesPayment();
	$request['RequestedShipment']['RateRequestTypes'] = 'ACCOUNT'; 
	$request['RequestedShipment']['RateRequestTypes'] = 'LIST'; 
	$request['RequestedShipment']['PackageCount'] = '1';
	$request['RequestedShipment']['RequestedPackageLineItems'] = addPackageLineItem1( $packages );
	try 
	{
		if(setEndpoint('changeEndpoint'))
		{
			$newLocation = $client->__setLocation(setEndpoint('endpoint'));
		}
		
		print_r( $request );
		
		$response = $client ->getRates($request);
		
		print_r( $response );
	        
	    if ($response -> HighestSeverity != 'FAILURE' && $response -> HighestSeverity != 'ERROR')
	    {  	
	    	$rateReply = $response -> RateReplyDetails;
	    	
	    	foreach( $rateReply as $indiv_reply ) {
	    	
	    		$data = array( 
	    						'name' => $indiv_reply -> ServiceType,
	    						'rate' => $indiv_reply->RatedShipmentDetails[0]->ShipmentRateDetail->TotalNetCharge->Amount
	    						);
	    		       
		        if(array_key_exists('DeliveryTimestamp',$indiv_reply)){
		        
		        	$data['date'] = $indiv_reply->DeliveryTimestamp;
		        	
		        }else if(array_key_exists('TransitTime',$rateReply)){
		        
		        	$data['date'] = $indiv_reply->TransitTime;
		        	
		        }else {
		        
		        	$data['date'] = '';

		        }
		        
		        $toreturn[ $indiv_reply -> ServiceType ] = $data;
	        
	        }	        
	        
	        //printSuccess($client, $response);
	    }
	    else
	    {
	        printError($client, $response);
	    } 
	    
	    writeToLog($client);    // Write to log file   
	
	} catch (SoapFault $exception) {
	   printFault($exception, $client);        
	}
	
	return $toreturn;

}

function addShipper(){
	$shipper = array(
		'Contact' => array(
			'PersonName' => 'Sender Name',
			'CompanyName' => 'Sender Company Name',
			'PhoneNumber' => '9012638716'),
		'Address' => array(
			'StreetLines' => array('602 Rutledge Ave. Suite B'),
			'City' => 'Charleston',
			'StateOrProvinceCode' => 'SC',
			'PostalCode' => '29403',
			'CountryCode' => 'US')
	);
	return $shipper;
}
function addRecipient( $name, $company, $phone, $street, $city, $state, $zipcode, $country, $residential ){
	$recipient = array(
		'Contact' => array(
			'PersonName' => $name,
			'CompanyName' => $company,
			'PhoneNumber' => $phone
		),
		'Address' => array(
			'StreetLines' => $street,
			'City' => $city,
			'StateOrProvinceCode' => $state,
			'PostalCode' => $zipcode,
			'CountryCode' => $country,
			'Residential' => $residential)
	);
	return $recipient;	                                    
}
function addShippingChargesPayment(){
	$shippingChargesPayment = array(
		'PaymentType' => 'SENDER', // valid values RECIPIENT, SENDER and THIRD_PARTY
		'Payor' => array(
			'ResponsibleParty' => array(
			'AccountNumber' => getProperty('billaccount'),
			'CountryCode' => 'US')
		)
	);
	return $shippingChargesPayment;
}
function addLabelSpecification(){
	$labelSpecification = array(
		'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
		'ImageType' => 'PDF',  // valid values DPL, EPL2, PDF, ZPLII and PNG
		'LabelStockType' => 'PAPER_7X4.75');
	return $labelSpecification;
}
function addSpecialServices(){
	$specialServices = array(
		'SpecialServiceTypes' => array('COD'),
		'CodDetail' => array(
			'CodCollectionAmount' => array('Currency' => 'USD', 'Amount' => 150),
			'CollectionType' => 'ANY')// ANY, GUARANTEED_FUNDS
	);
	return $specialServices; 
}
function addPackageLineItem1( $packages ){
	$packageLineItem = array(
		'SequenceNumber'=>1,
		'GroupPackageCount'=>1,
		'Weight' => array(
			'Value' => $packages['weight'],
			'Units' => 'LB'
		),
		'Dimensions' => array(
			'Length' => $packages['length'],
			'Width' => $packages['width'],
			'Height' => $packages['height'],
			'Units' => 'IN'
		)
	);
	return $packageLineItem;
}

?>