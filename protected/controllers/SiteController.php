<?php

class SiteController extends Controller
{
	public $metaTitle="";
	public $metaDescription="OKUTUS farklı alt sektörlerde faaliyet gösteren (akademik, eğitim, gazete ve dergi, ders kitapları, vb) yayıncılık firmalarının, kurum ve kuruluşların (devlet kurumları, üniversiteler, vb) ve bireysel yazar, yayıncı ve çevirmenlerin etkileşimli, içeriği multimedya destekli elektronik yayınlarını (eYayın) kolaylıkla ePub3 formatında üretmelerini, güvenli Linden LDDS (Linden Digital Distribution System) sistemi ile dağıtmalarını ve Linden elektronik kaynak okuyucu yazılımlarında gelişmiş okuyucu deneyimleri ile tüketmelerini sağlayan bir yazılım teknolojisidir.";
	public $metaKeywords="okutus, dijital yayıncılık,dijital yayıncılık,yayıncılık,ebook,ebooks,digital publishing,digital books,epub,epub3,dijital kitap,elektronik kitap,etkileşimli kitap,linden";
	public $metaAuthor="linden-tech.com";
	public $metaSubject="Digital Publishing";
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
			// if(isset($_POST['text']))
			// {
			// 	$this->redirect(Yii::app()->request->baseUrl.'/site/search?key='.$_POST['text']);
			// }
			$this->render('index');
	}

	public function addTurkishChars($text)
	{

	}

	public function actionSearch()
	{
		$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$exploded=explode('/', $actual_link);
		$page=($exploded[5]) ? $exploded[5] : 1 ;
		$key = ($exploded[4]) ? $exploded[4] : 0 ;
		$key=urldecode($key);

		// if (isset($_POST['page'])) {
		// 	$page=$_POST['page'];
		// }
		// else
		// {
		// 	$page=1;
		// }
		$books;
		$totalPage=0;
		$totalBooks=0;
		$page--;
		if (($key) AND $page >-1) {
			//$key=$_POST['key'];
			// $detectSQLinjectionKey=new detectSQLinjection($key);
			// if ($detectSQLinjectionKey->ok()) {
				$limit=10;
				$offset=$limit*$page;
		 		//$key = preg_replace("/[^a-z0-9_\s- ]/", "", $key);
		 		if (strpos($key, "author:") !== false) {
		 			$key_exp=explode(":", $key);
		 			$key=$key_exp[1];
					$books=Content::model()->findAll('author="'.$key.'" LIMIT '.$limit.' OFFSET '.$offset,array());
					$pages=Yii::app()->db->createCommand('select count(*) as count,ceil(count(*)/10) as pages  from content where author="'.$key.'"')->queryRow();
					$key = "author:".$key;
		 		}elseif (strpos($key, "publisher:") !== false) {
		 			$key_exp=explode(":", $key);
		 			$key=$key_exp[1];
					$books=Content::model()->findAll('organisationName="'.$key.'" LIMIT '.$limit.' OFFSET '.$offset,array());
					$pages=Yii::app()->db->createCommand('select count(*) as count,ceil(count(*)/10) as pages  from content where organisationName="'.$key.'"')->queryRow();
					$key = "publisher:".$key;
		 		}
		 		else{
					$books=Content::model()->findAll('contentTitle LIKE "%'.$key.'%" OR contentExplanation LIKE "%'.$key.'%" OR author LIKE "%'.$key.'%" OR organisationName LIKE "%'.$key.'%" LIMIT '.$limit.' OFFSET '.$offset,array());
					$pages=Yii::app()->db->createCommand('select count(*) as count,ceil(count(*)/10) as pages  from content where contentTitle LIKE "%'.$key.'%" OR contentExplanation LIKE "%'.$key.'%" OR author LIKE "%'.$key.'%" OR organisationName LIKE "%'.$key.'%"')->queryRow();
		 		}
		 		
				$totalPage=$pages['pages'];
				$totalBooks=$pages['count'];
				$this->updateSiteMapXmlWithSearchKey($key,$totalPage);
			// }
			// else{
			// 	echo "sql error";
			// }
		}

	    $this->render('search', array(
	    'books' => $books,
	    'criteria' =>$key,
	    'totalPage'=>$totalPage,
	    'currentPage'=>++$page,
	    'totalBooks'=>$totalBooks
	    ));
	}

	public function getNiceName($id)
	{
		$meta=ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'nicename'));
		if (!$meta) {
			$this->setNiceName($id);
			$meta=ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'nicename'));
		}
		return $meta->metaValue;
	}

	public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, strlen($characters) - 1)];
	    }
	    return $randomString;
	}

	public function setNiceName($id){
		$content=Content::model()->findByPk($id);
		$meta=ContentMeta::model()->find('contentId=:contentId AND metaKey=:metaKey',array('contentId'=>$id,'metaKey'=>'nicename'));
		if (!$meta) {
			$meta=new ContentMeta;
		}
		$meta->contentId=$id;
		$meta->metaKey='nicename';

		$bul = array('Ç', 'Ş', 'Ğ', 'Ü', 'İ', 'Ö', 'ç', 'ş', 'ğ', 'ü', 'ö', 'ı', ' ','-','.');
		$yap = array('C', 'S', 'G', 'U', 'I', 'O', 'c', 's', 'g', 'u', 'o', 'i', '_','_','');
		$perma = str_replace($bul, $yap, $content->contentTitle);
		$perma = preg_replace("@[^A-Za-z0-9\.\-_]@i", '', $perma);
		$nicename=strtolower($perma);
		$i=0;
		while (!$i) {
			$i=0;
			$hasNiceName=ContentMeta::model()->find('metaValue=:metaValue AND metaKey=:metaKey',array('metaValue'=>$nicename,'metaKey'=>'nicename'));
			if ($hasNiceName) {
				$nicename=$nicename.'-'.$this->generateRandomString(3);
			}
			else
			{
				$i++;
			}
		}

		$meta->metaCreationDate=date('Y-n-d g:i:s',time());
		$meta->metaValue=$nicename;
		$meta->save();
		
	}

	public function actionBook($id)
	{
		$nicename=$id;
		$meta=ContentMeta::model()->find('metaValue=:metaValue AND metaKey=:metaKey',array('metaValue'=>$nicename,'metaKey'=>'nicename'));
		$id=$meta->contentId;
		$book=Content::model()->findByPk($id);
		$this->exportRisFile($id);
		$bookMeta=array();
		$bookMeta['abstract']=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'abstract','contentId'=>$id))->metaValue;
		$bookMeta['publishDate']=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'date','contentId'=>$id))->metaValue;
		$bookMeta['totalPage']=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'totalPage','contentId'=>$id))->metaValue;
		$bookMeta['subject']=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'subject','contentId'=>$id))->metaValue;
		$bookMeta['language']=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'language','contentId'=>$id))->metaValue;
		$bookMeta['edition']=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'edition','contentId'=>$id))->metaValue;
		$bookMeta['translator']=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'translator','contentId'=>$id))->metaValue;
		$bookMeta['tracking']=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'tracking','contentId'=>$id))->metaValue;
		
		if ($book->contentTitle) {
			$this->metaTitle=$book->contentTitle;
		}
		
		if ($book->author) {
			$this->metaAuthor=$book->author;
		}
		
		if ($subject) {
			$this->metaSubject=$subject;
		}

		if ($book->contentExplanation) {
			$this->metaDescription=$book->contentExplanation;
		}
		
		$this->metaKeywords=$book->contentTitle.','.$book->author;
		
		$this->updateSiteMapXmlWithBook($nicename);

		$this->render("book",array('book'=>$book,'bookMeta'=>$bookMeta));
	}

	public function updateSiteMapXmlWithSearchKey($key,$totalPage)
	{
		$XML=file_get_contents('sitemap.xml');
		
		if(!strpos($XML, "<loc>".Yii::app()->params['catalog_host']."/q/".$key."</loc>"))
		{
			$newUrl="<url>\n";
			$newUrl.="  <loc>".Yii::app()->params['catalog_host']."/q/".$key."</loc>\n";
			$newUrl.="</url>\n";
			$XML_ex=explode('</urlset>', $XML);
			$XML = $XML_ex[0];
			$XML .=$newUrl;
			$XML .='</urlset>';
		}
		
		if ($totalPage==1) {
			if (!strpos($XML, "  <loc>".Yii::app()->params['catalog_host']."/q/".$key."/1</loc>")) {
				$newUrl="<url>\n";
				$newUrl.="  <loc>".Yii::app()->params['catalog_host']."/q/".$key."/1</loc>\n";
				$newUrl.="</url>\n";
				$XML_ex=explode('</urlset>', $XML);
				$XML = $XML_ex[0];
				$XML .=$newUrl;
				$XML .='</urlset>';
			}
		}
		else{
			for ($i=1; $i <= $totalPage; $i++) { 
				if (!strpos($XML, "  <loc>".Yii::app()->params['catalog_host']."/q/".$key."/".$i."</loc>")) {
					$newUrl="<url>\n";
					$newUrl.="  <loc>".Yii::app()->params['catalog_host']."/q/".$key."/".$i."</loc>\n";
					$newUrl.="</url>\n";
					$XML_ex=explode('</urlset>', $XML);
					$XML = $XML_ex[0];
					$XML .=$newUrl;
					$XML .='</urlset>';
				}
			}
		}


		file_put_contents('sitemap.xml', $XML);

	}

	public function updateSiteMapXmlWithBook($book)
	{
		$XML=file_get_contents('sitemap.xml');
		// $robot=file_get_contents('robots.txt');
		// $robot_in = "Sitemap: ".Yii::app()->params['catalog_host']."/sitemap.xml\n";
		// $robot_in .="User-agent: *\n";
		// $robot_in .="Disallow:\n";
		// file_put_contents("robots.txt", $robot_in);
		
		if(!strpos($XML, $book))
		{
			$newUrl="<url>\n";
			$newUrl.="  <loc>".Yii::app()->params['catalog_host']."/".$book."</loc>\n";
			$newUrl.="</url>\n";
		}
		$XML_ex=explode('</urlset>', $XML);
		$XML = $XML_ex[0];
		$XML .=$newUrl;
		$XML .='</urlset>';
		file_put_contents('sitemap.xml', $XML);
		
	}

	public function exportRisFile($id)
	{
		if (!$id) {
			return 0;
		}
		$book=Content::model()->findByPk($id);
		if (! $book) {
			return 0;
		}
		$file="ris/".$book->contentId.".ris";
		if (!file_exists($file)) {
			fopen($file, 'w');
		}
		else
		{
			return 0;
		}
		$content=file_get_contents($file);
		$content="TY - EBOOK\r\n";
		
		$authors=$book->author;
		$authorId=0;
		if ($authors) {
			$author_ex=explode(',', $authors);
			$authors_ris=array();
			foreach ($author_ex as $key => $author) {
				$author=explode(' ', $author);
				$author_surname=$author[count($author)-1].',';
				$author_name='';
				for ($i=0; $i < (count($author)-1) ; $i++) { 
					$author_name .= substr($author[$i], 0,1).'.';
				}
				$authors_ris[]=$author_surname.$author_name;
			}
			
			foreach ($authors_ris as $key => $author_ris) {
				if ($key==0) {
					$content .= 'AU - '.$author_ris."\r\n";
				}
				else
				{
					$content .= 'A'.($key+1).' - '.$author_ris."\r\n";
					$authorId=$key;
				}
			}

		}
		


		$translator=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'translator','contentId'=>$id))->metaValue;
		if ($translator) {
			$author=explode(' ', $translator);
			$author_surname=$author[count($author)-1].',';
			$author_name='';
			for ($i=0; $i < (count($author)-1) ; $i++) { 
				$author_name .= substr($author[$i], 0,1).'.';
			}
			if ($authorId==0) {
				$content.="AU - ".$author_surname.$author_name."\r\n";
			}
			else
			{
				$content.="A".($authorId+1)." - ".$author_surname.$author_name."\r\n";
			}
		}

		$content.="TI - ".$book->contentTitle."\r\n";
		$content.="J2 - ".$book->contentTitle."\r\n";

		$abstract=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'abstract','contentId'=>$id))->metaValue;
		if ($abstract) {
			$content.="AB - ".$abstract."\r\n";
		}

		$content.="CY - Ankara\r\n";

		$date=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'date','contentId'=>$id))->metaValue;
		if ($date) {
			$date_ex=explode('/', $date);
			$content.="DA - ".$date_ex[2]."/".$date_ex[1]."/".$date_ex[0]."\r\n";
			$content.="PY - ".$date_ex[2]."\r\n";
		}

		$content.="DB - OKUTUS\r\n";
		$content.="DP - Linden Digital Publishing\r\n";

		$edition=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'edition','contentId'=>$id))->metaValue;
		if ($edition) {
			$content.="ET - ".$edition."\r\n";
		}

		$content.="KW - ".$book->contentTitle."\r\n";
		$content.="KW - ".$book->organisationName."\r\n";
		$content.="KW - ".$book->author."\r\n";

		$language=ContentMeta::model()->find('metaKey=:metaKey AND contentId=:contentId',array('metaKey'=>'language','contentId'=>$id))->metaValue;
		if ($language) {
			$content.="LA - ".$language."\r\n";
		}
		
		$content.="LB - ".$book->contentTitle."\r\n";

		$content.="PB - Linden Digital Publishing\r\n";

		$content.="ER -";

		//echo $content;
		file_put_contents($file, $content);


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
		error_log(print_r($content,1));
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

		//Yii::app()->db->createCommand("DELETE FROM contentMeta WHERE contentId='".$contentId."'")->queryAll();

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



		unlink($uploadfile);


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
