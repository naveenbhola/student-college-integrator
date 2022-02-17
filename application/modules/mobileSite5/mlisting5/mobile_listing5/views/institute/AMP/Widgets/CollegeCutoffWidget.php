<?php 
if(!empty($cutOffData)){
    ?>
    <section>
        <div class="data-card m-5btm pos-rl" >
            <h2 class="color-3 f16 heading-gap font-w6">College Cut-Offs</h2>
            <div class="card-cmn color-w schlrs-ampDv">
                <div class="cutoff-div">
                    <div class="column-list">
                        <?php 
                        foreach ($cutOffData['previewText'] as $row) {
                            ?><p><?php echo $row; ?></p><?php
                        }
                        ?>
                    </div>
                </div>
                <div class="gradient-col hide-class" style="display: block">
                    <a href="<?php echo $cutOffData['cutOffUrl']; ?>" class="color-b btn-tertiary f14 ga-analytic" data-vars-event-name="VIEW_MORE_CUTOFF" >View All Cutoffs</a>
                </div>
            </div>
        </div>
    </section>

<?php
}
?>