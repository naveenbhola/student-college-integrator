<?php
$urlCriteria = split('Page/', $_SERVER['REQUEST_URI']);
$urlCriteria = preg_replace('/[0-9]/','', $urlCriteria[1]);
if($urlCriteria == '') { $urlCriteria = $categoryUrlName .'/All/All/All'; }
else { $urlCriteria = $categoryUrlName .'/All/All/All'; }
if($pageType == 'category') {
    $allUrl = '/index.php/shiksha/showCategoryContentPage/'.$categoryUrlName .'/All/All/All';
    $courseUrl = '/getCategoryPage/colleges/'. rtrim($urlCriteria, '/') .'/course';
    $instituteUrl = '/getCategoryPage/colleges/'. $urlCriteria ;
    $resultKey = $allCategoryId;
}
if($pageType == 'country') {
    $allUrl = '/index.php/shiksha/showCountryContentPage/All/'. $contentTitle.'/All/All';
    $courseUrl = '/getCategoryPage/colleges/studyabroad/'. $contentTitle .'/All/All/course';
    $instituteUrl = '/getCategoryPage/colleges/studyAbroad/'. $contentTitle;
    $resultKey = $countryId;
}
if($pageType == 'exam') {
    $allUrl = '/index.php/shiksha/showTestPrepContentPage/'. $selectedExamId .'/All/'. $contentTitle;
    $instituteUrl = '/shiksha/testprep/'.$selectedExamId .'/'.$contentTitle;
    $courseUrl = '/shiksha/testprep/'.$selectedExamId .'/'.$contentTitle .'/course';
    $resultKey = $selectedExamId;
}
$productsCount = is_array($productsCount) ? $productsCount : array(array('totalCourseCount' => '', 'totalInstituteCount'=>'')) ;
$totalCourseCount = (($productsCount['totalCourseCount'][$resultKey]=='') || ($productsCount['totalCourseCount'][$resultKey]==0))?'No':$productsCount['totalCourseCount'][$resultKey];
$totalInstituteCount = (($productsCount['totalInstituteCount'][$resultKey]=='') || ($productsCount['totalInstituteCount'][$resultKey]==0))?'No':$productsCount['totalInstituteCount'][$resultKey];
?>
<div style="margin:0px">

    <div class="float_R" style="width:290px;">
        <div class="lineSpace_24" style="background:#F4F4F4; padding:0 5px">
            <a href="<?php echo $courseUrl; ?>" title="" class="careerCourses fontSize_12p bld" style="padding:3px 0 3px 20px"><span class="blackFont"><?php echo ($totalCourseCount); ?>
</span> Courses</a> &nbsp; &nbsp;
            <a href="<?php echo $instituteUrl; ?>" title="" class="careerInsititute fontSize_12p bld" style="padding:3px 0 3px 20px"><span class="blackFont"><?php echo ($totalInstituteCount); ?></span> Institutes</a>
        </div>
    </div>

    <div class="OrgangeFont bld fontSize_18p cat<?php echo str_replace(' ', '', ucwords($contentTitle)); ?>" style="margin-right:275px;padding-bottom:10px">
        <h1><span style="font-size:28px">
		<?php 
			if($contentTitle=='newzealand'){ 
				$contentTitle = 'New Zealand';
			}  
			echo ucwords($contentTitle); 
		?>
		</span><span id="subCatCaption" style="font-size:18px"></span></h1>
    </div>
</div>
<div class="lineSpace_5">&nbsp;</div>
<!--LeftPanel-->
<div class="lineSpace_5">&nbsp;</div>
<div style="margin:0 10px">
	<span class="bld fontSize_14p">Career Options in <?php echo $contentTitle; ?></span> &nbsp; 
</div>

<div class="bgBrowseByCategory" style="margin:0 1px;border-left:1px solid #CCCCCC; border-right:1px solid #CCCCCC;z-index:100">
        <div class="float_R" style="width:1px;position:relative;left:1px"><img src="/public/images/bgBrowseByCategoryLeft.gif" /></div>
        <div class="float_L" style="width:1px;position:relative;left:-1px"><img src="/public/images/bgBrowseByCategoryLeft.gif" /></div>
        <div>
            <div class="lineSpace_5">&nbsp;</div>
			<div style="margin:0 17px">
				<ul class="browseCategory">
						<li><a id="allSubCategory" href="<?php echo $allUrl; ?>" >All</a></li>
					<?php
					$otherElementId = '';
					global $selectedSubCategoryText ;
					foreach($subCategories as $subCategory) { 
                        if($pageType == 'exam') {
                            $subCategoryId = $subCategory['blogId'];
                            $subCategoryName = $subCategory['acronym'] == '' ? $subCategory['blogTitle'] : $subCategory['acronym'];
						    $subCategoryUrl = '/index.php/shiksha/showTestPrepContentPage/'. $selectedExamId .'/'. $subCategoryId .'/'. $contentTitle .'/'. $subCategoryName;
                        } else {
                            $subCategoryId = $subCategory['boardId'];
                            $subCategoryName = $subCategory['name'];
                            $subCategoryUrlName = $subCategory['urlName'];
                        }
						if($pageType == 'category') {
						$subCategoryUrl = '/index.php/shiksha/showCategoryContentPage/'.$categoryUrlName .'/All/All/'.$subCategoryUrlName;
						}
						if($pageType == 'country') {
						$subCategoryUrl = '/index.php/shiksha/showCountryContentPage/'.$subCategoryUrlName .'/'. $contentTitle.'/All/All';
						}
						$subCategorySelectedClass = '';
						if($subCategoryId == $categoryId) {
						$selectedSubCategoryText = $subCategoryName;
						$subCategorySelectedClass = 'selected';
						}
						if(strpos($subCategoryName,'Others..') !== false){
						$otherElementId = $subCategoryId ;
						$otherElementSelectedClass = $subCategorySelectedClass;
						$otherElementUrl = $subCategoryUrl;
						continue;
						}
					?>
						<li class="<?php echo $subCategorySelectedClass; ?>">
							<a class="<?php echo $subCategorySelectedClass; ?> fontSize_12p" href="<?php echo $subCategoryUrl; ?>" title="<?php echo $subCategoryName; ?>"><?php echo $subCategoryName; ?></a>
						</li>
					<?php 
					} 
					if($selectedSubCategoryText == '') {
						$selectedSubCategoryText = 'All Categories'; 
					}
					if($otherElementId != '') {
					?>
						<li class="<?php echo $otherElementSelectedClass; ?>">
						<a class="fontSize_12p <?php echo $otherElementSelectedClass; ?>" href="<?php echo $otherElementUrl; ?>" title="Others..." >Others...</a></li>
					<?php } ?>
				</ul>
				<div class="clear_L lineSpace_5">&nbsp;</div>
			</div>			
        </div>
        <div class="clear_B"></div>
</div>
<div class="defaultSpaceIE6">&nbsp;</div>
    <script>
    <?php 
        if($selectedSubCategoryText != 'All Categories') {
    ?>
        document.getElementById('subCatCaption').innerHTML = ': <?php echo $selectedSubCategoryText; ?>';
    <?php
        } else {
            $selectedSubCategoryText = $contentTitle ;
    ?>
        document.getElementById('allSubCategory').className = 'selected';
        document.getElementById('allSubCategory').parentNode.className = 'selected';
    <?php 
        }
    ?>
    </script>
