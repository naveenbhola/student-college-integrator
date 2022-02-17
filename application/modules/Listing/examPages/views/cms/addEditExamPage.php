<?php
	$customElementCount 	= 1;
	$headingImageClass 	= ($actionType == "add" ? "plus-icon":"minus-icon");
	$sectionDisplayStyle	= ($actionType == "add" ? "display:none;":"");
	$collapseCheckboxTitle  = "Check this box to show this Information in expanded state on Shiksha.com";
	
	$basicPageData	= $examPageData['basic'];
	$homePageData 	= $examPageData['homepage'];
	$sectionData    = $examPageData['section_info'];
	$sectionDataArr = $sectionData;
	
	if($actionType =='add'){
		$sectionDataArr['home']['showLinkInMenu']       = true;
		$sectionDataArr['syllabus']['showLinkInMenu']   = true;
		$sectionDataArr['imp_dates']['showLinkInMenu']  = true;
		$sectionDataArr['results']['showLinkInMenu']    = true;
		$sectionDataArr['discussion']['showLinkInMenu'] = true;
		$sectionDataArr['colleges']['showLinkInMenu']    = true;
		$sectionDataArr['article']['showLinkInMenu']    = true;
		$sectionDataArr['preptips']['showLinkInMenu']   = true;
	}

	$disableFields = ($actionType == 'edit') ? 'disabled' : '';
	usort($sectionData, function($a, $b) {
		return $a['priority'] - $b['priority'];
	});

	if(empty($sectionData))
		$sectionData = array();
		
	$sectionOrder  = $sectionData;
	
?>
<form id ="form_<?=$formName?>" name="<?=$formName?>" action="/examPages/ExamPagesCMS/saveExamPageFormData"  method="POST" enctype="multipart/form-data">

<!-- <div class="abroad-cms-wrapper"> -->
	<div class="abroad-cms-content">
        <div class="abroad-cms-rt-box">
        <?php $this->load->view('/examPages/cms/manageTabs',array('tab'=>$activePage));?>
            <div class="abroad-cms-head" style="overflow: visible;">
                <h1 class="abroad-title">Manage Content</h1>
				<div class="last-uploaded-detail">
					<p>
					<?php
					if($actionType == 'edit')
					{
					?>
						Last updated <b><?=date("d-M-y h:i:s",strtotime($basicPageData['last_modified_date']))?></b> by <b><?=$basicPageData['last_modified_by_name']?></b>
					<?php
					}
					?>
						<br />
						*Mandatory
					</p>
				</div>

            </div>
            <div class="cms-form-wrapper clear-width">
	              <?php $this->load->view('cms/examBasicInfo',array('headingImageClass' => $headingImageClas,'sectionDisplayStyle' => $sectionDisplayStyle));?>
	             <div class="clear-width" id="cms-loader">
	             </div>
	             <div class="exam_content_form clear-width" id="exampages_cms_cont">
	             </div>
           	</div>
		</div>
		<div class="cms-form-wrapper clear-width hide" id="user_cmnts_text">
			<div id="exampage_comment_btn_section">
				<div class="clear-width">
					<div class="cms-form-wrap" style="margin:0 0 10px 0; padding-top:8px; border-top:1px solid #ccc;">
						<ul>
							<li>
								<label>User Comments: </label>
								<div class="cms-fields">
								   <textarea name="userComments" id="userComments" class="cms-textarea" style="width:75%;" validationtype="str" caption="User Comments" onblur="showErrorMessage(this, '<?=$formName?>');" onchange="showErrorMessage(this, '<?=$formName?>');" maxlength="256"></textarea>
				   <div id="userComments_error" class="errorMsg" style="display:none;"></div>
								</div>
							</li>
						</ul>
					</div>
				</div>

				<input type="hidden" value="<?=$actionType;?>" id="actionType">
			   
				<div class="button-wrap">
					<a  href="JavaScript:void(0);" onclick ="submitExamPageData('draft','<?=$formName?>', 'draftButton');"  id="draftButton" class="gray-btn">Save as Draft</a>
					<a  href="JavaScript:void(0);" onclick ="submitExamPageData('live','<?=$formName?>', 'liveButton');" id="liveButton" class="orange-btn">Save & Publish</a>
					<a  href="JavaScript:void(0);" onclick ="previewPage();" id="previewButton" style="display: none;" class="orange-btn">Preview</a>
					<a  href="JavaScript:void(0);" onclick="unlockEditorInfo()" class="cancel-btn">Cancel</a>
				</div>
				<div class="clearFix"></div>
			</div>
		</div>	   
    </div>
<!-- </div> -->
</form>
<?php $this->load->view('common/footerNew'); ?>
<?php $this->load->view('examPages/cms/footer'); ?>
<script>
	function previewPage(){
		setExamPagePreviewCookie('skipCache','true',180,'seconds');
		window.open(examPageUrl, "_blank");
	}
 
	var actionType = '<?php echo $actionType;?>';
	if(actionType !== 'edit'){
		$j('#examList_addExamPage').val('');	
	}
	var sectionOrder = <?php echo json_encode($sectionOrder);?>;
	var sectionList = [];
	//$j("#exampages_cms_cont").html();
	$j.each(sectionOrder, function(index, value) {
		var fieldName = value['section'];
		if(fieldName == 'discussion'){
			$j("#exampages_cms_cont").append($j("#exam_discussion_section"));
		}
		if(fieldName == 'syllabus'){
			$j("#exampages_cms_cont").append($j("#exam_syllabus_section"));
		}
		if(fieldName == 'imp_dates'){
			$j("#exampages_cms_cont").append($j("#exam_imp_dates_section"));
		}
		if(fieldName == 'results'){
			$j("#exampages_cms_cont").append($j("#exam_results_section"));
		}
		if(fieldName == 'home'){
			$j("#exampages_cms_cont").append($j("#exam_hp_section"));
		}
		if(fieldName == 'colleges'){
			$j("#exampages_cms_cont").append($j("#exam_colleges_section"));
		}
		if(fieldName == 'article'){
			$j("#exampages_cms_cont").append($j("#exam_article_section"));
		}
		if(fieldName == 'preptips'){
			$j("#exampages_cms_cont").append($j("#exam_preptips_section"));
		}
	});
	
    window.onbeforeunload =confirmExit; 
    var preventOnUnload = false;
    var saveInitiated = false;  
		function confirmExit()
		{//alert(saveInitiated);
			if(preventOnUnload == false)
				return 'Any unsaved change will be lost.';
		}
		
    var formname = "<?php echo $formName; ?>";
    var isTinymceEnabled = false;
    //$j(document).ready(function($j) {
	function enableTinymceEditor()
	{
        /*
         * initialize tinymce editor
         */
        var uploader;
	tinymce.init({
	    height : "500",
            selector: ".tinymce-textarea",
            theme: "modern",
            plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen textcolor emoticons",
                "insertdatetime media table contextmenu paste jbimages"//moxiemanager
            ],
            file_browser_callback: false,
            toolbar1: /*""*/ " bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image media preview ",
            toolbar2: "undo redo | styleselect | forecolor backcolor emoticons | jbimages ",
            relative_urls : false,
            rel_list: [
                        {title: 'alternate'    , value: 'alternate' },
                        {title: 'author'       , value: 'author'    },
                        {title: 'bookmark'     , value: 'bookmark'  },
                        {title: 'help'         , value: 'help'      },
                        {title: 'license'      , value: 'license'   },
                        {title: 'next'         , value: 'next'      },
                        {title: 'nofollow'     , value: 'nofollow'  },
                        {title: 'noreferrer'   , value: 'noreferrer'},
                        {title: 'prefetch'     , value: 'prefetch'  },
                        {title: 'prev'         , value: 'prev'      },
                        {title: 'search'       , value: 'search'    },
                        {title: 'tag'          , value: 'tag'       }
                    ],
            // added event handlers for focus & blur events to show/hide tooltips
            init_instance_callback : function(editor) {
                editor.on('focus', function(e) {
                    studyabroadtooltipshow(e,document.getElementById(editor.id));
                    showErrorMessage(document.getElementById(editor.id), formname );
                });
                editor.on('change',function(e){
                    $j("#"+editor.id).html(editor.getContent());
                    showErrorMessage(document.getElementById(editor.id), formname );
                    });
                editor.on('blur', function(e) {
                    $j("#"+editor.id).html(editor.getContent());
                    showErrorMessage(document.getElementById(editor.id), formname );
                    studyabroadtooltiphide();
		   });
		editor.on('keydown', function(e) {
                    if($j("#"+editor.id).closest(".syllabus-div").length){
			if(e.keyCode == 9) // press tab
				{   //get the h3 title tag of next section
					var h3tag = $j("#"+editor.id).closest(".cms-accordion-div").closest(".clear-width").next().find('.section-title');
					if(h3tag.find('i').hasClass("plus-icon")) // open the section only if the section is closed
					{
						h3tag.trigger("click");
					}
				}
			}
                    
                });
            }
        
        });

        if(isTinymceEnabled)
		{
			return;
		}
		isTinymceEnabled = true;
		$j(".section-title + .cms-accordion-div").hide();
		$j(".section-title i").removeClass('minus-icon plus-icon').addClass('plus-icon');
		$j(".section-title").live("click",function(e){
			$j(".section-title i").removeClass('minus-icon plus-icon').addClass('plus-icon');
	        $j(this).find('i').removeClass('minus-icon plus-icon').addClass('minus-icon');
			if($j(this).next().css('display')!='block'){
				$j(".section-title + .cms-accordion-div").hide();
			}else{
		        $j(this).find('i').removeClass('minus-icon plus-icon').addClass('plus-icon');
			}
	        $j(this).next().slideToggle();
	        $j('html, body').animate( { scrollTop: $j(this).offset().top }, 1000 );
        });
	
	/*
         * pressing tab on last input element of the section will open the next section and
         * set the focus to its first input element
         */
        $j(".last-in-section,.syllabus-div").live('keydown',function(event){
            if(event.keyCode == 9) // press tab
            {   //get the h3 title tag of next section
                var h3tag = $j(this).closest(".cms-accordion-div").closest(".clear-width").next().find('.section-title');
                if(h3tag.find('i').hasClass("plus-icon")) // open the section only if the section is closed
                {
                  h3tag.trigger("click");
                }
            }
        });
        
    }
        
    //});
    
    
    function addMoreImportantDates(thisObj)
    {
	var cloneDiv = $j(thisObj).closest('.cms-form-wrap').find(".importantDateElement").last().clone();

	var elementId = cloneDiv.find(".start-date").attr("id");
	
	var stringToBeReplaced = parseInt(elementId.substring(elementId.length-1));
	cloneDiv.find("input[type=text]").val("");
	cloneDiv.find(".remove-link-2").show();
	cloneDiv.find("input[type=number]").val("");
	//cloneDiv.find(".importantDateHeading").text(cloneDiv.find(".importantDateHeading").text().replace(stringToBeReplaced, stringToBeReplaced+1));
	cloneDiv.find(".start-date").attr("id", cloneDiv.find(".start-date").attr("id").replace("_"+stringToBeReplaced, "_"+(stringToBeReplaced+1)));

	cloneDiv.find(".end-date").attr("id", cloneDiv.find(".end-date").attr("id").replace("_"+stringToBeReplaced, "_"+(stringToBeReplaced+1)));
	cloneDiv.find(".calendar-icon").attr("id", cloneDiv.find(".calendar-icon").attr("id").replace("_"+stringToBeReplaced, "_"+(stringToBeReplaced+1)));

	cloneDiv.appendTo($j(thisObj).prev());
	if ($j(".importantDateElement").length >= 50) {
		$j("#addMoreImportantDateLink").hide();
	}
    }
    
    function removeImportantDatesSection(thisObj)
    {
	$j(thisObj).closest(".importantDateElement").remove();
	
	if ($j(".importantDateElement").length < 50) {
		$j("#addMoreImportantDateLink").show();
	}
    }
    
    function removeTopperInterviewSection(thisObj)
    {
	//$j(thisObj).closest(".topperInterview").remove();
	elementToBeRemoved = $j(thisObj).closest(".topperInterview");
	
	if (typeof(elementToBeRemoved) != 'undefined') {
		//remove editors
		tinymce.EditorManager.execCommand('mceRemoveEditor', false, elementToBeRemoved.find('.topperInterviewInfo').attr('id'));
		elementToBeRemoved.remove();
		//reorderGuideSections(prevElem);
	}
	
	if ($j(".topperInterview").length < 5) {
		$j("#addMoreTopperInterviewLink").show();
	}
    }
    
    function removeHomePageWiki(thisObj)
    {
	elementToBeRemoved = $j(thisObj).closest(".custom-wiki");
	
	if (typeof(elementToBeRemoved) != 'undefined') {
		//remove editors
		tinymce.EditorManager.execCommand('mceRemoveEditor', false, elementToBeRemoved.find('.custom-wiki-data').attr('id'));
		elementToBeRemoved.remove();
	}
	
	section = $j(thisObj).attr("section");
	if ($j(".homepage-custom-wiki-section"+section).length < 5) {
		$j("#addMoreSectionLink_"+section).show();
	}
	
    }

    function addMoreTopperInterview(thisObj)
    {
	var clonedElement = $j(".topperInterview").first().clone(true,true);
	
	clonedElement.find(".remove-link-2").show();
	clonedElement.find(".mce-container").remove();
	
	clonedElement.find(".topperInterviewInfo").show();
	clonedElement.find(".errorMsg").hide();
	clonedElement.find(".topperInterviewInfo").html("");
	clonedElement.find("input[name='topperIntCollapsibleState[]']").attr("checked",false);
	
	var lastElementId = $j(".topperInterviewInfo").last().attr("id");
	lastElementIdArr = lastElementId.split("_");
	lastId = lastElementIdArr[lastElementIdArr.length-1];
	var nextCount = parseInt(lastId)+1;
	
	clonedElement.find(".topperInterviewInfo").attr("id","topperInterviewInfo_"+nextCount);
	clonedElement.find(".errorMsg").attr("id","topperInterviewInfo_"+nextCount+"_error");
	
	
	if (!clonedElement.hasClass("add-more-sec2")) {
		clonedElement.addClass("add-more-sec2");
	}
	clonedElement.appendTo($j(".resultSectionList"));
	tinymce.EditorManager.execCommand('mceAddEditor', false, "topperInterviewInfo_"+nextCount);
	
	if ($j(".topperInterview").length >= 5) {
		$j("#addMoreTopperInterviewLink").hide();
	}
    }

    function saveWikiData(id, order, sectionName, label){
    	$j('.belowEdit-btnWrapper button').prop('disabled', true);
        var data = new Object();        
        if(sectionName=='homepage'){
        	if(label=='Summary' && trim($j('#'+id).val())==''){
        		alert("Fields marked in RED are mandatory while saving the form.");
	    		return false;
        	}
        	data[sectionName] = [];
        	data[sectionName].push({order:order, wikiData:$j('#'+id).val(), label:label, updatedOn:$j('#updatedOn_'+label.replace(' ','')).is(":checked"),prevUpdatedOn:$j('#prevUpdOn_'+label.replace(' ','')).val(), type:"fixed"});
        }
        if(sectionName=='importantdates' || sectionName=='samplepapers' || sectionName=='preptips'){
        	data[sectionName] = [];
        	data[sectionName].push({order:order, wikiData:$j('#'+id).val(), label:label, updatedOn:$j('#updatedOn_'+sectionName).is(":checked"), prevUpdatedOn :$j('#prevUpdOn_'+sectionName).val()});
        }
 
        if(sectionName=='applicationform' || sectionName=='counselling' || sectionName=='cutoff' || sectionName=='answerkey' || sectionName=='slotbooking' || sectionName=='admitcard' || sectionName=='results' || sectionName=='syllabus' || sectionName=='pattern' || sectionName=='vacancies' || sectionName=='callletter' || sectionName=='news'){
        	data[sectionName] = {};
        	data[sectionName].wikiData = $j('#'+id).val();
        	data[sectionName].updatedOn = $j('#updatedOn_'+sectionName).is(":checked");
        	data[sectionName].prevUpdatedOn = $j('#prevUpdOn_'+sectionName).val();
        }
        
        var examNameStr             = $j("select[name='examName']").val();
        examNameArr                 = examNameStr.split('@#');
        var $examValue              = $j("select[name='examName']").val();
        data['examId']              = examNameArr[0];
        data['examName']            = examNameArr[1];

        var groupNameStr = $j("select[name='group_name']").val();
        var groupNameArr = groupNameStr.split('@#');
        data['groupId']  = groupNameArr[0];
        data['groupName']= groupNameArr[1];

        data['status']           = 'live';
        data['examPageId']       = $j("[name='examPageId']").val();
        data['createdBy']        = $j("[name='createdBy']").val();
        data['creationDate']     = $j("[name='creationDate']").val();
        console.log(data);
        var ajaxUrl = "/examPages/ExamPagesCMS/saveExamPageContentData";
		$j.ajax({
		    type	: "POST",
		    url		: ajaxUrl,
	       	data    : { data: JSON.stringify(data) }, //
		    beforeSend 	: function(){
				    }
		})
		.done(function( res ) {
			res = JSON.parse(res);
	        if(!res){
	        	isPostAjaxInProgress = false;
	        }else{
	        	alert('Your data is saved successfully!!!');
	        	$j('.belowEdit-btnWrapper button').prop('disabled', false);
	        	return false;
	        }
	        preventOnUnload = true;
			//
		});
    }

    
    function addMoreHomePageSection(thisObj, sectionid)
    {
	var clonedElement = $j(".homepage-custom-wiki").clone(true,true);
	var customElementsAdded = $j(".homepage-custom-wiki-section"+sectionid);
	clonedElement.show();
	clonedElement.removeClass("homepage-custom-wiki");
	clonedElement.addClass("homepage-custom-wiki-section"+sectionid);
	clonedElement.find(".custom-wiki-data").show();
	clonedElement.find(".errorMsg").hide();
	
	
	clonedElement.find(".mce-container").remove();
	
	clonedElement.find(".custom-label-field").attr("name","customLabel[]");
	clonedElement.find(".custom-wiki-data").attr("name","homepagedata[]");
	//clonedElement.find(".custom-wiki-data").attr("maxlength",10000);
	clonedElement.find(".remove-link-2").attr("section", sectionid);
	
	var nextElementsIdNumber = 1;
	if(customElementsAdded.length > 0)
	{
		lastElementId = customElementsAdded.find(".custom-wiki-data").last().attr("id");
		lastElementIdArr = lastElementId.split("_");
		lastId = lastElementIdArr[lastElementIdArr.length-1];
		nextElementsIdNumber = parseInt(lastId)+1;
	}
	
	clonedElement.insertBefore($j(thisObj).closest("li"));
	clonedElement.find(".custom-wiki-data").attr("id","customWikiData"+sectionid+"_"+nextElementsIdNumber);
	clonedElement.find(".custom-wiki-data").closest("li").find(".errorMsg").attr("id","customWikiData"+sectionid+"_"+nextElementsIdNumber+"_error");

	tinymce.EditorManager.execCommand('mceAddEditor', false, "customWikiData"+sectionid+"_"+nextElementsIdNumber);
	//
	if ($j(".homepage-custom-wiki-section"+sectionid).length >= 5) {
		$j("#addMoreSectionLink_"+sectionid).hide();
	}
    }
    
    function checkForToDate(thisObj)
    {
	to_date = $j(thisObj).closest("ul").find(".end-date");
	if ($j(to_date).val() && $j(to_date).val() !== 'undefined')
	{
		if( Date.parse($j(to_date).val()) < Date.parse($j(thisObj).val()))
		{
			alert("Start date cannot be greater than To Date");
			$j(to_date).val("");
		}
	}
	
    }
	
	<?php if(!empty($examId) && !empty($groupId)) {?>
		$j(document).ready(function($j) {
			getContentBasedOnGroup();
		});	
	<?php }else{ 
			$preSelectExam = $_COOKIE['examcontentcms'];
			if(!empty($preSelectExam))
			{
				$preSelectExam = base64_decode($preSelectExam);	
			?>
				setCookie('examcontentcms','');
				$j("select[name='examName']").val('<?=$preSelectExam;?>').trigger('change');

	<?php } } ?>    
	window.onbeforeunload = null;
</script>
