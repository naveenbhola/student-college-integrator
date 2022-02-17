<div class="float_L" style="width:35%;line-height:18px">
    <div class="txt_align_r" style="padding-right:5px">When do you plan to go?<b class="redcolor">*</b>:</div>
</div>
<div style="width:63%;float:right;text-align:left;">
    <div>
            <select style="font-size:11px;width:90%" name = "plan" blurMethod="when_plan_start();" required = "true" caption = "when you plan to start the course" id="when_plan_start">
        <?php
                $array= array(
                '0000-00-00 00:00:00' => 'Select',
                date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y"))) => date("Y"),
                date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y") +1)) => date("Y") + 1,
                date ("Y-m-d H:i:s", mktime(0, 0, 0, date("m"),date("d"),date("Y") +2)) => 'Later',
                );
            $selected = $data[0]['PrefData'][0]['TimeOfStart'];
            foreach ($array as $key => $value)  {
                        if ($selected == $key ) {
                            $selected_string = "selected";
                        } else {
                            $selected_string = "";
                        }
                        $selected_string = "";
                echo '<option '.$selected_string.' value="'.$key.'">'.$value.'</option>';
            }
            ?>

        </select>
    </div>
    <div class="clear_L withClear">&nbsp;</div>
    <div>
        <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
            <div class="errorMsg" id="when_plan_start_error" style="*padding-left:4px"></div>
        </div>
    </div>
</div>
<div class="clear_L withClear" style="clear:both;">&nbsp;</div>
<div class="lineSpace_10">&nbsp;</div>


