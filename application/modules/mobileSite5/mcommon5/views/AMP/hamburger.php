<?php $this->load->view('mcommon5/AMP/pageHeader');?>
    <amp-sidebar id="sidebar" layout="nodisplay" side="left">
     <form class="menu-layer primary" action="/" target="_top">
       <div class="items color-w">
          <div class="block border-btmf1">
            <section class="p1" amp-access="NOT subscriber" amp-access-hide>
                <div class="bg-blue">
                  <div class="user-cols">
                    <div class="user-img">
                        <div class="user-avatar">G</div> 
                    </div>
                    <div class="user_info">
                      <p class="c-name-login color-f m-5btm">Welcome Guest</p>
                      <div class="log-block">
                          <a class="login ga-analytic" data-vars-event-name="HAMBURGER_LOGIN" href="<?php echo $loginUrl; ?>">Login</a>
                          
                           <a href="<?php echo $registerUrl; ?>" class="n-reg ga-analytic" data-vars-event-name="HAMBURGER_REGISTER">Register</a>

                      </div>
                    </div>
                    </div>
                </div>
              </section>
              <section class="p1" amp-access="subscriber" amp-access-hide>
                <div class="bg-blue log-inn">
                  <div class="user-cols">
                    <div class="user-img">
                        <div class="user-avatar"><template amp-access-template type="amp-mustache">{{profileIcon}}</template>
                        </div> 
                    </div>
                    <div class="user_info">
                      <p class="c-name-login color-f">Welcome 
                      <template amp-access-template type="amp-mustache">{{displayName}}</template></p>
                      <div class="log-block">
                          <a class="user-prof ga-analytic" data-vars-event-name="HAMBURGER_EDIT_PROFILE" href="<?php echo SHIKSHA_HOME.'/userprofile/edit';?>">View Profile</a>
                          <a href="<?php echo SHIKSHA_HOME;?>/muser5/MobileUser/logout" class="n-reg-out ga-analytic" data-vars-event-name="HAMBURGER_LOGOUT">Logout</a>
                      </div>
                    </div>
                    </div>
                </div>
              </section>

             <div class="block">

              <a href="<?php echo SHIKSHA_HOME;?>/resources/colleges-shortlisting" class="menu-item item-layer-1 has-sub-level pad10 txt-ovr cp ga-analytic" data-vars-event-name="HAMBURGER_MY_SHORTLIST">My Shortlist <span class="color-6 font-w4 inline-div" amp-access="subscriber" amp-access-hide><template amp-access-template type="amp-mustache" >{{shortlistCount}}</template></span>
              </a>  

              <a class="menu-item item-layer-1 has-sub-level pad10 txt-ovr cp ga-analytic" data-vars-event-name="HAMBURGER_COMPARE" href="<?php echo SHIKSHA_HOME;?>/resources/college-comparison?fromamp=1">My College Comparisons <span class="color-6 font-w4 inline-div">
              <span class="color-6 font-w4 inline-div" amp-access="subscriber" amp-access-hide><template amp-access-template type="amp-mustache" >{{cmpCount}}</template></span>
              </span></a>
            </div>
          </div>
          <div class="div-d pad-top border-btmf1">
           <label class="block padtb0 color-6 f12">COURSES/COLLEGES</label>
           <label class="menu-item item-layer-1 has-sub-level pad10 txt-ovr" for="find-by-course">
                Find Colleges by Specialization
           </label>

           <?php
            $ampHamburger = "HomePageRedesignCache/ampHamburger.html";
            if(file_exists($ampHamburger) && (time() - filemtime($ampHamburger))<=(1*24*60*60)){
                echo file_get_contents($ampHamburger);
            }else{
                ob_start();
                $this->load->view('/AMP/innerHamburger');
                $pageContent = ob_get_contents();
                ob_end_clean();
                echo $pageContent;
                $fp=fopen($ampHamburger,'w+');
                flock( $fp, LOCK_EX ); // exclusive lock
                fputs($fp,$pageContent);
                flock( $fp, LOCK_UN ); // release the lock
                fclose($fp);
            }
          ?>
    </form>
   </amp-sidebar>