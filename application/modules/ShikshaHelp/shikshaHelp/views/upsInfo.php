<?php
		$headerComponents = array(
						//'css'	=>	array('raised_all','mainStyle','header'),
						'css' => array('static'),
						'js'	=>	array('common'),
						'title'	=>	'User Point System',
						'tabName' =>	'User Point',
						'taburl' =>  site_url('shikshaHelp/ShikshaHelp/upsInfo'),
						'metaKeywords'	=>'Some Meta Keywords',
						'notShowSearch' => true,
						'product'	=>'User Point',
						'displayname'=> (isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:""),
                                                'callShiksha'=>1,
						'canonicalURL'=>$canonicalurl
					);
		$this->load->view('common/header', $headerComponents);
		//$this->load->view('common/homepage', $headerComponents);
?>
<div id="management-content-wrap" style="margin:0 20px">
					<div class="shiksha-rule-title">Shiksha member levels and point system</div>
                  	<div class="rule-page-content">
                    	<div class="rule-info flLt">
                        	<p>To encourage participation and reward Shiksha members, Shiksha.com has a system of points and user levels.
The levels and points denote a user’s contribution to the Shiksha Ask & Answer.</p>
<p>Users on Shiksha Ask & Answer mainly fall into 4 User levels with 18 sub levels. You start by becoming a Beginner-Level 1 and can reach up to Expert-Level 18.</p>
<p>In order to move up the levels (i.e. Guide and Expert level), one needs to have both quality and quantity of answers/comments. Garner points for quantity by answering as many questions as you can and the quality will be judged by the upvotes that an answer gets by the users.</p>
                        </div>
                        <div class="rule-medal-fig flRt">
                   	  		<img src="/public/images/medal.png" width="218" height="185" alt="medal" />
                       </div>
                    </div>
                    <div class="bld Fnt14 mb15">User levels and points </div>
                    <div class="rules-table-section">
                    	<table cellpadding="0" cellspacing="0" border="1">
                        	<tr>
                            	<th>User level</th>
                                <th>Sub-levels</th>
                                <th>Points range</th>
                                <th>Additional check</th>
                            </tr>
	                        <tr>
                            	<td rowspan="5" style="background:#F9F9F9; vertical-align:top">Beginner</td>
                                <td>Level 1</td>
                                <td>0-24</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Level 2</td>
                                <td>25-49</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Level 3</td>
                                <td>50-99</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Level 4</td>
                                <td>100-199</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Level 5</td>
                                <td>200-399</td>
                                <td>-</td>
							</tr>
                            
                            <tr>
                            	<td rowspan="5" style="background:#F9F9F9; vertical-align:top">Contributor</td>
                                <td>Level 6</td>
                                <td>400-699</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Level 7</td>
                                <td>700-1149</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Level 8</td>
                                <td>1150-1749</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Level 9</td>
                                <td>1750-2499</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Level 10</td>
                                <td>2500-3499</td>
                                <td>-</td>
							</tr>
                            
                            <tr>
                            	<td rowspan="5" style="background:#F9F9F9; vertical-align:top">Guide</td>
                                <td>Level 11</td>
                                <td>3500-4999</td>
                                <td>100 upvotes across all answers</td>
                            </tr>
                            <tr>
                                <td>Level 12</td>
                                <td>5000-7499</td>
                                <td>100 upvotes across all answers</td>
                            </tr>
                            <tr>
                                <td>Level 13</td>
                                <td>7500-11499</td>
                                <td>100 upvotes across all answers</td>
                            </tr>
                            <tr>
                                <td>Level 14</td>
                                <td>11500-17499</td>
                                <td>100 upvotes across all answers</td>
                            </tr>
                            <tr>
                                <td>Level 15</td>
                                <td>17500-27499</td>
                                <td>100 upvotes across all answers</td>
							</tr>
                            <tr>
                            	<td rowspan="5" style="background:#F9F9F9; vertical-align:top">Scholar</td>
                                <td>Level 16</td>
                                <td>27500-42499</td>
                                <td>250 upvotes across all answers</td>
                            </tr>
                            <tr>
                                <td>Level 17</td>
                                <td>42500-67499</td>
                                <td>500 upvotes across all answers</td>
                            </tr>
                            <tr>
                                <td>Level 18</td>
                                <td>67500 and above</td>
                                <td>1000 upvotes across all answers</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="shiksha-rule-title">How you gain points and get promoted?</div>
                  	<div class="rule-page-content">
                    	<div class="rule-info flLt">
                        	<p>Shiksha offers points for every content created (asking a question, answering, commenting or commenting on discussions).</p>
<p>A new user starts earning points by getting “One time joining bonus” of 10 points. You can earn your joining bonus by performing your first action – which can be asking a question, commenting, answering, voting, sharing, reporting abuse, following, inviting friends or any profile update.</p>

                        </div>
                        <div class="rule-medal-fig flRt">
							<img src="/public/images/points_up.png" width="184" height="173" alt="points " style="margin-right:30px;"/>
                       </div>
                    </div>
                    <div class="bld Fnt14 mb15">Actions to earn points</div>
                    <div class="rules-table-section" style="width:730px;">
                    	<table cellpadding="0" cellspacing="0" border="1">
                        	<tr>
                            	<th>Category</th>
                                <th>Action</th>
                                <th>Points Awarded</th>
                            </tr>
	                        <tr>
                            	<td rowspan="3" style="background:#F9F9F9; vertical-align:top">Question</td>
                                <td>Ask a Question*</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Question gets 25 follows</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Question gets 100 follows</td>
                                <td>15</td>
                            </tr>  
                             
                            <tr>
                            	<td rowspan="4" style="background:#F9F9F9; vertical-align:top">Answer</td>
                                <td>Answer a Question</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Answer gets 10 up-votes</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Answer gets 25 up-votes</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Answer gets 100 up-votes</td>
                                <td>30</td>
                            </tr>          
                            
                            <tr>
                            	<td rowspan="3" style="background:#F9F9F9; vertical-align:top">Discussion</td>
                                <td>Start a Discussion  (open to Level 11 <br />
and higher)</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Discussion gets 25 follows</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Discussion gets 100 follows</td>
                                <td>15</td>
                            </tr> 
                            
                            <tr>
                            	<td rowspan="4" style="background:#F9F9F9; vertical-align:top">Discussion comment</td>
                                <td>Comment on a Discussion</td>
                                <td>0</td>
                            </tr>
                            <tr>
                                <td>Comment gets 10 up-votes</td>
                                <td>10</td>
                            </tr>
                            <tr>
                                <td>Comment gets 25 up-votes</td>
                                <td>10</td>
                            </tr>             
                            <tr>
                                <td>Comment gets 100 up-votes</td>
                                <td>30</td>
                            </tr>
                            
                            <tr>
                            	<td rowspan="10" style="background:#F9F9F9; vertical-align:top">Profile Update</td>
                                <td>Upload a photo</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Residence City</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Field of Study</td>
                                <td>5</td>
                            </tr>             
                            <tr>
                                <td>Country of Interest</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Phone Number</td>
                                <td>5</td>
                            </tr>             
                            <tr>
                                <td>Education Background</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Work Experience</td>
                                <td>5</td>
                            </tr>
                            <tr>
                                <td>Add “About me”</td>
                                <td>5</td>
                            </tr>             
                            <tr>
                                <td>Date of Birth </td>
                                <td>5</td>
                            </tr>
                              <tr>
                            	<td style="background:#F9F9F9; vertical-align:top">Joining Bonus</td>
                                <td>First time action</td>
                                <td>10</td>
                            </tr>            
                        </table>
                        *Points will be awarded for only first 5 questions asked.
                    </div>
                    
                    <div class="rule-page-content">
                    	<p>Sample scoring for an Answer <br />
                        If you answer a question you earn 10 points. If your answer gets:</p>
                        <ul class="score-list">
                            <li>10 upvotes you earn 10 additional points</li>
                            <li>25 upvotes you earn 10 more additional points, </li>
                            <li>100 up votes you earn 30 additional points. </li>
                        </ul>
                        <p>In effect, your answer can earn you a maximum of 60 points.</p>
                    </div>
                    <div style="width:100%; float:left;">
                    	
                    	<div class="rule-page-content">
                    	<div class="rule-info flLt">
                       <div class="shiksha-rule-title" style="width:100%; float:left; margin:10px 0;">How you lose points (Penalties)?</div>
                         <p style="margin:0;">While content creation earns you points, misuse of the community leads to penalty. You could get penalized for: </p>
						<ul class="score-list">
                            <li>Inactivity</li>
                            <li>Misuse of “Report Abuse” </li>
                            <li>Your content if it’s Reported Abuse by other users and accepted by the moderator</li>
                        </ul>

                        </div>
                        <div class="rule-medal-fig flRt">
							<img src="/public/images/points_down.png" width="184" height="173" alt="points " style="margin-right:30px;"/>
                       </div>
                    </div>
                    </div>
                    <div class="bld Fnt14 clearFix">Actions that will be penalized</div>
                    <div class="rules-table-section" style="margin-top:20px;">
                    	<table cellpadding="0" cellspacing="0" border="1">
                        	<tr>
                            	<th>Category</th>
                                <th>Action</th>
                                <th>Penalty</th>
                                <th>Conditions</th>
                            </tr>
	                        <tr>
                            	<td rowspan="7" style="background:#F9F9F9; vertical-align:top">Report Abuse on your content</td>
                                <td>Report abuse[s] rejected by moderator</td>
                                <td>0</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Report abuse accepted without penalty<br />
(content deleted) </td>
                                <td>0</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td rowspan="5" style="vertical-align:top">Report abuse accepted with penalty<br />

(content deleted) </td>
                                <td>0</td>
                                <td>For the first deletion (just a warning)</td>
                            </tr>
                            <tr>
                                <td>10</td>
                                <td>From second time onwards<br />
(user at beginner level)</td>
							</tr>
                            <tr>
                                <td>20</td>
                                <td>From second time onwards<br />
(user at contributor level)</td>
							</tr>
                            <tr>
                                <td>100</td>
                                <td>From second time onwards<br />
(User at guide level)</td>
							</tr>
                            <tr>
                                <td>500</td>
                                <td>From second time onwards<br />
(User at expert level)</td>
							</tr>
                            
                            <tr>
                            	<td rowspan="2" style="background:#F9F9F9; vertical-align:top">When you Report Abuse on<br />
any content</td>
                                <td>Report abuse rejected without penalty</td>
                                <td>0</td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>Report abuse rejected with penalty</td>
                                <td>10</td>
                                <td>-</td>
                            </tr>                            
                            <tr>
                            	<td style="background:#F9F9F9; vertical-align:top">Inactivity<br /> (Valid only for users from<br />Level 11 and above) </td>
                                <td>No action for 60 days</td>
                                <td>Level drop</td>
                                <td>User will lose a level and his / her points will be set to the highest point of the previous level.</td>
                            </tr>
                        </table>
                    </div>
                    
                    <div class="report-abuse-list">
                    	<p>1. Report Abuse</p>
                        <p>When your content is marked as Report Abuse:</p>
                    	<ul>
							<li>It will go to Shiksha moderators to be reviewed. Depending on the nature and intent of the report, the moderator has the right to accept or reject.
	                            <ol>
                            	<li>-If it is accepted, the content gets deleted and the moderator sends a warning. First time warning carries no penalty</li>
                            </ol>
                            </li>
                        </ul>
                        
                        <ul>
							<li> If same behaviour is repeated, then penalty will be levied as per your User level.
	                            <ol>
                            		<li>- Beginner level – 10 points will be deducted </li>
                                    <li>- Contributor level – 20 points will be deducted </li>
                                    <li>- Guide level – 100 points will be deducted </li>
                                    <li>- Expert level – 500 points will be deducted </li>
                            </ol>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="report-abuse-list">
                    	<p>2. When you Report Abuse a content: </p>
                        <p>Depending on the moderator’s discretion, “report abuse” marked by you can be rejected without deducting any points from your account or it can also be rejected with a penalty of 10 points.  </p>	
                    </div>
                    
                    
                    <div class="report-abuse-list">
                    	<p>3. Inactivity</p>
                        <p>If you are inactive for more than 60 days (Valid only for users from Level 11 and above) you will be demoted by one level and your points will be set back to the highest point of the previous level. You are considered to be active only if you have created content (question, answer, comment, replies) in a 60 day period and not for other actions such as upvotes or shares.
For Eg- If you are inactive at Level 11 for 60 days then on 61st day, you will be demoted to Level 10 and your points will be decreased to 3499, i.e. the highest points for Level 10. </p>
                    	
                    </div>
               </div>
<div style="line-height:25px">&nbsp;</div>	
</div>
<?php $this->load->view('common/footer');  ?>
