        <div class="abroad-cms-rt-box">
			<div class="abroad-cms-head" style="margin-top:0;">
            	<h2 class="abroad-sub-title">All RMS Counsellors</h2>
                <div class="flRt"><a href="/listingPosting/AbroadListingPosting/addRMSCounsellor" class="orange-btn" style="padding:6px 7px 8px">+ Add New RMS Counsellors</a></div>
            </div>
                
                <table border="1" cellpadding="0" cellspacing="0" class="cms-table-structure">
                	<tr>
                        <th width="5%" align="center">
				<span class="flLt" style="margin-top:6px;">S.No.</span>
			</th>
                        <th width="30%">
                            <span class="flLt" style="margin-top:6px;">Counselor Name</span>
                        </th>
                        <th width="20%">
                        	<span class="flLt" style="margin-top:6px;">Email ID</span>
                        </th>
                        <th width="15%">
                        	<span class="flLt" style="margin-top:6px;">Mobile No</span>
                        </th>
                        <th width="15%">
				 <span class="flLt" style="margin-top:6px;">Manager Name</span>
			</th>
                        <th width="20%">
				 <span class="flLt" style="margin-top:6px;">Added on</span>
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
			 <div style="word-wrap:break-word; width:170px;">
                            <p><?=htmlspecialchars($value["counsellor_name"]) ?></p>
                            <div class="edit-del-sec">
								<?php if( $value["status"] == ENT_SA_PRE_LIVE_STATUS){?>
									<a href="<?=ENT_SA_CMS_PATH.ENT_SA_FORM_EDIT_RMS_COUNSELLOR.'?RMSId='.$value["counsellorId"]?>">Edit</a>&nbsp;&nbsp;
									<a href="javascript:void(0);" onclick="deleteCounsellor(<?php echo $value["counsellorId"]; ?>, '<?=htmlspecialchars($value["counsellor_name"]) ?>');">Delete</a>
								<?php } ?>
                            </div>
			 </div>
                        </td>
                        <td>
		         <div style="word-wrap:break-word; width:170px;">
                            <p class="cms-associated-cat"><?=$value["counsellor_email"]?></p>			    
			 </div>
			</td>
                        <td>
                            <p  style= "width:60px;" class="cms-associated-cat"><?=$value["counsellor_mobile"]?></p>
                        </td>
                        <td>
			  <div style="word-wrap:break-word; width:170px;">	
                            <p class="cms-table-date"><?=$value["counsellor_manager"]?></p>
                         </div>
			</td>
			 <td>                        	
                            <p class="cms-table-date"><?=(date("d M y",strtotime($value["added_on"])))?></p>
				<?php
				   if( $value["status"] == ENT_SA_PRE_LIVE_STATUS)
				      echo "<p class=\"publish-clr\">Live</p>";
				   else if( $value["status"] == 'deleted')
				      echo "<p class=\"draft-clr\">Deleted</p>";  
				?>	
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