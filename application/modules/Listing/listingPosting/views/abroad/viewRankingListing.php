        <div class="abroad-cms-rt-box">
			<div class="abroad-cms-head" style="margin-top:0;">
            	<h2 class="abroad-sub-title">All Rankings</h2>
                <div class="flRt"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_RANKING?>" class="orange-btn" style="padding:6px 7px 8px">+ Add a New Ranking</a></div>
            </div>
            <div class="search-section">
           		<div class="adv-search-sec">
                	<div class="cms-adv-box">
			<form name="form_searchRank" method="get" action="<?=$formURL?>">
                		<div class="cms-search-box">
				<i class="abroad-cms-sprite search-icon"></i>
				<input name="searchRank" id="searchRank" type="text" style="<?=($searchTerm != '') ? 'color:black' : '' ?>" defaulttext="Search Ranking" onBlur="toggleDefaultText(this,'blur');" onfocus="toggleDefaultText(this,'focus');" value="<?=($searchTerm)?$searchTerm:"Search Ranking";?>" class="search-field"/>
				<input type="hidden" name="status" value="<?=$displayDataStatus?>" />
				<?php if($searchTerm != '')
				    { ?>
				         <i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('searchRank').value='';document.form_searchRank.submit();"></i>
				    <?php } ?>
				    <a href="javascript:void(0);" onclick="document.form_searchRank.submit();" class="search-btn">Search</a>
				</div>
			</form>
                    </div>
                    <div class="flRt display-sec">
                    <ul>
                    	<li>Show:</li>
			<?php
				$activeClass = "all";
				if($displayDataStatus)
				
			?>
                    	<li class="<?=(!in_array($displayDataStatus,array('draft',ENT_SA_PRE_LIVE_STATUS)) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_RANKING."/?".$queryParams?>">All (<?=empty($totalResultCount[0]["all_count"])? 0 : $totalResultCount[0]["all_count"] ?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array(ENT_SA_PRE_LIVE_STATUS)) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_RANKING."/?status=".ENT_SA_PRE_LIVE_STATUS.$queryParams?>">Published (<?=empty($totalResultCount[0]["published_count"])? 0 : $totalResultCount[0]["published_count"]?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array('draft')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_PATH.ENT_SA_VIEW_LISTING_RANKING."/?status=draft".$queryParams?>">Drafts (<?=empty($totalResultCount[0]["draft_count"])? 0 : $totalResultCount[0]["draft_count"]?>)</a></li>
                        
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
                            <span class="flLt" style="margin-top:6px;">Ranking Name</span>
                        </th>
                        <th width="20%">
                        	<span class="flLt" style="margin-top:6px;">Country Name</span>
                        </th>
                        <th width="20%">
                        	<span class="flLt" style="margin-top:6px;">Category / Desired Course</span>
                        </th>
                        <th width="15%">
                        <span class="flLt" style="margin-top:6px;">Date</span>
                        </th>
                    </tr>
			<?php $index= $paginator->getLimitOffset() + 1;
				foreach($rankArr as $rankArrObj){
			?>
                    <tr>
                    	<td align="center"><?php echo $index++;?>.</td>
                        <td>
                            <p class="cms-associated-cat"><?php echo htmlspecialchars($rankArrObj['ranking_name']); ?></p>
                            <div class="edit-del-sec">
                            	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_EDIT_RANKING."/".$rankArrObj['ranking_page_id']?>">Edit</a>&nbsp;&nbsp;
                                <?php if($usergroup == 'saAdmin' || $usergroup == 'saCMSLead'){?>
				     <a href="javascript:void(0);" onclick="delete_row('<?=ENT_SA_DELETE_LISTING_RANKING?>',<?=$rankArrObj['ranking_page_id']?>)">Delete</a>
				<?php }?>
				<span style="float:right">Type:<?php echo ucfirst($rankArrObj['type']);?></span>
                            </div>
                        </td>
                        <td>
                            <p class="<?php echo ($rankArrObj['country_name'])?"cms-associated-cat":"not-mapped-univ"; ?>"><?php echo ($rankArrObj['country_name'])?$rankArrObj['country_name']:"Not Mapped"; ?></p>
                         
                        </td>
                        <td>
                        	 <p class="<?php echo ($rankArrObj['category_name'])?"cms-associated-cat":"not-mapped-univ"; ?>"><?php echo ($rankArrObj['category_name'])?$rankArrObj['category_name']:"Not Mapped"; ?></p>
                        </td>
                        <td>
                        	<p class="cms-table-date"><?php echo $rankArrObj['last_date']; ?></p>
				<?php if($rankArrObj['status'] == ENT_SA_PRE_LIVE_STATUS){?>
				<p class="publish-clr">Published</p>
				<?php }elseif($rankArrObj['status'] == 'draft'){?>
				<p class="draft-clr">Draft</p>
				<?php }?>
                        </td>
                    </tr>
		    <?php
				}
		    ?>
		    
		     <tr>
				<?php if(empty($rankArr))
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
