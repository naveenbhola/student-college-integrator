<div style="padding: 10px;">
    <p class="no-consultant-sec" style="margin-bottom:8px;">
        <strong>No consultant found in selected region</strong>
    </p>
    <p>
        Please <strong>change</strong> region to find consultants in other regions
    </p>
    <div class="cutsom-cons-dropdwn">
        <i class="cate-sprite consultant-loc-icon-2"></i>
        <select onchange="triggerConsultantCityChange('<?=$courseObj->getId()?>',this); studyAbroadTrackEventByGA('ABROAD_CAT_PAGE', 'catPageConsultantChangeRegionMiddle');">
            <?php foreach($regionConsultantMapping as $regionId => $region){ ?>
            <option value="<?=$regionId?>"><?=$region['regionName']?></option>
            <?php } ?>
        </select>
    </div>
</div>
