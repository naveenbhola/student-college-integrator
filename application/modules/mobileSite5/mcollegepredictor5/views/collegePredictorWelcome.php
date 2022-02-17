<div class="clg-predctr <?php if($resultPageFlag){ echo " hid";}?>" id="cp-banner">
    <div class="clg-bg" id="clg-bg">
        <div class="clg-txBx">
               <p><span><?php echo strtoupper($examName)." ".$examYear;?></span> College Predictor</p>
               <p class="sb-hdng">Use your <?php echo $examName." ".$examYear;?> rank to see where you can get admission</p>
            <ul>
                <li>See the courses, colleges & rounds you may crack</li>
                <li>Based on admissions data of <?php echo $examName." ".(empty($examYear)?"":$examYear-1);?></li>
                <li>Get resources & insights on colleges & courses</li>
            </ul>
        </div>
    </div>
</div>
