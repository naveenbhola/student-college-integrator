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
        font-size: 14px;
        line-height: 20px;
        text-align: center;
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
<form action="/location/Location/saveZone" method="post" onsubmit="javascript:return Xzonesubmit();">
    <div style="width:400px;margin:0 auto;">
        <h3>Add Zone</h3>&nbsp;<br/><br/>
        <?php
        if(is_array($validationErrors) && count($validationErrors) > 0) {
            echo "<div id='validation_errors'><p>".implode('</p><p>',$validationErrors)."</p></div>";
        }
      
        ?>
        <div class="label"></div>
        <div>
            <select name="City" id="City" class="xselect" onblur="XValidateSelect(this);">
                <option value="">Select City</option>
                <?php 
                    foreach($cities as $city) {
                        echo "<option value=\"".$city->getId()."\" ".($city->getId() == $formData['city_id'] ? "selected='selected'" : "").">".$city->getName()."</option>";
                    }
                ?>
            </select>
        </div>
        <div id="City_error" style="display:none;" class="xerror"></div>
        <div class="label">Enter Zone</div>
        <div>
            <input type="text" name="zone" id="zone" value="<?php echo $formData['zone']; ?>" class="xselect" maxlength="50" onblur="javascript:XValidateLocalityZoneString(this)"/>
        </div>
        <div id="zone_error" style="display:none;" class="xerror"></div>
        <div class="label">Enter its Locality</div>
        <div>
            <input type="text" name="Locality" id="Locality" value="<?php echo $formData['Locality']; ?>" class="xselect" maxlength="50" onblur="javascript:XValidateLocalityZoneString(this)"/>
        </div>
        <div id="Locality_error" style="display:none;" class="xerror"></div>
        <div class="label">&nbsp;</div>
        
                <div>
            <input type="submit" name="add" id="add" value="Add" />
        </div>
        
    </div>
</form><br /><br />

<?php
if($statusMessage) {
            echo '<div class="xmsg"> '.$statusMessage['message'].'</div>';
        }
$this->load->view('common/footerNew'); ?>
