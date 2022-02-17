<?php 
$headerComponents = array('js'=>array('multipleapply','category','user'),
			'jsFooter' =>array('common','ana_common'),
                        'css'=>array('modal-message','raised_all','mainStyle','online-styles'),
			'product'=>'home',
                        'taburl' =>  site_url(),
			'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),'tabName'	=>	'Event Calendar'
                         );

?>
<?php
$this->load->view('common/header', $headerComponents);
$categoryDetails = json_decode($categoryDetails);
$categoryName =  $categoryDetails['0']->name;
$categoryUrl =  $categoryDetails['0']->urlName;
?>
<div class="mb10 mlr10" style="width:700px; float:left">
   <div class ="wd100"> 
   <div>
      <div class="pf10 bdr" style="background:#f0f0f0">
	<div class="orangeColor Fnt18  " > This institute has been deleted</div>
	<div style="color: rgb(0, 0, 0); font-size: 15px;">Click here to continue browsing other
	<a href= "<?php echo constant('SHIKSHA_'. strtoupper($categoryUrl) .'_HOME'); ?>"> <?php echo $categoryName?> </a>institutes on Shiksha
	</div>
      </div>
    </div>
    </div>
<div class="clearFix"></div>
</div>
<div class="wd100">
    <div class="float_R " style="width:262px">
      <?php
      $bannerProperties = array('pageId'=>'LISTINGS', 'pageZone'=>'FOOTER');
      $this->load->view('common/banner',$bannerProperties);
      ?>
    </div>
    <div class="clear_B">&nbsp;</div>
</div>

<div class="clearFix"></div>
<?php $this->load->view('common/footerNew');?>
