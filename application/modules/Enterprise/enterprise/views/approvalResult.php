<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','footer'),
      'js'			=> array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils'),
      'title'			=> "Listings ".$type."D",
      'product' 		=> '',
      'displayname'	=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
   );
   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>
<div class="mar_full_10p">
<div>
<div class="lineSpace_10">&nbsp;</div>
<h3>You have successfully <?php echo $type;?>D the following Listings..</h3>
<div class="lineSpace_10">&nbsp;</div>
<table border="1">
<tr class='bld OrgangeFont'>
<td>Listing-Type</td>
<td>Listing-Type-Id</td>
</tr>
<?php $count = count($result);
for($i=0;$i<$count;$i++){ ?>
	<tr>
		<td><?php echo $result[$i]['type']; ?> </td>
		<td><?php echo $result[$i]['typeId']; ?> </td>
		</tr>
        <?php
}
?> 
</table> 
</div>

</div>
</body>
</html>
