                                <?php if(count($blogs['faq'][0]) > 0) { ?>
                                <div class="wdh100">
                                    <div class="shik_skyBorder">                                
                                        <div class="shik_roundCornerHeaderSpirit shik_skyGradient"><span class="Fnt14" style="padding-left:10px"><b>Important Facts <?php if($countryNameSelected == "Europe") { ?><?php echo " powered by Study in Holland"; } ?></b></span></div>

                                        <div class="mlr10">
                                            <div class="lh10"></div>
                                            <div>
                                            	<div class="float_L w85"><div class="wdh100" align="center"><img src="<?php echo str_replace('_s','_m',$blogs['faq'][0][0]['blogImageURL'] == '' ? '/public/images/faqSA.jpg' : $blogs['faq'][0][0]['blogImageURL'])?>" width = "85px" height = "72px"/></div></div>
                                                <div class="ml95">
                                                <?php
				                                if($countryNameSelected == "UK-ireland"){
													$countryNameSelected = "UK-Ireland";
												}
                                                if($countryNameSelected == 'Europe') { ?>
<div style="background:url(/public/images/clntLogo.gif) no-repeat right top;padding:0 100px 10px 0" >
<?php } ?>
                                                	<ul class="faqSA_ul">
                                                    <?php
                                                    for($i = 0;$i<count($blogs['faq'][0]);$i++) 
{ 
    ?>
        <li><a href="<?php echo $blogs['faq'][0][$i]['url']?>" title="<?php echo $blogs['faq'][0][$i]['blogTitle']?>"><?php echo $blogs['faq'][0][$i]['blogTitle']?></a></li>
        <?php } ?>
                                                    </ul>
                                                    <?php 

                                                if($countryNameSelected == 'Europe') { ?>
</div>
<?php } ?>
                                                </div>
                                            </div>
                                            <div class="clear_B lh10">&nbsp;</div>
<?php
                            $urlParams = '';
                            if($categoryId > 1) {
                                $urlParams .= 'category=1';
                            }
                            if($urlParams != '') {$urlParams .='&';}
                            if($countryId > 1 && strpos($countryId, ',')=== false) 
                            {
                                $urlParams .= 'country='. $countryId;
                            }
                            $urlParams1 = $urlParams.'type=faq';
                    ?>
                                           <?php if(strpos($countryId,',') === false) { ?>
                                            <div class="txt_align_r"><a href="<?php echo '/blogs/shikshaBlog/showArticlesList?type=faq&country='.$countryId?>">View all Important Facts</a></div>
                                          <?php } else
                                           {?>
                                           <div class="txt_align_r"><a href="#" onClick="showCountryOverlay('faq',this);return false;">View All Important Facts</a></div>
                                            <?php } ?>
                                        </div>
                                        <div class="lh10"></div>
                                    </div>
                                </div>
                                <div class="lh10"></div>
                                <?php } ?>
