
<section class="clearfix content-wrap">
  				<article class="clearfix naukri-inner-wrap">
                    <h1 class="rank-predictor-title"><?php echo $rankConfigData[$examName]['examName'];?> Rank Predictor</h1>
    			</article>
      </section>
<div id="rankpredictorResult" style="display:none">   
   <section class="clearfix content-section" >
      	<div class="predictor-rank-box">
                	<div class="flLt predictor-title-box font-14">
                    	<p>Your <?php echo $rankConfigData[$examName]['inputField']['score']['label'];?>: <strong id="ExamScoreDisplay"></strong> </p>
			<?php if (array_key_exists("marks",$rankConfigData[$examName]['inputField'])){?>
			   <p>Your <?php echo $rankConfigData[$examName]['inputField']['marks']['label'];?>: <strong id="BoardScoreDisplay"></strong> </p>
			<?php } ?>
                        <a href="javascript:void(0)" class="reset-btn2" onclick="resetRankSearch();">Reset</a>
                    </div>
			
		     <?php if( $rankConfigData[$examName]['isShowAakashLogo'] == 'YES'){?>
                    <div class="flRt">
                   	  	<p class="powered-title">Powered by:</p>
                   	  	<a  style="text-decoration: none;" href="http://www.aakash.ac.in" target="_blank">
                   		<img src="/public/mobile5/images/akash-logo.png" width="66" height="39" alt="logo"></a> 
                    </div>
		    <?php } ?>
                    <div class="clearfix"></div>
                </div>
      </section>
      
      <section class="clearfix content-section">
      	<div class="predictor-rank-box">
	 <?php if($minRange == 0 || $maxRange == 0) {?>
	 
	       <p class="predicted-rnk-info" id="rankResult" style="margin-bottom:5px">Sorry, unable to predict your rank now.<br> Please try again later.</p>
	 
	 <?php }else{ ?>
	 
        	<p class="predicted-rnk-info" id="rankResult" style="margin-bottom:5px">Your Predicted Rank: <strong><?=$minRange?> - <?=$maxRange?></strong></p>
	 <?php } ?>
	  
            <p class="rnk-disclaimer-txt" id="disclaimer-onresult">Disclaimer: <?php echo substr($rankConfigData[$examName]['disclaimer'],0,162).'..';?><a href="javascript:void(0)" onclick="openDisclaimer();" style="text-decoration: none;"> View More</a>
	    <span style="display: none;" id="disc_more">Disclaimer: <?php echo $rankConfigData[$examName]['disclaimer'];?></span>
	    </p>
        </div>
      <?php $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); ?>
      </section>

     
      <?php if($rankConfigData[$examName]['isTopCollegesWidget'] == 'YES'){?> 
      <section class="clearfix" style="padding-top:10px;">
      	<div class="admission-rank-head" style="padding: 0 !important" id="collegeLists">
        	<h2 style="padding: 10px;">Colleges that you can get admission based on your predicted rank:</h2>
		<div id="collegePredictionResults">
		     <div style="margin: 10px; text-align: center">
			<img src="/public/mobile5/images/ajax-loader.gif" border=0 />
		     </div>
	        </div>
        </div>
      </section>
      <?php } ?>
      
      <?php if($rankConfigData[$examName]['isStaticCollgePredictorWidget'] == 'YES'){?>
      <section class="clearfix content-section">
         	<div class="predictor-rank-widegt" onclick="window.location='<?php echo $rankConfigData[$examName]['staticWidgetUrl'];?>';trackEventByGAMobile('MOB_ RANK_PREDICTOR_COLLEGE_PREDICTOR_CLICK');">
                    	<div class="flLt"><i class="msprite predictor-rank-icon"></i></div>
                        <div class="predictor-widget-info">
                        	<p><?php echo $rankConfigData[$examName]['staticWidgetHeading'];?></p>
                            <a href="#" class="font-14" style="text-decoration:none;"><?php echo $rankConfigData[$examName]['staticWidgetLinkText'];?> </a>
                        </div>
                        <div class="clearFix"></div>
                    </div>
         </section>
      <?php } ?>
</div>

<div id="rankPredictorForm" >
<section class="clearfix content-section" >
      	<div class="predictor-rank-box">
                	<div class="flLt predictor-title-box">
                    	<h2><?php echo $rankConfigData[$examName]['heading'] ?></h2>
                    </div>
		     
		     <?php if($rankConfigData[$examName]['isShowAakashLogo'] == 'YES') {?>
                    <div class="flRt">
                   	  	<p class="powered-title">Powered by:</p>
                   		<a  style="text-decoration: none;" href="http://www.aakash.ac.in" target="_blank"><img src="/public/mobile5/images/akash-logo.png" width="66" height="39" alt="logo"></a> 
                    </div>
		    <?php } ?>
                    <div class="clearfix"></div>
                </div>
      </section>
      
      <section class="clearfix content-section" >
      	<div class="predictor-rank-box clearfix">
        	<p><?php echo $rankConfigData[$examName]['formHeading'] ?></p>
            <ul class="predictor-rank-form">
	       <?php if (array_key_exists("score",$rankConfigData[$examName]['inputField'])){?>
            	<li>
                	<label><?php echo $rankConfigData[$examName]['inputField']['score']['label'];?></label>
                    <input type="text" class="rnk-predictor-textfield" id="RP_ExamScore" minlength="<?=$rankConfigData[$examName]['inputField']['score']['maxLength'];?>" maxlength="<?=$rankConfigData[$examName]['inputField']['score']['maxLength'];?>"/>
		    <div class="rank-error-message" style="display:none;" id="RP_ExamScore_error"></div>
                </li>
		<?php } ?>
		
		<?php if (array_key_exists("marks",$rankConfigData[$examName]['inputField'])){?>
                <li>
                	<label><?php echo $rankConfigData[$examName]['inputField']['marks']['label'];?></label>
                    <input type="text" class="rnk-predictor-textfield" id="RP_BoardScore" minlength="<?=$rankConfigData[$examName]['inputField']['marks']['minLength'];?>" maxlength="<?=$rankConfigData[$examName]['inputField']['marks']['maxLength'];?>"/>
		    <div class="rank-error-message" style="display:none;" id="RP_BoardScore_error"></div>
                </li>
		<?php } ?>
		
		<?php if (array_key_exists("rollNo",$rankConfigData[$examName]['inputField'])){?>
                <li>
                	<label><?php echo $rankConfigData[$examName]['inputField']['rollNo']['label'];?> </label>
                    <input type="text" class="rnk-predictor-textfield" id="RP_ExamRollNo" minlength="<?=$rankConfigData[$examName]['inputField']['rollNo']['minLength'];?>" maxlength="<?=$rankConfigData[$examName]['inputField']['rollNo']['maxLength'];?>"/>
		    <div class="rank-error-message" style="display:none;" id="RP_ExamRollNo_error"></div>
                </li>
                <?php } ?>
		
		
                <li>
		  
		  <?php if (!array_key_exists("rollNo",$rankConfigData[$examName]['inputField']) && !array_key_exists("marks",$rankConfigData[$examName]['inputField'])){?>
                	<a href="javascript:void(0)" class="predict-rnk-btn" id="predictRankButton" onclick="getPredictedRankForJEEMains($('#RP_ExamScore').val(),'','','','',<?php echo $rankConfigData[$examName]['inputField']['score']['minRange'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['maxRange'];?>,'NO','NO','YES','','',<?php echo $rankConfigData[$examName]['inputField']['score']['minLength'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['maxLength'];?>,'<?php echo $trackingPageKeyId?>','<?php echo $shortlistTrackingPageKeyId?>','<?php echo $comparetrackingPageKeyId;?>', '<?php echo $downloadtrackingPageKeyId;?>');">Predict <?php echo $rankConfigData[$examName]['examName'];?> Rank</a>
		  <?php }else if(!array_key_exists("rollNo",$rankConfigData[$examName]['inputField'])) {?>
			<a href="javascript:void(0)" class="predict-rnk-btn" id="predictRankButton" onclick="getPredictedRankForJEEMains($('#RP_ExamScore').val(),$('#RP_BoardScore').val(),'',<?php echo $rankConfigData[$examName]['inputField']['marks']['minRange'];?>,<?php echo $rankConfigData[$examName]['inputField']['marks']['maxRange'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['minRange'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['maxRange'];?>,'NO','YES','YES',<?php echo $rankConfigData[$examName]['inputField']['marks']['minLength'];?>,<?php echo $rankConfigData[$examName]['inputField']['marks']['maxLength'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['minLength'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['maxLength'];?>,'<?php echo $trackingPageKeyId?>','<?php echo $shortlistTrackingPageKeyId?>','<?php echo $comparetrackingPageKeyId;?>', '<?php echo $downloadtrackingPageKeyId;?>');">Predict <?php echo $rankConfigData[$examName]['examName'];?> Rank</a>
		  <?php } else if(!array_key_exists("marks",$rankConfigData[$examName]['inputField'])) {?>
			<a href="javascript:void(0)" class="predict-rnk-btn" id="predictRankButton" onclick="getPredictedRankForJEEMains($('#RP_ExamScore').val(),'',$('#RP_ExamRollNo').val(),'','',<?php echo $rankConfigData[$examName]['inputField']['score']['minRange'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['maxRange'];?>,'YES','NO','YES','','',<?php echo $rankConfigData[$examName]['inputField']['score']['minLength'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['maxLength'];?>,'<?php echo $trackingPageKeyId?>','<?php echo $shortlistTrackingPageKeyId?>','<?php echo $comparetrackingPageKeyId;?>', '<?php echo $downloadtrackingPageKeyId;?>');">Predict <?php echo $rankConfigData[$examName]['examName'];?> Rank</a>
		  
		  <?php }else{ ?>
			<a href="javascript:void(0)" class="predict-rnk-btn" id="predictRankButton" onclick="getPredictedRankForJEEMains($('#RP_ExamScore').val(),$('#RP_BoardScore').val(),$('#RP_ExamRollNo').val(),<?php echo $rankConfigData[$examName]['inputField']['marks']['minRange'];?>,<?php echo $rankConfigData[$examName]['inputField']['marks']['maxRange'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['minRange'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['maxRange'];?>,'YES','YES','YES',<?php echo $rankConfigData[$examName]['inputField']['marks']['minLength'];?>,<?php echo $rankConfigData[$examName]['inputField']['marks']['maxLength'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['minLength'];?>,<?php echo $rankConfigData[$examName]['inputField']['score']['maxLength'];?>,'<?php echo $trackingPageKeyId?>','<?php echo $shortlistTrackingPageKeyId?>','<?php echo $comparetrackingPageKeyId;?>', '<?php echo $downloadtrackingPageKeyId;?>');">Predict <?php echo $rankConfigData[$examName]['examName'];?> Rank</a>
		  <?php } ?>
		  
		  
                </li>
		
            </ul>
        </div>
        <?php $this->load->view("mcommon5/socialShareThis",array('className'=>'shadow')); ?>
     </section>
      
      <form type='hidden' method="post" action="<?=SHIKSHA_HOME?>/muser5/MobileUser/register" id="RankPredictorHiddenForm">
            
                        <input type="hidden" name="category" id="category" value="<?php echo $rankConfigData[$examName]['fieldOfInterest'];?>" />
                        <input type="hidden" name="subcategory" id="subcategory" value="<?php echo $rankConfigData[$examName]['desiredCourse'];?>" />
                        <input type="hidden" name="yearOfPassing" id="yearOfPassing" value="<?php echo date('Y');?>" />
                        <input type="hidden" name="ExamFromRP" id="ExamFromRP" value="<?php echo $rankConfigData[$examName]['examFieldList'];?>" />
                        <input type="hidden" name="fromRankPredictorPage" id="fromRankPredictorPage" value="Yes" />
                        <input type="hidden" name="tracking_keyid" id="tracking_keyid" value='<?=$trackingPageKeyId?>'>
                        
   </form>
</div>

<script>
   

            <?php $cookieName='rankPredictorParam_'.$examName;
	    if(isset($_COOKIE[$cookieName]) && $_COOKIE[$cookieName] != '' ) { ?>
	   
	       if(is_user_logged_in=="true") {
				   
			   var valueCookie=getCookie("<?php echo $cookieName;?>");
			   var cookieArray = valueCookie.split('||');
			   $('#ExamScoreDisplay').html(cookieArray[0]);
			   $('#BoardScoreDisplay').html(cookieArray[1]);
			   $('#rankPredictorForm').hide();
			   $('#rankpredictorResult').show();  
				   
	       }
             <?php } ?>
	     
	     function resetRankSearch(){
				
		setCookie('<?php echo $cookieName;?>','',-30);
                $('#rankpredictorResult').hide();
		$('#RP_ExamScore').val('');
	       $('#RP_BoardScore').val('');
	       $('#RP_ExamRollNo').val('');
	       $('#RP_ExamRollNo_error').hide();
	       $('#RP_ExamScore_error').hide();
	       $('#RP_BoardScore_error').hide();
		$('#rankPredictorForm').slideDown(1000);
				
	     }
	     
	    $('#RP_ExamScore').val('');
	    $('#RP_BoardScore').val('');
	    $('#RP_ExamRollNo').val('');		
   
	     
   </script>


<!-- Google Code for registration Conversion Page -->

<?php if(isset($_COOKIE['rankPredictorParam']) && $_COOKIE['rankPredictorParam'] != '' && isset($userStatus[0]['userid']) && $userStatus[0]['userid'] != '') { ?>
      <!--
      <script type="text/javascript">
      
      
	    /* <![CDATA[ */
	    
	    var google_conversion_id = 1053765138;  
	    var google_conversion_language = "en_GB"; 
	    var google_conversion_format = "1"; 
	    var google_conversion_color = "ffffff"; 
	    var google_conversion_label = "O3WQCOaXRRCS3Lz2Aw";  
	    var google_conversion_value = 1.00;    
	    var google_conversion_currency = "INR";     
	    var google_remarketing_only = false;
	    
	    /* ]]> */
       </script>
	    
	    <script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
	    
	    </script>
	    
	    <noscript>
	    
	    <div style="display:inline;">
	    
	    <img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/1053765138/?value=1.00&amp;currency_code=INR&amp;label=O3WQCOaXRRCS3Lz2Aw&amp;guid=ON&amp;script=0"/>
	    
	    </div>
      
	 </noscript>
         -->
<?php } ?>
