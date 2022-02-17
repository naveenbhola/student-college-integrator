<?php if(count($results)>0) { ?>
            <div class="OrgangeFont fontSize_14p bld"><strong class="mar_left_10p">Filter By Listing</strong></div>
            <div class="lineSpace_10">&nbsp;</div>
			<select id="searchLeadByClient"  name="searchLeadByClient">
			<option value="<?php echo $clientId; ?>" selected="selected">Select</option>
			<?php foreach($results as $result)
			{
			?>   
			 <option value="<?php echo  $result['type']."-".$result['type_id']; ?>">
					<?php echo  $result['type']."-".$result['type_id']; ?>
			 </option>
			<?php
			}?>
			</select> <input type="button" onclick="getByClientListingLeads();" value=" GO " />
<?php } ?>
