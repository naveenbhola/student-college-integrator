<!-- Show the Category page Header -->    
<header id="page-header" class="clearfix" data-role="header" data-tap-toggle="false">
    <div class="head-group" data-enhance="false">
	<?php if(isset($pageName) && $pageName === 'postQuestion'){?>
	<a href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left" aria-hidden="true"></span></i></a> 
	
	<?php }?>
	<h3>
	    <?php if(isset($pageName) && $pageName === 'postQuestion'){?>
		<div class="left-align" style="margin-right:98px">
	    <?php } else {?>
		<div class="left-align" style="margin-left:16px !important" >
	    <?php } ?>
		    <?php echo (isset($headerTitle) && $headerTitle !='') ? $headerTitle : 'Question';?>
		</div>
	    
	    
        </h3>
</header>  
<!-- End the Header for Category page -->