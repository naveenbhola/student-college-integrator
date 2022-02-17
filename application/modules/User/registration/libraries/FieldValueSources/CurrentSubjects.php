<?php
/**
 * File for Value source for gender field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for gender field
 */ 
class CurrentSubjects extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
		$vals = array(
           "Accountancy",
            "Agriculture",
            "Anthropology",
            "Arts",
            "Biology",
            "Biotechnology",
            "Business Studies/Management",
            "Chemistry",
            "Commerce",
            "Computer Science",
            "Design and Technology",
            "Design and Textiles",
            "Earth Sciences",
			"Economics",
            "Engineering Graphics",
            "English","Entrepreneurship",
            "Environmental Studies",
            "Fashion Designing",
            "Film",
            "Fine Arts",
            "Food Studies",
            "Foreign Languages",
            "French",
            "General Studies",
            "Geography","German",
            "Health Sciences",
            "Hindi",
            "History&sbquo; Civics & Geography",
            "Home Science",
            "Information and Communication Technology",
            "Information Practices",
            "Law",
            "Marine science",
            "Math studies",
            "Mathematics",
            "Multimedia/Technology",
            "Other subjects",
            "Philosophy",
            "Physical Education",
            "Physics",
            "Political Science",
            "Psychology",
            "Science",
            "Social Science",
            "Sociology",
            "Technical Drawing",
            "Travel and Tourism"
        );
		natcasesort($vals);
        return $vals;


    }
}