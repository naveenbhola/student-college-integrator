<?php
    $isMainBoxClosed = in_array($prefillData['searchType'],array('closedSearch','mainBoxClosedSearch'));
    $disabledMainBox = ($prefillData['mainSearchBoxText']!='' && $isMainBoxClosed?'disabled="disabled"':'');
    $showClearMainBox = ($prefillData['mainSearchBoxText']==''?'':'style="display:inline"');
    $locBoxDisabled = $locBoxDisabledClass = ($prefillData['autoSelectedMain']['type'] == 'course'?'':'disabled');
    $locations = $prefillData['locations'];
?>
<div class="abroadSearch">
    <a class="close-i searchSprite" id="srchClsIcn"></a>
    <div class="searchContent">
        <div class="optionsColumn">
        <div class="sugstrLocation">
          <div class="inputDiv">
            <input type="text" id="mainSearchBox" name="mainSearchBox" placeholder="Enter course, college or exam" value="<?php echo $prefillData['mainSearchBoxText']; ?>" <?php echo $disabledMainBox; ?> />
            <a class="clrSearchBox hide" id="clearMainSearchBox" href="Javascript:void(0);" <?php echo $showClearMainBox; ?> ><i class="searchSprite rmvSml-icn"></i></a>
            <input type="hidden" id="autoSelectedMain" value="<?php echo (count($prefillData['autoSelectedMain'])>0 && $isMainBoxClosed?base64_encode(json_encode($prefillData['autoSelectedMain'])):''); ?>">
            <div class="sugstrBlock" id="mainSuggs">
              <?php //$this->load->view('commonModule/layers/searchLayerWidgets/mainSearchSuggestions'); ?>
              <ul class="srchFilterUl"></ul>
            </div>
          </div>
          <div class="inputDiv">
            <div class="locationAdd <?php echo $locBoxDisabledClass; ?>" contenteditable="false">
              
                <ul class="addPlaces">
                    <li id="addedLocs" <?php echo (count($locations)>0?'style="display:inline-block;"':''); ?> >
                        <?php if(count($locations)==0){ ?>
                        <div class="boxtext">
                        <span class="textFit"></span><span class="closeBox"></span>
                        </div>
                        <?php }else{
                            $i=0;
                            foreach($locations as $locData){ 
                                $display = ($i++==0?'':'style="display:none;"'); ?>
                        <div class="boxtext" locid="<?php echo $locData['locId']; ?>" title="<?php echo $locData['locName']; ?>" <?php echo $display; ?> >
                        <span class="textFit"><?php echo $locData['locName']; ?></span><span class="closeBox"></span>
                        </div>
                        <?php } 
                        } ?>
                    </li>
                    <li id="numAddedLocs" <?php echo (count($locations)>1?'style="display:inline-block;"':'style="display:none;"'); ?>>
                        <div class="addMany">
                        <span class="textFit"><?php echo "+".(count($locations)-1)." more"; ?></span>
                        </div>
                    </li>
                    <li id="univLocationLabel" class="hide"><span></span></li>
                    <?php $locPlaceholder = (count($locations)>0?'Add more':'Location'); 
                        $locWidth = (count($locations)>0?'style="width: 30%; display: inline-block;"':'');
                        $hideClass = (count($locations) >= 4?'class="hideLocation"':'');
                        $displayBox = (count($locations) >= 4?'style="display:none;"':'');
                    ?>
                    <li id="locInputContainer" <?php echo $locWidth; ?> <?php echo $hideClass; ?>>
                        <input type="text" placeholder="<?php echo $locPlaceholder; ?>" class="loc_placeholder" id="locInput" <?php echo $locBoxDisabled; ?> <?php echo $displayBox; ?> />
                        <a class="clrSearchBox hide" id="clearLocSearchBox" href="Javascript:void(0);" ><i class="searchSprite rmvSml-icn"></i></a>
                    </li>
                </ul>
               
            </div>
            <input type="hidden" id="autoSelectedLocation" value="<?php echo (count($locations)>0?base64_encode(json_encode($locations)):''); ?>">
            <div class="sugstrBlock" id="locSuggs"></div>
          </div>
          <button type="submit" class="Btn SearchBox-submitBtn" id="searchGoButton">Search</button>
        </div>
        
        <!--Recent search block-->
        <div class="recent-search-block" id="recentSearchBox"></div>
        <!--Advance options html- -->
        <div class="advanceBlock" <?php echo ($isMainBoxClosed?'style="display:block;"':'style="display:none;"'); ?>>
          <fieldset class="activeBlock">
            <legend>Advanced Options</legend>
                <div class="sliderBlock courseAdvOpt">
                    <p class="dropLabel">1st Year Total Cost <span>(Tuition + Expenses)</span></p>
                    <div class="feeFilterNA filterNA" style="display: none">This filter option is not available.</div>
                    <div>
                        <span class="leftLabel"></span>
                        <span class="rightLabel"></span>
                        <div id="feeSlider"></div>
                        <input type="hidden" id="courseFeeData" value="">
                    </div>

                </div>
                <div class="examBlock courseAdvOpt">
                    <p class="dropLabel">Exam & Scores</p>
                    <div class="">
                        <div class="makeDrop" onclick="toggleShowOptions(this);">
                            <p class="changeTxt">Select Exam</p>
                            <i class="rotateArw"></i>
                            <div class="showOptns">
                                <ul>
                                </ul>
                            </div>
                        </div>
                        <div class="examScoreFilterNA filterNA" style="display: none">This filter option is not available.</div>
                        <div>
                            <span class="leftLabel"></span>
                            <span class="rightLabel"></span>
                             <div id="examScoreSlider"></div>
                            <input type="hidden" id="examAdvOpt" value="">
                            <input type="hidden" id="examWithScoreData" value="">
                        </div>

                    </div>
                </div>


              <div class="sliderBlock univAdvOpt">
                  <p class="dropLabel">Course Level</p>
                  <div>
                      <div class="makeDrop" onclick="toggleShowOptions(this);">
                          <p class="changeTxt">All Course Level</p>
                          <i class="rotateArw"></i>
                          <div class="showOptns">
                              <ul>
                              </ul>
                          </div>
                      </div>
                      <input type="hidden" id="levelAdvOpt" value="">
                  </div>
              </div>

              <div class="examBlock univAdvOpt">
                  <p class="dropLabel">Course Stream</p>
                  <div>
                      <div class="makeDrop" onclick="toggleShowOptions(this);">
                          <p class="changeTxt">All Course Stream</p>
                          <i class="rotateArw"></i>
                          <div class="showOptns">
                              <ul>
                              </ul>
                          </div>
                      </div>
                      <input type="hidden" id="streamAdvOpt" value="">
                  </div>
              </div>
                <!--</div>-->
          </fieldset>
        </div>
        </div>
    </div>
  </div>
</div>
