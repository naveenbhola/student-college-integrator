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
<form action="/enterprise/Enterprise/addCity" method="post" onsubmit="return XCitySubmit();">
    <div style="width:400px;margin:0 auto;">
        <h3>Add City</h3>&nbsp;<br/><br/>
        <?php
            if(isset($country) && !empty($country))
                echo '<div class="xmsg">'.$country.' is successfully added.</div>';
        ?>
        <div class="label">Select Country</div>
        <div>
            <select name="country" id="country" class="xselect" onblur="XValidateSelect(this);" >
                <option value="-1">Select Country</option>
                <?php
                    foreach($countries as $row)echo "<option value=\"".$row["countryId"]."\">".$row["name"]."</option>";
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
        <?php for($i=1;$i<2;$i++){ ?>
        <div class="label">Enter City <?=$i?></div>
        <div>
            <input type="text" name="city<?=$i?>" id="city<?=$i?>" value="" class="xselect" onblur="javascript:XvalidateString(this)"/>
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
</form>