import React from 'react';
import './../assets/Contact.css';

class ExamContactInfo extends React.Component{
	constructor()
	{
		super();
	}

	render()
	{
		return (
			<React.Fragment>
				<section>
        <div className="_container">
          <h2 className="tbSec2">CAT 2018 Contact Information</h2>
        <div className="_subcontainer">
          <div className="contact-sctn">
            <div className="address-wrapper">
              <p className="addrs-titl">Website</p>
              <p className="address-data"><a href="">https://iimcat.ac.in</a></p>
            </div>
            <div className="address-wrapper">
              <p className="addrs-titl">Address</p>
              <div>
                <p className="address-data">CAT Centre 2018 C/o Admissions Office Indian Institute of Management Bangalore Bannerghatta Main Rd Bilekhalli, Bangaluru-560076, Karnataka, India</p>
              </div>
            </div>
            <div className="address-wrapper">
              <p className="addrs-titl">Phone</p>
              <p className="address-data"><strong>Helpdesk:</strong> <a href="tel:+9118002663549"> 18002663549</a></p>
            </div>
          </div>
        </div>
       </div>
      </section>
			</React.Fragment>
		)
	}
}
export default ExamContactInfo;
