<?php 
if(is_array($highlights) && count($highlights)>0){
?>
    <section>
             <div class="data-card m-5btm" id="high">
                 <h2 class="color-3 f16 heading-gap font-w6">Highlights</h2>
                 <div class="card-cmn color-w">
                     <input type="checkbox" class="read-more-state hide" id="post-11" />
                     <ol class="highlights-ol read-more-wrap">
                        <?php 
                        $highLiClassName = "";
                        for ($i=0; $i<count($highlights); $i++){ ?>
                        <?php if($i==4){
                            $highLiClassName = "read-more-target";
                        } ?>
                                <li class="color-6 f13 <?=$highLiClassName;?> word-break">
                                    <?=nl2br(makeURLAsHyperlink(htmlentities($highlights[$i]->getDescription()),true))?>
                                </li>
                        <?php } ?>
                     </ol>
                     <?php if(count($highlights)>4){?>
                        <label for="post-11" class="read-more-trigger color-b t-cntr f14 color-b block font-w6 v-arr ga-analytic" data-vars-event-name="Highlight_VIEWALL">View all</label>
                     <?php } ?>
                 </div>
             </div>
    </section>
<?php } ?>    