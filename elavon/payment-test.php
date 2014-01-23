<?php

//extract data from the post

extract($_POST);

//set POST variables

$url = 'https://demo.myvirtualmerchant.com/VirtualMerchantDemo/process.do';

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

'ssl_error_url' => 'http://localhost:8888/error/12345/?ajax=true',

//modify the value below from xxx to the location of your receipt script

'ssl_receipt_apprvl_get_url'=>'http://localhost:8888/response.php?ajax=true',

'ssl_transaction_type'=>urlencode('ccsale'),

'ssl_amount'=>urlencode('1.00'),

'ssl_card_number'=>urlencode('4111111111111111'),

'ssl_exp_date'=>urlencode('1214'),

'ssl_cvv2cvc2_indicator'=>urlencode($ssl_cvv2cvc2_indicator),

'ssl_cvv2cvc2'=>urlencode('123'),

'ssl_customer_code'=>urlencode('aducworth@gmail.com'),

'ssl_invoice_number'=>urlencode('1000'),

'ssl_first_name'=>'Austin',

'ssl_last_name'=>'Ducworth',

'ssl_avs_address'=>'PO Box 795',

'ssl_avs_zip'=>'29402',

'ssl_city'=>'Charleston',

'ssl_state'=>'29402',

'ssl_country'=>'USA',

'ssl_phone'=>'864.642.5163',

'ssl_email'=>'aducworth@gmail.com',

'ssl_ship_to_first_name'=>'Austin',

'ssl_ship_to_last_name'=>'Ducworth',

'ssl_ship_to_address1'=>'PO Box 795',

'ssl_ship_to_zip'=>'29402',

'ssl_ship_to_city'=>'Charleston',

'ssl_ship_to_state'=>'29402',

'ssl_ship_to_country'=>'USA',

);

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

//perform the curl post and store the result

$result = curl_exec($ch);

echo( 'testing' );

print_r( $result );

//close the curl session

curl_close($ch);

//a nice message to prevent people from seeing a blank screen

echo "Processing, please wait..."



?>