<?php
interface KerbelaEncryption
{
    public function encrypt($data, $key);
    public function decrypt($data, $key);

}