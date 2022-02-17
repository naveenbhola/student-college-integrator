<?php ob_start('compress'); ?>
<?php
//Since, this is a single page application, Cookies were not getting saved when used pressed Back from any page.
//To avoid this, we are making this page as no-cache
header('Cache-Control: no-cache, no-store, must-revalidate'); // HTTP 1.1.
header('Pragma: no-cache'); // HTTP 1.0.
header('Expires: 0'); // Proxies.
?>
<?php $this->load->view('/mcommon5/header');

?>
<style>.report-msg{position: fixed !important;}</style>

<div id="wrapper" data-role="page" class="of-hide" style="min-height: 371px;padding-top: 82px;">
    <?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel');
		echo Modules::run('mcommon5/MobileSiteHamburger/getRightPanel','myrightpanel');
    ?>
    <?php   $this->load->view('naukriToolHeader'); ?>
    <div data-role="content" style="background:#e6e6dc !important;">
    	<?php 
        $this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
?>   
	    <!----subheader--->
	       <?php //$this->load->view('naukriToolSubHeader');?>
	    <!--end-subheader-->
		
		<?php //$this->load->view('naukriToolSubHeading'); ?>
	<?php
	    if (getTempUserData('confirmation_message')){?>
	    <section class="top-msg-row">
	    <div class="thnx-msg">
	    <i class="icon-tick"></i>
	    <p>
		  <?php echo getTempUserData('confirmation_message'); ?>
	    </p>
	    </div>
	    <div style="clear:both"></div>
      </section>
<?php } ?>
<?php
		deleteTempUserData('confirmation_message');
		deleteTempUserData('confirmation_message_ins_page');
		?>

		<div   data-enhance="false">
			<?php
                $this->load->view('naukriToolMainPage');
			?>
		</div>

		<div id="searchResults" style="display: none;"><?=$resultsList?></div>

		<!-- Loading Div -->
		<div id="loading" style="text-align:center;margin-top:10px;display:none;"><img id="loadingImage" border=0 alt="" ></div>
	
		<!-- Discliamer Div -->
		<div id="disclaimerDiv" style='font-size: 0.8em;color: #515151; padding:8px; border: 2px solid #DFDFDF; display: none;margin: 5px;'>
		    <div><strong>Disclaimer: </strong>Please note that the above displayed colleges and branches are only for reference purpose. Shiksha.com does not take any responsibility for the validity of the predictions and analysis shown above.</div>
		    <div style='margin-top:10px;'><?php echo $examSettingsArray['notice'];?></div>
		</div>
		
		<div data-enhance="false">
			<div style="display: none;">
			    <a id="JobFuncContainerOpen" href="#JobFuncContainer" data-transition="slide" data-rel="dialog" data-inline="true">&nbsp;</a>
			    <a id="CompaniesFuncContainerOpen" href="#CompaniesFuncContainer" data-transition="slide" data-rel="dialog" data-inline="true">&nbsp;</a>
			    <a id="LocationFuncContainerOpen" href="#LocationFuncContainer" data-transition="slide" data-rel="dialog" data-inline="true">&nbsp;</a>
			</div>
			<?php $this->load->view('/mcommon5/footerLinks'); ?>
		</div>
		
		<!-- For Email result message -->
		<a href="#popupBasic" data-position-to="window" data-inline="true" data-rel="popup" id="emailSuccessPopup" data-transition="slideup" ></a>
		<div data-role="popup" id="popupBasic" data-theme="d" style="background:#EFEFEF">
			<div style="padding: 25px; font-size:1.1em;color: #828282;" data-theme="d">
				<p>Results have been mailed <br/>to you successfully.<p>
			</div>
			<div style="padding-left: 25px; padding-bottom: 12px; font-size:1.0em; text-align: center;">
				<a style="width:70px;" data-theme="d" id="closeEmailSuccessPopup" href="javascript:void(0)" data-rel="back" data-role="button" data-icon="delete" data-iconpos="Close" ><strong>&nbsp;&nbsp;Close</strong></a>
			</div>
		</div>
		
        </div>
</div>
<div id="naukri-widget-right-col" data-enhance="false" data-role="page">
		</div>	
<div data-role="page" data-enhance="false" id="JobFuncContainer">
    <header>
	<div class="layer-header">
            <a style="width:10px;" class="back-box" id="JobFuncContainerClose" onclick="NaukriToolComponent.JobFuncContainerClose();" href="javascript:void(0);" data-rel="back"><i class="msprite back-icn"></i></a> 
            <p style="text-align:left; font-size:14px;">Other Job Functions</p>
        </div>
    </header>
    <section class="other-job-layer">
	<input type="text" id="jobFuncSearchBox" class="job-textfield" onkeyup="NaukriToolComponent.otherMenuSearchFilter(this.value, 'JobFuncUL', 'func');" placeholder="Type Job Function"/>
	<ul id="JobFuncUL"><li>Loading...</li></ul>
    </section>
</div>
<div data-role="page" data-enhance="false" id="CompaniesFuncContainer">
    <header>
	<div class="layer-header">
            <a style="width:10px;" class="back-box" id="CompaniesFuncContainerClose" onclick="NaukriToolComponent.CompaniesFuncContainerClose();" href="javascript:void(0);" data-rel="back"><i class="msprite back-icn"></i></a> 
            <p style="text-align:left; font-size:14px;">Other Companies</p>
        </div>
    </header>
    <section class="other-job-layer">
      	<input type="text" id="companySearchBox" class="job-textfield" onkeyup="NaukriToolComponent.otherMenuSearchFilter(this.value, 'CompaniesFuncUL', 'comp')" placeholder="Type Company"/>
	<div class="alpha-criteria">
	    <ul><li>
	    <?php
	    for($i=65; $i<=90; $i++)
	    {
	       echo '<a href="javascript:void(0);" class="alpha-check '.(($i==65)?'active':'').'" onclick="NaukriToolComponent.populateDataOnAlphaAjax(\''.chr($i).'\')" id="alpha-'.chr($i).'">'.chr($i).'</a>';
	    }
	    ?>
	    </li></ul>
	</div>
	<div id="CompaniesFuncUL">Loading...</div>
    </section>
</div>
<div data-role="page" data-enhance="false" id="LocationFuncContainer">
    <header>
	<div class="layer-header">
            <a style="width:10px;" class="back-box" id="LocationFuncContainerClose" onclick="NaukriToolComponent.LocationFuncContainerClose();" href="javascript:void(0);" data-rel="back"><i class="msprite back-icn"></i></a> 
            <p style="text-align:left; font-size:14px;">Other Locations</p>
        </div>
    </header>
    <section class="other-job-layer">
	<input type="text" id="locationSearchBox" class="job-textfield" onkeyup="NaukriToolComponent.otherMenuSearchFilter(this.value, 'LocationFuncUL', 'loc')" placeholder="Type Location"/>
	<ul id="LocationFuncUL"><li>Loading...</li></ul>
    </section>
</div>
<div id="popupBasicBack" data-enhance='false'></div>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script>
$('#piechart1').html('<img id = "loaderImg" src="//<?php echo IMGURL; ?>/public/mobile5/images/ajax-loader.gif" style="border-right:0 none;">');
currentPageName = 'CAREER COMPASS';
var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
var graphData = '<?php echo $graphData;?>';
var seo_data = '<?php echo $seo_data;?>';
var obj = jQuery.parseJSON(graphData);
var naukriToolObj = '';
$(document).ready(function(){
    $.ajax({
        url: '//www.google.com/jsapi',
        dataType: 'script',
        cache: true,
        success: function() {
            google.load('visualization', '1', {
                'packages': ['corechart'],
                'callback': initiliazeData
            });
        }
    });
});
function initiliazeData(){
    naukriToolObj  = new NaukriTool(obj);
    NaukriToolComponent.trackingPageKeyId = '<?php echo $trackingPageKeyId;?>';
    NaukriToolComponent.shortlistTrackingPageKeyId = '<?php echo $shortlistCollegeTupleTrackingPageKeyId;?>';
 	naukriToolObj.drawChart();
    setTimeout(function(){
        $('#section1').hide();
        $('#section2').hide();
    },100);
    NaukriToolComponent.getCollegeList(NaukriToolComponent.trackingPageKeyId, NaukriToolComponent.shortlistTrackingPageKeyId);
}
</script>
<?php
global $shiksha_site_current_url;
global $shiksha_site_current_refferal;
?>
<?php $this->load->view('/mcommon5/footer');?>
<?php ob_end_flush(); ?>