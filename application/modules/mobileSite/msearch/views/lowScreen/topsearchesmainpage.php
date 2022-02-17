<?php 
$this->load->view('/mcommon/header',$headerComponents);
?>
<div id="head-sep"></div>
<div style="padding-bottom:3px" class="inst-box">
	<div class="search-title">
    	<span style="color:black;">Top Searches on Shiksha</span>
    </div>
    
    
    <div class="clearFix"></div>
    <span class="cloud-arr">&nbsp;</span>
	</div>


<div id="content-wrap">
	<div id="contents">
    	<ul>
	    <?php foreach($top_searches as $key=>$value):?>		
            <li><div class="cate-list"><a href="<?php echo SHIKSHA_HOME.$key;?>"><?php echo $value;?></a></div></li>
            <?php endforeach;?>
            <li class="blank">&nbsp;</li>
        </ul>
    </div>
<?php $this->load->view('/mcommon/footer');?>
