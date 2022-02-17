<div class="search-layer-field">
	<input type="text" class="search-clg-field shikshaSelect_input" readonly="readonly" id="dropDown<?=$id?>_input">
 	<em class="pointerDown"></em>
</div>
<span style="display:none;" class='select_err' id="dropDown<?=$id?>_error"></span>
<p class='clr'></p>

<div class="select-Class">
	<select name="dropDown<?=$id?>" id="dropDown<?=$id?>" style="display:none;">
		<?php if(isset($data)) {
			foreach ($data as $key => $value) { ?>
				<option value="<?php echo $key;?>"><?php echo $key;?></option>
		<?php	}
			
			 }?>
	</select>                     
</div>  
