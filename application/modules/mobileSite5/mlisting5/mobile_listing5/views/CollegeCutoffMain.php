
<?php
    $headerComponents = array(
      'm_meta_title'=>$seoData['seoTitle'],
      'm_meta_description'=>$seoData['seoDesc'],
      'm_meta_keywords'=>$metaKeywords,
      'canonicalURL' => $canonicalURL,
      'jsMobile' => array(),
      'mobilePageName' => 'CollegeCutoffPageMobile',
      'noJqueryMobile' => 1
    );
$this->load->view('/mcommon5/headerV2',$headerComponents);
?>
<style type="text/css">
	<?php $this->load->view('/mcollegepredictor5/V2/collegePredictorCss',$headerComponents); ?>
</style>
<div id="wrapper" data-role="page" class="of-hide" style="min-height: 413px;">

    <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
	  echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    <header id="page-header" class="header ui-header ui-bar-inherit slidedown" data-role="banner" data-tap-toggle="false" style="height: auto;">
        <?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true);?>
    </header>
	<div data-role="content">
    <?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    ?>
		<div data-enhance="false">
		    <div class="clg-predctr cutOff-Dv">
            	<div class="clg-bg">
            	    <div class="clg-txBx">
            	        <p class="DU-hdng"><?php echo $instituteName; ?> Cut-off</p>                    
            	    </div>
            	</div>     
            	<div class="cutOff-txt">
               		<table>
                	    <tr>
                	        <th><h2><?php echo $instituteName; ?> Cut-off <?php echo date('Y');?></h2></th>
                	    </tr>
                	    <tr>
                        <td>
                           <p><?php echo $shortText; ?>...<a class="du-rdMre">Read More</a></p>
                           <p class="rdMRe-txt" style="display:none;">
                            <?php foreach ($previewText as $row) { ?>
                              <?php echo $row; ?></br>
                            <?php
                            } ?> 
                            </p>
                           </td>
                       </tr>
                   </table>
            	</div>
            	<div class="du-filtrs">
            	    <div class="fltr-cntnrDiv">
            	        <div class="flt-catgry flt-btn">
            	           <a href="javascript:void(0)">
            	                <label>Category:</label>
            	                <span class="cat-Fld"><?php echo ucfirst($categoryName); ?></span>
            	                <i class="cat-arrw"></i>
            	            </a>
            	        </div>
            	        <div class="fltOpt-catgry flt-btn">
            	            <a href="javascript:void(0)">FILTER <i class="fltr-arrw"></i></a>
            	        </div>
            	    </div>
            	</div>
            	
            	<?php $this->load->view('CollegeCutoffTupleListMobile');?>
          	</div>
		</div>
		<!-- Loading Div -->
		<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>	

		<div data-enhance="false">
			<?php $this->load->view('/mcommon5/footerLinksV2',array( 'jsFooter'=> array('collegePredictorNM'),'cssFooter'=>array('websiteTour', 'mcommon') )); ?>
		</div>
	</div>		
</div>

<div id="popupBasicBack" data-enhance='false'></div> 
<?php $this->load->view('/mcommon5/footerV2'); ?>

<?php $this->load->view('collegeCutoff_filter_dialog'); ?>

<script type="text/javascript">
	var start = 20;
	var GA_currentPage = '<?php echo 'cutoff_page_mobile_'.$examName;?>';
	var totalResults = '<?php echo $totalCount; ?>';
  var viewedResponseAction = 'MOB_Institute_Viewed';
  var viewedResponseTrackingKey = 2075;
  var viewedResponseCourseId = '<?=$flagshipCourseId;?>';
  
</script>
<?php ob_end_flush(); ?>
