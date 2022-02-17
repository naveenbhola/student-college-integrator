<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

    <?php
        $headerComponents = array(
            'css'      =>        array('headerCms','raised_all','mainStyle','footer'),
            'js'         =>            array('user','tooltip','common','newcommon','prototype','scriptaculous','utils'),
            'title'      =>        'Products and Services',
            'tabName'          =>        'P&C',
            'taburl' =>  site_url('enterprise/Enterprise/prodAndServ'),
            'metaKeywords'  =>'Some Meta Keywords',
            'product' => '',
            'search'=>false,
            'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
            'callShiksha'=>1
        );
        $this->load->view('enterprise/headerCMS', $headerComponents);
    ?>
</head>

<div class="lineSpace_5">&nbsp;</div>

<?php $this->load->view('enterprise/cmsTabs'); ?>


<div style="line-height:10px">&nbsp;</div>
<div class="mar_full_10p">
    <table width="100%">
     <tr>
         <td width="223" valign="top">
     <div style="width:223px;">
        <div class="raised_greenGradient"> 
            <b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
            <div class="boxcontent_greenGradient1">				  		
                <div class="mar_full_10p">
                    <div class="lineSpace_10">&nbsp;</div>
                    <div class="fontSize_14p bld">What We Offer </div>
                    <div class="lineSpace_10">&nbsp;</div>

                </div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
        <div class="lineSpace_10">&nbsp;</div>
        <div class="raised_greenGradient"> 
            <b class="b1"></b><b class="b2"></b><b class="b3" style="background-color:#F5FDE6"></b><b class="b4" style="background-color:#F5FDE6"></b>
            <div class="boxcontent_greenGradient1">				  		
                <div class="mar_full_10p">
                    <div class="float_L" style="cursor:pointer;" >
                        <div class="lineSpace_5">&nbsp;</div>
                        <div class="arrowBullets"><a onClick="productView('L');">Listing Solutions</a></div>
                        <div class="lineSpace_5">&nbsp;</div>
                        <div class="arrowBullets"><a onClick="productView('A');">Keyword based Advertising Solutions</a></div>
                        <div class="lineSpace_5">&nbsp;</div>
                        <div class="arrowBullets"><a onClick="productView('B');">Branding Solutions</a></div>
                        <div class="lineSpace_5">&nbsp;</div>
                    </div>
                    <div class="clear_L">&nbsp;</div>
                </div>
            </div>
            <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
        </div>
    </div>

    <div class="lineSpace_20">&nbsp;</div>
    <div class="normaltxt_11p_blk bld">
        <button class="btn-submit7 w9" id="goBkEnterp" value="Go_Back_Enterprise" type="button" onClick="window.location='<?php echo site_url().'/enterprise/Enterprise'; ?>'" style="width:125px">
            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Go Back</p></div>
        </button>
    </div>

</td>
    <td>
    <?php
        $this->load->view('enterprise/listingSolution');
        $this->load->view('enterprise/brandingSolution');
        $this->load->view('enterprise/advSolution');
    ?>
</td></tr></table>
</div>
<div class="lineSpace_35">&nbsp;</div>
</body>
</html>
<?php $this->load->view('enterprise/footer'); ?>

<script>
    function productView(prodId){
            switch(prodId){
                    case 'L':
                    $('listingSol').style.display = 'inline';
                    $('brandingSol').style.display = 'none';
                    $('advertiseSol').style.display = 'none';
                    break;
                    case 'A':
                    $('listingSol').style.display = 'none';
                    $('brandingSol').style.display = 'none';
                    $('advertiseSol').style.display = 'inline';
                    break;
                    case 'B':
                    $('listingSol').style.display = 'none';
                    $('brandingSol').style.display = 'inline';
                    $('advertiseSol').style.display = 'none';
                    break;
                    default:
            }
    }
</script>
