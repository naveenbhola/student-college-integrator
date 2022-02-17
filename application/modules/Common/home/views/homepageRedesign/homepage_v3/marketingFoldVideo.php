 <div class="videoSec"> 	
    <?php 
    $videoUrl = $data[0]['targetURL'];
 	preg_match('#(v\/|watch\?v=)([\w\-]+)#', $videoUrl, $match);
    echo preg_replace('#((https://)?(www.)?youtube\.com/watch\?[=a-z0-9&_;-]+)#i',"<iframe title=\"YouTube video player\" width=\"512\" height=\"288\" src=\"https://www.youtube.com/embed/$match[2]?autoplay=$autoplay\" frameborder=\"0\" allowfullscreen></iframe>",$videoUrl);?>
    <!--<div class="img-caption">
        <p class="videoCaption">
        <?php //echo $data[0]['header']; ?>
        </p>
    </div>-->
</div>