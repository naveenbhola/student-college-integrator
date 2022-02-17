
<input type="hidden" id="totalAgents" value="<?php echo $search_agents_all_array['totalRows']; ?>"/>
<div id="manageSADiv" class="">
    	<div style="margin-top:10px">
            <!--Start_Repeating_Data-->
			<div style="text-align:right">
				<div id="pagingIDc" style="padding:3px 0 3px 3px">
						<!--Pagination Related hidden fields Starts-->
						<input type="hidden" id="startOffset" value="<?php echo $start; ?>"/>
						<input type="hidden" id="countOffset" value="<?php echo $count; ?>"/>
						<input type="hidden" id="methodName" value="getPaginatedAgents"/>
						<!--Pagination Related hidden fields Ends  -->
						<span><span class="pagingID" id="paginataionPlace1"><?php echo $paginationHTML; ?></span></span>
				</div>
			</div>
			<?php $i=1+$start;
				if($search_agents_all_array['totalRows']==0){
				echo "<div class='showMessages'>You do not have any Genie saved. You may create one by saving your search from the Search Results page.</div>";
				}
				foreach($search_agents_all_array as $tempArray){
				if(is_array($tempArray) && $tempArray['sa_id'] > 0){
			?>
    		<div class="sBdrBtm" style="background:#f3fbfe;padding:10px;border:none">			
				<div class="float_L Fnt20" style="width:40px"><?php echo $i; ?>.</div>
				<div class="float_L" style="width:895px">
						<div class="mb5">
							<?php $activatePauseSearchAgent="activatePauseSearchAgent".$tempArray['sa_id']; ?>
								<span class="Fnt20"><?php echo $tempArray['name']; ?></span>

                              <div class="inputGenie" style="margin-bottom: 10px;">
								<input type="number" min=1 id="portingNumber<?php echo $tempArray['sa_id']?>" value="<?php echo $tempArray['auto_download']['detail']['leads_daily_limit']?>" style="text-align: center;padding: 6px 10px;">
								

								<button class="lightblue_btn" onclick="savePortingNumber('<?php echo $tempArray['sa_id']?>')">Set Daily Limit</button>

								<a href="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','is_active','live','');" class="orange-button" style="margin-left:30px">Delete</a>
                               </div>

                               Backlog: <span id='<?php echo $tempArray['sa_id']; ?>_leftover' style="padding: 3px 16px;background: #f8f8f8;border: 1px solid #ccc;min-width: 110px;"><?php echo $leftOver[$tempArray['sa_id']]; ?></span> 
    				<a href="javascript:resetLeftOverStatus('<?php echo $tempArray['sa_id']; ?>');" class="orange-button">Reset</a>
    						Click on Reset button to set backlog counter value to zero(0)
    						<p class="backlog--txt"><strong style="
						">Backlog</strong>  Counter for each genie is equal to shortfall between daily limit of genie and lead/MR delivered to you fo each preceding day aggregated till date. It is calculated from the day genie was created or backlog counter was set to Zero(0), whichever date is greater of two.</p>
    				
                                <label class="error_label" id="errorText<?php echo $tempArray['sa_id']?>"></label>
								<?php if($deliveryMethod == 'normal') { ?>
								<span id="<?php echo $activatePauseSearchAgent; ?>">
									<a href="#" onClick="return runsearchAgent('<?php echo $tempArray['sa_id']; ?>');">Run Genie</a><span class="fcGya">&nbsp;|&nbsp;</span> <a href="javascript:editActivatePaused('<?php echo $tempArray['sa_id']; ?>','is_active','live','<?php echo $activatePauseSearchAgent; ?>');">Delete</a>
								</span>
								<?php } ?>
						</div>

						<?php foreach($search_agents_all_display_array as $tempDisplay){
								if($tempDisplay[0]['searchagentid']==$tempArray['sa_id']){
									$displayData = base64_decode($tempDisplay[0]['inputhtml']);
									if (!strpos($displayData,'Active User Included')){
										if ($search_agents_all_array[$tempArray['sa_id']]["type"] == "lead")
										{	
											$displayData.=" <i>|</i><b>Active User Included : </b>";
											if ($activeUserMapping[$tempDisplay[0]['searchagentid']]=='no'){
												$displayData.='No';
											}
											else{
												$displayData.='Yes';
											}
										}
									}
									?>
									<div class="dandaSepGray"><?php echo $displayData; ?></div> 
								<?php }
							} ?>
						<div class="lineSpace_20">&nbsp;</div>
					
						<div class="lineSpace_10">&nbsp;</div>
				</div>
				<div class="clear_B">&nbsp;</div>
			</div>
			<?php $i++; }}?>
            <!--End_Repeating_Data-->
		</div>
		<div id="showingEventsList2"></div>
		<div class="txt_align_r mt10">
			<div id="pagingIDc" style="padding:3px">
				<span>
					<span class="pagingID" id="paginataionPlace2"><?php echo $paginationHTML;?></span>
				</span>
			</div>
		</div>
</div>
<?php if($hasPortingAgents && !$Search_Agent_Update_flag) { ?>
	<b><a href='/searchAgents/searchAgents/openUpdateSearchAgent/0/10/porting' id="viewPortingGenieLink">View porting genie</a></b>
<?php } ?>			
<div class="lineSpace_35">&nbsp;</div>	
</div>
</div>
</div>
