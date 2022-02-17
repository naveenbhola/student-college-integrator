                 <ul>
                <?php for($i=0;$i<count($onlineFormEnterpriseInfo['instituteDetails'][0]);$i++){?>
                <?php $info = $onlineFormEnterpriseInfo['instituteDetails'][0][$i];?>
                   <?php $alertCount = 0;?>
                    <?php if($info['alertsMsgId']!=NULL):?>
                    <?php $countA = count($info['alertsMsgId']);?>
                    <?php
                         foreach ($info['alertsMsgId'] as $infos):
                         if(in_array($infos,array('20','21','22','23','40') )){$alertCount++;}
                         endforeach;endif;?>
                 <?php if(isset($info['GDPIDate']) && trim($info['GDPIDate'])!='' && trim($info['GDPIDate'])!='0000-00-00 00:00:00'){$gdpiDate = date("d/m/Y",strtotime($info['GDPIDate'])); $gdpiLinkText = $gdpiDate;}else{$gdpiDate = date('d/m/Y'); $gdpiLinkText = 'Update '.$gdPiName;}?>
                
                
                	<li <?php if($i>0 && ($i+1)%2==0){ ?>class="evenRow"<?php }?> id="formContent">
                    	<div class="entFormLftCol">
                    	<div class="entSelectCol"><input type="checkbox" value="<?php echo $info['onlineFormId'];?>-<?php echo $info['userId'];?>" id="<?php echo $gdpiDate;?>#<?php echo $info['gdpiId'];?>#<?php echo $gdpiInfoCourseWise[$info['courseId']]['cityName'];?>#<?php echo $gdpiInfoCourseWise[$info['courseId']]['cityId'];?>#<?php echo $info['onlineFormId'];?>" specId="<?php echo $info['instituteSpecId'];?>"/>
                        </div>
                    	
                        <div class="entAppsNo" <?php if($tab== 'awaitedForms'){?> style="white-space:normal;"<?php }?>><?php if($tab!= 'awaitedForms'){?><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?userId=<?php echo $info['userId'];?>&formId=<?php echo $info['onlineFormId'];?>&institute_id=<?php echo $info['instituteId'];?>"><?php } ?><?php if(!empty($info['instituteSpecId'])) {echo $info['instituteSpecId'];} else {echo $info['onlineFormId'];}?> (<?php echo $info['firstname'];?><?php if(isset($info['middlename']) && $info['middlename']!=''){?>&nbsp;<?php echo $info['middlename'];}?><?php if(isset($info['lastname']) && $info['lastname']!=''){?>&nbsp;<?php echo $info['lastname'];}?>)<?php if($tab!= 'awaitedForms'){?></a><?php } ?>
                        </div>
                        </div>
                        
                        <div class="entFormRtCol">
                    	<div class="entFromStage"><?php if($info['formStatus']=='accepted') echo 'Accepted';else if($info['formStatus']=='rejected') echo 'Rejected';else if($info['formStatus']=='shortlisted') echo 'Shortlisted';else if($info['formStatus']=='cancelled') echo 'Cancelled';else echo 'Under Process';?>
                        </div>
                    	
                        <div class="entLocation"><?php echo (isset($info['cityName']) && $info['cityName']!='')?$info['cityName']:'NA';?></div>
                        
                    	<div class="entGDPI"><?php if($tab!= 'awaitedForms'){?><a href="javascript:void(0);" onclick="showGDPIDateOverlay('<?php echo $gdpiDate;?>','<?php echo $info['gdpiId'];?>','<?php echo $gdpiInfoCourseWise[$info['courseId']]['cityName'];?>','<?php echo $gdpiInfoCourseWise[$info['courseId']]['cityId'];?>','<?php echo $info['onlineFormId'];?>');var x= sendAlerts();x('5');setUserFormId('<?php echo $info['onlineFormId'];?>-<?php echo $info['userId'];?>','<?php echo $info['instituteSpecId']?>');"><?php } ?><?php echo $gdpiLinkText;?><?php if($tab!= 'awaitedForms'){?> </a> <?php } ?> 
                        </div>
                    	
                        <div class="entFromStatus" <?php if($info['onlineFormEnterpriseStatus']=='New Submission'){?> style="color:#d61a18;"<?php }?>><?php echo str_ireplace("GD/PI",$gdPiName,$info['onlineFormEnterpriseStatus']);?>
                        </div>
                        
                    	<div class="entPayment"><?php if($info['paymentMode']=='Offline'){ echo 'Draft';}else{ echo $info['paymentMode'];}?></div>
                        
                        <div class="entAlerts"><?php if($tab!= 'awaitedForms'){?><a href="javascript:void(0);" <?php if($alertCount>0){?>onclick="showNotification('<?php echo $info['onlineFormId'];?>','<?php echo $info['userId'];?>','<?php echo $data['institute_id'];?>');"<?php }?> class="notifyAlerts" title=""><?php } ?><?php echo $alertCount;?><?php { ?></a><?php } ?>
                        </div>
                        
                        <div class="entDownloadDocs"><?php if($tab!= 'awaitedForms'){?><a href="javascript:void(0);" onclick="getDownloadDocumentInfo('<?php echo $info['userId'];?>','<?php echo $info['onlineFormId'];?>');"><?php } ?>Download<?php if($tab!= 'awaitedForms'){?></a><?php } ?></div>
                        
                        <div class="clearFix"></div>
                		<div <?php if($i>0 && ($i+1)%2==0){ ?>class="evenRow"<?php }?> style="display:none;" id="alerts_<?php echo $info['onlineFormId'];?>_<?php echo $info['userId'];?>">
						<!--Alert Cloud starts here-->
                    	<div class="entAlertCloud">
                        	<div class="alertCloudPointer"></div>
                            <div class="notifyContent alertCloudContent">
                            	<h3>Notifications</h3>
                                <?php $totalNotifications = count($info['alertsMessageText']);// print_r($info['alertsNotifications']);//print_r($onlineFormEnterpriseInfo['alerts']);for($i=0;$i<count($onlineFormEnterpriseInfo['alerts']);$i++){?>
                                <ul>
                                    <?php for($k=0;$k<$totalNotifications;$k++){?>
                                    <?php $ids = array('20','21','22','23','40');?>
                                    <?php if(in_array($info['alertsMsgId'][$k],$ids) && $info['alertStatus'][$k]=='Unviewed'){?>
                                    <?php if($info['alertsNotifications'][$k]!='') ?>
                                        <li><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?userId=<?php echo $info['userId'];?>&formId=<?php echo $info['onlineFormId'];?>&alertId=<?php if($info['alertsMsgId'][$k] == '40'){echo 'online_payment_details_of_bug_fix';} else {echo 'alert'.$info['alertStatusId'][$k];}?>"><?php echo $info['alertsNotifications'][$k]; ?></a></li>
                                         <?php if($k>1) break;?>
                                    <?php ?>
                                    <?php }?>
                                    <?php }?>
                                </ul>
                                <?php //} ?>
                                <p class="viewAllNotify"><a href="/onlineFormEnterprise/OnlineFormEnterprise/enterpriseUserDashBoard?userId=<?php echo $info['userId'];?>&formId=<?php echo $info['onlineFormId'];?>&alertId=communicationHistory" title="View All">View All <span>&raquo;</span></a></p>
                            </div>
                         </div>
						<!--Alert Cloud ends here-->
                	</div>
                        
                     </div>
                	</li>
			<?php } ?>
                </ul>
