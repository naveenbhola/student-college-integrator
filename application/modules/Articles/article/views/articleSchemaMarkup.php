<script type="application/ld+json">
    {
        "@context" : "http://schema.org",
        "@type": "Article",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo $canonicalURL; ?>"
        },
        "headline": "<?php echo $blogObj->getTitle(); ?>",
        <?php 
            if(!empty($articleImageData)){
                ?>
                "image" : [
                    <?php 
                    foreach ($articleImageData as $key => $imageData) {
                        ?>
                        {
                            "@type": "ImageObject",
                            "url": "<?php echo $imageData['imageUrl']; ?>",
                            "height": <?php echo $imageData['width']; ?>,
                            "width": <?php echo $imageData['height']; ?>
                        }
                        <?php
                        echo ($key != count($articleImageData)-1) ? ",": "";
                    }
                    ?>
                ],
                <?php
            }
        ?>
        "datePublished": "<?php echo date ("M j, Y, h.iA", strtotime($blogObj->getCreationDate())) . ' IST '; ?>",
        "dateModified": "<?php echo "Updated on: ".date ("M j, Y, h.iA", strtotime($blogObj->getLastModifiedDate())) . ' IST '; ?>",
        "author": {
            "@type": "Person",
            "name": "<?php 
                    if($blogObj->getType() == 'kumkum'){
                        echo 'Kumkum Tandon';
                    }
                    else{
                        echo empty($authoruserName) ? 'Shiksha' : $authoruserName;
                    }
                ?>"
        },
        "publisher":{
            "@type": "Organization",
            "name": "Shiksha",
            "logo": {
                "@type":"ImageObject",
                "url": "https://www.shiksha.com/public/images/shiksha-amp-logo.jpg",
                "height": 60,
                "width":167
            },
            "description": "<?php echo $blogObj->getSummary(); ?>"
        }
    }
</script>