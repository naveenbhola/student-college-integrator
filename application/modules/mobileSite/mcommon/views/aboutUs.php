<?php
$this->load->view('header');

if (isset($_COOKIE['ci_mobile']) and ($_COOKIE['ci_mobile'] == 'mobile'))
{
	echo $canonical_url;
}
?>
<style type="text/css">
.about-cont{padding:10px 10px 15px 10px; font-size:small; border-top:1px solid #e4e4e4; margin-top:10px; line-height:17px}
</style>

<div class="about-cont">
<div style="color:#FD8103; font-size:18px; font-weight:normal; margin-bottom:12px; display:block">About Us</div>
<p  style="font-size:12px; margin-bottom:12px">
<img src="/public/images/education-about.jpg" width="215" height="157" hspace="2" vspace="2" align="left" />Everyday we need to make decisions related to the different aspects of our life. Making right education related decision can go a long way in shaping ones career. This is a very time consuming and difficult process because education related information is unorganized, ever changing and not customized to suit your strengths. For e.g.: after completing 3 year graduation one may ask, should I do MBA or MCA? Further one may ask should I do higher studies in India or abroad. Even further should I study in England or America? Getting answers to these queries is only half work done. Having figured out which course to do and the location, there will be more questions like which colleges are good to pursue MBA or how to go to Australia to pursue higher studies or which is the best institute to receive coaching for IIT entrance examination.
</p>

<p style="font-size:12px; margin-bottom:12px">
Colleges, Institutes and Universities are striving to adapt to market requirements by introducing new courses. However due to the lack of availability of a common forum such crucial information is not reached to education seekers.
</p>
<p style="font-size:12px; margin-bottom:12px">
At Shiksha.com we are trying to solve problems described above. Shiksha.com is a place that connects education seeker with education provider. <strong>Shiksha.com provides information for over 70,000 colleges, courses, scholarships and admission notifications.</strong> This information can be easily searched by using Shiksha search bar.Shiksha.com provides tools that enable its users to make well informed decision by interacting with other Shiksha users, Shiksha experts. For e.g.: a Shiksha user can start a discussion at "Ask & Discuss" in order to seek opinion of other Shiksha users and Shiksha experts.
</p>
<p style="font-size:12px; margin-bottom:12px">
Shiksha.com has a team of counsellors on staff and works with education agents across the globe to assist students find the right college and course. Services of our counsellors are completely free of cost. Our counsellors are experienced and assist students in decision making process.
</p>
<p style="font-size:12px; margin-bottom:12px">
<strong>Shiksha.com is part of the naukri.com group-Indias No.1 job portal.</strong> Other portals owned by our parent company Info Edge are 99acres.com, JeevanSathi.com, Brijj.com and AskNaukri.com.
</p>
<p style="font-size:12px; margin-bottom:12px">
Feel free to view commonly asked questions here and take the site tour here.
</p>
<p style="font-size:12px; margin-bottom:12px">
We are continuously improving experience of our users by providing them tools to make the right decision.
</p>


<div style="color:#FD8103; font-weight:bold">All the best!</div>
</div>
<div class="clearFix"></div>
<?php $this->load->view('footer');  ?>
