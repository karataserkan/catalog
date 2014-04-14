<?php
class KerbelaIosEncryption implements KerbelaEncryption
{
	public function encrypt($data,$key){
		$output=array();
		exec('echo "'.$data.'" | openssl enc -e -aes-256-cbc -a -k '.$key,$output);
		return $this->merge($output);
	}

    public function decrypt($data, $key){
		$output=array();
		exec('echo "'.$data.'" | openssl enc -d -aes-256-cbc -a -k '.$key,$output);
		return $this->merge($output);
    }

    private function merge(Array $output){
		$result="";
		foreach($output as $item){
			$result.=str_replace('\n','',$item);
		}
		return $result;
	}

}