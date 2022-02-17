<?php
if(PREF_YEAR_MANDATORY) {
    $prefYearMandatory = 'Yes';
} else {
    $prefYearMandatory = 'No';
}

$fieldSettings = array(
    'email' => array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'No'
    ),
    'isdCode' => array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'No'
    ),
    'mobile' => array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'No'
    ),
    'firstName' => array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'No'
    ),
    'lastName' => array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'No'
    ),
    'residenceCityLocality' => array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'No'
    ),
    'residenceLocality' => array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'No'
    ),
    'password' => array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'No'
    ),
    'prefYear' => array(
        'visible' => 'Yes',
        'mandatory' => $prefYearMandatory,
        'multiSelect' => 'No'
    ),
    'stream' => array(
    	'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'No'
	),
    'educationType'=>array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'YES'
    ),
    'baseCourses'=>array(
        'visible' => 'Yes',
        'mandatory' => 'Yes',
        'multiSelect' => 'Yes'
    ),
    'subStreamSpecializations'=>array(
        'visible' => 'Yes',
        'mandatory' => 'No',
        'multiSelect' => 'Yes'
    ),
    'flowChoice'=>array(
       'visible' => 'Yes',
        'mandatory' => 'No',
        'multiSelect' => 'Yes'      
    )
);