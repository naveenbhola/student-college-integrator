<div class="clg-predctr" id="cp-banner">
    <div class="clg-bg <?php if(strtoupper($examName) == 'NIFT') echo 'niftBanner'; ?>" id="clg-bg">
        <div class="clg-txBx">
               <p><span><?php echo strtoupper($examName)." ".$examYear ?></span> College Predictor</p>
               <p class="sb-hdng">Use your <?php echo strtoupper($examName)." ".$examYear ?> rank to see<br> where you can get admission</p>
            <ul>
                <li>See the courses, colleges & rounds you may crack</li>
                <li>Based on admissions data of <?php echo strtoupper($examName)." ".(empty($examYear)?"":$examYear-1) ?></li>
                <li>Get resources & insights on colleges & courses</li>
            </ul>
        </div>
    </div>    
</div>