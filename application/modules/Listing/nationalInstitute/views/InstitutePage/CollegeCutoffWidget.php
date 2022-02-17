<?php 
if(!empty($cutOffData)){
    ?>
    <div class="new-row">
        <div class="group-card no__pad gap listingTuple" id="clgcutoff">
            <h2 class="head-1 gap">College Cut-Offs</h2>
            <div class="events cutoff">
                <div class="cutoff-div">
                    <div class="column-list">
                        <?php 
                        foreach ($cutOffData['previewText'] as $row) {
                            ?><p><?php echo $row; ?></p><?php
                        }
                        ?>
                    </div>
                </div>
                <div class="gradient-col ankit" id="viewMoreLink" style="display: block">
                    <a href="<?php echo $cutOffData['cutOffUrl']; ?>" class="button button--secondary mL10 arw_link" ga-attr="VIEW_MORE_CUTOFF" target="_blank">View All Cutoffs</a>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>