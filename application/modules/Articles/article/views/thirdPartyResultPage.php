<?php
ob_start('compressHTML');
$tempJsArray = array();
$headerComponents = array(
                'css'   =>  array('vitResult'),
                'js' => array('vitResult'),
                'jsFooter'=>    $tempJsArray,
                'showBottomMargin' => false,
                'title' =>      $seoTitle,
                'metaDescription' => $metaDescription,
                'canonicalURL' =>$canonicalURL,
                'lazyLoadJsFiles' => true,
                'noIndexNoFollow' => true,
                'product' => 'thirdPartyResult'
);
$this->load->view('common/header', $headerComponents);
?>
<div class="breadcrumb2">
             <span class="home"><a href="<?=SHIKSHA_HOME;?>"><span>Home</span></a></span>
             <span class="breadcrumb-arrow">&#8250;</span>
             <span><a href="<?=SHIKSHA_HOME;?>/b-tech/articles-pc-10"><span>All B.Tech Articles</span></a></span>
             <span class="breadcrumb-arrow">&#8250;</span>
             <span ><?=$examType;?> 2017 Admit Card Download Begins</span>                            
</div>
<div class="title-head">
    <h1> Your <?=$examType;?> 2017 Result</h1>
    <a class="btn-primary orange" id="modify-search" ga-attr="MODIFY_SEARCH"> Modify Search</a>
</div>
<div class="clr"></div>
 <div id="vitee-result-card" class="card vitee-result-card output-card">
          <p class="disc-text">
            Shiksha is hosting the official <?=$examType;?> 2017 Result for your convenience. The scores you can see are directly sourced from the <?=$examName;?> website. 
          </p>
          <?php if($examName != 'SRM'){?>
          <p class="disc-text">
            <a href="<?=$examUrl;?>" ga-attr="<?=$gaAttrExamLink;?>" target="_blank">Click here</a> to get redirected to the actual result page.
          </p>
          <?php } ?>
        <div class="tbl">
            <div class="cell img-cont">
                <img class="vit-img" src="<?=$logoUrl;?>" alt="<?=$altTxt;?>" />
            </div>
            <div class="cell timr-cont">
                <div class="result-table tbl">
                    <div class="tbl-row">
                        <div class="cell title-box">
                            <label class="title">Name</label>
                        </div>
                        <div class="cell value-box">
                            <label class="value"><?=ucwords(strtolower($studentInfo['candidate_name']));?></label>
                        </div>
                    </div>
                    <div class="tbl-row">
                        <div class="cell title-box">
                            <label class="title">Application Number</label>
                        </div>
                        <div class="cell value-box">
                            <label class="value"><?=$studentInfo['app_num'];?></label>
                        </div>
                    </div>
                    <div class="tbl-row">
                        <div class="cell title-box">
                            <label class="title">DOB</label>
                        </div>
                        <div class="cell value-box">
                            <label class="value"><?=date('d/m/Y',strtotime($studentInfo['dob']));?></label>
                        </div>
                    </div>
                    <div class="tbl-row">
                        <div class="cell title-box">
                            <label class="title">Gender</label>
                        </div>
                        <div class="cell value-box">
                            <label class="value"><?=ucfirst(strtolower($studentInfo['gender']));?></label>
                        </div>
                    </div>
                    <div class="tbl-row">
                        <div class="cell title-box">
                            <label class="title">Rank</label>
                        </div>
                        <div class="cell value-box">
                            <label class="value"><strong class="rank"><?=$studentInfo['rank'];?></strong></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="card vitee-result-card no-border">
        <span class="txt-info">To learn more about <?=$examName;?> University</span>
        <a class="btn-primary blue link" ga-attr="<?=$gaAttrCollegeLink;?>" target="_blank" href="<?=$instUrl;?>">Click Here</a>
    </div>


<?php 
echo modules::run('nationalInstitute/InstituteDetailPage/getRecommendedListingWidget',$listing_id,$listing_type, 'alsoViewed',$courseIdsMapping);
$this->load->view('common/footer'); ?>  
<script type="text/javascript">
  var modifyUrl = "<?php echo $modifyUrl;?>";
  var cookieName = "<?php echo $cookieName;?>";
  var GA_currentPage = "<?php echo $examName.' RESULT PAGE';?>";
  var ga_user_level = "Logged In";
  var ga_commonCTA_name = "<?php echo '_'.$examName.'_RESULT_PAGE_DESK';?>";
	jQuery(document).ready(function()
	{
		initializePage();
	});
</script>
