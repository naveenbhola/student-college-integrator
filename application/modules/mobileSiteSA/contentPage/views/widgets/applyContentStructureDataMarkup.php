<?php
$datetime = new DateTime($contentData['created']);
$isoDateTime = $datetime->format(DateTime::ATOM);
?>
<meta itemprop="datePublished" content="<?php echo $isoDateTime; ?>"/>  
<div itemprop="author" itemscope itemtype="http://schema.org/Person"> 
    <meta itemprop="name" content="<?php echo htmlentities($contentData['authorInfo']['firstname']." ".$contentData['authorInfo']['lastname']);?>"/>         	
</div>	 	
<div itemprop="publisher" itemscope itemtype="https://schema.org/Organization">
    <div itemprop="logo" itemscope itemtype="https://schema.org/ImageObject">
        <meta itemprop="url" content="http://www.shiksha.com/public/images/logo-abroad.gif">
        <meta itemprop="width" content="190">
        <meta itemprop="height" content="53">
    </div>
    <meta itemprop="name" content="studyabroad.shiksha.com">
</div>
<?php if (isset($contentData['contentImageURL']) && !empty($contentData['contentImageURL'])){?>
 <div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
    <meta itemprop="url" content="<?php echo $contentData['contentImageURL']; ?>">
    <?php 
        if(($imageInfo=getimagesize($contentData['contentImageURL']))){
            ?>
            <meta itemprop="width" content="<?php echo $imageInfo[0] ?>">
            <meta itemprop="height" content="<?php echo $imageInfo[1] ?>">
        <?php }
    ?>
</div>
   <?php
 }
?>