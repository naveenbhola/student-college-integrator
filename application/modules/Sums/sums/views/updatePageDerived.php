<?php
   $headerComponents = array(
      'css'			=> array('headerCms','raised_all','mainStyle','footer'),
      'js'			=> array('user','tooltip','common','newcommon','listing','prototype','scriptaculous','utils'),
      'title'			=> "SUMS - Derived Products ".$type."d",
      'product' 		=> '',
      'displayname'	=> (isset($sumsUserInfo['validity'][0]['displayname'])?$sumsUserInfo['validity'][0]['displayname']:""),
   );
   $this->load->view('enterprise/headerCMS', $headerComponents);
   $this->load->view('enterprise/cmsTabs',$sumsUserInfo);
?>
<div class="mar_full_10p">
<div>
<div class="lineSpace_10">&nbsp;</div>
<h3>You have successfully <?php echo $type;?>d the following Derived Products..</h3>
<div class="lineSpace_10">&nbsp;</div>
<table border="1">
<tr class='bld OrgangeFont'>
    <td>Derived-Product-Ids</td>
</tr>
<?php $count = count($result['subsArr']);
for($i=0;$i<$count;$i++){ ?>
    <tr>
        <td><?php echo $result['subsArr'][$i]; ?> </td>
        </tr>
        <?php
}
?> 
</table> 
<div class="lineSpace_10">&nbsp;</div>

<?php if(array_key_exists(11,$sumsUserInfo['sumsuseracl']))
        {?>
        <button class="btn-submit7 w7" id="createTrans" value="" type="button" 
            onClick="window.location='/sums/Product/getAllDerivedProducts';" style="">
            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">See Enable/Disable Derived Products Page</p></div>
        </button>
        <?php } ?>
    
    </div>

</div>
</body>
</html>
