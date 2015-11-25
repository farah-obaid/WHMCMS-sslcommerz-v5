<?php

function sslcommerz_config() {
    $configarray = array(
     "FriendlyName" => array("Type" => "System", "Value"=>"sslcommerz"),
     "username" => array("FriendlyName" => "Store ID", "Type" => "text", "Size" => "1000", ),
     "password" => array("FriendlyName" => "Validation Password", "Type" => "text", "Size" => "100", ),
     "testmode" => array("FriendlyName" => "Test Mode", "Type" => "yesno", "Description" => "Tick this to test", ),
    );
	return $configarray;
}
function currency_convert($from,$to,$amount) {
//	$string = "1".$from."=?".$to;
//	echo $google_url = "http://www.google.com/ig/calculator?hl=en&q=".$string;
//	$result = file_get_contents($google_url);
//	$result = explode('"', $result);
//	$converted_amount = explode(' ', $result[3]);
//	$conversion = $converted_amount[0];
//	$conversion = $conversion * $amount;
//	$conversion = round($conversion, 2);
//	$rhs_text = ucwords(str_replace($converted_amount[0],"",$result[3]));
//	$rhs = $conversion.$rhs_text;
//	$price = preg_replace('_^\D+|\D+$_', "", $rhs);
//	return number_format($price, 2, '.', ',');
    
    $url  = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
    $data = file_get_contents($url);
    preg_match("/<span class=bld>(.*)<\/span>/",$data, $converted);
    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
    return round($converted, 3);
}
 


function sslcommerz_link($params) {

	# Gateway Specific Variables
	$gatewayusername = $params['username'];
	$gatewaytestmode = $params['testmode'];

	# Invoice Variables
	$invoiceid = $params['invoiceid'];
	$description = $params["description"];
        $amount = $params['amount']; # Format: ##.##
         $currency = $params['currency']; # Currency Code

	# Client Variables
	$firstname = $params['clientdetails']['firstname'];
	$lastname = $params['clientdetails']['lastname'];
	$email = $params['clientdetails']['email'];
	$address1 = $params['clientdetails']['address1'];
	$address2 = $params['clientdetails']['address2'];
	$city = $params['clientdetails']['city'];
	$state = $params['clientdetails']['state'];
	$postcode = $params['clientdetails']['postcode'];
	$country = $params['clientdetails']['country'];
	$phone = $params['clientdetails']['phonenumber'];

	# System Variables
	$companyname = $params['companyname'];
	$systemurl = $params['systemurl'];
	$currency = $params['currency'];
        if($currency=='BDT'){
	 $total=$amount;
        }  else {
            $total=currency_convert($currency, 'BDT',$amount);
        }
        //$total=100;
	$results = array();
    if ($gatewaytestmode == "on") {
        $url ='https://www.sslcommerz.com.bd/gwprocess/testbox/';
    } else {
        $url ='https://www.sslcommerz.com.bd/gwprocess/';
    }

	# Enter your code submit to the gateway...

	$code = '<form method="POST" action="'.$url.'">
<input type="hidden" name="store_id" value="'.$gatewayusername.'" />
<input type="hidden" name="tran_id" value="'.$invoiceid.'" />
<input type="hidden" name="total_amount" value="'.$total.'" />
<input type="hidden" name="success_url" value="'.$params['systemurl'].'/modules/gateways/callback/sslcommerz.php" />
<input type="hidden" name="fail_url" value="'.$params['systemurl'].'/modules/gateways/callback/sslcommerz.php" />
<input type="hidden" name="cancel_url" value="'.$params['systemurl'].'/modules/gateways/callback/sslcommerz.php" />

</form>';
        ///print_r($code);exit;
	return $code;
}



?>