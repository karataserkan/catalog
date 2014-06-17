<?php

class ApiController extends Controller
{
	public $metaTitle="";
	public $metaDescription="OKUTUS farklı alt sektörlerde faaliyet gösteren (akademik, eğitim, gazete ve dergi, ders kitapları, vb) yayıncılık firmalarının, kurum ve kuruluşların (devlet kurumları, üniversiteler, vb) ve bireysel yazar, yayıncı ve çevirmenlerin etkileşimli, içeriği multimedya destekli elektronik yayınlarını (eYayın) kolaylıkla ePub3 formatında üretmelerini, güvenli Linden LDDS (Linden Digital Distribution System) sistemi ile dağıtmalarını ve Linden elektronik kaynak okuyucu yazılımlarında gelişmiş okuyucu deneyimleri ile tüketmelerini sağlayan bir yazılım teknolojisidir.";
	public $metaKeywords="okutus, dijital yayıncılık,dijital yayıncılık,yayıncılık,ebook,ebooks,digital publishing,digital books,epub,epub3,dijital kitap,elektronik kitap,etkileşimli kitap,linden";
	public $metaAuthor="linden-tech.com";
	public $metaSubject="Digital Publishing";
	public $response=null; 
	public $errors=null; 

	public function response($response_avoition=null){

		$response['result']=$response_avoition ? $response_avoition : $this->response;
		if ($this->errors) $response['errors']=$this->errors;

		$response_string=json_encode($response);


		header('Content-type: plain/text');
		header("Content-length: " . strlen($response_string) ); // tells file size
		echo $response_string;
	}
 
	public function error($domain='EditorActions',$explanation='Error', $arguments=null,$debug_vars=null ){
		$error=new error($domain,$explanation, $arguments,$debug_vars);
		$this->errors[]=$error; 
		return $error;
	}

	public function actionService(){
		$auth=Yii::app()->request->getPost('auth',0);
		$http_service_ticket=Yii::app()->request->getPost('http_service_ticket',0);
		$kerberized=new KerberizedServer($auth,$http_service_ticket);
		$myarray=$kerberized->ticketValidation();

		error_log("ticket validation:".serialize($myarray));	
		$kerberized->authenticate();			
	}

	private function authenticate()
	{
		$auth=Yii::app()->request->getPost('auth',0);
		$http_service_ticket=Yii::app()->request->getPost('http_service_ticket',0);
		$type=Yii::app()->request->getPost('type','android');
		// error_log("auth:".$auth);
		// error_log("http_service_ticket:".$http_service_ticket);
		$kerberized=new KerberizedServer($auth,$http_service_ticket,KerbelaEncryptionFactory::create($type));
		

		 $myarray=$kerberized->ticketValidation();
		// error_log("user_id:".$kerberized->getUserId());
		//$kerberized->authenticate();
		if ($kerberized->getUserId()) {
			return $kerberized->getUserId();
		}
		else
			return 0;
	} 

	public function actionAuthenticate()
	{
		$auth=Yii::app()->request->getPost('auth',0);
		$http_service_ticket=Yii::app()->request->getPost('http_service_ticket',0);
		$type=Yii::app()->request->getPost('type','android');
		// error_log("auth:".$auth);
		// error_log("http_service_ticket:".$http_service_ticket);
		$kerberized=new KerberizedServer($auth,$http_service_ticket,KerbelaEncryptionFactory::create($type));
		

		 $myarray=$kerberized->ticketValidation();
		// error_log("user_id:".$kerberized->getUserId());
		$kerberized->authenticate();
	}

	public function actionDocumentation()
	{
		$this->render('documentation');
	}

	public function actionRemove()
	{
		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-GMI","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			$this->response("");
			return null;			
		}
		
		$id=CHttpRequest::getPost('id',0);
		 error_log('id: '.$id);

		$hosts= Yii::app()->db->createCommand()
    		->select('h.*')
    		->from('content c,host h,content_host ch')
    		->where('c.contentId=:content AND h.id=ch.host_id AND c.contentId=ch.content_id',array('content'=>$id))
    		->queryAll();
		
    	foreach ($hosts as $key => $host) {
			$deleteFromCloud="python bin/client.py '{\"host\":\"".$host["address"]."\",\"port\":".$host["port"]."}' DeleteFromCatalog ".$id;
			shell_exec($deleteFromCloud);
			error_log($deleteFromCloud);
    	}

		$content=Content::model()->find('contentId=:contentId',array('contentId'=>$id));
		$content->delete();


		//$deleteFromCloud="python bin/client.py DeleteFromCatalog'" .$id;



	}

	public function actionGetMainInfo()
	{
		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-GMI","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			$this->response("");
			return null;			
		}

		 $id=CHttpRequest::getPost('id',0);
		// $id="VgWaWF8DQ3J8U7tAiGqQuRHucsA6uyWLQjk1Qm0Ibz5e";
		if (!$id) {
			$this->error("AC-GMI","Catalog Not Found",func_get_args());
			return false;
		}

		//$res=ContentMeta::model()->findAll('contentId=:contentId',array('contentId'=>$id));
		$content=Content::model()->find('contentId=:contentId',array('contentId'=>$id));

		$data['contentTitle']=$content->contentTitle;
		$data['contentExplanation']=$content->contentExplanation;
		$data['contentIsForSale']=$content->contentIsForSale;
		$data['contentPriceCurrencyCode']=$content->contentPriceCurrencyCode;
		$data['contentPrice']=$content->contentPrice;
		$data['contentDate']=ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'date'))->metaValue;
		$data['contentAuthor']=ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'author'))->metaValue;
		$data['contentTotalPage']=ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'totalPage'))->metaValue;
		$data['created']=$content->created;//ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'date'))->metaValue;


		$host=Yii::app()->db->createCommand("SELECT h.address FROM host h, content_host c where c.content_id='".$id."' AND h.id=c.host_id")->queryRow();
		$data['host_address']=$host['address'];
		$data['host_port']=$host['port'];
		// if ($res) {
		// 	foreach ($res as $key => &$items) {
		// 		$items=$items->attributes;
		// 	}
		// }

		$this->response($data);

	}

	public function actionGetThumbnail($id=null)
	{
		if (!$id) {
			echo "Not Found";
			return false;
		}
		$res=ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'thumbnail'))->metaValue;
		
		if (empty($res)) {
			$res = base64_encode(file_get_contents(Yii::app()->params['catalog_host'].'/css/thumbnail2.jpg'));
			$extension='jpg';
			
		}
		define('UPLOAD_DIR', 'images/');
		$img = $res;
		$exp=explode(";", $img);
		$ext=explode("/", $exp[0]);
		$extension = $ext[1]; 
		$img = str_replace('data:image/'.$extension.';base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$data = base64_decode($img);
		$file = UPLOAD_DIR . uniqid() . '.'.$extension;
		$success = file_put_contents($file, $data);
		shell_exec("convert ".$file." -resize 270x390 ".$file);
		$im = file_get_contents($file);
    	//$imdata = 'data:image/jpeg;base64,'.base64_encode($im);

		

     	header('Content-Type: image/'.$extension);
		  echo $im; 
		 unlink($file);
	}

	public function actionGetCover($id)
	{
		// if (!$this->authenticate()) {
		// 	return null;
		// }

		// if (!CHttpRequest::getIsPostRequest()) {
		// 	$this->error("AC-GC","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
		// 	$this->response("");
		// 	return null;			
		// }

		// $id=CHttpRequest::getPost('id',0);
		if (!$id) {
			$this->error("AC-GC","Catalog Not Found",func_get_args());
			return false;
		}

		 $res=ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'cover'))->metaValue;
		// define('UPLOAD_DIR', 'images/');
		// $img = $res;
		// $img = str_replace('data:image/jpeg;base64,', '', $img);
		// $img = str_replace(' ', '+', $img);
		// $data = base64_decode($img);
		// $file = UPLOAD_DIR . uniqid() . '.jpeg';
		// $success = file_put_contents($file, $data);
		// //shell_exec("convert ".$file." -resize 270x390 ".$file);
		$exp=explode(";", $res);
		$ext=explode("/", $exp[0]);
		$extension = $ext[1];
		$im = file_get_contents($res);

		header('Content-Type: image/'.$extension);
		echo $im;

  //   	$imdata = 'data:image/jpeg;base64,'.base64_encode($im);
		// $this->response($imdata);

	}

	public function actionGetMetaValue()
	{
		if (!$this->authenticate()) {
			return "authenticate error!";
		}
		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-GM","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			$this->response("");
			return null;			
		}

		$id=CHttpRequest::getPost('id',0);
		$metaKey=CHttpRequest::getPost('metaKey','');

		if (!$id) {
			$this->error("AC-GM","Catalog Not Found",func_get_args());
			return false;
		}

		$res=ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>$metaKey))->metaValue;
		$this->response($res);
	}

	public function actionGetContentHost()
	{
		// if (!$this->authenticate()) {
		// 	return "authenticate error!";
		// }

		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-GD","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			//$this->response("");
			return 'Wrong Request';			
		}

		$id=CHttpRequest::getPost('id',0);

		if (!$id) {
			$this->error("AC-GD","Catalog Not Found",func_get_args());
			return 'Catalog Not Found';
		}

		$hosts= Yii::app()->db->createCommand()
    		->select('h.*')
    		->from('content c,host h,content_host ch')
    		->where('c.contentId=:content AND h.id=ch.host_id AND c.contentId=ch.content_id',array('content'=>$id))
    		->queryAll();

    		$this->response($hosts);

	}

	public function actionGetDetail()
	{
		// if (!$this->authenticate()) {
		// 	return "authenticate error!";
		// }

		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-GD","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			//$this->response("");
			return 'Wrong Request';			
		}

		$id=CHttpRequest::getPost('id',0);

		if (!$id) {
			$this->error("AC-GD","Catalog Not Found",func_get_args());
			return 'Catalog Not Found';
		}
		$content=Yii::app()->db->createCommand()
    		->select('*')
    		->from('content c')
    		->where('c.contentId=:content',array('content'=>$id))
    		->queryRow();

		$categories= Yii::app()->db->createCommand()
    		->select('ct.*')
    		->from('content c,categories ct,content_categories cc')
    		->where('c.contentId=:content AND cc.content_id=c.contentId AND ct.category_id=cc.category_id',array('content'=>$id))
    		->queryAll();

		$hosts= Yii::app()->db->createCommand()
    		->select('h.*')
    		->from('content c,host h,content_host ch')
    		->where('c.contentId=:content AND h.id=ch.host_id AND c.contentId=ch.content_id',array('content'=>$id))
    		->queryAll();

    	$metas= Yii::app()->db->createCommand()
    		->select('metaKey, metaValue,metaCreationDate')
    		->from('contentMeta m')
    		->where('m.contentId=:content',array('content'=>$id))
    		->queryAll();

    	$res=array('content'=>$content,'categories'=>$categories,'hosts'=>$hosts,'metas'=>$metas);
		

		//$hosts=ContentHost::model()->findAll('content_id=:val1',array('val1'=>$id));
		
		// if (!$hosts) {
		// 	$this->error("AC-GD","Host Not Found",func_get_args());
		// 	return false;
		// }
		
		// foreach ($hosts as $key => $host) {
		// 	$res[]=Host::model()->findByPk($host->host_id);
		// }
		if (empty($res)) {
			$this->error("AC-GD","Content Detail Not Found",func_get_args());
			return 'Content Detail Not Found';
		}

		
		//print_r($res);
		 $this->response($res);

	}

	public function actionIndex()
	{
		$this->render('index');
	}

	public function actionListIos()
	{
		
		error_log("POST:VALUES".print_r($_POST,1));
		error_log("GET:VALUES".print_r($GET,1));
		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-L","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			$this->response("");
			return "not post request";			
		}
		//print_r("sdfsdfdsf");die();

		$json=CHttpRequest::getPost('attributes',0);
		
		$newArray=array();
		$ar=json_decode($json,true);
		foreach ($ar as $key2 => $v) {
			foreach ($v as $key => $v2) {
				$newArray[$key]=$v2;
			}
		}
		$as=$newArray;
		$criteriaValues=array();
		$criteria='';

		if ($as) {
			
			foreach ($as as $k1 => $q) {
				if (is_array($q) && !empty($q)) {
					$criteria.='(';
					if ($k1=='contentTitle'||$k1=='contentExplanation'||$k1=='author') {
						foreach ($q as $k2 => $t) {
							$criteria.=$k1.' LIKE :'.$t.$k1.' OR ';
							$criteriaValues[$t.$k1]='%'.$t.'%';
						}	
					}
					elseif ($k1=='categories') {
						foreach ($q as $k2 => $t) {
							$criteria.='categories.category_id=:category_id'.$t.$k1.' OR ';
							$criteriaValues['category_id'.$t.$k1]=$t;
						}
					}
					elseif ($k1=='organisationId'||$k1=='contentType'||$k1=='contentIsForSale')
					{
						foreach ($q as $k2 => $t) {
							$criteria.=$k1.'=:'.$t.$k1.' AND';
							$criteriaValues[$t.$k1]=$t;
						}
					}
					
					$criteria=substr($criteria,0, -3);
					$criteria.=') AND';	
				}
			}
			$criteria=substr($criteria,0, -4);
		//.' AND host.id=content_host.host_id AND content_host.content_id=content.contentId'
	    	$list=Content::model()->with('categories')->findAll($criteria,$criteriaValues);
		}
		else
		{
			$list=Content::model()->findAll(array('limit'=>10));
		}
		//$list=Content::model()->findAll("author=:author",array('author'=>'Canan Karayay'));
		//var_dump($criteriaValues);die();

	 //    if(!$list)  {
		// 	$this->error("AC-L","Catalogs Not Found",func_get_args());
		// 	return false;
		// }
		if ($list) {
			foreach ($list as $key => &$items) {
				$items=$items->attributes;
			}
		}

		$this->response($list);

	}

	public function actionList()
	{
		/*
		if (!$this->authenticate()) {
			return "auth error";
		}*/
		
		error_log("POST:VALUES".print_r($_POST,1));
		error_log("GET:VALUES".print_r($GET,1));
		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-L","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			$this->response("");
			return "not post request";			
		}
		//print_r("sdfsdfdsf");die();

		$attributes=CHttpRequest::getPost('attributes',0);
		$as=json_decode($attributes);
		
		$criteriaValues=array();
		$criteria='';

		if (!empty($as)) {
			
			foreach ($as as $k1 => $q) {
				if (is_array($q) && !empty($q)) {
					$criteria.='(';
					if ($k1=='contentTitle'||$k1=='contentExplanation'||$k1=='author') {
						foreach ($q as $k2 => $t) {
							$criteria.=$k1.' LIKE :'.$t.$k1.' OR ';
							$criteriaValues[$t.$k1]='%'.$t.'%';
						}	
					}
					elseif ($k1=='categories') {
						foreach ($q as $k2 => $t) {
							$criteria.='categories.category_id=:category_id'.$t.$k1.' OR ';
							$criteriaValues['category_id'.$t.$k1]=$t;
						}
					}
					elseif ($k1=='organisationId'||$k1=='contentType'||$k1=='contentIsForSale')
					{
						foreach ($q as $k2 => $t) {
							if ($t) {
								$criteria.=$k1.'=:'.$t.$k1.' AND';
								$criteriaValues[$t.$k1]=$t;
							}
						}
					}
					
					$criteria=substr($criteria,0, -3);
					$criteria.=') AND';	
				}
			}
			$criteria=substr($criteria,0, -4);
		//.' AND host.id=content_host.host_id AND content_host.content_id=content.contentId'
		error_log("criteria:".$criteria);
		error_log("criteriaValues:".json_encode($criteriaValues));
			if (!$criteria || $criteria==")") {
				//$list=Content::model()->with('categories')->findAll(array('limit'=>10));
				$list=Content::model()->with('categories')->findAll();
			}else{
	    		$list=Content::model()->with('categories')->findAll($criteria,$criteriaValues);
			}
		}
		else
		{
				$list=Content::model()->with('categories')->findAll();
		}
		//$list=Content::model()->findAll("author=:author",array('author'=>'Canan Karayay'));
		//var_dump($criteriaValues);die();

	 //    if(!$list)  {
		// 	$this->error("AC-L","Catalogs Not Found",func_get_args());
		// 	return false;
		// }
		if ($list) {
			foreach ($list as $key => &$items) {
				$items=$items->attributes;
			}
		}

		$this->response($list);
	}

	public function actionGetCatalog($bookId=null)
	{
		if ($bookId) {
			$id=$bookId;
		}
		else
		{
			if (!$this->authenticate()) {
			return null;
			}
			if (!CHttpRequest::getIsPostRequest()) {
				$this->error("AC-GC","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
				$this->response("");
				return null;			
			}

			$id=CHttpRequest::getPost('id',0);

			if (!$id) {
				$this->error("AC-GC","Catalog Not Found",func_get_args());
				return false;
			}	
		}
		

		$res=Content::model()->findByPk($id);
		$res=$res->attributes;
		$this->response($res);
	}

	public function actionGetOrganisationCategories()
	{
		// if (!$this->authenticate()) {
		// 	return null;
		// }

		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-GC","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			$this->response("Wrong Request");
			return null;			
		}

		$id=CHttpRequest::getPost('id',0);
		
		if (!$id) {
			$this->error("AC-GC","Organisation Not Found",func_get_args());
			$this->response("Organisation Not Found");
			return false;
		}

		$categories=Categories::model()->findAll('organisation_id=:organisation_id ORDER BY category_name ASC',array('organisation_id'=>$id));

		foreach ($categories as $key => &$items) {
			$items=$items->attributes;
		}

		//print_r($categories);
		$this->response($categories);

			
	}

	public function actionListAllCategories()
	{
		// if (!$this->authenticate()) {
		// 	return null;
		// }
		$categories=Categories::model()->findAll(array('order'=>'category_name'));
		if(!$categories)  {
			$this->error("AC-LC","Categories Not Found",func_get_args());
			return false;
		}
		
		foreach ($categories as $key => &$items) {
			$items=$items->attributes;
		}

		//print_r($categories);
		$this->response($categories);

	}

	public function actionListCategoryCatalogs()
	{
		if (!$this->authenticate()) {
			return null;
		}
		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-LCB","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			$this->response("");
			return null;			
		}

		$id=CHttpRequest::getPost('id',0);

		if (!$id) {
			$this->error("AC-LCB","Category Not Found",func_get_args());
			return false;
		}

		$categoryBooks=ContentCategories::model()->findAll('category_id=:category_id',array('category_id'=>$id));
		$res=array();
		if (!$categoryBooks) {
			// $this->error("AC-LCB","Category Catalogs Not Found",func_get_args());
			// return false;
		}
		else
		{
			foreach ($categoryBooks as $key => $book) {
				$res[]=Content::model()->findByPk($book->content_id);
			}
		}

		// if (empty($res)) {
		// 	$this->error("AC-LCB","Category Catalogs Not Found",func_get_args());
		// 	return false;
		// }
		
		foreach ($res as $key => &$items) {
			$items=$items->attributes;
		}

		$this->response($res);

	}

	public function actionGetCatalogMeta()
	{
		if (!$this->authenticate()) {
			return null;
		}
		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-GC","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			$this->response("");
			return null;			
		}

		$id=CHttpRequest::getPost('id',0);

		if (!$id) {
			$this->error("AC-GCM","Catalog Not Found",func_get_args());
			return false;
		}

		$res=ContentMeta::model()->findAll('contentId=:contentId',array('contentId'=>$id));

		if (!$res) {
			//$this->error("AC-GCM","Meta Not Found",func_get_args());
			//return false;
			
		}

		foreach ($res as $key => &$items) {
			$items=$items->attributes;
		}

		$this->response($res);

	}

	public function actionGetCatalogReaders()
	{
		if (!$this->authenticate()) {
			return null;
		}
		if (!CHttpRequest::getIsPostRequest()) {
			$this->error("AC-GCR","Wrong Request",func_get_args(),CHttpRequest::getIsPostRequest());
			$this->response("");
			return null;			
		}

		$id=CHttpRequest::getPost('id',0);

		if (!$id) {
			$this->error("AC-GCR","Catalog Not Found",func_get_args());
			return false;
		}

		$res=ContentMeta::model()->findAll('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'reader'));

		if (!$res) {
			$res=ContentMeta::model()->findAll('metaKey=:metaKey',array('metaKey'=>'defaultReader'));
		}

		foreach ($res as $key => &$items) {
			$items=$items->attributes;
		}

		$this->response($res);

	}

	public function actionGetHosts($id=null)
	{
		if (!$id) {
			$this->response('Not Found');
			return 0;
		}
		$hosts=ContentHost::model()->findAll('content_id=:content_id',array('content_id'=>$id));
		if (!$hosts) {
			$this->response('Not Found');
			return 0;
		}
		$contentHosts=array();
		foreach ($hosts as $key => $host) {
			$contentHosts[]=Host::model()->findByPk($host->host_id);
		}
		foreach ($contentHosts as $key => &$items) {
			$items=$items->attributes;
		}

		$this->response($contentHosts);

	}

	public function actionSearch()
	{
		$this->render('search');
	}

	public function actionDeneme()
	{
		$url = 'http://catalog.lindneo.com/api/listIos';
		$a='[{"organisationId": ["seviye","qwertyu"]},{"contentType":["epub"]}]';
		//$a="VgWaWF8DQ3J8U7tAiGqQuRHucsA6uyWLQjk1Qm0Ibz5e";
		//$b="556633";
		// $c="1234";
		// $d="asd";
		
		$params = array(
						'attributes'=>$a,
						//'book_id'=>$b,
						// 'notes'=>json_encode(array(
						// 			array('book_id'=>'12345','page_id'=>'8','note'=>'asd'),
						// 			array('book_id'=>'12345','page_id'=>'8','note'=>'12312asd'),
						// 			array('book_id'=>'556633','page_id'=>'8','note'=>'12312asd'),
						// 			))
						// 'page_id'=>$c,
						// 'note'=>$d
						);
		$ch = curl_init( $url );
		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt( $ch, CURLOPT_HEADER, 0);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec( $ch );
		$this->response($response);
	}

	
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
