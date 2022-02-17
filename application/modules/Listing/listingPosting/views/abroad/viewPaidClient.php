        <div class="abroad-cms-rt-box">
			<div class="abroad-cms-head" style="margin-top:0;">
            	<h2 class="abroad-sub-title">All Upgraded/Downgraded Courses</h2>
                <div class="flRt"><a href="/listingPosting/AbroadListingPosting/addPaidClient" class="orange-btn" style="padding:6px 7px 8px">+ Upgrade/Downgrade course</a></div>
            </div>
            <div class="search-section">
           		<div class="adv-search-sec">
                	<div class="cms-adv-box">
		<form name="searchClient" action="<?=$URL?>">
                		<div class="cms-search-box">
                        <i class="abroad-cms-sprite search-icon"></i>
                    	<input type="text" name="q" id="q" style="<?=($searchTerm != '') ? 'color:black' : '' ?>" defaulttext="Search Upgraded Course" onBlur="toggleDefaultText(this,'blur');" onfocus="toggleDefaultText(this,'focus');" value="<?=($searchTerm)?$searchTerm:"Search Upgraded Course";?>" class="search-field"/>
			<?php if($searchTerm != '')
			{ ?>
		        <i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('q').value='';document.searchClient.submit();"></i>
			<?php
			} ?>
                        <a href="javascript:void(0);" onclick="document.searchClient.submit();" class="search-btn">Search</a>
				</div>
				<!--<a href="javascript:void(0);" onclick="document.searchClient.submit();" style="float: left; width: 90px; margin-top:7px;">Advance Search</a>-->
		</form>
		</div>
			
                    <div class="flRt display-sec">
				<?php if($totalCount>0) {?>
				<ul> 
				    <li>Show:</li>
				    <li class="active">All (<?php echo $totalCount;?>)</li>
			   
				</ul>
				<?php }?>
                    <!--<ul>
                    	<li>Show:</li>
			<?php
				$activeClass = "all";
				if($displayDataStatus)
				
			?>
                    	<li class="<?=(!in_array($displayDataStatus,array('draft','published')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_PAID_CLIENT.$queryParams?>">All (<?=empty($totalResultCount["all_count"])? 0 : $totalResultCount["all_count"] ?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array('published')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_PAID_CLIENT."/published".$queryParams?>">Published (<?=empty($totalResultCount["published_count"])? 0 : $totalResultCount["published_count"]?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array('draft')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_PAID_CLIENT."/draft".$queryParams?>">Drafts (<?=empty($totalResultCount["draft_count"])? 0 : $totalResultCount["draft_count"]?>)</a></li>
                        
                    </ul>-->
		
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
                            <span class="flLt" style="margin-top:6px;">Course Name & ID</span>
                        </th>
                        <th width="20%">
                        	<span class="flLt" style="margin-top:6px;">Client Name & ID</span>
                        </th>
                        <th width="20%">
                        	<span class="flLt" style="margin-top:6px;">Subscription Details</span>
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
                            <p><?=htmlspecialchars($value["course_name"]) ?></p>
                            <p class="cms-sub-cat">Course ID: <?=htmlspecialchars($value["course_id"])?></p>
                            <div class="edit-del-sec">
                            	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_EDIT_PAID_CLIENT.'?course_id='.$value["course_id"]?>">Edit</a>&nbsp;&nbsp;
                            </div>
                        </td>
                        <td>
                            <p class="cms-associated-cat"><?=$value["client_name"]?></p>
                            <p class="cms-sub-cat">Client ID: <?=htmlspecialchars($value["client_id"])?></p>
                        </td>
                        <td>
                        	 <p class="cms-associated-cat"><?=$value["subscription_id"]?></p>
                        	 <p class="cms-associated-cat">(EXP : <?=date("d M y",strtotime($value["expiry_date"]));?>)</p>
                        </td>
                        <td>
                        	<p class="cms-table-date"><?=$value["subscription_consume_date"]=="0000-00-00 00:00:00"?"--":(date("d M y",strtotime($value["subscription_consume_date"])))?></p>
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
