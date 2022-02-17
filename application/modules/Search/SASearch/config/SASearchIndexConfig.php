<?php

$config['LOCATION_SYNONYMS'] = array(
	'COUNTRIES' => array(
		4 => "Britain, England, United Kingdom",
		3 => "US, America, United States, United States of America",
        14=> "Holland",
        7=> "NZ",
        12=> "French",
        9=> "German",
		8 => ""
	),
	'STATES' => array(
        151 => "Cali"
	),
	'CITIES' => array(
        11269 => "NY"
	),
	'CONTINENTS' => array(
	)
);
$config['QER_SYNONYM_MAPPING'] = array(
    "LEVEL"=>array(
        'Masters'	=>"PG, Post Graduate, Graduate Degree, Masters Degree, Postgrad, Master",
        'Bachelors'	=> "UG, Under Graduate, Undergraduate Degree, Undergraduate, Undergrad, Bachelor",
        'Masters_Diploma'	=> "PG Diploma, Diploma, postgraduate diploma, postgrad diploma, master diploma",
        'Bachelors_Diploma'	=> "UG Diploma, Diploma, postgraduate certificate, postgrad certificate, bachelor diploma",
        'Masters_Certificate'	=> "PG Certificate, Certificate, Graduation certificate, postgrad certificate, postgraduation certificate, master certificate",
        'Bachelors_Certificate'	=> "UG Certificate, Certificate, undergrad certificate, undergraduation certificate, bachelor certificate",
        'PhD'	=>"Doctorate, Doctor, Doc"),
    "CATEGORY"=>array(
        239=>"Management")
    );

$config['INVENTORY_TYPE_CAT_SPONSOR']=1;
$config['INVENTORY_TYPE_MAIN']=2;
$config['INVENTORY_TYPE_PAID']=3;
$config['INVENTORY_TYPE_FREE']=4;

$config['GPA_TO_PERCENTAGE_MAPPING']=array(
    array(
        'start'=>3.63,
        'end'=>4,
        'conversionFactor'=>25
    ),
    array(
        'start'=>3.25,
        'end'=>3.62,
        'conversionFactor'=>24.59
    ),
    array(
        'start'=>2.88,
        'end'=>3.24,
        'conversionFactor'=>24.38
    ),
    array(
        'start'=>2.5,
        'end'=>2.87,
        'conversionFactor'=>24.04
    ),
    array(
        'start'=>1.8,
        'end'=>2.49,
        'conversionFactor'=>23.69
    ),
    array(
        'start'=>1,
        'end'=>1.79,
        'conversionFactor'=>27.37
    ),
    array(
        'start'=>0,
        'end'=>1,
        'conversionFactor'=>27.36
    )
);
$config['SYNONYM_AUTOSUGGESTOR_MAPPING']=  array(
    // array('synonym' => 'BBA', 'courseLevel' => 'Bachelors', 'categoryId' => '239', 'subCatId' => 'All', 'specialization' => 'All')
    // ,
    array('synonym' => 'MCA', 'courseLevel' => 'Masters', 'categoryId' => '241', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    // array('synonym' => 'MBBS', 'courseLevel' => 'Bachelors', 'categoryId' => '243', 'subCatId' => 'All', 'specialization' => 'All')
    // ,
    // array('synonym' => 'BHM', 'courseLevel' => 'Bachelors', 'categoryId' => '239', 'subCatId' => '256', 'specialization' => 'All')
    // ,
    array('synonym' => 'BTech', 'courseLevel' => 'Bachelors', 'categoryId' => '240', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    array('synonym' => 'BE', 'courseLevel' => 'Bachelors', 'categoryId' => '240', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    // array('synonym' => 'MA', 'courseLevel' => 'Masters', 'categoryId' => '244', 'subCatId' => 'All', 'specialization' => 'All')
    // ,
    array('synonym' => 'BArch', 'courseLevel' => 'Bachelors', 'categoryId' => '240', 'subCatId' => '262', 'specialization' => 'All')
    ,
    // array('synonym' => 'MArch', 'courseLevel' => 'Masters', 'categoryId' => '240', 'subCatId' => '262', 'specialization' => 'All')
    // ,
    array('synonym' => 'MTech', 'courseLevel' => 'Masters', 'categoryId' => '240', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    array('synonym' => 'MTech', 'courseLevel' => 'Masters', 'categoryId' => '241', 'subCatId' => 'All', 'specialization' => 'All')
    ,
  
    array('synonym' => 'LLB', 'courseLevel' => 'Bachelors', 'categoryId' => '245', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    array('synonym' => 'LLM', 'courseLevel' => 'Masters', 'categoryId' => '245', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    // array('synonym' => 'MIS', 'courseLevel' => 'All', 'categoryId' => '241', 'subCatId' => 'All', 'specialization' => array(2086,2087,2088,2089,2090,2091,2092)) 
    // ,
    // array('synonym' => 'MIM', 'courseLevel' => 'Masters', 'categoryId' => '239', 'subCatId' => 'All', 'specialization' => 'All')
    // ,
    array('synonym' => 'BCom', 'courseLevel' => 'Bachelors', 'categoryId' => '239', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    array('synonym' => 'MCom', 'courseLevel' => 'Masters', 'categoryId' => '239', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    array('synonym' => 'BA', 'courseLevel' => 'Bachelors', 'categoryId' => '241', 'subCatId' => '276', 'specialization' => 'All')
    ,
      array('synonym' => 'BA', 'courseLevel' => 'Bachelors', 'categoryId' => '244', 'subCatId' => 'All', 'specialization' => 'All')
  ,
    // array('synonym' => 'BSc', 'courseLevel' => 'Bachelors', 'categoryId' => '241', 'subCatId' => 'All', 'specialization' => 'All')
    // ,
    //   array('synonym' => 'BSc', 'courseLevel' => 'Bachelors', 'categoryId' => '242', 'subCatId' => 'All', 'specialization' => 'All')
    // ,
  
    array('synonym' => 'BFA', 'courseLevel' => 'Bachelors', 'categoryId' => '244', 'subCatId' => '323', 'specialization' => 'All')
    ,
    //array('synonym' => 'MFA', 'courseLevel' => 'Masters', 'categoryId' => '244', 'subCatId' => '323', 'specialization' => 'All')
    //,
    //array('synonym' => 'MCA', 'courseLevel' => 'Masters', 'categoryId' => '241', 'subCatId' => '277', 'specialization' => 'All')
    //,
    array('synonym' => 'BCA', 'courseLevel' => 'Bachelors', 'categoryId' => '241', 'subCatId' => '277', 'specialization' => 'All')
    ,
    array('synonym' => 'PGDM', 'courseLevel' => 'Masters', 'categoryId' => '239', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    array('synonym' => 'PGDBA', 'courseLevel' => 'Masters', 'categoryId' => '239', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    array('synonym' => 'BMS', 'courseLevel' => 'Bachelors', 'categoryId' => '239', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    array('synonym' => 'MMS', 'courseLevel' => 'Masters', 'categoryId' => '239', 'subCatId' => 'All', 'specialization' => 'All')
    ,
    array('synonym' => 'MMC', 'courseLevel' => 'Masters', 'categoryId' => '244', 'subCatId' => '328', 'specialization' => 'All')
    ,
    array('synonym' => 'BEd', 'courseLevel' => 'Bachelors', 'categoryId' => '244', 'subCatId' => '329', 'specialization' => 'All')
    ,
    array('synonym' => 'MEd', 'courseLevel' => 'Masters', 'categoryId' => '244', 'subCatId' => '329', 'specialization' => 'All')
    ,
//     array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'262' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'263' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'264' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'265' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'266' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'267' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'257' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'269' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'339' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'241' ,'subCatId'=>'275' ,'specialization'=>'All')
// ,
// array('synonym'=>'Meng' ,'courseLevel'=> 'All' , 'categoryId'=>'241' ,'subCatId'=>'277' ,'specialization'=>'All')
// ,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'262' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'263' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'264' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'265' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'266' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'267' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'257' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'269' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'339' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'241' ,'subCatId'=>'275' ,'specialization'=>'All')
,
array('synonym'=>'Beng' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'241' ,'subCatId'=>'277' ,'specialization'=>'All')
,
array('synonym'=>'PMP' ,'courseLevel'=> 'Masters' , 'categoryId'=>'239' ,'subCatId'=>'260' ,'specialization'=>'All')
,
// array('synonym'=>'Mdes' ,'courseLevel'=> 'Masters' , 'categoryId'=>'241' ,'subCatId'=>'276' ,'specialization'=>'All')
// ,
// array('synonym'=>'Mdes' ,'courseLevel'=> 'Masters' , 'categoryId'=>'240' ,'subCatId'=>'339' ,'specialization'=>'All')
// ,
// array('synonym'=>'Mdes' ,'courseLevel'=> 'Masters' , 'categoryId'=>'244' ,'subCatId'=>'323' ,'specialization'=>'All')
// ,
// array('synonym'=>'Mdes' ,'courseLevel'=> 'Masters' , 'categoryId'=>'244' ,'subCatId'=>'325' ,'specialization'=>'All')
// ,
// array('synonym'=>'Mdes' ,'courseLevel'=> 'Masters' , 'categoryId'=>'244' ,'subCatId'=>'331' ,'specialization'=>'All')
// ,
// array('synonym'=>'Mdes' ,'courseLevel'=> 'Masters' , 'categoryId'=>'244' ,'subCatId'=>'334' ,'specialization'=>'All')
// ,
array('synonym'=>'Bdes' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'241' ,'subCatId'=>'276' ,'specialization'=>'All')
,
array('synonym'=>'Bdes' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'240' ,'subCatId'=>'339' ,'specialization'=>'All')
,
array('synonym'=>'Bdes' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'244' ,'subCatId'=>'323' ,'specialization'=>'All')
,
array('synonym'=>'Bdes' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'244' ,'subCatId'=>'325' ,'specialization'=>'All')
,
array('synonym'=>'Bdes' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'244' ,'subCatId'=>'331' ,'specialization'=>'All')
,
array('synonym'=>'Bdes' ,'courseLevel'=> 'Bachelors' , 'categoryId'=>'244' ,'subCatId'=>'334' ,'specialization'=>'All')
,
array('synonym'=>'HR' ,'courseLevel'=> 'All' , 'categoryId'=>'239' ,'subCatId'=>'248' ,'specialization'=>'All')
,
array('synonym'=>'CFQ' ,'courseLevel'=> 'Masters' , 'categoryId'=>'239' ,'subCatId'=>'246' ,'specialization'=>'All')
,
array('synonym'=>'SCM' ,'courseLevel'=> 'All' , 'categoryId'=>'239' ,'subCatId'=>'259' ,'specialization'=>'All')
,
array('synonym'=>'Mphil' ,'courseLevel'=> 'Masters' , 'categoryId'=>'All' ,'subCatId'=>'All' ,'specialization'=>'All')
,
array('synonym'=>'Masscom' ,'courseLevel'=> 'Masters' , 'categoryId'=>'244' ,'subCatId'=>'328' ,'specialization'=>'All')
,
array('synonym'=>'MOM' ,'courseLevel'=> 'Masters' , 'categoryId'=>'239' ,'subCatId'=>'All' ,'specialization'=>'All')
,
array('synonym'=>'EEE' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'263' ,'specialization'=>'All')
,
array('synonym'=>'Bio' ,'courseLevel'=> 'All' , 'categoryId'=>'242' ,'subCatId'=>'287' ,'specialization'=>'All')
,
array('synonym'=>'Bio' ,'courseLevel'=> 'All' , 'categoryId'=>'240' ,'subCatId'=>'267' ,'specialization'=>'All')
,
array('synonym'=>'Biochem' ,'courseLevel'=> 'All' , 'categoryId'=>'242' ,'subCatId'=>'287' ,'specialization'=>'All')
,
array('synonym'=>'MIB' ,'courseLevel'=> 'Masters' , 'categoryId'=>'239' ,'subCatId'=>'252' ,'specialization'=>'All')
,
array('synonym'=>'IT' ,'courseLevel'=> 'Masters' , 'categoryId'=>'241' ,'subCatId'=>'275' ,'specialization'=>'All')
,
array('synonym'=>'CS' ,'courseLevel'=> 'All' , 'categoryId'=>'241' ,'subCatId'=>'277' ,'specialization'=>'All')
,
array('synonym'=>'MPH' ,'courseLevel'=> 'Masters' , 'categoryId'=>'243' ,'subCatId'=>'303' ,'specialization'=>'All')
,
array('synonym'=>'Dip' ,'courseLevel'=> 'Bachelors Diploma' , 'categoryId'=>'All' ,'subCatId'=>'All' ,'specialization'=>'All')
,
array('synonym'=>'Dip' ,'courseLevel'=> 'Masters Diploma' , 'categoryId'=>'All' ,'subCatId'=>'All' ,'specialization'=>'All')
,
array('synonym'=>'Cert' ,'courseLevel'=> 'Bachelors Certificate' , 'categoryId'=>'All' ,'subCatId'=>'All' ,'specialization'=>'All')
,
array('synonym'=>'Cert' ,'courseLevel'=> 'Masters Certificate' , 'categoryId'=>'All' ,'subCatId'=>'All' ,'specialization'=>'All')
,
array('synonym'=>'CSE' ,'courseLevel'=> 'All' , 'categoryId'=>'241' ,'subCatId'=>'275' ,'specialization'=>'All')
,
array('synonym'=>'CSE' ,'courseLevel'=> 'All' , 'categoryId'=>'241' ,'subCatId'=>'277' ,'specialization'=>'All')

    );
