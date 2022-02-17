<?php
 $headerComponents = array(
   'searchPage'=>'true',
   'shikshaHomepage'=>'true',
   'jsMobile'	=> array(),
   );
$this->load->view('/mcommon5/headerV2',$headerComponents); 
echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_HOMEPAGE',1);
$this->load->view('mcommon5/homepageWidgets/homePageFirstFoldCSS');
?>
<script>var searchPageName = 'shikshaHomepage';</script>
<div id="wrapper" data-role="page" class="of-hide">
<?php 
echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
?>
	<header id="page-header" data-role="header" class="header" style="padding:0px;">
	<?php echo Modules::run('mcommon5/MobileSiteHamburger/getMainHeader',$displayHamburger=true,'','',true);?>
    </header>
    <div data-role="content">
    	<?php 
        	$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    	?>
    	<div data-enhance="false">
    		<div style="display: none;">
    			<form action="/mcommon5/MobileSiteHome/browseInstitute" id="homepageForm" accept-charset="utf-8" method="post" enctype="multipart/form-data" novalidate="novalidate" >
                	<input name="categoryIdSelected" value="" type="hidden"/>
                    <input name="subcategoryIdSelected" value="" type="hidden"/>
                    <input name="locationIdSelected" value="" type="hidden"/>
                    <input name="locationTypeSelected" value="city" type="hidden"/>
                    <input name="countryIdSelected" value="2" type="hidden"/>
                    <input name="regionIdSelected" value="0" type="hidden"/>
                    <input name="isStudyAbroad" value="0" type="hidden"/>
	     		</form>
	     	</div>
	    </div>
	    <div class="MHP" id="homepageMiddlePanel">
		    <div class="bdl">
			    <div data-enhance="false">
			    	<!-- Search Section Start -->
			    	<?php $this->load->view('homepageWidgets/homepageSearchWidget'); ?>
			    	<!-- Search Section End -->
			    <?php 
			    // added by akhter
				$homepagemiddlepanelcache = "HomePageRedesignCache/mobileMiddlepanel.html";
				if(file_exists($homepagemiddlepanelcache) && (time() - filemtime($homepagemiddlepanelcache))<=7200 && !$resetPage){
    				echo file_get_contents($homepagemiddlepanelcache);
			    }else{
			    	ob_start();
			    	$this->load->view('homepageWidgets/allCategoriesWidget');
			    	/*second fold css*/
			    	$this->load->view('mcommon5/homepageWidgets/homePageSecondFoldCSS');
			    	/*end second fold css*/
			    	$this->load->view('homepageWidgets/askNowWidget');
			    	echo Modules::run('shiksha/getRecentArticles',3,'homepageMobile');
			    	$this->load->view('homepageWidgets/featuredCollegesWidget');

				    $pageContent = ob_get_contents();
				    ob_end_clean();
				    echo $pageContent;
				    $fp=fopen($homepagemiddlepanelcache,'w+');
				    flock( $fp, LOCK_EX ); // exclusive lock
				    fputs($fp,$pageContent);
				    flock( $fp, LOCK_UN ); // release the lock
				    fclose($fp);
				}?>
			    </div>
			    <!-- Homepage Widgets Section End -->
			</div>
		</div>
		<!-- Footer Links Section Start -->
		<?php $this->load->view('/mcommon5/footerLinksV2',array('jsFooter'=>array('mExamPage'))); ?>
		<!-- Footer Links Section End -->
		<?php $this->load->view('includeHomePageJSCode');?>
    </div>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<?php 
deleteTempUserData('onlineForm_StartApplication');
?>
<?php $data['pageName'] = $pageName;$this->load->view('/mcommon5/footerV2',$data); ?>