<?php
/*
 * Define your validations here
 */ 

$config['validations'] = array(
                                
    'addCountry' => array(
                            'country' => array(
                                                'label' => 'Country name',
                                                'rules' => 'Required|AlphaNumeric|Length:3,50|UniqueCountryName'
                                            ),
                            'region'  => array(
                                                'label' => 'Region',
                                                'rules' => 'Required'
                                            ),
                            'capital' => array(
                                                'label' => 'Capital city name',
                                                'rules' => 'Required|AlphaNumeric|Length:3,50|UniqueCityName'
                                            )
                        ),
    'addCity'    => array(
                            'country' => array(
                                                'label' => 'Country',
                                                'rules' => 'Required'
                                            ),
                            'city[1-9]'  => array(
                                                'label' => 'City name',
                                                'rules' => 'Required|AlphaNumeric|Length:3,50|UniqueCityName'
                                            )
                        ),
    'addLocality' => array(
                            'Locality' => array(
                                                'label' => 'Locality',
                                                'rules' => 'Required|Length:3,50|UniqueLocalityName'
                                            ),
                            'Zone'  => array(
                                                'label' => 'Zone',
                                                'rules' => 'Required'
                                            ),
                            'City' => array(
                                                'label' => 'City',
                                                'rules' => 'Required'
                                            )
                        ),
    'addZone'    => array(
                            
                            'zone'  => array(
                                                'label' => 'zone',
                                                'rules' => 'Required|Length:3,50|UniqueZoneName'
                                            ),
                            'City' => array(
                                                'label' => 'City',
                                                'rules' => 'Required'
                                            )
    )
    
    
);