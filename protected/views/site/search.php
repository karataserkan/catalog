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
								  <div class="input-group">
									<input id="searchkey" class="form-control" type="text" placeholder="<?php echo ($criteria) ? $criteria : 'Search...' ;?>" value="<?php echo ($criteria) ? $criteria : '' ;?>" onkeypress="return runScript(event)" name="key">
									<span class="input-group-btn">
										<button id="searchbutton" class="btn btn-primary" type="button">Search <i class="fa fa-search"></i></button>
									</span>
								  </div>
							   </div>
							</div>
							<div class="divide-20"></div>
							<?php if ($totalBooks) { ?>
								<h4><?php echo $totalBooks; ?> Result<?php echo ($totalBooks>1) ? "s" : "" ;?></h4>
								<p>Page <?php echo $currentPage; ?> / <?php echo $totalPage; ?></p>
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
											<div class="row">
									   		<img class="pull-left" src="<?php echo Yii::app()->request->baseUrl; ?>/api/getThumbnail?id=<?php echo $book->contentId ?>" height="90">
									   		<h4><a href="<?php echo Yii::app()->request->baseUrl; ?>/<?php echo $this->getNiceName($book->contentId); ?>"><?php echo $book->contentTitle; ?></a></h4>
									   		<div class="url"><a href="<?php echo Yii::app()->request->baseUrl; ?>/q/publisher:<?php echo $book->organisationName; ?>"><?php echo $book->organisationName; ?></a></div>
									   		<div class="url"><a href="<?php echo Yii::app()->request->baseUrl; ?>/q/author:<?php echo $book->author; ?>"><?php echo $book->author; ?></a></div>
									   		<p><?php echo $book->contentExplanation; ?></p>
									   		<hr>
									   		</div>
										<?php } ?>
									</div>
								<?php } ?>
								<div>
										<ul class='pagination'>
										  <li <?php echo ($currentPage==1) ? "class='disabled'" : "" ; ?>>
											<a href='/q/<?php echo $criteria ?>/<?php echo ($currentPage>1) ? ($currentPage-1) : "#" ; ?>' style="height:32px">
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
										  			echo "<li><a href='/q/".$criteria."/".$i."'>".$i."</a></li>";
										  		}
										  	}
										  ?>
										  <li <?php echo ($currentPage>=$totalPage) ? "class='disabled'" : "" ; ?>>
											<a href='/q/<?php echo $criteria ?>/<?php echo ($currentPage!=$totalPage) ? ($currentPage+1) : "#" ; ?>'>
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
<script type="text/javascript">
	var key;
	function runScript(e)
	{
		if (e.keyCode == 13) {
			key=$('#searchkey').val();
			redirect(key);
		};
		console.log(key);
	}

	function redirect(key)
	{
		window.location.href="<?php echo Yii::app()->params['catalog_host'] ?>/q/"+key;
	}

	$('#searchbutton').click(function(){
		key=$('#searchkey').val();
		redirect(key);
	});
</script>
