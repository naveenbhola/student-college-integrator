<!--Start_SearchPanel-->
    <div style="width:100%">
    	<div class="shik_bgSearchPanel" style="height:108px;overflow:hidden">
        	<div style="width:100%">
            	<div style="width:238px;height:108px;position:relative" class="float_L">
                	<!--Start-LeftSide_Logo-->
                    <div id = "imagespace">

                    <!--End_LeftSide_Logo-->
                    </div>
                </div>
                <div style="margin-left:238px">
                	<div style="width:100%" class="float_L">
                		<div class="shik_bgSearchPanel_2">
                        <!-- Search Panel-->
                        <?php $this->load->view('home/commonSearchPanel');?>
                        <!-- Search Panel -->
                            <!--Start_CategoryPage_Title-->
                            <div style="width:100%">
                                <?php if($also_see != 'no' && $also_see != ''){?>
                                <div class="float_L" style="width:450px">
                                <?php } else{?>
                                <div>
                                <?php } ?>
                                    <h1><span class="Fnt18" style="color:#000"><?php if (trim($course_page_heading) != ''){ echo $course_page_heading; } else {echo $categoryData['name'];}?>&nbsp;<!--<span class="orangeColor Fnt12">&#9660;</span>--></span>
                                    <?php if ( trim($show_subcategory) == 'yes') { ?><span class="Fnt14" style = "color:#000"><b> - <?php echo $subCategorySelected; ?></b></span><?php } ?> &nbsp;</h1>
                                    <?php if ( trim($show_subcategory) == 'yes') {?>
                                    <span style="font-size:12px;font-weight:400">
                                    	<b style="color:#bdbdbd">[</b>
                                        <a href="#" style=""  onClick = "drpdwnOpen(this, 'overlayCategoryHolder');return false;">Change Category <span class="orangeColor">&#9660;</span></a>
                                        <b style="color:#bdbdbd">]</b>
                                    </span>
                                    <?php } ?>
                                    <b> <?php if ( trim($show_subcategory) == 'yes') {?>:&nbsp;<?php } else{ ?> - <?php } echo $cityNameSelected?> &nbsp;</b>
                                    <span style="font-size:12px;font-weight:400">
                                    	<b style="color:#bdbdbd">[</b>
                                    	<a href="#" onclick = "showlocationlayer(<?php echo $openOverlay;?>);return false;" style="">Change Location <span class="orangeColor">&#9660;</span></a>
                                    	<b style="color:#bdbdbd">]</b>
                                    </span>
                                </div>
                                <?php if($also_see != 'no' && $also_see != ''){?>
                                <div class="float_R" style="width:285px">
                                  <div style="border-left: 1px dashed #CCCCCC;height: 42px;margin: 0;padding-left: 10px;">
                                    <div style="padding:5px 10px 0 0">Also See:
                                          <?php
                                          for($kk=0; $kk < count($also_see); $kk++)
                                          {
                                              if(  0 < $also_see[$kk]  &&  $also_see[$kk] < 6)
                                                $categ= 'Management';
                                              elseif(  5 < $also_see[$kk]  &&  $also_see[$kk] < 10)
                                                $categ= 'it';
                                              elseif(  9 < $also_see[$kk]  &&  $also_see[$kk] < 12)
                                                $categ= 'science';
                                              else
                                                $categ='hospitality';

                                              if( $countryNameSelected != '')
                                              $countryN= strtolower($countryNameSelected);
                                              else
                                              $countryN='All';

                                              if( $cityNameSelected != '')
                                              $cityN= $cityNameSelected;
                                              else
                                              $cityN='All';

                                              if($selectedCity != '')
                                              $cityD= $selectedCity;
                                              else
                                              $cityD='All';

                                              $location= $countryN.'/'.$cityD.'/'.'All'.'/'.$cityN;

                                              ?>
                                                    <?php if($kk == 2) { ?>
                                                    <span style="padding-left:55px">
                                                    <?php } ?>

                                                    <?php if ($kk == 1 || $kk == 3){?>
                                                        <span class="fcGya">|</span>
                                                    <?php  } ?>
                                                    <a  href="<?php echo getSeoUrlCourse(constant('SHIKSHA_'.strtoupper($categ).'_HOME'),$categ,$also_see_url[$kk],$location); ?>"><?php if(strpos($also_see_title[$kk],'Management') !== false){$also_see_title[$kk] = 'Mgmt. Certifications';} echo $also_see_title[$kk]; ?></a>
                                                    <?php if ($kk == 1){?>
                                                    <br />
                                                    <?php } ?>

                                           <?php } ?>

                                           <?php if (count($also_see) > 2){?>
                                           </span>
                                           <?php } ?>
                                    </div>
                                  </div>
                                </div>
                                <div class="clear_B">&nbsp;</div>
                                <?php } ?>
                             </div>
                            <!--End_CategoryPage_Title-->
                    	</div>
                    </div>
                </div>
                <div class="clear_L">&nbsp;</div>
            </div>
        </div>
    </div>
    <!--End_SearchPanel-->

    <style>
    .ad_float_on{width:760px; height:100px;}
    .ad_float_off{width:220px; height:100px;}
    </style>
