<?php
    if(count($consultantLocations)> 0){
?>
<div class="mapped-univ-table" id = "consultant-location-table">
        <h4 style="margin-left:0; margin-top:10px;" class="ranking-head">
            <?php if(count($consultantLocations)>1) {?>
                All <?php echo count($consultantLocations)?>  Locations of <?php echo htmlentities($selectedConsultantName);?>
            <?php }
            else {
                echo count($consultantLocations)?>  Location of <?php echo htmlentities($selectedConsultantName);?>
            <?php } ?>
        </h4>
        <table cellpadding="0" border="1" cellspacing="0" style="margin:15px 0 0;" class="cms-table-structure">
                <tbody><tr>
                <th width="5%" align="center">S.No.</th>
                <th width="28%">
                    <span class="flLt">Location Name</span>
                </th>
                <th width="15%">
                    <span class="flLt">Contact Person</span>
                </th>
                <th width="22%">
                <span class="flLt">Phone No.</span>
                 </th>
                 <th width="18%">
                <span class="flLt">Shiksha PRI No.</span>
                 </th>
                 <th width="12%">
                <span class="flLt">Head Office</span>
                 </th>
            </tr>
        <?php foreach($consultantLocations as $k => $consultantLocation){ ?>
            <tr>
                <?php if($consultantLocation['headOffice'] == 'yes'){ ?>
                <input type= "hidden" id = "headOfficeLocationId" value = "<?=($consultantLocation['consultantLocationId'])?>" />
                <?php } ?>

                <td align="center"><?=($k+1)?>.</td>
                <td>
                    <p><?=(htmlentities($consultantLocation['locationName']))?></p>
                    <div class="edit-del-sec">
                        <a href="<?=(ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_EDIT_CONSULTANT_LOCATION.'/'.$consultantLocation['consultantLocationId'])?>">Edit</a>&nbsp;&nbsp;
                        <?php   // show delete link only to SA admin
                        if($usergroup == 'saAdmin' || $usergroup == 'saCMSLead') { ?>                                
                        <a href="javascript:void(0);" onclick="deleteLocation('<?php echo $consultantLocation['consultantLocationId']?>','<?php echo $requestedConsultantId;?>');">Delete</a>
                        <?php } ?>
                    </div>
                </td>
                <td>
                    <p><?=(htmlentities($consultantLocation['contactPerson']))?></p>
                </td>
                
                <td>
                    <p><?=(htmlentities($consultantLocation['phone']))?></p>
                </td>
                <td>
                    <p><?=($consultantLocation['shikshaPRI'])?></p>
                </td>
                <td>
                    <input type="radio" name = "headOfficeRadio" onclick = "setLocationAsHeadOffice(this,event);" id = "headOffice_<?=($consultantLocation['consultantLocationId'])?>" <?=($consultantLocation['headOffice']=='yes'?'checked="checked"':'')?> value="<?=($consultantLocation['consultantLocationId'])?>" onchange>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
        <script>
            function deleteLocation (consultantLocationId , consultantId)
            {
                var deleteMsg = "Are you sure you want to delete?";
                var ajaxURL = '<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_DELETE_CONSULTANT_LOCATION?>';
                var r=confirm(deleteMsg);
                
                if (r==true)
                {
                    var datastring = 'consultantLocationId='+consultantLocationId+'&consultantId='+consultantId;
                    $j.ajax({
                                 type: 'POST',
                                 url : ajaxURL,	
                                 data: datastring,
                                 
                                 success : function(res)
                                        {
                                            if(res=='Successfully Deleted!!')
                                            {
                                               preventOnUnload =true;
                                               alert(res);
                                               if(window.location['href'] == "<?=SHIKSHA_STUDYABROAD_HOME.ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_LOCATION."/".$requestedConsultantId."?all=1"?>")
                                               {
                                                   window.location.reload();
                                               }
                                               else
                                               {
                                                   window.location = "<?=ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_ADD_CONSULTANT_LOCATION."/".$requestedConsultantId."?all=1"?>";
                                               }
                                            }
                                            else if (res == 'notloggedin')
                                            {
                                               preventOnUnload =true;
                                               window.location.reload();
                                            }
                                            else if (res=='disallowedaccess')
                                            {
                                               alert("Something Went Wrong");
                                               preventOnUnload =true;
                                               window.location.reload();
                                            }
                                            else
                                            {
                                               alert(res);  
                                            }
                                           
                                        }
                          });		
                }
            }
        
        </script>
</div>
<?php 
    }
?>

