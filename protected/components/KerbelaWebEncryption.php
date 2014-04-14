<?php
class KerbelaWebEncryption implements KerbelaEncryption
{
	public function encrypt($data,$key){
		$output=array();
		exec('echo "'.$data.'" | openssl enc -e -aes-256-cbc -a -k '.$key,$output);
		return $this->merge($output);
	}

    public function decrypt($data, $key){
		$output=array();
		exec('echo "'.$this->slice($data).'" | openssl enc -d -aes-256-cbc -a -k '.$key,$output);
		return $this->merge($output);
    }

    private function slice($string){
    	$sliced=str_split($string,64);
    	$sliced_string='';
    	for($i=0;$i<sizeof($sliced);$i++){
    		$sliced_string.=$sliced[$i].'\n';
    	}
    	error_log('SLICED');
    	error_log($sliced_string);
    	return $sliced_string;
    }
    private function merge(Array $output){
		$result="";
		foreach($output as $item){
			$result.=str_replace('\n','',$item);
		}
		return $result;
	}

}

