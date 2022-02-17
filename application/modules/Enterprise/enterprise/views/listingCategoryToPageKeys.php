<select name="selectedPageKey" id="selectedPageKey">
    <option value="" selected>Select Category Page</option>
    <?php
        foreach($FinalKeyArr as $key=>$pageName){ ?>
        <option value="<?php echo $key; ?>" ><?php echo $pageName; ?></option>
        <?php } ?>

    </select>
<!--    <input type="hidden" name="setListingTypeId" id="setListingTypeId" value="<?php echo $type_id; ?>" />
<input type="hidden" name="setListingType" id="setListingType" value="<?php echo $listing_type; ?>" /> -->
