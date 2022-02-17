                                <?php
                                
                                global $hideCityForInstitutes;
                                
                                $countArray = $collegeList['countArray'];
                                $totalres = $collegeList['countArray']['totalcount'];
                                $collegeList = $collegeList['institutesarr'];
                                if($countryNameSelected == "UK-ireland"){
									$countryNameSelected = "UK-Ireland";
								}
                                ?>
                                <div class="wdh100">
                                    <div class="shik_skyBorder">                                	
                                        <div class="shik_roundCornerHeaderSpirit shik_skyGradient"><h2><span class="Fnt14 fcblk" style="padding-left:10px"><b>Institutes &amp; Universities in <?php echo $countryNameSelected;?></b></span></h2></div>
                                        <div class="mlr10">
                                        	<div class="mtb10 wdh100">
												<span id = "categoryCollegesCountLabel" class="float_L">Showing 1-<?php echo ($totalres > 10)  ? 10 : $totalres?> of <?php echo $totalres?></span>
<?php
                               
if($countSelected == "new zealand")
{
$countSelected = "newzealand";
}
 $urlName = constant('SHIKSHA_'.strtoupper($countSelected).'_DETAIL'); ?>
                                                <span class="float_R"><span style="padding:5px"><a href="<?php echo $urlName;?>" title="View All Institutes"><b>View All Institutes</b></a></span></span>

												<div class="clear_B">&nbsp;</div>
                                            </div>
                                            <!--Start_Repeating_Data_Container-->
                                            <div id = "collegeListPlace">
                                            <ul class="faqSA_ul">
                                               <?php
                                               $classNamein = 'homeShik_Icon shik_checkedIcon';

                                               for($i = 0;$i<count($collegeList);$i++) {
                                               $k = 0;
                                              // if($countArray['mainids'][$i] == $collegeList[$i]['institute']['id'])
                                               $classNamein = 'mtb10 brdbottom pb15';
                                for($mk = 0;$mk <count($countArray['mainids']);$mk++)
                                {
                                    // if(isset($institutearr['isSponsored']) && $institutearr['isSponsored'] == 'true')
                                    if($countArray['mainids'][$mk] == $collegeList[$i]['institute']['id'])
                                    {
                                               $classNamein = 'mtb10 pb15 brdbottom chk';
                                        break;
                                    }
                                }
                                    for($mn = 0;$mn < count($countArray['categoryselector']);$mn++)
                                    {
                                        if($countArray['categoryselector'][$mn] == $collegeList[$i]['institute']['id'])
                                        {
                                           // $back="background:#fefde8";
                                               $classNamein = 'mtb10 pb15 brdbottom chk';
                                            break;
                                        }

                                    }
                                              
                                               ?>
                                               <li class="<?php echo $classNamein;?>">
                                                    <div class="wdh100">
                                                        <div class="float_L w200">
                                                            <div class="wdh100">
                                                                <a href="<?php echo $collegeList[$i]['institute']['url']?>" title="<?php echo $collegeList[$i]['institute']['institute_Name']?>"><?php echo $collegeList[$i]['institute']['institute_Name']?>,</a><br />
<?php
/*
Vikas K, July 13 2011 | Ticket #412
Do not show city if we found institute's entry in hideCityForInstitutes global array
defined in shikshaConstants.php
*/
if(!isset($hideCityForInstitutes) || !is_array($hideCityForInstitutes) || !in_array($collegeList[$i]['institute']['id'],$hideCityForInstitutes)) { ?>
<span class="fs11 fcGrn"><?php echo $collegeList[$i]['institute']['city']?>,<?php echo $collegeList[$i]['institute']['countryName']?></span><br />
<?php } ?>
       
       <?php 
        $courName = $collegeList[$i]['courses'][$k]['course_title'];
                                                                if(strlen($collegeList[$i]['courses'][$k]['course_title']) > 70)
                                                                {

        $courName = substr($collegeList[$i]['courses'][$k]['course_title'],0,70) . '..';
                                                                }
                                                                ?>
                                                                <a class="fs11" href = "<?php echo $collegeList[$i]['courses'][$k]['courseurl'];?>" title="<?php echo $courName;?>"><?php echo $courName;?></a>                                                                
                                                            </div>
                                                        </div>
														<div class="float_R w110">
                                                        <?php
														if($collegeList[$i]['institute']['isSendQuery']) { 
?><div class="float_L wdh100"><input type="button" onClick = "openOverlayInterested(this,<?php echo $collegeList[$i]['institute']['id']?>,'<?php echo str_replace("\"","",trim($collegeList[$i]['institute']['institute_Name']));?>','<?php echo $collegeList[$i]['institute']['url']?>');" class="btn_Im" /></div>
														<?php } else { ?>
														<div>&nbsp;</div>
														<?php } ?>
														</div>
                                                        <div class="clear_B"></div>
                                                    </div>
                                                    <div class="fcGya fs11">
                                                    <?php
													if($collegeList[$i]['institute']['mediadata']['photo'] == 0){
														$collegeList[$i]['institute']['mediadata']['photo'] = NULL;
													}
													if($collegeList[$i]['institute']['mediadata']['video'] == 0){
														$collegeList[$i]['institute']['mediadata']['video'] = NULL;
													}
													if(isset($collegeList[$i]['institute']['mediadata']['photo'])) {
                                                    ?><a href="<?php echo $collegeList[$i]['institute']['url'] . '#gallery'?>" class="phtoA"> <?php echo $collegeList[$i]['institute']['mediadata']['photo'] . " Photos" ?></a> &nbsp; <?php  
                                                    } 
                                                    if(isset($collegeList[$i]['institute']['mediadata']['photo']) && (isset($collegeList[$i]['institute']['mediadata']['video']) || isset($collegeList[$i]['institute']['mediadata']['rating']))) 
                                                    { ?> | &nbsp;
                                                    <?php } 
                                                    if(isset($collegeList[$i]['institute']['mediadata']['video'])) 
                                                    { ?><a href="<?php echo $collegeList[$i]['institute']['url'].'#videos'?>" class="vidoA"><?php echo $collegeList[$i]['institute']['mediadata']['video']. " Videos"?></a> <?php } ?>
                                                    <?php 
                                                    if(isset($collegeList[$i]['institute']['mediadata']['rating']) && isset($collegeList[$i]['institute']['mediadata']['video']) && $collegeList[$i]['institute']['mediadata']['rating'] > 0)
                                                    { ?>
                                                    &nbsp; |
                                                   <?php  }
                                                    if(isset($collegeList[$i]['institute']['mediadata']['rating']) && $collegeList[$i]['institute']['mediadata']['rating'] > 0)
                                                    { ?>
                                                   &nbsp; <a href="<?php echo $collegeList[$i]['institute']['url'].'#alumrating'?>" class="starA"><?php echo $collegeList[$i]['institute']['mediadata']['rating']?>/5 Rating</a>
                                                    <?php } ?>
                                                    </div>
                                                </li>                                                    
                                             <?php } ?>   
                                            </ul> 
                                            </div>
                                            <!--End_Repeating_Data_Container-->
                                            <div class="wdh100">
                                          <!--  	<div class="float_L">
													<div id="pagingIDc">
														<div class="txt_align_r pagingID" style="padding:5px 10px 5px 5px" id="paginataionPlace1" >
                                                            <?php
                                                            if($totalres > 10) { ?>
															<script>
                                                           	var pageName = 'FOREIGN_PAGE';
															setStartOffset(0,'startOffSet','countOffset');
															doPagination(<?php echo $totalres?>,'startOffSet','countOffset','paginataionPlace1',null,'methodName',4);
															</script>
                                                            <?php } ?>
														</div>
													</div>
                                                </div>-->
                                                <div class="float_R"><div style="padding:5px"><a href="<?php echo $urlName;?>" title="View All Institutes"><b>View All Institutes</b></a></div></div>
                                                <div class="clear_B"></div>
                                            </div>
                                        </div>
                                        <div class="lh10"></div>
                                    </div>
                                </div>
<div class="lh10"></div>
