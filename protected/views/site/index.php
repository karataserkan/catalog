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
					 <div class="input-group">
						<input id="searchkey" type="text" class="form-control" placeholder="search here..." name="key" onkeypress="return runScript(event)">
						<span class="input-group-btn">                   
							<button id="searchbutton" type="button" class="btn btn-danger" style="height:34px"><i class="fa fa-search"></i></button>
						</span>
					 </div>
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