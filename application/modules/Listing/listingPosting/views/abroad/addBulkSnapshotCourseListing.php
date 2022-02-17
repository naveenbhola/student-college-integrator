        <div class="abroad-cms-rt-box">
        	
           
	<?php
		$displayData["breadCrumb"] 	= array(array("text" => "All Courses", "url" => ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE),
											array("text" => "Upload Snapshot Course", "url" => "")
									);
		$displayData["pageTitle"]  	= "Upload Snapshot Courses";
		if(!empty($lastuploadedBy)){
		$displayData["lastUpdatedInfo"] = array("title"    => "Last CSV uploaded",
                             "date"     => $lastUploaded,
                             "username" => $lastuploadedBy);
		}
	
		$this->load->view("listingPosting/abroad/abroadCMSPageTitle", $displayData);
	?>
    <?php if(!empty($uploadErrorMsg)) {?>        
   <form action ="<?php echo ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_BULK_SNAPSHOT_SOURES?>" name = "form_addBulkSnapshotCourseForm" method="post"
enctype="multipart/form-data">
            <div class="clear-width">
            	<h3 class="section-title">Upload through .csv file</h3>
                <div class="clear-width table-cont">
                <p class="section-sub-title">Please ensure that following mandatory columns are present in your csv file with '|' as a seperator:</p>
                <table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
                	
                    <tr>
                    	<td>COUNTRY ID*</td>
                        <td>UNIVERSITY NAME*</td>
                        <td>COURSE EXACT NAME*</td>
                        <td>COURSE TYPE*</td>
                        <td>PARENT CATEGORY*</td>
                        <td>CHILD CATEGORY*</td>
                        <td>COURSE WEBSITE LINK*</td>
                    </tr> 
                </table>
                </div>
                <div class="cms-form-wrapper clear-width">
                <div class="cms-form-wrap">
                    <ul>
                        <li>

                           <label style="padding-top:4px">Upload CSV file* : </label>
                            <div class="cms-fields">
                            	<input caption="CSV file" validationType="file"  required = true   type="file" name="file" id="file_addBulkSnapshotCourseForm" />
                                <div style="display: none" class="errorMsg" id="file_<?=$formName?>_error"></div>
                                <p>First row of csv file will be ignored</p>
                            </div>
                            
                         <?php 	if(!empty($uploadErrorMsg) && $uploadErrorMsg != "Please upload a CSV File"){ ?>
                         
							<div id="errorMsg" style="color:red; margin:5px 0 0 252px;"><?php echo $uploadErrorMsg;?></div>
						<?php
							}
						?>
                        </li>
                        <li>
						<label>User Comments*: </label>
						<div class="cms-fields">
							<textarea id="comments_<?=$formName?>" name="comment" caption="comment" validationType="str"  maxlength="500" minlength="3" required=true class="cms-textarea" style="width:75%;"></textarea>
							<div style="display: none" class="errorMsg" id="comments_<?=$formName?>_error"></div>
						</div>
					</li>
                    </ul>
                </div>
                </div>
            </div>
            <div class="button-wrap">
                <a href="#" onclick ="validateBulkAdditionFrom(this, 'addBulkSnapshotCourseForm');"  class="orange-btn">Upload CSV File</a>
                <a href="javascript:void(0);" onclick="cancelAction('<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE?>');" class="cancel-btn">Cancel</a>
            </div>
             </form>
           <?php } else{ ?>
           
             <div class="clear-width">
            	<h3 class="section-title">Upload through .csv file</h3>
                <div class="cms-msg-section">
                	<h4 class="succes-head">Upload Summary</h4>
                    <p class="section-sub-title"><i class="abroad-cms-sprite success-icon"></i> Successfully uploaded <?php echo $fileName?></p>
                    <div class="success-msg">
                    	
                    	<?php if($showErrorLogLink) {?>
                    	<p class="font-15"><strong><?php echo $noOfSnapshotCoursesInserted;?> Course<?php if($noOfSnapshotCoursesInserted > 1) echo "s";?> successfully added!</strong></p>
                    	<p class="font-15"><strong><?php echo $noOfCoursesFailed;?> Course<?php if($noOfCoursesFailed > 1) echo "s";?> failed (<a href = "<?php echo ENT_SA_CMS_PATH.downloadBulkSnapshotUploadFile."/".$fileName."_log";?>">refer to error log</a>). </strong></p>
                    	<?php }?>
                        
                     
                        <p>Done! Go back to <a href="<?php echo ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_SNAPSHOT_COURSE?>">Snapshot courses</a> &nbsp;OR&nbsp; <a href="">Upload another CSV file</a></p>
                    </div>
                    <?php 
              
                    
                    ?>
                    
                </div>
            </div>
           <?php } ?>  
        </div>
        <div class="clearFix"></div>