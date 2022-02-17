
<?php

if($type == 'city') { 
    $values = $fields['residenceCityLocality']->getValues(array('cities' => TRUE));
?>
<div class="text-show ">
    <label class="form-label">Residence City</label>
    <!-- <input type="text" name="ename" class="user-txt-flds" /> -->
    <input class="user-txt-flds ssLayerWOG" id="residenceCityLocality_<?php echo $regFormId; ?>_input" readonly="readonly" type="text">
        <a aria-expanded="false" aria-haspopup="true" aria-owns="myPopup" class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup"></a>
    </div>
    <div>
        <div class="regErrorMsg" id="residenceCityLocality_error_<?php echo $regFormId; ?>">
        </div>
    </div>
    <div class="select-Class" id="residenceCityLocality_block_<?php echo $regFormId; ?>" visible="Yes">
        <select class="select-hide" caption="city of residence" label="Residence Location" mandatory="1" regfieldid="residenceCityLocality" name="residenceCityLocality" id="residenceCityLocality_<?php echo $regFormId; ?>" onchange="shikshaUserProfileForm['<?php echo $regFormId; ?>'].getUserLocalities(this.value, '<?php echo $UserResidentCity; ?>')" >
            <?php $this->load->view('registration/common/dropdowns/residenceLocality', array('residenceCityLocality'=>$personalInfo['City'], 'isUnifiedProfile'=>'YES')); ?>
        </select>
    </div>
<?php }else if($type == 'locality'){ ?>
              
    <div class="text-show " >
        <label class="form-label">Resident Locality</label>
        <input class="user-txt-flds ssLayerWOG" id="residenceLocality_<?php echo $regFormId; ?>_input" readonly="readonly" type="text">
            <a aria-expanded="false" aria-haspopup="true" aria-owns="myPopup" class="ui-btn ui-btn-inline ui-corner-all select-hide" data-rel="popup" href="#myPopup"></a>
    </div>
    <div>
        <div class="regErrorMsg" id="residenceLocality_error_<?php echo $regFormId; ?>">
        </div>
    </div>
    <div class="select-Class">
        <select class="select-hide" name="residenceLocality" id="residenceLocality_<?php echo $regFormId; ?>">
            <option value="-1" >Locality </option>
        </select>
    </div>
<?php } ?>