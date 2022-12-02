<?php

    /**
     * @param $method - e.g. POST, GET
     * @param $path - e.g /sell/finances/v1/seller_funds_summary
     * @param $host - e.g. api.ebay.com
     * @param $tokens // public, private and jwt keys generated from https://apiz.ebay.com/developer/key_management/v1/signing_key
     * @param $timestamp - e.g. time()
     * @return array of headers
     */
     
     /* Vars im really using*/
     //'base_uri'      => 'https://api.ebay.com/' (url base for the $path) for guzzle call
     
     $method = "GET;
     
     $path = "/sell/fulfillment/v1/order/xx-xxxxx-xxxxx"; // order-id
     
     $host = "api.ebay.com";
     
     $tokens = [
          "jwe" => "xxx-using-rsa-signing_key";
          "privateKey" => "yyy-using-rsa-signing_key";
     ];
     
     $time = time();
     
     
    private function getDigitalSignatureHeaders(string $method, string $path, string $host, array $tokens, int $time)
    {
        $signature_input_txt = '("x-ebay-signature-key" "@method" "@path" "@authority");created=' . $time;

        // $signature_base = '"content-digest": sha-256=:' . base64_encode($contentDigest) . ":\n";
        $signature_base = '"x-ebay-signature-key": ' . $tokens['jwe'] . "\n";
        $signature_base .= '"@method": ' . $method . "\n";
        $signature_base .= '"@path": ' . $path . "\n";
        $signature_base .= '"@authority": ' . $host . "\n";
        $signature_base .= '"@signature-params": ' . $signature_input_txt;

        // format the private key as required
        $formatted_private_key = "-----BEGIN PRIVATE KEY-----" . PHP_EOL . $tokens['privateKey'] . PHP_EOL . "-----END PRIVATE KEY-----";

        openssl_sign($signature_base, $signed_signature, $formatted_private_key, OPENSSL_ALGO_SHA256);
        
        return [
            'Signature-Input' => 'sig1=' . $signature_input_txt,
            'Signature' => 'sig1=:' . base64_encode($signed_signature) . ':',
            'x-ebay-signature-key' => $tokens['jwe'],
            'x-ebay-enforce-signature' => "true"
        ];
    }
