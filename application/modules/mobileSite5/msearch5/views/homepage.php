<?php ob_start('compress'); ?>
<?php
$headerComponent = array(
        'm_meta_title' => 'Search Institutes and Courses on Shiksha',
	'searchPage'=>'true',
	'jsMobile'	=> array('searchTrackingMobile')
);
$this->load->view('mcommon5/header',$headerComponent);

$hidden = array(
	'start'=>0,
	'institute_rows'=>-1,
	'content_rows'=>0,
	'country_id'=>'',
	'zone_id'=>'',
	'locality_id'=>'',
	'search_type'=>'institute',
	'search_data_type'=>'institute',
	'sort_type'=>'',
	'utm_campaign'=>'',
	'utm_medium'=>'',
	'utm_source'=>'',
	'from_page'=>'mobilesearchhome',
	'show_sponsored_results'=>1	
);

?>
<script>
	var searchPageName = 'shikshaHomepage';
	<?php if(TRACK_AUTOSUGGESTOR_RESULTS) { ?>
		var TRACK_AUTOSUGGESTOR_RESULTS_JS = true;
		var autoSuggestorPageName = '<?php echo $autoSuggestorPageName;?>';
	<?php } else { ?>
		var TRACK_AUTOSUGGESTOR_RESULTS_JS = false;
		var autoSuggestorPageName = '<?php echo $autoSuggestorPageName;?>';
	<?php } ?>
</script>
<div id="wrapper" data-role="page" class="of-hide" data-enhance="false"> 

	<form id="searchForm" method="get" accept-charset="utf-8" autocomplete="off" action="/search/index" onSubmit="if(!validateSearch()){return false;}  delaySubmitSearch(); return false;">
	<header id="page-header" class="clearfix">
		<div id="logo-box"><a href="<?=SHIKSHA_HOME?>" class="logo"></a></div>
	</header>
	
	<div class="home-content-wrap">
    	<section class="content-child clearfix" style="height:180px">
            <h1>
                <span class="black-icon"><i class="icon-search2"></i></span>
                <p>Enter Institute or Courses</p>
            </h1>
        
        
            <div class="home-search" id="searchContainerDiv">
                <aside>
          <div class="hidden">
                 <input type="hidden" name="from_page" value="mobilesearchhome" />
                    </div>
		    <div class="full-width search-base">
		    <input class="searchInstitute" style="width: 87% !important" id="keywordSuggest" type="search" name="keyword"  minlength="1" placeholder="Search Institutes or Courses " />    
		    
		    </div>
		</aside>
		<ul id="suggestions_container">
			 
			</ul>
		<i class="cross-icon" style="right: 12px;display:none;" onClick="jQuery('#keywordSuggest').val('');jQuery('#suggestions_container').hide();jQuery('.cross-icon').hide();">&times;</i>
            </div>
	      <input type="hidden" name="suggestedInstitutes" id="suggestedInstitutes" value="" />
            
		<div class="errorMsg" style="display: none;" id="searchError">
					Please Enter Keyword to Search			
		</div>
		<input type="button" style=" padding: 0;" id="home_search_button" class="search-btn" value="Search" name="search" onClick="if(!validateSearch()){return false;} delaySubmit(); trackUserAutoSuggestion('bc');return false;">		
		<input type="button" id="home_search_button_disabled" class="search-btn" value="Searching..." name="search" style=" display:none;background: #CCC;text-shadow: none;color:#666; padding: 0;" disabled="disabled" />
		
	    </section>
	    <script>
                function delaySubmit(){
                        setTimeout(function(){$('#home_search_button').hide();$('#home_search_button_disabled').show();},100);
                }
                function delaySubmitSearch(){
                    setTimeout(function(){$('#home_search_button').hide();$('#home_search_button_disabled').show(); },100);
            }                
        </script>
	</div>
	</form>
	

        <?php $this->load->view('mcommon5/footerLinks');?>


    
</div>
<script>
$(document).click(function (e)
{
    var container = $("#searchContainerDiv");

    if (!container.is(e.target) // if the target of the click isn't the container...
        && container.has(e.target).length === 0) // ... nor a descendant of the container
    {
        handleClickForAutoSuggestor();
    }
});

</script>
<?php
$footerComponent = array(
			 'doNotLoadImageLazyLoad'=>'true'
		);
$this->load->view('mcommon5/footer',$footerComponent);
?>

<script>

if ((/iphone|ipod|ipad.*os 5/gi).test(navigator.appVersion)) {
        window.onpageshow = function(evt) {
        if (evt.persisted) {
                document.body.style.display = "none";
                location.reload();
        }
        };
}

function validateSearch(){	
if($('#keywordSuggest').val().trim()!=''){
return true;
}
else{
$('#searchError').show();
$('#keywordSuggest').val('');
return false;
}
}

</script>
<?php ob_end_flush(); ?>
