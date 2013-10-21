<?php

/*============================ Disclaimer ====================================

Copyright 2004 Dialect Solutions Holdings.  All rights reserved.

This document is provided by Dialect Holdings on the basis that you will treat
it as confidential.

No part of this document may be reproduced or copied in any form by any means
without the written permission of Dialect Holdings.  Unless otherwise expressly
agreed in writing, the information contained in this document is subject to
change without notice and Dialect Holdings assumes no responsibility for any
alteration to, or any error or other deficiency, in this document.

All intellectual property rights in the Document and in all extracts and things
derived from any part of the Document are owned by Dialect and will be assigned
to Dialect on their creation. You will protect all the intellectual property
rights relating to the Document in a manner that is equal to the protection
you provide your own intellectual property.  You will notify Dialect
immediately, and in writing where you become aware of a breach of Dialect's
intellectual property rights in relation to the Document.

The names "Dialect", "QSI Payments" and all similar words are trademarks of
Dialect Holdings and you must not use that name or any similar name.

Dialect may at its sole discretion terminate the rights granted in this
document with immediate effect by notifying you in writing and you will
thereupon return (or destroy and certify that destruction to Dialect) all
copies and extracts of the Document in its possession or control.

Dialect does not warrant the accuracy or completeness of the Document or its
content or its usefulness to you or your merchant customers.   To the extent
permitted by law, all conditions and warranties implied by law (whether as to
fitness for any particular purpose or otherwise) are excluded.  Where the
exclusion is not effective, Dialect limits its liability to $100 or the
resupply of the Document (at Dialect's option).

Data used in examples and sample data files are intended to be fictional and
any resemblance to real persons or companies is entirely coincidental.

Dialect does not indemnify you or any third party in relation to the content or
any use of the content as contemplated in these terms and conditions.

Mention of any product not owned by Dialect does not constitute an endorsement
of that product.

This document is governed by the laws of New South Wales, Australia and is
intended to be legally binding.

==============================================================================
 
This example assumes that a form has been sent to this example with the
required fields. The example then processes the command and displays the
receipt or error to a HTML page in the users web browser.

@author Dialect Payment Solutions Pty Ltd Group 

==============================================================================

Version 3.1

==============================================================================

  Please refer to the following guides for more information:
      1. Payment Client Integration Guide
         This details how to integrate with Payment Client 3.0.
      2. Payment Client Reference Guide
         This guide details all the input and return parameters that are used
         by the Payment Client and Payment Server for a Payment Client
         integration.
      3. Payment Client Install Guide
         This guide details the installation of Payment Client 3.0 and related
         issues.
 
  For a definitive list of QSI Response Codes please refer to the Payment
  Client Reference Guide. For a list of the QSI Response Codes used in this
  example please refer to the 'getResponseDescription' sub.
 
  This example assumes that a form has been sent to this example with the
  required fields. The example then processes the command and displays the
  result or error to a HTML page in the users web browser.*/
 

// Initialisation
// ==============

// Define Constants
// ----------------
// This is the time that the example will allow for the socket to complete
// sending a command to the Payment Client in seconds
$SHORT_SOCKET_TIMEOUT = 2;
$MEDIUM_SOCKET_TIMEOUT  = 5;

// This is the status character in the socket response that indicates that a
// command was run successfully
$OK = "1";

// Define Variables
// ----------------
// Set exception and socket response values
$exception = "";
$cmdResponse = "";

// this debug parameter simply allows the user to see the sockets
// commands going to/from the Payment Client socket listener. 
// This is not required for production code.
$getDebug = array_key_exists("DebugOn", $_GET) ? $_GET["DebugOn"] : "0";

//   if debug = true then sockets information output to screen
//   if debug = false then NO sockets information output to screen
$debug = false;
if ($getDebug == "1") {
	$debug = true;
}

// Set error message flag and socket created OK flag
$errorExists = false;
$socketCreated = true;

// receipt variables
$merchTxnRef     = "";
$orderInfo       = "";
$merchantID      = "";
$qsiResponseCode = "";
$receiptNo       = "";
$acqResponseCode = "";
$authorizeID     = "";
$batchNo         = "";
$amount          = "";
$transactionNr   = ""; 
$cardType        = ""; 
$cscResultCode   = ""; 
$receiptKeys     = ""; 

// *********************
// START OF MAIN PROGRAM
// *********************

// Step 1 - Check for an encrypted Digital Receipt
// ===============================================
$digitalReceipt= array_key_exists("DR", $_GET) ? $_GET["DR"] : "";

// Check to make sure the Digital Receipt was recieved
if (strlen($digitalReceipt) == 0) {
    // Display an Error Page as the Digital Order was not created properly
    $message = "(4) An empty Digital Receipt was received from the Payment Server";
    $errorExists = true;
    $socketCreated = false;
}

// Step 2 - Connect to the Payment Client
// ======================================
// Payment Client IP number. In a production system you would not pass 
// in this value, but hard it into the program
$payClientIP = array_key_exists("HostName", $_GET) ? $_GET["HostName"] : "";

// Payment Client Port number. In a production system you would not pass 
// in this value, but hard it into the program
$payClientPort = intval(array_key_exists("Port", $_GET) ? $_GET["Port"] : "");

// Initialise the Payment Client socket connection
$payClientSocket = -1;

// Create the socket connection to the Payment Client
$errno = "";
$errstr = "";

// This is the time (in seconds) that the example will allow for the socket to
// complete sending a command to the Payment Client(refer to Payment Client
// Integration Guide)
$payClientTimeout = $SHORT_SOCKET_TIMEOUT;

// create the socket connection
if (strlen($payClientIP) > 0 && $payClientPort > 0) {
    $payClientSocket = fsockopen($payClientIP, $payClientPort, $errno, $errstr, $payClientTimeout);
} else {
    $payClientPort = $payClientPort != 0 ? $payClientPort : "";
    $message = "(44) Incorrect data to create a socket connection to Payment Client - Host:$payClientIP Port:$payClientPort";     
    $errorExists = true;
    $socketCreated = false;
}

if (!$errorExists && $payClientSocket < 1) {
    // Display an error page as the socket connection failed
    $message = "(45) Failed to create a socket connection to Payment Client - Host:$payClientIP Port:$payClientPort";
    $errorExists = true;
    $socketCreated = false;
};

// Step 3 - Test the Payment Client socket was created successfully
// ================================================================
// Test the communication to the Payment Client using an "echo" command.
// This is a local command that only communicates to the Payment Client and
// nothing is sent to the Payment Server.
if (!$errorExists) {
   $cmdResponse = sendCommand($payClientSocket,"1,Test",$debug);
    if (substr($cmdResponse,2) != "echo:Test") {
        // Display an error as the communication to the Payment Client failed
        $message = "(5) Failed to complete echo test to Payment Client - should be: Should be: '1,echo:Test', but received: '$cmdResponse'";
        $errorExists = true;
    }
}

// Step 4 - Decrypt Digital Receipt
// ********************************
if (!$errorExists) {
	// Set the socket timeout value to medium value for the primary
	// command as takes a little longer to encrypt the data.
	$payClientTimeout = $MEDIUM_SOCKET_TIMEOUT;

	// (This primary command also receives the encrypted Digital Receipt)
    $cmdResponse = sendCommand($payClientSocket,"3,$digitalReceipt",$debug);
    if (substr($cmdResponse,0,1) != $OK) {
        // Retrieve the Payment Client Error (There may be none to retrieve)
        $cmdResponse = sendCommand($payClientSocket,"4,PaymentClient.Error",$debug);
		if (substr($cmdResponse,0,1) == $OK) {
            $exception = substr($cmdResponse,2);
        }
        // Display an error message as the command failed
        $message = "(11) Digital Order has not created correctly - decryptDigitalReceipt($digitalReceipt) failed.";
        $errorExists = true;
    }
}

// Step 5 - Check the Digital Receipt to see if there is a valid result
// ====================================================================
// Use the "nextResult" command to check if the Payment Client contains a
// valid Hash table containing the receipt results.
if (!$errorExists) {
	// Set the socket timeout value back to the short value for the
	// following supplementary commands
	$payClientTimeout = $SHORT_SOCKET_TIMEOUT;

	$cmdResponse = sendCommand($payClientSocket,"5",$debug);
    if (substr($cmdResponse,0,1) != $OK) {
        // Retrieve the Payment Client Error (There may be none to retrieve)
        if ($payClientSocket != "") {
            $cmdResponse = sendCommand($payClientSocket,"4,PaymentClient.Error",$debug);
            if (substr($cmdResponse,0,1) == $OK) {
                $exception = substr($cmdResponse,2);
            }
        }
        // Display an error message as the command failed
        $message = "(12) No Results for Digital Receipt";
        $errorExists = true;
    }
}

// Step 6 - Get the result data from the Digital Receipt
// =====================================================
// Check the QSIResponseCode for the transaction from the Payment Client Hash
// table. This is the most important result field and indicates the status of 
// the transaction. If the QSIResponseCode doesn't exist, this is an error
// condition.
$qsiResponseCode = "null";
if (!$errorExists) {
    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.QSIResponseCode", $debug);
    if (substr($cmdResponse,0,1) != $OK) {
        // Display an error message as the command failed
        $message = "(23) No result for this field: 'DigitalReceipt.QSIResponseCode'. Received: .$cmdResponse";
        $errorExists = true;
    } else {
        $qsiResponseCode = substr($cmdResponse,2);
    }
}

// If the QSIResponseCode is '0' the transaction is successful.
// If the QSIResponseCode is not '0' the transaction has beed declined or an 
// error condition was detected, such as the customer typed in an invalid card
// number.
if (!$errorExists) {
    if ($qsiResponseCode != "0") {
        // The response may contain an error message so find out what the error
        // is. 'DigitalReceipt.ERROR' may not contain any error information as 
        // the transaction may have simply been declined - 'Insufficient Funds'
        // (QSIResponseCode='5',no further error message required)
        $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.ERROR", $debug);
        if (substr($cmdResponse,0,1) == $OK) {
            $exception = substr($cmdResponse,2);
            $message = "(24) Error detected by Payment Server";
            $errorExists = true;
        }
    }
}

// Use the "getAvailableFieldKeys" command to obtain a list of keys available
// within the Payment Client Hash table containing the receipt results.
// This is a local command that only communicates to the Payment Client and
// nothing is sent to the Payment Server. The keys will not be used or displayed here. 
$receiptKeys = "";
if (!$errorExists) {
    $cmdResponse = sendCommand($payClientSocket,"33,",$debug);
    $receiptKeys = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";
	$receiptKeys = str_replace( ",", "<br/>",$receiptKeys);
}

// In a production system the sent values and the receive
// values could be checked to make sure they are the same.
if (!$errorExists) {
    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.MerchTxnRef",$debug);
    $merchTxnRef = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

	$cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.MerchantId",$debug);
    $merchantID = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.OrderInfo",$debug);
    $orderInfo = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.PurchaseAmountInteger",$debug);
    $amount = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.Locale",$debug);
    $locale = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.ReceiptNo",$debug);
    $receiptNo = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.TransactionNo",$debug);
    $transactionNr = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.AcqResponseCode",$debug);
    $acqResponseCode = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.AuthorizeId",$debug);
    $authorizeID = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.BatchNo",$debug);
    $batchNo = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.CardType",$debug);
    $cardType = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";

    $cmdResponse = sendCommand($payClientSocket,"4,DigitalReceipt.CSCResultCode",$debug);
    $cscResultCode = substr($cmdResponse,0,1) == $OK ? substr($cmdResponse,2) : "No Value Returned";
}

// Step 7 - We are finished with the Payment Client so tidy up
// ===========================================================
// Close the socket connection to the Payment Client
if ($socketCreated) {
    close($payClientSocket);
}

// *********************
// END OF MAIN PROGRAM
// *********************

// FINISH TRANSACTION - Process the Response Data
// ==============================================
// For the purposes of demonstration, we simply display the Result fields on a
// web page.

// This is the display title for 'Receipt' page 
$title = $_GET["Title"];

/* The URL link for the receipt to do another transaction.
 * Note: This is ONLY used for this example and is not required for 
 * production code.  */
$againLink  = $_GET["AgainLink"];

if (!$errorExists) {
    $title = $title." Receipt Page";
} else {
    $title = $title." Error Page";
    // if exception is empty then set it to a value
    if (strlen($exception) == 0) {
        $exception = "No Further Information Returned";
    }
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title><?=$title?></title>
    <meta http-equiv="Content-Type" content="text/html, charset=iso-8859-1"/>
    <style type='text/css'>
        <!--
        h1       { font-family:Arial,sans-serif; font-size:20pt; font-weight:600; margin-bottom:0.1em; color:#08185A;}
        h2       { font-family:Arial,sans-serif; font-size:14pt; font-weight:100; margin-top:0.1em; color:#08185A;}
        h2.co    { font-family:Arial,sans-serif; font-size:24pt; font-weight:100; margin-top:0.1em; margin-bottom:0.1em; color:#08185A}
        h3       { font-family:Arial,sans-serif; font-size:16pt; font-weight:100; margin-top:0.1em; margin-bottom:0.1em; color:#08185A}
        h3.co    { font-family:Arial,sans-serif; font-size:16pt; font-weight:100; margin-top:0.1em; margin-bottom:0.1em; color:#FFFFFF}
        body     { font-family:Verdana,Arial,sans-serif; font-size:10pt; background-color:#FFFFFF; color:#08185A}
        th       { font-family:Verdana,Arial,sans-serif; font-size:8pt; font-weight:bold; background-color:#CED7EF; padding-top:0.5em; padding-bottom:0.5em;  color:#08185A}
        tr       { height:25px; }
        tr.shade { height:25px; background-color:#CED7EF }
        tr.title { height:25px; background-color:#0074C4 }
        td       { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#08185A }
        td.red   { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#FF0066 }
        td.green { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#008800 }
        p        { font-family:Verdana,Arial,sans-serif; font-size:10pt; color:#FFFFFF }
        p.blue   { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#08185A }
        p.red    { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#FF0066 }
        p.green  { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#008800 }
        div.bl   { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#0074C4 }
        div.red  { font-family:Verdana,Arial,sans-serif; font-size:7pt;  color:#FF0066 }
        li       { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#FF0066 }
        input    { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#08185A; background-color:#CED7EF; font-weight:bold }
        select   { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#08185A; background-color:#CED7EF; font-weight:bold; }
        textarea { font-family:Verdana,Arial,sans-serif; font-size:8pt;  color:#08185A; background-color:#CED7EF; font-weight:normal; scrollbar-arrow-color:#08185A; scrollbar-base-color:#CED7EF }
        -->
    </style>
</head>
<body>

<!-- Start branding table -->
<table width='100%' border='2' cellpadding='2' bgcolor='#0074C4'>
    <tr>
        <td bgcolor='#CED7EF' width='90%'>
            <h2 class='co'> Payment Client v3.1 Example</h2>
        </td>
        <td bgcolor='#0074C4' align='center'>
            <h3 class='co'>Dialect<br />Solutions</h3>
        </td>
    </tr>
</table>
<!-- End branding table -->

<center><h1><?=$title?></h1><br/></center>

<table align='center' border='0' width='80%'>

	<tr class='title'>
		<td colspan='2'><p><strong>&nbsp;Transaction Receipt Fields</strong></p></td>
	</tr>
	<tr>
		<td align='right'><strong><em>Merchant Transaction Reference: </em></strong></td>
		<td><?=$merchTxnRef?></td>
	</tr>
	<tr class='shade'>
		<td align='right'><strong><em>Merchant ID: </em></strong></td>
		<td><?=$merchantID?></td>
	</tr>
	<tr>
		<td align='right'><strong><em>Order Information: </em></strong></td>
		<td><?=$orderInfo?></td>
	</tr>
	<tr class='shade'>
		<td align='right'><strong><em>Transaction Amount: </em></strong></td>
		<td><?=$amount?></td>
	</tr>
	<tr>
		<td colspan='2' align='center'>
			<div class='bl'>Fields above are the primary request values.<hr>Fields below are receipt data fields.</div>
		</td>
	</tr>
<?php
// only display these next fields if not an error
if (!$errorExists) {
?>  <tr class='shade'>
		<td align='right'><strong><em>QSI Response Code: </em></strong></td>
		<td><?=$qsiResponseCode?></td>
	</tr>
	<tr>
		<td align='right'><strong><em>QSI Response Code Description: </em></strong></td>
		<td><?=getResponseDescription($qsiResponseCode)?></td>
	</tr>
	<tr class='shade'>
		<td align='right'><strong><em>Acquirer Response Code: </em></strong></td>
		<td><?=$acqResponseCode?></td>
	</tr>
	<tr>
		<td align='right'><strong><em>Shopping Transaction Number: </em></strong></td>
		<td><?=$transactionNr?></td>
	</tr>
	<tr class='shade'>
		<td align='right'><strong><em>Receipt Number: </em></strong></td>
		<td><?=$receiptNo?></td>
	</tr>
	<tr>
		<td align='right'><strong><em>Authorization ID: </em></strong></td>
		<td><?=$authorizeID?></td>
	</tr>
	<tr class='shade'>
		<td align='right'><strong><em>Batch Number for this transaction: </em></strong></td>
		<td><?=$batchNo?></td>
	</tr>
	<tr>                  
		<td align='right'><em><strong>CardType: </strong></em></td>
		<td><?=$cardType?></td>
	</tr>
	<tr>
		<td colspan='2' align='center'>
			<div class='bl'>Fields above are for a Standard Transaction<br />
			<hr />
			Fields below are additional fields for extra functionality.</div>
		</td>
	</tr>
    <tr class='title'>
        <td colspan='2' height='25'><p><strong> Card Security Code Fields</strong></p></td>
    </tr>
    <tr>
        <td align='right'><strong><em>CSC Result Code: </em></strong></td>
        <td><?=$cscResultCode?></td>
    </tr>
    <tr class='shade'>
        <td align='right'><strong><em>CSC Result Description: </em></strong></td>
        <td><?=displayCSCResponse($cscResultCode)?></td>
    </tr>
<?php
// only display these next fields if an error condition exists-->
} else {
?>  <tr class='title'>
		<td colspan='2'><p><strong>&nbsp;Error in processing the data</strong></p></td>
	</tr>
	<tr>
		<td align='right' width='35%'><em><strong>Error Description: </strong></em></td>
		<td><?=$message?></td>
	</tr>
	<tr class='shade'>
		<td align='right'><em><strong>Additional Information: </strong></em></td>
		<td><?=$exception?></td>
	</tr>
<?php  }
?>
	<tr>    
		<td width="50%">&nbsp;</td>
		<td width="50%">&nbsp;</td>
	</tr>
</table>

<center><p class='blue'><a href='<?=$againLink?>'>Another Transaction</a></p></center>

</body>
</html>

<?php    
// End Processing

// =============================================================================

// This subroutine sends the command to the Payment Client and retrieves the
// response for the main body of the example. If an error is encountered
// an error page is displayed.
//
// @param $payClientSocket - The Payment Client socket
// @param $command  - String that will be sent to the Payment Client Socket
// @param $debug - If this is true, output commands to screen
//
// @return String containing the Payment Client response
function sendCommand($payClientSocket, $command, $debug) {

    global $payClientTimeout;
    socket_set_timeout($payClientSocket, $payClientTimeout);

    if ($debug) {
		$replaceChars = array(" ", "<", ">", "\n");
		$replacementChars = array("&nbsp;", "&lt;", "&gt;", "<br/>");
		$sendcommand = str_replace($replaceChars, $replacementChars, $command);
        print("<font color='#FF0066'>Sent : ".$sendcommand."</font><br/>");
    }
    
    // output the data to the socket & read in the response
    $buf = $command . "\n";
    $response = fputs($payClientSocket, $buf) == strlen($buf);    
    if (!$response) {
        // Display an error as there has been a communication error
        return "(46) Socket communication error - received: no response, sent: '$command'";
    }    
    
    // Set the time to stop reading using the globale timeout variable
    $stop=time() + $payClientTimeout;
    $reply="";
    while (!strpos($reply,"\n")) {
	    // Check to see if we have timed out
        if (time() >= $stop) {
            return "(49) Socket communication error: socket timed out waiting for reply - sent: '$command'";
        }
        $reply .= fgets($payClientSocket, 4096);
    }
    
    if ($debug) {
        print("<font color='#008800'>Recd: ".$reply."</font><br/><br/>");
    }

    // return the socket response
     return chop($reply);
}

//  ----------------------------------------------------------------------------


// This function closes the socket
//
// @param $payClientSocket The Payment Client socket
function close($payClientSocket) {
    $buf = "99";
    $response = fputs($payClientSocket, $buf) == strlen($buf);    
    fclose($payClientSocket);
}

//  ----------------------------------------------------------------------------

// This function uses the QSI Response code retrieved from the Digital
// Receipt and returns an appropriate description for the QSI Response Code
//
// @param $responseCode String containing the QSI Response Code
//
// @return String containing the appropriate description
//
function getResponseDescription($responseCode) {

    switch ($responseCode) {
        case "0" : $result = "Transaction Successful"; break;
        case "?" : $result = "Transaction status is unknown"; break;
        case "1" : $result = "Transaction Declined"; break;
        case "2" : $result = "Bank Declined Transaction"; break;
        case "3" : $result = "No Reply from Bank"; break;
        case "4" : $result = "Expired Card"; break;
        case "5" : $result = "Insufficient funds"; break;
        case "6" : $result = "Error Communicating with Bank"; break;
        case "7" : $result = "Payment Server detected an error"; break;
        case "8" : $result = "Transaction Type Not Supported"; break;
        case "9" : $result = "Bank declined transaction (Do not contact Bank)"; break;
        case "A" : $result = "Transaction Aborted"; break;
        case "C" : $result = "Transaction Cancelled"; break;
        case "D" : $result = "Deferred transaction has been received and is awaiting processing"; break;
        case "F" : $result = "3D Secure Authentication failed"; break;
        case "I" : $result = "Card Security Code verification failed"; break;
        case "L" : $result = "Shopping Transaction Locked (Please try the transaction again later)"; break;
        case "N" : $result = "Cardholder is not enrolled in Authentication scheme"; break;
        case "P" : $result = "Transaction has been received by the Payment Adaptor and is being processed"; break;
        case "R" : $result = "Transaction was not processed - Reached limit of retry attempts allowed"; break;
        case "S" : $result = "Duplicate SessionID (Amex Only)"; break;
        case "T" : $result = "Address Verification Failed"; break;
        case "U" : $result = "Card Security Code Failed"; break;
        case "V" : $result = "Address Verification and Card Security Code Failed"; break;
        default  : $result = "Unable to be determined"; 
    }
    return $result;
}

//  ----------------------------------------------------------------------------

// This function uses the QSI AVS Result Code retrieved from the Digital
// Receipt and returns an appropriate description for this code.

// @param avsResultCode String containing the QSI AVS Result Code
// @return description String containing the appropriate description

function displayAVSResponse($avsResultCode) {
    
    if ($avsResultCode != "") { 
        switch ($avsResultCode) {
            Case "Unsupported" : $result = "AVS not supported or there was no AVS data provided"; break;
            Case "X"  : $result = "Exact match - address and 9 digit ZIP/postal code"; break;
            Case "Y"  : $result = "Exact match - address and 5 digit ZIP/postal code"; break;
            Case "S"  : $result = "Service not supported or address not verified (international transaction)"; break;
            Case "G"  : $result = "Issuer does not participate in AVS (international transaction)"; break;
            Case "A"  : $result = "Address match only"; break;
            Case "W"  : $result = "9 digit ZIP/postal code matched, Address not Matched"; break;
            Case "Z"  : $result = "5 digit ZIP/postal code matched, Address not Matched"; break;
            Case "R"  : $result = "Issuer system is unavailable"; break;
            Case "U"  : $result = "Address unavailable or not verified"; break;
            Case "E"  : $result = "Address and ZIP/postal code not provided"; break;
            Case "N"  : $result = "Address and ZIP/postal code not matched"; break;
            Case "0"  : $result = "AVS not requested"; break;
            default   : $result = "Unable to be determined"; 
        }
    } else {
        $result = "null response";
    }
    return $result;
}

//  ----------------------------------------------------------------------------

// This function uses the QSI CSC Result Code retrieved from the Digital
// Receipt and returns an appropriate description for this code.

// @param cscResultCode String containing the QSI CSC Result Code
// @return description String containing the appropriate description

function displayCSCResponse($cscResultCode) {
    
    if ($cscResultCode != "") {
        switch ($cscResultCode) {
            Case "Unsupported" : $result = "CSC not supported or there was no CSC data provided"; break;
            Case "M"  : $result = "Exact code match"; break;
            Case "S"  : $result = "Merchant has indicated that CSC is not present on the card (MOTO situation)"; break;
            Case "P"  : $result = "Code not processed"; break;
            Case "U"  : $result = "Card issuer is not registered and/or certified"; break;
            Case "N"  : $result = "Code invalid or not matched"; break;
            default   : $result = "Unable to be determined"; break;
        }
    } else {
        $result = "null response";
    }
    return $result;
}

//  -----------------------------------------------------------------------------

?>

