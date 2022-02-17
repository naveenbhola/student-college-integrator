<div style="width:500px;display:none" id="thank-you-layer">

	<div class="blkRound">
		<div class="bluRound">
			<span class="float_R">
			   <img class="pointer" onclick="movewindow();" src="/public/images/fbArw.gif" border="0"/>
			</span>
		   <span class="title">Request E-Brochure</span>
			<div class="clear_B"></div>
		</div>
		<div class="whtRound" style="padding:10px 15px">
            <div class="fontSize_16p bld">Thank you for your request</div>
			<div class="fontSize_16p bld" id="idContent1" style="width:100%">You will be receiving E-brochure of the selected institute(s) in your mailbox shortly.</div>
            <center>
                <input onclick="movewindow();" type="submit" class="fbBtn" class="submitGlobal" value="OK" id="submitGlobalForRequestEBrochure" />
            </center>
        </div>
	</div>
    
	</form>
</div>
<script type="text/javascript">
    function movewindow() {
        if(category_course_base_url != ""){
            window.location = category_course_base_url;
        }else{
			var showRecommendation = true;
			if((keyname == "MAIN_SEARCH_PAGE"  && typeof $searchPage != 'undefined') || ( keyname == "RANKING_PAGE"  && typeof $rankingPage != 'undefined' )){
				showRecommendation = false;
			}
			if(showRecommendation){
				openRecommendations($categorypage.categoryId,recommendation_json);
			}
                        
			if(!userlogin){
				window.location.reload();
			}else{
				$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';
				closeMessage();
			}
        }
    }
	if(category_course_base_url == "COMPARE_1"){
		category_course_base_url = "";
		var thanksHTML = '<div id="confirmation-box">';
		thanksHTML += '<strong>Thank you! We have successfully mailed E-brochure for <a href="'+compare_institute_url+'">'+compare_institute_name+'</a> to you.</strong>';
		thanksHTML += '<br />';
		if( ( ( keyname !== "MAIN_SEARCH_PAGE"  && typeof $searchPage == 'undefined' ) || ( keyname != "RANKING_PAGE"  && typeof $rankingPage == 'undefined' ) ) && trim(getCookie('recommcheckcompare')) == 1){
			//thanksHTML += ' Your fellow students also saw these institutes. <input type="button" class="gray-button" value="View Now" onclick="openRecommendations($categorypage.categoryId);"/></div>';
			thanksHTML += '</div>';
		}
                thanksHTML += '</div>';
		recommendation_json_i = compare_institute_id;
		recommendation_json_c = compare_course_id;
		setCookie("recommendation_json_i",recommendation_json_i,60,'seconds');
		setCookie("recommendation_json_c",recommendation_json_c,60,'seconds');
		if(typeof(STUDY_ABROAD_TRACKING_KEYWORD_PREFIX) == 'undefined'){
			 STUDY_ABROAD_TRACKING_KEYWORD_PREFIX = "";
		}
		setCookie("thanks_you_text"+STUDY_ABROAD_TRACKING_KEYWORD_PREFIX,thanksHTML,60,'seconds');
		setCookie("comparelayer"+$categorypage.key,1);
		var position = $j("#compare-cont").offset();
		scroll(0,position.top-10);
		setCookie("scrollPosition",position.top,60,'seconds');
		if(!userlogin || (compareSlide == 1 && studyAbroad == 1)){
			//window.location =  $categorypage.currentUrl;
			location.reload();
		}else{
			$('confirmation-box-wrapper').innerHTML = thanksHTML;
			$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';
			closeMessage();
			$('reb_button_'+compare_course_id).innerHTML = '<p class="eb-sent">E-brochure Sent</p>';
			//$('reb_button_'+compare_course_id+'_other').innerHTML = '<p class="eb-sent">E-brochure Sent</p>';
			$j('#bottomREB_'+compare_course_id).html('<p class="eb-sent">E-brochure Sent</p>');
			setCookie("thanks_you_text"+STUDY_ABROAD_TRACKING_KEYWORD_PREFIX,"",60,'seconds');
			//refreshCompareLayer();
		}
	}else if(category_course_base_url == "COMPARE_2"){
		$j("#thanks-box").show();
		var position = $j("#thanks-box").offset();
		scroll(0,position.top-10);
		setCookie("compare_bottom_widget",1,60,'seconds');
		setCookie("scrollPosition",position.top,60,'seconds');
		if(!userlogin || (compareSlide == 1 && studyAbroad == 1)){
			window.location =  $categorypage.currentUrl;
		}else{
			$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';
			closeMessage();
			orangeButtonDisableEnableWithEffect('submitRegisterFormButton_w',false);
			refreshCompareLayer();
		}
	}else if(category_course_base_url == "COMPARE_3"){
		category_course_base_url = "";
		var thanksHTML = '<div id="confirmation-box">';
		thanksHTML += '<strong>Thank you for request. We have emailed the comparison to you.</strong>';
		if(typeof(STUDY_ABROAD_TRACKING_KEYWORD_PREFIX) == 'undefined'){
			STUDY_ABROAD_TRACKING_KEYWORD_PREFIX = "";
		}
		setCookie("thanks_you_text"+STUDY_ABROAD_TRACKING_KEYWORD_PREFIX,thanksHTML,60,'seconds');
		setCookie("comparelayer"+$categorypage.key,1);
		var position = $j("#compare-cont").offset();
		scroll(0,position.top-10);
		setCookie("scrollPosition",position.top,60,'seconds');
		if(!userlogin || (compareSlide == 1 && studyAbroad == 1)){
			//window.location =  $categorypage.currentUrl;
			location.reload();
		}else{
			$('confirmation-box-wrapper').innerHTML = thanksHTML;
			$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';
			closeMessage();
			setCookie("thanks_you_text"+STUDY_ABROAD_TRACKING_KEYWORD_PREFIX,"",60,'seconds');
			//refreshCompareLayer();
		}
	}else{
		$('thank-you-layer').style.display = 'block';
	}
</script>
