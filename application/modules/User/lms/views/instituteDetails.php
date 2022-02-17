<div id="Main" style="display: none; width: 750px; height: 425px; ">
<div class="layer-outer" style="width:auto; font-family: Trebuchet MS;">
    <div class="layer-contents">
        <?php if (empty($clientToInstituteMapping[$clientId])){
                    echo '<b>No paid Institute exist.</b>
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
            <table>
                <?php 
                if ($customField == 'parent'){
                    ?>
                    <th><div><strong>Parent ID</strong></div></th>
                    <?php
                }
                else
                {
                    ?>
                    <th><div><strong>Primary ID</strong></div></th>
                    <?php
                
                }
                ?>
                    
                <th><div><strong>Institute Name</strong></div></th>
                <th><div><strong>Customized Field</strong></div></th>
            <?php
                $instituteIds = explode(',',$clientToInstituteMapping[$clientId]);
                foreach($instituteIds as $instituteId) {
                    echo '<tr>
                            <input type="hidden" name="instituteId[]" value="'. $instituteId .'"/>
                            <td><span id="instituteId_'.$instituteId.'" name="instituteId[]" value="'.$instituteId.'">'. $instituteId .'</span></td>
                            <td><span id="instituteName_'.$instituteId.'" name="instituteName[]" value="">'. $InstituteNameMapping[$instituteId] .'</span></td>
                            <td><input id="custom_field_'.$instituteId.'" name="customFieldId[]" type="text" style="width:100px;" class="universal-txt-field" value="'.$mappedFields[$instituteId].'" required="true" maxlength="200" minlength="1"/></td>
                          </tr>';
                }
            ?>
            <!-- <tr>
                <input type="hidden" name="instituteId[]" value="-1"/>
                <td></td>
                <td><span id="instituteName_dummy" name="instituteName[]" value=""><?=Inst_Viewed_Action_Course?></span></td>
                <td><input id="custom_field_dummy" name="customFieldId[]" type="text" style="width:100px;" class="universal-txt-field" value="<?=$dummyMappedFields[-1]?>" required="true" maxlength="50" minlength="1"/></td>
            </tr> -->
            </table>
            </div>
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
