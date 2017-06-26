<?php

$date = gmdate('D, d M Y H:i:s \G\M\T');

//$account_name = "almacenamientoesteeu";
//$containername = "vhds";
//$blob = "maquinal28.vhd";

$account_name = $argv[1];
$containername = $argv[2];
$blob = $argv[3];

//$account_key = "dZwgzrIIOtOc6uDsuuLlTZg7Qe8zbMyFCBthV0RYv8E5mIkJO+HYhKZoQxGMCxtxcOBRWArhuZle7zXHXjj4WA==";

$account_key = str_replace("@","=",$argv[4]);

$canonicalizedHeaders = "x-ms-date:$date\nx-ms-version:2014-02-14";
$canonicalizedResource = "/$account_name/$containername/$blob";

$arraysign = array();
$arraysign[] = 'DELETE';                     /* HTTP Verb */
$arraysign[] = '';                        /* Content-Encoding */
$arraysign[] = '';                        /* Content-Language */
$arraysign[] = '';                        /* Content-Length (include value when zero) */
$arraysign[] = '';                        /* Content-MD5 */
$arraysign[] = '';                        /* Content-Type */
$arraysign[] = '';                        /* Date */
$arraysign[] = '';                        /* If-Modified-Since */
$arraysign[] = '';                        /* If-Match */
$arraysign[] = '';                        /* If-None-Match */
$arraysign[] = '';                        /* If-Unmodified-Since */
$arraysign[] = '';                        /* Range */
$arraysign[] = $canonicalizedHeaders;     /* CanonicalizedHeaders */
$arraysign[] = $canonicalizedResource;    /* CanonicalizedResource */

$stringtosign = implode("\n", $arraysign);
//echo "01---------</br>";
$signature = 'SharedKey' . ' ' . $account_name . ':' . base64_encode(hash_hmac('sha256', $stringtosign, base64_decode($account_key), true));
//echo "02---------".$signature."</br>";
$endpoint = 'https://' . $account_name . '.blob.core.windows.net';
$url = $endpoint . '/' . $containername . '/' . $blob;
//$url = $endpoint.'/'.$containername.'?restype=container&comp=list';
//echo "03---------".$url."</br>";
$headers = [
    "x-ms-date:{$date}",
    'x-ms-version:2014-02-14',
    'Accept:application/json;odata=nometadata',
    "Authorization:{$signature}"
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$info = curl_getinfo($ch);
echo $info["http_code"];
?>        

