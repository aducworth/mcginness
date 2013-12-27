<?php



//extract data from the post

extract($_POST);

//set POST variables

$url = 'https://demo.myvirtualmerchant.com/VirtualMerchant/process.do';

//Modify the values from xxx to your own account ID, user ID, and PIN

//Additional fields can be added as necessary to support custom fields

//or required fields configured in the Virtual Merchant terminal

$fields = array(

'ssl_merchant_id'=>'002536',

//Vi rtualMerchant Developer's Guide.docx Page 138 of 152

'ssl_user_id'=>'webpage',

'ssl_pin'=>'ZGWD24',

'ssl_show_form'=>'false',

'ssl_result_format'=>'html',

'ssl_test_mode'=>'false',

'ssl_receipt_apprvl_method'=>'redg',

//modify the value below from xxx to the location of your error script

'ssl_error_url' => 'http://localhost:8888/error.php',

//modify the value below from xxx to the location of your receipt script

'ssl_receipt_apprvl_get_url'=>'http://localhost:8888/response.php',

'ssl_transaction_type'=>urlencode('ccsale'),

'ssl_amount'=>urlencode('1.00'),

'ssl_card_number'=>urlencode('41********1111'),

'ssl_exp_date'=>urlencode('1208'),

'ssl_cvv2cvc2_indicator'=>urlencode($ssl_cvv2cvc2_indicator),

'ssl_cvv2cvc2'=>urlencode($ssl_cvv2cvc2),

'ssl_customer_code'=>urlencode($ssl_customer_code),

'ssl_invoice_number'=>urlencode($ssl_invoice_number),

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

//close the curl session

curl_close($ch);

//a nice message to prevent people from seeing a blank screen

echo "Processing, please wait..."



?>