<?php
	   function  getStringwithWBR($str)
	   {
		$str = (strlen($str) <= 75)?$str:substr($str,0,75)."...";
		$newStr = '';
		for($i=0;$i<strlen($str);$i=($i+10))
		{
			$newStr .= substr($str,$i,10)."<wbr>";
		}	
		$newStr .= substr($str,strlen($newStr),strlen($str));
		return $newStr;
	   }
	
	if(isset($css) && is_array($css)) : 
?>
	<?php foreach($css as $cssFile) : ?>
        <?php if ($cssFile=="header" && ($_COOKIE['client']<=800)) : ?>
             <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile."800x600"); ?>" type="text/css" rel="stylesheet" />
        <?php else: ?>
            <link href="//<?php echo CSSURL; ?>/public/css/<?php echo getCSSWithVersion($cssFile); ?>" type="text/css" rel="stylesheet" />
        <?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
<?php if(isset($js) && is_array($js)) :?>
	<?php foreach($js as $jsFile): ?>
            <?php if(in_array($jsFile,$jsToBeExcluded)){?>
                <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo $jsFile; ?>.js"></script>
            <?php } else { ?>
                <script language="javascript" src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion($jsFile); ?>"></script>
            <?php } ?>
	<?php endforeach; ?>
<?php endif; ?>
<div class="raised_sky" id = "AskPanel" >               
<div class="boxcontent_skyCollegeGrp" style="background:none">
<div class="row normaltxt_12p_blk bld" style="width:100%;background:#E3EDF9; border-top:1px solid #C8ECFC;  border-bottom:1px solid #C8ECFC; height:33px">
<div class="quesAnsleftIcon">Answer Questions &amp; Earn Shiksha Points</div>
</div>

<div class="row" id = "relatedquestions">
<!-- <div class="row" id="relatedLoader" align="center"><img src="/public/images/loader.gif" /></div> -->
<?php $i=0; foreach($relatedTopics as $temp): 
$question =  getStringwithWBR($temp['msgTxt']);
$i++;
if($i%2==0){
    $bgColor ='background:#F5F6F8';
}else{
    $bgColor ='background:#FFFFFF';
}
?>
<div class = "row" id="relatedQues<?php echo $i; ?>" style="<?php echo $bgColor; ?>;padding:5px 0;">
<div class="quesAnsBullets"><a class = "fontSize_12p" href ="#" onClick="javascript:reloadParentFromIframe('<?php echo $temp['url']; ?>');" style = "padding-left:4px"><?php echo $question; ?></a></div>
<div class = "lineSpace_5">&nbsp;</div>
<span class="arrowBullets" style="margin-left:10px"><a class = "fontSize_12p bld" href = "#" onClick="javascript:reloadParentFromIframe('<?php echo $temp['url']; ?>/askHome#gRep');" style = "padding-left:5px">Answer Now</a></span>
</div>
<?php endforeach; ?>
</div>

<div class = "fontSize_12p" id = "noquesmsg" align = "center"></div>

<div class="lineSpace_11">&nbsp;</div>
</div>
<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
</div>
<div class="lineSpace_10">&nbsp;</div>

<script>
	getRelatedContent('1',showRelatedQues,parent.document.body.innerHTML);
    function reloadParentFromIframe(urlForParent)
    {
        parent.location = urlForParent;
    }
</script>
