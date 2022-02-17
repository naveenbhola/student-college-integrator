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
    <div class="lineSpace_10">&nbsp;</div>
   <div>
	<h2>You have successfully <?php echo $pageInfo['type'];?>ed <?php echo $pageInfo['product'];?> with ID : <?php echo $pageInfo['id'];?></h2>
    </div>
    <?php if ($pageInfo['product']=='Quotation') { ?>
    
    <div class="lineSpace_10">&nbsp;</div>
    <?php if(array_key_exists(6,$sumsUserInfo['sumsuseracl']))
        {?>
        <button class="btn-submit7 w7" name="createTrans" id="createTrans" value="" type="button" 
            <?php if($quoteType == 'customized'){ ?>
            onClick="window.location='/sums/Quotation/editCustomizedQuote/<?php echo $pageInfo['id'];?>/1/ACTIVE/20';" style="">
            <?php }else{ ?>
            onClick="window.location='/sums/Quotation/editQuotation/<?php echo $pageInfo['id'];?>/1/ACTIVE/20';" style="">
            <?php } ?>
            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Convert this Quotation to Transaction</p></div>
        </button>
        <?php } ?>
        
        <?php if($quoteType == 'standard'){ ?>
            <?php if(array_key_exists(3,$sumsUserInfo['sumsuseracl']))
            {?>
            <button class="btn-submit7 w7" name="createQu" id="createQu" value="" type="button" onClick="window.location='/sums/Quotation/editQuotation/<?php echo $pageInfo['id'];?>/-1/ACTIVE/20';" style="margin-left:100px">
                <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Edit this Quotation</p></div>
            </button>
            <?php } ?>
        <?php } ?>
            
        <?php if($quoteType == 'customized'){ ?>
        <?php if(array_key_exists(5,$sumsUserInfo['sumsuseracl']))
            {?>
            <button class="btn-submit7 w7" name="createQu" id="createQu" value="" type="button" onClick="window.location='/sums/Quotation/editCustomizedQuote/<?php echo $pageInfo['id'];?>/-1/ACTIVE/20';" style="margin-left:100px">
                <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Edit this Customized Quotation</p></div>
            </button>
            <?php } ?>
        <?php } ?>

   <?php } ?>

</div>
</body>
</html>
