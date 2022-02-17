    <div class="abroad-cms-rt-box">
	<div class="abroad-cms-head" style="margin-top:0;">
            <h2 class="abroad-sub-title">All Consultants</h2>
            <div class="flRt"><a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT?>" class="orange-btn" style="padding:6px 7px 8px">+ Add New Consultant</a></div>
        </div>
        <div class="search-section">
            <div class="adv-search-sec">
                <div class="cms-adv-box">
                    <form name="searchConsultant" action="<?=$URL?>">
                	<div class="cms-search-box" style="width:250px;">
                        <i class="abroad-cms-sprite search-icon"></i>
                    	<input type="text" name="q" id="q" style="<?=($searchTerm != '') ? 'color:black' : '' ?> ; width:130px;" defaulttext="Search Consultants" onBlur="toggleDefaultText(this,'blur');" onfocus="toggleDefaultText(this,'focus');"
                                value="<?=($searchTerm)?$searchTerm:"Search Consultants";?>" class="search-field"/>
			<?php if($searchTerm != '')
			{ ?>
                            <i class="abroad-cms-sprite remove-gray-icon" title="Reset Search" onclick="document.getElementById('q').value='';document.searchConsultant.submit();"></i>
			<?php
			} ?>
                        <a href="javascript:void(0);" onclick="document.searchConsultant.submit();" class="search-btn">Search</a>
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
                    	<li class="<?=(!in_array($displayDataStatus,array('draft','published')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE.$urlParams?>">All (<?=empty($resultCount["all_count"])? 0 : $resultCount["all_count"] ?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array('published')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE."/published".$urlParams?>">Published (<?=empty($resultCount["published_count"])? 0 : $resultCount["published_count"]?>)</a></li>
                        <li><span class="cms-seperator"> | </span></li>
                        <li class="<?=(in_array($displayDataStatus,array('draft')) ? "active" : "")?>"><a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_VIEW_CONSULTANT_TABLE."/draft".$urlParams?>">Drafts (<?=empty($resultCount["draft_count"])? 0 : $resultCount["draft_count"]?>)</a></li>
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
                            <span class="flLt" style="margin-top:6px;">Consultant Name</span>
                        </th>
                        <th width="30%">
                            <span class="flLt" style="margin-top:6px;">Locations(Head Office, Others)</span>
                        </th>
                        <th width="15%">
                            <span class="flLt" style="margin-top:6px;">Updated on</span>
                        </th>
                    </tr>
			
                    <?php
                    if(empty($consultantData))
                    { ?>
                    <tr>
                    	<td align="center">&nbsp;</td>
			<td colspan=4><i>No Results Found !!!</i></td>
                    </tr>
                    <?php }
                      
                    $count = $paginator->getLimitOffset() + 1;
                    foreach($consultantData as $key=>$value)
                    {  ?>
                    
                    <tr>
                    	<td align="center"><?=($count++)?>.</td>
                        <td>
                            <p><?=htmlentities($value["name"]) ?></p>
                            <div class="edit-del-sec">
                                <a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_EDIT_CONSULTANT.'?consultantId='.$value["consultantId"] ?>">Edit</a>&nbsp;&nbsp;
                                <?php
                                    // show delete link only to SA admin
                                    if($usergroup == 'saAdmin' || $usergroup == 'saCMSLead')
                                { ?>                                
                                <a href="javascript:void(0);" onclick="deleteConsultant(<?=$value["consultantId"]?>,'<?=  base64_encode($value["name"])?>');">Delete</a>
                                <?php } ?>
                            </div>
                        </td>
                        
                        <td>
                            <p class="cms-associated-cat">
			    <?php
			          $locations = $value["totallocations"]-1;
				  if($locations >0)
				  {
				    echo htmlentities($value["locationName"]);
				    echo ", +".($locations)." more";
			    ?>  
			    </p>
			    <a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_LOCATION.'/'.$value["consultantId"].'?all=1' ?>">View All</a>
			    <span class="cms-seperator"> | </span>
			    <?php }
			    else if($locations ==0)
				  {
				    echo htmlentities($value["locationName"]);
			    ?>  
			    </p>
			    <a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_LOCATION.'/'.$value["consultantId"].'?all=1' ?>">View All</a>
			    <span class="cms-seperator"> | </span>
			    <?php  }
			    else
			    {
				echo "0 location";
			    ?>
			    </p>
			    <?php } ?>
		            <a href="<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_LOCATION.'/'.$value["consultantId"]?>">Add New Location</a>
			    
                        </td>
                        <td>
                            <p class="cms-table-date"><?=(date("d M Y",strtotime($value["modifiedAt"])))?></p>
			    <?php
			        if( $value["status"] == ENT_SA_PRE_LIVE_STATUS){ ?>
			           <p class="publish-clr">Published</p>
                                   
			    <?php
                                }
                                else if( $value["status"] == 'draft')  {?>
				    <p class="draft-clr">Draft</p>
                                    
                            <?php } ?>		    
                        </td>
                </tr>

		<?php }	?>
                </table>
            </div>
	    
            <?php $this->load->view('listingPosting/paginator/paginationBottomSection');?>

        <div class="clearFix"></div>
                <script>
                        function deleteConsultant(consultantId,consultantName){
                                deleteMsg = base64_decode(consultantName)+" will be deleted from the system along with its all locations/branches, universities mapped & student profiles. This cannot be undone.";
                                var resp        = confirm(deleteMsg);
                                var url         = "/consultantPosting/ConsultantPosting/deleteConsultant";
                                if(resp == true){
                                        var data = "consultantId="+consultantId;
                                        $j.ajax({
                                                type    : 'POST',
                                                url     : url,
                                                data    : data,
                                                success : function(response){
                                                                if(response == 1){
                                                                        alert("Successfully Deleted !!");
                                                                        location.reload();
                                                                }else if(response == 0){
                                                                        alert("This consultant has active subscription. It cannot be deleted.");
                                                                }else if(response == -1){
                                                                        alert("Something went wrong !!");
                                                                }else if(response == 'disallowedaccess'){
                                                                    window.location = '<?=SHIKSHA_STUDYABROAD_HOME?>/enterprise/Enterprise/disallowedAccess';
                                                                }else if(response == 'notloggedin'){
                                                                    window.location = '<?=SHIKSHA_STUDYABROAD_HOME?>/enterprise/Enterprise/loginEnterprise';
                                                                }
                                                        },
                                                error   : function(response){
                                                                alert("Something went wrong !!");
                                                                location.reload();
                                                        }
                                        });
                                }
                            }
                </script>
        </div>
