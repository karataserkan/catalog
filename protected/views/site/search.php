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
										<h3 class="content-title pull-left">Search Results</h3>
									</div>
									<div class="description">Easy Search Experience</div>
								</div>
							</div>
						</div>
						<!-- /PAGE HEADER -->
						<div class="panel panel-default">
						  <div class="panel-body">
							<div class="row">
							   <div class="col-md-12">
							   	<form action="<?php echo Yii::app()->request->baseUrl; ?>/site/search" method="POST">
								  <div class="input-group">
									<input class="form-control" type="text" placeholder="<?php echo ($criteria) ? $criteria : 'Search...' ;?>" value="<?php echo ($criteria) ? $criteria : '' ;?>" name="text">
									<span class="input-group-btn">
										<button class="btn btn-primary" type="button">Search <i class="fa fa-search"></i></button>
									</span>
								  </div>
								</form>
							   </div>
							</div>
							<div class="divide-20"></div>
							<?php if ($totalBooks) { ?>
								<h4>Page <?php echo $currentPage; ?> / <?php echo $totalBooks; ?> Result<?php echo ($totalBooks>1) ? "s" : "" ;?></h4>
							<?php }
							else
							{ ?>
								<h4>Nothing found. Change your search criteria.</h4>
							<?php } ?>
							<div class="divide-20"></div>
							<div class="row">
								<div class="col-md-12">
								<?php if (!empty($books)) { ?>
									<div class="search-results">
										<?php
										foreach ($books as $k => $book) {?>
									   		<img class="pull-left" src="<?php echo Yii::app()->request->baseUrl; ?>/api/getThumbnail?id=<?php echo $book->contentId ?>" height="80">
									   		<h4><a href="<?php echo Yii::app()->request->baseUrl; ?>/site/book?name=<?php echo $this->getNiceName($book->contentId); ?>"><?php echo $book->contentTitle; ?></a></h4>
									   		<div class="url"><?php echo $book->organisationName; ?></div>
									   		<div class="url"><?php echo $book->author; ?></div>
									   		<p><?php echo $book->contentExplanation; ?></p>
										<?php } ?>
									</div>
								<?php } ?>
								<div>
										<ul class='pagination'>
										  <li <?php echo ($currentPage==1) ? "class='disabled'" : "" ; ?>>
											<a href='<?php echo ($currentPage>1) ? "/site/search?page=".($currentPage-1)."&key=".$criteria : "#" ; ?>' style="height:32px">
											  <i class='fa fa-caret-left'></i> Prev
											</a>
										  </li>
										  <?php
										  	for ($i=1; $i <= $totalPage; $i++) { 
										  		if ($i==$currentPage) {
										  			echo "<li class='active'><a href='#'>".$i."</a></li>";
										  		}
										  		else
										  		{
										  			echo "<li><a href='/site/search?page=".$i."&key=".$criteria."'>".$i."</a></li>";
										  		}
										  	}
										  ?>
										  <li <?php echo ($currentPage>=$totalPage) ? "class='disabled'" : "" ; ?>>
											<a href='<?php echo ($currentPage!=$totalPage) ? "/site/search?page=".($currentPage+1)."&key=".$criteria : "#" ; ?>'>
											  Next <i class='fa fa-caret-right'></i>
											</a>
										  </li>
										</ul>
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
	<script>
		jQuery(document).ready(function() {		
			App.setPage("search_results");  //Set current page
			App.init(); //Initialise plugins and elements
		});
	</script>