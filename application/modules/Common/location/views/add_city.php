<style> 
    .label{
        line-height: 20px;
        font-size: 12px;
    }
    .xselect{
        width:200px;
        padding: 4px 2px;
    }
    .xerror{
        color:red;
    }
    .xmsg{
        color:green;
    }
    #validation_errors
    {
        background:#ffdddd;
        margin-bottom: 10px;
        padding:5px;
    }
    #validation_errors p
    {
        color:#ff0000;
    }
</style>
<form action="/location/Location/saveCity" method="post" onsubmit="return XCitySubmit();">
    <div style="width:400px;margin:0 auto;">
        <h3>Add City</h3>&nbsp;<br/><br/>
        <?php
        if(is_array($validationErrors) && count($validationErrors) > 0) {
            echo "<div id='validation_errors'><p>".implode('</p><p>',$validationErrors)."</p></div>";
        }
        if($statusMessage) {
            echo '<div class="xmsg">'.$statusMessage['message'].'</div>';
        }
        ?>
        <div class="label">Select Country</div>
        <div>
            <select name="country" id="country" class="xselect" onblur="XValidateSelect(this);" >
                <option value="">Select Country</option>
                <?php
                    foreach($countries as $country) {
                        if($country->getId() > 2) {
                            echo "<option value=\"".$country->getId()."\" ".($country->getId() == $formData['country'] ? "selected='selected'" : "").">".$country->getName()."</option>";
                        }
                    }
                ?>
            </select>
        </div>
        <div id="country_error" style="display:none;" class="xerror"></div>
        <div style="display:none;">
        <div class="label">Select State(optional)</div>
        <div>
            <select name="state" id="state" class="xselect">
                <option value="-1">Select State</option>
            </select>
        </div>
        </div>
        
        <div id="cities_block">
        <?php
        $numCityBlocks = 1;
        while($formData['city'.$numCityBlocks]) {
            $numCityBlocks++;
        }
        
        if($numCityBlocks > 1) {
            $numCityBlocks--;
        }
        
        for($i=1;$i<=$numCityBlocks;$i++){ ?>
        <div class="label">Enter City Name</div>
        <div>
            <input type="text" name="city<?=$i?>" id="city<?=$i?>" value="<?php echo $formData['city'.$i]; ?>" class="xselect" maxlength="50" onblur="javascript:XvalidateString(this)"/>
        </div>
        <div id="city<?=$i?>_error" style="display:none;" class="xerror"></div>
        <?php } ?>
        </div>
        <div><a href="javascript:add_more_cities();">Add More</a></div>
        <div class="label">&nbsp;</div>
        <div>
            <input type="submit" name="add" id="add" value="Add"/>
        </div>
        
    </div>
    <input type="hidden" name="numCities" id="numCities" value="1" />
</form><br /><br />
<?php $this->load->view('common/footerNew'); ?>