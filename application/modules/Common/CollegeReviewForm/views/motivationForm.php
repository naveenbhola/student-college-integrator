<div class="" style="margin:0;">
          <ul>

				    <li style="position:relative;">
                    <p style="margin-bottom:7px;">My biggest motivation to join this college was </p> 
					
                    <select id="motivationFactor" class="select-width" name="motivationFactor">
						                 <option value="">Select</option>
                            <?php foreach ($motivationFactor as $key => $motivation) {?>
                                <option value="<?php echo $key?>">
                                    <?php echo $motivation?>
                                </option>
                            <?php }?>
                    </select>
				
                	<div style="display:none;"><div class="errorMsg" id="motivationFactor_error" style="*float:left"></div></div>
             </li>
       </ul>
</div>