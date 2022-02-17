<?php 
$headerComponents = array(
        'js'                  => array('shikshaCommon'),
        'jsFooter'            => array('lazyload'),
        'css'                 => array('management'),
        'cssFooter'           => array('registration'),
        'product'             => "managementPage",
        'title'               => 'Management Page',
        'canonicalURL'        => trim(SHIKSHA_HOME, "/") . "/team",
        'lazyLoadJsFiles'     => array('multipleapply', 'processForm', 'userRegistration')
    );
$this->load->view('common/header',$headerComponents);
?>
<div class="mang-wrapper">
 <div class="container">
    <div class="n-row">
        <section class="mangmnt">
          <h2>Our Management Team</h2>
          <div class="mngmnt-dtls">
             <div class="mngmnt-dtls-col">
               <div class="mngmnt-image">
                  <img src="/public/images/VivekJain.jpg" width='124' height='124' />
               </div>
             <div class="mngmnt-prfl">
                
                <div class="lead-name">
                  <h3 class="name">Vivek Jain</h3>
                  <p class="positn">Chief Business Officer- Shiksha.com, Naukri Learning and Naukri FastForward</p>
                  <p class="qualification">MBA - Finance & Marketing (IIM, Bangalore), Electrical Engineering - (IIT, Delhi)</p>
                </div>
                
                <div class="brf-descrptn">
                    <p class="about-lead">Proven leader in the online space, Vivek is spearheading Team Shiksha towards our next phase of growth and profitability, with an aim to make it the default platform for prospective students to research and decide about careers and colleges related to higher education in India or abroad. Vivek is part of Info Edge family from past 9 years and have been involved in building businesses from scratch. Vivek brings with him strong product and consulting experience with leading firms like Naukri.com, Adobe Systems and IBM Research.</p>
                </div>
                
             </div>
             <p class="clr"></p>
            </div>
          </div>

          <div class="mngmnt-dtls">
             <div class="mngmnt-dtls-col">
               <div class="mngmnt-image">
                  <img src="/public/images/ambrish.jpg" />
               </div>
             <div class="mngmnt-prfl">
                
                <div class="lead-name">
                  <h3 class="name">Ambrish K Singh</h3>
                  <p class="positn">Executive VP & Head – Sales & Customer Delivery</p>
                  <p class="qualification">PGDM – Marketing (Amity Business School, Noida), BE – Mechanical (Chandrapur Engineering College)</p>
                </div>
                
                <div class="brf-descrptn">
                    <p class="about-lead">Ambrish has been conceptualising & leading strategic sales initiatives to drive revenue growth and profitability for shiksha business. He has more than fifteen years of exposure in sales, relationship & delivery management in service sector and  has also been involved in building businesses from ground up in <a href="ww.naukri.com">Naukri.com</a> and <a href="www.99acres.com">99acres.com</a> in past.</p>
                </div>
                
             </div>
             <p class="clr"></p>
            </div>
          </div>
          
          <div class="mngmnt-dtls">
             <div class="mngmnt-dtls-col">
               <div class="mngmnt-image">
                  <img src="/public/images/Nishant.jpg" width="122"/>
               </div>
             <div class="mngmnt-prfl">
                
                <div class="lead-name">
                  <h3 class="name">Nishant Pandey</h3>
                  <p class="positn">Executive VP & Head – Product Management, Analytics & Operations</p>
                  <p class="qualification">MBA – Major in Analytical Finance (ISB, Hyderabad), BTech – Chemical Engineering (IIT Delhi)</p>
                </div>
                
                <div class="brf-descrptn">
                    <p class="about-lead">Giving his success trajectory a new turn at Infoedge, Nishant joined Shiksha in the year 2016. At Shiksha, he is responsible for the complete product portfolio and leads the product management, operations and counselling teams. He is passionate about building teams and Products to solve consumer problems with the focus on profitability. </p>
                     <p class="about-lead1">Earlier in his stint Nishant successfully orchestrated the turn-around and reinvention of Jeevensaathi.com; led the conceptualization, detailing and launch of the NaukriRecruiter platform; and led the Product Management team of Naukri.com. </p>

                </div>
                
             </div>
             <p class="clr"></p>
            </div>
          </div>
          
          
          <div class="mngmnt-dtls">
             <div class="mngmnt-dtls-col">
               <div class="mngmnt-image">
                  <img src="/public/images/abhinav.jpg" />
               </div>
             <div class="mngmnt-prfl">
                
                <div class="lead-name">
                  <h3 class="name">Abhinav Katiyar</h3>
                  <p class="positn">Sr. VP & Head – Engineering</p>
                  <p class="qualification">BTech (Hons) – Computer Science & Engineering (UP Technical University)</p>
                </div>
                
                <div class="brf-descrptn">
                    <p class="about-lead">Spearheading technology at Shiksha, Abhinav is busy building next generation products to strengthen the leadership position of Shiksha.com on desktop as well as mobile platforms. In his previous stint Abhinav served as CTO for Rocket Internet and led technology at Yahoo Search . In the last decade he has built technology teams/products/infra/strategy ground-up for high growth companies like Jabong, Foodpanda, Printvenue and 99acres. </p>
                </div>
                
             </div>
             <p class="clr"></p>
            </div>
          </div>
          
        </section>
    </div>
 </div>

</div>

<?php $this->load->view('common/footerNew'); ?>
