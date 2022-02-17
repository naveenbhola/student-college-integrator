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
<form action="/location/Location/saveCountry" method="post" onsubmit="javascript:return Xsubmit();">
    <div style="width:400px;margin:0 auto;">
        <h3>Add Country</h3>&nbsp;<br/><br/>
        <?php
        if(is_array($validationErrors) && count($validationErrors) > 0) {
            echo "<div id='validation_errors'><p>".implode('</p><p>',$validationErrors)."</p></div>";
        }
        if($statusMessage) {
            echo '<div class="xmsg">'.$statusMessage['message'].'</div>';
        }
        ?>
        <div class="label">Enter Country Name</div>
        <div>
            <input type="text" name="country" id="country" value="<?php echo $formData['country']; ?>" class="xselect" maxlength="50" onblur="javascript:XvalidateString(this)"/>
        </div>
        <div id="country_error" style="display:none;" class="xerror"></div>
        
        <div class="label">Select Region(optional)</div>
        <div>
            <select name="region" id="region" class="xselect">
                <option value="">Select Region</option>
                <?php 
                    foreach($regions as $region) {
                        echo "<option value=\"".$region->getId()."\" ".($region->getId() == $formData['region'] ? "selected='selected'" : "").">".$region->getName()."</option>";
                    }
                ?>
            </select>
        </div>
        <div id="region_error" style="display:none;" class="xerror"></div>
        
        <div class="label">Enter its Capital City</div>
        <div>
            <input type="text" name="capital" id="capital" value="<?php echo $formData['capital']; ?>" class="xselect" maxlength="50" onblur="javascript:XvalidateString(this)"/>
        </div>
        <div id="capital_error" style="display:none;" class="xerror"></div>
        
        <div class="label">&nbsp;</div>
        <div>
            <input type="submit" name="add" id="add" value="Add"/>
        </div>
        
    </div>
</form><br /><br />
<?php $this->load->view('common/footerNew'); ?>