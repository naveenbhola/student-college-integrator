        <div class="abroad-cms-rt-box">
			<div class="abroad-cms-head" style="margin-top:0;">
            	<h2 class="abroad-sub-title">All Course</h2>
                <div class="flRt"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_COURSE?>" class="orange-btn" style="padding:6px 7px 8px">+ Add New Course</a></div>
            </div>
            <div class="search-section">
           		<div class="adv-search-sec">
                	<div class="cms-adv-box">
                		<form name="form_searchCourse" method="get" action="<?=$formURL?>">
				<div class="cms-search-box">
				     <i class="abroad-cms-sprite search-icon"></i>
				     <input name="searchCourse" id="searchCourse" type="text" style="<?=($searchTerm != '') ? 'color:black' : '' ?>" defaulttext="Search Course" onBlur="toggleDefaultText(this,'blur');" onfocus="toggleDefaultText(this,'focus');" value="<?=($searchTerm)?$searchTerm:"Search Course";?>" class="search-field"/>
				     <input type="hidden" name="status" value="<?=$displayDataStatus?>" />
				     <?php if($searchTerm != '')
					{ ?>
					<i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('searchCourse').value='';document.form_searchCourse.submit();"></i>
					<?php
					} ?>
				     <a href="javascript:void(0);" onclick="document.form_searchCourse.submit();" class="search-btn">Search</a>
				</div>
				</form>
                    	<a href="#" style="margin-top:5px; display:block">Advance Search</a>
                    </div>
                    <div class="flRt display-sec">
                    <ul>
                    	<li>Show:</li>
			<?php
				$activeClass = "all";
				if($displayDataStatus)
				
			?>
                    	<li class="<?=(!in_array($displayDataStatus,array('draft',ENT_SA_PRE_LIVE_STATUS)) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE."/?".$queryParams?>">All (<?=empty($totalResultCount[0]["all_count"])? 0 : $totalResultCount[0]["all_count"] ?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array(ENT_SA_PRE_LIVE_STATUS)) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE."/?status=".ENT_SA_PRE_LIVE_STATUS.$queryParams?>">Published (<?=empty($totalResultCount[0]["published_count"])? 0 : $totalResultCount[0]["published_count"]?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array('draft')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_COURSE."/?status=draft".$queryParams?>">Drafts (<?=empty($totalResultCount[0]["draft_count"])? 0 : $totalResultCount[0]["draft_count"]?>)</a></li>
                        
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
                        <th width="26%">
                        	<span class="flLt" style="margin-top:6px;">Department Name</span>
                        </th>
                        <th width="14%">
                        <span class="flLt" style="margin-top:6px;">Date</span>
                        </th>
                    </tr>
			<?php $index= $paginator->getLimitOffset() + 1;
				foreach($courseArr as $courseArrObj){
			?>
                    <tr>
                    	<td align="center"><?php echo $index++;?>.</td>
                        <td>
                        <p><?php echo  htmlspecialchars($courseArrObj['courseTitle']); ?></p>
		           <p class="cms-sub-cat"><?php echo $courseArrObj['course_level_1'];
							if($courseArrObj['parentCategory_name']){echo " ".$courseArrObj['parentCategory_name'];}
							if($courseArrObj['subCategory_name']){echo ", ".$courseArrObj['subCategory_name'];}	?></p>
                            <div class="edit-del-sec">
                            	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_EDIT_COURSE."/".$courseArrObj['course_id']?>">Edit</a>
                                <a class="cmsmargingLeft" href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_COURSE."/course/".$courseArrObj['course_id']?>">Clone </a>&nbsp;&nbsp;
				<?php if($usergroup == 'saAdmin' || $usergroup == 'saCMSLead'){?>
				     <a href="javascript:void(0);" onclick="delete_row('<?=ENT_SA_DELETE_COURSE?>',<?=$courseArrObj['course_id']?>)">Delete</a>
				<?php } ?>
				<span style="float:right">Form Completed : <strong><?php echo ($courseArrObj['profile_percentage_completion'])?$courseArrObj['profile_percentage_completion']:0;?>%</strong> </span>
                            </div>
                        </td>
                        <td>
                            <p><?php echo htmlspecialchars($courseArrObj['university_name']); ?></p>
                            <p class="cms-sub-cat"><?php echo htmlspecialchars($courseArrObj['city_name'].", ".$courseArrObj['country_name']);?></p>
                        </td>
                        <td>
                        	 <p><?php echo htmlspecialchars($courseArrObj['department_name']);?></p>
                        </td>
                        <td>
                        	<p class="cms-table-date"><?php echo $courseArrObj['date'];?></p>
				<?php if($courseArrObj['status'] == ENT_SA_PRE_LIVE_STATUS){?>
				<p class="publish-clr">Published</p>
				<?php }elseif($courseArrObj['status'] == 'draft'){?>
				<p class="draft-clr">Draft</p>
				<?php }?>
                        </td>
                    </tr>
		     <?php
				}
		    ?>
		    <tr>
				<?php if(empty($courseArr))
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
