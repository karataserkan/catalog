<?php

class KerberizedServer{
	public $auth;
	public $http_service_ticket;
	public $encryptionLib;
	const KERBERIZED_SERVER_NAME = "catalog";
	const TICKET_TIMEOUT = 120;//2 minutes

	public function __construct($auth,$http_service_ticket,$encryptionLib){
		$this->setSuper($auth,$http_service_ticket,$encryptionLib);
	} 
	public function ticketValidation(){

		$HTTP_service_secret_key=$this->getHTTPServiceSecretKey();
		$HTTP_service_ticket_decrypted=CJSON::decode($this->decoder($this->getEcryptionLib()->decrypt($this->getHttpServiceTicket(),$HTTP_service_secret_key)));
		$HTTP_service_session_key=$HTTP_service_ticket_decrypted['HTTP_service_session_key'];
		$AUTH_decrypted=CJSON::decode($this->decoder($this->getEcryptionLib()->decrypt($this->getAuth(),$HTTP_service_session_key)));

		if($this->is_http_service_correct($HTTP_service_ticket_decrypted['requested_http_service']))
		{
			if(!$this->is_replay_attack_check()){
				if($HTTP_service_ticket_decrypted['user_id']==$AUTH_decrypted['user_id']){

					
					if(((int)$AUTH_decrypted['timestamp']-(int)$HTTP_service_ticket_decrypted['timestamp'])<KerberizedServer::TICKET_TIMEOUT){

						if(((int)$HTTP_service_ticket_decrypted['timestamp']+(int)$HTTP_service_ticket_decrypted['requested_lifetime'])>time()){

							if($HTTP_service_ticket_decrypted['ip']==Yii::app()->request->getUserHostAddress()){
								return array('status'=>TRUE,'message'=>'ticket validated!');	
							}
							else{
								return array('status'=>FALSE,'message'=>'access to server from different ip prevented!');	
							}
						}
						else{
							return array('status'=>FALSE,'message'=>'dead ticket!');
						}
					}
					else{
						return array('status'=>FALSE,'message'=>'ticket timeout!');

					}


				}
				else{
					return array('status'=>FALSE,'message'=>'user_id injection attack prevented!');
				}
			
			}
			else
				{
					return array('status'=>FALSE,'message'=>'replay attack prevented!');
				}

		}
		else
		{
			return array('status'=>FALSE,'message'=>'Http service is not available!');
		}

	}

	public function authenticate(){


		
		$HTTP_service_secret_key=$this->getHTTPServiceSecretKey();//"0000000000000000";
		$HTTP_service_ticket_decrypted=CJSON::decode($this->decoder($this->getEcryptionLib()->decrypt($this->getHttpServiceTicket(),$HTTP_service_secret_key)));
		$HTTP_service_session_key=$HTTP_service_ticket_decrypted['HTTP_service_session_key'];

		$AUTH_decrypted=CJSON::decode($this->decoder($this->getEcryptionLib()->decrypt($this->getAuth(),$HTTP_service_session_key)));

		error_log("KERBERIZED");
		error_log(print_r($HTTP_service_ticket_decrypted,1));
		error_log(print_r($AUTH_decrypted,1));
		
		if($this->is_http_service_correct($HTTP_service_ticket_decrypted['requested_http_service']))
		{
			if(!$this->is_replay_attack()){
				if($HTTP_service_ticket_decrypted['user_id']==$AUTH_decrypted['user_id']){

					$time_difference=2*60;//2 minutes
					if(((int)$AUTH_decrypted['timestamp']-(int)$HTTP_service_ticket_decrypted['timestamp'])<$time_difference){

						if(((int)$HTTP_service_ticket_decrypted['timestamp']+(int)$HTTP_service_ticket_decrypted['requested_lifetime'])>time()){

							if($HTTP_service_ticket_decrypted['ip']==Yii::app()->request->getUserHostAddress()){

								error_log("SENDING DATA...");
								REST::sendResponse(200,
														$this->getEcryptionLib()->encrypt(
																					CJSON::encode(
																									array('requested_http_service'=>KerberizedServer::KERBERIZED_SERVER_NAME,
																						  				  'timestamp'=>time()
																					      				 )
																				 				)
																					,$HTTP_service_session_key
																  				)
																
											);
							//REST::sendResponse(200,CJSON::encode(array('status'=>'false','message'=>'access to server from different ip prevented!')));	
							}
							else{
								REST::sendResponse(200,CJSON::encode(array('status'=>False,'message'=>'access to server from different ip prevented!')));	
							}
						}
						else{
							REST::sendResponse(200,CJSON::encode(array('status'=>False,'message'=>'dead ticket!')));
						}
					}
					else{
						REST::sendResponse(200,CJSON::encode(array('status'=>False,'message'=>'ticket timeout!')));

					}


				}
				else{
					REST::sendResponse(200,CJSON::encode(array('status'=>False,'message'=>'user_id injection attack prevented!')));
				}
			
			}
			else
				{
					REST::sendResponse(200,CJSON::encode(array('status'=>False,'message'=>'replay attack prevented!')));
				}

			}
			else
			{
				REST::sendResponse(200,CJSON::encode(array('status'=>False,'message'=>'Http service is not available!')));
			}
	}
	public function getUserId(){
		$HTTP_service_secret_key=$this->getHTTPServiceSecretKey();
		$HTTP_service_ticket_decrypted=CJSON::decode($this->decoder($this->getEcryptionLib()->decrypt($this->getHttpServiceTicket(),$HTTP_service_secret_key)));
		$HTTP_service_session_key=$HTTP_service_ticket_decrypted['HTTP_service_session_key'];
		$AUTH_decrypted=CJSON::decode($this->decoder($this->getEcryptionLib()->decrypt($this->getAuth(),$HTTP_service_session_key)));
		return $AUTH_decrypted['user_id'];
	}
	/*internal functions*/
	private function setSuper($auth,$http_service_ticket,$encryptionLib){
		$this->setAuth($auth);
		$this->setHttpServiceTicket($http_service_ticket);
		$this->setEncryptionLib($encryptionLib);
	}
	
	private function is_http_service_correct($requested_http_service){
		return $requested_http_service==KerberizedServer::KERBERIZED_SERVER_NAME;
	}
	private function is_replay_attack(){
		$ticket=$this->getAuth();
		if(Tickettrash::model()->exists('ticket=:ticket',array('ticket'=>$ticket))){
			return True;
		}
		$tickettrash=new Tickettrash();
		$tickettrash->ticket=$ticket;
		$tickettrash->timestamp=date("Y-m-d");
		if($tickettrash->save()){
			return False;
		}
		return True;
	}
	private function is_replay_attack_check(){
		$ticket=$this->getAuth();
		if(Tickettrash::model()->exists('ticket=:ticket',array('ticket'=>$ticket))){
			return True;
		}
		return false;

	}
	private function getHTTPServiceSecretKey(){

		$httpservice=Httpservice::model()->find('http_service_name=:http_service_name',array('http_service_name'=>KerberizedServer::KERBERIZED_SERVER_NAME));
		return $httpservice->https_service_secret_key;
	}

	private function decoder($string){
		
		if(!$this->isJson($string)){
		$json=array();
		$string=str_replace('{','',$string);
		$string=str_replace('}','',$string);
		$string=explode(',',$string);
		for($i=0;$i<sizeof($string);$i++){
			$item=explode(':',$string[$i]);
			$item[0]=utf8_encode($item[0]);
			$item[1]=utf8_encode($item[1]);
			$json+=array($item[0]=>$item[1]);
		}
		return CJSON::encode($json);
	}
		else
			{
				
				return $string;
			}
	
	}

	private function isJson($string) {
 				json_decode($string);
 				return (json_last_error() == JSON_ERROR_NONE);
	}

	/*getters and setters*/
	public function setAuth($auth){
		$this->auth=$auth;
	}
	public function getAuth(){
		return $this->auth;
	}
	public function setHttpServiceTicket($http_service_ticket){
		$this->http_service_ticket=$http_service_ticket;
	}
	public function getHttpServiceTicket(){
		return $this->http_service_ticket;
	}
	public function setEncryptionLib($encryptionLib){
		$this->encryptionLib=$encryptionLib;
	}
	public function getEcryptionLib(){
		return $this->encryptionLib;
	}

}
?>