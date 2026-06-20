<?php
$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
$out='';
for($i=1;$i<=10;$i++)
{
    $out.=base64_encode(openssl_random_pseudo_bytes($ivlen))."\n";
}
file_put_contents('output_keys.txt',$out);
