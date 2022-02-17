                <form id="portingMisForm" autocomplete="off" action="misData" method="POST">
                    <ul>
                        <li>
                            <input type="hidden" id="clientid" name="clientid" value="<?php echo $clientid; ?>" />
                            <label>Select Porting:</label>
                            <div class="form-fields">
                                <div class="select-porting">
                                <?php if(count($response_duration)> 0) { ?>
                                    <ol id="responsedurationporting_holder">
                                        <li><input type="checkbox" id="responsedurationporting" onClick="checkUncheckChilds1(this, 'responsedurationporting_holder');" /> <b>All Responses (Duration) Portings</b></li>
                                        <?php foreach($response_duration as $k=>$v){ ?>
                                            <li class="sub-portings"><input type="checkbox" name="portings[]" onClick="uncheckElement1(this, 'responsedurationporting', 'responsedurationporting_holder');" value="<?php echo $v['portingid']; ?>" /> <?php echo $v['name'];?> <b>(<?php echo $v['portingid']; ?>)</b></li>
                                        <?php } ?>
                                    </ol>
                                <?php } ?>
                                <?php if(count($lead_duration)> 0) { ?>
                                    <ol id="leaddurationporting_holder">
                                        <li><input type="checkbox" id="leaddurationporting" onClick="checkUncheckChilds1(this, 'leaddurationporting_holder');" /> <b>All Leads (Duration) Portings</b></li>
                                        <?php foreach($lead_duration as $k=>$v){ ?>
                                            <li class="sub-portings"><input type="checkbox" name="portings[]" onClick="uncheckElement1(this, 'leaddurationporting', 'leaddurationporting_holder');" value="<?php echo $v['portingid']; ?>" /> <?php echo $v['name'];?> <b>(<?php echo $v['portingid']; ?>)</b></li>
                                        <?php } ?>
                                    </ol>
                                <?php } ?>
                                <?php if(count($lead_quantity)> 0) { ?>
                                    <ol id="leadquantityporting_holder">
                                        <li><input type="checkbox" id="leadquantityporting" onClick="checkUncheckChilds1(this, 'leadquantityporting_holder');" /> <b>All Leads (Quantity) Portings</b></li>
                                        <?php foreach($lead_quantity as $k=>$v){ ?>
                                            <li class="sub-portings"><input type="checkbox" name="portings[]" onClick="uncheckElement1(this, 'leadquantityporting', 'leadquantityporting_holder');" value="<?php echo $v['portingid']; ?>" /> <?php echo $v['name'];?> <b>(<?php echo $v['portingid']; ?>)</b></li>
                                        <?php } ?>
                                    </ol>
                                <?php } ?>
                                <?php if(count($exam_response)> 0) { ?>
                                    <ol id="examresponseporting_holder">
                                        <li><input type="checkbox" id="examresponseporting" onClick="checkUncheckChilds1(this, 'examresponseporting_holder');" /> <b>All Exam Response Portings</b></li>
                                        <?php foreach($exam_response as $k=>$v){ ?>
                                            <li class="sub-portings"><input type="checkbox" name="portings[]" onClick="uncheckElement1(this, 'examresponseporting', 'examresponseporting_holder');" value="<?php echo $v['portingid']; ?>" /> <?php echo $v['name'];?> <b>(<?php echo $v['portingid']; ?>)</b></li>
                                        <?php } ?>
                                    </ol>
                                <?php } ?>
                                </div>
                            </div>
                        </li>
                        
                        <li>
                            <div class="porting-duration">
                            <label>Duration:</label>
                            
                            <div class="form-fields">
                            <div class="flLt">
                                <label style="width:auto">From &nbsp; </label>
                                <input type="text" style="width:75px; color:#888a89" value="yyyy-mm-dd" readonly="true" name="timerange_from" id="timerange_from">&nbsp;&nbsp; <img style="cursor:pointer;" src="/public/images/calen-icn.gif" id="timerange_from_img" onclick="timerangeFrom();">
                            </div>
                            <div class="flLt">
                                <label style="width:auto">To &nbsp;</label>
                                <input type="text" style="width:75px; color:#888a89" value="yyyy-mm-dd" readonly="true" name="timerange_to" id="timerange_to">&nbsp;&nbsp; <img style="cursor:pointer;" src="/public/images/calen-icn.gif" id="timerange_to_img" onclick="timerangeTo();">
                            </div>
                            </div>
                            </div>
                        </li>
                        
                        <li>
                            <label>Report Type:</label>
                            <div class="form-fields">
                                <div><input type="radio" name="report_type" value="number" onClick="select_report_type(this);" /> Total Numbers &nbsp; <select name="report_days" id="report_days" style="width:120px" disabled ><option value="">Select</option>
                                <option value="D">Daily</option>
                                <option value="W">Weekly</option>
                                <option value="M">Monthly</option></select>
                                </div>
                                <div class="spacer5 clearFix"></div>
                                <div><input type="radio" name="report_type" value="data" onClick="select_report_type(this);" /> Actual Data</div>
                                <div class="clearFix spacer20"></div>
                                <input type="button" value="Submit" class="orange-button" onClick="doSubmitPortingMisForm();" />
                            </div>
                        </li>
                    </ul>
                </form>