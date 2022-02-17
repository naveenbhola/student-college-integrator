<div style="width:1200px; margin:0 auto; margin-bottom:40px;">
  <?php $this->load->view('common/diffReportTabs'); ?>
  <div id="tabs-1">
        <table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1170'>
          <tr style='background: none repeat scroll 0 0 #f7f7f7;'>
            <th width='50'>#</th>
            <th width='100'>Team</th>
            <th width='150'>Module</th>
            <th width='150'>Controller</th>
            <th width='200'>Method</th>
            <th width='60'>Count</th>
          </tr>
          <?php
          $i = 1;
          foreach ($newSlowPages as $key => $value) {
             $background_color = "";
             if($i % 2 == 0){
                $background_color = "background-color:#F3FAF2";
             }
             $separateNames = explode('-', $key);
            ?>
            <tr style='<?=$background_color;?>'>
              <td><?=$i++;?></td>
              <td><?=$value['team'];?></td>
              <td><?=$separateNames[0];?></td>
              <td><?=$separateNames[1];?></td>
              <td><?=$separateNames[2];?></td>
              <td><?=$value['count'];?></td>
            </tr>
            <?php
          }
          ?>
      </table>
  </div>
  <div id="tabs-2" style="display:none">
        <table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1170'>
          <tr style='background: none repeat scroll 0 0 #f7f7f7;'>
            <th width='50'>#</th>
            <th width='100'>Team</th>
            <th width='150'>Module</th>
            <th width='150'>Controller</th>
            <th width='200'>Method</th>
            <th width='60'>Count</th>
          </tr>
          <?php
          $i = 1;
          foreach ($pagesReduced as $key => $value) {
            $background_color = "";
             if($i % 2 == 0){
                $background_color = "background-color:#F3FAF2";
             }
             $separateNames = explode('-', $key);
            ?>
           <tr style='<?=$background_color;?>'>
              <td><?=$i++;?></td>
              <td><?=$value['team'];?></td>
              <td><?=$separateNames[0];?></td>
              <td><?=$separateNames[1];?></td>
              <td><?=$separateNames[2];?></td>
              <td><?=$value['count'];?></td>
            </tr>
            <?php
          }
          ?>
      </table>
  </div>
  <div id="tabs-3" style="display:none;">
     <table class='exceptionErrorTable' style='word-break:break-all;border-collapse: collapse;margin: auto;margin-top: 12px; border-top:1px solid #ccc; border-left:1px solid #ccc;' width='1170'>
          <tr style='background: none repeat scroll 0 0 #f7f7f7;'>
            <th width='50'>#</th>
            <th width='100'>Team</th>
            <th width='150'>Module</th>
            <th width='150'>Controller</th>
            <th width='200'>Method</th>
            <th width='60'>Count</th>
          </tr>
          <?php
          $i = 1;
          foreach ($pagesIncreasedCount as $key => $value) {
            $background_color = "";
            if($i % 2 == 0){
                $background_color = "background-color:#F3FAF2";
             }
             $separateNames = explode('-', $key);
            ?>
            <tr style='<?=$background_color;?>'>
              <td><?=$i++;?></td>
              <td><?=$value['team'];?></td>
              <td><?=$separateNames[0];?></td>
              <td><?=$separateNames[1];?></td>
              <td><?=$separateNames[2];?></td>
              <td class='redColor'><span style='float:right;margin-right:10px;'>&#x25B2;</span><span style='float:left'><?=$value['count'];?></span> </td>
            </tr>
            <?php
          }

          foreach ($pagesReducedCount as $key => $value) {
            $background_color = "";
            if($i % 2 == 0){
                $background_color = "background-color:#F3FAF2";
             }
             $separateNames = explode('-', $key);
            ?>
            <tr style='<?=$background_color;?>'>
              <td><?=$i++;?></td>
              <td><?=$value['team'];?></td>
              <td><?=$separateNames[0];?></td>
              <td><?=$separateNames[1];?></td>
              <td><?=$separateNames[2];?></td>
              <td  class='greenColor'><span style='float:right;margin-right:10px;'>&#x25BC;</span><span style='float:left'><?=$value['count'];?></span> </td>
            </tr>
            <?php
          }
          ?>
      </table>
  </div>
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
  </style>
