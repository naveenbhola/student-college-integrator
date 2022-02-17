 <?php
global $countriesForStudyAbroad;
global $countries;
$this->load->builder('LocationBuilder','location');
$locationBuilder = new LocationBuilder;
$locationRepository = $locationBuilder->getLocationRepository();
$countries1 = $locationRepository->getCountriesByRegion(1);
$countries2 = $locationRepository->getCountriesByRegion(2);
$countries3 = $locationRepository->getCountriesByRegion(3);
$countries4 = $locationRepository->getCountriesByRegion(4);
?>

<div class="abroad-explore-layer" style="position: absolute;top:95px;left:27px; z-index:999;cursor: pointer;" onClick="window.location='<?=SHIKSHA_STUDYABROAD_HOME?>';">
			<p>Shiksha's new & detailed college search website for Studying Abroad</p>
			<p><a href="<?=SHIKSHA_STUDYABROAD_HOME?>" class="orange-button">Click to Explore</a></p>
</div>

<div style="opacity: 0.3;z-index:99;cursor: pointer;" onClick="window.location='<?=SHIKSHA_STUDYABROAD_HOME?>';">
<ul>
                	<?php $index_of_cat=0;foreach($countriesForStudyAbroad as $countryId => $country):
                    	if(strtolower($countryId) == 'india') continue;
						$countryFlag = isset($country['flagImage']) ? $country['flagImage'] : '';
						$countryname = isset($country['name']) ? $country['name'] : '';
						$linkUrl = constant('SHIKSHA_'. strtoupper($countryId) .'_HOME');
						if($countryId == 'china-hk,taiwan') {						        	
							$linkUrl = constant('SHIKSHA_'. strtoupper(str_replace(",","_",$countryId)) .'_HOME');
						} else if($countryId == 'newzealand&fiji') {
							$linkUrl = SHIKSHA_NEWZEALAND_FIJI_HOME;
						}
						$ulr_array[$countryId] = $linkUrl;
                    ?>
                    <li id="abroad_catrgory_li<?php echo $index_of_cat;?>" alreadyclicked='NO'>
                        <div id="hpgrdgn_abroad_left_category<?php echo $index_of_cat;?>">
                            <strong><?php echo $countryname;?></strong>
                            <?php if($countryId == 'southeastasia'):?>
                            <p>Malaysia <span>|</span> Singapore <span>|</span> Philippines <span>|</span> Thailand</p>
                            <?php elseif($countryId == 'europe'):?>
                            <p>France <span>|</span> Germany <span>|</span> Holland <span>|</span> Spain <span>|</span> Poland</p>
                            <?php elseif($countryId == 'middleeast'):?>
                             <p>Qatar <span>|</span> Saudi Arabia <span>|</span> UAE</p>
                            <?php elseif($countryId == 'uk-ireland'):?>
                            <p><?php echo $countries4[0]->getName();?> <?php if(array_key_exists(1, $countries4)):?><span>|</span> <?php echo $countries4[1]->getName();?><?php endif;?><?php if(array_key_exists(2, $countries4)):?> <span>|</span> <?php echo $countries4[2]->getName();?><?php endif;?><?php if(array_key_exists(3, $countries4)):?> <span>|</span> <?php echo $countries4[3]->getName();?><?php endif;?></p>
                            <?php elseif($countryId == 'newzealand&fiji'):?>
							 <p>New Zealand <span>|</span> Fiji</p>
							<?php elseif($countryId == 'fareast'):?>
							<p>Japan <span>|</span> North Korea <span>|</span> South Korea</p>
							<?php elseif($countryId == 'china-hk,taiwan'):?>
							<p>China <span>|</span> Hong Kong <span>|</span> Taiwan</p>
							<?php elseif($countryId == 'africa'):?>
							<p>Mauritius</p>
							<?php elseif($countryId == 'usa'):?>
                            <p id="countryusa">Boston <span>|</span> New York <span>|</span> Chicago <span>|</span> Texas</p>
                            <?php elseif($countryId == 'australia'):?>
                            <p id="countryaustralia">Sydney <span>|</span> Melbourne <span>|</span> Brisbane <span>|</span> Perth</p>
                            <?php elseif($countryId == 'newzealand'):?>
                            <p id="countrynewzealand">Auckland <span>|</span> Christchurch <span>|</span> Wellington <span>|</span> Hamilton</p>
                            <?php elseif($countryId == 'canada'):?>
                            <p id="countrycanada">Toronto <span>|</span> Edmonton <span>|</span> Ottawa <span>|</span> Vancouver</p>
                            <?php endif;?>
                        </div>
                        <div class="sub-cates" style="display:none" id="hpgrdgn_abroad_left_category<?php echo $index_of_cat;?>_subcat">
                        	<div class="sub-cates-items">
				<ul>								
                                    <li><a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage','topstudyabroadUGcourses');
					   	    trackEventByGA('homepagelefttabsubcatclickabroad',this.innerHTML);" href="<?php echo $linkUrl;?>">UG / Bachelors Courses</a></li>
 				    <li><a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage','topstudyabroadPGcourses');
						    trackEventByGA('homepagelefttabsubcatclickabroad',this.innerHTML);" href="<?php echo $linkUrl;?>">PG / Masters Courses</a></li>
  				    <li><a onclick="setCookie('ug-pg-phd-nav-click','YES');setCookie('ug-pg-phd-catpage','topstudyabroadPHDcourses');
						    trackEventByGA('homepagelefttabsubcatclickabroad',this.innerHTML);" href="<?php echo $linkUrl;?>">Ph.D / Doctoral Courses</a></li>
                                    
                                </ul>
                            </div>
                        </div>
                    </li>
                    <?php $index_of_cat++;endforeach;?>
                </ul>            
</div>