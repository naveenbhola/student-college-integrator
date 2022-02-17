import React, { Component } from 'react';

const JsonLd = ({ data }) => {
	let jsonArray = [];
	for (let i in data){
		let schema = {
			"@context": "http://schema.org/",
		    "@type": "Event",
  			"name": data[i].event_name,
  			"startDate": data[i].start_date,
  			"endDate": data[i].end_date,
  			"location": {
		    "@type": "Place",
		    "address": {
		      "@type": "PostalAddress",
		      "addressLocality": "India",
		      "addressRegion": "India"
		    }
		  }
		}
		jsonArray.push(<script
    	type="application/ld+json"
    	dangerouslySetInnerHTML={{ __html: JSON.stringify(schema) }}
  	/>);	
	}
  	return jsonArray;
  }

const EventSchema = (props) => {
	return(
		<JsonLd data={props.data.dates.futureDates} />
	)
}
export default EventSchema;
