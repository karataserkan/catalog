<?php
ini_set("upload_max_filesize", "1000M");


$uploadRes->contentId=$_POST['contentId'];
$uploadRes->contentTrustSecret=$_POST['contentTrustSecret'];

$uploadRes->orjhosts=json_decode($_POST['hosts']);


foreach ($uploadRes->orjhosts as $key => $host) {
$newhost->host=$host->host;
$newhost->port=(int)$host->port;
$uploadRes->hosts[]=$newhost;
unset($newhost);	
}



$uploaddir = '/var/www/catalog/catalog_files/';
$uploadfile = $uploaddir . $uploadRes->contentId;


$uploadRes->checksum=md5_file($_FILES['contentFile']['tmp_name']);
if($uploadRes->checksum!=$_POST['checksum']){
	$uploadRes->msg[]="WrongCheckSum";
	$control[]=false;
}

if(sha1($uploadRes->checksum."ONLYUPLOAD".$uploadRes->contentId . $_SERVER['HTTP_X_FORWARDED_FOR'] . $_SERVER['REMOTE_ADDR']) !== $uploadRes->contentTrustSecret ){
	$uploadRes->msg[]="WrongContentTrustSecret";
	$control[]=false;
}


if ( ! in_array(false, $control)){
	$uploadRes->gettingFile=move_uploaded_file($_FILES['contentFile']['tmp_name'], $uploadfile);
}

if ($uploadRes->gettingFile) {
	$uploadRes->msg[]="File is valid, and was successfully uploaded.";
} else {
    $uploadRes->msg[]="Possible file upload attack!";
}

$uploadRes->file=$_FILES;


foreach ($uploadRes->hosts as $key => $host) {
	$sendFileToHostCommand="python bin/client.py '" .json_encode($host). "' AddToCatalog ".$uploadfile." " .$uploadRes->contentId;
}

$uploadRes->shell=$sendFileToHostCommand;
$uploadRes->shell_output=shell_exec($sendFileToHostCommand);

unlink($uploadfile);


echo json_encode($uploadRes);
?>
