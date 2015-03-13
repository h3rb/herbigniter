<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * HerbIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		HerbIgniter
 * @author		Herb
 * @copyright		Copyright 2009 (c) Gudagi 
 * @license		http://herbigniter.com/HI_user_guide/license.html
 * @link		http://herbigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * HerbIgniter Biz-ness Helpers
 *
 * @package		HerbIgniter
 * @subpackage		Helpers
 * @category		Helpers
 * @author		Herb
 * @link		http://herbigniter.com/HI_user_guide/helpers/biz_helper.html
 */

// ------------------------------------------------------------------------




//-----------------------------------------------------------------------

if ( !function_exists('authorize_net')) {
// By Maurice Rickard
function authorize_net($x_card_num, $x_exp_date, $x_invoice_num, $x_description,
		       $x_amount, $x_cust_id, $x_first_name, $x_last_name,
		       $x_company, $x_address, $x_city, $x_state, $x_zip,
		       $x_country, $x_phone, $x_fax, $x_email, $x_ship_to_first_name,
		       $x_ship_to_last_name, $x_ship_to_company, $x_ship_to_address,
		       $x_ship_to_city, $x_ship_to_state, $x_ship_to_zip, $x_ship_to_country,
		       $x_tax, $x_duty, $x_freight, $x_tax_exempt, $x_po_num) {

 if ($_SERVER['SERVER_NAME'] == 'maurice.local' || $_SERVER['SERVER_NAME'] != 'maurice-asc.local' || $_SERVER['SERVER_NAME'] != 'maurice-asc.westell.com') {
	$DEBUGGING					= 1;				# Display additional information to track down problems
	$TESTING					= 1;				# Set the testing flag so that transactions are not live
	$ERROR_RETRIES				= 2;				# Number of transactions to post if soft errors occur
	$auth_net_login_id			= "7wYB5c6R";
	$auth_net_tran_key			= "4px54kx6ZZ7489Gq";
	$auth_net_url				= "https://test.authorize.net/gateway/transact.dll";
	$auth_net_host              = 'test.authorize.net';
	$auth_net_path              = '/gateway/transact.dll';
 } else { // live
	$DEBUGGING					= 0;				# Display additional information to track down problems
	$TESTING					= 0;				# Set the testing flag so that transactions are not live
	$ERROR_RETRIES				= 2;				# Number of transactions to post if soft errors occur
	$auth_net_login_id			= '';
	$auth_net_tran_key			= '';
	$auth_net_url				= "https://secure.authorize.net/gateway/transact.dll";
	$auth_net_host              = 'secure.authorize.net';
	$auth_net_path              = '/gateway/transact.dll';
 }
//$auth_net_url				= "https://certification.authorize.net/gateway/transact.dll";
#  Uncomment the line ABOVE for test accounts or BELOW for live merchant accounts
#  $auth_net_url				= "https://secure.authorize.net/gateway/transact.dll";

 $authnet_values				= array(
	'x_login'				=> $auth_net_login_id,
	'x_version'				=> '3.1',
	'x_delim_char'			=> '|',
	'x_delim_data'			=> 'TRUE',
	'x_type'				=> 'AUTH_CAPTURE',
	'x_method'				=> 'CC',
 	'x_tran_key'			=> $auth_net_tran_key,
 	'x_relay_response'		=> 'FALSE',
	'x_card_num'			=> $x_card_num,
	'x_exp_date'			=> $x_exp_date,
	'x_description'			=> $x_description,
	'x_amount'				=> number_format($x_amount, 2, '.',''),
	'TESTING'				=> $TESTING,
	'x_invoice_num'			=> $x_invoice_num,
	'x_cust_id'				=> $x_cust_id,
	'x_first_name'			=> $x_first_name,
	'x_last_name'			=> $x_last_name,
	'x_company'				=> $x_company,
	'x_address'				=> $x_address,
	'x_city'				=> $x_city,
	'x_state'				=> $x_state,
	'x_zip'					=> $x_zip,
	'x_country'				=> $x_country,
	'x_phone'				=> $x_phone,
	'x_fax'					=> $x_fax,
	'x_email'				=> $x_email,
	'x_ship_to_first_name'	=> $x_ship_to_first_name,
	'x_ship_to_last_name'	=> $x_ship_to_last_name,
	'x_ship_to_company'		=> $x_ship_to_company,
	'x_ship_to_address'		=> $x_ship_to_address,
	'x_ship_to_city'		=> $x_ship_to_city,
	'x_ship_to_state'		=> $x_ship_to_state,
	'x_ship_to_zip'			=> $x_ship_to_zip,
	'x_ship_to_country'		=> $x_ship_to_country,
	'x_tax'					=> $x_tax,
	'x_freight'				=> $x_freight,
	'x_po_num'				=> $x_po_num,
 );


 $fields = "";
 foreach( $authnet_values as $key => $value ) $fields .= "$key=" . urlencode( $value ) . "&";

 //echo $fields;

 try {
	$ch = curl_init($auth_net_url); 
	curl_setopt($ch, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
	curl_setopt($ch, CURLOPT_POSTFIELDS, rtrim( $fields, "& " )); // use HTTP POST to send form data
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response. ###
	$resp = curl_exec($ch); //execute post and get results
	curl_close ($ch);

 } catch (Exception $err) {

	//$content = file_get_contents('https://remoteserver.com/path/script.php?name=value'); 
	//POST is a little more complicated, but once you learn how to send POST 
	//headers and read HTTP(S) resposes, it gets really easy: 
	$resp = ''; 
	$flag = false; 
	//$post_query = 'a=1&b=2'; // name-value pairs 
	$post_query = urlencode($fields) . "\r\n"; 
	$host = $auth_net_host; 
	$path = $auth_net_path; 
	//$fp = fsockopen($host, '80'); 
	// This is plain HTTP; for HTTPS, use 
	$fp = fsockopen($host, '443'); 
	if ($fp) { 
	  fputs($fp, "POST $path HTTP/1.0\r\n"); 
	  fputs($fp, "Host: $host\r\n"); 
	  fputs($fp, "Content-length: ". strlen($post_query) ."\r\n\r\n"); 
	  fputs($fp, $post_query); 
	  while (!feof($fp)) { 
		$line = fgets($fp, 10240); 
		if ($flag) { 
		  $resp .= $line; 
		} else { 
		  $headers .= $line; 
		  if (strlen(trim($line)) == 0) { 
			$flag = true; 
		  } 
		} 
	  } 
	  fclose($fp); 
	
	}  // end if FP worked


 } //end catch
 //echo $resp;
 $howMany = substr_count($resp, "|");

 $text = $resp;
 $h = substr_count($text, "|");
 $h++;

 $response_from_server = FALSE;
 $body = '';

	for($j=1; $j <= $h; $j++){

	$p = strpos($text, "|");

	if ($p === false) { // note: three equal signs
		//not a valid response
		
	} else {
		$response_from_server = TRUE;

		$p++;

		//  We found the x_delim_char and accounted for it . . . now do something with it

		//  get one portion of the response at a time
		$pstr = substr($text, 0, $p);

		//  this prepares the text and returns one value of the submitted
		//  and processed name/value pairs at a time
		//  for AIM-specific interpretations of the responses
		//  please consult the AIM Guide and look up
		//  the section called Gateway Response API
		$pstr_trimmed = substr($pstr, 0, -1); // removes "|" at the end

		if($pstr_trimmed==""){
			$pstr_trimmed="NO VALUE RETURNED";
		}


		switch($j){
			case 1:
				$body .= "Response Code: ";
				$fval="";
				if($pstr_trimmed=="1"){
					$fval="Approved";
				}elseif($pstr_trimmed=="2"){
					$fval="Declined";
				}elseif($pstr_trimmed=="3"){
					$fval="Error";
				}

				$Response_Code = $fval;
				break;
			case 2:
				$Response_Subcode = $pstr_trimmed;
				break;
			case 3:
				$Response_Reason_Code = $pstr_trimmed;
				break;
			case 4:
				$Response_Reason_Text = $pstr_trimmed;
				break;
			case 5:
				$Approval_Code = $pstr_trimmed;
				break;
			case 6:
				$AVS_Result_Code = $pstr_trimmed;
				break;
			case 7:
				$Transaction_ID = $pstr_trimmed;
				break;
			case 8:
				$Invoice_Number = $pstr_trimmed;
				break;
			case 9:
				$Description = $pstr_trimmed;
				break;
			case 10:
				$Amount = $pstr_trimmed;
				break;
			case 11:
				$Method = $pstr_trimmed;
				break;
			case 12:
				$Transaction_Type = $pstr_trimmed;
				break;
			case 13:
				$Customer_ID = $pstr_trimmed;
				break;
			case 38:
				$MD5_Hash = $pstr_trimmed;
				break;
			case 39:
				$fval="";
				if($pstr_trimmed=="M"){
					$fval="M = Match";
				}elseif($pstr_trimmed=="N"){
					$fval="N = No Match";
				}elseif($pstr_trimmed=="P"){
					$fval="P = Not Processed";
				}elseif($pstr_trimmed=="S"){
					$fval="S = Should have been present";
				}elseif($pstr_trimmed=="U"){
					$fval="U = Issuer unable to process request";
				}else{
					$fval="NO VALUE RETURNED";
				}
				$Card_Code_Response = $fval;
				break;
			default:

				break;
		}
		// remove the part that we identified and work with the rest of the string
		$text = substr($text, $p);
	}
 } // end for

 if ($response_from_server) {
 $return_array = array(
	'Response_Code' => $Response_Code,
	'Response_Subcode' => $Response_Subcode,
	'Response_Reason_Code' => $Response_Reason_Code,
	'Response_Reason_Text' => $Response_Reason_Text,
	'Approval_Code' => $Approval_Code,
	'AVS_Result_Code' => $AVS_Result_Code,
	'Transaction_ID' => $Transaction_ID,
	'Invoice_Number' => $Invoice_Number,
	'Description' => $Description,
	'Amount' => $Amount,
	'Method' => $Method,
	'Transaction_Type' => $Transaction_Type,
	'MD5_Hash' => $MD5_Hash,
	'Card_Code_Response' => $Card_Code_Response,
 );

 return $return_array;
 }
}// end function authorize_net
}


/*
 * The following code was taken from PayPal.com and modified to fit all of the
 * functionality of PayPal into this helper.
 *
 * The code has been 'unwrapped' in places.  It's intended to be exactly like PP,
 * and should be because I was careful.
 */

/**
 * Send HTTP POST Request for PayPal Masspay NVP last modified 08MAY23.
 *
 * @param	string	email's subject:
 * @param	string  payer address
 * @param       string  environment: sandbox, beta-sandbox or 'live'
 * @param	string  currency code ie: USD
 * @param	string  an array( 'username'=>'', 'password'=>'', 'signature'=>'' )
 * @return	boolean or response array
 */
if ( !function_exists('PayPal_Button') ) {
function PayPal_Button( $payer_email, $item_name, $amount, $currency,
		        $alt='PayPal - The safer, easier way to pay online',
		        $image='https://www.paypal.com/en_US/i/btn/btn_buynow_LG.gif',
			$type_titles=NULL, $types=NULL, $options=NULL, $default=NULL, $extra='<br>' ) {
 $output =
        '<form action="https://www.paypal.com/cgi-bin/webscr" method="post"> <input type="hidden" name="business" value="' . $payer_email
      . '"> <input type="hidden" name="cmd" value="_xclick"><input type="hidden" name="item_name" value="' . $item_name. '">'
      . '<input type="hidden" name="' . $amount . '" value="' . $amount . '"> <input type="hidden" name="currency_code" value="' . $currency . '">';
 if ( !is_null($types) ) {
  for( $n=0; $n<$c; $n++ ) {
   $tt=array_pop($type_titles);
   $t=array_pop($types);
   $d=array_pop($defaults);
   $o=array_pop($options);
   $output .=
        '<input type="hidden" name="on' . $n
      . '" value="' . $t . '">'
      . $tt
      . '<select name="os' . $n . '">';
   foreach( $o as $k=>$v ) 
	$output .= '<option value="'.$v.'"' . ($default == $v ? 'selected' : '') . '>'.$k.'</option>';
   $output .= $extra;
  }
 } 
 $output .= '<input type="image" name="submit" border="0" src="' . $image . '" alt="' . $alt . '">'
      . ' </form> ';
 return $output;
}
}

if ( !function_exists('DoMassPayRequest') ) {
function DoMassPayRequest( $paypal_credentials, $emailSubject, $emailAddress, $currency, $payees, $environment='beta-sandbox', $ignore=false ) {

	$emailSubject =urlencode($emailSubject);
	$receiverType = urlencode($emailAddress);
	$currency = urlencode($currency);		// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
	$methodName_ = 'MassPay';
	// Add request-specific fields to the request string.
	$nvpStr="&EMAILSUBJECT=$emailSubject&RECEIVERTYPE=$receiverType&CURRENCYCODE=$currency";

	$receiversArray = array();

	$n=0;
	foreach ( $payees as $payee ) { 
	$receiverData = array(	'receiverEmail' => $payee['email'],
				       'amount' => $payee['amount'],
				     'uniqueID' => $payee['id'],
					 'note' => $payee['note'] );
	$receiversArray[$n++] = $receiverData;
	}

	foreach($receiversArray as $i => $receiverData) {
	$receiverEmail = urlencode($receiverData['receiverEmail']);
	$amount = urlencode($receiverData['amount']);
	$uniqueID = urlencode($receiverData['uniqueID']);
	$note = urlencode($receiverData['note']);
	$nvpStr .= "&L_EMAIL$i=$receiverEmail&L_Amt$i=$amount&L_UNIQUEID$i=$uniqueID&L_NOTE$i=$note";
	}

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($paypal_credentials['username']);
	$API_Password = urlencode($paypal_credentials['password']);
	$API_Signature = urlencode($paypal_credentials['signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

        if ( $ignore === true ) {
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) return true;
		return false;		
	}
	
	return $httpParsedResponseAr;
}
}

/** DoAuthorization NVP example; last modified 08MAY23.
 *
 *  Authorize a payment.
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
if ( !function_exists('DoAuthorize') ) {
function DoAuthorize( $paypal_credentials, $transactionID, $amount, $currency, $environment='beta-sandbox', $ignore=false ) {
	
	$methodName_ = 'DoAuthorization';

	// Set request-specific fields.
	$transactionID = urlencode($transactionID);
	$amount = urlencode($amount);
	$currency = urlencode($currency);							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')
	$trxType = urlencode('V');

	// Add request-specific fields to the request string.
	$nvpStr="&TRANSACTIONID=$transactionID&AMT=$amount&CURRENCYCODE=$currency&TRXTYPE=$trxType";

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($paypal_credentials['username']);
	$API_Password = urlencode($paypal_credentials['password']);
	$API_Signature = urlencode($paypal_credentials['signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}
	
        if ( $ignore === true ) {
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) return true;
		return false;		
	}
	
	return $httpParsedResponseAr;
}
}


/** DoReauthorization NVP example; last modified 08MAY23.
 *
 *  Reauthorize a previously authorized payment. 
*/

/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
if ( !function_exists('DoReauthorization') ) {
function DoReauthorization( $paypal_credentials, $auth_id, $amount, $currency, $environment='beta-sandbox', $ignore=false ) {
	// Set request-specific fields.
	$authorizationID = urlencode($auth_id);
	$amount = urlencode($amount);
	$currency = urlencode($currency);							// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

	// Add request-specific fields to the request string.
	$nvpStr="&AUTHORIZATIONID=$authorizationID&AMT=$amount&CURRENCYCODE=$currency";

	$methodName_='DoReauthorization'; 

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($paypal_credentials['username']);
	$API_Password = urlencode($paypal_credentials['password']);
	$API_Signature = urlencode($paypal_credentials['signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

        if ( $ignore === true ) {
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) return true;
		return false;		
	}
	
	return $httpParsedResponseAr;
}
}

//----------------------------------------------------------------------------------------------------

/**
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
if ( !function_exists('DoDirectPayment') ) {
function DoDirectPayment( $paypal_credentials,
			$firstName, $lastName, $ccType, $ccNumber, $expDateMonth, $expDateY, $cvv2, $addr1, $addr2, $city, $state, $zip, $country,
		        $amount, $currency, $environment='beta-sandbox', $ignore=false ) {
	// Set request-specific fields.
	$paymentType = urlencode('Authorization');				// or 'Sale'
	$firstName = urlencode($firstName);
	$lastName = urlencode($lastName);
	$creditCardType = urlencode($ccType);
	$creditCardNumber = urlencode($ccNumber);
	//$expDateMonth = 'cc_expiration_month';
	// Month must be padded with leading zero
	$padDateMonth = urlencode(str_pad(trim($expDateMonth), 2, '0', STR_PAD_LEFT));
	
	$expDateYear = urlencode($expDateY);
	$cvv2Number = urlencode($cvv2);
	$address1 = urlencode($addr1);
	$address2 = urlencode($addr2);
	$city = urlencode($city);
	$state = urlencode($state);
	$zip = urlencode($zip);
	$country = urlencode($country);	// US or other valid country code
	$amount = urlencode($amount);
	$currencyID = urlencode($currency);

	$methodName_ = 'DoDirectPayment';

	// Add request-specific fields to the request string.
	$nvpStr =	"&PAYMENTACTION=$paymentType&AMT=$amount&CREDITCARDTYPE=$creditCardType&ACCT=$creditCardNumber".
			"&EXPDATE=$padDateMonth$expDateYear&CVV2=$cvv2Number&FIRSTNAME=$firstName&LASTNAME=$lastName".
			"&STREET=$address1&CITY=$city&STATE=$state&ZIP=$zip&COUNTRYCODE=$country&CURRENCYCODE=$currencyID";

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($paypal_credentials['username']);
	$API_Password = urlencode($paypal_credentials['password']);
	$API_Signature = urlencode($paypal_credentials['signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

        if ( $ignore === true ) {
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) return true;
		return false;		
	}
	
	return $httpParsedResponseAr;
}
}



/** DoVoid NVP PayPal; last modified 08MAY23.
 *
 *  Cancel a previously authorized payment. 
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
if ( !function_exists('DoVoid') ) {
function DoVoid( $paypal_credentials, $auth_id, $note, $environment='beta-sandbox', $ignore=false ) {

	// Set request-specific fields.
	$authorizationID = urlencode($auth_id);
	$note = urlencode($note);

	// Add request-specific fields to the request string.
	$nvpStr="&AUTHORIZATIONID=$authorizationID&NOTE=$note";
	$methodName_ = 'DoVoid';

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($paypal_credentials['username']);
	$API_Password = urlencode($paypal_credentials['password']);
	$API_Signature = urlencode($paypal_credentials['signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}


        if ( $ignore === true ) {
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) return true;
		return false;		
	}
	
	return $httpParsedResponseAr;
}
}


/** DoCapture NVP example; last modified 08MAY23.
 *
 *  Capture a payment. 
*/
if ( !function_exists('DoCapture') ) {
function DoCapture( $paypal_credentials, $auth_id, $amount, $currency, $invoice_id, $note, $environment='beta-sandbox', $ignore=false ) {
	
	// Set request-specific fields.
	$authorizationID = urlencode($auth_id);
	$amount = urlencode($amount);
	$currency = urlencode($currency);
	$completeCodeType = urlencode('Complete');	// or 'NotComplete'
	$invoiceID = urlencode($invoice_id);
	$note = urlencode($note);

	// Add request-specific fields to the request string.
	$nvpStr="&AUTHORIZATIONID=$authorizationID&AMT=$amount&COMPLETETYPE=$completeCodeType&CURRENCYCODE=$currency&NOTE=$note";

	$methodName_ = 'DoCapture';

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($paypal_credentials['username']);
	$API_Password = urlencode($paypal_credentials['password']);
	$API_Signature = urlencode($paypal_credentials['signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

        if ( $ignore === true ) {
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) return true;
		return false;		
	}
	
	return $httpParsedResponseAr;
}
}




/**
 * Request PayPal Account Balance
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */

if ( !function_exists('GetBalance') ) {
function GetBalance( $paypal_credentials, $environment='beta-sandbox', $ignore=false ) {
	$nvpStr="";
        $methodName_ = 'GetBalance';

	$API_UserName = urlencode($paypal_credentials['username']);
	$API_Password = urlencode($paypal_credentials['password']);
	$API_Signature = urlencode($paypal_credentials['signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// setting the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// turning off the server and peer verification(TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// NVPRequest for submitting to server
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";

	// setting the nvpreq as POST FIELD to curl
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// getting response from server
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the RefundTransaction response details
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

        if ( $ignore === true ) {
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) return true;
		return false;		
	}
	
	return $httpParsedResponseAr;
}
}





/** RefundTransaction NVP example; last modified 08MAY23.
 *
 *  Issue a refund for a prior transaction. 
 * Send HTTP POST Request
 *
 * @param	string	The API method name
 * @param	string	The POST Message fields in &name=value pair format
 * @return	array	Parsed HTTP Response body
 */
if ( !function_exists('DoRefund') ) {
function DoRefund( $paypal_credentials, $transactionID, $refundType='Full', $currency='USD', $amount=NULL, $memo=NULL, $environment='beta-sandbox', $ignore=false ) {

	// Set request-specific fields.
	$transactionID = urlencode($transactionID);
	$refundType = urlencode($refundType);			// 'Full' or 'Partial'
	$currencyID = urlencode($currency);			// or other currency ('GBP', 'EUR', 'JPY', 'CAD', 'AUD')

	// Add request-specific fields to the request string.
	$nvpStr = "&TRANSACTIONID=$transactionID&REFUNDTYPE=$refundType&CURRENCYCODE=$currencyID";

	if(strcasecmp($refundType, 'Partial') == 0) {
		if(is_null($amount)) {
			exit('Partial Refund Amount is not specified.');
		} else {
	 		$nvpStr = $nvpStr."&AMT=$amount";
		}
		if(is_null($memo)) $memo='PayPal Refund: Partial Refund Memo is not specified.';		
	}


	if(!is_null($memo)) {
		$nvpStr .= "&NOTE=$memo";
	}
	
	$methodName_='RefundTransaction';

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($paypal_credentials['username']);
	$API_Password = urlencode($paypal_credentials['password']);
	$API_Signature = urlencode($paypal_credentials['signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

        if ( $ignore === true ) {
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) return true;
		return false;		
	}
	
	return $httpParsedResponseAr;
}
}


/** TransactionSearch NVP example; last modified 08MAY23.
 *
 *  Search your account history for transactions that meet the criteria you specify. 
*/


if ( !function_exists('GetTransactions') ) { // in 'mm/dd/ccyy' format
function GetTransactions( $paypal_credentials, $transactionID, $startDateStr=NULL, $endDateStr=NULL, $environment='beta-sandbox', $ignore=false ) {
	
	// Set request-specific fields.
	$transactionID = urlencode($transactionID);
	
	$methodName_ = 'TransactionSearch';
	
	// Add request-specific fields to the request string.
	$nvpStr = "&TRANSACTIONID=$transactionID";

	// Set additional request-specific fields and add them to the request string.
	if(!is_null($startDateStr)) {
	   $start_time = strtotime($startDateStr);
	   $iso_start = date('Y-m-d\T00:00:00\Z',  $start_time);
	   $nvpStr .= "&STARTDATE=$iso_start";
	  }

	if(!is_null($endDateStr)&&$endDateStr!='') {
	   $end_time = strtotime($endDateStr);
	   $iso_end = date('Y-m-d\T24:00:00\Z', $end_time);
	   $nvpStr .= "&ENDDATE=$iso_end";
	}	

	// Set up your API credentials, PayPal end point, and API version.
	$API_UserName = urlencode($paypal_credentials['username']);
	$API_Password = urlencode($paypal_credentials['password']);
	$API_Signature = urlencode($paypal_credentials['signature']);
	$API_Endpoint = "https://api-3t.paypal.com/nvp";
	if("sandbox" === $environment || "beta-sandbox" === $environment) {
		$API_Endpoint = "https://api-3t.$environment.paypal.com/nvp";
	}
	$version = urlencode('51.0');

	// Set the curl parameters.
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $API_Endpoint);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);

	// Turn off the server and peer verification (TrustManager Concept).
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);

	// Set the API operation, version, and API signature in the request.
	$nvpreq = "METHOD=$methodName_&VERSION=$version&PWD=$API_Password&USER=$API_UserName&SIGNATURE=$API_Signature$nvpStr_";

	// Set the request as a POST FIELD for curl.
	curl_setopt($ch, CURLOPT_POSTFIELDS, $nvpreq);

	// Get response from the server.
	$httpResponse = curl_exec($ch);

	if(!$httpResponse) {
		exit("$methodName_ failed: ".curl_error($ch).'('.curl_errno($ch).')');
	}

	// Extract the response details.
	$httpResponseAr = explode("&", $httpResponse);

	$httpParsedResponseAr = array();
	foreach ($httpResponseAr as $i => $value) {
		$tmpAr = explode("=", $value);
		if(sizeof($tmpAr) > 1) {
			$httpParsedResponseAr[$tmpAr[0]] = $tmpAr[1];
		}
	}

	if((0 == sizeof($httpParsedResponseAr)) || !array_key_exists('ACK', $httpParsedResponseAr)) {
		exit("Invalid HTTP Response for POST request($nvpreq) to $API_Endpoint.");
	}

        if ( $ignore === true ) {
		if("SUCCESS" == strtoupper($httpParsedResponseAr["ACK"]) || "SUCCESSWITHWARNING" == strtoupper($httpParsedResponseAr["ACK"])) return true;
		return false;		
	}
	
	return $httpParsedResponseAr;
}
}

/* End of file biz_helper.php */
/* Location: ./system/helpers/biz_helper.php */