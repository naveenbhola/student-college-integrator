<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 *
 * will add api later, that will check URL Tracking ...
 * will add api later, that check user agent to detect mobile device...
 * will add api later, that provide info related to user group & aval. rights ...
 * We can add more Controller LIKE ...
 * class SUMS_Controller extends MY_Controller
 * class BLOG_Controller extends MY_Controller
 * class ANA_Controller extends MY_Controller
 * class SA_Controller extends MY_Controller
 * class LDB_Controller extends MY_Controller
 * more .. and more ... each class represent as a parent class for that module
 * or group of modules.
 *
 */

class Controller extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __destruct()
    {

    }
}

// END Controller class

/* End of file Controller.php */
/* Location: ./application/core/Controller.php */