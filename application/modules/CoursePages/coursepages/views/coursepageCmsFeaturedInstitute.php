<?php

if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}

?>

<?php

       
	$string_array = array();
	$string_array[] = "<option value=''>Select Course Page</option>";
	
	foreach ($COURSE_PAGES_HOME_ARRAY as $key =>$courseHome) {
		$string_array[] = "<option value='".$key."'>".$courseHome['Name']."</option>";
	}
	
	$position_array = array(1,2,3,4);
	$position_string = array();
	
	foreach ($position_array as $position) {
		$position_string[] = "<option value='".$position."'>".$position."</option>";
	}
?>

<div id="content-wrapper">
	<div class="wrapperFxd">
    	<div id="content-child-wrap">
        	<div id="course-cms-wrapper">
        	<div class="faq-widget-tab">
                    <ul>
                        <li class="active"><a href="javascript:void(0);">Widget Information</a></li>
                        <li><a href="/coursepages/CoursePageCms/reorderCoursepageWidgets">Widget Position</a></li>
			<li><a href="/coursepages/CoursePageCms/restrictContent">Restrict Content</a></li>
                    </ul>
            </div>
            <div id="add-widget-wrap">
            <form id="addFeaturedInstitutes" action="/coursepages/CoursePageCms/addSlide" method="post" onsubmit="return postFormData(this);">
            <input type="hidden" name="slide_id" id="slide_id"/>
            <input type="hidden" name="original_slide_order" id="slide_order"/>
            <input type="hidden" name="count_slides" id="count_slides" value="<?php echo $slideCount;?>"/>
                <div class="add-widget-title" style="padding-top: 10px;">
                    <h4>Add new Widget in &nbsp;<select class="universal-select" onchange="setSectionId();" name="subcatId" id="subcatId" style="width:180px !important"><?php echo implode("", $string_array);?></select></h4>
                  <div id="subcatId_error" class="regErrorMsg"></div>  
                </div>
                <div id="add-widget">
                    <div class="widget-header">
                        <h5 class="flLt" id="cms_feature_heading" style="display:none;">Slide show on MBA Course Page</h5>
                        <p class="flRt">Max 5 slides</p>
                    </div>
                    
                    <div class="widget-content" id="addslide_form">
                        <ul class="widget-form">
                            <li>
                                <label>Title:</label>
                                <div class="form-field">
                                    <input maxlength="70" caption="title" name="imageTitle" id="imageTitle" type="text" class="universal-txt-field" />
                                    <p class="form-hint">Max 70 characters</p>
                                    <div id="imageTitle_error" class="regErrorMsg"></div>
                                </div>                             
                            </li>
                            
                            <li>
                                <label>Photo:</label>
                                <div class="form-field">
                                    <input type="file" name="myImage[]" id="imageUrl"/>
                                    <p class="form-hint">Please upload an image of dimensionn (455*280)</p>
                                    <div id="imageUrl_error" class="regErrorMsg"></div>
                                </div>                             
                            </li>
                            
                            <li>
                                <label>URL:</label>
                                <div class="form-field">
                                    <input name="landingUrl" id="landingUrl" type="text" class="universal-txt-field" />
                                    <div id="landingUrl_error" class="regErrorMsg"></div>
                                </div>                              
                            </li>
                            
                            <li id="slidePosition_li" style="display: none;">
                                <label>Slide Position:</label>
                                <div class="form-field">
                                    <select name="slidePosition" id="slidePosition" class="universal-select" style="width:55px !important"><?php echo implode("", $position_string);?></select>
                                </div>
                            </li>
                            
                            <li>
                                <label style="padding-top:1px">Open in new window:</label>
                                <div class="form-field">
                                    <input type="checkbox" name="open_new_tab" id="open_new_tab" checked="checked"/>
                                </div>
                            </li>
                            
                        </ul>
                        <div class="clearFix"></div>
                    </div>
                </div>
        		<div class="save-btn-box"><input type="submit" class="gray-button" value="Save" /></div>
        		<div id="upload"></div>
        		<div id="message_box" class="regErrorMsg"></div>
        		</form>
            </div>
            
            <div id="added-widget-wrap">
            <div id="added_featured_institutes">
            	<?php echo html_entity_decode($slidesHtml);?>
            </div>
            </div>
            <form id="addFeaturedInstituteSections" action="/coursepages/CoursePageCms/addSection" method="post" onsubmit="return postSectionData(this);">
            <input type="hidden" name="courseHomePageId" id="sectionSubcatId"/>
            <input type="hidden" name="section_id" id="section_id"/>
            <input type="hidden" name="edited_link_id" id="link_id"/>
            <input type="hidden" name="link_order" id="link_order"/>
            <input type="hidden" name="section_order" id="section_order"/>
            <input type="hidden" name="updateSection" id="updateSection"/>
            <div id="add-widget-wrap">
                <div id="add-widget">
                    <div class="widget-header">
                        <h5>Add featured institutes</h5>
                    </div>
                    <div id="complete_section">
                    <div class="widget-content" style="background:none">
                    	<ul class="widget-form">
                            <li>
                                <label>Add Heading:</label>
                                <div class="form-field">
                                    <input maxlength="45" type="text" name="sectionHeading[]" id="sectionHeading" class="universal-txt-field" caption="section heading"/>
                                    <p class="form-hint">Max 45 characters</p>
                                    <div class="regErrorMsg"></div>
                                </div>
                            </li>
                            
                            <li>
                                <label>View more URL:</label>
                                <div class="form-field">
                                	<input caption="section url" id="sectionURL" name="sectionURL[]" value="" type="text" class="universal-txt-field" />
                                	<div class="regErrorMsg"></div>                                    
                                </div>
                            </li>
                            <li class="section_position_li" style="display: none;">
                                <label>Heading Position:</label>
                                <div class="form-field">
                                    <select name="sectionPosition[]" id="sectionPosition" class="universal-select" style="width:55px !important"><?php echo implode("", $position_string);?></select>
                                </div>
                            </li>
                        </ul>
                        <div class="clearFix"></div>
                    </div>
                    
                    <div class="widget-content">
                        <div>
                    	<ul class="widget-form">
                    	<li><input type="hidden" name="link_id[0][]" value=""/></li>
                            <li>
                                <label>Title:</label>
                                <div class="form-field">
                                    <input maxlength="30" caption="link title" name="linkTitle[0][]" value="" type="text" class="universal-txt-field" />
                                    <p class="form-hint">Max 30 characters</p>
                                    <div class="regErrorMsg"></div>
                                </div>
                            </li>
                            
                            <li>
                                <label>URL:</label>
                                <div class="form-field">
                                    <input caption="link url" name="landinURL[0][]" value="" type="text" class="universal-txt-field" />
                                    <div class="regErrorMsg"></div>
                                </div>
                            </li>
                            
                            <li class="link_position_li" style="display: none;">
                                <label>Link Position:</label>
                                <div class="form-field">
                                    <select name="displayOrder[0][]"  class="universal-select" style="width:55px !important"><?php echo implode("", $position_string);?></select>
                                </div>
                            </li>
                            
                            <li>
                                <label style="padding-top:1px">Open in new window:</label>
                                <div class="form-field">
                                    <input name="open_new_tab[0][0]" value="" type="checkbox" />
                                </div>
                            </li>
                        </ul>
                        </div>
                        <div class="clearFix"></div>
                        <div style="margin-left:157px"><a href="javascript:void(0);" onclick="cp_obj.addLink(this)">+Add more titles</a></div>
                    </div>
                    </div>
                    <div id="add_section_link" style="margin:15px 0 0 157px"><a href="javascript:void(0);" onclick="cp_obj.addSection(this)">+Add another heading</a></div>
                </div>
        		<div class="save-btn-box"><input type="submit" class="gray-button" value="Save" /></div>
            </div>
            <div id="upload_section"></div>
        	<div id="message_box_section" class="regErrorMsg"></div>
            </form>
            <div id="added-widget-wrap">
            	<div id="sections_html"> </div>
            </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('common/footer');?>

<script type="text/javascript">
	
	var cp_obj = new coursepageCMS();
	var slide_flow = 0;
	var slieshow_data = "";
	var section_add_flow = 0;
	var formSubmissionInProgress = false;
	function postFormData(obj) {		
		var elements_array = new Array('imageTitle','imageUrl','landingUrl','subcatId');
		var flag = 1;
		
		for(var i =0;i<elements_array.length;i++) {
			var returned_value = validateField(document.getElementById(elements_array[i]));
			flag = flag*returned_value; 
		}

		if(flag == 0) {
			return false;
		}
        if(formSubmissionInProgress) {
            return false;
        }
		
		var url = '/coursepages/CoursePageCms/addSlide/'+slide_flow;

        formSubmissionInProgress = true;
	    cp_obj.postFormData(obj,url,'upload','addSlide');

		return true;
	}
	
	function setSectionId() {
		$j('#sectionSubcatId').val($j('#subcatId').val());
                $j('#cms_feature_heading').html('Slide show on '+$j("#subcatId option[value='"+$j('#subcatId').val()+"']").text()+' Course Page');
                $j('#cms_feature_heading').show();
		cp_obj.getPreviouslyAddedContent($j('#subcatId').val());
	}
	var sectionSubmissionInProgress = false;
	function postSectionData(obj) {

		if(section_add_flow == 0 && $j('#updateSection').val() == '' && cp_obj.count_sections == cp_obj.max_number_section) {
			alert("Can't add further sections");
			return false;
		}
		
		var flag = 1;
				
		$j('#addFeaturedInstituteSections *').filter(':input').each(
			function(key,element) {
				
				element = $j(element);		
				if(trim(element.val()).length == 0) {
					
				if(element.attr('id') == 'sectionSubcatId') {
					$j('#subcatId_error').html('Please select a course page');
					flag = flag*0;					
				} else if((element.next().attr('class') == 'regErrorMsg')) {
					element.next().html('Please enter '+element.attr('caption'));			
					flag = flag*0;
				} else if(element.next().next().attr('class') == 'regErrorMsg') {
					element.next().next().html('Please enter '+element.attr('caption'));					
					flag = flag*0;
				}
				
			} else if(trim(element.val()).length >0 && element.attr('caption')=='link url') {
				var rtrn_val = validateUrl(element.val(),'link url');
				if(rtrn_val !== true) {
					element.next().html(rtrn_val);
					flag = flag*0;
				}					
			} else if(trim(element.val()).length >0 && element.attr('caption')=='section url') {
					
				var rtrn_val = validateUrl(element.val(),'section url');
				if(rtrn_val !== true) {
					element.next().html(rtrn_val);
					flag = flag*0;
				}
			}else if(trim(element.val()).length >0 && (element.attr('caption')=='section heading' || element.attr('caption')=='link title')) {
				if(validateForSpecialChar(trim(element.val()))) {
					flag = flag*1;
					element.next().html();
				} else {
					flag = flag*0;
					element.next().next().html("Please enter a valid image "+element.attr('caption')+" , only alphabets, number and special characters_-“%&‘:\"\' are allowed");
				}
			}
			}
		);
		if(flag == 0) {
			return false;
		} 
		if(sectionSubmissionInProgress) {
            return false;
        }
		var url = '/coursepages/CoursePageCms/addSection/'+section_add_flow;
		cp_obj.postFormData(obj,url,'upload_section','addSectionData');
		sectionSubmissionInProgress = true;
		return true;
	}
	
	function validateField(element) {
		
		try {
			
		var value = element.value.replace(/^\s*|\s*$/g,'');
		if(!value) {
			if(element.name=='myImage[]') {				
				 if(slide_flow == 1) {
					return 1;
				 }
	 		     $j("#"+element.id+"_error").html("Please upload slider image");
			} else {
				$j("#"+element.id+"_error").html("Please enter "+element.name.replace('_',' '));
			}
			return 0;
			 
		} else {
			if(element.id == 'landingUrl') {
				var rtrn_val = validateUrl(value,'url');
				if(rtrn_val !== true) {
					$j("#"+element.id+"_error").html(rtrn_val);
					return 0;
				}	
			} else if(element.id == 'imageTitle') {
					if(validateForSpecialChar(value)) {
						$j("#"+element.id+"_error").html("");
						return 1;
					} else {
						$j("#"+element.id+"_error").html("Please enter a valid image title, only alphabets, number and special characters_-“%&‘:\"\' are allowed");	
						return 0;
					}
			}
			$j("#"+element.id+"_error").html("");
			return 1;
		 
		}
		
		} catch(ex) {
			//
		}
	}

	function validateForSpecialChar(string) {
		var allowedChars = /^[a-zA-Z0-9;_\-“%&,()" ":"']+$/;
		var result = allowedChars.test(string);
		//alert(result);
		return result;
	}	
var formlist = [ "addFeaturedInstituteSections", "addFeaturedInstitutes" ];
if($j('#subcatId').val() == '') {
	$j(window).bind("load",formlist,cp_obj.disableFormElements);
}
</script>

