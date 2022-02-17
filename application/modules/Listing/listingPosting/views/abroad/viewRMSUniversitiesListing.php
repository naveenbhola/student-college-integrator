        <div class="abroad-cms-rt-box">
			<div class="abroad-cms-head" style="margin-top:0;">
            	<h2 class="abroad-sub-title">All Mapped Counsellors</h2>
                <div class="flRt"><a href="/listingPosting/AbroadListingPosting/addRMSUniversities" class="orange-btn" style="padding:6px 7px 8px">+ Create New Mapping</a></div>
            </div>
                
                <table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
                	<tr>
                        <th width="5%" align="center">
				<span class="flLt" style="margin-top:6px;">S.No.</span>
			</th>
                        <th width="35%">
                            <span class="flLt" style="margin-top:6px;">Counsellor Name</span>
                        </th>
                        <th width="25%">
                        	<span class="flLt" style="margin-top:6px;">No. of Universities Mapped</span>
                        </th>
                        <th width="20%">
                        	<span class="flLt" style="margin-top:6px;">Response Count</span>
                        </th>
                        <th width="20%">
				 <span class="flLt" style="margin-top:6px;">Updated on</span>
			</th>
                    </tr>
			
		<?php
		if(empty($reportData))
		{
		?>
		<tr>
                    	<td align="center">&nbsp;</td>
			<td colspan=5><i>No Results Found !!!</i></td>
		</tr>		
		<?php
		}
		$count = 1; 
		foreach($reportData as $key=>$value)
		{
		?>
		<tr>
                    	<td align="center"><?=($count++)?>.</td>
                        <td>
			 <div style="word-wrap:break-word; width:250px;">
                            <p><?=htmlspecialchars($value["counsellor_name"]) ?></p>
                            <div class="edit-del-sec">
                            	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_RMS_UNIVERSITIES.'/'.$value["counsellor_id"]?>">Edit</a>&nbsp;&nbsp;			
                            </div>
			    </div>
                        </td>
                        <td>
                            <p class="cms-associated-cat"><?=$value["no_of_mapping"]?></p>
			    <div class="edit-del-sec">
                            	<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_ADD_RMS_UNIVERSITIES.'/'.$value["counsellor_id"]?>">Map New University</a>&nbsp;&nbsp;			
                            </div>
                        </td>
                        <td>
                            <p class="cms-associated-cat"><?=$value["response_count"]?></p>
			    <?php if($value["response_count"] >0){?>
			    <a class="export-btn flRt" href="/listingPosting/AbroadListingPosting/exportRmsCounsellorResponse/<?=$value["mappingId"]?>">Export &gt;</a>
			    <?php } ?>
                        </td>
                        <td>
                            <p class="cms-table-date"><?=(date("d M y",strtotime($value["last_modified"])))?></p>
                        </td>
                </tr>
		<?php
		}
		?>
                </table>
		</div>
	<div class="clearFix"></div>
            </div>
        <div class="clearFix"></div>
        </div>