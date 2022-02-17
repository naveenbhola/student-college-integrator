<?php
if(is_array($headerContentaarray) && count($headerContentaarray)>0) {
	foreach ($headerContentaarray as $content) {
		echo $content;
	}
}
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script> $j = jQuery.noConflict(); </script>

<div id="smart-content">
    <h2 style='float:left; width:100%;'>Welcome <strong><?php echo trim($dynamicTitle); ?> </strong></h2>
    <div class="client-login-list">

        <ul>
            <li>
                <div id='select_div'>
                    
                    <label for='loginTypeClient'>
                        Select a Client
                    </label>
                    
                    <select id='executiveSelect' name='executiveId' style="margin-left:10px;" >
                        <option>Select an Executive</option>
                        <?php 
                            foreach ($executiveClientMapping as $executiveId => $clientsIds) {
                        ?>
                            <option id='executive_id_<?php echo $executiveId; ?>' value="<?php echo $executiveId; ?>">
                                <?php 
                                    if(trim($executiveNames[$executiveId]) != '')
                                        echo $executiveNames[$executiveId]; 
                                ?>
                            </option>
                        <?php         
                            }
                        ?>
                    </select>
                    
                    <select id='clientSelect' name='clientId' style="margin-left:10px;">
                        <option>Select a Client</option>
                    </select>

                </div>
            </li>
            <li>
                <input type="button" class="login-btn" value='' style="margin:15px 0 0 0" onclick="submitLogin();" />
            </li>
        </ul>
</div>

<?php
if(is_array($footerContentaarray) && count($footerContentaarray)>0) {
	foreach ($footerContentaarray as $content) {
		echo $content;
	}
}
?>

<script type="text/javascript">

    var executiveClientMapping = <?php echo json_encode($executiveClientMapping); ?>;
    var executiveNames = <?php echo json_encode($executiveNames); ?>;
    var clientNames = <?php echo json_encode($clientNames); ?>;
    
    document.getElementById('executiveSelect').onchange = function(){

        var selectedExecutiveId = jQuery('#executiveSelect option:selected').val();
      
        jQuery("#clientSelect").empty();
        jQuery('#clientSelect').html("<option value=''>Select a Client</option>");

        returnHTML = "<option value=''>Select a Client</option>";
        
        for (var id in executiveClientMapping[selectedExecutiveId]) {

            returnHTML += '<option id="client_id_' + executiveClientMapping[selectedExecutiveId][id] + '" value="' + executiveClientMapping[selectedExecutiveId][id] + '">';
            returnHTML += clientNames[executiveClientMapping[selectedExecutiveId][id]]+'</option>';

        }
        
        jQuery('#clientSelect').html(returnHTML);
    };

    function submitLogin() {

        var userId = jQuery('#clientSelect option:selected').val();
        var home_shiksha_url = '<?php echo SHIKSHA_HOME; ?>';
        var data = 'userId='+userId;
        jQuery.post('/smart/SmartMis/getLoginDetails',data,function(response){
            
            if(response == 'success') {
                window.location = home_shiksha_url;
            } else {  
                alert("Unauthorized Access");
            }
        });
        
    }

</script>