<style> 
    table.rmTable{
        border-left:1px solid #ddd;
        border-top:1px solid #ddd;
        margin-top:10px;
        font-size:13px;
        font-family:Arial,Helvetica,sans-serif;
    }
    table.rmTable th {
        text-align:left;
        background: #eee;
        padding:10px;
        border-right:1px solid #ddd;
        border-bottom:1px solid #ddd;
        font-size:15px;
        color:#444;
        font-family: Trebuchet MS,Arial,Helvetica;
    }
    table.rmTable td {
        text-align:left;
        background: #fff;
        padding:10px;
        border-right:1px solid #ddd;
        border-bottom:1px solid #ddd;
        font-size: 14px;
    }
    .rmInputBox {
        border:1px solid #888; font-size: 15px; padding:5px; width:180px; color:green;
    }
</style>
<script type="text/javascript">
    
    function testPositiveInt(number) {
	var intRegex = /^\d+$/;
	if(number > 0 && intRegex.test(number)) {
		return true;
	}
	else {
		return false;
	}
    }
    
    function validateRMForm()
    {
        var formobj = $('rmForm');
        var formData = $j(formobj).serializeArray();
	var formDataObj = {};
	$j.each(formData, function() {
		if (formDataObj[this.name] !== undefined) {
			if (!formDataObj[this.name].push) {
				formDataObj[this.name] = [formDataObj[this.name]];
			}
			formDataObj[this.name].push(this.value || '');
		} else {
			formDataObj[this.name] = this.value || '';
		}
	});
        
        var oldListings = [];
	var newListings = [];
        
	for(var i=0; i<formDataObj['oldListingId[]'].length; i++) {
		if(formDataObj['oldListingId[]'][i] != "" && formDataObj['newListingId[]'][i] != "") {
			oldListings.push(formDataObj['oldListingId[]'][i]);
			newListings.push(formDataObj['newListingId[]'][i]);
		}
	}
        
        if (oldListings.length == 0 && newListings.length == 0) {
            alert("Please give at least one listing ID to migrate.");
            return false;
        }
        
        if (oldListings.length != newListings.length) {
            alert("Please give one New Listing ID corresponding each Old Listing ID.");
            return false;
        }
        
        for(var i=0; i<oldListings.length; i++) {
                if(!testPositiveInt(oldListings[i])) {
                    alert("Please give valid listings ID.");
                    return false;
                }
        }
        
        for(var i=0; i<newListings.length; i++) {
                if(!testPositiveInt(newListings[i])) {
                    alert("Please give valid listings ID.");
                    return false;
                }
        }
        
        formobj.action = '/enterprise/Enterprise/index/790/confirmMigration';
        return true;
    }
</script>
<?php
    if($dataValidation) {
        $this->load->view('lms/responseMigrationConfirmation'); 
    }
?>
<div id="rmFormContainer" style="padding:10px;font-family:Arial,Helvetica,sans-serif;font-size:12px; min-height: 500px; <?php if($dataValidation) echo "display:none;"; ?>">
    <div class="orangeColor fontSize_14p bld" style="width: 950px; font-size: 22px;">
        <span><b>Response Migration</b></span><br>
    </div>
    <form method="post" id='rmForm'>
    <div style='background:#fafafa; padding:5px 0 20px 0; margin-top: 10px;'>
        <div style='width:600px; margin:0 auto;'>
            <table class='rmTable' cellpadding='0' cellspacing='0'>
                <tr>
                    <th width='120'>Listing Type</th>
                    <th width='200'>Old Listing ID<br/>(Already deleted)</th>
                    <th width='200'>New Listing ID</th>
                </tr>
                <?php for($i=0;$i<10;$i++) { ?>
                <?php
                $thisListingType = '';
                $thisListingOldId = '';
                $thisListingNewId = '';
                
                if(is_array($dataValidation[$i])) {
                    $thisListingType = $dataValidation[$i]['listingType'];
                    $thisListingOldId = $dataValidation[$i]['oldListingId'];
                    $thisListingNewId = $dataValidation[$i]['newListingId'];
                }
                ?>
                <tr>
                    <td>
                        <select id='listingType' style='font-size: 14px; padding:2px;' name='listingType[]'>
                            <option value='course' <?php if($thisListingType == 'course') echo "selected='selected'"; ?>>Course</option>
                            <!--<option value='institute'>Institute</option>-->
                        </select>
                    </td>
                    <td><input id='oldListingId' type='text' class='rmInputBox' name='oldListingId[]' value='<?php echo $thisListingOldId; ?>' /></td>
                    <td><input id='newListingId' type='text' class='rmInputBox' name='newListingId[]' value='<?php echo $thisListingNewId; ?>' /></td>
                </tr>
                <?php } ?>
            </table>
            <br />
            <input type="submit" value="Migrate Responses" title="Migrate Responses" class="orange-button flLt" style='margin-left:200px; margin-top: 10px;' onClick="validateRMForm();">
            <input type='hidden' id='migrationConfirmed' name='migrationConfirmed' value='no' />
            <div style='clear: both;'></div>
            <br />
        </div>
    </div>
    </form>
</div>
<?php $this->load->view('common/leanFooter'); ?>