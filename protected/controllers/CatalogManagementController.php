<?php
class CatalogManagementController extends Controller{
	
	public function init()
    {
        $this->layout = false;
    }
	
	public function actionDeleteCategories(){	
		$data=Yii::app()->request->getPost('data');
		if($data){
			$data_arr=json_decode(base64_decode($data));
			foreach ($data_arr as $category) {
				$category_model=Categories::model()->find('category_id=:category_id',array('category_id'=>$category));
				if($category_model)
				{
					$category_model->delete();
				}
			}
		}
			
		}
	public function filters()
	{
	  return array(
	    'postOnly + DeleteCategories'
	  );
	}
	
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
    }

}