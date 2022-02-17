<?php 
if(!empty($cutOffData)){
    ?>
    <div class="crs-widget listingTuple" id="clgcutoff">
        <h2 class="head-L2">College Cut-Offs</h2>
        <div class="lcard">
            <div class="cutoff-secDv">
                <div class="column-list">
                    <?php 
                    foreach ($cutOffData['previewText'] as $row) {
                        ?><p><?php echo $row; ?></p><?php
                    }
                    ?>
                </div>
            </div>
            <div class="gradient-col ankit" id="viewMoreLink" style="display: block">
                <a href="<?php echo $cutOffData['cutOffUrl']; ?>" class="btn-tertiary  mL10" ga-attr="VIEW_MORE_CUTOFF" >View All Cutoffs</a>
            </div>
        </div>
    </div>
<?php
}
?>