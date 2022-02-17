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
                        <li class="active"><a href="javascript:void(0);">Widget Position</a></li>
			<li><a href="/coursepages/CoursePageCms/restrictContent">Restrict Content</a></li>
                    </ul>
                </div>  
                
    			<div class="clearFix"></div>  
                <div class="faq-tab-contents">
                    <!-- Widget Position Tab STARTS here -->
            		<div class="position-tab-contents">
            		<div class="add-widget-title">
                    	<h4>Course Widgets in &nbsp;<select id="subcategory_id" class="universal-select" onchange="resetWidgetData(this);" style="width:180px !important"><?php echo implode("", $string_array);?></select></h4>
                	</div>	
                	<div id="widget_table_order" style="display:none;">                      
                     </div>
                </div>
                	<!-- Widget Position Tab ENDS here -->
            	</div>
        	</div>                                       
        </div>
    </div>
</div>
<?php $this->load->view('common/footer');?>