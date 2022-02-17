<?php
$product = 'browseSeo';
$headerComponents = array(
	'js' => array(),
	'css'=>array('common','category-styles','browse_seo'),                                                
	'jsFooter' =>array('common','lazyload'),
	'product'=> $product,
	'taburl' =>  site_url(),
	'bannerProperties' => array(
				'pageId'=>'CATEGORY',
				'pageZone'=>'TOP',
				'shikshaCriteria' => $criteriaArray
				),
	'title'	=>	'View '.$catname.' courses in locations starting with '.$alphabet1,
	'searchEnable' => true,
	'canonicalURL' => $canonicalurl,
	'metaDescription' => 'View '.$catname.' courses in locations starting with '.$alphabet1,
	'metaKeywords'	=> ''
	);
if(count($subCategories)>1) {
$count = count($subCategories);
if($count%2 == 0) {
	$length = $count/2;
} else {
	$length = ($count/2)+1;
}
$subCategoriesnew = array_chunk($subCategories,$length);
$subCategoriesnew1 = $subCategoriesnew[0];
$subCategoriesnew2 = $subCategoriesnew[1];
} else {
$subCategoriesnew1 = $subCategories;
$subCategoriesnew2 = array();
}
if(count($LDBCourses)>1) {
$count = count($LDBCourses);
if($count%2 == 0) {
	$length = $count/2;
} else {
	$length = ($count/2)+1;
}
$LDBCoursesnew = array_chunk($LDBCourses,$length);
$LDBCoursesnew1 = $LDBCoursesnew[0];
$LDBCoursesnew2 = $LDBCoursesnew[1];
} else {
$LDBCoursesnew1 = $LDBCourses;
$LDBCoursesnew2 = array();
}
if(empty($alphabet1)) $alphabet1 = 'A';
$this->load->view('common/header', $headerComponents);
?>
    <!--Browse HTML STARTS HERE-->
    <div id="browse-contents">
    	<h2 class="bot-padd">View <span><?php echo $catname;?></span> courses in locations starting with <span><?php echo $alphabet1;?></span></h2>
        <div class="clearFix"></div>
        <div id="course-loc-list">
        	<ul>
                <?php foreach($locations as $alphabet):?>
                <?php if($alphabet1 == $alphabet) {$class='active';} else {$class='';}?>
                <li class="<?php echo $class;?>"><?php echo "<a href='/categoryList/Browse/page2/".$catid."/".$catname."/$scope/$alphabet'>$alphabet</a>";?><span></span></li>
                <?php endforeach;?>
            </ul>
        </div>
        <div class="clearFix"></div>
        <div class="box-shadow">
        	<div class="contents2">
            	<div id="browse-left-col2">
                	<ul>
                        <?php if(count($subCategoriesnew1)>0):?>
                        <?php $k=0;foreach($subCategoriesnew1 as $subCategory):?>
                    	<li <?php if($k % 2 !=0) {echo "class='alt-row'";}?>><a href="/categoryList/Browse/page3/subcategory/<?php echo $subCategory->getId().'/'.$alphabet1.'/'.$scope?>"><?php echo $subCategory->getName();?></a></li>
                        <?php $k++;endforeach;endif;?>
                        <?php if(count($LDBCoursesnew1)>0):?>
                       <?php $k=0;foreach($LDBCoursesnew1 as $LDBCourse):
                       $var = trim(str_replace("all",'',strtolower($LDBCourse->getDisplayName())));?>
                       <?php if(!in_array($var,$subcat_name_array)):?>
                    	<li <?php if(((count($subCategoriesnew1)>1 || count($subCategoriesnew1)==0) && $k % 2 !=0) || (count($subCategoriesnew1)==1 && $k % 2 ==0)) {echo "class='alt-row'";}?>><a href="/categoryList/Browse/page3/ldbcourse/<?php echo $LDBCourse->getId().'/'.$alphabet1.'/'.$scope?>"><?php echo str_replace(" ALL","",$LDBCourse->getDisplayName());?></a></li>
                        <?php $k++;endif;endforeach;endif;?>
                    </ul>
                </div>
               <?php if(count($subCategoriesnew2)>0 || count($LDBCoursesnew2)>0):?> 
                <div id="browse-right-col2">
                    <ul>
                        <?php if(count($subCategoriesnew2)>0):?>
                        <?php $k=0;foreach($subCategoriesnew2 as $subCategory):?>
                    	<li <?php if($k % 2 !=0) {echo "class='alt-row'";}?>><a href="/categoryList/Browse/page3/subcategory/<?php echo $subCategory->getId().'/'.$alphabet1.'/'.$scope?>"><?php echo $subCategory->getName();?></a></li>
                        <?php $k++;endforeach;endif;?>
                        <?php if(count($LDBCoursesnew2)>0):?>
                        <?php $k=0;foreach($LDBCoursesnew2 as $LDBCourse):
                        $var = trim(str_replace("all",'',strtolower($LDBCourse->getDisplayName())));?>
                         <?php if(!in_array($var,$subcat_name_array)):?>
                    	<li <?php if(((count($subCategoriesnew2)>1 || count($subCategoriesnew2) ==0) && $k % 2 !=0) || (count($subCategoriesnew2)==1 && $k % 2 ==0)) {echo "class='alt-row'";}?>><a href="/categoryList/Browse/page3/ldbcourse/<?php echo $LDBCourse->getId().'/'.$alphabet1.'/'.$scope?>"><?php echo str_replace(" ALL","",$LDBCourse->getDisplayName());?></a></li>
                        <?php $k++;endif;endforeach;endif;?>
                    </ul>
                </div>
                <?php endif;?>
                <div class="clearFix"></div>
            </div>
        </div>
        
        
        <div class="clearFix"></div>
    </div>
    <!--Browse HTML ENDS HERE-->
<div class="spacer20 clearFix"></div>	
<?php 
$this->load->view('common/footerNew');
?>
<script>
$j(document).ready(function($) {
if($('#browse-left-col2').height() >= $('#browse-right-col2').height()) {
	$('#browse-right-col2').height($('#browse-left-col2').height());
} else {
	$('#browse-left-col2').height($('#browse-right-col2').height());
}
});
</script>
