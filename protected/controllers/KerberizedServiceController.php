<?php
class KerberizedServiceController extends Controller{
	
	public function init()
    {
        $this->layout = false;
    }
	
	public function actionAuthenticate(){	
		$auth=Yii::app()->request->getPost('auth',0);
		$http_service_ticket=Yii::app()->request->getPost('http_service_ticket',0);
		$type=Yii::app()->request->getPost('type','android');
		$kerberized=new KerberizedServer($auth,$http_service_ticket,KerbelaEncryptionFactory::create($type));
		//$myarray=$kerberized->ticketValidation();
		//error_log("user_id:".$kerberized->getUserId());
		$kerberized->authenticate();

			
		}
	public function actionAnyservice(){
		$auth=Yii::app()->request->getPost('auth',0);
		$http_service_ticket=Yii::app()->request->getPost('http_service_ticket',0);
		$type=Yii::app()->request->getPost('type','android');
		$kerberized=new KerberizedServer($auth,$http_service_ticket,KerbelaEncryptionFactory::create($type));
		$myarray=$kerberized->ticketValidation();
		error_log("user_id:".$kerberized->getUserId());
		print_r($myarray);
	}

}