#!/home/owen/bin/php
<?

/*


Note:


If the orderid is missing from the XML request, CURL can hang Apache. 


---------Sample PHP Elavon Remote code-----------------


Pay and Shop Limited (www.realexpayments.com) - License Agreement.

� Copyright and zero Warranty Notice.
Merchants and their internet, call centre, and wireless application
developers (either in-house or externally appointed partners and
commercial organisations) may access Elavon Payment Gateway technical
references, application programming interfaces (APIs) and other sample
code and software ("Programs") either free of charge from
https://resourcecentre.elavonpaymentgateway.com
or by emailing support@elavonpaymentgateway.com. 

Pay and Shop Limited provides the programs "as is" without any warranty
of any kind, either expressed or implied, including, but not limited to,
the implied warranties of merchantability and fitness for a particular
purpose. The entire risk as to the quality and performance of the
programs is with the merchant and/or the application development company
involved. Should the programs prove defective, the merchant and/or the
application development company assumes the cost of all necessary
servicing, repair or correction.

Copyright remains with Pay and Shop Limited, and as such any copyright
notices in the code are not to be removed. The software is provided as
sample code to assist internet, wireless and call center application
development companies integrate with the Elavon Payment Gateway service.

Any Programs licensed by Pay and Shop Limited to merchants or developers
are licensed on a non-exclusive basis solely for the purpose of availing
of the Elavon Payment Gateway payment solution service in accordance with the
written instructions of an authorised representative of Pay and Shop Limited.
Any other use is strictly prohibited.

Dated May 2013.


----------------------------------------------------------------------
*/

//Initialise arrays
$parentElements = array();
$TSSChecks = array();
$currentElement = 0;
$currentTSSCheck = "";


// In this example the values are hardcoded in.In reality they should be read in by a script or from a database.
$amount = "2999";
$currency = "EUR";
$cardnumber = "4111111111111111";
$cardname = "Owen O Byrne";
$cardtype = "visa";
$expdate = "0104";


// These values will be provided to you by Elavon Payment Gateway, if you have not already received them please contact us
$merchantid = "002536";
$secret = "ZGWD24";
$account = "002536";


//Creates timestamp that is needed to make up orderid
$timestamp = strftime("%Y%m%d%H%M%S");
mt_srand((double)microtime()*1000000);


//You can use any alphanumeric combination for the orderid. Although each transaction must have a unique orderid.
$orderid = $timestamp."-".mt_rand(1, 999);


// This section of code creates the md5hash that is needed
$tmp = "$timestamp.$merchantid.$orderid.$amount.$currency.$cardnumber";
$md5hash = md5($tmp);
$tmp = "$md5hash.$secret";
$md5hash = md5($tmp);


// Create and initialise XML parser
$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "cDataHandler");

//A number of variables are needed to generate the request xml that is send to Realex Payments.
$xml = "<request type='auth' timestamp='$timestamp'>
	<merchantid>$merchantid</merchantid>
	<account>$account</account>
	<orderid>$orderid</orderid>
	<amount currency='$currency'>$amount</amount>
	<card> 
		<number>$cardnumber</number>
		<expdate>$expdate</expdate>
		<type>$cardtype</type> 
		<chname>$cardname</chname> 
	</card> 
	<autosettle flag='1'/>
	<md5hash>$md5hash</md5hash>
	<tssinfo>
		<address type=\"billing\">
			<country>ie</country>
		</address>
	</tssinfo>
</request>";
    

// Send the request array to Elavon Payment Gateway
$ch = curl_init();    
curl_setopt($ch, CURLOPT_URL, "https://demo.myvirtualmerchant.com/VirtualMerchantDemo/process.do");
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_USERAGENT, "payandshop.com php version 0.9"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$response = curl_exec ($ch);     
curl_close ($ch); 

//Tidy it up
$response = eregi_replace ( "[[:space:]]+", " ", $response );
$response = eregi_replace ( "[\n\r]", "", $response );

// parse the response xml
if (!xml_parse($xml_parser, $response)) {
    die(sprintf("XML error: %s at line %d",
           xml_error_string(xml_get_error_code($xml_parser)),
           xml_get_current_line_number($xml_parser)));
}

print $TSSChecks["3202"];

// garbage collect the parser.
xml_parser_free($xml_parser);



/* THe "startElement()" function is called when an open element tag is found.
It creates a variable on the fly contructed of all the parent elements
 joined together with an underscore. So the following xml:

 <response><something>Owen</something></response>
 would create two variables:
 $RESPONSE and $RESPONSE_SOMETHING
*/

function startElement($parser, $name, $attrs) {
    global $parentElements;
	global $currentElement;
	global $currentTSSCheck;
	
	array_push($parentElements, $name);
	$currentElement = join("_", $parentElements);

	foreach ($attrs as $attr => $value) {
		if ($currentElement == "RESPONSE_TSS_CHECK" and $attr == "ID") {
			$currentTSSCheck = $value;
		}

		$attributeName = $currentElement."_".$attr;
		// print out the attributes..
		//print "$attributeName\n";

		global $$attributeName;
		$$attributeName = $value;
	}

	// uncomment the "print $currentElement;" line to see the names of all the variables you can 
	// see in the response.
	// print $currentElement;

}

/* The "cDataHandler()" function is called when the parser encounters any text that's 
   not an element. Simply places the text found in the variable that 
   was last created. So using the XML example above the text "Owen"
   would be placed in the variable $RESPONSE_SOMETHING
*/

function cDataHandler($parser, $cdata) {
	global $currentElement;
	global $currentTSSCheck;
	global $TSSChecks;

	if ( trim ( $cdata ) ) { 
		if ($currentTSSCheck != 0) {
			$TSSChecks["$currentTSSCheck"] = $cdata;
		}

		global $$currentElement;
		$$currentElement .= $cdata;
	}
	
}


//  The "endElement()" function is called when the closing tag of an element is found. 
//  Just removes that element from the array of parent elements.

function endElement($parser, $name) {
    global $parentElements;
	global $currentTSSCheck;

	$currentTSSCheck = 0;
	array_pop($parentElements);
}

?>

//Print out variables

Timestamp: <? echo $RESPONSE_TIMESTAMP ?>
<br>
Result: <? echo $RESPONSE_RESULT ?>
<br>
Message: <? echo $RESPONSE_MESSAGE ?>

