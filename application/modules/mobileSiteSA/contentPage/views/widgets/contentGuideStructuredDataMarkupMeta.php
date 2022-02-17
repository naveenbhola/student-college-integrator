<?php
$contentDetails=$content['data'];
$datetime = new DateTime($contentDetails['created']);
$isoDateTime = $datetime->format(DateTime::ATOM);
?>
<meta itemprop="datePublished" content="<?php echo $isoDateTime; ?>"/>  
<div itemprop="author" itemscope itemtype="http://schema.org/Person"> 
    <meta itemprop="name" content="<?php echo $content['data']['displayName'];?>"/>         	
</div>	 	
<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
    <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
        <meta itemprop="url" content="http://www.shiksha.com/public/images/logo-abroad.gif">
        <meta itemprop="width" content="190">
        <meta itemprop="height" content="53">
    </div>
    <meta itemprop="name" content="studyabroad.shiksha.com">
</div>
<?php if (isset($contentDetails['contentImageURL']) && !empty($contentDetails['contentImageURL'])){?>
 <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
    <meta itemprop="url" content="<?php echo $contentDetails['contentImageURL']; ?>">
    <?php 
        if(($imageInfo=getimagesize($contentDetails['contentImageURL']))){
            ?>
            <meta itemprop="width" content="<?php echo $imageInfo[0] ?>">
            <meta itemprop="height" content="<?php echo $imageInfo[1] ?>">
        <?php }
    ?>
</div>
   <?php
 }
?>