<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<section id="page">
			<div class="container">
				<div class="row">

					
					<div id="content" class="col-lg-12">
						<!-- PAGE HEADER-->
						<div class="row">
							<div class="col-sm-12">
								<div class="page-header">
									<div class="clearfix">
										<h3 class="content-title pull-left"><?php echo $book->contentTitle; ?></h3>
									</div>
									<div class="description"><?php echo $book->author; ?></div>
								</div>
							</div>
						</div>
						<!-- /PAGE HEADER -->
						<div class="panel panel-default">
						  <div class="panel-body">
							<div class="divide-20"></div>
							<div class="row">
								<div class="col-sm-2">
									<img class="pull-left" src="<?php echo Yii::app()->request->baseUrl; ?>/api/getThumbnail?id=<?php echo $book->contentId ?>">
								</div>
								<div class="col-sm-6">
									<h3><?php echo $book->contentTitle; ?></h3>
									<p><b>Author:</b> <a href="<?php echo Yii::app()->request->baseUrl; ?>/q/author:<?php echo $book->author; ?>"><?php echo $book->author; ?></a></p>
									<?php if ($bookMeta["translator"]) { ?>
										<p><b>Translator:</b> <?php echo $bookMeta["translator"]; ?></p>
									<?php }?>

									<?php if ($book->organisationName) { ?>
										<p><b>Publisher:</b> <a href="<?php echo Yii::app()->request->baseUrl; ?>/q/publisher:<?php echo $book->organisationName; ?>"><?php echo $book->organisationName; ?></a></p>
									<?php } ?>
									<?php if ($bookMeta["subject"]) { ?>
										<p><b>Subject:</b> <?php echo $bookMeta["subject"]; ?></p>
									<?php } ?>
									<?php if ($book->contentExplanation) { ?>
										<p><b>Explanation:</b> <?php echo $book->contentExplanation; ?></p>
									<?php } ?>
									<?php if ($bookMeta["abstract"]) { ?>
										<p><b>Abstract:</b> <?php echo $bookMeta["abstract"] ?></p>
									<?php } ?>
									<?php if ($bookMeta["edition"]) { ?>
										<p><b>Edition:</b> <?php echo $bookMeta["edition"] ?></p>
									<?php } ?>
									<?php if ($bookMeta["language"]) { ?>
										<p><b>Language:</b> <?php echo $bookMeta["language"] ?></p>
									<?php } ?>
									<?php if ($bookMeta["publishDate"]) { ?>
										<p><b>Published:</b> <?php echo $bookMeta["publishDate"] ?></p>
									<?php } ?>
									<?php if ($bookMeta["totalPage"]) { ?>
										<p><b>Pages:</b> <?php echo $bookMeta["totalPage"] ?></p>
									<?php } ?>
								</div>
								<div class="col-sm-2">
									<div class="row">
										<br><a target="_blank" href="<?php echo Yii::app()->params['reader_host']; ?>/content/details/<?php echo $book->contentId; ?>" class="col-sm-6 btn btn-lg btn-info"><i class="fa fa-globe"> </i> Web</a>
									</div>
									<div class="row">
										<br><a target="_blank" href="<?php echo Yii::app()->params['android_reader']; ?>" class="col-sm-6 btn btn-lg btn-info"><i class="fa fa-android"> </i> Android</a>
									</div>
									<div class="row">
										<br><a href="#" class="col-sm-6 btn btn-lg btn-inverse" disabled><i class="fa fa-apple"> </i> IOS</a>
									</div>
									<div class="row">
										<br>
										<div id="fb-root"></div>
											<script>(function(d, s, id) {
											  var js, fjs = d.getElementsByTagName(s)[0];
											  if (d.getElementById(id)) return;
											  js = d.createElement(s); js.id = id;
											  js.src = "//connect.facebook.net/tr_TR/all.js#xfbml=1&appId=1427629194160426";
											  fjs.parentNode.insertBefore(js, fjs);
											}(document, 'script', 'facebook-jssdk'));</script>
											<div class="fb-share-button" data-href="<?php echo Yii::app()->params['catalog_host']; ?>/<?php echo $this->getNiceName($book->contentId); ?>" data-width="200" data-type="button_count"></div>
											<br>
											<!-- Place this tag where you want the share button to render. -->
											<div class="g-plus" data-action="share"></div>

											<!-- Place this tag after the last share tag. -->
											<script type="text/javascript">
											  (function() {
											    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
											    po.src = 'https://apis.google.com/js/platform.js';
											    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
											  })();
											</script>
									</div>
								</div>
							</div>
						</div>
						</div><!-- /CONTENT-->
						<div class="footer-tools">
							<span class="go-top">
								<i class="fa fa-chevron-up"></i> Top
							</span>
						</div>
			</div>
		</div>
	</section>
	<?php 
	if ($bookMeta["tracking"]) {
		echo stripcslashes(htmlspecialchars_decode($bookMeta["tracking"]));
	}
	?>
	<script>
		jQuery(document).ready(function() {		
			App.setPage("search_results");  //Set current page
			App.init(); //Initialise plugins and elements
		});
	</script>