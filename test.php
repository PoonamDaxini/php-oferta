<?php

$baseUrl;
$accountID;
$apiUrl = "https://demo.docusign.net/restapi/v2/login_information";
$envelopID;
$header = "<DocuSignCredentials><Username>ruturaj.v@directi.com</Username><Password>ruturaj</Password><IntegratorKey>MEDI-b881d75d-4e7b-4d7c-aab6-12eead250b39</IntegratorKey></DocuSignCredentials>";


$curl = curl_init($apiUrl);
curl_setopt($curl, CURLOPT_HEADER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-DocuSign-Authentication: $header"));

$json_response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

if ($status != 200) {
    echo "error calling webservice, status is:" . $status;
    exit(-1);
}


$response = json_decode($json_response, true);
echo '<pre>';
print_r($response);
$accountID = $response["loginAccounts"][0]["accountId"];
$baseUrl = $response["loginAccounts"][0]["baseUrl"];
curl_close($curl);

//--- display results
echo "\naccountId = " . $accountID . "\nbaseUrl = " . $baseUrl . "\n";
/*
$curl = curl_init($baseUrl . "/envelopes");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "X-DocuSign-Authentication: $header")
        );
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 200) {
            echo "error calling webservice, status is:" . $status . "\nError text --> ";
            print_r($json_response);
            echo "\n";
            exit(-1);
        }
        $response = json_decode($json_response, true);
//--- display results
        echo "status = " . $response["status"] . "\nsent time = " . $response["sentDateTime"] . "\n\n";
      */


$email = 'poonam.da@directi.com'; //reciipient email address
$recipientName = 'Poonam'; //reciipient Name
$documentName = 'Ajay_Gaikawad_Page_1.pdf';
$data = array(
    "emailSubject" => 'test',
    "documents" => array(array("documentId" => "1", "name" => $documentName)),
    "recipients" => array("signers" => array(
            array("email" => $email,
                "name" => $recipientName,
                "recipientId" => "1",
                "routingOrder" => "1",
//                        "clientUserId" => 'qwedsa', //clientid
                "tabs" => array(
                    "signHereTabs" => array(
                        array("xPosition" => "100",
                            "yPosition" => "100",
                            "documentId" => "1",
                            "pageNumber" => "1")
                    ))
            )//add another array for another recipient
        )
    ),
    "status" => "sent",
    "eventNotification" => array(
//        "url" => "http://localhost.account/docuSigns/updateDocusign",
//        "url" => "http://localhost.test/auction.php",
        "url" => "http://shine.shop.pw/log_status.php",
        "envelopeEvents" => array(array(
                "envelopeEventStatusCode" => "sent",
                "envelopeEventStatusCode" => "completed",
                "includeDocuments" => "true"
            )),
        "recipientEvents" => array(array(
                "recipientEventStatusCode" => "Sent",
                "recipientEventStatusCode" => "completed",
                "includeDocuments" => 'true'
            ))
    )
//    "returnUrl" => "http://localhost.test/auction.php"
);
$data_string = json_encode($data);
var_dump($data_string);
$file_contents = file_get_contents('/tmp/' . $documentName);

$requestBody = "\r\n"
        . "\r\n"
        . "--myboundary\r\n"
        . "Content-Type: application/json\r\n"
        . "Content-Disposition: form-data\r\n"
        . "\r\n"
        . "$data_string\r\n"
        . "--myboundary\r\n"
        . "Content-Type: application/pdf\r\n"
        . "Content-Disposition: file; filename=\"$documentName\"; documentid=1 \r\n"
        . "\r\n"
        . "$file_contents\r\n"
        . "--myboundary--\r\n"
        . "\r\n";

// *** append "/envelopes" to baseUrl and as signature request endpoint
$curl = curl_init($baseUrl . "/envelopes");
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $requestBody);
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    'Content-Type: multipart/form-data;boundary=myboundary',
    'Content-Length: ' . strlen($requestBody),
    "X-DocuSign-Authentication: $header")
);

$json_response = curl_exec($curl);
$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
if ($status != 201) {
    echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
    print_r($json_response);
    echo "\n";
    exit(-1);
}

$response = json_decode($json_response, true);
$envelopID = $response["envelopeId"];
echo '<pre>';
print_r($response);
curl_close($curl);
//--- display results
echo "Document is sent! Envelope ID = " . $envelopID . "\n\n";
       /* 
function get_envelop_status(){
        $header = "<DocuSignCredentials><Username>" . Configure::read('DOCU_SIGN_API_USERNAME') . "</Username><Password>" . Configure::read('DOCU_SIGN_API_PASSWORD') . "</Password><IntegratorKey>" . Configure::read('DOCU_SIGN_API_INTEGRATOR_KEYS') . "</IntegratorKey></DocuSignCredentials>";
        $curl = curl_init($this->baseUrl . "/envelopes/");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "X-DocuSign-Authentication: $header")
        );
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 200) {
            echo "error calling webservice, status is:" . $status . "\nError text --> ";
            print_r($json_response);
            echo "\n";
            exit(-1);
        }
        $response = json_decode($json_response, true);
//--- display results
        echo "status = " . $response["status"] . "\nsent time = " . $response["sentDateTime"] . "\n\n";
    
    }
    function get_recipient_email_status() {
        $header = "<DocuSignCredentials><Username>" . Configure::read('DOCU_SIGN_API_USERNAME') . "</Username><Password>" . Configure::read('DOCU_SIGN_API_PASSWORD') . "</Password><IntegratorKey>" . Configure::read('DOCU_SIGN_API_INTEGRATOR_KEYS') . "</IntegratorKey></DocuSignCredentials>";
        $curl = curl_init($this->baseUrl . "/envelopes/" . $this->envelopID);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            "X-DocuSign-Authentication: $header")
        );
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 200) {
            echo "error calling webservice, status is:" . $status . "\nError text --> ";
            print_r($json_response);
            echo "\n";
            exit(-1);
        }
        $response = json_decode($json_response, true);
//--- display results
        echo "status = " . $response["status"] . "\nsent time = " . $response["sentDateTime"] . "\n\n";
    }

    function get_recipient_status() {
        $header = "<DocuSignCredentials><Username>" . Configure::read('DOCU_SIGN_API_USERNAME') . "</Username><Password>" . Configure::read('DOCU_SIGN_API_PASSWORD') . "</Password><IntegratorKey>" . Configure::read('DOCU_SIGN_API_INTEGRATOR_KEYS') . "</IntegratorKey></DocuSignCredentials>";

        $email = 'poonam.da@direct.com'; //reciipient email address
        $recipientName = 'Poonam';

        $data = array("returnUrl" => "http://www.docusign.com/devcenter",
            "authenticationMethod" => "None", "email" => $email,
            "userName" => $recipientName
        );
        $data_string = json_encode($data);
        $curl = curl_init($this->baseUrl . "/envelopes/$this->envelopID/views/recipient");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
            "X-DocuSign-Authentication: $header")
        );
        $json_response = curl_exec($curl);
        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($status != 201) {
            echo "error calling webservice, status is:" . $status . "\nerror text is --> ";
            print_r($json_response);
            echo "\n";
            exit(-1);
        }
        $response = json_decode($json_response, true);
        $url = $response["url"];
//--- display results
        echo "Embedded URL is: \n\n" . $url . "\n\nNavigate to this URL to start the embedded signing view of the envelope\n";

        curl_close($curl);
    }
*
        * 
        */
?>
