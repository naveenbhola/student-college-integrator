<select name="whenPlanToStart" id="whenPlanToStart" <?php echo $registrationHelper->getFieldCustomAttributes('whenPlanToStart'); ?>>
    <option value="">When do you plan to start ?</option>
    <?php foreach($fields['whenPlanToStart']->getValues() as $plannedToStartValue => $plannedToStartText) { ?>
        <option value="<?php echo $plannedToStartValue; ?>" <?php if($formData['whenPlanToStart'] == $plannedToStartValue) echo "selected='selected'"; ?>><?php echo $plannedToStartText; ?></option>
    <?php } ?>
</select>