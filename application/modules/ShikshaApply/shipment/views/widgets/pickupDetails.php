<div class="smpl-box">
    <div class="head-col">
        <h2 class="color-3 f18 f-semi">Sender and pick up address details</h2>
        <p class="f12 color-6">Enter location for DHL to collect your application documents</p>
    </div>
    <div class="form-col">
        <ul class="form-ul">
            <li>
                <label class="color-6 i-block f14">First Name</label>
                <div class="lft-sec">
                    <input type="text" id="firstname" placeholder="Enter first name" class="field-nrml shippingInformationFields" value="<?php echo $pickupData['firstname']?>" sepcialCharactorAllowed="0" mandatory="1" maxlength="35" minlength="1"/>
                </div>
                <span class="snd-err-mg" style="display:none;">Please enter valid sender's first name</span>
            </li>
            <li>
                <label class="color-6 i-block f14">Last Name</label>
                <div class="lft-sec">
                    <input type="text" id="lastname" placeholder="Enter last name" class="field-nrml shippingInformationFields" value="<?php echo $pickupData['lastname']?>" sepcialCharactorAllowed="0" mandatory="1" maxlength="35" minlength="1"/>
                </div>
                <span class="snd-err-mg" style="display:none;">Please enter valid sender's last name</span>
            </li>
            <li>
                <label class="color-6 i-block f14">Address Line1</label>
                <div class="lft-sec">
                    <input type="text" id="addrLine1" placeholder="Enter Address Line1" class="field-nrml shippingInformationFields" maxlength="35" mandatory="1"/>
                </div>
                <span class="snd-err-mg" style="display:none;">Please enter address line 1</span>
            </li>
            <li>
                <label class="color-6 i-block f14">Address Line2</label>
                <div class="lft-sec">
                    <input type="text" id="addrLine2" placeholder="Enter Address Line2" class="field-nrml" maxlength="35"/>
                </div>
            </li>
            <li>
                <label class="color-6 i-block f14">City </label>
                <div class="lft-sec lft-b-sec">
                    <select id="pickupCity" class="ct-slct f12 color-6" mandatory="1">
                        <option value="" >Select a city </option>
                        <?php
                            foreach($dhlCityData as $cityId => $data){
                                $isSelected = '';
                                if($data['id'] == $pickupData['cityDetails']['id']) $isSelected = "selected='selected'";
                        ?>
                                <option value="<?=$data['id']?>" stateName="<?=$data['stateName']?>" stateId="<?=$data['stateId']?>" <?=$isSelected?>>
                                    <?=$data['name']?>
                                </option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <span class="snd-err-mg" style="display:none;">
                Please select a city
                </span>
            </li>
            <li>
                <label class="color-6 i-block f14">State</label>
                <div class="lft-sec">
                    <p class="f12 color-3" id="pickupState" mandatory="1">
                        <?php 
                            if($pickupData['cityDetails']['stateName']){
                                echo $pickupData['cityDetails']['stateName'];
                            }else{
                                echo '-';
                            }
                        ?>
                    </p>
                </div>
            </li>
            <li>
                <label class="color-6 i-block f14">Postal Code</label>
                <div class="lft-sec lft-b-sec">
                    <select class="ct-slct f12 color-6" id="pickupPincodes" mandatory="1">
                        <option value=""> Select pin code </option>
                        <?php
                            foreach($pickupData['cityDetails']['pincodes'] as $pincode){
                        ?>
                                <option value="<?=$pincode?>"><?=$pincode?></option>
                        <?php
                            }
                        ?>
                    </select>
                </div>
                <span class="snd-err-mg" style="display:none;">Please select user's postal code</span>
                <p class="pin-txt">Select pin code of nearest locality in case your area does not appear</p>
            </li>
            <li>
                <label class="color-6 i-block f14">Country</label>
                <div class="lft-sec">
                    <p class="f12 color-3">India</p>
                </div>
            </li>
            <li>
                <label class="color-6 i-block f14">Mobile</label>
                <div class="lft-sec">
                    <p class="f12 color-3"><?=$pickupData['mobile']?></p>
                </div>
            </li>
            <li>
                <label class="color-6 i-block f14">Email ID</label>
                <div class="lft-sec">
                    <p class="f12 color-3"><?=$pickupData['email']?></p>
                </div>
            </li>
        </ul>
        <div id="pickupTimings" class="rc-col">
            <?php $this->load->view('shipment/widgets/pickupDateOptions'); ?>
        </div>
    </div>
</div>