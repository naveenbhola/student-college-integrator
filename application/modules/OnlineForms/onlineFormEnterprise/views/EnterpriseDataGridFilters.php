<div class="filter-cont">
	<h4 class="filter-title">Filter Data:</h4>
	<ul>
		<?php
		for($i=0 ; $i<10; $i++){
			if($i%2 == 0){
				$class = "alt-row";
			}else{
				$class = "";
			}
		?>
		<li class="<?=$class?> filters" id="filter-<?=$i?>" style="display:none">
			<div class="col-1"><select class="universal-select" id="filter-includeExcludeFlag-<?=$i?>" onblur="saveFilter(<?=$i?>)">
				<option value="true">Include</option>
				<option value="false">Exclude</option>
			</select></div>
			<div class="col-2"><select class="universal-select" id="filter-field-<?=$i?>" onblur="saveFilter(<?=$i?>)">
			<?php
				foreach($fields as $key=>$heading){
			?>
				<option value="<?=$key?>"><?=$heading['name']?></option>
			<?php
				}
			?>
			</select></div>
			<div class="col-3"><select class="universal-select" id="filter-type-<?=$i?>" onblur="saveFilter(<?=$i?>)">
			<option value="equal">Equal</option>
			<option value="greaterThan">Greater Than</option>
			<option value="lessThan">Less Than</option>
			<option value="similar">Similar</option>
			<option value="in">In</option>
			</select>
			</div>
			<div class="col-4"><input class="universal-txt-field" type="text" id="filter-value-<?=$i?>" onblur="saveFilter(<?=$i?>);hidetip();" value="" onfocus="showFilterToolTip(<?=$i?>);" /></div>
			<div class="del-col"><a href="#" class="del-rows" onclick="deleteFilter(<?=$i?>); return false;"></a></div>
		</li>
		<?php
		}
		?>
	</ul>
	
	<div class="spacer10 clearFix"></div>
	<a href="#" class="add-dimension" onclick="addNewFilterBox(); return false;">+ Add a dimension</a>
	<div class="spacer10 clearFix"></div>
	<div class="btn-box">
	<input type="button" value="Submit" class="orange-button" onclick="applyFilters();" />
	</div>
</div>