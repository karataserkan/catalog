<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>
<section id="page">
		<div class="row">
			<div class="col-md-12">
				<div class="divide-100"></div>
			</div>
		</div>
		<div class="row">
			<div class="container">
			<div class="col-md-12 not-found">
			   <div class="error">
				  Linden
			   </div>
			</div>
			<div class="col-md-6 not-found">
			   <div class="content">
				  <h3>Are you lost in the worlds?</h3>
				  <form action="<?php echo Yii::app()->request->baseUrl; ?>/site/search" method="GET">
					 <div class="input-group">
						<input type="text" class="form-control" placeholder="search here..." name="key">
						<span class="input-group-btn">                   
							<button type="submit" class="btn btn-danger" style="height:34px"><i class="fa fa-search"></i></button>
						</span>
					 </div>
				  </form>
			   </div>
			</div>
			</div>
		</div>
</section>
<script>
		jQuery(document).ready(function() {		
			App.setPage("widgets_box");  //Set current page
			App.init(); //Initialise plugins and elements
		});
</script>