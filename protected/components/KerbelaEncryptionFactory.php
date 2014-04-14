<?php

class KerbelaEncryptionFactory
{
	public static function create($type){
		if($type=="android"){
			return new KerbelaAndroidEncryption();
		}
		else if($type=="ios"){
			return new KerbelaIosEncryption();
		}
		else if($type=="web"){
			return new KerbelaWebEncryption();
		}
	}
}