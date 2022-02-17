        <div class="abroad-cms-rt-box">
			<div class="abroad-cms-head" style="margin-top:0;">
            	<h2 class="abroad-sub-title">All Courses</h2>
                <div class="flRt">
                	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_SNAPSHOT_COURSE?>" class="orange-btn" style="padding:6px 7px 8px">+ Add Single Snapshot Course</a>
                	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_BULK_SNAPSHOT_SOURES?>" class="orange-btn" style="padding:6px 7px 8px" >+ Bulk Upload Courses (Excel)</a></div>
            </div>
		
		 <script type="text/javascript">
            function toggleDefaultTextForSnapshotCourse(thisObj,e){
              if( $j(thisObj).attr("defaulttext") == $j(thisObj).val() && e == 'focus')
            	{
            	$j(thisObj).val("");
            	$j(thisObj).css("color","black");
            	}
            	if($j(thisObj).val() == "" && e == "blur")
            	{
            	$j(thisObj).val("Select Course");
            	$j(thisObj).css("color","");
            	}
            	} 

            </script>
			
			
			
            <div class="search-section">
           		<div class="adv-search-sec">
                	<div class="cms-adv-box">
				<form id="form_searchSnapshotCourse" name="form_searchSnapshotCourse" method="get" action="/listingPosting/AbroadListingPosting/viewSnapshotCourseListing">
				<div class="cms-search-box">
						<i class="abroad-cms-sprite search-icon"></i>
						<input type="text" id="snapshotCourse" name="snapshotCourse" style="<?=($snapshotCourse != '' && $snapshotCourse != 'Select Course') ? 'color:black' : '' ?>" defaulttext="Select Course" value="<?= ($snapshotCourse) ? $snapshotCourse : "Select Course";?>" onBlur="toggleDefaultTextForSnapshotCourse(this,'blur');" onfocus="toggleDefaultTextForSnapshotCourse(this,'focus');" class="search-field"/>
						 <?php if($snapshotCourse != '' && $snapshotCourse != 'Select Course')
							{ ?>
							<i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('snapshotCourse').value='';document.form_searchSnapshotCourse.submit();"></i>
							<?php
							} ?>
						<a href="#" onclick="document.form_searchSnapshotCourse.submit();" class="search-btn">Search</a>
				</div>
				</form>
                    	<!--<a href="#" style="margin-top:5px; display:block">Advance Search</a>-->
                    </div>
                    <div class="flRt display-sec">
                    <ul>
                    	<li>Show:</li>
                    	<li class="active"><!--<a href="#">All (<?php //echo $totalRecords;?>)</a>-->
			<b>All (<?php echo $totalRecords;?>)</b></li>
                    </ul>
                    <?php $this->load->view('listingPosting/paginator/paginationTopSection');?>
                    </div>
                    <div class="clearFix"></div>
                </div>
                <table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
                	<tr>
                        <th width="5%" align="center">
		            <span class="flLt" style="margin-top:6px;">S.No.</span>
			</th>
                        <th width="30%">
                            <span class="flLt" style="margin-top:6px;">Course Name</span>
                        </th>
                        <th width="25%">
                        	<span class="flLt" style="margin-top:6px;">University Name</span>
                        </th>
                        <th width="14%">
                        	<span class="flLt" style="margin-top:6px;">Date</span>
                        </th>
                        <th width="28%">Action</th>
                    </tr>
				<?php $index= $paginator->getLimitOffset() + 1;
				foreach($snapshotCourseArr as $snapshotCourse){
			?>
                    <tr>
                    	<td align="center"><?php echo $index++;?>.</td>
                        <td>
                            <p><?php echo htmlspecialchars($snapshotCourse['course_name']);?></p>
                            <p class="cms-sub-cat"><?php echo $snapshotCourse['course_type']." ".$snapshotCourse['parentCategory_name'].", ".$snapshotCourse['subCategory_name'];?></p>
                            <div class="edit-del-sec">
                                <?php if($snapshotCourse['snapshot_in_draft'] == "true"){ ?>
                                <a href="javascript:void(0);" onclick="return confirm('This snapshot course cannot be edited, it lies in the draft state..')">Edit</a>&nbsp;&nbsp;
                                <a href="javascript:void(0);" onclick="return confirm('This snapshot course cannot be deleted, it lies in the draft state.')">Delete</a>
                                 
                                <?php }else{ ?>
                                
                            	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_EDIT_SNAPSHOT_COURSE?>/<?=$snapshotCourse['course_id']?>">Edit</a>&nbsp;&nbsp;
                                <?php if($usergroup == 'saAdmin' || $usergroup == 'saCMSLead'){?>
				<a href="<?=ENT_SA_CMS_PATH.ENT_SA_DELETE_LISTING_SNAPSHOT_COURSE?>/<?=$snapshotCourse['course_id']?>" onclick="return confirm('Are you sure you want to delete ?')">Delete</a>
				<?php } ?>
                                <?php } ?>
                            </div>
                        </td>
                        <td>
				<p><?php echo htmlspecialchars($snapshotCourse['university_name']);?></p>
				<p class="cms-sub-cat"><?php echo htmlspecialchars($snapshotCourse['city_name'].", ".$snapshotCourse['country_name']);?></p>
                        </td>
                        <td>
                        	<p class="cms-table-date"><?php echo $snapshotCourse['date'];?></p>
                        </td>
                        <td>
                            <a href="/listingPosting/AbroadListingPosting/convertSnapshotCourseToDetailCourse?snapshotcourse_id=<?=$snapshotCourse['course_id']?>" class="gray-convrt-button" style="margin-top:3px;" >Convert to Detailed Course <span>&rsaquo;</span></a>
                        </td>
                    </tr>
		    <?php
				}
		    ?>
		    <tr>
				<?php if(empty($snapshotCourseArr))
				{ ?>
				<td colspan=5>
				<p class="no-found">No Data Found For This Search</p>
				</td>
				<?php } ?>
		    </tr>
                </table>
		
            </div>
	    <?php $this->load->view('listingPosting/paginator/paginationBottomSection');?>
	    
        <div class="clearFix"></div>
        </div>
