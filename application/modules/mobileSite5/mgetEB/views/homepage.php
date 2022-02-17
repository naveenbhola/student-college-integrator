<?php ob_start('compress'); ?>
<?php
$headerComponent = array(
        'm_meta_title' => 'Search Institutes and Courses on Shiksha'
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

function moneyFormatIndiaMobile($num){
    $explrestunits = "" ;
    if(strlen($num)>3){
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++){
            // creates each of the 2's group and adds a comma to the end
            if($i==0)
            {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            }else{
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.
}

$brochureNumber = moneyFormatIndiaMobile( (round(time()/60) - 20966281) );
?>

<div id="wrapper" data-role="page" > 

	
	<!--<header id="page-header" class="clearfix">
		<div id="logo-box"><a href="<?=SHIKSHA_HOME?>" class="logo"></a></div>
	</header>-->
	<!-- Show the Search page Header -->    
	<?php echo Modules::run('mcommon5/MobileSiteHamburgerV2/getWrapperHtmlForHamburger','mypanel','false');?>
	
	<header id="page-header" class="clearfix" data-role="header">
	    <div class="head-group" data-enhance="false">
		<a class="head-icon" href="#mypanel"><i class="icon-menu"></i></a>
		<h1>
			<div class="left-align" style="margin-right:50px;">
			    Request Brochures
			</div>
			<span style="cursor: pointer;" onclick="window.location='<?=SHIKSHA_HOME?>';" class="head-icon-r"><i class="icon-home"></i></span>		    
		</h1>
	    </div>
	</header>    
	<!-- End the Search for Category page -->
	
	<div data-role="content">
		<?php 
        	$this->load->view('mcommon5/dfpBannerView',array('bannerPosition' => 'leaderboard'));
    	?>
		<div data-enhance="false">
		
		<?php
		if (getTempUserData('confirmation_message')){?>
		<section class="top-msg-row" style="margin: 0.2em 0;">
			<div class="thnx-msg">
			    <i class="icon-tick"></i>
				<p>
				<?php
					if(strpos(getTempUserData('confirmation_message'),'You will be receiving E-brochure')!==false){
						echo "You will be receiving brochure in your mailbox shortly.";	
					}				
				?>
				</p>
			</div>
		</section>
		<?php } ?>
		<?php
		   deleteTempUserData('confirmation_message');
		   deleteTempUserData('confirmation_message_ins_page');
		?>


		<form id="searchForm" method="get" accept-charset="utf-8" autocomplete="off" action="/getEB/index" onSubmit="if(!validateSearch()){return false;}  delaySubmitSearch() ;">
			<div class="home-content-wrap" style="border-radius: 0px; margin-top: 10px;" >
				<section class="content-child clearfix" style="height:175px">
				    <h1>
					<img src="/public/mobile5/images/Brochure.png" border=0 style="display: inline-block; float: left; position: relative;"/>
					<p style="line-height: 110%;">Get brochures for institutes</p>
					<i class="info" style="font-size: 0.9em;margin-top:5px; "><?=$brochureNumber?> brochures downloaded. Get one now.</i>
				    </h1>
				
				
				    <div class="home-search">
					<aside>
						<div class="full-width search-base">
					    <input id="search" class="searchInstitute" type="text" placeholder="Enter Institute Name" data-type="search" minlength="1" value="<?=$posted_keyword?>" name="keyword" onkeypress="$('#searchError').hide();">
					    </div>
					</aside>
				    </div>
				    
					<div class="errorMsg" style="display: none;" id="searchError">
								Please Enter Keyword			
					</div>
					<input type="button" id="home_search_button" class="search-btn" value="Submit" name="search" onClick="trackEventByGAMobile('HTML5_GET_EB_Homepage_Submit_Button'); if(!validateSearch()){return false;} delaySubmit();  ">		
					<input type="button" id="home_search_button_disabled" class="search-btn" value="Submitting..." name="search" style=" display:none;background: #CCC;text-shadow: none;color:#666;" disabled="disabled" />
					
				</section>
			    
			    
				<script>
				    function delaySubmit(){
					    setTimeout(function(){$('#home_search_button').hide();$('#home_search_button_disabled').show(); $('#searchForm').submit();},100);
				    }
				    function delaySubmitSearch(){
					setTimeout(function(){$('#home_search_button').hide();$('#home_search_button_disabled').show(); },100);
				}                
				</script>
				
			</div>
		
		</form>	

			<?php if(isset($_COOKIE['getEBCourseId'])){	?>
			<div id="alsoOnShiksha">
			</div>
			<script>
			$('#alsoOnShiksha').load('/mgetEB/Msearch/alsoOnShiksha/<?php echo $_COOKIE['getEBCourseId'];?>');
			</script>
			<?php
			}
			?>
			
		
		</div>
		
		<?php $this->load->view('mcommon5/footerLinks');?>
		
	</div>
	



    
</div>
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
if($('#search').val().trim()!=''){
return true;
}
else{
$('#searchError').show();
$('#search').val('');
return false;
}
}

</script>
<?php ob_end_flush(); ?>
