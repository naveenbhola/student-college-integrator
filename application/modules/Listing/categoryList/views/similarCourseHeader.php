<?php	
$defaultJs = array('category','AutoSuggestor',"json2","multipleapply",'ajax-api');
if($js){
	$defaultJs = array_merge($defaultJs,$js);
}

$headerComponents = array(
	'js'=>$defaultJs,
	'jsFooter' =>array('common','processForm','onlinetooltip'),
	'product'=>'categoryHeader',
	'title'	=> "Find institutes similar to your dream institute",
	'metaDescription' => "",
	'metaKeywords'	=> "",
	'indentCPGSHeader'=> true,
	'searchEnable' => true,
	'noIndexNoFollow' => 1
);

$this->load->view('common/header', $headerComponents);
?>
<style>
.similar-inst-cont{background:#fafafa; padding:12px 12px 20px;border:1px solid #e4e4e4; -moz-box-shadow:0 1px 2px #E4E4E4; -webkit-box-shadow:0 1px 2px #E4E4E4; box-shadow:0 1px 2px #E4E4E4;}
.similar-inst-cont .cont-title{font-size:20px; color:#3f3f3f; font-family:Arial, Helvetica, sans-serif; font-weight:normal; margin:0 0 10px 0;}
.similar-textfield, .similar-select {border: 1px solid #DBDBDB;border-radius:1px;-moz-border-radius:1px;-webkit-border-radius:1px;-moz-box-shadow: 0 1px 3px #dbdbdb inset;-webkit-box-shadow: 0 1px 3px #dbdbdb inset;box-shadow: 0 2px 3px #ebebeb inset; -moz-box-shadow: 0 2px 3px #ebebeb inset; -webkit-box-shadow: 0 2px 3px #ebebeb inset;color: #8e8e8e;font-family: Tahoma, Geneva, sans-serif; font-size: 14px;padding:7px;width:400px;}
.similar-select{width:416px; padding:6px 4px}
.find-btn{margin-left:5px; padding:6px 12px !important; font-size:16px !important; color:#fff !important; text-decoration:none !important;}
.view-smlr-btn{background:#f0f0f0; border:1px solid #c8c8c8; color:#474646 !important; font-weight:normal; padding:7px 8px !important; margin-left:15px; font-size:12px !important; text-decoration:none !important}
.similar-content{margin:18px 0 0 0; color:#939393;}
.similar-content strong{font-size:14px; margin-bottom:3px; display:block;}
.similar-content p{font-size:13px; line-height:20px;}
.similar-content p.err-msg{font-size:16px; line-height:24px;}
.similar-title{font:normal 25px Tahoma, Geneva, sans-serif; color:#ff8400; margin-bottom:15px;}
.similar-inst-widget{}
.similar-inst-widget .widget-title{background:#ababab; padding:10px; color:#fff; font-size:18px; position:relative}
.similar-inst-widget .widget-content{padding:10px; background:#f8f8f8; color:#939393; font-size:13px; line-height:20px;}
.similar-inst-widget strong{font-size:16px; display:block; margin:4px 0 10px 0;}
.similar-inst-widget p{margin:15px 0 0 0;}
.similar-pointer{background:url(public/images/cate-sprite.gif) no-repeat; background-position:-207px -225px; width:16px; height:18px; position:absolute; left:-14px; top:8px;}
.similar-sprite{background:url(public/images/similar-sprite.png) no-repeat scroll 0 0 transparent;display: inline-block;overflow: hidden; font-style:none; vertical-align:middle;}
.similar-bg{background-position:-283px 0; width:317px; height:205px; float:right; margin-right:60px;}
.help-image{background-position:0 0; width:258px;height:160px;}
.loader{background:#fff; padding:10px 25px; text-align:center; position:absolute; -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px; -moz-box-shadow:0 0 4px #939292; -webkit-box-shadow:0 0 4px #939292; box-shadow:0 0 4px #939292; font:normal 18px Tahoma, Geneva, sans-serif; color:#868585}
.loader img{vertical-align:middle; margin-right:10px}
.similar-inst-wrapper{clear: both;}
#selectCourseSection{margin:12px 0 0;}
#suggestions_container_similar{position:absolute;background-color:#fff;width: 395px;}
.more-results-tab{background: #E4E4E4;clear: both;line-height: 3;margin-bottom: 10px;text-align: center;}
.more-results-tab:hover{cursor: pointer;}
.resultfound-section{padding: 20px 0px 0px 10px;font-size: 22px;color: #8B8B8B;}
.resultfound-title{color: #F7A24F;}
.similar-inst-cont0{margin:14px;}
</style>
        <script>
            /*
             For Institute Suggestor
            */
            var similarautoSuggestorInstance = null;
	    var isSimilarInstitutePage = 1;
	    var subcatSameAsldbCourseCategoryPage = <?=$subcatSameAsldbCourseCategoryPage?>;
	    var currentUrl = "<?=$currentURL?>";

            function initializeAutoSuggestorInstanceForSimilarInstitute(){

                if (window.addEventListener){
                    var ele = document.getElementById("keywordSuggest");
                   ele.addEventListener('keyup', handleInputKeysForInstituteSuggestorForSimilar, false);
                } else if (window.attachEvent){
                   var ele = document.getElementById("keywordSuggest");
                   ele.attachEvent('onkeyup', handleInputKeysForInstituteSuggestorForSimilar);
                }
                try{
                    similarautoSuggestorInstance = new AutoSuggestor("keywordSuggest" , "suggestions_container_similar", false, 'institute');
                    similarautoSuggestorInstance.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClickForSimilar;
                    similarautoSuggestorInstance.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressedForSimilar;
                    similarautoSuggestorInstance.callBackFunctionOnRightKeyPressed = handleAutoSuggestorRightKeyPressedForSimilar;
		    similarautoSuggestorInstance.callBackFunctionOnInputKeysPressed = handleAutoSuggestorInputKeyPressedForSimilar;
                    //similarautoSuggestorInstance.instituteSuggestionsForCategoryIds = "23";
                }catch(e){}
            }

            function handleInputKeysForInstituteSuggestorForSimilar(e)
            {
                if(similarautoSuggestorInstance && $('keywordSuggest').hasFocus){
                    similarautoSuggestorInstance.handleInputKeys(e);
                }
            }

	    function handleAutoSuggestorInputKeyPressedForSimilar(callBackData) {
		if ($j("#selectedInstituteName").val() != $j("#keywordSuggest").val() ) {
			$j("#selectCourseSection").hide();
		}
		else{
			$j("#selectCourseSection").show();
		}
	    }
            function handleAutoSuggestorMouseClickForSimilar(callBackData){
		$j("#noInstituteFoundError").hide();
                if(similarautoSuggestorInstance){
					viewSimilarInsttDbTrack('', callBackData['id'], 'SimilarInstitute', '', callBackData['ui'], '', 0);
                    similarautoSuggestorInstance.hideSuggestionContainer();
                    showSelectedInstitute(callBackData['id'],callBackData['sp']);
                }
            }
            
            function handleAutoSuggestorRightKeyPressedForSimilar(callBackData){
		$j("#noInstituteFoundError").hide();
                if(similarautoSuggestorInstance){
					viewSimilarInsttDbTrack('', callBackData['id'], 'SimilarInstitute', '', callBackData['ui'], '', 0);
                    similarautoSuggestorInstance.hideSuggestionContainer();
                    showSelectedInstitute(callBackData['id'],callBackData['sp']);
                }
            }
            
            function handleAutoSuggestorEnterPressedForSimilar(callBackData){
				$j("#noInstituteFoundError").hide();
				if (callBackData['id'] == -1) {
					viewSimilarInsttDbTrack('', '', 'SimilarInstitute', '', callBackData['ibt'], '', 1);
					$j("#noInstituteFoundError").show();
					$j("#selectCourseSection").hide();
				} else {
					viewSimilarInsttDbTrack('', callBackData['id'], 'SimilarInstitute', '', callBackData['ui'], '', 0);
				}
                if(similarautoSuggestorInstance){
                    similarautoSuggestorInstance.hideSuggestionContainer();
                    showSelectedInstitute(callBackData['id'],callBackData['sp']);
                }
            }
            
            var tempInstituteField = '';
            function showSelectedInstitute(instId,instTitle){
                if(checkValidInstitute(instId,instTitle)){
			$j("#selectedInstituteName").val(instTitle);
                    tempInstituteField = instId;
		    $j('#suggestedInstitutes').val(tempInstituteField);
                    var url = "/categoryList/CategoryList/getMBACoursesOfInstitutes";
                    new Ajax.Request(url, { method:'post', parameters: ('instituteId='+instId), onSuccess:
                                    function(request){
                                        instURL = request.responseText;
                                        instURL = JSON.parse(instURL);
                                        $j("#instituteCoursesList").html('');
                                        if (!$j.isEmptyObject(instURL))
                                        {
                                            $j("#selectCourseSection").show();
                                            //alert("non empty");
                                            $j("#instituteCoursesList").show();
                                            $j("#instituteCoursesList").append('<option value="">Select Course of Interest</option>');
                                            $j.each(instURL,function(index, ele){
                                                $j("#instituteCoursesList").append('<option value="'+index+'">'+ele+'</option>');
                                            });
                                        }
                                        
                                    }
                    });
                }
            }

            function checkValidInstitute(instituteId,title){
                if(instituteId<=0 || title=='')
                    return false;
                if(tempInstituteField == instituteId)
                    return false;
                selectedInstitutes = $('suggestedInstitutes').value;
                
		return true;
            }

		function getSimilarCourses()
		{
				if(!validateSearchFields())
					return false;
		
				var selectedCourse = $j("#instituteCoursesList").val();
				var ajaxUrl = "categoryList/CategoryList/similarInstitutes";
				resultSetOffset = 0;
				$j.ajax({
					    url  : ajaxUrl,
					    type : 'POST',
					    data : {
					    	    selectedCourse : selectedCourse ,
					    	    AJAX : 1
					    },
					    beforeSend 	: function(){
						showFilterLoader();
				            }
				}).done(function( res ) {
						res = JSON.parse(res);
						hideFilterLoader();
						$j("#cateLeftCol").html(res.viewObj);
						//$j("#resultfound-title").html(res.totalSimilarCourseCount);
				});
		}
		
		function getMoreResults()
		{
				var selectedCourse = $j("#instituteCoursesList").val();
				resultSetOffset = resultSetOffset + 20;
				var totalCoursesFound = Number($j("#resultfound-title").html());

				var ajaxUrl = "categoryList/CategoryList/similarInstitutes"
				$j.ajax({
					    url  : ajaxUrl,
					    type : 'POST',
					    data : {
					    	    selectedCourse : selectedCourse ,
						    resultSetOffset : resultSetOffset ,
						    AJAX : 1,
						    moreresultflag : 1
					    },
					    beforeSend 	: function(){
						showFilterLoader();
				            }
				}).done(function( res ) {
						res = JSON.parse(res);
						hideFilterLoader();
						$j("#instituteLists > ul").append(res.viewObj);
						
						if (totalCoursesFound <= (resultSetOffset+20))
						     $j("#more-results-tab").hide();		
				});
				

		}
		
		/* Function to show the loader image
		*/
		function showFilterLoader() {
				//disable background
				var body = document.getElementsByTagName('body')[0];
				overlayHackLayerForIE('overlayContainer', body);
				if(typeof $j('#iframe_div') != "undefined") {
						$j('#iframe_div').css({backgroundColor: "#000", opacity: "0.4" });
						$j('#iframe_div').attr("allowTransparency", "true");
				}
				
				// get the position of the current viewport so as to position the loader image in the center
				var top 	= $j(window).scrollTop();
				var left 	= $j('html').offset().left;
				var imageheight = $j("#loadingImage").height();
				var imagewidth 	= $j("#loadingImage").width();
				$j("#loadingImage").css("top",top + ($j(window).height() /2) - (imageheight/2));
				$j("#loadingImage").css("left",left + ($j(window).width() /2) - (imagewidth/2));
				$j("#loadingImage").show();
		}
		/* Function to hide the loader image
		*/
		function hideFilterLoader() {
				dissolveOverlayHackForIE();
				$j("#loadingImage").hide();
				$j("#iframe_div").css("background","none");
		}
		
		function redirectToSimilarListingPage()
		{
			if(!validateSearchFields())
				return false;
		
			var institute_id 	= $j("#suggestedInstitutes").val();
			var course_id 		= $j("#instituteCoursesList").val();
			var url = "/similar-institutes-"+institute_id+"-"+course_id;
			window.location = url;
		}
		
		function validateSearchFields() {
			if (!$j("#instituteCoursesList").val())
			{
				$j("#instituteCoursesList_error").show();
				return false;
			}
			else
				$j("#instituteCoursesList_error").hide();
			return true;
		}
</script>
		
<div style="padding : 0 8px;">
	<?php
	$loadMoreStyle = ($totalSimilarCourseCount > $resultSetChunksSize) ? "" : "display:none";
	//echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', 23, "Institutes", TRUE);
	
	
	?>
