
<head>
    <title>Redis</title>
</head>
<div style='float:right'>

</div>
<h1>Redis Monitor</h1>
<div style='width:100%;'>
<div style='float:right;width:70%'>
    <div id='actionInputBoxContainer'  style='display:none;'>
        <table>
            <tr><td>Key : </td><td><input type='text' name='keyInputBox' id='keyInputBox' style='width:350px;' value="userHomeFeed:user:3392259"></td></tr>
            <tr class='expireTimeRow' style='height:15px;display:none;'></tr>
            <tr class='expireTimeRow' style='display:none;'><td>Time in seconds(0 to delete)</td><td><input type='text' name='expireTimeInputBox' id='expireTimeInputBox' style='width:350px;'></td></tr>
            <tr style='height:15px'></tr> 
            <tr><td colspan=2><input type='button' value='Click here' id='actionSubmitButton' name='actionSubmitButton'></td></tr>
        </table>   
    </div>

</div>
<div style='float:left;width:30%'>
  Select The action  <select name='actions' id='actions'>
        <option value='-1' selected>Select</option>
        <option value='directView'>Search a Key</option>
        <option value='expireKey'>Expire a Key</option>
    </select>
</div>
</div>
<div id='response' style='margin-top:160px;padding: 0 10px;'>
    
</div>







<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"> </script>
<script type="text/javascript" src="//<?php echo JSURL;?>/public/js/<?php echo getJSWithVersion('redisMonitor'); ?>"></script>
<script>
        $j = jQuery.noConflict();
        $j(document).ready(function(){
            bindDropDownChange();
            bindSubmitButtonClick();
        });

</script>
