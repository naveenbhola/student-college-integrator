<?php
        if ($isNewUser == false || $widget == 'credit' || $widget == 'leads') {
?>
<div class="ent-details">
		<?php
			if ($widget == 'response' || $widget == 'activity') {
		?>
        <div class="sorting">
                <strong>View By:</strong>
                <p>
                        <a href="javascript:void(0)" uniqueattr="Dashboard/<?php echo $widget; ?>/ViewByDay" id= "<?php echo $widget; ?>_view_by_day" is_active="false" graph_type="view_by_day" chart="<?php echo $widget; ?>" start_date="" end_date="" start_date_compare="" end_date_compare="" onclick="onClickAction(this);"  >Day</a> |
                        <a href="javascript:void(0)" uniqueattr="Dashboard/<?php echo $widget; ?>/ViewByWeek" id= "<?php echo $widget; ?>_view_by_week" is_active="false" graph_type="view_by_week" chart="<?php echo $widget; ?>" start_date="" end_date="" start_date_compare="" end_date_compare="" onclick="onClickAction(this);">Week</a> |
                        <a href="javascript:void(0)" uniqueattr="Dashboard/<?php echo $widget; ?>/ViewByMonth" id= "<?php echo $widget; ?>_view_by_month" is_active="false"  graph_type="view_by_month" chart="<?php echo $widget; ?>" start_date="" end_date="" start_date_compare="" end_date_compare="" onclick="onClickAction(this);">Month</a>
                </p>
        </div>
		<?php
			}
		?>
        <div class="select-details" id="<?php echo $widget; ?>_layer_action" chart="<?php echo $widget; ?>" onclick="hideDateLayer(this.getAttribute('chart'));">
                <p id="<?php echo $widget; ?>_range"></p>
                <p id="<?php echo $widget; ?>_duration"></p>
                <div class="drop-back">
                        <i class="drop-icon"></i>
                </div>
                <!--Popup Layer: Starts-->
                <div id="<?php echo $widget; ?>_popup_layer" class="popup-layer" style="display: none;">
                        <div class="popup-content">
                                <div class="quick-dateBox">
                                        <?php
                                        	if ($widget == 'response' || $widget == 'activity') {
                                        		$graph_type = "comparitive";
                                        		echo "<h6>Compare By:</h6>";
                                        	}
                                        	else {
                                        		$graph_type = "cumulative";
                                        		echo "<h6>Quick Dates:</h6>";
                                        	}
                                        ?>
                                        <p>
                                                <a href="javascript:void(0)" id= "<?php echo $widget; ?>_quick_link_1" uniqueattr="Dashboard/<?php echo $widget; ?>/QuickLink1" graph_type="<?php echo $graph_type; ?>" range="Last 7 Days" chart="<?php echo $widget; ?>" onclick="onClickAction(this);">Last 7 Days</a> |
                                                <a href="javascript:void(0)" id= "<?php echo $widget; ?>_quick_link_2" uniqueattr="Dashboard/<?php echo $widget; ?>/QuickLink2" graph_type="<?php echo $graph_type; ?>" range="Last 30 Days" chart="<?php echo $widget; ?>" onclick="onClickAction(this);">Last 30 Days</a> |
                                                <a href="javascript:void(0)" id= "<?php echo $widget; ?>_quick_link_3" uniqueattr="Dashboard/<?php echo $widget; ?>/QuickLink3" graph_type="<?php echo $graph_type; ?>" range="Last 90 Days" chart="<?php echo $widget; ?>" onclick="onClickAction(this);">Last 90 Days</a> |
                                                <a href="javascript:void(0)" id= "<?php echo $widget; ?>_quick_link_4" uniqueattr="Dashboard/<?php echo $widget; ?>/QuickLink4" graph_type="<?php echo $graph_type; ?>" range="This Month" chart="<?php echo $widget; ?>" onclick="onClickAction(this);">This Month:<?php echo date("M"); ?></a> |
                                                <a href="javascript:void(0)" id= "<?php echo $widget; ?>_quick_link_5" uniqueattr="Dashboard/<?php echo $widget; ?>/QuickLink5" graph_type="<?php echo $graph_type; ?>" range="Last Month" chart="<?php echo $widget; ?>" onclick="onClickAction(this);">Last Month:<?php echo date("M",strtotime("-1 month",time())); ?></a>
                                        </p>
                                </div>
                                <div class="clearFix"></div>
                                <h6>Date Range</h6>
                                <div class="date-range">
                                        <label>From</label>
                                        <input type="text" value="yyyy-mm-dd" readonly="true" name="timefilter[from]" id="<?php echo $widget; ?>_timerange_from" chart="<?php echo $widget; ?>" onchange="setDuration(this.getAttribute('chart'), this.name, this.value, '');"/>
                                        <i class="icon-cal" id="<?php echo $widget; ?>_timerange_from_img" chart="<?php echo $widget; ?>" name="cal_img" onclick="timerangeFrom(this.getAttribute('chart'), '');" style="cursor: pointer;"></i>
                                </div>
                                <div class="date-range">
                                        <label>To</label>
                                        <input type="text" value="yyyy-mm-dd" readonly="true" name="timefilter[to]" id="<?php echo $widget; ?>_timerange_to" chart="<?php echo $widget; ?>" onchange="setDuration(this.getAttribute('chart'), this.name, this.value, '');"/>
                                        <i class="icon-cal" id="<?php echo $widget; ?>_timerange_to_img" chart="<?php echo $widget; ?>" name="cal_img" onclick="timerangeTo(this.getAttribute('chart'), '');;" style="cursor: pointer;"></i>
                                </div>
                                <?php
                                        if ($widget == 'response' || $widget == 'activity') {
                                ?>
                                        <div class="clearFix"></div>
                                        <input id="<?php echo $widget; ?>_compare_date" type="checkbox" chart="<?php echo $widget; ?>" onclick="showCompareDate(this.checked, this.getAttribute('chart'));">
                                        Compare to
                                        <div class="clearFix"></div>
                                        <div id="<?php echo $widget; ?>_compare_date_box" style="display: none;">
                                                <div class="date-range">
                                                        <label>From</label>
                                                        <input type="text" value="yyyy-mm-dd" readonly="true" name="timefilter[from]" id="<?php echo $widget; ?>_timerange_from_compare" chart="<?php echo $widget; ?>" onchange="setDuration(this.getAttribute('chart'), this.name, this.value, '_compare');"/>
                                                        <i class="icon-cal" id="<?php echo $widget; ?>_timerange_from_img_compare" chart="<?php echo $widget; ?>" name="cal_img" onclick="timerangeFrom(this.getAttribute('chart'), '_compare');" style="cursor: pointer;"></i>
                                                </div>
                                                <div class="date-range">
                                                        <label>To</label>
                                                        <input type="text" value="yyyy-mm-dd" readonly="true" name="timefilter[to]" id="<?php echo $widget; ?>_timerange_to_compare" chart="<?php echo $widget; ?>" onchange="setDuration(this.getAttribute('chart'), this.name, this.value, '_compare');"/>
                                                        <i class="icon-cal" id="<?php echo $widget; ?>_timerange_to_img_compare" chart="<?php echo $widget; ?>" name="cal_img" onclick="timerangeTo(this.getAttribute('chart'),'_compare');;" style="cursor: pointer;"></i>
                                                </div>
                                        </div>
                                <?php
                                        }
                                ?>
                                <div class="clearFix"></div>
                        </div>
                        <div class="popup-footer">
                                <input id= "<?php echo $widget; ?>_apply" uniqueattr="Dashboard/<?php echo $widget; ?>/ApplyButton" graph_type="cumulative" range="Duration" start_date="" end_date="" start_date_compare="" end_date_compare="" chart="<?php echo $widget; ?>" onclick="onClickAction(this);" type="button" value="Apply" class="gray-button" /> &nbsp;&nbsp;
                                <a href="javascript:void(0)" chart="<?php echo $widget; ?>" onclick="hideDateLayer(this.getAttribute('chart'));">Cancel</a>
                        </div>
                </div>
                <!--Popup Layer: Ends-->
        </div>
        <div class="clearFix"></div>
</div>
<?php
        }
?>
        
<?php
                if ($widget == 'response' || $widget == 'activity') {
                        if ($isNewUser == true) {
?>
        <div id="<?php echo $widget; ?>_text" class="graph-box" style="text-align:center; margin-top:115px; padding:30px 0 30px 0; font-size:18px; color:#777;">
                <span>
                        Please purchase paid subscriptions to view your reports.
                        <a href="<?php echo SHIKSHA_HOME; ?>/shikshaHelp/ShikshaHelp/contactUs" target="_new"> Click here </a>
                        to contact us.
                </span>
        </div>
<?php
                        }
                        else {
?>
        <div id="<?php echo $widget; ?>_ajax_loader" style="position:relative;">
                <div style="position:absolute;">
                        <img src='/public/images/smartajaxloader.gif' style="margin:110px 0 0 200px;" />
                </div>
        </div>

        <div id="<?php echo $widget; ?>" class="graph-box" name="chart"></div>
        <div id="<?php echo $widget; ?>_text" class="graph-box" style="display: none; text-align:center; padding:30px 0 30px 0; font-size:18px; color:#777;">
                <span>No data available for the selected date range.</span>
        </div>

<?php
                        }
                }
                if ($widget == 'credit') {
                        if (!empty($salesUser)) {
?>
                <div id='<?php echo $widget; ?>_text' style='text-align:center; padding:30px 0 30px 0; font-size:18px; color:#777;'>
                        <span>Please select a client to view credit details.</span>
                </div>
                <div id="<?php echo $widget; ?>_ajax_loader" style="position: relative; display: none;">
                        <div style="position:absolute;">
                                <img src='/public/images/smartajaxloader.gif' style="margin:70px 0 0 200px;" />
                        </div>
                </div>
        <?php
                        }
                        else {
        ?>
        <div id="<?php echo $widget; ?>_ajax_loader" style="position:relative;">
                <div style="position:absolute;">
                        <img src='/public/images/smartajaxloader.gif' style="margin:85px 0 0 200px;" />
                </div>
        </div>
        <?php
                        }
        ?>
        
        <div id="<?php echo $widget; ?>" class="credit-view" style="display:none;">
                <ul class="credit-head">
                        <li style="width:115px">Total credits</li>
                        <li>Credits Consumed</li>
                        <li class="last" style="width:147px">Gold Listings Left</li>
                </ul>
                
                <ul class="credit-details">
                        <li class="active" style="width:115px"><span id="total_credits">Select Client</span><p>as on <?php echo date("j M Y"); ?></p></li>
                        <li id="credits_consumed">Select Client</li>
                        <li id="gold_left" class="last" style="width:147px">Select Client</li>
                </ul>
                
                <div class="clearFix"></div>
        </div>

<?php
                }
        	if ($widget == 'leads') {
                        if (!empty($salesUser)) {
?>
                <div id='<?php echo $widget; ?>_text' style='text-align:center; padding:30px 0 30px 0; font-size:18px; color:#777;'>
                        <span>Please select a client to view lead allocation.</span>
                </div>
                <div id="<?php echo $widget; ?>_ajax_loader" style="position: relative; display: none;">
                        <div style="position:absolute;">
                                <img src='/public/images/smartajaxloader.gif' style="margin:0 0 0 430px;" />
                        </div>
                </div>
        <?php
                        }
                        else {
        ?>
        <div id="<?php echo $widget; ?>_ajax_loader" style="position:relative;">
                <div style="position:absolute;">
                        <img src='/public/images/smartajaxloader.gif' style="margin:20px 0 0 430px;" />
                </div>
        </div>
        <?php
                        }
        ?>

        <div id="<?php echo $widget; ?>"></div>

<?php
                }
?>