<header id="page-header" class="clearfix" >
        <div data-role="header" data-position="fixed" class="head-group ui-header-fixed" data-tap-toggle="false">
	    <a id="categoryOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a>   
            <h3>Choose desired Stream</h3>
        </div>
</header>

<section class="content-wrap2 fixed-wrap">
	<ul class="stream-list">

<?php

foreach($HomePageData as $key=>$tab_object) {
	$className = 'icn-'.strtolower($tab_object['id']);
		if($key == 3) {
			$title_of_cat = 'MBA Colleges and Courses';
		      } else if($key == 2) {
		$title_of_cat = 'Engineering Colleges and Courses'; 
		      } else if($key == 4) {
		$title_of_cat = 'Banking Institutes and Courses';   
		      } else if($key == 10) {
		$title_of_cat = 'IT Colleges and Courses';  
		      } else if($key == 14) {
		$title_of_cat = 'Coaching Classes'; 
		      } else if($key == 12) {
		$title_of_cat = 'Animation Courses and Institutes'; 
		      } else if($key == 6) {
		$title_of_cat = 'Hotel Management Courses and Colleges';    
		      } else if($key == 5) {
		$title_of_cat = 'Medical Colleges and Courses'; 
		      } else if($key == 13) {
		$title_of_cat = 'Fashion and Design Courses';   
		      } else if($key == 7) {
		$title_of_cat = 'Media and Journalism Courses'; 
		      } else if($key == 9) {
		$title_of_cat = 'Law Colleges and Courses'; 
		      } else if($key == 11) {
		$title_of_cat = 'Retail Management Courses';    
		      }
?>
	<li onClick="categorySelected('<?=$key;?>','scroll');" style="cursor:pointer;" id="categoryList<?=$key;?>">
        	<figure>
			<i class="<?=$className?>"></i>
		</figure>
		<div class="details">
		    <h2 id="categoryName<?=$key?>"><?php echo $tab_object['name'];?></h2>
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
$(document).ready(function(){
if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(fillLocationUsingGeo);
}
createLocationHTML();
});

</script>

<?php
if(isset($_COOKIE['abroad_selected']) && $_COOKIE['abroad_selected']==1){
	echo "<script>toggleTabs(2)</script>";
}
if(isset($_COOKIE['category_selected']) && $_COOKIE['category_selected']>0){
	echo "<script>categorySelected(".$_COOKIE['category_selected'].")</script>";
}
?>
