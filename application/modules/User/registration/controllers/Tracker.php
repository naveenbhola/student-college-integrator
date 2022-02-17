<?php
/**
 * File for showing Tracker Image
 */

/**
 * Tracker Image Class
 */ 
class Tracker extends MX_Controller
{
        /**
         * Function to render the contents of the image
         */
        public function tracker()
        {
                header('Content-Type: image/gif');
                echo file_get_contents('public/images/blankImg.gif');
        }
        
        /**
         * Function to render the contents of the image
         * @param integer $id
         */
        public function newTracker($id)
        {
                header('Content-Type: image/gif');
                echo file_get_contents('public/images/blankImg.gif');
        }
        
        /**
         * Function to set Content-Type Header
         */
        public function headTracker()
        {
                header("Content-type: text/css");
        }

}
