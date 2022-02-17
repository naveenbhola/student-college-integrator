<?php
    $appliedCity  = $pageData['appliedFilter']['city'];
    $appliedState = $pageData['appliedFilter']['state'];
    $appliedCountry = $pageData['appliedFilter']['country'];
    // this view file represents nested multiselect view for location filter
    // the nesting will always be of 2 level &  based on type of location searched, it can vary.
    /*$listData = array();
    if($type=='continent')
    {
        $locData = array_values($locData);
        foreach($locData as $continent)
        {
            foreach($continent as $countryId => $countryData)
            {
                $listData[$countryId] = array('type'=>'co',
                'name'=>$countryData['name'],
                'id'=>$countryId,
                'count'=>$countryData['count']);
                $listData[$countryId]['children'] = array();
                foreach($countryData as $stateId => $state)
                {
                    $listData[$countryId]['children'][$stateId] = array('name'=>$state['name'],
                                            'id'=>$stateId,
                                            'count'=>$state['count']);
                    unset($state['name']);
                    unset($state['count']);
                    if(count($state)>0) // a continent can have countries that have states as well as countries that have cities directly
                    {
                        $listData[$countryId]['children'][$stateId]['type']='st';
                    }else{
                        $listData[$countryId]['children'][$stateId]['type']='ct';
                    }
                }
            }
        }
    }else if($type=='state'){
        foreach($locData as $stateData)
        {
            unset($stateData['name']);
            unset($stateData['count']);
            foreach($stateData as $cityId => $city)
            {
                $listData[$cityId] = array('type'=>'ct',
                                            'name'=>$city['name'],
                                            'id'=>$cityId,
                                            'count'=>$city['count']);
            }
        }
    }else if($type=='country'){
        $locDataState = ($locData['stateCountries']);
        foreach($locDataState as $countryId=>$countryData)
        {
            unset($countryData['name']);
            unset($countryData['count']);
            foreach($countryData as $stateId=>$stateData)
            {
                $listData[$stateId] = array('type'=>'st',
                                          'name'=>$stateData['name'],
                                          'id'=>$stateId,
                                          'count'=>$stateData['count']);
                $listData[$stateId]['children'] = array();
                unset($stateData['name']);
                unset($stateData['count']);
                foreach($stateData as $cityId => $city)
                {
                    $listData[$stateId]['children'][$cityId] = array('name'=>$city['name'],
                                            'id'=>$cityId,
                                            'type'=>'ct',
                                            'count'=>$city['count']);
                }
            }
        }
        $locDataCity = ($locData['cityCountries']);
        foreach($locDataCity as $countryData)
        {
            unset($countryData['name']);unset($countryData['count']);
            foreach($countryData as $cityId => $city){
                $listData[$cityId] = array('type'=>'ct',
                                          'name'=>$city['name'],
                                          'id'=>$cityId,
                                          'count'=>$city['count']);
            }
        }

    }else if($type=='all'){
        $locDataState = ($locData['stateCountries']);
        foreach($locDataState as $countryId=>$countryData)
        {
            $listData[$countryId] = array('type'=>'co',
                                          'name'=>$countryData['name'],
                                          'id'=>$countryId,
                                          'count'=>$countryData['count']);
            unset($countryData['name']);
            unset($countryData['count']);
            foreach($countryData as $stateId=>$stateData)
            {
                $listData[$countryId]['children'][$stateId] = array('type'=>'st',
                                          'name'=>$stateData['name'],
                                          'id'=>$stateId,
                                          'count'=>$stateData['count']);
            }
        }
        $locDataCity = ($locData['cityCountries']);
        foreach($locDataCity as $countryData)
        { 
            $listData[$countryId] = array('type'=>'co',
                                            'name'=>$countryData['name'],
                                            'id'=>$countryId,
                                            'count'=>$countryData['count']);
            unset($countryData['name']);unset($countryData['count']);
            foreach($countryData as $cityId => $city){
                $listData[$countryId]['children'][$cityId] = array('type'=>'ct',
                                          'name'=>$city['name'],
                                          'id'=>$cityId,
                                          'count'=>$city['count']);
            }
        }

    }
*/
    // in order to check if the filter is disabled we need to compare filter with parent
?>

<ul class="multiList">
    <?php foreach($locationFilter as $listItem){
            if(count($listItem['children'])>0)
            {
                $extraClass = 'insideList'.($listItem['count'] == 0?' disabled':'');
            }else{
                $extraClass = ($listItem['count'] == 0?' disabled':'');
            }
            $disableInput = ($listItem['count'] == 0?'disabled':'');
            $labelString = $listItem['count'];
            if($listItem['type'] == 'co')
            {
                $checkedStr = (in_array($listItem['id'],$appliedCountry)?'checked="checked"':'');
                $fType = 'locationCountry';
            }else if($listItem['type'] == 'st')
            {
                $checkedStr = (in_array($listItem['id'],$appliedState)?'checked="checked"':'');
                $fType = 'locationState';
            }else if($listItem['type'] == 'ct')
            {
                $checkedStr = (in_array($listItem['id'],$appliedCity)?'checked="checked"':'');
                $fType = 'locationCity';
            }else{
                $disabledClassStr = '';
		$checkedStr = '';
            }
        ?>
        <li class="multiLi <?php echo $extraClass; ?>">
            <input type="checkbox" fValue="<?php echo $listItem['heading']; ?>" value="<?php echo $listItem['id']; ?>" fType="<?php echo $fType; ?>" class="toggleFilter" alias="<?php echo $listItem['type']; ?>" name="<?php echo $listItem['type']; ?>" id="<?php echo $listItem['type'].$listItem['id']; ?>" <?php echo $disableInput; ?> <?php echo $checkedStr; ?> >
            <label for="<?php echo $listItem['type'].$listItem['id']; ?>"><span class="black"><?php echo $listItem['heading']; ?></span> <span>(<?php echo $labelString; ?>)</span></label>
            <?php if(count($listItem['children'])>0){ ?>
                <i class="srpSprite iPlus"></i>
                <ul class="inheritList" style="display: none;">
                <?php foreach($listItem['children'] as $childItem){ 
                    $childLabelString = $childItem['count'];
                    $disableChildInput = ($childItem['count'] == 0?'disabled':'');
                    $disabledClassStr = ($childItem['disabled'] == 1?'class="disabled"':'');
                    if($childItem['type']=='st'){
                        $checkedChildStr = (($checkedStr != '' || in_array($childItem['id'],$appliedState)) && $disabledClassStr==''?'checked="checked"':'');
                        $fType = 'locationState';
                    }else{
                        $checkedChildStr = (($checkedStr != '' || in_array($childItem['id'],$appliedCity)) && $disabledClassStr==''?'checked="checked"':'');
                        $fType = 'locationCity';
                    }
                ?>
                <li <?php echo $disabledClassStr; ?>>
                <input type="checkbox" fValue="<?php echo $childItem['heading']; ?>" fType="<?php echo $fType?>" class="toggleFilter" alias="<?php echo $childItem['type']?>" name="<?php echo $childItem['type']; ?>" value="<?php echo $childItem['id']; ?>" id="<?php echo $childItem['type'].$childItem['id']; ?>" <?php echo $disableChildInput; ?> <?php echo $checkedChildStr; ?> >
                <label for="<?php echo $childItem['type'].$childItem['id']; ?>"><span class="black"><?php echo $childItem['heading']; ?></span> <span>(<?php echo $childLabelString; ?>)</span></label>
                </li>
                <?php } ?>
            </ul>
            <?php } ?>
        </li>
    <?php } ?>
</ul>
