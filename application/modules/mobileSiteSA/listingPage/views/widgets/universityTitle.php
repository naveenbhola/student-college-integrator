<section class="detail-widget _headingSec">
    <div class="detail-widegt-sec">
        <div class="detail-info-sec" style="padding:5px 10px;">
            <h1><?=htmlentities($universityObj->getName())?></h1>
            <p style="color:#999"><?=$universityObj->getMainLocation()->getCity()->getName().' , '.$universityObj->getMainLocation()->getCountry()->getName()?></p>
        </div>
    </div>
</section>
