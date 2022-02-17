<?php

class Upcoming_Events_Model extends MY_Model
{
    function __construct()
    {
        parent::__construct('Event');
    }
    
    function getUpcomingEvents($category_id,$subcategory_id=0)
    {
	$dbHandle = $this->getReadHandle();
        $category_for_filter = intval($subcategory_id)>0?$subcategory_id:$category_id;
        
        $sql = "SELECT event.event_id,event.event_title,
                       event_date.start_date,event_date.end_date,
                       venue.venue_name,venue.Address_Line1 as venue_address,city.city_name,country.name as country_name
                FROM event
                INNER JOIN eventCategoryTable event_cat ON (event_cat.eventId = event.event_id AND event_cat.boardId = ?)
                LEFT JOIN event_date ON event_date.event_id = event.event_id
                LEFT JOIN event_venue as venue ON venue.venue_id = event.venue_id
                LEFT JOIN countryCityTable city ON city.city_id = venue.city
                LEFT JOIN countryTable country ON country.countryId = venue.country
                WHERE event_date.end_date >= '".date('Y-m-d')."'
                AND venue.country = '2'
                AND event.fromOthers != '1'
                AND (event.status_id IS NULL OR event.status_id = 2)
                ORDER BY event_date.start_date
                LIMIT 5";
                
        $query = $dbHandle->query($sql, array($category_for_filter));
        
        $rows = $query->result();
        
        return $rows;
    }
}
