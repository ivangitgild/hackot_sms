<?php
$request = $_GET;

$user_phone = $request['contact_number'];
$text_body  = $request['text_message'];
//$user_phone = '09274010141';
//$text_body = 'test';

include('client.php');

$client = new SMS_CLIENT($user_phone, $text_body);
$client->__send();

echo json_encode($client->getResponse());
die();
?>