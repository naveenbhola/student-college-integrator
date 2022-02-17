<div class="abroad-cms-rt-box">
	<div class="abroad-cms-head" style="margin-top:0;">
            <h2 class="abroad-sub-title">All Upgraded Consultants</h2>
            <div class="flRt"><a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CLIENT_CONSULTANT_SUBSCRIPTION?>" class="orange-btn" style="padding:6px 7px 8px">Upgrade New Consultant</a></div>
        </div>
        <div class="search-section">
            <div class="adv-search-sec">
                <div class="cms-adv-box">
                    <form name="searchConsultantId" action="<?=$URL?>">
                	<div class="cms-search-box" style="width:250px;">
                        <i class="abroad-cms-sprite search-icon"></i>
                    	<input type="text" name="q" id="q" style="<?=($searchTerm != '') ? 'color:black' : '' ?> ; width:150px;" defaulttext="Search Consultant ID" onBlur="toggleDefaultText(this,'blur');" onfocus="toggleDefaultText(this,'focus');"
                                value="<?=($searchTerm)?htmlentities($searchTerm):"Search Consultant ID";?>" class="search-field"/>
			<?php if($searchTerm != '')
			{ ?>
                            <i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('q').value='';document.searchConsultantId.submit();"></i>
			<?php
			} ?>
                        <a href="javascript:void(0);" onclick="document.searchConsultantId.submit();" class="search-btn">Search</a>
                        </div>
		</form>
		</div>
			
                <div class="flRt display-sec">
                    <ul>
                    	<li>Show:</li>
			<?php   $activeClass = "all";  ?>
                    	<li class="active">All (<?php echo $totalCount;?>)</li>
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
                        <th width="20%">
                            <span class="flLt" style="margin-top:6px;">Consultant ID</span>
                        </th>
                        <th width="20%">
                            <span class="flLt" style="margin-top:6px;">Client ID</span>
                        </th>
                        <th width="15%">
                            <span class="flLt" style="margin-top:6px;">CPR</span>
                        </th>
                        <th width="10%">
                            <span class="flLt" style="margin-top:6px;">Credit Left</span>
                        </th>
                        <th width="15%">
                            <span class="flLt" style="margin-top:6px;">End Date</span>
                        </th>
                        <th width="15%">
                            <span class="flLt" style="margin-top:6px;">Created Date</span>
                        </th>
                    </tr>
			
                    <?php
                    if(empty($consultantData))
                    { ?>
                    <tr>
                    	<td align="center">&nbsp;</td>
			<td colspan=6><i>No Results Found !!!</i></td>
                    </tr>
                    <?php }
                      
                    $count = $paginator->getLimitOffset() + 1;
                    foreach($consultantData as $key=>$value)
                    {  ?>
                    
                    <tr>
                    	<td align="center"><?=($count++)?>.</td>
                        <td>
                            <p><?=htmlentities($value["consultantId"]) ?></p>
                            <div class="edit-del-sec">
                                <a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_EDIT_CLIENT_CONSULTANT_SUBSCRIPTION.'?mappingId='.$value['mappingId']?>">Edit</a>
                            </div>
                        </td>
                        
                        <td>
                            <p class="cms-associated-cat"><?=htmlentities($value["clientId"]) ?></p>
                        </td>
                        <td>
                            <p class="cms-associated-cat"><?=htmlentities($value["CPR"]) ?></p>
                        </td>
                        <td>
                            <p class="cms-associated-cat"><?=htmlentities($subscriptionData[$value["subscriptionId"]]["BaseProdRemainingQuantity"]) ?></p>
                        </td>
                          <td>

                            <p class="cms-table-date"><?=(date("d M Y",strtotime($subscriptionData[$value["subscriptionId"]]["SubscriptionEndDate"])))?></p>
                            <?php if($subscriptionData[$value["subscriptionId"]]["Status"]=="INACTIVE")
                            { ?>
                                <div style="color:#ff0000">EXPIRED</div>
                            <?php }
                            ?>
                        </td>
                        <td>
                            <p class="cms-table-date"><?=(date("d M Y",strtotime($value["createdAt"])))?></p>
                        </td>
                </tr>
		<?php }	?>
                </table>
            </div>
	    
            <?php $this->load->view('listingPosting/paginator/paginationBottomSection');?>

        </div>