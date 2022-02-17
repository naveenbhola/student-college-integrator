<?php
/**
 * File for Value source for Tenth marks field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for X marks field
 */ 
class Tenthmarks extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        return array(
           'CBSE' => array('4 - 4.9'=>'4 - 4.9', '5 - 5.9'=>'5 - 5.9', '6 - 6.9'=>'6 - 6.9', '7 - 7.9'=>'7 - 7.9', '8 - 8.9'=>'8 - 8.9', '9 - 10.0'=>'9 - 10.0'),
           'ICSE' => array('50' => '< than 50%', '60' => '50% to 60%', '70' => '60% to 70%', '80'=>'70% to 80%', '90' =>'80% to 90%',  '100'=>'90% or above'),
           'IGCSE'=> array('A*'=>'A*','A'=>'A','B'=>'B', 'C'=>'C', 'D'=>'D', 'E'=>'E','F'=>'F', 'G'=>'G'),
           'IBMYP'=> array('28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56'),
           'NIOS' => array('50' => '< than 50%', '60' => '50% to 60%', '70' => '60% to 70%', '80'=>'70% to 80%', '90' =>'80% to 90%',  '100'=>'90% or above')
        );
    }
}