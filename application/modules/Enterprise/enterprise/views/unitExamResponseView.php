<?php
    $rowCount=0;
    foreach($resultResponse['responses'] as $response) {
        $userId = $response['userId'];
?>
        <div style="width:100%">
            <!--Start_PersonSay-->
            <div style="width:100%">
                <div style="margin:0 10px">
                    <div style="width:100%">
                        <div style="height:23px"><input allocationId= "<?php echo $response['id']?>" class="allo_check"  type="checkbox" id="rowName_<?php echo $rowCount?>" name="userCheckList[]" value="<?php echo $response['userId']; ?>"/> <b class="fontSize_14p"><?php echo $userData[$userId]['Full Name']; ?></b>
                        </div>
                    </div>                    
                </div>
            </div>
                                        
            <!--Start_PersonInformation-->
            <div class="lineSpace_20">&nbsp;</div>
            <div style="width:100%">
                <div style="margin:0 40px">
                    <div style="width:100%">
                        <div class="float_L" style="width:33%">
                            <div style="width:100%">
                                <div style="padding-right:15px">
                                    <div style="width:100%">
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Email:</span> <b style="word-wrap:break-word;"> <?php echo $userData[$userId]['Email']; ?></b></div>
                                        <?php if(empty($userData[$userId]['Mobile'])) {
                                                $userData[$userId]['Mobile'] = 'N.A.';
                                            } ?>
                                        <div class="cmsSResult_pdBtm7">
                                            <span class="darkgray">Mobile:</span><?php if(isset($userData[$userId]['ISD Code'])) { echo '+' . $userData[$userId]['ISD Code'] . '-'; } ?> <?php echo ($userData[$userId]['mobileverified'] == '1') ? $userData[$userId]['Mobile'] .' <i style="color:#ff0000">Verified </i>' : $userData[$userId]['Mobile']; ?>
                                        </div>
                                        <div class="cmsSResult_pdBtm7">
                                            <span class="darkgray">Is in NDNC List:</span><i style="color:black"><b>
                                                <?php echo ($userData[$userId]['NDNC Status'] ? (($userData[$userId]['NDNC Status'] =="Do not call")?'YES':'NO') : 'N.A.');?></b> </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="float_L" style="width:33%">
                            <div style="width:100%">
                                <div class="cmsSResult_dottedLineVertical">
                                    <div style="width:100%">
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Exam:</span> <?php echo ($response['examName'] ? $response['examName'] : 'N.A.'); ?></div>
                                    </div>
                                    <div style="width:100%">
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Course:</span> <?php echo ($response['groupName'] ? $response['groupName'] : 'N.A.'); ?></div>
                                    </div>

                                    <?php
                                        global $responseActionViewMapping;
                                        if(stripos($response['action'], 'client')) {
                                                $source = 'Mailer Alert';
                                        }else{
                                                $source = $responseActionViewMapping[$response['action']] ? $responseActionViewMapping[$response['action']] : $response['action'];
                                        }
                                    ?>
                                        
                                    <div class="cmsSResult_pdBtm7"><span class="darkgray">Response Type: </span> <?php echo ($source ? $source : 'N.A.'); ?></div>
                                </div>
                            </div>
                        </div>

                        <div class="float_L" style="width:33%">
                            <div style="width:100%">
                                <div class="cmsSResult_dottedLineVertical">
                                    <div style="width:100%">
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Date: </span><b><?php echo ($response['submit_date'] ? date('d M Y',strtotime($response['submit_date'])) : 'N.A.'); ?></b></div><br>
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Current City: </span><?php echo ($userData[$userId]['Current City'] ? $userData[$userId]['Current City'] : 'N.A.'); ?></div>
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Current Locality: </span><?php echo ($userData[$userId]['Current Locality'] ? $userData[$userId]['Current Locality'] : 'N.A.'); ?></div>
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Exams Taken:</span> <?php echo ($userData[$userId]['Exams Taken'] ? $userData[$userId]['Exams Taken'] : 'N.A.'); ?></div>                        
                                        <div class="cmsSResult_pdBtm7"><span class="darkgray">Work Experience:</span>       <?php echo $userData[$userId]['Work Experience'];?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div style="clear:both"></div>

                        <div class="cmsClear">&nbsp;</div>
                    </div>
                 
                    <div>
                        <?php if(!empty($usersContactHistory[$userId])) { ?>
                            <span class="redcolor" id="emailUser_<?php echo $rowCount; ?>"><img align="absbottom" src="/public/images/cmsSResult_mailCheck.gif"/> Emailed on <?php echo date("dS M Y",strtotime($usersContactHistory[$userId])); ?></span>
                        <?php } else { ?>
                            <span id="emailUser_<?php echo $rowCount; ?>"><a href="javascript:void(0);" onclick="communicateIndividualUser($('rowName_<?php echo $rowCount; ?>'),'Email');">Send Email</a></span>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!--End_PersonInformation-->
        </div>
        <div class="cmsSearch_SepratorLine" style="margin:15px 10px">&nbsp;</div>
<?php    
        $rowCount++;
    }
?>

<form id="examResponseViewerDownload" action="/enterprise/ResponseViewerEnterprise/getExamDownloadResponses" method="post" style="display:none"> 
    <input type="hidden" name="allocationIds">     
</form>
