<?php

$examList = array(

    /************************
     *** MANAGEMENT EXAMS ***
     ************************/
    
    'CAT' => array(
        'name' => 'CAT',
        'scoreType' => 'Percentile',
        'minScore' => 0,
        'maxScore' => 100,
        'hasPassingYear' => 'no'
    ),
    'MAT' => array(
        'name' => 'MAT',
        'scoreType' => 'Percentile',
        'minScore' => 0,
        'maxScore' => 100,
        'hasPassingYear' => 'no'
    ),
    'XAT' => array(
        'name' => 'XAT',
        'scoreType' => 'Percentile',
        'minScore' => 0,
        'maxScore' => 100,
        'hasPassingYear' => 'no'
    ),
    'CMAT' => array(
        'name' => 'CMAT',
        'scoreType' => 'Score',
        'minScore' => 0,
        'maxScore' => 400,
        'hasPassingYear' => 'no'
    ),
    'SNAP' => array(
        'name' => 'SNAP',
        'scoreType' => 'Percentile',
        'minScore' => 0,
        'maxScore' => 100,
        'hasPassingYear' => 'no'
    ),
    'NMAT' => array(
        'name' => 'NMAT',
        'scoreType' => 'Percentile',
        'minScore' => 0,
        'maxScore' => 100,
        'hasPassingYear' => 'no'
    ),
    'IIFT' => array(
        'name' => 'IIFT',
    ),
    'IRMA' => array(
        'name' => 'IRMA',
        'scoreType' => 'Percentile',
        'minScore' => 0,
        'maxScore' => 100,
        'hasPassingYear' => 'no'
    ),
    'IBSAT' => array(
        'name' => 'IBSAT',
        'scoreType' => 'Percentile',
        'minScore' => 0,
        'maxScore' => 100,
        'hasPassingYear' => 'no'
    ),
    'GMAT' => array(
        'name' => 'GMAT',
        'scoreType' => 'Score',
        'minScore' => 0,
        'maxScore' => 800,
        'hasPassingYear' => 'no'
    ),
    'KMAT' => array(
        'name' => 'KMAT',
        'scoreType' => 'Percentile',
        'minScore' => 0,
        'maxScore' => 100,
        'hasPassingYear' => 'no'
    ),
    'MICAT' => array(
        'name' => 'MICAT'
    ),
    
    /*************************
     *** ENGINEERING EXAMS ***
     *************************/
    
    'JEE_MAINS' => array(
        'name' => 'JEE Mains',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no'
    ),
    'JEE_ADVANCE' => array(
        'name' => 'JEE Advance',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no'
    ),
    'BITSAT' => array(
        'name' => 'BITSAT',
        'scoreType' => 'Score',
        'minScore' => 0,
        'maxScore' => 450,
        'hasPassingYear' => 'no'
    ),
    'CGPET' => array(
        'name' => 'CGPET',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'Chattisgarh'
    ),
    'COMED_K' => array(
        'name' => 'COMED-K',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'Karnataka'
    ),
    'EAMCET' => array(
        'name' => 'EAMCET',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'Andhra Pradesh'
    ),
    'KCET' => array(
        'name' => 'KCET',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'Karnataka'
    ),
    'KEAM' => array(
        'name' => 'KEAM',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'Kerala'
    ),
    'MANIPAL_ENAT' => array(
        'name' => 'MANIPAL - ENAT',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no'
    ),
    'MPPEPT' => array(
        'name' => 'MPPEPT',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'Madhya Pradesh'
    ),
    'MT_CET' => array(
        'name' => 'MT-CET',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'Maharashtra'
    ),
    'SRMJEEE' => array(
        'name' => 'SRMJEEE',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no'
    ),
    'TNEA' => array(
        'name' => 'TNEA',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'Tamil Nadu'
    ),
    'UPSEE' => array(
        'name' => 'UPSEE',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'Uttar Pradesh'
    ),
    'WBJEE' => array(
        'name' => 'WBJEE',
        'scoreType' => 'Rank',
        'hasPassingYear' => 'no',
        'state' => 'West Bengal'
    ),
    'NOEXAM' => array(
        'name' => 'Haven\'t given any exam'
    ),
    'Other' => array(
        'name' => 'Others',
        'scoreType' => 'Score',
        'minScore' => 0,
        'maxScore' => 99999,
        'nameBox' => 'yes'
    )
);

$courseExamMapping = array(
    2 => array(
            'featured' => array('CAT','MAT','XAT','CMAT'),
            'others'   => array('SNAP','NMAT','IIFT','IRMA','IBSAT','GMAT','KMAT','MICAT','Other','NOEXAM')
        ),
    /**
     * B.E./B.Tech.
     */
    52 => array(
            'featured' => array('JEE_MAINS','JEE_ADVANCE'),
            'others'   => array('BITSAT','CGPET','COMED_K','EAMCET','KCET','KEAM','MANIPAL_ENAT','MPPEPT','MT_CET','SRMJEEE','TNEA','UPSEE','WBJEE','Other','NOEXAM')
        ),
);

