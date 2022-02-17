<div style="width:1200px; margin:0 auto; margin-bottom:40px;">
  <?php $this->load->view('common/diffReportTabs'); ?>
  <div id="tabs-1">
        <table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1170'>
          <tr style='background: none repeat scroll 0 0 #f7f7f7;'><th width='30'>#</th><th>Query</th><th width='100'>Server</th><th width='60'>Count</th></tr>
          <?php
          $i = 1;
          foreach ($newSlowQueries as $key => $value) {
             $background_color = "";
             if($i % 2 == 0){
                $background_color = "background-color:#F3FAF2";
             }
            ?>
            <tr style='<?=$background_color;?>'>
              <td><?=$i++;?></td>
              <td style='font-family:monospace; font-size:11px;'>
                  <?php
                   if(strlen($key) > 200){
                  ?>
                  <a href='javascript:void(0);' onclick="$('#elps_newQ<?php echo $i; ?>').hide(); $('#fullq_newQ<?php echo $i; ?>').show(); return false;" style="color:#333; text-decoration:none;">
                  <?php
                }
                  echo substr($key, 0, 200);
                  if(strlen($key) > 200){
                    echo "</a>";
                    echo "<span id='elps_newQ".$i."'>...</span>";
                    echo "<span id='fullq_newQ".$i."' style='display:none;'>".(substr($key,200))."</span>";
                  }
                  echo "</a>";
                  
                ?>
              </td>
              <td><?=$value['server'];?></td>
              <td><?=$value['count'];?></td>
            </tr>
            <?php
          }
          ?>
      </table>
  </div>
  <div id="tabs-2" style="display: none;">
        <table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>
          <tr style='background: none repeat scroll 0 0 #f7f7f7;'><th width='30'>#</th><th>Query</th><th width='100'>Server</th><th width='60'>Count</th></tr>
          <?php
          $i = 1;
          foreach ($queriesReduced as $key => $value) {
            $background_color = "";
             if($i % 2 == 0){
                $background_color = "background-color:#F3FAF2";
             }
            ?>
           <tr style='<?=$background_color;?>'>
              <td><?=$i++;?></td>
              <td style='font-family:monospace; font-size:11px;'>
                  <?php
                   if(strlen($key) > 200){
                  ?>
                  <a href='javascript:void(0);' onclick="$('#elps_redQ<?php echo $i; ?>').hide(); $('#fullq_redQ<?php echo $i; ?>').show(); return false;" style="color:#333; text-decoration:none;">
                  <?php
                }
                  echo substr($key, 0, 200);
                  if(strlen($key) > 200){
                    echo "</a>";
                    echo "<span id='elps_redQ".$i."'>...</span>";
                    echo "<span id='fullq_redQ".$i."' style='display:none;'>".(substr($key,200))."</span>";
                  }
                  echo "</a>";
                  
                ?>
              </td>
              <td><?=$value['server'];?></td>
              <td><?=$value['count'];?></td>
            </tr>
            <?php
          }
          ?>
      </table>
  </div>
  <div id="tabs-3" style="display: none;">
     <table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1180'>
          <tr style='background: none repeat scroll 0 0 #f7f7f7;'><th width='30'>#</th><th>Query</th><th width='100'>Server</th><th width='60'>Count</th></tr>
          <?php
          $i = 1;
          foreach ($queriesIncreasedCount as $key => $value) {
            $background_color = "";
            if($i % 2 == 0){
                $background_color = "background-color:#F3FAF2";
             }
            ?>
            <tr style='<?=$background_color;?>'>
              <td><?=$i++;?></td>
                <td style='font-family:monospace; font-size:11px;'>
                  <?php
                   if(strlen($key) > 200){
                  ?>
                  <a href='javascript:void(0);' onclick="$('#elps_chngQ<?php echo $i; ?>').hide(); $('#fullq_chngQ<?php echo $i; ?>').show(); return false;" style="color:#333; text-decoration:none;">
                  <?php
                }
                  echo substr($key, 0, 200);
                  if(strlen($key) > 200){
                    echo "</a>";
                    echo "<span id='elps_chngQ".$i."'>...</span>";
                    echo "<span id='fullq_chngQ".$i."' style='display:none;'>".(substr($key,200))."</span>";
                  }
                  echo "</a>";
                  
                ?>
              </td>
              <td><?=$value['server'];?></td>
              <td class='redColor'><span style='float:right;margin-right:10px;'>&#x25B2;</span><span style='float:left'><?=$value['count'];?></span></td>
            </tr>
            <?php
          }

          foreach ($queriesReducedCount as $key => $value) {
            $background_color = "";
            if($i % 2 == 0){
                $background_color = "background-color:#F3FAF2";
             }
            ?>
            <tr style='<?=$background_color;?>'>
              <td><?=$i++;?></td>
                              <td>
                  <?php
                   if(strlen($key) > 200){
                  ?>
                  <a href='javascript:void(0);' onclick="$('#elps_chngQ<?php echo $i; ?>').hide(); $('#fullq_chngQ<?php echo $i; ?>').show(); return false;" style="color:#333; text-decoration:none;">
                  <?php
                }
                  echo substr($key, 0, 200);
                  if(strlen($key) > 200){
                    echo "</a>";
                    echo "<span id='elps_chngQ".$i."'>...</span>";
                    echo "<span id='fullq_chngQ".$i."' style='display:none;'>".(substr($key,200))."</span>";
                  }
                  echo "</a>";
                  
                ?>
              </td>
               <td><?=$value['server'];?></td>
              <td  class='greenColor'><span style='float:right;margin-right:10px;'>&#x25BC;</span><span style='float:left'><?=$value['count'];?></span> </td>
            </tr>
            <?php
          }
          ?>
      </table>
  </div>
  </div>
  <script>
  function activateTab(id)
  {
      for(i=1;i<=3;i++) {
        $('#tabs-'+i).hide();
        $('#tablink'+i).removeClass('internaltabsactive');
      }
      
      $('#tabs-'+id).show();
      $('#tablink'+id).addClass('internaltabsactive');
  }
  </script>
  <style type="text/css">
  .redColor{
    color: #f00 !important;
    font-weight: bold;
  }
  .greenColor{
    color: green !important;
    font-weight: bold;

  }
  .exceptionErrorTable11{
    width: 100%;
    font-family: monospace !important;
    color: #ccc;
  }
  </style>
