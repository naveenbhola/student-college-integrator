<div id="selectnduseshoshkele" style="display:none;z-index:90">
    <form
          action="<?php echo site_url() . 'nationalCategoryList/CategoryProductEnterprise/addShoshkele'; ?>"
          method="post" name="selectShoshkeleForm" id="selectShoshkeleForm"
          autocomplete="off" id="selectnduseform" novalidate>
        <input id="clientId" name="clientId" value="<?php echo $clientId; ?>" type="hidden"/>
        <input id="bannerId" name="bannerId" value="" type="hidden"/>

        <div>
            <div class="lineSpace_10"></div>
            <div class="normaltxt_11p_blk lineSpace_20 float_L"></div>
            <div class="row">
                <div>
                    <div id="shoshkeleName" style="font-size:17px;"></div>
                    <br/>
                    <select id="subscription_id" validate="validateSelect" caption="Subscription To Proceed"
                            required="true" name="subscription_id" minlength="1" maxlength="100" caption="Pack"
                            style="width:100%;" onchange="Shoshkele.getSubscriptionDetails();">
                        <option value="" selected>Select Subscription</option>
                        <?php
                        foreach ($subscriptionDetails as $key => $value) {
                            if( $value['BaseProdCategory']=='Category-Sponsor'){
                                ?>
                                <option
                                    value="<?php echo implode("|", $value); ?>"><?php echo $value['BaseProdCategory'], "-", $value['BaseProdSubCategory'], " : ", $key; ?></option>
                                <?php
                            }
                        } ?>
                    </select>
                    <div>
                        <div id="subscription_id_error" class="errorMsg" style="padding-left:92px"></div>
                    </div>

                    <div>
                        <!-- To show subscription details -->
                        <table>
                            <tr>
                                <th align="left">Start Date</th>
                                <td>:</td>
                                <td align="right">
                                    <div id="startDateSubscription">N/A</div>
                                </td>
                            </tr>
                            <tr>
                                <th align="left">Expiry Date</th>
                                <td>:</td>
                                <td align="right">
                                    <div id="endDateSubscription">N/A</div>
                                </td>
                            </tr>
                            <tr>
                                <th align="left">Listings Remaining</th>
                                <td>:</td>
                                <td align="right">
                                    <div id="qtyRemainingSubscription">N/A</div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <div id="subscription_id_error" class="errorMsg" style="padding-left:92px"></div>
                    </div>
                    <div class="lineSpace_10">&nbsp;</div>
                    <hr style="color:#aaa;"/>
                    <br/><br/>


                    <div class="lineSpace_10">&nbsp;</div>
                    <div class="row" id="selectCity">
                        <div>
                            <select id="cities" name="cities" caption="city" style="width:100%;">
                                <option value=''>Select a City</option>
                            </select>
                        </div>

                        <div id="OR1" style="margin-top:10px;margin-left:120px;font-weight:bold">
                            <div class="lineSpace_10">OR</div>
                        </div>
                        <div class="lineSpace_10">&nbsp;</div>
                        <div>
                            <select id="states" name="states"
                                    caption="state" style="width:100%;">
                                <option value=''>Select a State</option>
                            </select>
                        </div>
                        <div>
                            <div id="states_error" class="errorMsg" style="padding-left:92px"></div>
                        </div>
                        <div class="lineSpace_10">&nbsp;</div>
                        <div class="lineSpace_10">&nbsp;</div>
                        <div class="lineSpace_10">&nbsp;</div>


                        <div class="row" id="categoriesdiv">
                            <div>
                                <select name="criterion" id="criterion" caption="Criterion" style="width:100%;">
                                    <option value=''>Select a Criterion</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <div id="criterion_error" class="errorMsg" style="padding-left:92px"></div>
                        </div>
                        <div id="response_error" class="errorMsg" style="padding-left:92px"></div>

                        <div class="lineSpace_10">&nbsp;</div>
                    </div>

                    <div class="lineSpace_8">&nbsp;</div>
                    <div class="row" style="">
                        <div class="buttr3">
                            <input type="submit" id="addShoshkele" class="orange-button" value="Add" onclick="Shoshkele.addShoshkele();" />
                        </div>
                        <div class="buttr3">
                            <input type="reset" class="orange-button" value="Cancel" onclick="anotheraction = 1; hideOverlay()"/>
                        </div>
                        <br clear="left"/>
                    </div>
                </div>
                <span id="nr" style="display:inline"></span>
            </div>
        </div>
    </form>
    <div class="lineSpace_10">&nbsp;</div>
</div>