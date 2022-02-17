<div id='upcoming_events_widget'>
    <strong class="Fnt14">Upcoming Events</strong>
    <div class='ln'></div>
    <ul>
    <?php
    foreach($events as $event)
    {
        $event_start = $event['start_date'];
        $event_end = $event['end_date'];
        
        $event_start_date = date("j",strtotime($event_start));
        $event_start_month = date("M",strtotime($event_start));
        $event_start_display = date("j F Y",strtotime($event_start));
        
        $event_end_display = date("j F Y",strtotime($event_end));
    ?>
    <li>
    <div id='upcoming_events_widget_calendar'>
        <div id='upcoming_events_widget_calendar_month'>
            <?php echo $event_start_month; ?>
        </div>
        <div id='upcoming_events_widget_calendar_date'>
            <?php echo $event_start_date; ?>
        </div>
    </div>
    
    <div id='upcoming_events_widget_detail'>
        <a href='<?php echo getSeoUrl($event['event_id'],'event',$event['event_title']); ?>'><?php echo $event['event_title']; ?></a>
        <div style='margin-top:3px;'>
        <b>Date:</b> <?php echo $event_start_display; ?> - <?php echo $event_end_display; ?>
        <br />
        <b>Venue:</b> <?php echo !empty($event['venue_name'])?$event['venue_name']:$event['venue_address']; ?> (<?php echo $event['city_name']; ?> - <?php echo $event['country_name']; ?>)
    </div>
    </div>
    
    <div style='clear:both;'></div>
    </li>
    <?php
    }
    ?>
    </ul>
</div>