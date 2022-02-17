				<div class="float_L bgsearchResultBorderDotted bgsearchHeight" style="width:<?php echo $Type?>%">
				<div class="OrgangeFont bld pd_left_10p">Search Result Type</div>
				<div class="lineSpace_5">&nbsp;</div>
				<?php if(count($cluster['type'])>5){ ?>
				<div class="mar_full_10p borderSearchResult" style="height:97px; overflow-y:auto">
				<?php } else { ?>
					<div class="mar_full_10p" style="height:97px; overflow-y:auto">
					<?php }?>			
					<div style="width:85%; li" class="pd_left_5p">
					<?php
						$type = $cluster['type'];
						$count = count($type);
                        $selectedLeftType = '0';
                        if(isset($_REQUEST['subType']) && $_REQUEST['subType'] != '' && $_REQUEST['subType']!= '0') {
                            $selectedLeftType = '1';
                        }
						if(is_array($type)):
                        if(isset($_REQUEST['searchType']) && $_REQUEST['searchType'] != "") {
                            $allType = $_REQUEST['searchType'];
							foreach($type as $typeName) {
							    $displyType = strtolower($typeName);
							    $displyType = ($displyType == 'msgbrd')? 'Forum':$displyType;
							    $displyType = ($displyType == 'institute')? 'college':$displyType;
							    $displyType = ($displyType == 'kumkum')? 'Articles by Kum Kum Tandon':$displyType;
							    $displyType = ($displyType == 'Web')? 'Web Search Result':$displyType;
                                if($_REQUEST['searchType'] == $displyType){
                                    $allType = 0;
                                    break;
                                }
                            } 
                        }
					?>
					<?php endif; ?>
					 <div id="1_type">
						<div id="typeCluster">
														<?php if ($selectedLeftType==0) {?>
	
							<div id="0_leftSubType" onClick="return showResultsForType('<?php echo $allType; ?>', <?php echo $count; ?>, 0);" class="">
								<a href="javascript:void(0);" class="disBlock bld blackFont" style="font-size:11px" title="All"> <span class="redcolor">&raquo;</span> All <span id="allTypeCountPlace"></span></a>
							</div>
		<?php 
				$selectedValue="All";
			} else { ?>
	
							<div id="0_leftSubType" onClick="return showResultsForType('<?php echo $allType; ?>', <?php echo $count; ?>, 0);" class="">
								<a href="javascript:void(0);" class="disBlock" style="font-size:11px" title="All">All <span id="allTypeCountPlace"></span></a>
							</div>
	
							<?php
								}
								$Id = 0;
								$totalResultCount = 0;
                                $typePref= array('institute'=>10,'course'=>9,'scholarship'=>8,'kumkum'=>7,'exam'=>6,'Event'=>5,'user'=>4,'msgbrd'=>3,'misc'=>0);
                                $sortArray = array();
                                foreach($type as $typeName => $typeCount)
                                {
                                    if(isset($typePref[$typeName]))
                                    {
                                    $sortArray[$typeName] = $typePref[$typeName];
                                    }
                                    else
                                    {
                                    $sortArray[$typeName] = 1;
                                    }
                                }
                                array_multisort($sortArray, SORT_DESC, $type);
								if(is_array($type))
								foreach($type as $typeName => $typeCount) {
								$Id++;
								$totalResultCount += $typeCount;
								$onClick =  "return showResultsForType('". $typeName ."','".  $count ."', '". $Id ."');";
								$displyType = $typeName;
                                switch($displyType)
                                {
                                    case 'msgbrd':
                                        $displyType='Forums';
                                        break;
                                    case 'institute':
                                        $displyType='Institutes';
                                        break;
                                    case 'Event':
                                        $displyType='Imp Dates';
                                        break;
                                    case 'kumkum':
                                        $displyType='Articles by Kum Kum Tandon';
                                        break;
                                    case 'exam':
                                        $displyType='Examinations';
                                        break;
                                    case 'user':
                                        $displyType='Shiksha Articles';
                                        break;
                                    case 'misc':
                                        $displyType='Web Search Results';
                                        break;
                                    case 'schoolgroups':
                                        break;
                                    default:
                                        $displyType=$displyType."s";
                                        break;
                                }
							?>
							<?php if ($selectedLeftType==$Id) {?>
							<div id="<?php echo $Id; ?>_leftSubType">
								<a href="javascript:void(0);" onClick="<?php echo $onClick;?>" class="disBlock bld blackFont" style="font-size:11px" title="<?php echo ucwords($displyType);?>"> <span class="redcolor">&raquo;</span> 
									<?php echo ucwords($displyType); ?> <span id="typeCount_<?php echo $typeName;?>">(<?php echo $typeCount; ?>)</span>
								</a>
							<?php $selectedValue=ucwords($displyType);?>
							</div>
							<?php } else { ?>
							<div id="<?php echo $Id; ?>_leftSubType">
								<a href="javascript:void(0);" onClick="<?php echo $onClick;?>" class="disBlock" style="font-size:11px" title="<?php echo ucwords($displyType);?>">
									<?php echo ucwords($displyType); ?> <span id="typeCount_<?php echo $typeName;?>">(<?php echo $typeCount; ?>)</span>
								</a>
							</div>
								
							<?php
									}
								}
							?>
                            <?php
                            switch(trim($_REQUEST['searchType']))
                            {
                                case 'course': $searchType='course';
                                    break;
                                case 'institute': $searchType='institute';
                                    break;
                                case 'forums': $searchType='msgbrd';
                                    break;
                                case 'events': $searchType='eventAdm';
                                    break;
                                case 'scholarship': $searchType='scholarship';
                                    break;
                                default: $searchType=0;
                                    break;
                            }
                            ?>
						</div>
					</div>
</div>
				</div>
			</div>
							<input type="hidden" name="type" id="type" value="<?php echo $selectedValue; ?>" autocomplete="off"/>

