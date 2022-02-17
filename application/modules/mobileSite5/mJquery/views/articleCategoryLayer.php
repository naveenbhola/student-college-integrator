<header id="page-header" class="clearfix" >
        <div data-role="header" data-position="fixed" class="head-group ui-header-fixed" data-tap-toggle="false">
	    <a id="" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   
            <div class="title-text">Choose desired Stream</div>
        </div>
</header>

<section class="content-wrap2 fixed-wrap">
	<ul class="stream-list">

	<li onClick="articleCategorySelected('');" style="cursor:pointer;position: relative;" id="categoryList0">
        	<figure>
			<i class="icon-article"></i>
		</figure>
		<div class="details">
		    <h2>All Articles</h2>
		</div>
		
	</li>
	
<?php

foreach($HomePageData as $key=>$tab_object) {
	$className = 'icn-'.strtolower($tab_object['id']);
		
?>
	
	<li onClick="articleCategorySelected('<?=$key;?>');" style="cursor:pointer;position: relative;" id="categoryList<?=$key;?>">
        	<figure>
			<i class="<?=$className?>"></i>
		</figure>
		<div class="details">
		    <h2><?php echo $tab_object['name'];?></h2>
		    <span>
			    <?php if($key == 3):?>
			    Full Time MBA | Part Time MBA | Distance MBA | BBA
			    <?php elseif($key == 2):?>
			    BE/BTech | MTech | Agriculture &amp; Forestry
			    <?php elseif($key == 14):?>
			    Govt. Sector Examinations | Medical Exams | Engineering Exams
			    <?php elseif($key == 12):?>
			    Animation | Graphic Designing | Web Designing
			    <?php elseif($key == 4):?>
			    Accounting | Banking | CA related | Commerce
			    <?php elseif($key == 10):?>
			    BCA | MCA | Part Time MCA | Distance MCA | Networking
			    <?php elseif($key == 6):?>
			    BHM | Air Hostess Training | Airlines &amp; Ticketing
			    <?php elseif($key == 7):?>
			    Acting, Modelling | Advertising | Films and Television
			    <?php elseif($key == 13):?>
			    Interior Design | Fashion &amp; Textile Design | Interaction Design | Industrial, Automotive, Product Design
			    <?php elseif($key == 9):?>
			    Arts &amp; Humanities | Creative Arts | Government Sector
			    <?php elseif($key == 11):?>
			    Front Office | Inventory | Shop Floor management
			    <?php elseif($key == 5):?>
			    Clinical Research | Dental Sciences | Health Care
			    <?php endif;?>
		    </span>
		</div>
		
	</li>
	<?php } ?>
    </ul>
</section>
<script>
function articleCategorySelected(categoryId){

	trackEventByGAMobile('HTML5_ARTICLE_CATEGORY_CLICK');
	if(categoryId!="")
		url= "<?=$baseUrl;?>?category="+categoryId;
	else
		url = "<?=$baseUrl;?>";
	setCookie('articleCategory',categoryId,0,'/',COOKIEDOMAIN);

	window.location.href = url;
	
}
</script>
