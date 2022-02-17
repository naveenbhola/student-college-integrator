<style type="text/css">
     .subscriptionDetail-table tr td {
         vertical-align: top;
         border-bottom: 1px solid #111;
         padding-right: 0px;
         word-wrap: break-word;
         border-right: 1px solid #111;
         padding: 6px;
         text-align: center;
     }

     .subscriptionDetail-table{
         width: 100%;
         table-layout: fixed;
         border: 1px solid #111;
         border-collapse: collapse;
     }

     .btn-submit7.w9{
         width: auto;
     }

     .subscriptionDetail-table thead{
       background: #e6e5e5;
       font-size: 14px;
     }

     .subscriptionDetail-table tbody{
         font-size: 12px;
     }

     .subscriptionDetail-table tbody tr:nth-child(odd){
         background: #f4f4f4;
     }

     .disabled{
         pointer-events : none;
     }
</style>
<div style="float:left;width:100%">
   <div style="padding-bottom:15px;">      
      <?php      
       if(count($subscriptionDetails) <=0){ ?>
                <p style="font-size: 16px;text-align: center"> <b>No Data Available</b></p><br>
        <?php }else{ ?>
            
      <br>            
      <table class="subscriptionDetail-table">
         <thead>
            <tr>
               <td>Exam Page</td>
               <td>Course(s)</td>
               <td >Location(s)</td>
               <td>Start Date</td>
               <td>End Date</td>
               <td>Total Responses</td>
               <td>Responses Received</td>
               <td>Download</td>
               <td>View Details</td>
            </tr>
         </thead>
         <tbody>
            <?php foreach ($subscriptionDetails as $key => $subscriptionDetail) {?>
                <tr>
                   <td><?php echo $subscriptionDetail['examName']?></td>
                   <td title="<?php echo $subscriptionDetail['groupNamesToolTip']?>"><?php echo $subscriptionDetail['groupNames']?></td>
                   <td title="<?php echo $subscriptionDetail['userLocationsToolTip']?>" class="userLocations"><?php echo $subscriptionDetail['userLocations']?></td>
                   <td ><?php echo $subscriptionDetail['startDate']?></td>
                   <td ><?php echo $subscriptionDetail['endDate']?></td>
                   <td ><?php echo $subscriptionDetail['quantityExpected']?></td>
                   <td ><?php echo $subscriptionDetail['quantityDelivered']?></td>
                   <td>
                        <?php if($subscriptionDetail['quantityDelivered'] > 0){?>
                        <button class="subscription btn-submit7 w9 rvSubDown" subscriptionId ="<?php echo $subscriptionDetail['id']?>"  type="button">
                            <div class="btn-submit7">
                                <p class="btn-submit8 btnTxtBlog">Download</p>
                            </div>
                        </button>
                        <?php }else{?>
                        N/A
                        <?php } ?>
                    </td>
                    <td>
                      <?php if($subscriptionDetail['quantityDelivered'] > 0){?>
                        <button class="subscription btn-submit7 w9"
                          type="button"
                          onclick="location.replace('/enterprise/ResponseViewerEnterprise/getResponseForExam/<?php echo $subscriptionDetail['id'];?>')"   >
                            <div class="btn-submit7">
                                <p class="btn-submit8 btnTxtBlog">View Details</p>
                            </div>
                        </button>
                      <?php }else{?>
                        N/A
                        <?php } ?>
                    </td>
                </tr>
                
            <?php }?>                             
         </tbody>
      </table>
      <?php } ?>
   </div>
</div>

<form id="respViewerExamDownloadForm" action="/enterprise/ResponseViewerEnterprise/getExamDownloadResponses" method="post" style="display:none"> 
    <input type="hidden" name="subscriptionId">     
</form>
<script>
window.onload=function(){
    $j('.rvSubDown').click(function(e){
        e.preventDefault();
        $j("#respViewerExamDownloadForm input[name='subscriptionId']").val($j(this).attr('subscriptionId'));
        $j('#respViewerExamDownloadForm').submit();        
    });
    
};
</script>