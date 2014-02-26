	<?

	$db = new DB;
	$store = new Store( $db );
	
	//print_r( $_POST );
	
	//print_r( $store->getFedexRates( 'Austin Ducworth', '', '864.642.5163', '2903 Rambling Path', 'Anderson', 'SC', '29621', 'US', true ) );
	
	print_r( $store->getFedexRates( 'Austin Ducworth', '', '864.642.5163', '534 King Street', 'Charleston', 'SC', '29403', 'US', true ) );
	
//Required for All Web Services
//Developer Test Key:	 qa4vSDcVUy2mc9Mo
//Required for FedEx Web Services for Intra Country Shipping in US and Global
//Test Account Number:	 510087429
//Test	 Meter Number:	 118609886
//Required for FedEx Web Services for Office and Print
//Test	 FedEx Office Integrator ID:	 123
//Test	 Client Product ID:	 TEST
//Test	 Client Product Version:	 9999

//Prod: https://ws.fedex.com:443/web-services/rate

//Authentication Key:	 Jhjgu8nMtXpgEpA5
//Meter Number:	 		106219765
		
?>	