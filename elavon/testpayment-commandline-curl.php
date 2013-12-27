<?

/*

Note:


If the orderid is missing from the XML request, CURL can hang Apache. 



---------------Sample PHP Elavon Remote code----------------



Pay and Shop Limited (www.realexpayments.com) - License Agreement.

© Copyright and zero Warranty Notice.
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


$URL="https://remote.elavonpaymentgateway.com/remote";

$parentElements = array();
$TSSChecks = array();
$currentElement = 0;
$currentTSSCheck = "";


// In this example the values are hardcoded in, but in reality these values should be taken from a script or a database
$amount = "2999";
$currency = "EUR";
$cardnumber = "4111111111111111";
$cardname = "Owen O Byrne";
$cardtype = "visa";
$expdate = "0116";



//Replace these with the values you receive from Elavon Payment Gateway support.(If we have not already contacted you with these details please call us)
$merchantid = "yourmerchantid";
$secret = "yoursecret";
$account = "";


// The Timestamp is created here and used in the digital signature
$timestamp = strftime("%Y%m%d%H%M%S");
mt_srand((double)microtime()*1000000);


// Order ID -  You can use any alphanumeric combination for the orderid.Although each transaction must have a unique orderid.
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


//A number of variables are needed to generate the request xml that is send to Elavon Payment Gateway.
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
exec("curl -k -s -m 120 -d \"$xml\" $URL -L", $return_message_array, $return_number);


//Go through each element in the array to make up the response
for ($i = 0; $i < count($return_message_array); $i++) {
    $response = $response.$return_message_array[$i];
}


/*
or the integrated cURL way.... (if you've compiled cURL into PHP)
$ch = curl_init();    
curl_setopt($ch, CURLOPT_URL, "https://remote.elavonpaymentgateway.com/remote");
curl_setopt($ch, CURLOPT_POST, 1); 
curl_setopt($ch, CURLOPT_USERAGENT, "payandshop.com php version 0.9"); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
$response = curl_exec ($ch);     
curl_close ($ch); 
*/


// Tidy it up
$response = eregi_replace ( "[[:space:]]+", " ", $response );
$response = eregi_replace ( "[\n\r]", "", $response );


// Parse the response xml
if (!xml_parse($xml_parser, $response)) {
    die(sprintf("XML error: %s at line %d",
           xml_error_string(xml_get_error_code($xml_parser)),
           xml_get_current_line_number($xml_parser)));
}

print $TSSChecks["3202"];

// garbage collect the parser.
xml_parser_free($xml_parser);



/*
 The "startElement()" function is called when an open element tag is found.
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

	// Uncomment the "print $currentElement;" line to see the names of all the variables you can see 
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

// The "endElement()" function is called when the closing tag of an element is found. 
// Just remove that element from the array of parent elements.

function endElement($parser, $name) {
    global $parentElements;
	global $currentTSSCheck;

	$currentTSSCheck = 0;
	array_pop($parentElements);
}

?>

//Print out response values
Timestamp: <? echo $RESPONSE_TIMESTAMP ?>
<br>
Result: <? echo $RESPONSE_RESULT ?>
<br>
Message: <? echo $RESPONSE_MESSAGE ?>
