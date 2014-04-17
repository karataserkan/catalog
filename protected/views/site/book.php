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
									<p><b>Author:</b> <?php echo $book->author; ?></p>
									<?php if ($translator) { ?>
										<p><b>Translator:</b> <?php echo $translator; ?></p>
									<?php }?>

									<?php if ($book->organisationName) { ?>
										<p><b>Publisher:</b> <?php echo $book->organisationName; ?></p>
									<?php } ?>
									<?php if ($subject) { ?>
										<p><b>Subject:</b> <?php echo $subject; ?></p>
									<?php } ?>
									<?php if ($book->contentExplanation) { ?>
										<p><b>Explanation:</b> <?php echo $book->contentExplanation; ?></p>
									<?php } ?>
									<?php if ($abstract) { ?>
										<p><b>Abstract:</b> <?php echo $abstract; ?></p>
									<?php } ?>
									<?php if ($edition) { ?>
										<p><b>Edition:</b> <?php echo $edition; ?></p>
									<?php } ?>
									<?php if ($language) { ?>
										<p><b>Language:</b> <?php echo $language; ?></p>
									<?php } ?>
									<?php if ($publishDate) { ?>
										<p><b>Published:</b> <?php echo $publishDate; ?></p>
									<?php } ?>
									<?php if ($totPage) { ?>
										<p><b>Pages:</b> <?php echo $totPage; ?></p>
									<?php } ?>
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
	<script>
		jQuery(document).ready(function() {		
			App.setPage("search_results");  //Set current page
			App.init(); //Initialise plugins and elements
		});
	</script>