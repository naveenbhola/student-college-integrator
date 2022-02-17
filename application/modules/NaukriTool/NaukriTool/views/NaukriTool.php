<?php
$tempJsArray = array('myShiksha','user','naukriTool');
$headerComponents = array(
                'css'   =>      array('naukri-tool'),
                'js' =>         array('common','facebook','ajax-api','processForm','json2'),
                'jsFooter'=>    $tempJsArray,
                'title' =>      $title,
                'metaDescription' => 'Find out the best MBA colleges to achieve your career goals. See colleges based on alumni data, only on Shiksha.com',
                'canonicalURL' => $canonicalURL,
                'product'       =>'naukritool',
                'showBottomMargin' => false,
                'displayname'=>(isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),

);

$headerComponents['bannerPropertiesGutter'] = array();
$headerComponents['shikshaCriteria'] = array();

$this->load->view('common/header', $headerComponents);
?>
<div class="notify-bubble report-msg" id="noti" style="top: 130px;opacity: 1; display: none;">
   <div class="msg-toast">
   <a class="cls" href="javascript:void(0);" onclick="closeToast(this);">×</a>
   <p id="toastMsg"></p>
   </div>
</div>
<?php $this->load->view('messageBoard/headerPanelForAnA',array('collegePredictor' => true));?>
<style>
#JobFuncContainer {left: 40%; top: 12%;}
#CompaniesFuncContainer {left: 20%; top: 12%;}
#LocationFuncContainer {left: 40%; top: 12%;}
</style>
<script type="text/javascript">
	currentPageName = 'CAREER COMPASS';
	var COOKIEDOMAIN = '<?php echo COOKIEDOMAIN; ?>';
	var chart_data = "";
	var chartJobFunc1, dataJobFunc1, chartJobFunc2, dataJobFunc2;
	var colors = ["#d6d6d6",'#d6d6d6',"#d6d6d6",'#d6d6d6',"#d6d6d6",'#d6d6d6'];
	
</script>

<div>
	<div class="wrapperFxd">
        <div class="naukri-wrapper">
            <div class="naukri-wrap-detail clear-width">
                <div class="naukri-head naukri-tool-widget" style="margin-top:0"><h1 class="crComHead">MBA Career Compass</h1><span class="flRt">Powered by: <i class="common-sprite naukri-sml-logo"></i></span>
                	<div class="mt8">
                    	<p class="dream-job-title">Have a dream job? Find colleges to help you get there!</p>
                    	<span class="flLt job-fun-info">Select a job function or a company. And we will tell you which colleges have their former students working there.</span>
                        <ul class="flRt social-media-list" style="width:27% !important;">
							<?php
    						$url_parts = parse_url("https://".$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"]);
 
    						$constructed_url = $url_parts['scheme'] . '://' . $url_parts['host'] . (isset($url_parts['path'])?$url_parts['path']:'');
    						$constructed_url = preg_replace(array("/https:\/\//i") , "https://", $constructed_url);
    						$urlArticle = urlencode($constructed_url);
							?>
                            <li>
                            	<div class="flLt">
                                	<iframe src="https://www.facebook.com/plugins/like.php?href=<?php echo $urlArticle; ?>&amp;layout=button_count&amp;show_faces=false&amp;width=450&amp;action=like&amp;font=tahoma&amp;stream=true&amp;header=true&amp;appId=<?php echo FACEBOOK_API_ID; ?>" colorscheme=light" scrolling="no" frameborder="0" allowTransparency="true" style="border:none; overflow:hidden; width:79px; height:25px"></iframe>
                                </div>
                            </li>
                            <li>
								<div class="flLt" style="width: 75px ! important;">
									<a href="https://twitter.com/share" class="twitter-share-button">Tweet</a>
										<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'https':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
								</div>
							</li>
                        </ul>
                        <div class="clearFix"></div>
                    </div>
            	</div>
                <div class="naukri-tool-widget">
                	<ul>
                    	<li style="overflow: hidden;position: relative;">
                        	<div class="job-alumini-title" id="jobFuncPieChartTitle">Job functions of Alumni</div>
							<div id="piechart1" ></div>
							<p id="innerText1" style="position: absolute; color: rgb(102, 102, 102); text-align: center; top: 133px; font-size: 12px; left: 120px;">Select<br> Job Function</p>
   
                            <div class="search-all-field viewAllItems" id="allJobFunctions"><a href="javascript:void(0);">View all Job Functions &raquo;</a></div>
                        </li>
                        <li style="overflow: hidden;position: relative;">
							<div class="job-alumini-title" id="companyPieChartTitle">Companies where alumni are working</div>
                        	<div id="piechart2" ></div>
							<p id="innerText2" style="position: absolute; color: rgb(102, 102, 102); text-align: center; top: 133px; font-size: 12px; left: 128px;">Select<br> Company</p>
                                <div class="search-all-field viewAllItems" id="allCompanies"><a href="javascript:void(0);">View all Companies &raquo;</a></div>
                        </li>
                        <li class="naukri-lastItem" style="overflow: hidden;position: relative;">
                        	<div class="job-alumini-title" id="locationPieChartTitle">College locations where alumni studied</div>
							<div id="piechart3" ></div>
							<p id="innerText3" style="position: absolute; color: rgb(102, 102, 102); text-align: center; top: 128px; font-size: 12px; left: 130px;">Select<br> College<br> Location</p>
							<div class="search-all-field viewAllItems" id="allLocations"><a href="javascript:void(0);">View all Locations &raquo;</a></div>
                        </li>
                    </ul>
                </div>
                <div class="selection-tool selectedCriteriaTool" style="display:none;">
                    <div class="flLt" style="width:88%;">
                    	<label class="flLt" style="padding-top:4px;">Your Selection:</label>
                        <div id="selectedTools" class="flLt"></div>
                    </div>
		    		<a href="javascript:void(0);" id="clrAllCriteria" class="modify-search-btn flRt">Clear All</a>
                    <div class="clearFix"></div>
                </div>
                <div class="naukri-tool-widget clear-width" style="padding:0;">
		  			<span id="searchResultHeading"></span>
                	<b class="college-count-title clear-width" id="searchResultHeading1">We have found <span id='totalCount' class="totalCount">0</span> colleges with Alumni Information <strong></strong></b>
                    <div class="naukri-widget-sec">	
                    	<div class="naukri-widget-left-col" style="width: 280px; height: 750px;">
			    			<div class="naukri-animate-left-height" style="width: 280px; height:708px;">
				  				<div id="widget-institute-list">
				      				<ul class="widget-institute-list" id="widget-list" style="cursor: pointer;">
										<?php echo modules::run('NaukriTool/NaukriToolController/getCollegeList');?>
				      				</ul>
				  				</div>
			    			</div>
			    			<div id="widget-list-pagination" style="width:280px;">
				      			<ul class="widget-institute-list">
					  				<li class="up-dwn-sort">
						    			<a class="disabled" href="javascript:void(0);" onclick="getInstituteList('prev',this)" style="color:#9a9a9a !important">Previous 12 <i class="caret-up" id="prev"></i></a>
						    			<a href="javascript:void(0);" onclick="getInstituteList('next',this)" style="border-right:0 none;color:#9a9a9a !important">Next 12 <i class="caret-dwn-a" id="next"></i></a>
				          			</li>
				      			</ul>
			    			</div>  
                        </div>
						<div class="naukri-widget-right-col" id="naukri-widget-right-col" style="min-height:787px;">
			  				<?php //$this->load->view("naukriToolInstituteDetails");?>
						</div>
                	</div>
                </div>
                <?php //$this->load->view("registrationWidget");?>
                <?php echo $this->load->view("InstituteDetailsWidget"); ?>
          	</div>
	    	<div class="other-company-layer" id="JobFuncContainer" style="width:320px; display: none; z-index: 999;">
            	<div class="other-detail-head clear-width">
                	<a href="javascript:void(0);" id="JobFuncContainerClose" class="close-icon-2">&times;</a>
                    <p class="search-title flLt">Select a Business Function</p>
                    <div class="clearFix"></div>
                    <input type="text" placeholder="Type Business Function" onkeyup="jobFunctionsSearchFilter(this.value);" class="layer-textfield" style="width:275px"/>
                </div>
                <div class="other-company-detail clear-width" style="overflow-y:scroll; max-height: 400px; min-height: 250px;">
                    <div class="company-list">
                    	<ul id="JobFuncUL">Loading...</ul>
                    </div>
                </div>
            </div>
	    	<div class="other-company-layer" id="CompaniesFuncContainer" style="display: none; z-index: 999;">
           		<div class="other-detail-head clear-width">
                	<a href="javascript:void(0);" id="CompaniesFuncContainerClose" class="close-icon-2">&times;</a>
                    <p class="search-title flLt">Select a Company</p>
                    <div class="clearFix"></div>
                </div>
                <div class="other-company-detail clear-width">
                    <div class="clearFix"></div>
                    <div class="alpha-criteria">
	                    <?php
							$alphabet = 'A';
							for($i=65; $i<=90; $i++){
							   echo '<a href="javascript:void(0);" class="alpha-check '.(($i==65)?'active':'').'">'.chr($i).'</a>';
							}
						?>
                    </div>
                    <div class="company-list" style="">
		      			<div style="overflow-x:scroll; min-height: 400px; ">
                    		<div id="CompaniesFuncUL" style="">Loading...</div>
                      	</div>
		    		</div>
                </div>
            </div>
	    	<div class="other-company-layer" id="LocationFuncContainer" style="width:320px; display: none; z-index: 999;">
            	<div class="other-detail-head clear-width">
                	<a href="javascript:void(0);" id="LocationFuncContainerClose" class="close-icon-2">&times;</a>
                    <p class="search-title flLt">Select a Location</p>
                    <div class="clearFix"></div>
                    <input type="text" placeholder="Type Location" onkeyup="cityFunctionsSearchFilter(this.value);" class="layer-textfield" style="width:275px"/>
                </div>
                <div class="other-company-detail clear-width" style="overflow-y:scroll; max-height: 400px; min-height: 400px;">
                    <div class="company-list">
                    	<ul id="LocationFuncUL">Loading...</ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="clearFix"></div>
</div>
<div id="pageOverlayBg" style="width:100%; height: 100%; position: fixed; background-color: #000; opacity: 0.4; z-index: 998; top: 0; left: 0; display: none;">
</div>
<style>.disabled {pointer-events: none;cursor: default;}</style>  <!--// this is used only for ask btn-->
<script>
	var jobFuncSelection = '';
	var companiesSelection = '';
	var citiesSelection = '';
	var companiesSelectedRow = '';
	var jobFuncSelectionRow = '';
	var selectedCriteria = new Array();
	var selectedCriteriaIndex = [];
	totalPages = Math.ceil(totalInstitutes / perPage);
	var pageNo = 0;
	var total_pages = totalPages;
		
	var count_salary_slots = 0;
	var max_value = 0;
	var chartsalary;
	var salaryoptions = {};
	var slantedTextFlag = false;	
</script>
<?php $this->load->view('myShortlist/shortlistOnHoverMsg');?>
<?php $this->load->view('common/footer'); ?>
<!--myshortlist notification layer scroller-->
<script>
	var graphData = '<?php echo $graphData;?>';
	var obj = jQuery.parseJSON(graphData);
	var mainJobFuncArr = new Array();
	var mainCompaniesArr = new Array();
	var mainLocationArr = new Array();
	var viewAllLayerFlag = false;

	$j(document).ready(function(){
		$j('#naukri-widget-right-col').html('<div style="width: 100%;text-align: center;margin-top: 100px;"><img id = "loaderImg" src="/public/images/smartajaxloader.gif" ></div>');
		$j('#piechart1').html('<img id = "loaderImg" src="/public/images/smartajaxloader.gif" style="margin:85px 0 82px 108px; border-right:0 none;">');
		$j('#innerText1').html('');
		$j('#piechart2').html('<img id = "loaderImg" src="/public/images/smartajaxloader.gif" style="margin:85px 0 82px 108px; border-right:0 none;">');
		$j('#innerText2').html('');
		$j('#piechart3').html('<img id = "loaderImg" src="/public/images/smartajaxloader.gif" style="margin:85px 0 82px 108px; border-right:0 none;">');
		$j('#innerText3').html('');

		$j.ajax({
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
	  	$j('#totalCount').text(totalInstitutes);
	  	if (totalInstitutes < 12) {
	    	$j('#next').removeClass('caret-dwn-a').addClass('caret-dwn').parent('a').addClass('disabled');
	  	}
	  	// myshortlist notification layer scroller
	  	setScrollbarForMsNotificationLayer();
	});
</script>
