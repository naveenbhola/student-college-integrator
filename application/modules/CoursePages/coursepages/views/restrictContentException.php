<?php

if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}

?>

<?php

	$string_array = array();
	$string_array[] = "<option value='' selected>Select Course Page</option>";
	
	foreach ($COURSE_HOME_DICTIONARY as $key =>$subcat) {
		$string_array[] = "<option value='".$key."'>".$subcat['Name']."</option>";
	}
?>

<div id="content-wrapper">
	<div class="wrapperFxd">
    	<div id="content-child-wrap">
        	<div id="course-cms-wrapper">                
                <div class="faq-widget-tab">
                    <ul>
                        <li><a href="/coursepages/CoursePageCms/featuredInstitute">Widget Information</a></li>
                        <li><a href="/coursepages/CoursePageCms/reorderCoursepageWidgets">Widget Position</a></li>
                        <li class="active"><a href="javascript:void(0);">Restrict Content</a></li>
                    </ul>
                </div>  
                
    		<div class="clearFix"></div>  
                <div class="faq-tab-contents">
                    <!-- Widget Position Tab STARTS here -->
            	    <div class="position-tab-contents" id="add-widget">
			<div class="widget-content">
            		 <div class="form-field" style="padding-top: 10px; margin-left:120px;">
                            <label style="display:inline-block; width: 110px; text-align: right;">Course Widgets in &nbsp;</label>
			    <select id="courseHomePageId" class="universal-select" onchange="" style="width:180px !important"><?php echo implode("", $string_array);?></select>
                	</div>
			<div id="subcategory_id_error" class="regErrorMsg"></div>  
                        <div class="form-field" style="padding-top: 10px; margin-left:120px;">
                            <label style="display:inline-block; width: 110px;text-align: right;">Content Type &nbsp;</label>
                            <select id="content_type" class="universal-select" onchange="" style="width:180px !important">
                                <option value='' selected>Select Content Type</option>
                                <option value="article">Articles</option>
                                <option value="discussion">Discussions</option>
                                <option value="qna">Questions</option>
                            </select>
                        </div>
			<div id="content_type_error" class="regErrorMsg"></div>  
                        <div class="form-field" style="padding-top: 10px; margin-left:120px;">
                            <label style="display:inline-block; width: 110px;text-align: right;">Flag Type &nbsp;</label>
                            <select id="noise_flag" class="universal-select" onchange="" style="width:180px !important">
                                <option value='' selected>Select Flag Type</option>
                                <!--<option value="boost">Boost</option>-->
                                <option value="noise">Noise</option>
                                <option value="nullify">Nullify</option>
                            </select>
                        </div>
			<div id="noise_flag_error" class="regErrorMsg"></div>
                        <div class="form-field" style="padding-top: 10px; margin-left:120px;">
                            <label style="display:inline-block; width: 110px;text-align: right;">Content ID &nbsp;</label>
                            <input maxlength="15" name="contentId" id="contentId" type="text" value="" class="universal-txt-field" style="width: 18%" />
                        </div>
			<div id="contentId_error" class="regErrorMsg"></div>
			
			</div>
			<input type="submit" style="width:90px;margin:25px 0 10px 240px;" class="gray-button" value="Save" onclick="saveRestrictedContent()">
			</div>
                    </div>
                    
                                        
                	<!-- Widget Position Tab ENDS here -->
            	</div>
        	</div>                                       
        </div>
    </div>
</div>
<?php $this->load->view('common/footer');?>
<script>
	function confirmToRestrict(args) {
		if (confirm('Do You really want to proceed with this request.. ??')) {
			saveRestrictedContent();
		}else{
			alert('Your request has been discarded.');
		}
	}
</script>