
	<div class="find-field-row">
	<select name = "plan" onblur="hideFundField();validatePlanStart();" required = "true" caption = "when you plan to start the course" id="when_plan_start_abroad" onchange="hideFundField();">
        <?php
                $array= array(
                '0000-00-00 00:00:00' => 'When do you plan to go ?',
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
                echo '<option '.$selected_string.' value="'.$key.'">'.$value.'</option>';
            }
            ?>

        </select>
    
    <div class="clearFix"></div>
    <div class="errorPlace" style="display:none;">
    	<div class="errorMsg" id="when_plan_start_abroad_error"></div>
    </div>
    <div class="clearFix"></div>
</div>
