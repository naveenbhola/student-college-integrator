<?php
  $image_url = SHIKSHA_HOME.'/public/images/MOneRecMailer/';

  $data = array();
  $data['image_url'] = $image_url;
  $data['course_url'] = $course_url;

?>
<style type="text/css">
.box{ }
@media only screen and (max-width: 480px){
   .box{float:left;}
}               
</style>
  <tr>
    <td valign="top">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #e8e8e8; border-radius:5px; background-color:#ffffff">
        <tr>
          <td width="45"></td>
          <td>
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td height="30"></td>
              </tr>
              <tr>
                <td style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:11px; color:#666666;">Hi <!-- #username --><!-- username# -->,</td>
              </tr>
              <tr>
                <td height="15"></td>
              </tr>
              <tr>
                <td height="25" valign="middle" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:11px; color:#333333; line-height:17px;"> 
                  Students interested in the same course as you (<strong style="color:#000000;"><?php echo $interest; ?></strong>) have also downloaded the brochure of the following college. We recommend you to do the same.<br />
                </td>
              </tr>              
              <tr>
                <td height="25"></td>
              </tr>
            </table>
          </td>
          <td width="45"></td>
        </tr>
      </table>
    </td>
  </tr>
  <tr>
    <td bgcolor="#f1f1f1" height="14"></td>
  </tr>
  <tr>
    <td bgcolor="#f1f1f1">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:1px solid #e8e8e8; border-radius:5px; border-top:none; background-color:#ffffff">
        <tr>
          <td align="center">
            <a target="_blank" href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->">
              <img width="100%" src="<?php if(strlen($recommendationWidgets['recommendationBasicInfo']['instituteLogoURL'])) { echo $recommendationWidgets['recommendationBasicInfo']['instituteLogoURL']; } else { echo $image_url.'institute_img.jpg'; } ?>" alt="Image cannot be displayed" />
            </a>
          </td>
        </tr>
        <tr>
          <td height="30"></td>
        </tr>
        <tr>
          <td align="center">
            <table width="80%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="5" valign="middle" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666;"></td>
                <td height="25" align="center" valign="middle" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#333333; line-height:17px; font-weight:bold;"> 
                  <a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" title="<?php echo $recommendationWidgets['recommendationBasicInfo']['instituteName']; ?>" style="font-family:open sens,Tahoma,Helvetica,sans-serif;font-size:12px;text-decoration:none;color:#333333;">
                    <?php echo $recommendationWidgets['recommendationBasicInfo']['instituteName']; ?>
                  </a>, <?php echo $recommendationWidgets['recommendationBasicInfo']['instituteCityName']; ?> <?php if($isAbroadRecommendation) { echo ' | '.$recommendationWidgets['recommendationBasicInfo']['instituteCountryName']; } ?><br />
                  <?php if($recommendationWidgets['recommendationBasicInfo']['establishYear']) { if($isAbroadRecommendation) { echo ', '; } echo 'Established in '.$recommendationWidgets['recommendationBasicInfo']['establishYear']; } ?>
                </td>
                <td width="5" valign="middle" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666;"></td>
              </tr>
              <tr>
                <td valign="middle" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666;"></td>
                <td height="25" valign="middle" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666;"></td>
                <td valign="middle" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666;"></td>
              </tr>
              <tr>
                <td valign="middle" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666;"></td>
                <td height="25" align="center">
                  <table width="170" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                      <td height="35" align="center" bgcolor="#048088" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:11px; border-radius:5px; font-weight:bold;"><a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="Download Brochure" target="_blank" style="color:#ffffff; text-decoration:none; cursor:pointer; display:block; width:100%; line-height:27px;">Download Brochure</a></td>
                    </tr>
                  </table>
                </td>
                <td valign="middle" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#666666;"></td>
              </tr>
              <tr>
                <td></td>
                <td height="25" style="border-bottom:solid 1px #dadada;"></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td height="14"></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td height="25" align="center" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; line-height:21px; color:#666666; font-weight:bold;">
                  <a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" target="_blank" title="<?php echo $recommendationWidgets['recommendationBasicInfo']['courseName']; ?>" style="font-family:open sens,Tahoma,Helvetica,sans-serif;font-size:12px;text-decoration:none;color:#666666;">
                    <?php echo $recommendationWidgets['recommendationBasicInfo']['courseName']; ?>
                  </a><br />
                    <?php if(!$isAbroadRecommendation && strlen(trim($recommendationWidgets['recommendationBasicInfo']['courseApprovals']))) { echo $recommendationWidgets['recommendationBasicInfo']['courseApprovals']; } ?>
                </td>
                <td></td>
              </tr> 
              <tr>
                <td></td>
                <td height="18" align="center" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:12px; color:#999999; line-height:17px;">
                  <?php echo 'Course Duration : '.$recommendationWidgets['recommendationBasicInfo']['courseDuration']; ?>
                  <?php if($isAbroadRecommendation && !$recommendationWidgets['recommendationBasicInfo']['hasDummyDepartment']) { echo ' | Offered by : '.$recommendationWidgets['recommendationBasicInfo']['departmentName']; } ?>
                </td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td height="15"></td>
                <td></td>
              </tr>
              <tr>
                <td></td>
                <td height="25" align="center">
                  <table width="130" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                      <td height="35" align="center" bgcolor="#048088" style="font-family:open sens,Tahoma,Helvetica,sans-serif; font-size:11px; border-radius:5px; font-weight:bold;">
                        <a href="<?php echo $course_url."~recommendation"; ?><!-- #widgettracker --><!-- widgettracker# -->" title="Know more" target="_blank" style="color:#ffffff; text-decoration:none; cursor:pointer; display:block; width:100%; line-height:27px;">Know more</a>
                      </td>
                    </tr>
                  </table>
                </td>
                <td></td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td height="43"></td>
        </tr>
      </table>
    </td>
  </tr>
  <?php 
    if(count($recommendationWidgetPositions)) {
  ?>
  <tr>
    <td height="283" align="center" bgcolor="#f1f1f1">
      <table width="85%" border="0" cellspacing="0" cellpadding="0">
<?php
      $currentRow = 0;
      $currentColumn = 0;
      $newRow = false;
      $totalNumberOfWidgets = count($recommendationWidgetPositions);
      $currentWidget = 0;

      foreach($recommendationWidgetPositions as $widgetName => $widgetPosition) {
        $widgetRow = $widgetPosition['row'];
        $widgetColumn = $widgetPosition['column'];
        $currentWidget++;
        // if($currentColumn > $widgetColumn) {
        //     $newRow = false;
        //     echo '</td></tr>';
        // }
        $currentColumn = $widgetColumn;
  
        if($currentRow != $widgetRow) {
          $newRow = true;
          $currentRow = $widgetRow;
          echo '<tr><td>';
          $data['table_ui'] = 'align="left"';
        }
        
        if(($totalNumberOfWidgets == 1 || $totalNumberOfWidgets == 3) && ($currentWidget == $totalNumberOfWidgets)){
          $data['table_ui'] = 'align="center" class="box"';
        }
        
        if(isset($recommendationWidgets[$widgetName])) {
          $this->load->view('personalizedMailer/widgets/RecoMailerWidgets/'.$widgetName, $data);
        }

        if($widgetColumn % 2 == 0){
          $newRow = false;
          echo '</td></tr>';
        }
      }
    
      // if($newRow == true) {
      //   echo '</td></tr>';
      // }
?>
      </table>
    </td>
  </tr>
<?php } ?>
  <tr>
    <td bgcolor="#f1f1f1" height="8"></td>
  </tr>
  