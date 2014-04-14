<h3>List catalog</h3>
<?php 
$b=array(
	'organisationId'=>array(),
	'contentType'=>array(),
	'contentTitle'=>array(),
	'contentExplanation'=>array(),
	'contentIsForSale'=>array(),
	'categories'=>array(),
	'author'=>array(),
	);
$ex=array(
	'organisationId'=>array('linden','tubitak'),
	'contentType'=>array('epub','pdf'),
	'contentTitle'=>array('technology','science'),
	'contentIsForSale'=>array('Free'),
	'categories'=>array('1122','75643'),
	'author'=>array('ekaratas'),
	);
$c = json_encode($b);
$exp = json_encode($ex);
?>

Empty JSON:
<textarea rows="2" cols="110">
<?php echo $c; ?>
</textarea>
<br><br>
Example JSON:
<textarea rows="4" cols="110">
<?php echo $exp; ?>
</textarea>
<br>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>attributes:</b> JSON</li>
</ul>
<p><b>Result:</b> This query will return "Free", "Epub" OR "Pdf" books which organisations are "linden" OR "tubitak", and title like "technology" OR "science" and categories ids 1122 OR 75643</p>
<p><b>Call:</b> http://catalog.lindneo.com/api/list</p>
<br><br>

<h3>Get Catalog</h3>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Catalog ID</li>
</ul>
<p><b>Call:</b> http://catalog.lindneo.com/api/getCatalog</p>
<p><b>Result:</b> This query will return the catalog</p>
<br><br>

<h3>Get Catalog Detail</h3>
<p><b>Call:</b> http://catalog.lindneo.com/api/getDetail</p>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Catalog ID</li>
</ul>
<p><b>Result:</b> This query will return the catalog host etc</p>
<br><br>

<h3>Get Catalog Thumbnail</h3>
<p><b>Call:</b> http://catalog.lindneo.com/api/getThumbnail/Book_Id</p>
<p><b>Method:</b> GET</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Book Id</li>
</ul>
<p><b>Result:</b> This query will show the image</p>
<br><br>

<h3>Get Catalog Cover</h3>
<p><b>Call:</b> http://catalog.lindneo.com/api/getCover/Book_Id</p>
<p><b>Method:</b> GET</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Book Id</li>
</ul>
<p><b>Result:</b> This query will show the image</p>
<br><br>


<!-- <h3>Get Catalog Thumbnail</h3>
<p><b>Call:</b> http://catalog.lindneo.com/api/getThumbnail</p>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Catalog ID</li>
</ul>
<p><b>Result:</b> This query will return the catalog thumbnail Base64</p>
<br><br>

<h3>Get Catalog Cover</h3>
<p><b>Call:</b> http://catalog.lindneo.com/api/getCover</p>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Catalog ID</li>
</ul>
<p><b>Result:</b> This query will return the catalog cover Base64</p>
<br><br> -->

<h3>Get Catalog Main Info</h3>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Catalog ID</li>
</ul>
<p><b>Call:</b> http://catalog.lindneo.com/api/getMainInfo</p>
<p><b>Result:</b> This query will return the catalog's [Explanation, Date, Author,IsForSale, CurrencyCode, Price]'</p>
<br><br>

<h3>Get Catalog Meta</h3>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Catalog ID</li>
</ul>
<p><b>Call:</b> http://catalog.lindneo.com/api/getCatalogMeta</p>
<p><b>Result:</b> This query will return the catalog metas (thumbnail etc.)</p>
<br><br>

<h3>Get Catalog Meta Value</h3>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Catalog ID</li>
<li><b>metaKey:</b> ex: abstract, author, ciltNo, date, edition, issn, language, totalPage, translator </li>
</ul>
<p><b>Call:</b> http://catalog.lindneo.com/api/getCatalogMeta</p>
<p><b>Result:</b> This query will return the catalog metas (thumbnail etc.)</p>
<br><br>

<h3>Get Catalog Readers</h3>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Catalog ID</li>
</ul>
<p><b>Call:</b> http://catalog.lindneo.com/api/getCatalogReaders</p>
<p><b>Result:</b> This query will return the catalog readers (if not, returns default reader)</p>
<br><br>

<h3>List All Categories</h3>
<p>Call: http://catalog.lindneo.com/api/listAllCategories</p>
<p>Result: This query will return All categories (category_id | category_name | organisation_id)</p>
<br><br>

<h3>List Organisation Categories</h3>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Organisation ID</li>
</ul>
<p>Call: http://catalog.lindneo.com/api/getOrganisationCategories</p>
<p>Result: This query will return Organisation categories (category_id | category_name | organisation_id)</p>
<br><br>

<h3>List Category Catalogs</h3>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Category ID</li>
</ul>
<p><b>Call:</b> http://catalog.lindneo.com/api/listCategoryCatalogs</p>
<p><b>Result:</b> This query will return All Catalogs belongs to category send</p>
<br><br>

<h3>Get Content Hosts</h3>
<p><b>Method:</b> POST</p>
<ul><b>Attributes:</b>
<li><b>id:</b> Catalog ID</li>
</ul>
<p><b>Call:</b> http://catalog.lindneo.com/api/getContentHost</p>
<p><b>Result:</b> This query will return Hosts => id,address,port,key1,key2</p>
<br><br>