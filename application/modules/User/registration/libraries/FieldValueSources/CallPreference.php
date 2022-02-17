<?php
/**
 * File for alue source for call preference field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for call preference field
 */ 
class CallPreference extends AbstractValueSource
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
                        1 => 'Yes, Call anytime',
						2 => 'Yes, Call me in the morning',
						3 => 'Yes, Call me in the evening',
						0 => 'No'
		);
    }
}