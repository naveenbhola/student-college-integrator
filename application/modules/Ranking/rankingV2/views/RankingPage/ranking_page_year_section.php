<table>
    <tr>
        <?php 
            $publisherData = $rankingPage->getPublisherData();
            foreach($publisherData as $sourceDetails) { ?>
                <th width="7%" class="tac fnt__sb">Rank'<?=substr($sourceDetails['year'], -2);;?></th>
        <?php } ?>
        <th width="86%" colspan="3" class="fnt__sb p-Lft">Colleges</th>
    </tr>
</table>