<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
$db_config_list=array(
			"walleye"=>array(
                        			'connectionString' => 'mysql:host=pufferfish.private.services.okutus.com;port=3306;dbname=catalog',
                        			'emulatePrepare' => true,
                        			'username' => 'walleye',
                        			'password' => '6ZSCZC2vPDFd3TfP',
                        			'charset' => 'utf8',
                			),
			"lindneo"=>array(
                        			'connectionString' => 'mysql:host=lindneo.com;dbname=catalog',
									'emulatePrepare' => true,
									'username' => 'db_catalog',
									'password' => 'ZqUVExpdps4tjmnG',
									'charset' => 'utf8',
                			)
);

$params=array(
	"walleye"=>array(
			'adminEmail'=>'webmaster@example.com',
			'reader_host'=>'http://reader.okutus.com',
			'catalog_host'=>'http://bigcat.okutus.com',
			'android_reader'=>'https://play.google.com/store/apps/details?id=com.linden.story.keloglan',
		),
	"lindneo"=>array(
			'adminEmail'=>'webmaster@example.com',
			'reader_host'=>'http://reader.lindneo.com/ekaratas',
			'catalog_host'=>'http://catalog.lindneo.com',
			'android_reader'=>'https://play.google.com/store/apps/details?id=com.linden.story.keloglan',
		),
	);

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Catalog Application',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'ww14@LnDnctl',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>false,
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			'rules'=>array(
				array('KerberizedService/authenticate','pattern'=>'kerberizedservice/authenticate/','verb'=>'POST'),
				'search|q'=>'site/search',
				'search|q/<key:\w+>'=>'site/search',
				'search|q/<key:\w+>/<page:\d+>'=>'site/search',
				'<id:\w+>'=>'/site/book',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database
		
		'db'=>$db_config_list[gethostname()],
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>$params[gethostname()],
);