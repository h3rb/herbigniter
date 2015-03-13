<?php  if ( ! defined('BASEPATH')) exit('You must configure the MassPayIPN.php script and remove this line.');

/**
 * Script massPayIPN
 * 
 * A PHP script to receive IPN responses from a MassPay operation and
 * record your fields on Database
 *
 * Based on this:
 * https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_r_MassPay
 *
 * LICENCE
 * This code has been placed in the Public Domain for all to enjoy.
 *
 *
 * @name 		massPayIPN
 * @author 		Diego Maradona Rodrigues <diegomr86@gmail.com>
 * @version 	1.0
 */

// Set whether or not you're in sandbox mode and also whether or not your web server has SSL or not.
$sandbox = true;
$ssl = false;


//DB connect credentials
$DB_Server = "localhost"; //your MySQL Server
$DB_Username = "root"; //your MySQL User Name
$DB_Password = ""; //your MySQL Password
$DB_DBName = "database"; //your MySQL Database Name


//create MySQL connection
$connect = mysql_connect($DB_Server, $DB_Username, $DB_Password)
or die("Couldn't connect to MySQL:<br>" . mysql_error() . "<br>" . mysql_errno());


//select database
$Db = mysql_select_db($DB_DBName, $connect)
or die("Couldn't select database:<br>" . mysql_error(). "<br>" . mysql_errno());


// Set end-point based on sandbox value
if($sandbox)
$ppHost = "www.sandbox.paypal.com";
else
$ppHost = "www.paypal.com";

// read the post from PayPal system and add 'cmd'
$req = 'cmd=_notify-validate';

// Store each $_POST value in a NVP string: 1 string encoded and 1 string decoded
foreach ($_POST as $key => $value)
{
	$value = urlencode(stripslashes($value));
	$req .= "&" . $key . "=" . $value;
	$IPNDecoded .= $key . " = " . urldecode($value) ."\n\n";
}

// post back to PayPal system to validate using SSL or not based on flag set above.
if($ssl)
{
	$header = '';
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Host: " . $ppHost . ":443\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen ('ssl://' . $ppHost, 443, $errno, $errstr, 30);
}
else
{
	$header = '';
	$header .= "POST /cgi-bin/webscr HTTP/1.0\r\n";
	$header .= "Host: " . $ppHost . ":80\r\n";
	$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
	$header .= "Content-Length: " . strlen($req) . "\r\n\r\n";
	$fp = fsockopen ($ppHost, 80, $errno, $errstr, 30);
}

if (!$fp)
{
	// HTTP Error validating data with PayPal.  Maybe email yourself here or just have the script try again.
}
else
{
	// Response from PayPal was good.  Now check to see if it returned verified or invalid.  Simply set $IsValud to true/false accordingly.
	fputs ($fp, $header . $req);
	while(!feof($fp))
	{
		$res = fgets ($fp, 1024);
		if(strcmp ($res, "VERIFIED") == 0)
		$IsValid = true;
		else if (strcmp ($res, "INVALID") == 0)
		$IsValid = false;
	}
	fclose ($fp);
}

// Basic Variables
$payer_id = $_REQUEST['payer_id'];
$payment_date = $_REQUEST['payment_date'];
$payment_status = $_REQUEST['payment_status'];
$charset = $_REQUEST['charset'];
$first_name = $_REQUEST['first_name'];
$notify_version = $_REQUEST['notify_version'];
$payer_status = $_REQUEST['payer_status'];
$verify_sign = $_REQUEST['verify_sign'];
$payer_email = $_REQUEST['payer_email'];
$payer_business_name = $_REQUEST['payer_business_name'];
$last_name = $_REQUEST['last_name'];
$txn_type = $_REQUEST['txn_type'];
$residence_country = $_REQUEST['residence_country'];
$test_ipn = $_REQUEST['test_ipn'];

$i = 1;

// While have multiple variables
while(isset($_POST['masspay_txn_id_' . $i]))
{

	// Multiple Variables
	$payment_gross = $_REQUEST['payment_gross_'.$i];
	$receiver_email = $_REQUEST['receiver_email_'.$i];
	$mc_currency = $_REQUEST['mc_currency_'.$i];
	$masspay_txn_id = $_REQUEST['masspay_txn_id_'.$i];
	$unique_id = $_REQUEST['unique_id_'.$i];
	$status = $_REQUEST['status_'.$i];
	$mc_gross = $_REQUEST['mc_gross_'.$i];
	$payment_fee = $_REQUEST['payment_fee_'.$i];
	$mc_fee = $_REQUEST['mc_fee_'.$i];


	//check if transaction ID has been processed before
	$checkquery = "SELECT masspay_txn_id FROM masspay_IPN WHERE masspay_txn_id = '".$masspay_txn_id."'";

	$myResult = mysql_query($checkquery) or die("Duplicate txn id check query failed:<br>" . mysql_error() . "<br>" . mysql_errno());

	$numRows = mysql_num_rows($myResult);

	if ($numRows == 0){

		//String Query
		$strQuery = "INSERT INTO masspay_IPN (
											payer_id,
											payment_date,
											payment_status,
											charset,
											first_name,
											notify_version,
											payer_status,
											verify_sign,
											payer_email,
											payer_business_name,
											last_name,
											txn_type,
											residence_country,
											test_ipn,
											payment_gross,
											receiver_email,
											mc_currency,
											masspay_txn_id,
											unique_id,
											status,
											mc_gross,
											payment_fee,
											mc_fee) 
									VALUES (
												'".$payer_id."',
												'".$payment_date."',
												'".$payment_status."',
												'".$charset."',
												'".$first_name."',
												".$notify_version.",
												'".$payer_status."',
												'".$verify_sign."',
												'".$payer_email."',
												'".$payer_business_name."',
												'".$last_name."',
												'".$txn_type."',
												'".$residence_country."',
												".$test_ipn.",
												".$payment_gross.",
												'".$receiver_email."',
												'".$mc_currency."',
												'".$masspay_txn_id."',
												'".$unique_id."',
												'".$status."',
												".$mc_gross.",
												".$payment_fee.",
												".$mc_fee.")";

		// Record IPN information on Database
		$myResult = mysql_query($strQuery) or die("Default - masspay_IPN, Query failed:<br>" . mysql_error() . "<br>" . mysql_errno());

	}
	$i++;
}

// Close Mysql Connection
mysql_close($connect);
?>