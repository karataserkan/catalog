<?php
class CatalogManagementController extends Controller{
	
	public function init()
    {
        $this->layout = false;
    }
	
	public function actionDeleteCategories($categories){	
		error_log($categories);
		print_r($categories);

			
		}
	public function actionX($data){

		echo "hellocu".$data."aq";
		die();
	}
	/*
	public function accessRules() {
        return array(
            array('allow',
            'actions' => array('deleteCategories'),
            'ips' => Yii::app()->params['editor_ip'],
           ),
            array('deny',
                'actions' => array('deleteCategories'),
                'ips' => array('*'),
            ),
        );
    }*/

}