<div class="smpl-box1">
           <div class="head-col">
             <h2 class="color-3 f18 f-semi">University destination address details</h2>
             <p class="f12 color-9">Enter details of university where you want your documents to be sent </p>
           </div>
           <div class="form-col">
            <section class="nform-col pck">
              <ul class="form-ul">
              <li>
                  <label class="color-6 i-block f14">Package Weight</label>
                  <div class="lft-sec">
                     <div class="lft-sec lft-b-sec">
                        <select class="ct-slct f12 color-6" id="weight">
                        <?php
                          foreach ($pickageWeight as $key => $value) { ?>
                            <option value="<?php echo $key;?>" class="color-6"><?php echo $value?></option>
                        <?php  }
                        ?>
                        </select>
                  </div>
                  </div>
                </li>
                <li>
                  <label class="color-6 i-block f14">Country</label>
                  <div class="lft-sec lft-b-sec">
                    <select class="ct-slct f12 color-6" mandatory="1" id="destinationCountry">
                    <option value="">Select a country </option>
                    <?php foreach ($destinationCountries as $key => $counrtyDetails) {?>
                      <option value="<?php echo $counrtyDetails['countryId']?>" countryCode="<?php echo $counrtyDetails['countryCode']?>" postCodeRequired="<?php echo $counrtyDetails['postCodeRequired']?>" class="color-6" countryName=<?php echo $counrtyDetails['countryName']?>><?php echo $counrtyDetails['countryName']?></option>
                    <?php  } ?>
                    </select>
                  </div>
                  <span class="snd-err-mg" style="display:none;">Please select a Country</span>
                </li>
                <li style="display:none">
                   <label class="color-6 i-block f14">Postal Code</label>
                   <div class="lft-sec">
                      <input type="text" placeholder="Enter Postal Code" class="field-nrml shippingInformationFields" mandatory="1" maxlength="12" id="destinationPostalCode"/>
                   </div>
                   <span class="snd-err-mg" style="display:none;">Please enter postal code</span>
                 </li>
                 <li style="display:none">
                   <label class="color-6 i-block f14">City </label>
                   <div class="lft-sec">
                      <input type="text" id="destCityForCheckPrice" placeholder="Enter City Name" class="field-nrml shippingInformationFields destCities" maxlength="35" id="destinationCity"/>
                   </div>
                   <span class="snd-err-mg" style="display:none;" >Please enter city name</span>
                 </li>

                 <li>
                 <label class="color-6 i-block f14"></label>
                   <div class="lft-sec">
                  <p class="price-tag color-3 f-bold f18" id="priceDetailsForCheckPrice" style="display:none"><!-- Price: Rs. 899/- --></p>
                  <p class="price-tag color-3 f-bold f18 " id="priceDetailsForCheckPrice_default"> Price: <span style=" font-weight: 400;font-size: 12px;">Please enter above fields to get price </span></p>
                  <a class="book-btn chck-avail" id="checkPriceAndAvail">Check Price and Availability</a>
                   </div>
                 </li>
                  
                </ul>
            </section>
            <section class="nform-col">
           
              <ul class="form-ul">
                
                 <li>
                   <label class="color-6 i-block f14">University/College Name</label>
                   <div class="lft-sec">
                      <input type="text" id="universityName" class="field-nrml destinationUniversity" mandatory="1" maxlength="35"/>
                   </div>
                   <span class="snd-err-mg" style="display:none;"></span>
                 </li>
                 <li>
                   <label class="color-6 i-block f14">Department/Person Name</label>
                   <div class="lft-sec">
                      <input type="text" id="departmentName" class="field-nrml shippingInformationFields" mandatory="1" maxlength="35"/>
                   </div>
                   <span class="snd-err-mg" style="display:none;">Please enter department / person name</span>
                 </li>
                  <li>
                   <label class="color-6 i-block f14">Course Name</label>
                   <div class="lft-sec">
                      <input type="text" id="courseName" class="field-nrml universityCourseName" mandatory="1" maxlength="150"/>
                   </div>
                   <span class="snd-err-mg" style="display: none;"></span>
                 </li>
                 <li>
                   <label class="color-6 i-block f14">Address Line1</label>
                   <div class="lft-sec">
                      <input type="text" id="destAddrLine1" placeholder="Enter Address Line1" class="field-nrml shippingInformationFields" mandatory="1" maxlength="35"/>
                   </div>
                   <span class="snd-err-mg" style="display:none;">Please enter address line 1</span>
                 </li>
                 <li>
                   <label class="color-6 i-block f14">Address Line2</label>
                   <div class="lft-sec">
                      <input type="text" id="destAddrLine2" placeholder="Enter Address Line2" class="field-nrml" maxlength="35"/>
                   </div>
                 </li>
                 <li>
                   <label class="color-6 i-block f14">City </label>
                   <div class="lft-sec">
                      <input type="text" id="destCity" placeholder="Enter City Name" class="field-nrml shippingInformationFields destCities" maxlength="35"/>
                   </div>
                   <span class="snd-err-mg" style="display:none;" >Please enter city name</span>
                 </li>
                 <li style="display:none;">
                   <label class="color-6 i-block f14">State</label>
                   <div class="lft-sec lft-b-sec">
                     <select class="ct-slct f12 color-6" id="destinationStates">
                       <option value="" class="color-6">Select a state </option>
                       <?php foreach ($USStateDetails as $stateCode => $stateName) { ?>
                          <option class="color-6" value="<?php echo $stateCode.'-'.$stateName;?>" class="color-6"><?php echo $stateName;?></option>
                       <?php } ?>
                     </select>
                   </div>
                   <span class="snd-err-mg" style="display:none;">Please select a state</span>
                 </li>
              </ul>

            </section>
                <div class="rc-col">
                  <ul class="form-ul">
                    <li>
                      <label class="color-6 i-block f14 v-top">Receiver Phone Number</label>
                      <div class="lft-sec">
                      <div style="float:left">

                          <div class="lft-b-sec code-sec" style="margin-right:10px">
                            <select class="ct-slct num-col f12 color-6"  id="isdCode">
                              <option value="">Select ISD code </option>
                            <?php 
                              foreach ($isdCodes as $key => $value) { ?>
                                <option value="<?php echo $key;?>" isdCodeForCountry = <?php echo $value['isdCode'];?> class="color-6"><?php echo $value['name'];?></option>
                            <?php  }
                            ?>
                            </select>
                          </div>
                          <input type="text" id="receiverPhoneNo" placeholder="Enter Number" class="num-dial destContectInfo" mandatory="1" maxlength="12" minlength="6" numeric="1"/>
                          <span class="snd-err-mg" id="isdCode_error" style="display:none;">Please ISD code</span>
                           <span class="snd-err-mg" id="receiverPhoneNo_error" style="display:none;">Please enter vaild phone number</span>

                         </div> 
                         
                          
                          <input type="text" id="extension" placeholder="Extension if any" class="ext-field destExtensionNo" minlength="1" maxlength="5" numeric="1"/>
                          <span class="snd-err-mg destExtensionNo_error" id="extension_error" style="display:none;">Please enter vaild extension number</span>
                      </div>
                    </li>

                  </ul>
                </div>
           </div>
         </div>
