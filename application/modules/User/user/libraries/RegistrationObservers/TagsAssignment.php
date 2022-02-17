<?php
/**
 * Registration observer to assign tags to user
 * @author : Romil Goel
 */ 
namespace user\libraries\RegistrationObservers;

/**
 * Registration observer to assign tags to user
 */ 
class TagsAssignment extends AbstractObserver
{
	/**
	 * Constructor
	 */
    function __construct()
    {
        parent::__construct();
    }
    
	/**
	 * Function to assign tags to user
	 *
	 * @param object $user \user\Entities\User
	 */ 
    public function update(\user\Entities\User $user, $data)
    {
		$userId = $user->getId();

		/**
	     * MAB-1386 : Tag assignment on the basis of desired course chosen
	     */
	    
	    if($data['desiredCourse']){
			$ci = & get_instance();
	    	$usermodel = $ci->load->model('usermodel');

	    	$entityType = NULL;
	    	if($data['isTestPrep'] == 'yes'){
	            $entityType = 'testprep';              
	        }
	    	$usermodel->updateUserTags($userId, $data['desiredCourse'], $entityType);
		}

    }
}
