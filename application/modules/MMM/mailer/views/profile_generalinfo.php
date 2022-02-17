<li>
	<label>Work Experience:</label>
	<div class="flLt">
		<select id="min_workex" name="min_workex" onChange="validateMaxExp();">
			<option value="">Min</option>
			<?php
				for($i=0;$i<=10;$i++)
				{
					echo "<option value=\"".$i."\">".$i."</option>";
				}
			?>
		</select>&nbsp;
		<select id="max_workex" name="max_workex" onChange="validateMaxExp();">
			<option value="">Max</option>
			<?php
				for($i=0;$i<=10;$i++)
				{
					echo "<option value=\"".$i."\">".$i."</option>";
				}
			?>
		</select>
	</div>
</li>
<li>
	<label>Age:</label>
	<div class="flLt">
		<input style="width:60px" type="text" value="Min" id="age_min" name="age_min" onblur="validAge(this, 'Min')" onfocus="if(this.value=='Min')this.value='';" />
		&nbsp; To &nbsp;
		<input style="width:60px" type="text" value="Max" id="age_max" name="age_max" onblur="validAge(this, 'Max')" onfocus="if(this.value=='Max')this.value='';" />
	</div>
</li>
<li>
	<label>Gender:</label>
	<div class="flLt">
		<input type="checkbox" id="user_gender_male" name="user_gender[]" value="Male" /> Male &nbsp;&nbsp;
		<input type="checkbox" id="user_gender_female" name="user_gender[]" value="Female" /> Female
	</div>
</li>