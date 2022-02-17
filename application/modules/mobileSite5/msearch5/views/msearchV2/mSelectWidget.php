<div class="search-layer-field <?php echo $disabled ? "search-disableField":''; ?>">
	<input type="text" class="search-clg-field shikshaSelect_input" <?php echo $disabled ? "disabled=disabled":''; ?> readonly="readonly" <?= $placeholder ?>  id="<?=$id?>_input">
 	<em class="pointerDown"></em>
</div>
<span style="display:none;" class='select_err' id="<?=$id?>_error"></span>
<p class='clr'></p>

<div class="select-Class">
    <select name="<?=$id?>" id="<?=$id?>" style="display:none;" <?= $onchange?> >
        
		<?php if(isset($data)) {
			if($firstDisabled){
				?>
				<option disabled='disabled' selected='selected'></option>
				<?php
			}
			foreach ($data as $key => $value) { ?>
				<option value="<?php echo $key;?>"><?php echo $value;?></option>
		<?php	}
			
			 }?>
	</select>                     
</div>  
