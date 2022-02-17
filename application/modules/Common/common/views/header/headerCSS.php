<?php 
$cssUrl = getCssUrl();
 /* search and home page dose not load common_new.css*/
if(!in_array($product, array('SearchV2','home','anaDesktopV2','institutePage','coursePage','Category','allContentPage', 'new_profile_page', 'resultPage','examPage', 'shiksha_analytics', 'ranking','collegePredictorV2', 'rankPredictorV2','collegeCutoff'))) {?>
	<link href="//<?php echo $cssUrl; ?>/public/css/<?php echo getCSSWithVersion("common_new"); ?>" type="text/css" rel="stylesheet" />
<?php } 
echo includeCSSFiles('shiksha-com');
?>

<?php if($product == 'anaDesktopV2') {?>
			<style type="text/css">
		  /*homePageRegistration Layer css*/
		  .Overlay{display:none;padding:10px;position:absolute;width:250px;z-index:1000001}
		</style>
<?php } 
	if($product == 'forums'){
		$css = array('common','ask');
	} else if($product == 'RNRCategoryPage'){
		$css = array('category-revamp');
	} else if($product == 'examPage'){
		$css = array('examPage');
	}else if($product == 'myShortlist'){
		$css = array('shortlist','recommend','BeatPicker','quesDiscPosting');
	}elseif(($product == 'categoryHeader' || $product == 'testprep' || $product == 'MBA' || $product == 'gradHeader') && ($css[0] != 'listing'))
	{
		if(($product == 'categoryHeader' || $product == 'testprep' || $product == 'MBA' || $product == 'gradHeader'))
			$css = array('category-styles','recommend');
		else
			$css = array('common','category-styles','recommend');
		
	}elseif($product == 'events'){
		$css = array('common','impDate');
	} else if(strtolower($product) == 'search'){
		$css = array('search','category-styles','recommend');
	} else if(strtolower($product) == 'ranking'){
		$css = array('ranking_page');
        /*if(NEW_RANKING_PAGE) {
		} else {
		$css = array('ranking','recommend');
        }*/
	} elseif($product == 'foreign'){
		$css = array('studyAbroad-styles','recommend');
	}elseif($product == 'ArticlesD'){
        }elseif($product == 'CareerProduct'){
	        $css = array('careers');		
	}elseif($product == 'coursePagesHeader'){ 
                $css = array('coursePages');
	}elseif($product == 'campusAmbassador'){
	        $css = array('campus-representative','registration');
	}elseif($product == 'campusAmbassadorMarketing'){
	        $css = array('campus-connect-mkt');
	}elseif($product == 'CollegeReviewForm'){
		$css = array('campus-representative','colge_revw_desk');
	}elseif($product=='home' && in_array('shiksha_common',$css)){ ?>
	    <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("shiksha_common"); ?>" type="text/css" rel="stylesheet" />
    	
    	<?php }elseif(isset($css) && is_array($css) && !in_array('mainStyle',$css) && $page_is_listing !='YES' && $product != 'naukritool' && $product!='collegeReviewHomepage' && $product != 'mentor'){ ?>
    	
    	<?php if($product != 'SearchV2' && $product != 'home' && $product != 'anaDesktopV2') {?>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("common"); ?>" type="text/css" rel="stylesheet" />
		<?php } ?>
<?php	}else{ ?>
                <?php if($page_is_listing != 'YES' && $product != 'naukritool' && $product!='collegeReviewHomepage' && $product != 'mentor'): ?>
		<link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion("mainStyle"); ?>" type="text/css" rel="stylesheet" />
                <?php endif;?>
<?php
}

if(isset($css) && is_array($css)) {	
        foreach($css as $cssFile) {
            if(!in_array($cssFile,$cssExclude)) { 

?>
 <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
<?php
            }
        }
    }
?>
<?php 
echo includeCSSFiles($product);
?>
