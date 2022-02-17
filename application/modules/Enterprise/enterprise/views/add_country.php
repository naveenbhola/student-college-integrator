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
</style>
<form action="/enterprise/Enterprise/addCountry" method="post" onsubmit="javascript:return Xsubmit();">
    <div style="width:400px;margin:0 auto;">
        <h3>Add Country</h3>&nbsp;<br/><br/>
        <?php
            if(isset($country) && !empty($country))
                echo '<div class="xmsg">'.$country.' is successfully added.</div>';
        ?>
        <div class="label">Enter Country Name</div>
        <div>
            <input type="text" name="country" id="country" value="" class="xselect" onblur="javascript:XvalidateString(this)"/>
        </div>
        <div id="country_error" style="display:none;" class="xerror"></div>
        
        <div class="label">Select Region(optional)</div>
        <div>
            <select name="region" id="region" class="xselect">
                <option value="-1">Select Region</option>
                <?php
                    foreach($regions as $row)echo "<option value=\"".$row["regionid"]."\">".$row["regionname"]."</option>";
                ?>
            </select>
        </div>
        
        <div class="label">Enter its Capital City</div>
        <div>
            <input type="text" name="capital" id="capital" value="" class="xselect" onblur="javascript:XvalidateString(this)"/>
        </div>
        <div id="capital_error" style="display:none;" class="xerror"></div>
        
        <div class="label">&nbsp;</div>
        <div>
            <input type="submit" name="add" id="add" value="Add"/>
        </div>
        
    </div>
</form>