<?php
/*
   Copyright 2015 Info Edge India Ltd
   $Author: Ankur Gupta
   $Id: AnACommonLib.php
*/
        $config = array('streamMapping' => array(
                            'Accounting & Commerce' => array(
                                                                    'id' => '4',
                                                                    'name' => 'Banking & Finance'
                                    ),
                            'Animation & Multimedia' => array(
                                                                    'id' => '12',
                                                                    'name' => 'Animation, Visual Effects, Gaming & Comics (AVGC)'
                                    ),
                            'Architecture' => array(
                                                                    'id' => '2',
                                                                    'name' => 'Science & Engineering'
                                    ),
                            'Arts & Humanities' => array(
                                                                    'id' => '9',
                                                                    'name' => 'Arts, Law, Languages and Teaching'
                                    ),
                            'Aviation' => array(
                                                                    'id' => '6',
                                                                    'name' => 'Hospitality, Aviation & Tourism'
                                    ),
                            'Banking ,Finance & Insurance' => array(
                                                                    'id' => '4',
                                                                    'name' => 'Banking & Finance'
                                    ),
                            'Beauty & Fitness' => array(
                                                                    'id' => '5',
                                                                    'name' => 'Medicine, Beauty & Health Care'
                                    ),
                            'Business Management Studies' => array(
                                                                    'id' => '3',
                                                                    'name' => 'Management'
                                    ),
                            'Computers & IT (Non-Engg)' => array(
                                                                    'id' => '10',
                                                                    'name' => 'Information Technology'
                                    ),
                            'Design' => array(
                                                                    'id' => '13',
                                                                    'name' => 'Design'
                                    ),
                            'Engineering' => array(
                                                                    'id' => '2',
                                                                    'name' => 'Science & Engineering'
                                    ),
                            'Fine Arts & Performing Arts' => array(
                                                                    'id' => '9',
                                                                    'name' => 'Arts, Law, Languages and Teaching'
                                    ),
                            'Hotel Management' => array(
                                                                    'id' => '6',
                                                                    'name' => 'Hospitality, Aviation & Tourism'
                                    ),
                            'Law' => array(
                                                                    'id' => '9',
                                                                    'name' => 'Arts, Law, Languages and Teaching'
                                    ),
                            'Mass Communication & Media' => array(
                                                                    'id' => '7',
                                                                    'name' => 'Media, Films & Mass Communication'
                                    ),
                            'Medicine' => array(
                                                                    'id' => '5',
                                                                    'name' => 'Medicine, Beauty & Health Care'
                                    ),
                            'Nursing & Health sciences' => array(
                                                                    'id' => '5',
                                                                    'name' => 'Medicine, Beauty & Health Care'
                                    ),
                            'Retail' => array(
                                                                    'id' => '11',
                                                                    'name' => 'Retail'
                                    ),
                            'Science' => array(
                                                                    'id' => '2',
                                                                    'name' => 'Science & Engineering'
                                    ),
                            'Teaching & Education' => array(
                                                                    'id' => '9',
                                                                    'name' => 'Arts, Law, Languages and Teaching'
                                    ),
                            'Tourism & Travel' => array(
                                                                    'id' => '6',
                                                                    'name' => 'Hospitality, Aviation & Tourism'
                                    ),
                    ),
                
        
       'overflowTabs' => array('question'=>array(
        
                                                'owner' => array('0'=>array('id'=>'101','label'=>'Edit Tags'),
                                                                '1'=>array('id'=>'102','label'=>'Edit Question'),
                                                                '2'=>array('id'=>'103','label'=>'Close'),
                                                               '3'=>array('id'=>'104','label'=>'Delete') 
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'101','label'=>'Edit Tags'),
                                                                '1'=>array('id'=>'102','label'=>'Edit Question'),
                                                                '2'=>array('id'=>'103','label'=>'Close'),
                                                               '3'=>array('id'=>'104','label'=>'Delete'),
                                                               '4'=>array('id'=>'105','label'=>'Report Abuse'),
                                                               '5'=>array('id'=>'106','label'=>'Answer Later')
                                                            
                                                            ),
                                                
                                                'otherUser' => array('0'=>array('id'=>'105','label'=>'Report Abuse'),
                                                                      '1'=>array('id'=>'106','label'=>'Answer Later')
                                 
                                                        )
                                                ),
                                    'answer'=>array(
        
                                                'owner' => array('0'=>array('id'=>'107','label'=>'Edit'),
                                                                 '1'=>array('id'=>'108','label'=>'Delete')
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'107','label'=>'Edit'),
                                                                     '1'=>array('id'=>'108','label'=>'Delete'),
                                                                     '2'=>array('id'=>'109','label'=>'Report Abuse')
                                                            
                                                            ),
                                                
                                                'otherUser' => array('0'=>array('id'=>'109','label'=>'Report Abuse')
                                 
                                                        )
                                                ),
                                    
                                    'comment'=>array(
        
                                                'owner' => array('0'=>array('id'=>'110','label'=>'Edit'),
                                                                 '1'=>array('id'=>'111','label'=>'Delete')
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'110','label'=>'Edit'),
                                                                     '1'=>array('id'=>'111','label'=>'Delete'),
                                                                     '2'=>array('id'=>'112','label'=>'Report Abuse')
                                                            
                                                            ),
                                                
                                                'otherUser' => array('0'=>array('id'=>'112','label'=>'Report Abuse')
                                 
                                                        )
                                                ),
                                    
                                    'discussion'=>array(
        
                                                'owner' => array('0'=>array('id'=>'113','label'=>'Edit Tags'),
                                                                '1'=>array('id'=>'114','label'=>'Edit Discussion'),
                                                               '2'=>array('id'=>'115','label'=>'Delete'),
                                                               '3'=>array('id'=>'117','label'=>'Comment Later')
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'113','label'=>'Edit Tags'),
                                                                '1'=>array('id'=>'114','label'=>'Edit Discussion'),
                                                               '2'=>array('id'=>'115','label'=>'Delete'),
                                                               '3'=>array('id'=>'116','label'=>'Report Abuse'),
                                                               '4'=>array('id'=>'117','label'=>'Comment Later'),
                                                            
                                                            ),
                                                
                                                'otherUser' => array('0'=>array('id'=>'116','label'=>'Report Abuse'),
                                                                     '1'=>array('id'=>'117','label'=>'Comment Later')
                                 
                                                        )
                                                ),
                                    
                                    'reply'=>array(
        
                                                'owner' => array('0'=>array('id'=>'118','label'=>'Edit'),
                                                                 '1'=>array('id'=>'119','label'=>'Delete')
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'118','label'=>'Edit'),
                                                                     '1'=>array('id'=>'119','label'=>'Delete'),
                                                                     '2'=>array('id'=>'120','label'=>'Report Abuse')
                                                            
                                                            ),
                                                
                                                'otherUser' => array('0'=>array('id'=>'120','label'=>'Report Abuse')
                                 
                                                        )
                                                ),
                              ),
       
       'closedLinkedOverflowTabs' => array('question'=>array(
        
                                                'owner' => array('0'=>array('id'=>'104','label'=>'Delete'),
                                                                 '1'=>array('id'=>'121','label'=>'Share')
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'104','label'=>'Delete'),
                                                                     '1'=>array('id'=>'121','label'=>'Share'),
                                                                     '2'=>array('id'=>'105','label'=>'Report Abuse'),
                                                            
                                                            ),
                                                
                                                'otherUser' => array(
                                                                      '0'=>array('id'=>'121','label'=>'Share'),
                                                                      '1'=>array('id'=>'105','label'=>'Report Abuse'),
                                 
                                                        )
                                                ),
                                    'answer'=>array(
        
                                                'owner' => array('0'=>array('id'=>'108','label'=>'Delete')
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'108','label'=>'Delete'),
                                                                     '1'=>array('id'=>'109','label'=>'Report Abuse')
                                                            
                                                            ),
                                                
                                                'otherUser' => array('0'=>array('id'=>'109','label'=>'Report Abuse')
                                 
                                                        )
                                                ),
                                    
                                    'comment'=>array(
        
                                                'owner' => array('0'=>array('id'=>'111','label'=>'Delete'),
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'111','label'=>'Delete'),
                                                                     '1'=>array('id'=>'112','label'=>'Report Abuse')
                                                            
                                                            ),
                                                
                                                'otherUser' => array('0'=>array('id'=>'112','label'=>'Report Abuse')
                                 
                                                        )
                                                ),
                                    
                                    'discussion'=>array(
        
                                                'owner' => array(
                                                               '0'=>array('id'=>'115','label'=>'Delete'),
                                                               '1'=>array('id'=>'122','label'=>'Share')
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'115','label'=>'Delete'),
                                                                     '1'=>array('id'=>'122','label'=>'Share'),
                                                                     '2'=>array('id'=>'116','label'=>'Report Abuse')
                                                            ),
                                                
                                                'otherUser' => array(
                                                                     '0'=>array('id'=>'122','label'=>'Share'),
                                                                     '1'=>array('id'=>'116','label'=>'Report Abuse'),
                                 
                                                        )
                                                ),
                                    
                                    'reply'=>array(
        
                                                'owner' => array('0'=>array('id'=>'119','label'=>'Delete')
                                                            ),
                                                
                                                'moderator' => array('0'=>array('id'=>'119','label'=>'Delete'),
                                                                     '1'=>array('id'=>'120','label'=>'Report Abuse')
                                                            
                                                            ),
                                                
                                                'otherUser' => array('0'=>array('id'=>'120','label'=>'Report Abuse')
                                 
                                                        )
                                                ),
                       )
       
                                      
            );

?>

