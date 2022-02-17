<div id="Main" style="display: none; width: 1200px; height: 300px;position: relative;left: -360px; ">
<div class="layer-outer" style="width:auto; font-family: Trebuchet MS;">
    <div class="layer-contents">
        <?php if (empty($clientToEntityMapping[$clientId])){
                    echo '<b>No paid courses exist.</b>
                    <input type="button" value="Cancel" class="orange-button" onclick="hideListingsOverlay(true);" />';
                }
                else{ ?>
        <form id="Customized_Mapping_form" onsubmit="setCustomizedFields();">
        <div class="layer-head">
            <a href="javascript:void(0);" class="flRt sprite-bg cross-icon" onclick="hideListingsOverlay(true);" title="Close"></a>
            <div class="layer-title-txt" align="center" style="font-family: Trebuchet MS; font-size: large;"><b>Customized Fields Mapping</b></div>
        </div>
        <div>

            <div style="height: 300px; overflow-y: auto;">
            <table class="porting-table">
                <th width="110px;"><div><strong><?php echo $entity_id_text; ?></strong></div></th>
                <th width="300px"><div><strong><?php echo $entity_name_text; ?></strong></div></th>
                <th><div><strong>Customized Field</strong></div></th>
            <?php
                $entityIds = explode(',',$clientToEntityMapping[$clientId]);
                foreach($entityIds as $entityId) {
                    echo '<tr>
                            <input type="hidden" name="entityId[]" value="'. $entityId .'"/>
                            <td><span id="entityId_'.$entityId.'" name="entityId[]" value="'.$entityId.'">'. $entityId .'</span></td>
                            <td><span id="entityName_'.$entityId.'" name="entityName[]" value="">'. $entityNameMapping[$entityId] .'</span></td>
                            <td><input id="custom_field_'.$entityId.'" name="customFieldId[]" type="text" style="box-sizing: border-box;" class="universal-txt-field" value = "'.htmlentities($mappedFields[$entityId]).'" required="true" maxlength="200" minlength="1"/></td>
                          </tr>';
                }
            ?>
           <!--  <tr>
                <input type="hidden" name="entityId[]" value="-1"/>
                <td></td>
                <td><span id="entityName_dummy" name="entityName[]" value=""><?=Inst_Viewed_Action_Course?></span></td>
                <td><input id="custom_field_dummy" name="customFieldId[]" type="text" style="box-sizing: border-box;" class="universal-txt-field" value="<?=$dummyMappedFields[-1]?>" required="true" maxlength="50" minlength="1"/></td>
            </tr> -->
            </table>
            </div>


            <?php if(!empty($instituteNameMapping)){?>
            <div style="height: 100px; overflow-y: auto;">
            <table class="porting-table">
                <th width="110px;"><div><strong>Institute ID</strong></div></th>
                <th width="300px"><div><strong>Institute Name (IVR)</strong></div></th>
                <th><div><strong>Customized Field</strong></div></th>
            <?php

                $insticustomField = false;

                foreach($instituteNameMapping as $instituteId => $instituteName) {
                    $insticustomField = true;
                    echo '<tr>
                            <input type="hidden" name="instituteId[]" value="'. $instituteId .'"/>
                            <td><span id="entityId_'.$instituteId.'" name="instituteId[]" value="'.$instituteId.'">'. $instituteId .'</span></td>
                            <td><span id="entityName_'.$instituteId.'" name="instituteName[]" value="">'. $instituteName .'</span></td>
                            <td><input id="custom_field_'.$instituteId.'" name="insticustomFieldId[]" type="text" style="box-sizing: border-box;" class="universal-txt-field" value="'.$mappedFields[$instituteId].'" required="true" maxlength="200" minlength="1"/></td>
                          </tr>';
                }
                
            ?>
            
            <?php if ($insticustomField) { ?>
                <input type="hidden" name="insticustomField" value="1">

            <?php  } ?>
            
            <!-- <tr>
                <input type="hidden" name="entityId[]" value="-1"/>
                <td></td>
                <td><span id="entityName_dummy" name="entityName[]" value=""><?=Inst_Viewed_Action_Course?></span></td>
                <td><input id="custom_field_dummy" name="customFieldId[]" type="text" style="box-sizing: border-box;" class="universal-txt-field" value="<?=$dummyMappedFields[-1]?>" required="true" maxlength="50" minlength="1"/></td>
            </tr> -->
            </table>
            </div>
            <?php } ?>

            <br/> <br/>
            <div class="form-fields">
            <div align="center">
                <input type="button" value="Save" class="orange-button" onclick="setCustomizedFields('<?php echo $clientId; ?>','<?php echo $customField; ?>');"/>
                <input type="button" value="Cancel" class="orange-button" onclick="hideListingsOverlay(true);" />
            </div>
            </div>
        </div>



        </form>
        <?php } ?>
    </div>
</div>
</div>
