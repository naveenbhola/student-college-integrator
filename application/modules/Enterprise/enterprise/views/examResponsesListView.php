<div class="featured-article-tab">
    <ul>
        <li onclick="clickTab('activatedTab');" 
            class="<?php echo ($subscriptionStatus =='active')?'active':''?>" >
            <a id="activatedTab" href="/enterprise/ResponseViewerEnterprise/getExamResponsesForClient/live">Active Subscriptions</a>
        </li>
        <li onclick="clickTab('deletedTab');"
            class="<?php echo ($subscriptionStatus !='active')?'active':''?>" >
            <a id="deletedTab" href="/enterprise/ResponseViewerEnterprise/getExamResponsesForClient/expired">Inactive / Expired Subscriptions</a>
        </li>
    </ul>
</div>
 
<div style="width:100%">
    <div style="width:100%">
    	<!--Start_Margin_10px-->
    	<div style="margin:0 10px">
            <div id="searchFormSubContents" style="display:none">
                <input type="hidden" id="subscriptionId" name="subscriptionId" value="<?php echo $subscriptionId;?>"/>
                <input type="hidden" id="startOffSetSearch" name="startOffSetSearch" value="<?php echo $startOffset;?>"/>
                <input type="hidden" id="countOffsetSearch" name="countOffsetSearch" value="<?php echo $countOffset;?>"/>
                <input type="hidden" id="selectedUsers" name="selectedUsers" value=""/>
                <input type="hidden" id="methodName" name="methodName" value="getResponsesForCriteria"/>
            </div>

            <?php if(isset($resultResponse['error'])){?>
                <div class="fontSize_18p" style="padding-bottom:7px"><?php echo $resultResponse['error'];?></div>
            <?php }else{?>
                <div style="width:100%">
                    <!--Start_ShowResutlCount-->
                    <div style="font-size:18px">Shiksha Response Viewer</div>
    				<div class="lineSpace_10">&nbsp;</div>
                    <div style="width:100%">
                        <?php if(isset($resultResponse['numrows'])) {
                                $studentCount = 'Only 1 responses';
                                if($resultResponse['numrows'] > 1) {
                                    $studentCount = 'Total <span class="OrgangeFont">'. $resultResponse['numrows'] .'</span> responses';
                                }
                                if($resultResponse['numrows'] == 0) {
                                    $studentCount = 'No responses';
                                }
                        ?>
                            <div>
    							<div style="width:70%">
    								<div style="width:100%">
    									<div class="fontSize_1xi68p" style="padding-bottom:7px"><span id="resultCount" style="font-size:18px"><?php echo $studentCount; ?> found</span></div>
    								</div>
    							</div>
                            </div>
                        <?php }
                        ?>
    					<div class="lineSpace_5">&nbsp;</div>
                        <div class="dandaSepGray"><b>Exam: <span class="OrgangeFont"><?php echo $examName;?></span></b>
                        </div>
    					
    					<div class="dandaSepGray"><b>Course : <span class="OrgangeFont"><?php echo $groupNames;?></span></b>
    					</div>
                    </div>
                    <!--End_ShowResutlCount-->
                    <div class="lineSpace_10">&nbsp;</div>
                    <div style="width:100%">
    				    <div style="line-height:7px;height:7px;overflow:hidden">&nbsp;</div>
    				    <div style="height:22px">
                            <span> 
                                <b>Generated in:</b> 
                                <select id="changeRegdateFilter_DD1" onChange="filterByTime(this.options.selectedIndex);">
                                <?php foreach ($timeIntervalValues as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?> 
                                
                                </select> 
                            </span>
    				    </div>
                    </div>

    			    <div class="lineSpace_10">&nbsp;</div>
                    <!--Start_NavigationBar-->
                    <div style="width:100%;<?php if(isset($resultResponse['numrows']) && $resultResponse['numrows'] <1) { echo 'display:none';} ?>">
            	        <div class="cmsSResult_pagingBg">
                	       <div style="margin:0 10px">
                    	       <div style="line-height:6px">&nbsp;</div>
                    	       <div style="width:100%">
                        	       <div class="float_L">
                                        <div style="width:100%"><div style="height:22px">
                                            <span><span class="pagingID" id="paginationPlace1"></span></span></div>
                                        </div>
                                    </div>
                                    <div class="cmsClear">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <!--End_NavigationBar-->
                
                        <!--Start_SendMail-->
                        <?php $this->load->view('enterprise/responseCTAs',array("checkBoxId"=>"checkAllUsers_1")); ?>
                        <!--End_SendMail-->                    
                        <div class="lineSpace_10">&nbsp;</div>
                
                        <!--  Start_MainDateRowContainer -->
                        <div style="width:100%" id="searchResultContainer">
                            <!--Start_DateRow_1 -->
                            <?php $this->load->view("enterprise/unitExamResponseView");?>
                            <!--End_DateRow_1  -->
                        </div>
                        <!--End_MainContainerDateRow -->
                
                        <div class="lineSpace_10">&nbsp;</div>
                        <!--Start_SendMail_SendSMS-->
                        <?php $this->load->view('enterprise/responseCTAs',array("checkBoxId"=>"checkAllUsers_2")); ?>
                        <!--End_SendMail_SendSMS-->
                        <div class="lineSpace_10">&nbsp;</div>
                        
                        <!--Start_NavigationBar-->
                        <div style="width:100%">
            	           <div class="cmsSResult_pagingBg">
                	           <div style="margin:0 10px">
                    	           <div style="line-height:6px">&nbsp;</div>
                    	           <div style="width:100%">
                        	           <div class="float_L" style="width:41%">
                                            <div style="width:100%"><div style="height:22px">
                                                <span><span class="pagingID" id="paginationPlace2"></span></span></div>
                                            </div>
                                        </div>
                                        <div class="float_R" style="width:25%">
    			                            <div style="width:100%">
                                                <div style="height:22px" class="txt_align_c"><b>Generated in:</b> 
                                                <select id="changeRegdateFilter_DD2" onChange="filterByTime(this.options.selectedIndex);">
                                                    <?php foreach ($timeIntervalValues as $key => $value) { ?>
                                                        <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                    <?php } ?> 
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cmsClear">&nbsp;</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--End_NavigationBar-->
                    </div>
                </div>
            <?php }?>
        </div>
    </div>
</div>
<script>
doPagination('<?php echo $resultResponse['numrows']; ?>','startOffSetSearch','countOffsetSearch','paginationPlace1','paginationPlace2','methodName',4);

selectComboBox($('changeRegdateFilter_DD1'), '<?php echo $timeInterval; ?>'); 
selectComboBox($('changeRegdateFilter_DD2'), '<?php echo $timeInterval; ?>');
</script>
