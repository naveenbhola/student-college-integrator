<?php

$config = array();

# change after insertion in production

$config['fieldMapping'] = array(
    	
    	'pattern' 		=> array(
    						20801 => '#1# ^#2# ^#3#',
    						20805 =>'#18# ^#19# ^#20#',
    						20817=>'#25# ^#26# ^#27#'
    					),

    	'customized'	=> array(
    						20803 =>array(
                                'field_type'=>'oaf_course',
                                'function_name'=>'getCourseNameForForm'
                            ),
                            20819 =>array(
                                'field_type'=>'oaf_gender',
                                'function_name'=>'getDetailsForCustomizedLayer',
                                'fieldId'=>4
                            ),
                            21251 =>array(
                                'field_type'=>'oaf_paymentmode',
                                'function_name'=>'getDetailsForPaymentMode',
                                'column_name'=>'log',
                                'key_name'=>'payment_mode'
                            )
    					),
 		
 		'table'         => array(
			 				20807=>'OF_UserForms',
			 				20809=>array('OF_Payments','mode'),
			 				20811=>array('OF_Payments','orderId'),
			 				20813=>array('OF_Payments','bankName'),
                            20815=>array('OF_Payments','amount'),
                            21247=>array('OF_Payments','date'),
			 				21249=>array('OF_PaymentLog','log','bank_ref_no'),                            
                            21=>'country',
                            28=>'country',
                            23=>'city',
                            30=>'city',
                            22=>'state',
                            29=>'state'
 						)
     );

$config['paymentModes']  = array('Unified Payments','Credit Card','Debit Card','Wallet','Net Banking');
?>
