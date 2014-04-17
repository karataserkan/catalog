<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
			$this->render('index');
	}

	public function actionSearch($page=1,$key=null)
	{
		$books;
		$page--;
		if ((isset($_POST['text']) || $key) AND $page >-1) {
			$limit=10;
			$offset=$limit*$page;
			if (!$key) {
				$key=$_POST['text'];
			}
			$books=Content::model()->findAll('contentTitle LIKE "%'.$key.'%" OR contentExplanation LIKE "%'.$key.'%" OR author LIKE "%'.$key.'%" OR organisationName LIKE "%'.$key.'%" LIMIT '.$limit.' OFFSET '.$offset,array());
			$pages=Yii::app()->db->createCommand('select count(*) as count,ceil(count(*)/10) as pages  from content where contentTitle LIKE "%'.$key.'%" OR contentExplanation LIKE "%'.$key.'%" OR author LIKE "%'.$key.'%" OR organisationName LIKE "%'.$key.'%"')->queryRow();
			$totalPage=$pages['pages'];
			$totalBooks=$pages['count'];
		}

	    $this->render('search', array(
	    'books' => $books,
	    'criteria' =>$key,
	    'totalPage'=>$totalPage,
	    'currentPage'=>++$page,
	    'totalBooks'=>$totalBooks
	    ));
	}

	public function actionBook($name,$id)
	{
		$book=Content::model()->findByPk($id);
		$abstract=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'abstract','contentId'=>$id))->metaValue;
		$publishDate=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'date','contentId'=>$id))->metaValue;
		$totalPage=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'totalPage','contentId'=>$id))->metaValue;
		$subject=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'subject','contentId'=>$id))->metaValue;
		$language=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'language','contentId'=>$id))->metaValue;
		$edition=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'edition','contentId'=>$id))->metaValue;
		$translator=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'translator','contentId'=>$id))->metaValue;
		$this->render("book",array('book'=>$book,'abstract'=>$abstract,'publishDate'=>$publishDate,'totPage'=>$totalPage,'subject'=>$subject,'language'=>$language,'edition'=>$edition,'translator'=>$translator));
	}

	public function actionImport()
	{
		$control=array();

		ini_set("upload_max_filesize", "2000M");

		$contentId=$_POST['contentId'];

		$retrievedContent=Content::model()->findByPk($contentId);
		if($retrievedContent){
			$retrievedContent->delete();
		}

		$hosts=json_decode($_POST['hosts'],true);
		$acls=json_decode($_POST['acls'],true);
		$categories=json_decode($_POST['categories'],true);
		$siralicategories=json_decode($_POST['siraliCategory'],true);
		
		error_log("\nA01");

		$content=Content::model()->findByPk($contentId);
		if (empty($content)&& !$content) {
			$content=new Content;
		}
		error_log("\nA02");

		$content->contentId=$contentId;
		$content->organisationId=$_POST['organisationId'];
		$content->contentType=$_POST['contentType'];
		$content->contentTitle=$_POST['contentTitle'];//.$hosts['GIWwMdmQXL']['key1'];
		$content->contentExplanation=$_POST['contentExplanation'];
		$content->contentIsForSale=$_POST['contentIsForSale'];
		$content->contentPrice=$_POST['contentPrice'];
		$content->contentPriceCurrencyCode=$_POST['contentCurrencyCode'];
		//$content->contentReaderGroup=$_POST['contentReaderGroup'];
		$content->created=$_POST['created'];
		$content->organisationName=$_POST['organisationName'];
		$content->author=$_POST['author'];
		if ($content->save()) {
			$uploadRes->catalog=0;
			error_log("\nA021");
		}
		else
		{
			$uploadRes->catalog=1;
			error_log("\nA022");
		}
		error_log("\nA03");
		
		foreach ($hosts as $key => $host) {
			$newHost = Host::model()->findByPk($host['id']);
			if (!$newHost) {
				$newHost = new Host;
			}
			$newHost->id=$host['id'];
			$newHost->address=$host['host'];
			$newHost->port=(int)$host['port'];
			$newHost->key1=$host['key1'];
			$newHost->key2=$host['key2'];
			if ($newHost->save()) {
				$contentHost=new ContentHost;
				$contentHost->host_id=$newHost->id;
				$contentHost->content_id=$content->contentId;
				$contentHost->save();
			}
		}
		error_log("\nA04");
		
		foreach ($acls as $aclKey => $acl) {
			$newAcl=new ContentACL;
			$newAcl->contentId=$content->contentId;
			$newAcl->aclId=$acl['id'];
			$newAcl->aclName=$acl['name'];
			$newAcl->aclType=$acl['type'];
			$newAcl->aclVal1=$acl['val1'];
			$newAcl->aclVal2=$acl['val2'];
			$newAcl->aclComment=$acl['comment'];
			$newAcl->save();
		}
		
		error_log("\nA05");

		foreach ($categories as $key => $category) {
			$newCategory= Categories::model()->findByPk($category['category_id']);
			if (empty($newCategory) && !$newCategory) {
				$newCategory=new Categories;
			}
			$newCategory->category_id=$category['category_id'];
			$newCategory->category_name=$category['category_name'];
			$newCategory->organisation_id=$content->organisationId;
			if ($newCategory->save()) {
				$contenCategory= new ContentCategories;
				$contenCategory->content_id=$content->contentId;
				$contenCategory->category_id=$newCategory->category_id;
				$contenCategory->save();
			}
		}

		
		if ($siralicategories&& !empty($siralicategories)) {
			foreach ($siralicategories as $key => $category) {
				$newCategory= Categories::model()->findByPk($category['category_id']);
				if (empty($newCategory) && !$newCategory) {
					$newCategory=new Categories;
					$newCategory->category_id=$category['category_id'];
					$newCategory->category_name=$category['category_name'];
					$newCategory->organisation_id=$content->organisationId;
					$newCategory->periodical=1;
					$newCategory->save();
				}
				
				$contenCategory= new ContentCategories;
				$contenCategory->content_id=$content->contentId;
				$contenCategory->category_id=$newCategory->category_id;
				$contenCategory->save();

				if ($_POST['siraNo']) {
					$contentMeta=new ContentMeta;
					$contentMeta->contentId=$content->contentId;
					$contentMeta->metaKey='siraNo';
					$contentMeta->metaValue=$_POST['siraNo'];
					$contentMeta->metaCreationDate=$content->created;
					$contentMeta->save();
				}

				if ($_POST['ciltNo']) {
					$contentMeta=new ContentMeta;
					$contentMeta->contentId=$content->contentId;
					$contentMeta->metaKey='ciltNo';
					$contentMeta->metaValue=$_POST['ciltNo'];
					$contentMeta->metaCreationDate=$content->created;
					$contentMeta->save();
				}

				$contentMeta=new ContentMeta;
					$contentMeta->contentId=$content->contentId;
					$contentMeta->metaKey='periodical';
					$contentMeta->metaValue='1';
					$contentMeta->metaCreationDate=$content->created;
					$contentMeta->save();
			}
		}

		if ($_POST['contentCover']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="cover";
			$contentMeta->metaValue=$_POST['contentCover'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}
		if ($_POST['toc']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="toc";
			$contentMeta->metaValue=$_POST['toc'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		if ($_POST['totalPage']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="totalPage";
			$contentMeta->metaValue=$_POST['totalPage'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		if ($_POST['contentThumbnail']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="thumbnail";
			$contentMeta->metaValue=$_POST['contentThumbnail'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		if ($_POST['tracking']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="tracking";
			$contentMeta->metaValue=$_POST['tracking'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		//book MARC
		if ($_POST['abstract']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="abstract";
			$contentMeta->metaValue=$_POST['abstract'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		if ($_POST['language']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="language";
			$contentMeta->metaValue=$_POST['language'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		if ($_POST['subject']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="subject";
			$contentMeta->metaValue=$_POST['subject'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		if ($_POST['edition']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="edition";
			$contentMeta->metaValue=$_POST['edition'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		// if ($_POST['author']) {
		// 	$contentMeta=new ContentMeta;
		// 	$contentMeta->contentId=$content->contentId;
		// 	$contentMeta->metaKey="author";
		// 	$contentMeta->metaValue=$_POST['author'];
		// 	$contentMeta->metaCreationDate=$content->created;
		// 	$contentMeta->save();
		// }

		if ($_POST['translator']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="translator";
			$contentMeta->metaValue=$_POST['translator'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		if ($_POST['issn']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="issn";
			$contentMeta->metaValue=$_POST['issn'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();
		}

		if ($_POST['date']) {
			$contentMeta=new ContentMeta;
			$contentMeta->contentId=$content->contentId;
			$contentMeta->metaKey="date";
			$contentMeta->metaValue=$_POST['date'];
			$contentMeta->metaCreationDate=$content->created;
			$contentMeta->save();	
		}
		//book MARC

		error_log("\nA06");
		


		$uploadRes->contentId=$contentId;
		$uploadRes->contentTrustSecret=$_POST['contentTrustSecret'];

		

		
		//$uploadRes->orjhosts=json_decode($_POST['hosts']);
		
		$uploadRes->orjhosts=$hosts;

		foreach ($uploadRes->orjhosts as $key => $host) {
		$newhost->host=$host['host'];
		$newhost->port=(int)$host['port'];
		$uploadRes->hosts[]=$newhost;
		unset($newhost);	
		}

		error_log("\nA07");

		$uploaddir = '/var/www/catalog/catalog_files/';
		$uploadfile = $uploaddir . $uploadRes->contentId;
		error_log("\nA08");


		$uploadRes->checksum=md5_file($_FILES['contentFile']['tmp_name']);
		if($uploadRes->checksum!=$_POST['checksum']){
			$uploadRes->msg[]="WrongCheckSum";
			$control[]=true;
		}

		if(sha1($uploadRes->checksum."ONLYUPLOAD".$uploadRes->contentId . $_SERVER['HTTP_X_FORWARDED_FOR'] . $_SERVER['REMOTE_ADDR']) !== $uploadRes->contentTrustSecret ){
			$uploadRes->msg[]="WrongContentTrustSecret";
			$control[]=true;
		}


		if ( ! in_array(false, $control)){
			$uploadRes->gettingFile=move_uploaded_file($_FILES['contentFile']['tmp_name'], $uploadfile);
		}
		error_log("\nA09");

		if ($uploadRes->gettingFile) {
			$uploadRes->msg[]="File is valid, and was successfully uploaded.";
		} else {
		    $uploadRes->msg[]="Possible file upload attack!";
		}

		$uploadRes->file=$_FILES;

		$uploadRes->organisation=$_POST['organisationId'];

		error_log("\nA10");
		foreach ($uploadRes->hosts as $key => $host) {
			$sendFileToHostCommand="python bin/client.py '" .json_encode($host). "' AddToCatalog ".$uploadfile." " .$uploadRes->contentId;
		}

		$uploadRes->shell=$sendFileToHostCommand;
		exec($sendFileToHostCommand,$output,$signal);

		$uploadRes->shell_output=$output;
		$uploadRes->shell_signal=$signal;

		if ($uploadRes->shell_signal == 0){
			$sendFileToHostCommand="python bin/client.py '" .json_encode($host). "' ListCatalog" ;
			exec($sendFileToHostCommand,$newoutput,$signal);
			$uploadRes->cc_output=$newoutput[0];
			$uploadRes->cc_signal=$signal;
			if (in_array($uploadRes->contentId, json_decode($uploadRes->cc_output))){
				$uploadRes->cc=true;
			}	
		}

		
		error_log("\nA11");



		//unlink($uploadfile);


		echo json_encode($uploadRes);


		//$this->render('import');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}
