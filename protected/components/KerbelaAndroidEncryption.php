<?php
class KerbelaAndroidEncryption implements KerbelaEncryption
{
    public function encrypt($data, $key){
    	return openssl_encrypt ($data,'aes-256-cfb', $key,$options=0,$iv=substr($key,0,16));
    }

    public function decrypt($data, $key){
    	return openssl_decrypt ($data,'aes-256-cfb', $key,$options=0,$iv=substr($key,0,16));
    }

}