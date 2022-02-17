<div id="Main" style="display: none; width: 500px; height: 300px; ">
<div class="layer-outer" style="width:auto; font-family: Trebuchet MS;">
    <div class="layer-contents">
        <?php if (empty($entityData)){
                    echo '<b>No courses for these forms exist.</b>
                    <input type="button" value="Cancel" class="orange-button" onclick="hideListingsOverlay(true);" />';
                }
                else{ ?>
        <form id="Customized_Mapping_form">
        <div class="layer-head">
            <a href="javascript:void(0);" class="flRt sprite-bg cross-icon" onclick="hideListingsOverlay(true);" title="Close"></a>
            <div class="layer-title-txt" align="center" style="font-family: Trebuchet MS; font-size: large;"><b>Customized Fields Mapping</b></div>
        </div>
        <div>
            <div style="height: 300px; overflow-y: auto;">
            <table>
                <th><div><strong>Entity ID</strong></div></th>
                <th><div><strong>Entity Name</strong></div></th>
                <th><div><strong>Customized Field</strong></div></th>
            <?php
                foreach($entityData as $entity) {
                    echo '<tr>
                            <input type="hidden" name="courseId[]" value="'. $entity['course_id'] .'"/>
                            <td><span id="courseId_'.$entity['course_id'].'" name="courseId[]" value="'.$entity['course_id'].'">'. $entity['course_id'] .'</span></td>
                            <td><span id="courseName_'.$entity['course_id'].'" name="courseName[]" value="">'. $entity['name'] .'</span></td>
                            <td><input id="custom_field_'.$entity['course_id'].'" name="customFieldId[]" type="text" style="width:100px;" class="universal-txt-field" value="'.$mappedFields[$entity['course_id']].'" required="true" maxlength="200" minlength="1"/></td>
                          </tr>';
                }
            ?>
            </table>
            </div>
            <br/> <br/>
            <div class="form-fields">
            <div align="center">
                <input type="button" value="Save" class="orange-button" onclick="setOAFCustomizedFields('<?php echo $clientId; ?>','<?php echo $customField; ?>');"/>
                <input type="button" value="Cancel" class="orange-button" onclick="hideListingsOverlay(true);" />
            </div>
            </div>
        </div>
        </form>
        <?php } ?>
    </div>
</div>
</div>
