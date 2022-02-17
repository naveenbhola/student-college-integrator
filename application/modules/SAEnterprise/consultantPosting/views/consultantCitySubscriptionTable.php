<?php
    if(count($data)> 0){
?>
<div class="mapped-univ-table" id = "consultant-location-table">
        <h4 style="margin-left:0; margin-top:10px;" class="ranking-head">
            <?php if(count($data)>1) {?>
                All <?=count($data)?> regions assigned to <?=htmlentities($consultantName)?> 
            <?php }
            else { ?>
                1 region assigned to <?=htmlentities($consultantName)?> 
            <?php } ?>
        </h4>
        <table cellpadding="0" border="1" cellspacing="0" style="margin:15px 0 0;" class="cms-table-structure">
                <tbody>
                    <tr>
                        <th width="5%" align="center">
                            S.No.
                        </th>
                        <th width="28%">
                            <span class="flLt">Mapped University Name</span>
                        </th>
                        <th width="15%">
                            <span class="flLt">City Name</span>
                        </th>
                        <th width="22%">
                            <span class="flLt">Start Date</span>
                        </th>
                        <th width="18%">
                            <span class="flLt">End Date</span>
                        </th>
                    </tr>
        <?php foreach($data as $k => $row){ ?>
            <tr>
                <td align="center"><?=($k+1)?>.</td>
                <td>
                    <p><?=(htmlentities($row['universityName']))?></p>
                    <div class="edit-del-sec">
                        <?php if($row['liveCheck'] == ''){ ?>
                        [Mapping Deleted]
                        <?php }else{ ?>
                        <a href="<?=(ENT_SA_CMS_CONSULTANT_PATH.ENT_SA_FORM_EDIT_ASSIGNED_REGION.'/'.$row['primaryKey'])?>">Edit</a>
                        <?php } ?>
                        
                    </div>
                </td>
                <td>
                    <p><?=(htmlentities($row['cityName']))?></p>
                </td>
                
                <td>
                    <p><?=(htmlentities($row['startDate']))?></p>
                </td>
                <td>
                    <p><?=(htmlentities($row['endDate']))?></p>
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

