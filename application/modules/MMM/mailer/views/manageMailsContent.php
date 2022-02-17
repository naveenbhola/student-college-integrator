<style>
.mail-fire-section{color:040404; font-family:Arial, Helvetica, sans-serif; float:none;}
.mail-fire-col{ border:1px solid #ebebeb; float:none;margin-bottom: 12px;}
.mailer-title{padding:8px 10px; background:#ccc; color:#333; font-size:14px; font-weight:bold; margin:0; text-align:left;}
.caption{font-size: 12px;font-weight: 400}
ul.saved-mailer-list{margin:5px 0; text-align:left; float:left; width:100%;padding:0; height:500px; overflow-y:scroll; overflow-x:scroll;}
ul.saved-mailer-list li{list-style:none; margin-bottom:5px; float:left; color:#040404; float:left; width:95%; padding:4px; font-size:13px; line-height:16px;}
.clearfix{clear:both;}
.test-reset-col{margin:10px 0px 30px; text-align:center}
/*.test-reset-button{padding:3px 20px; color:#040404 !important; font-size:12px; font-weight:bold; background:#eaeaea; border:1px solid #a6a6a6; display:inline-block; text-decoration:none !important; text-align:center; font-family:Arial, Helvetica, sans-serif;}
*/
.mailer-table{width: 100%;text-align: left;border-collapse: collapse;}
.mailer-table th,.mailer-table td{padding: 5px 8px;}
.mailer-table tbody {
  display: block;
  max-height: 400px;
  overflow-y: scroll;
  overflow-x: hidden;
}
.mailer-table tbody td{
    border:1px solid #d2d2d2;
    border-collapse: collapse;
}

.mailer-table thead,
tbody tr {
  display: table;
  width: 100%;
  table-layout: fixed;
}
.mailer-table th:first-child, .mailer-table td:first-child {
    width: 235px;
}
.boldHeading{
    margin: 0px 0px 8px 0px;
    font-size: 20px;
    font-weight: 600;
    display: block;
}
.systemButton{-webkit-appearance: button;padding: 5px 15px;color:#040404 !important;text-decoration:none !important;}
</style>
<form name="cancelMailerDetails" method="post" action="/mailer/Mailer/cancelMailerDetails">
    <div class="mail-fire-section">
        <div><strong class="boldHeading">Manage saved and scheduled mails from here</strong></div>
    	<div class="mail-fire-col">
        	<p class="mailer-title">Saved Mailers <span class="caption">(Select mail below to schedule)</span></p>
            <div class="scrollDiv">
                <table class="mailer-table">
                    <thead>
                        <tr>
                            <th>Mailer Name</th>
                            <th>Mailer Id</th>
                            <th>Campaign Name</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($mailers['draft'] as $mailer) { 
                        if(empty($mailer['parentMailerId'])) { ?>
                    <tr>
                        <td>
                            <input type="radio" name="draftMailer" value="<?=$mailer['id']?>" class="flLt"/>
                            <label><?=$mailer['mailerName']?></label>
                        </td>
                        <td>
                            <label><?=$mailer['id']?></label>
                        </td>
                        <td>
                           <label><?=$campaignNames[$mailer['campaignId']]?></label>
                        </td>
                    </tr>
                  <?php } 
                    }?>
                    </tbody>
                </table>
            </div>

            <div class="clearfix"></div>
        </div>
        <div class="test-button-sec">
            <div class="test-reset-col"><a href="javascript:void(0);" onclick="scheduleMailer()" class=" systemButton">Modify & Schedule</a></div>
        </div>
        <div class="mail-fire-col" style="margin:0;">
        	<p class="mailer-title">Scheduled Mailers <span class="caption">(Select mailers below to cancel)</span></p>
            <div class="scrollDiv">
                <table class="mailer-table">
                    <thead>
                        <tr>
                            <th>Mailer Name</th>
                            <th>Mailer Id</th>
                            <th>Campaign Name</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($mailers['false'] as $mailer) { 
                            $scheduleDateTime = array();
                            $scheduleDateTime = explode(' ', $mailer['time']);
                            $scheduledDate = explode('-', $scheduleDateTime[0]);
                            ?>

                        <tr>
                            <td>
                                <input type="checkbox" name="scheduledMailer[]" id="scheduledMailer" value="<?=$mailer['id']?>" class="flLt"/>
                                <label><?=$mailer['mailerName']?></label>

                            </td>
                            <td>
                                <label><?=$mailer['id']?></label>
                            </td>
                            <td>
                                <label><?=$campaignNames[$mailer['campaignId']]?></label>                        
                            </td>
                            <td>
                                <label><?=$scheduledDate[2].'-'.$scheduledDate[1].'-'.$scheduledDate[0]?></label>
                            </td>
                            <td>
                                <label><?=$scheduleDateTime[1]?></label>
                            </td>
                        </tr>
                       <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
    <div class="clearfix"></div>
    <div class="test-button-sec">
    	<div class="test-reset-col"><input type="submit" value="Cancel" class="systemButton" style="cursor:pointer" onclick="return validateCancelMailers();"></div>                
    </div>
    <div class="clearfix"></div>
</form>
