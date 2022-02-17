<div class="find-field-row">
	<div id="fieldOfInterestFieldContainer_studyabroad">
    	<select onblur="validateFieldInterest();" validate = 'validateSelect' required = 'true' caption = 'the field of interest' id='fieldOfInterestAbroad' name="board_id">
			<option value=''>Education Interest</option>
            	<?php
                foreach($categories as $categoryId => $categoryName) {
                if ($categoryId !=11 && $categoryId !=14) {
                echo "<option value='". $categoryId."'>". $categoryName ."</option>";
                }
                }
                ?>
             </select>
     </div>
     <div class="clearFix"></div>
    <div class="errorPlace">
    	<div class="errorMsg" id="fieldOfInterestAbroad_error"></div>
	</div>
</div>

