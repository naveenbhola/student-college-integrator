<?php

class upcomingEvents extends MX_Controller
{
    function getUpcomingEvents($category_id,$subcategory_id=0)
    {
        /*
         Make xml-rpc call at main Events front-end library
         to retrieve upcoming events
        */
        
        $this->load->library('Event_cal_client');
        $events = $this->event_cal_client->getUpcomingEvents($category_id,$subcategory_id);
        
        if(count($events))
        {
            $data['events'] = $events;
            echo $this->load->view('upcomingEvents/upcomingEvents',$data,TRUE);
        }
    }
}