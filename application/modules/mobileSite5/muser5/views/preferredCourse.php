<header id="page-header" class="clearfix">
        <div data-role="header" data-position="fixed" class="head-group ui-header-fixed">
             <a id="courseOverlayClose" href="javascript:void(0);" data-rel="back"><i class="head-icon-b"><span class="icon-arrow-left"></span></i></a>
            <h3>Choose Course</h3>
        </div>
</header>
<section class="layer-wrap fixed-wrap">
	<ul class="layer-list2">
    <!--	<li class="search-option">
        	<form id="searchbox2" action="">
            	<span class="icon-search" aria-hidden="true"></span>
                <input id="search" type="text" placeholder="MBA">
                <i class="icon-cl"><span class="icon-close" aria-hidden="true"></span></i>
            </form>
        </li>-->
	 <?php foreach($courseListOptions as $key=>$value){?>
	<?php if($key!=0){?>
    		<li onClick="courseSelected('<?=$key;?>');" style="cursor:pointer;" id="courseName<?php echo $key;?>"><?php echo $value;?></li>
	<?php } ?>
	<?php } ?>
    </ul>
</section>
