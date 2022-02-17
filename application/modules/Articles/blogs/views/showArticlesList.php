<?php
if(!$isCoursePagesTabsEnabled) {
	if($blogType=='news'){
                $metaTitle=$pageNumber.'Education News Articles - Read Latest Education News from India and Abroad - Shiksha.com';
        }else if($blogType=='ALL_NEWS_ARTICLES'){
                $metaTitle = 'Latest Education News and Articles - Shiksha.com';
        }
        else{
                $metaTitle = 'Higher Education Articles by Career Experts - Topics on Education in India & Abroad, Career Courses, Top Colleges, Institutes &amp; Universities, Competitive Exams & Job Prospects.';
        }
        
        if($blogType=='news'){
                $metaDescription =$pageNumber.'Get the latest online education news at Shiksha.com. Read all latest daily education news articles on entrance exams, admissions, colleges, results, study material, and more.';
        }else if($blogType=='ALL_NEWS_ARTICLES'){
                $metaDescription = 'Read latest education news and articles on colleges, courses, career, entrance exams, results, admission notification, important dates and more.';
        }else{
                $metaDescription = 'Higher Education Articles by Career Experts. Find details on latest courses, study routes, Top Education Stories, Indian Education Features, special education & lots more.';       
        }	
	$metKeywords = 'Latest Courses, Study Routes, Top Education Stories, Indian Education Features, Special Education,  Education, Colleges, Courses, Institutes, Universities, Career, Study Routes, Scholarships, Expert Articles, Admissions, Results, Study Abroad, Foreign Education, Career Options, Exams, Events, Expert Articles, Education Articles';
}

$headerComponents = array(
    'css'	=>	array('articles','category-styles'),
    'jsFooter'      =>      array('common'),
    'title'	=>	$metaTitle,
    'tabName'	=>	'Articles',
    'taburl' =>  $_SERVER['REQUEST_URI'],
    'metaKeywords'	=> $metKeywords,
    'metaDescription' => $metaDescription,
    'product' => 'Articles',
    'shikshaProduct' => 'Articles',   
	'bannerProperties' => array('pageId'=>'ARTICLES_LIST', 'pageZone'=>'HEADER'),
	'canonicalURL'=>$canonicalURL,
    'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
    'callShiksha'=>1,
	'searchEnable'=>true
);

$this->load->view('common/header', $headerComponents);
//$this->load->view('blogs/headerSearchPanelForArticles');

if(isset($engineeringExamPage) && $engineeringExamPage=='true') {
	$paginatedPage = doPaginationForArticleAuthor($totalArticles,$paginationURL,$startOffset,$countOffset,4);
    $paginationHTML = preg_replace('/-1"/','"',$paginatedPage);    
} else if($tab_required_course_page) {
	$paginationHTML = doCoursepagePagination($totalArticles,$paginationURL,$startOffset,$countOffset,4);
} else if($blogType=="news"){
	$paginatedPage = doPaginationForArticleAuthor($totalArticles,$paginationURL,$startOffset,$countOffset,4);
    $paginationHTML = preg_replace('/-1"/','"',$paginatedPage);
} else if($blogType == 'news_Articles' && count($subcatArr['subCatArr'])>0){
	$paginatedPage = doPaginationForArticleAuthor($totalArticles,$paginationURL,$startOffset,$countOffset,4);
        $paginationHTML = preg_replace('/-1"/','"',$paginatedPage);
} else if($blogType == 'ALL_NEWS_ARTICLES'){
        $paginatedPage = doCoursepagePagination($totalArticles,$paginationURL,$startOffset,$countOffset,4);
        $paginationHTML = preg_replace('/-1"/','"',$paginatedPage);
}else {
	$paginationHTML = doPagination($totalArticles,$paginationURL,$startOffset,$countOffset,4);
	$displayArr = array(10,15,20,25);
	//$selectBoxHTML = getPaginationSelectBox($totalArticles,$paginationURL,$startOffset,$countOffset,$displayArr,"View : ");
} ?>

<script>if($('tempSearchType')) $('tempSearchType').value = 'blog';</script>

<?php if($isCoursePagesTabsEnabled):?>
	<script>if($('tempkeyword')){ $('tempkeyword').value = 'Search for any Institute or Course'; $('tempkeyword').setAttribute('default','Search for any Institute or Course'); }</script>
<?php else:?>
	<script>if($('tempkeyword')){ $('tempkeyword').value = 'Search Articles'; $('tempkeyword').setAttribute('default','Search Articles'); }</script>
<?php endif;?>

<input type="hidden" name="categoryId" id="categoryId" value="<?php echo $selectedCategory; ?>"/>
<input type="hidden" name="countryId" id="countryId" value="<?php echo $selectedCountry; ?>"/>
<input type="hidden" name="articleType" id="articleType" value="<?php echo $selectedType ; ?>"/>

<?php
    if($blogType=='ALL_NEWS_ARTICLES'){
        $resultText = $totalArticles .' News and Articles';
    }else{
        if($totalArticles < 1) { $resultText = 'No Articles'; }
        if($totalArticles == 1) { $resultText = $totalArticles .' Article'; }
        if($totalArticles > 1) { $resultText = $totalArticles .' Articles'; }
    }

?>

<?php
	if(!(isset($subCategoryId) && $subCategoryId != "") || 1) { ?>
		<div class="spacer10 clearFix"></div>
	<?php } ?>

	<div class="mlr10">
		<div style="float:left; width:100%;">
		    <?php
				$isAcoursePage = 0;$isShowTitle = 0;
				if(isset($subCategoryId) && $subCategoryId != "") {
					$backLinkArray['AUTOSCROLL'] = 1;
				    /*if(isset($engineeringExamPage) && $engineeringExamPage=='true'){
				    	$response = Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $subCategoryId, "Exams", TRUE, $backLinkArray,TRUE);
				    }
				    else{
				    	//$response = Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $subCategoryId, "News", TRUE, $backLinkArray,TRUE);
				    }*/
				    if($subCategoryId !='') {
						//echo $response;
						$isAcoursePage = 1;
						global $COURSE_PAGES_SUB_CAT_ARRAY;		
						$msg = "News and Articles related to ".$COURSE_PAGES_SUB_CAT_ARRAY[$subCategoryId]['Name']." in India";
				    }
				    if(isset($engineeringExamPage) && $engineeringExamPage=='true'){
						$msg = $caption;
				    }
				}
				
				if($blogType == 'news_Articles' && count($subcatArr['subCatArr'])>0){
					$isShowTitle = 1;
					global $ARTICLES_CATEGORY_NAME_ARRAY;		
					$msg = "News and Articles related to ".$ARTICLES_CATEGORY_NAME_ARRAY[$subcatArr['catId']]['Name']." in India";
				}
			?>
	    	
	        <div style="padding:5px;float:left;">
	        	<?php if($isAcoursePage  || $isShowTitle) { ?>
	            	<h1 style="font-size:20px"><span id="criteriaLabel" class="OrgangeFont"><?=$msg?></span></h1>
		    	<?php } else { ?>
		    		<h1 style="font-size:20px"><span id="criteriaLabel" class="OrgangeFont"></span> <span id="resultText" class="blackColor"><?php echo $resultText; ?> available</span></h1>
		    	<?php } ?>
	        </div>
        	
        	<div class="float_R" style="margin-top:16px;">
            	<div id="pagingIDc">
				    <span>
				        <span class="pagingID" id="paginataionPlace1"><?php echo preg_replace('/\/0\/25/','',$paginationHTML);?></span>
					</span>
            	</div>
        	</div>
			<div class="clearFix"></div>
		</div>
    	<div style="padding-left:5px;">
            <?php
            	$remaining = ($startOffset + $countOffset) ;
            	$remaining = $remaining > $totalArticles ? $totalArticles : $remaining;
            ?>
           Showing <?php echo ($startOffset +1) .' - ' . $remaining .' of '. $totalArticles  ; ?> 
    	</div>
    	
    	<div class="dottedLine">&nbsp;</div>
		<!--MiddlePanel-->
        <?php 
	$data['paginationHTML'] = $paginationHTML;
	$data['isAcoursePage'] = $isAcoursePage;
	$data['isShowTitle'] = $isShowTitle;
	$this->load->view('blogs/articleListMiddlePanel',$data); ?>
	<!--End_MidPanel-->
    <div class="clearFix"></div>			
</div>
<?php
	$this->load->view('common/footer', $bannerProperties);
?>    
<script>
    /*selectComboBox(document.getElementById('countOffset_DD1'), document.getElementById('countOffset').value);
    selectComboBox(document.getElementById('countOffset_DD2'), document.getElementById('countOffset').value);
    doPagination(<?php echo $totalArticles;?>,'startOffset','countOffset','paginataionPlace1','paginataionPlace2','methodName',4);
    function getArticlesForCriteria(){
        var startOffset = document.getElementById('startOffset').value;
        var countOffset = document.getElementById('countOffset').value;
        var urlParams = 'startOffset='+startOffset +'&countOffset='+countOffset +'&<?php echo $criteria; ?>';
        location.replace('/index.php/blogs/shikshaBlog/showArticlesList?'+ urlParams);
    }*/
    //callPaginationMethod();
    function callPaginationMethod()
    {
	  $('countOffset_DD1').innerHTML = '<?php echo $selectBoxHTML;?>';
	  $('countOffset_DD2').innerHTML = '<?php echo $selectBoxHTML;?>';
    }

</script>
