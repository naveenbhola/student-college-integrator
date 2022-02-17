<?php
$rankingPageDataArr = $rankingPageObject->getRankingPageData();
?>
<div class="univ-tab-details clearwidth">
    <table class="ranking-table" cellpadding="0" cellspacing="0">
            <tr class="header-border">
                    <th width="5%">Rank</th>
                <th width="30%">Institute name</th>
                <th width="15%">University Type</th>
                <th width="10%">Accommodation</th>
                <th width="15%">Date of Estd.</th>
                <th width="20%"></th>
            </tr>
            <?php
                $rankCounter = 0;
                foreach($rankingPageDataArr as $ranking){
                $universityObj = $ranking['university'];
                
                if(!($universityObj instanceof University) || ($universityObj instanceof University && $universityObj->getName()=="")){
                    continue;
                }
            ?>
            <tr class="seperator"></tr>
            <tr>
                    <td class="number-bg"><?=++$rankCounter?></td>
                <td>
                    <a href="<?=$universityObj->getURL()?>" target="_blank" style="font-size:14px;"><strong><?=htmlentities($universityObj->getName())?></strong></a>
                    <span class="cntry-title"><?=$universityObj->getLocation()->getCity()->getName().", ",$universityObj->getLocation()->getCountry()->getName()?></span>
                </td>
                <td>
                    <?php
                        if($universityObj->getTypeOfInstitute() == 'public'){
                    ?>
                        <p><span class="tick-mark">&#10004;</span>Public</p>
                    <?  }else{?>
                        <p class="non-available"><span class="cross-mark">&times;</span>Public</p>
                    <?php }?>
                </td>
                <td>
                    <?php if($universityObj->hasCampusAccommodation()){?>
                        <p><span class="tick-mark">&#10004;</span>Available</p>
                    <?php }else{?>
                        <p class="non-available"><span class="cross-mark">&times;</span>Not Available</p>
                    <?php }?>
                </td>
                <td>
                    <?php
                        if($universityObj->getEstablishedYear()){
                            $estdYear = $universityObj->getEstablishedYear();
                        }else{
                            $estdYear = "---";
                        }
                    ?>
                    <p><?=$estdYear?></p>
                </td>
                <td class="last">
                    <?php
                        //get user preference, education data (to populate when do you plan to start?, exams taken)
                        //$userStartTimePrefWithExamsTaken = $this->getUserStartTimePrefWithExamsTaken($displayData['validateuser']);
                        $dataObj    =   array(
                                            'sourcePage'            =>  'university_rankingpage_abroad',
                                            'universityId'          =>  $universityObj->getId(),
                                            'universityName'        =>  $universityObj->getName(),
                                            'destinationCountryId'  =>  $universityObj->getLocation()->getCountry()->getId(),
                                            'destinationCountryName'=>  $universityObj->getLocation()->getCountry()->getName(),
                                            'widget'                =>  'universityRankingPage',
                                            'trackingPageKeyId'     => 28,
                                            'consultantTrackingPageKeyId' => 391
                                        );
                    ?>
                <div>
                    <a href="javascript:void(0);" class="button-style" style="margin-top:20px;" onclick = "loadBrochureDownloadForm('<?=base64_encode(json_encode($dataObj))?>','/responseAbroad/ResponseAbroad/getBrochureDownloadForm','downloadBrochureFormContainer','downloadBrochure');"><i class="common-sprite dwnld-icon"></i><strong>Download Brochure</strong></a>
                </div>
                </td>
            </tr>
            
            <?php }?>
        </table>
</div>
