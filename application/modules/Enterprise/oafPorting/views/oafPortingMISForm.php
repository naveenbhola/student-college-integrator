                <form id="portingMisForm" autocomplete="off" action="/oafPorting/OafPorting/misData" method="POST">
                    <ul>
                        <li>
                            <input type="hidden" id="clientid" name="clientid" value="<?php echo $clientid; ?>" />
                            <label>Select Porting:</label>
                            <div class="form-fields">
                                <div class="select-porting">
                                <?php if(count($oaf_porting)> 0) { ?>
                                    <ol id="oafporting_holder">
                                        <li><input type="checkbox" id="oafporting" onClick="checkUncheckChilds1(this,'oafporting_holder');" /> <b>All OAF Portings</b></li>
                                        <?php foreach($oaf_porting as $k=>$v){ ?>
                                            <li class="sub-portings"><input type="checkbox" name="portings[]" onClick="uncheckElement1(this, 'oafporting', 'oafporting_holder');" value="<?php echo $v['id']; ?>" /> <?php echo $v['name'];?> <b>(<?php echo $v['id']; ?>)</b></li>
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