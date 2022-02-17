        <div class="abroad-cms-rt-box">
			<div class="abroad-cms-head" style="margin-top:0;">
            	<h2 class="abroad-sub-title">All Universities</h2>
                <div class="flRt"><a href="/listingPosting/AbroadListingPosting/addUniversityForm" class="orange-btn" style="padding:6px 7px 8px">+ Add New University</a></div>
            </div>
            <div class="search-section">
           		<div class="adv-search-sec">
                	<div class="cms-adv-box">
		<form name="searchUniversity" action="<?=$URL?>">
                		<div class="cms-search-box">
                        <i class="abroad-cms-sprite search-icon"></i>
                    	<input type="text" name="q" id="q" style="<?=($searchTerm != '') ? 'color:black' : '' ?>" defaulttext="Search University" onBlur="toggleDefaultText(this,'blur');" onfocus="toggleDefaultText(this,'focus');" value="<?=($searchTerm)?$searchTerm:"Search University";?>" class="search-field"/>
			<?php if($searchTerm != '')
			{ ?>
		        <i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('q').value='';document.searchUniversity.submit();"></i>
			<?php
			} ?>
                        <a href="javascript:void(0);" onclick="document.searchUniversity.submit();" class="search-btn">Search</a>
				</div>
				<a href="javascript:void(0);" onclick="document.searchUniversity.submit();" style="float: left; width: 90px; margin-top:7px;">Advance Search</a>
		</form>
		</div>
			
                    <div class="flRt display-sec">
                    <ul>
                    	<li>Show:</li>
			<?php
				$activeClass = "all";
				if($displayDataStatus)
				
			?>
                    	<li class="<?=(!in_array($displayDataStatus,array('draft','published')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_UNIVERSITY.$queryParams?>">All (<?=empty($totalResultCount["all_count"])? 0 : $totalResultCount["all_count"] ?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array('published')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_UNIVERSITY."/published".$queryParams?>">Published (<?=empty($totalResultCount["published_count"])? 0 : $totalResultCount["published_count"]?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array('draft')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_UNIVERSITY."/draft".$queryParams?>">Drafts (<?=empty($totalResultCount["draft_count"])? 0 : $totalResultCount["draft_count"]?>)</a></li>
                        
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
                        <th width="40%">
                            <span class="flLt" style="margin-top:6px;">University Name</span>
                        </th>
                        <th width="20%">
                        	<span class="flLt" style="margin-top:6px;">No. associated <br />Departments</span>
                        </th>
                        <th width="20%">
                        	<span class="flLt" style="margin-top:6px;">No. associated <br />Course</span>
                        </th>
                        <th width="15%">
                        <span class="flLt" style="margin-top:6px;">Date</span>
                        </th>
                    </tr>
			
		<?php
		if(empty($reportData))
		{
		?>
		<tr>
                    	<td align="center">&nbsp;</td>
			<td colspan=4><i>No Results Found !!!</i></td>
		</tr>
		<?php
		}
		$count = $paginator->getLimitOffset() + 1;
		foreach($reportData as $key=>$value)
		{
		?>
		<tr>
                    	<td align="center"><?=($count++)?>.</td>
                        <td>
                            <p><?=htmlspecialchars($value["universityName"]) ?></p>
                            <p class="cms-sub-cat"><?=htmlspecialchars($value["cityName"].($value["stateName"]?"(".$value["stateName"].")":"") ) ?><?=$value["cityName"]?", ":""?><?=$value["countryName"] ?></p>
                            <div class="edit-del-sec">
                            	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_EDIT_UNIVERSITY.'?uniId='.$value["universityId"]?>">Edit</a>&nbsp;&nbsp;
				<?php
						// show delete link only to SA admin
						if($usergroup == 'saAdmin' || $usergroup == 'saCMSLead')
						{
						?>
								<a href="javascript:void(0);" onclick="delete_row('<?=ENT_SA_DELETE_UNIVERSITY?>',<?=$value["universityId"]?>)">Delete</a>
						<?php
						}
							
				?>
                                <span style="float:right">Form Completed : <strong><?=$value["profileCompletion"]?>%</strong> </span>
                            </div>
                        </td>
                        <td>
                            <p class="cms-associated-cat"><?=$value["institute_num"]?></p>
			    <?php if($value["status"] != 'draft' && $value["universityType"] != "college")
			    { ?>
                        	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_DEPARTMENT.'?uniId='.$value["universityId"]?>">Add new department</a>
				<?php
			    }
			    ?>
			    
                        </td>
                        <td>
                        	 <p class="cms-associated-cat"><?=$value["course_num"]?></p>
		             <?php if($value["status"] != 'draft')
			       { ?>
                        	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_COURSE.'/university/'.$value["universityId"]?>">Add new Course</a>
				<?php }
				?>
                        </td>
                        <td>
                        	<p class="cms-table-date"><?=(date("d M y",strtotime($value["submitDate"])))?></p>
				<?php
				   if( $value["status"] == ENT_SA_PRE_LIVE_STATUS)
				      echo "<p class=\"publish-clr\">Published</p>";
				   else if( $value["status"] == 'draft')
				      echo "<p class=\"draft-clr\">Draft</p>";
				?>
                            
                        </td>
                </tr>

		<?php
		}
		
		?>
                </table>
            </div>
	                <?php $this->load->view('listingPosting/paginator/paginationBottomSection');?>

        <div class="clearFix"></div>
        </div>
