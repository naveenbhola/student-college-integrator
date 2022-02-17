<?php $this->load->view('shikshaSchema/header'); ?>

<h1>Load Testing Results (using JMeter)</h1>

<div style='margin-bottom: 40px;'>
            <table cellspacing=0 border=1>
                <tr style="font-weight: bold">
                    <td width="20%" style=min-width:50px>Desc</td>
                    <td width="20%" style=min-width:50px>API</td>
                    <td width="10%" style=min-width:50px>No of Hits (per sec)</td>
                    <td width="10%" style=min-width:50px>Avg Time (in ms)</td>
                    <td width="10%" style=min-width:50px>Max Time (in ms)</td>
                    <td width="10%" style=min-width:50px>Min Time (in ms)</td>
                    <td width="10%" style=min-width:50px>No of hits(>200ms)</td>
                    <td width="10%" style=min-width:50px>No of hits(>250ms)</td>
                </tr>
                <tr>
                    <td style=min-width:50px>QDP sorted on upvotes</td>
                    <td style=min-width:50px>getQuestionDetailWithAnswers(sorted on upvotes)</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>411.65562152860002</td>
                    <td style=min-width:50px>3299.6530532837</td>
                    <td style=min-width:50px>140.1689052582</td>
                    <td style=min-width:50px>19</td>
                    <td style=min-width:50px>6</td>
                </tr>
                <tr>
                    <td style=min-width:50px>QDP sorted on latest</td>
                    <td style=min-width:50px>getQuestionDetailWithAnswers(sorted on latest)</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>210.2045726776</td>
                    <td style=min-width:50px>1287.1999740601</td>
                    <td style=min-width:50px>137.06803321839999</td>
                    <td style=min-width:50px>20</td>
                    <td style=min-width:50px>4</td>
                </tr>
                <tr>
                    <td style=min-width:50px>DDP sorted on upvotes</td>
                    <td style=min-width:50px>getDiscussionDetailWithComments(sorted with upvotes)</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>187.66742706299999</td>
                    <td style=min-width:50px>247.26605415340001</td>
                    <td style=min-width:50px>136.332988739</td>
                    <td style=min-width:50px>19</td>
                    <td style=min-width:50px>0</td>
                </tr>
                <tr>
                    <td style=min-width:50px>DDP sorted on latest</td>
                    <td style=min-width:50px>getDiscussionDetailWithComments(sorted with latest)</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>277.9736280441</td>
                    <td style=min-width:50px>1320.3120231628</td>
                    <td style=min-width:50px>150.5298614502</td>
                    <td style=min-width:50px>30</td>
                    <td style=min-width:50px>12</td>
                </tr>
                <tr>
                    <td style=min-width:50px>User Stats Page(users among top 100 users with max points)</td>
                    <td style=min-width:50px>getUserActivitiesAndStats</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>527.16279506679996</td>
                    <td style=min-width:50px>1944.1089630127001</td>
                    <td style=min-width:50px>30.102968215899999</td>
                    <td style=min-width:50px>24</td>
                    <td style=min-width:50px>20</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Link and Related Thread(Question)</td>
                    <td style=min-width:50px>getLinkedAndRelatedThread(questions)</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>158.8235807419</td>
                    <td style=min-width:50px>1164.6349430083999</td>
                    <td style=min-width:50px>31.491041183499998</td>
                    <td style=min-width:50px>13</td>
                    <td style=min-width:50px>13</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Link and Related Thread(Discussion)</td>
                    <td style=min-width:50px>getLinkedAndRelatedThread(discussion)</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>150.60303211210001</td>
                    <td style=min-width:50px>1165.3511524200001</td>
                    <td style=min-width:50px>38.243055343599998</td>
                    <td style=min-width:50px>3</td>
                    <td style=min-width:50px>3</td>
                </tr>
                <tr>
                    <td style=min-width:50px>InApp Notification Fetch</td>
                    <td style=min-width:50px>fetchInAppNotification</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>157.55671978000001</td>
                    <td style=min-width:50px>339.72096443179998</td>
                    <td style=min-width:50px>45.185089111300002</td>
                    <td style=min-width:50px>15</td>
                    <td style=min-width:50px>11</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Tag Detail Page</td>
                    <td style=min-width:50px>getTagDetailPage</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>137.68497467040001</td>
                    <td style=min-width:50px>366.39690399170001</td>
                    <td style=min-width:50px>67.452192306499995</td>
                    <td style=min-width:50px>2</td>
                    <td style=min-width:50px>1</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen default)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>355.87262153630002</td>
                    <td style=min-width:50px>1371.0510730742999</td>
                    <td style=min-width:50px>77.810049057000001</td>
                    <td style=min-width:50px>44</td>
                    <td style=min-width:50px>41</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen Discussion)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>424.93787765500002</td>
                    <td style=min-width:50px>1340.607881546</td>
                    <td style=min-width:50px>51.202058792099997</td>
                    <td style=min-width:50px>40</td>
                    <td style=min-width:50px>30</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen Unanswered)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>324.77117061619998</td>
                    <td style=min-width:50px>828.14908027649994</td>
                    <td style=min-width:50px>76.237916946400006</td>
                    <td style=min-width:50px>34</td>
                    <td style=min-width:50px>30</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen default Tag Reco)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>271.0132741928</td>
                    <td style=min-width:50px>646.15201950070002</td>
                    <td style=min-width:50px>66.814899444600002</td>
                    <td style=min-width:50px>35</td>
                    <td style=min-width:50px>25</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen default User Reco)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>144.4472122192</td>
                    <td style=min-width:50px>253.70407104489999</td>
                    <td style=min-width:50px>61.972856521600001</td>
                    <td style=min-width:50px>8</td>
                    <td style=min-width:50px>1</td>
                </tr>
                <tr>
                    <td style=min-width:50px>User Profile Page</td>
                    <td style=min-width:50px>getUserData</td>
                    <td style=min-width:50px>50</td>
                    <td style=min-width:50px>146.0090637207</td>
                    <td style=min-width:50px>1256.8738460541001</td>
                    <td style=min-width:50px>34.377098083500002</td>
                    <td style=min-width:50px>3</td>
                    <td style=min-width:50px>3</td>
                </tr>
            </table>
	</div>
	<div style='margin-bottom: 40px;'>
            <table cellspacing=0 border=1>
                <tr style="font-weight: bold">
                    <td width="20%" style=min-width:50px>Desc</td>
                    <td width="20%" style=min-width:50px>API</td>
                    <td width="10%" style=min-width:50px>No of Hits (per sec)</td>
                    <td style=min-width:50px>Avg Time (in ms)</td>
                    <td style=min-width:50px>Max Time (in ms)</td>
                    <td style=min-width:50px>Min Time (in ms)</td>
                    <td style=min-width:50px>No of hits(>200ms)</td>
                    <td style=min-width:50px>No of hits(>250ms)</td>
                </tr>
                <tr>
                    <td style=min-width:50px>QDP sorted on upvotes</td>
                    <td style=min-width:50px>getQuestionDetailWithAnswers (sorted on upvotes)</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>221.6711115837</td>
                    <td style=min-width:50px>1257.9901218414</td>
                    <td style=min-width:50px>116.4259910584</td>
                    <td style=min-width:50px>44</td>
                    <td style=min-width:50px>4</td>
                </tr>
                <tr>
                    <td style=min-width:50px>QDP sorted on latest</td>
                    <td style=min-width:50px>getQuestionDetailWithAnswers</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>315.79301834109998</td>
                    <td style=min-width:50px>1330.8520317078001</td>
                    <td style=min-width:50px>135.0889205933</td>
                    <td style=min-width:50px>39</td>
                    <td style=min-width:50px>24</td>
                </tr>
                <tr>
                    <td style=min-width:50px>DDP sorted on upvotes</td>
                    <td style=min-width:50px>getDiscussionDetailWithComments (sorted on upvotes)</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>211.75737619399999</td>
                    <td style=min-width:50px>1272.7379798889001</td>
                    <td style=min-width:50px>110.3870868683</td>
                    <td style=min-width:50px>33</td>
                    <td style=min-width:50px>7</td>
                </tr>
                <tr>
                    <td style=min-width:50px>DDP sorted on latest</td>
                    <td style=min-width:50px>getDiscussionDetailWithComments</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>376.28897190089998</td>
                    <td style=min-width:50px>1337.9609584807999</td>
                    <td style=min-width:50px>117.4252033234</td>
                    <td style=min-width:50px>57</td>
                    <td style=min-width:50px>29</td>
                </tr>
                <tr>
                    <td style=min-width:50px>User Stats Page(users among top 100 users with max points)</td>
                    <td style=min-width:50px>getUserActivitiesAndStats</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>1059.1799378395001</td>
                    <td style=min-width:50px>3351.5040874481001</td>
                    <td style=min-width:50px>140.74206352229999</td>
                    <td style=min-width:50px>97</td>
                    <td style=min-width:50px>97</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Link and Related Thread(Question)</td>
                    <td style=min-width:50px>getLinkedAndRelatedThread</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>235.21611928940001</td>
                    <td style=min-width:50px>2152.3008346557999</td>
                    <td style=min-width:50px>37.293910980200003</td>
                    <td style=min-width:50px>40</td>
                    <td style=min-width:50px>31</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Link and Related Thread(Discussion)</td>
                    <td style=min-width:50px>getLinkedAndRelatedThread </td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>73.523793220499996</td>
                    <td style=min-width:50px>181.79202079769999</td>
                    <td style=min-width:50px>33.021926879900001</td>
                    <td style=min-width:50px>0</td>
                    <td style=min-width:50px>0</td>
                </tr>
                <tr>
                    <td style=min-width:50px>InApp Notification Fetch</td>
                    <td style=min-width:50px>fetchInAppNotification</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>130.60271263120001</td>
                    <td style=min-width:50px>1218.0778980255</td>
                    <td style=min-width:50px>22.7558612823</td>
                    <td style=min-width:50px>12</td>
                    <td style=min-width:50px>7</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Tag Detail Page</td>
                    <td style=min-width:50px>getTagDetailPage</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>237.2992658615</td>
                    <td style=min-width:50px>899.23691749570003</td>
                    <td style=min-width:50px>62.784910201999999</td>
                    <td style=min-width:50px>47</td>
                    <td style=min-width:50px>38</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen default)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>275.02439737319997</td>
                    <td style=min-width:50px>597.002029419</td>
                    <td style=min-width:50px>62.943935394299999</td>
                    <td style=min-width:50px>75</td>
                    <td style=min-width:50px>61</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen Discussion)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>333.49120140079998</td>
                    <td style=min-width:50px>2561.3849163055002</td>
                    <td style=min-width:50px>61.615943908699997</td>
                    <td style=min-width:50px>72</td>
                    <td style=min-width:50px>67</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen Unanswered)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>462.74621725079999</td>
                    <td style=min-width:50px>1537.5061035156</td>
                    <td style=min-width:50px>60.887098312399999</td>
                    <td style=min-width:50px>80</td>
                    <td style=min-width:50px>79</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen default Tag Reco)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>212.08053588870001</td>
                    <td style=min-width:50px>1214.4958972930999</td>
                    <td style=min-width:50px>68.9589977264</td>
                    <td style=min-width:50px>36</td>
                    <td style=min-width:50px>20</td>
                </tr>
                <tr>
                    <td style=min-width:50px>Home Page(with Home screen default User Reco)</td>
                    <td style=min-width:50px>getHomepageData</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>171.1001014709</td>
                    <td style=min-width:50px>331.52413368229998</td>
                    <td style=min-width:50px>73.843002319299998</td>
                    <td style=min-width:50px>29</td>
                    <td style=min-width:50px>9</td>
                </tr>
                <tr>
                    <td style=min-width:50px>User Profile Page</td>
                    <td style=min-width:50px>getUserData</td>
                    <td style=min-width:50px>100</td>
                    <td style=min-width:50px>177.77770280839999</td>
                    <td style=min-width:50px>1222.5298881531</td>
                    <td style=min-width:50px>40.183067321800003</td>
                    <td style=min-width:50px>7</td>
                    <td style=min-width:50px>7</td>
                </tr>
            </table>

</div>

<?php $this->load->view('shikshaSchema/footer'); ?>

