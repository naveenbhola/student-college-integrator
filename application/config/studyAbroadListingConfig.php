<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Define the Constants here with "ENT_SA_"(Entity of study abroad) prefix.
**/
$config['ENT_SA'] = 'ENT_SA';

define("ENT_SA_CMS_PATH" , "/listingPosting/AbroadListingPosting/");

// Display Order of the exams
$config['ENT_SA_EXAM_ORDER'] = array("GMAT", "GRE", "SAT", "IELTS", "TOEFL (IBT)");

//Pass the exam IDS which have exam guide it will display the download link on couselisting page
$config['ENT_SA_EXAM_WITH_GUIDE'] = array(1,2,4,5);

$config['ENT_SA_CURRENCY_SYMBOL_MAPPING'] = array("1" => "Rs.",
                                                  "2" => "$",
                                                  "3" => "$",
                                                  "4" => "$",
                                                  "5" => "$",
                                                  "6" => "£",
                                                  "7" => "€",
                                                  "8" => "$",
                                                  "9" => "€",);

$config['ENT_COURSE_WISE_EXAM_ORDER'] = array(
                                        DESIRED_COURSE_MBA => array( // MBA and Business
                                                'GMAT'  =>  1,
                                                'GRE'   =>  2,
                                                'IELTS' =>  3,
                                                'TOEFL' =>  4,
                                                'PTE'   =>  5,
                                                'MELAB' =>  6,
                                                'CAE'   =>  7,
                                                'CAEL'  =>  8,
                                                'SAT'   =>  9
                                                ),
                                        DESIRED_COURSE_MS => array( // MS and Engineering, Computers, Science (except Bachelors)
                                                'GRE'   =>  1,
                                                'IELTS' =>  2,
                                                'TOEFL' =>  3,
                                                'GMAT'  =>  4,
                                                'PTE'   =>  5,
                                                'MELAB' =>  6,
                                                'CAE'   =>  7,
                                                'CAEL'  =>  8,
                                                'SAT'   =>  9
                                                ),
                                        DESIRED_COURSE_BTECH => array( // BTech and Bachelors of Engineering, Computers, Science
                                                'SAT'   =>  1,
                                                'GRE'   =>  2,
                                                'IELTS' =>  3,
                                                'TOEFL' =>  4,
                                                'GMAT'  =>  5,
                                                'PTE'   =>  6,
                                                'MELAB' =>  7,
                                                'CAE'   =>  8,
                                                'CAEL'  =>  9    
                                                ),
                                        DESIRED_COURSE_MEM => array(
                                            'GRE'   =>  1,
                                            'IELTS' =>  2,
                                            'TOEFL' =>  3,
                                            'GMAT'  =>  4,
                                            'PTE'   =>  5,
                                            'MELAB' =>  6,
                                            'CAE'   =>  7,
                                            'CAEL'  =>  8,
                                            'SAT'   =>  9
                                        ),
                                        DESIRED_COURSE_MPHARM => array(
                                            'GRE'   =>  1,
                                            'IELTS' =>  2,
                                            'TOEFL' =>  3,
                                            'GMAT'  =>  4,
                                            'PTE'   =>  5,
                                            'MELAB' =>  6,
                                            'CAE'   =>  7,
                                            'CAEL'  =>  8,
                                            'SAT'   =>  9
                                        ),
                                        DESIRED_COURSE_MFIN => array(
                                            'GRE'   =>  1,
                                            'IELTS' =>  2,
                                            'TOEFL' =>  3,
                                            'GMAT'  =>  4,
                                            'PTE'   =>  5,
                                            'MELAB' =>  6,
                                            'CAE'   =>  7,
                                            'CAEL'  =>  8,
                                            'SAT'   =>  9
                                        ),
                                        DESIRED_COURSE_MDES => array(
                                            'IELTS' =>  1,
                                            'TOEFL' =>  2,
                                            'SAT'   =>  3,
                                            'GRE'   =>  4,
                                            'GMAT'  =>  5,
                                            'PTE'   =>  6,
                                            'MELAB' =>  7,
                                            'CAE'   =>  8,
                                            'CAEL'  =>  9,
                                        ),
                                        DESIRED_COURSE_MFA => array(
                                            'IELTS' =>  1,
                                            'TOEFL' =>  2,
                                            'SAT'   =>  3,
                                            'GRE'   =>  4,
                                            'GMAT'  =>  5,
                                            'PTE'   =>  6,
                                            'MELAB' =>  7,
                                            'CAE'   =>  8,
                                            'CAEL'  =>  9,
                                        ),
                                        DESIRED_COURSE_MENG => array(
                                            'GRE'   =>  1,
                                            'IELTS' =>  2,
                                            'TOEFL' =>  3,
                                            'GMAT'  =>  4,
                                            'PTE'   =>  5,
                                            'MELAB' =>  6,
                                            'CAE'   =>  7,
                                            'CAEL'  =>  8,
                                            'SAT'   =>  9
                                        ),
                                        DESIRED_COURSE_BSC => array(
                                            'SAT'   =>  1,
                                            'GRE'   =>  2,
                                            'IELTS' =>  3,
                                            'TOEFL' =>  4,
                                            'GMAT'  =>  5,
                                            'PTE'   =>  6,
                                            'MELAB' =>  7,
                                            'CAE'   =>  8,
                                            'CAEL'  =>  9
                                        ),
                                        DESIRED_COURSE_BBA => array(
                                            'SAT'   =>  1,
                                            'GRE'   =>  2,
                                            'IELTS' =>  3,
                                            'TOEFL' =>  4,
                                            'GMAT'  =>  5,
                                            'PTE'   =>  6,
                                            'MELAB' =>  7,
                                            'CAE'   =>  8,
                                            'CAEL'  =>  9
                                        ),
                                        DESIRED_COURSE_MBBS => array(
                                            'IELTS' =>  1,
                                            'TOEFL' =>  2,
                                            'SAT'   =>  3,
                                            'GRE'   =>  4,
                                            'GMAT'  =>  5,
                                            'PTE'   =>  6,
                                            'MELAB' =>  7,
                                            'CAE'   =>  8,
                                            'CAEL'  =>  9,
                                        ),
                                        DESIRED_COURSE_BHM => array(
                                            'SAT'   =>  1,
                                            'GRE'   =>  2,
                                            'IELTS' =>  3,
                                            'TOEFL' =>  4,
                                            'GMAT'  =>  5,
                                            'PTE'   =>  6,
                                            'MELAB' =>  7,
                                            'CAE'   =>  8,
                                            'CAEL'  =>  9
                                        ),
                                        DESIRED_COURSE_MARCH => array(
                                            'GRE'   =>  1,
                                            'IELTS' =>  2,
                                            'TOEFL' =>  3,
                                            'GMAT'  =>  4,
                                            'PTE'   =>  5,
                                            'MELAB' =>  6,
                                            'CAE'   =>  7,
                                            'CAEL'  =>  8,
                                            'SAT'   =>  9
                                        ),
                                        DESIRED_COURSE_MIS => array(
                                            'GRE'   =>  1,
                                            'IELTS' =>  2,
                                            'TOEFL' =>  3,
                                            'GMAT'  =>  4,
                                            'PTE'   =>  5,
                                            'MELAB' =>  6,
                                            'CAE'   =>  7,
                                            'CAEL'  =>  8,
                                            'SAT'   =>  9
                                        ),
                                        DESIRED_COURSE_MIM => array(
                                            'GRE'   =>  1,
                                            'IELTS' =>  2,
                                            'TOEFL' =>  3,
                                            'GMAT'  =>  4,
                                            'PTE'   =>  5,
                                            'MELAB' =>  6,
                                            'CAE'   =>  7,
                                            'CAEL'  =>  8,
                                            'SAT'   =>  9
                                        ),
                                        DESIRED_COURSE_MASC => array(
                                            'GRE'   =>  1,
                                            'IELTS' =>  2,
                                            'TOEFL' =>  3,
                                            'GMAT'  =>  4,
                                            'PTE'   =>  5,
                                            'MELAB' =>  6,
                                            'CAE'   =>  7,
                                            'CAEL'  =>  8,
                                            'SAT'   =>  9
                                        ),
                                        DESIRED_COURSE_MA => array(
                                            'GRE'   =>  1,
                                            'IELTS' =>  2,
                                            'TOEFL' =>  3,
                                            'GMAT'  =>  4,
                                            'PTE'   =>  5,
                                            'MELAB' =>  6,
                                            'CAE'   =>  7,
                                            'CAEL'  =>  8,
                                            'SAT'   =>  9
                                        ),
                                    'OTHERS' => array( // Rest all courses and categories
                                                'IELTS' =>  1,
                                                'TOEFL' =>  2,
                                                'SAT'   =>  3,
                                                'GRE'   =>  4,
                                                'GMAT'  =>  5,
                                                'PTE'   =>  6,
                                                'MELAB' =>  7,
                                                'CAE'   =>  8,
                                                'CAEL'  =>  9,
                                                )
                                            );
$config['ENT_COURSE_AND_COUNTRY_WISE_EXAM_ORDER'] = array(
                                                //usa
                                                3 => array(
                                                    //MBA
                                                    DESIRED_COURSE_MBA => array(
                                                        'GMAT' => 5,
                                                        'TOEFL'=> 1
                                                    ),
                                                    //MS
                                                    DESIRED_COURSE_MS => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    //BEBTECH
                                                    DESIRED_COURSE_BTECH => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MEM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MPHARM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFIN =>array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MDES => array(
                                                        'TOEFL' => 1,
                                                        'IELTS' => 2
                                                    ),
                                                    DESIRED_COURSE_MFA => array(
                                                        'TOEFL' => 1,
                                                        'IELTS' => 2
                                                    ),
                                                    DESIRED_COURSE_MENG => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BBA => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MBBS => array(
                                                        'TOEFL' => 1,
                                                        'IELTS' => 2
                                                    ),
                                                    DESIRED_COURSE_BHM => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIS => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BSC => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MARCH => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MASC => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ), DESIRED_COURSE_MA => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    )

                                                ),
                                                //Canada
                                                8 => array(
                                                    DESIRED_COURSE_MBA => array(
                                                        'IELTS' =>2,
                                                        'GMAT' =>5
                                                    ),
                                                    DESIRED_COURSE_MS => array(
                                                        'GRE' => 4,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_BTECH => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MEM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MPHARM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFIN =>array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MDES => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFA => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MENG => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BBA => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MBBS => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BHM => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIS => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BSC => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MARCH => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MASC => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ), DESIRED_COURSE_MA => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    )
                                                ),
                                                //Australia
                                                5 => array(
                                                    DESIRED_COURSE_MBA => array(
                                                        'IELTS' =>2,
                                                        'GMAT' =>5
                                                    ),
                                                    DESIRED_COURSE_MS => array(
                                                        'IELTS' =>2,
                                                        'PTE' => 3
                                                    ),
                                                    DESIRED_COURSE_BTECH => array(
                                                        'IELTS' =>2,
                                                        'PTE' =>3
                                                    ),
                                                    DESIRED_COURSE_MEM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MPHARM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFIN =>array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MDES => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFA => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MENG => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BBA => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MBBS => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BHM => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIS => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BSC => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MARCH => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MASC => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ), DESIRED_COURSE_MA => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    )
                                                ),
                                                //UK
                                                4 => array(
                                                    DESIRED_COURSE_MBA => array(
                                                        'IELTS' =>2,
                                                        'GMAT' =>5
                                                    ),
                                                    DESIRED_COURSE_MS => array(
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_BTECH => array(
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MEM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MPHARM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFIN =>array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MDES => array(
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_MFA => array(
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_MENG => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BBA => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MBBS => array(
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_BHM => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIS => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BSC => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MARCH => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MASC => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ), DESIRED_COURSE_MA => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    )
                                                ),
                                                //Ireland
                                                21 => array(
                                                    DESIRED_COURSE_MBA => array(
                                                        'IELTS' =>2,
                                                        'GMAT' =>5
                                                    ),
                                                    DESIRED_COURSE_MS => array(
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_BTECH => array(
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_MEM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MPHARM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFIN =>array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MDES => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFA => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MENG => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BBA => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MBBS => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BHM => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIS => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BSC => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MARCH => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MASC => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ), DESIRED_COURSE_MA => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    )
                                                ),
                                                //Singapore
                                                6 => array(
                                                    DESIRED_COURSE_MBA => array(
                                                        'GMAT'=>5,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MS => array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_BTECH => array(
                                                        'SAT' =>6,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MEM => array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MPHARM => array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MFIN =>array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MDES => array(
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MFA => array(
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MENG => array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_BBA => array(
                                                        'SAT' =>6,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MBBS => array(
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_BHM => array(
                                                        'SAT' =>6,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MIS => array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MIM => array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_BSC => array(
                                                        'SAT' =>6,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MARCH => array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MASC => array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    ), DESIRED_COURSE_MA => array(
                                                        'GRE' =>4,
                                                        'IELTS'=>2
                                                    )
                                                ),
                                                //NEW Zealand 
                                                7 => array(
                                                    DESIRED_COURSE_MBA => array(
                                                        'GMAT' =>5,
                                                        'IELTS'=>2
                                                    ),
                                                    DESIRED_COURSE_MS => array(
                                                       'IELTS' =>2,
                                                        'PTE' =>3
                                                    ),
                                                    DESIRED_COURSE_BTECH => array(
                                                        'IELTS' =>2,
                                                        'PTE' =>3
                                                    ),
                                                    DESIRED_COURSE_MEM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MPHARM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFIN =>array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MDES => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFA => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MENG => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BBA => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MBBS => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BHM => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIS => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BSC => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MARCH => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MASC => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ), DESIRED_COURSE_MA => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    )
                                                ),
                                                'europeexceptuk'=>array(
                                                    DESIRED_COURSE_MBA => array(
                                                        'GMAT' =>5,
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_MS => array(
                                                        'GRE' =>4,
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_BTECH => array(
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_MEM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MPHARM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFIN =>array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MDES => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFA => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MENG => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BBA => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MBBS => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BHM => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIS => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BSC => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MARCH => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MASC => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ), DESIRED_COURSE_MA => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    )
                                                ),
                                                'others'=>array(
                                                    DESIRED_COURSE_MBA => array(
                                                        'GMAT' =>5,
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_MS => array(
                                                        'GRE' =>4,
                                                        'IELTS' =>2
                                                    ),
                                                    DESIRED_COURSE_BTECH => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MEM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MPHARM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFIN =>array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MDES => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MFA => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MENG => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BBA => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MBBS => array(
                                                        'IELTS' =>2,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BHM => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIS => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MIM => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_BSC => array(
                                                        'SAT' => 6,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MARCH => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ),
                                                    DESIRED_COURSE_MASC => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    ), DESIRED_COURSE_MA => array(
                                                        'GRE' => 4,
                                                        'TOEFL' =>1
                                                    )
                                                )
);
