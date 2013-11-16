<?php
ini_set('error_reporting', 0);
date_default_timezone_set();

include("lib/nusoap.php");
include('secrets.php');

class SMS_CLIENT {
    
    private $contact_number;
    private $text_message;
    private $_response;
    
    public function __construct($contact_number, $text_message) {
        $this->contact_number = $contact_number;
        $this->text_message = $text_message;
    }
    
    public function __send() {
        
        // create the client and define the URL endpoint
        $client = new nusoap_client(URL_ENDPOINT);
        
        // set the character encoding, utf-8 is the standard.
        $client->soap_defencoding = 'UTF-8';
        
        // check if we generated an error in creating the client / assigning the endpoint
        $err = $client->getError();
        if ($err) {
            // Display the error
            $error_message = 'Constructor error: ' . $err;
        }
        
        // Call the SOAP method, note the definition of the xmlnamespace as the third parameter in the call and how the posted message is added to the message string
        
        $result = $client->call('sendSMS', array(
                                                'uName' => uNAME,
                                                'uPin'  => uPIN,
                                                'MSISDN'    => $this->contact_number,
                                                'messageString' => $this->text_message,
                                                'Display'   => '1',
                                                'udh'   => '',
                                                'mwi'   => '',
                                                'config'    => '0'
                                            ),"http://ESCPlatform/xsd" );
        
        $this->setResponse($result, $client);
    }
    
    private function setResponse($result, $client) {
        $response = array();
        // Check for a fault
        if ($client->fault) {
            $response = array('result' => false, 'message' => 'Fault generated');
        } else {
            // Check for errors
            $err = $client->getError();
            if ($err) {
                // Display the error
                $response = array('result' => false, 'message' => 'An unknown error was generated: '.$err, 'error_code' => $err);
        
            } else {
                // Display the result
                if ($result == "201") {
                    $response = array('result' => true, 'message' => 'Message succesfully sent');
                }
                else {
                    $response = array('result' => false, 'message' => 'Server responded with a '.$result.' message', 'error_code' => $result);
                }
            }
        }
        
        $this->_response = $response;
    }
    
    public function getResponse() {
        return $this->_response;
    }
}
?>