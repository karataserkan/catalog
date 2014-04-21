<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="description" content="<?php echo htmlspecialchars($this->metaDescription);?>">
	<meta name="keywords" content="<?php echo htmlspecialchars($this->metaKeywords);?>">
	<meta name="author" content="<?php echo htmlspecialchars($this->metaAuthor); ?>">
	<meta name="subject" content="<?php echo htmlspecialchars($this->metaSubject); ?>">

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/css/cloud-admin.css" >
	<!--<link rel="stylesheet" type="text/css"  href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/css/themes/night.css" >-->
	<link rel="stylesheet" type="text/css"  href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/css/responsive.css" >
	<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/font-awesome/css/font-awesome.min.css" rel="stylesheet">

	<!-- DATE RANGE PICKER -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/bootstrap-daterangepicker/daterangepicker-bs3.css" />
	<!-- UNIFORM -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/uniform/css/uniform.default.min.css" />
	<!-- ANIMATE -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/css/animatecss/animate.min.css" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/bootstrap-wizard/wizard.css" />
	
<!-- JS -->

<!-- JAVASCRIPTS -->
		<!-- Placed at the end of the document so the pages load faster -->
		<!-- JQUERY -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/jquery/jquery-2.0.3.min.js"></script>
		<!-- JQUERY UI-->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js"></script>
		<!-- BOOTSTRAP -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/bootstrap-dist/js/bootstrap.min.js"></script>
		<!-- ISOTOPE -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/isotope/jquery.isotope.min.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/isotope/imagesloaded.pkgd.min.js"></script>
		<!-- COLORBOX -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/colorbox/jquery.colorbox.min.js"></script>            

            
		<!-- DATE RANGE PICKER -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/bootstrap-daterangepicker/moment.min.js"></script>
		
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/bootstrap-daterangepicker/daterangepicker.min.js"></script>
		<!-- SLIMSCROLL -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/jQuery-slimScroll-1.3.0/jquery.slimscroll.min.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/jQuery-slimScroll-1.3.0/slimScrollHorizontal.min.js"></script>
		<!-- COOKIE -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/jQuery-Cookie/jquery.cookie.min.js"></script>
		<!-- CUSTOM SCRIPT -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/script.js"></script>

		<!-- UNIFORM -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/uniform/jquery.uniform.min.js"></script>
		<!-- BACKSTRETCH -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/backstretch/jquery.backstretch.min.js"></script>

	<!-- EASY PIE CHART -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/jquery-easing/jquery.easing.min.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/easypiechart/jquery.easypiechart.min.js"></script>
		<!-- FLOT CHARTS -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/flot/jquery.flot.min.js"></script>
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/flot/jquery.flot.time.min.js"></script>
	    <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/flot/jquery.flot.selection.min.js"></script>
		<!-- <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/flot/jquery.flot.resize.min.js"></script>-->
	    <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/flot/jquery.flot.pie.min.js"></script>
	    <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/flot/jquery.flot.stack.min.js"></script>
		<!-- GRITTER -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/gritter/js/jquery.gritter.min.js"></script>
		<!-- TYPEHEAD -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/typeahead/typeahead.min.js"></script>
		<!-- AUTOSIZE -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/autosize/jquery.autosize.min.js"></script>
		<!-- COUNTABLE -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/countable/jquery.simplyCountable.min.js"></script>
		<!-- INPUT MASK -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/bootstrap-inputmask/bootstrap-inputmask.min.js"></script>
		<!-- FILE UPLOAD -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/bootstrap-fileupload/bootstrap-fileupload.min.js"></script>
		<!-- SELECT2 -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/select2/select2.min.js"></script>
		<!-- UNIFORM -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/uniform/jquery.uniform.min.js"></script>
	

			<!-- WIZARD -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/bootstrap-wizard/jquery.bootstrap.wizard.min.js"></script>
		<!-- WIZARD -->
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/jquery-validate/jquery.validate.min.js"></script>
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/jquery-validate/additional-methods.min.js"></script>
		<!-- BOOTBOX -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/bootbox/bootbox.min.js"></script>
		<script src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/bootstrap-wizard/form-wizard.js"></script>
		<!-- HUBSPOT MESSENGER -->
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/hubspot-messenger/js/messenger.min.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/hubspot-messenger/js/messenger-theme-flat.js"></script>
		<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/ui/js/hubspot-messenger/js/messenger-theme-future.js"></script>

		<!--<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/libs/lazyloader.min.js"></script>-->
		<!-- /JS -->
	<title>
	<?php if ($this->metaTitle) {
		echo $this->metaTitle;
	}
	else{
		echo CHtml::encode($this->pageTitle);
	} ?>
	</title>
</head>
<?php if (Yii::app()->controller->action->id=="index"):?>
<body id="not-found-bg">
	<div class="overlay"></div>
<?php else: ?>
<body>
	<div class="overlay"></div>

<?php endif; ?>
	<?php echo $content; ?>
</body>
</html>
