<?php
    $currencySymbol = $this->config->item("ENT_SA_CURRENCY_SYMBOL_MAPPING");
?>
<div class="course-detail-tab placement-tab">
    <div class="course-detail-mid flLt">
        <h2 style="margin-bottom:6px; height: 18px;">Placement Information</h2>
        <div id="abroadCoursePlacement"  class="cons-scrollbar1 scrollbar1 clearwidth">
            <div class="cons-scrollbar scrollbar" style="visibility:hidden;">
                <div class="track">
                    <div class="thumb"></div>
                </div>
            </div>

                <div class="viewport" style="height:310px">
                    <div class="overview dyanamic-content" style="width:98%;">
                        <?php   if($fromAverageSalary != '' && $toAverageSalary != ''){?>
                                <p class="scholar-head mL10"><strong>Avg Salary</strong></p>
                                <div class="cost-table">
                                    <div class="cost-row">
                                        <?php   if(!empty ($currencySymbol[$courseObj->getJobProfile()->getCurrencyEntity()->getId()])) { ?>
                                                    <div class="cost-columns">
                                                        <strong>
                                                            <?php echo $courseObj->getJobProfile()->getCurrencyEntity()->getName()?> (<?php echo $currencySymbol[$courseObj->getJobProfile()->getCurrencyEntity()->getId()]?>)
                                                        </strong>
                                                    </div>
                                        <?php   }else{ ?>
                                                    <div class="cost-columns"><strong><?php echo $courseObj->getJobProfile()->getCurrencyEntity()->getName()?> (<?php echo $courseObj->getJobProfile()->getCurrencyEntity()->getCode()?>)</strong></div>
                                        <?php   } ?>
                                        <div class="cost-columns" style="padding:0 20px">&nbsp;</div>
                                        <?php   if(!$isIndianCurr){?>
                                                    <div class="cost-columns"><strong>Indian Rupee (Rs.)</strong></div>
                                        <?php   }?>
                                    </div>
                                    <div class="cost-row row-box">
                                        <div class="cost-columns font-14">
                                            <?php   if(!empty ($currencySymbol[$courseObj->getJobProfile()->getCurrencyEntity()->getId()])) { ?>
                                                        <p>
                                                            <strong>
                                                                    <?php echo $currencySymbol[$courseObj->getJobProfile()->getCurrencyEntity()->getId()]?> <?php echo $fromAverageSalary?>
                                                            </strong>
                                                            <span class="price-tag">
                                                                Annually
                                                            </span>
                                                        </p>
                                            <?php   }else{?>
                                                        <p>
                                                            <strong>
                                                                <?php echo $courseObj->getJobProfile()->getCurrencyEntity()->getCode()?> <?php echo $fromAverageSalary?>
                                                            </strong>
                                                            <span class="price-tag">
                                                                Annually
                                                            </span>
                                                        </p>
                                            <?php   }?>
                                        </div>
                                        <?php   if(!$isIndianCurr){?>
                                                    <div class="cost-columns font-18" style="padding:0 20px"> = </div>
                                                    <div class="cost-columns font-14">
                                                        <p>
                                                            <strong>
                                                                <span style="font-size:13px;">
                                                                    <?php echo $currencySymbol['1']?>
                                                                </span>
                                                                <?php echo substr($toAverageSalary,0,strpos($toAverageSalary,'.'));?>
                                                            </strong>
                                                            <span class="price-tag">
                                                                Annually</span></p>
                </div>
                <?php }?>
                </div>
            </div>
            <?php }
                $recruitCompanies = $courseObj->getRecruitingCompanies();
                if(!is_null($recruitCompanies) && reset($recruitCompanies)->getName() != '')
                {
                   if(count($recruitCompanies)> 1){?>
                   <p class="scholar-head"><strong>Recruiting Companies</strong></p>
                   <?php } 
                   else {?>
                   <p class="scholar-head"><strong>Recruiting Company</strong></p>
                   <?php } ?>
                <!-- Code Section for recruiting companies -->
                <?php
                $layerAnchorOpen = '<a href="JavaScript:void(0);" onclick="openRecruitingCompaniesLayer();"	class="flRt font-13">';
                $layerAnchorClosed = '</a>';
                $companyListClass = "animation-company-list";
                $companyListLimit = 4;
                ?>
                <ol class="<?php echo $companyListClass?> clearwidth" style="margin:10px 0 !important;">
                <?php
                if(count($recruitCompanies)<=$companyListLimit){
                $maxNoOfCompaniesToDisplay = count($recruitCompanies);
                $layerAnchorOpen = $layerAnchorClosed = "";
                }
                else
                {
                $maxNoOfCompaniesToDisplay = $companyListLimit;
                }
                for($i=0;$i<$maxNoOfCompaniesToDisplay;$i++)
                {
                    echo '<li>'.$layerAnchorOpen.'<img class="lazy" src="" data-original="'.$recruitCompanies[$i]->getLogoURL().'" alt="'.$recruitCompanies[$i]->getName().'"  title="'.$recruitCompanies[$i]->getName().'" />'.$layerAnchorClosed.'</li>';
                } ?>	
                </ol>
                <p><?php
                    if(count($recruitCompanies)>$companyListLimit){
                    echo $layerAnchorOpen.'View all '.count($recruitCompanies).' companies >'.$layerAnchorClosed;
                    }

                ?>
                </p>
                <?php if(count($recruitCompanies)>4){ ?>
                <div id="modal-overlay"></div>
                <div class="management-layer" id="recruiting-companies-layer" style="<?php echo (count($recruitCompanies)<=18?'width:410px;':'width:430px;');?>display:none;z-index:999;">
                <div class="layer-head">
                 <a href="JavaScript:void(0);" onclick="closeRecruitingCompaniesLayer();" class="flRt common-sprite close-icon" title="Close"></a>
                     <div class="layer-title-txt flLt">Recruiting Companies</div>

                </div>
                <div class="clearfix"></div>
                 <div class="company-thumbs <?php echo (count($recruitCompanies)>=19?"scrollbar1":"");?> testscroller" style="margin-top:15px; <?php echo (count($recruitCompanies)>=19?"height: 320px;":"");?>" >
                     <?php if(count($recruitCompanies)>=19){ ?>
                     <div class="scrollbar">
                         <div class="track">
                             <div class="thumb">
                                 <div class="end"></div>
                             </div>
                         </div>
                     </div>
                     <div class="viewport" style="height:290px;width:380px;">
                         <div class="overview">
                     <?php } ?>
                         <!-- my content-->
                         <ul style="margin-left:0px;">
                             <li style="list-style: none;padding-right:0px;">
                             <?php 
                             for($i=0;$i<count($recruitCompanies);$i++){
                                 if($i%3==0 && $i !=0){echo '</li><li style="padding-right:0px;">'; }
                                 echo '<img class = "recruitmentLogoImg" src="" alt="'.$recruitCompanies[$i]->getName().'" title="'.$recruitCompanies[$i]->getName().'" />';
                             }
                             ?>
                             </li>
                         </ul>
                     <?php if(count($recruitCompanies)>=19){ ?>
                         </div>
                     </div>
                     <?php } ?>
                     <div class="clearfix"></div>     
                    </div>
                 </div>
                 <?php }
            }?>

            <?php if($courseObj->getJobProfile()->getPercentageEmployed()){?> 
                <div class="clearwidth">
                <p class="scholar-head"><strong>Percentage of students employed: </strong><?php echo $courseObj->getJobProfile()->getPercentageEmployed()?></p>
            </div>
            <?php }?>
                <div class="popular-sec clearwidth">
                    <?php if($courseObj->getJobProfile()->getPopularSectors()) {?>
                    <p class="scholar-head"><strong>Popular sectors</strong></p>
                    <p><?php echo $courseObj->getJobProfile()->getPopularSectors()?></p>
                <?php }?>       
               <div class="clear"></div>
               </div>
               <div class="internship-sec clearwidth">
                <?php if($courseObj->getJobProfile()->getInternships()){?>
                <p class="scholar-head"><strong>Internships</strong></p>
                <p><?php echo $courseObj->getJobProfile()->getInternships()?></p>
                <?php }?>
                <?php if($courseObj->getJobProfile()->getInternshipsLink()){
                $jobExtLink = '';
                if(0===strpos($courseObj->getJobProfile()->getInternshipsLink(),'http')){
                $jobExtLink = $courseObj->getJobProfile()->getInternshipsLink();
                }else{
                $jobExtLink = 'http://'.$courseObj->getJobProfile()->getInternshipsLink();
                }
                ?>
                    <a target="_blank" rel="nofollow" href="<?php echo $jobExtLink?>" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');" class="flRt font-11" style="margin-top:5px">More about Internship<i class="common-sprite ex-link-icon"></i></a>
                <?php }?>
                </div>
            </div>
            </div>
        </div>
    </div>
<div class="course-detail-rt flRt clearfix">
<?php   if($courseObj->getJobProfile()->getCareerServicesLink())
{
    $careerLink = '';
    if(0===strpos($courseObj->getJobProfile()->getCareerServicesLink(),'http'))
    {
        $careerLink = $courseObj->getJobProfile()->getCareerServicesLink();
    }
    else
    {
        $careerLink = 'http://'.$courseObj->getJobProfile()->getCareerServicesLink();
    }
?>                          
    <div class="course-rt-tab">
        <ul class="course-dur">
            <li class="course-dur-bdr">
                <strong style="margin-bottom:4px; display:block;">Placement Services</strong>
                <a target="_blank" href="<?php echo $careerLink?>" onclick="studyAbroadTrackEventByGA('ABROAD_COURSE_PAGE', 'outgoingLink');" rel="nofollow" >More about placement on university website <i class="common-sprite ex-link-icon"></i></a>
            </li>
        </ul>
    </div>
    <?php   }?>
    <!--This rate my chance button-->
    <?php   $param['widget'] = 'placementTab'; 
            $param['trackingPageKeyId'] = 367;  
            $this->load->view('listing/abroad/widget/rateMyChanceWidget',$param);
    ?>

</div>
</div>


<script>
    var recruitingURLs = [];
    var companyImgLoaded = false;
    var recruitCompanies = <?php echo count($recruitCompanies); ?>;
  <?php for($i=0;$i<count($recruitCompanies);$i++){ ?>
  recruitingURLs[<?php echo $i?>] = "<?php echo $recruitCompanies[$i]->getLogoURL() ?>";
  <?php }?>
</script>
