<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','footer'),
      'js'			=> array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils'),
      'title'			=> "SUMS - ".$pageInfo['product']." ".$pageInfo['type']."ed",
      'product' 		=> '',
      'displayname'	=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
   );
   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>
<div class="mar_full_10p">
   <div>
       <h2>You have successfully approved Transaction with Transaction-Id : <?php echo $result['ApprovedTransaction'];?></h2>
       <?php if(isset($Created_Subscriptions)){ ?>
       <h3>The Subcription Ids Created are as follows:
           <?php foreach($Created_Subscriptions as $key=>$val) {
                   echo $val;
               }
           ?>
       </h3>
       <?php } ?>
   </div>

</div>
</body>
</html>
