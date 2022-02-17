<div class="fix-admsn">
    <div class="prcs-col">
        <div class="head-fix">
           <a id="closeLayer">✕</a>
           <h3 class="f14__semi clr__0" id="layerTitle">Top <?php echo $rankingPage->getName();?> colleges accepting</h3>
        </div>
   <div class="new-layer-mask">
        <div class="stages-data" id="locationLayer">
          <div class='locationBlock'>
                <div class="cities__block clear__float ">
                    <h4 class="f14__bold clr__0">CITIES</h4>
                    <ul class="citi__col clear__float">
                        <?php foreach ($filters['city'] as $key => $locationFilter) { ?>
                            <li><a class="f12__normal clr__6 fit__block" href="<?=$locationFilter->getUrl();?>" ga-attr="RELATEDRANKING"><?=$locationFilter->getName();?></a></li>
                        <?php } ?>
                    </ul>
                </div>

                <div class="states__block clear__float ">
                    <h4 class="f14__bold clr__0">STATES</h4>
                    <ul class="state__col clear__float">
                        <?php foreach ($filters['state'] as $key => $locationFilter) { ?>
                            <li><a class="f12__normal clr__6 fit__block" href="<?=$locationFilter->getUrl();?>" ga-attr="RELATEDRANKING"><?=$locationFilter->getName();?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="stages-data" id="examLayer">
            <div class="cities__block clear__float">
                <h4 class="f14__bold clr__0">Exams</h4>
                <ul class="citi__col clear__float">
                    <?php foreach($filters['exam'] as $key => $examFilter) { ?>
                        <li><a class="f12__normal clr__6 fit__block" href="<?=$examFilter->getUrl();?>" ga-attr="RELATEDRANKING"><?=$examFilter->getName();?></a></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
        </div>
    </div>
</div>