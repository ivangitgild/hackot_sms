<?php

date_default_timezone_set();

//include ("secret.php");

// load the nusoap libraries. These are slower than those built in PHP5 but don't require you to recompile PHP
require_once("lib/nusoap.php");

// create the client and define the URL endpoint
$client = new nusoap_client('http://iplaypen.globelabs.com.ph:1881/axis2/services/Platform/');

// development server
//$client = new soapclient('http://iplaypen.globelabs.com.ph:1887/axis2/services/Platform/');


// set the character encoding, utf-8 is the standard.
$client->soap_defencoding = 'UTF-8';

// check if we generated an error in creating the client / assigning the endpoint
$err = $client->getError();
if ($err) {
    // Display the error
    $error_message = 'Constructor error: ' . $err;
}

// check if a message was sent
echo "calling service ...\n";

// Call the SOAP method, note the definition of the xmlnamespace as the third parameter in the call and how the posted message is added to the message string
$result = $client->call('sendSMS', array(
										'uName' => 'i5731u0pt', 
                                        'uPin' => '21737742',
                                        'MSISDN' => '09274010141',
                                                                            'messageString' => "What's with the WiFi",
                                                                            'Display' => '1',
                                        'udh' => '', 
                                        'mwi' => '',
										'coding' => '0'
                                  ), "http://ESCPlatform/xsd");
                                            
                               
// Check for a fault
if ($client->fault) {
    $error_message = "Fault Generated: \n";
} else {
    // Check for errors
    $err = $client->getError();
    if ($err) {
        // Display the error
        $error_message =  "An unknown error was generated: $err  \n";
    } else {
        // Display the result
        if ($result == "201") {
          $error_message =  "Message was successfully sent!";
          var_dump ($client);
        }
        else {
          $error_message =  "Server responded with a $result message";
        }
    }
}


echo $error_message . "\n";
//var_dump ($client);
die("exiting\n");


?>