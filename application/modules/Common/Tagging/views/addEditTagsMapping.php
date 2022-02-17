                            <div id="map_Error" class="errorMsg" style="text-align:center;"></div>
                    
                            <li><h3 class='customH3' style='text-align:center;'><?=$heading;?></h3></li>
                            <li style='margin-left : 120px;'>
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity1" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_1'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>" <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_1'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped1' maxlength='100'> 
                                     
                                     <span id="shikshaEntityToMapped1_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                            <li style='margin-left : 120px;' >
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity2" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_2'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>"  <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_2'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped2' maxlength='100'> 
                                     <span id="shikshaEntityToMapped2_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                            <li style='margin-left : 120px;' >
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity3" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_3'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>"  <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_3'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped3' maxlength='100'>
                                     <span id="shikshaEntityToMapped3_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                            <li style='margin-left : 120px;' >
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity4" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_4'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>"  <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_4'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped4' maxlength='100'> 
                                     <span id="shikshaEntityToMapped4_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                            <li style='margin-left : 120px;' >
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity5" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_5'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>"  <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_5'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped5' maxlength='100'> 
                                     <span id="shikshaEntityToMapped5_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                             <li style='margin-left : 120px;' >
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity6" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_6'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>" <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_6'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped6' maxlength='100'> 
                                     <span id="shikshaEntityToMapped6_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                             <li style='margin-left : 120px;' >
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity7" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_7'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>" <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_7'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped7' maxlength='100'> 
                                     <span id="shikshaEntityToMapped7_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                             <li style='margin-left : 120px;' >
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity8" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_8'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>" <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_8'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped8' maxlength='100'> 
                                     <span id="shikshaEntityToMapped8_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                             <li style='margin-left : 120px;' >
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity9" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_9'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>" <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_9'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped9' maxlength='100'> 
                                     <span id="shikshaEntityToMapped9_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                             <li style='margin-left : 120px;' >
                                <span class='customLabel'>Select Shiksha Entity</span>
                                <div class="shiksha_entity_div" style='display:inline;'>
                                    <select id="shikshaEntity10" name="shikshaEntity[]" class="universal-select cms-field" caption="Shiksha Entity" required="true" style='width:20%;'>
                                        <option value="">Select Shiksha Entity</option>
                                        <?php
                                            foreach($totalShikshaEntites as $data) {
                                                $selected = "";
                                                if($selectedData['selectedEntity_10'] == $data){
                                                    $selected = "selected";
                                                }
                                                ?>
                                                    <option value="<?=$data;?>" <?=$selected;?>><?=$data;?></option>              
                                                <?php
                                            }
                                        ?>
                                    </select>
                                
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <span class='customLabel'>Shiksha Entity ID</span>                                
                                     <input type="text" autocomplete="off" class="universal-txt-field cms-text-field"   style=" color:#565656;width:130px !important;" value="<?=$selectedData['selectedEntityId_10'];?>" name='shikshaEntityToMapped[]' id='shikshaEntityToMapped10' maxlength='100'> 
                                     <span id="shikshaEntityToMapped10_error" class="errorMsg" style="display:none;"></span>
                                </div> 

                            </li>
                            <?php
                            if(count($otherMappings) > 0){
                                ?>
                                <div id='extraMappings'>
                                    <li><h3 class='customH3' style='text-align:center;'>Others Mappings</h3></li>
                                    <?php
                                        foreach ($otherMappings as $key => $value) {
                                            ?>
                                            <li>
                                                <span class='customLabel'><?=$value['entity']?></span> 
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <input type="text" autocomplete="off" class="universal-txt-field cms-text-field otherMap"   style=" color:#565656;width:130px !important;" value="<?=$value['entityId'];?>"entity="<?=$value['entity']?>" maxlength='100' disabled> 
                                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a href='javascript:void(0)' class='removeOtherMap'>Remove</a>
                                            </li>
                                            <?php
                                        }
                                    ?>
                                </div>
                                <?php
                            }
                            ?>


                            