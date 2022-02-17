import React, {Component} from 'react';
import { connect } from 'react-redux';

import {setCookie, extend} from '../../../../utils/commonHelper';
//import gnbConfig from '../../config/desktopGNBConfig';

import GNBSubmenu from './GNBSubmenu';

let timer = 0;
class HeaderGNB extends Component {
  render(){
    return (
      <div className="global-wrapper" id="_innerNav">
        <ul className="nav">
          {this.getGNBNodeWithData()}
        </ul>
        <p className="clr"></p>
      </div>
    );
  }
  componentDidMount(){
    document.querySelectorAll('.nav-othercourses .gnb-nav-indctr span').forEach(
      moreCourses => moreCourses.addEventListener('click', this.handleMoreCourseBtn.bind(this))
    );
    document.querySelectorAll('ul.g_lev2 > li .submenu2 .career-page-link').forEach(
      careerPage => careerPage.addEventListener('click', this.handleCareerPageLinkOnGNB.bind(this))
    );
    document.querySelector('body').addEventListener('mouseover', this.handleBodyHover.bind(this));
    //menu tunneling
    let submenu = document.querySelectorAll('.g_lev2').forEach(
      currObj => {
        this.gnbTunneling(currObj, {
          activate: this.activateSubmenu.bind(this),
          deactivate: this.deactivateSubmenu.bind(this)
        });
      }
    );
  }
  activateSubmenu(row){
    document.querySelector('.menu-overlay').style.display = 'block';
    let submenu = document.querySelectorAll(".g_lev1 ul.g_lev2 > li");
    for (var i = 0; i < submenu.length; i++) {
      submenu[i].classList.remove('activee');
      submenu[i].querySelector('.submenu2').style.display = 'none';
    }
    if(document.querySelector('.nav-othercourses ul.g_lev2').style.top == '-240px'){
      document.querySelectorAll('.nav-othercourses ul.g_lev2 > li .submenu2').forEach(
        currObj => {
          currObj.style.top = '240px'}
      );
    }else{
      document.querySelectorAll('.nav-othercourses ul.g_lev2 > li .submenu2').forEach(
        currObj => {
          currObj.style.top = '-3px'
        }
      );
    }
    row.classList.add("activee");
    let submenu2 = row.querySelector('div.submenu2');
    submenu2.style.display = 'block';
    this.setGNBHeight(row.parentElement.parentElement.parentElement);
   	this.manageotherBoxHeight(submenu2);
  }
  deactivateSubmenu(row){
    let submenu2 = row.querySelector('div.submenu2');
    submenu2.style.display = 'none';
    row.classList.remove("activee");
  }

  hideGNB(){
    document.querySelectorAll('.submenu').forEach(
        currObj => currObj.style.display = 'none'
      );
      document.querySelectorAll('.menu-overlay').forEach(
        currObj => currObj.style.display = 'none'
      );
      document.querySelectorAll('.shiksha-navCut').forEach(
        currObj => currObj.style.display = 'none'
      );
      document.querySelectorAll('.g_lev1').forEach(
        currObj => currObj.classList.remove('active')
      );
  }
  handleBodyHover(obj){
    let target = obj.target;
    if(target.classList.contains('menu-overlay') || target.classList.contains('global-wrapper')){
     this.hideGNB();
    }
  }
  handleCareerPageLinkOnGNB(event){
    let stream = event.target.getAttribute('careerPage');
    setCookie('streamSelected', stream, 0);
		window.location.href = this.props.config.SHIKSHA_HOME + "/careers/opportunities";
  }
  handleMoreCourseBtn(obj){
    let btn = obj.currentTarget;
    if(!btn.classList.contains('disable')){
      btn.parentElement.querySelectorAll('span').forEach(
        currObj => currObj.classList.remove('disable')
      );
      btn.classList.add('disable');
      document.querySelectorAll('.nav-othercourses ul.g_lev2 > li .submenu2').forEach(
        currObj => currObj.style.display = 'none'
      );
      let btnSibling = btn.parentElement.parentElement.querySelector('ul.g_lev2');
      //click up
      if(btn.querySelector('i').classList.contains('go-up')){
        btnSibling.style.top = '0';
        btnSibling.style.height = '720px';
        setTimeout(()=>{
          document.querySelector('.nav-othercourses .submenu').style.borderTop = '';
          document.querySelectorAll('.nav-othercourses ul.g_lev2 > li').forEach(
            currObj => {
              currObj.classList.remove('activee')
              currObj.querySelector('.submenu2').style.top = '-3px';
            }
          );
          document.querySelector('.nav-othercourses ul.g_lev2 > li').classList.add('activee');
          document.querySelector('.nav-othercourses ul.g_lev2 > li .submenu2').style.display = 'block';
        }, 200);

      }else{ //click down
        btnSibling.style.top = '-240px';
        btnSibling.style.height = '720px';
        setTimeout(()=>{
          document.querySelector('.nav-othercourses .submenu').style.borderTop = '1px solid #f6f6f6';
          let submenu = document.querySelectorAll('.nav-othercourses ul.g_lev2 > li');
          submenu.forEach(
            currObj => {
              currObj.classList.remove('activee')
              currObj.querySelector('.submenu2').style.top = '240px';
            }
          );
          submenu[submenu.length-1].classList.add('activee');
          submenu[submenu.length-1].querySelector('.submenu2').style.display = 'block';
        }, 200);
      }
    }
  }
  getGNBNodeWithData() {
    let tempArr = [];
    if(this.props.gnbConfig == null){
      return tempArr;
    }
    let liLevelZeroClass = '';
    this.props.gnbConfig.config.child[0].forEach(
      topNodeArr => {
        topNodeArr.forEach(
          topNode => {
            liLevelZeroClass ='g_lev1';
            if(topNode.key == "More Courses"){
              liLevelZeroClass = "g_lev1 nav-othercourses";
            }
            tempArr.push(
              <li key={topNode.key} className={liLevelZeroClass} onMouseEnter={this.handleGNB.bind(this, 'in')} onMouseLeave={this.handleGNB.bind(this, 'out')}>
                <a lang={topNode.key}>{topNode.key}<div className="spaceWrpr"></div><em className="g_pointer"></em></a>
                <GNBSubmenu nodeName={topNode.key} nodeData={topNode.child} hideGNB={this.hideGNB.bind(this)}/>
              </li>
            );
          }//top node loop
        );
      }
    );
    return tempArr;
  }

  handleGNB(action, obj){
    let clsObj = this;
    if(action == 'in'){
      let self = obj.currentTarget, submenu, mainmenu;
      //remove active class from all
      mainmenu = document.querySelectorAll('.g_lev1');
      for (var i = 0; i < mainmenu.length; i++) {
        mainmenu[i].classList.remove('active');
      }
      timer = setTimeout(function(){
        document.getElementsByClassName('menu-overlay')[0].style.display = 'block';
        self.classList.add("active");
        self.querySelector(".submenu").style.display = 'block';
        submenu = self.querySelectorAll("ul.g_lev2 > li");
        for (var i = 0; i < submenu.length; i++) {
          submenu[i].classList.remove('activee');
          submenu[i].querySelector('.submenu2').style.display = 'none';
        }
        if(self.classList.contains('nav-othercourses') && self.querySelector('.gnb-nav-indctr .go-dwn').parentElement.classList.contains('disable')){
          submenu = self.querySelectorAll(".nav-othercourses ul.g_lev2 > li");
          submenu[submenu.length-1].classList.add('activee');
          submenu[submenu.length-1].querySelector('.submenu2').style.top = '240px';
          submenu[submenu.length-1].querySelector('.submenu2').style.display = 'block';
        }else if(self.classList.contains('nav-othercourses') && self.querySelector('.gnb-nav-indctr .go-up').parentElement.classList.contains('disable')){
          submenu = self.querySelectorAll(".nav-othercourses ul.g_lev2 > li");
          submenu[0].classList.add('activee');
          submenu[0].querySelector('.submenu2').style.top = '-3px';
          submenu[0].querySelector('.submenu2').style.display = 'block';
        }else{
          submenu = self.querySelector("ul.g_lev2 > li");
          submenu.classList.add("activee");
          submenu.querySelector('.submenu2').style.display = 'block';
        }
        clsObj.setGNBHeight(self);
      }, 150);
      if(self.classList.contains('nav-othercourses')){
        clsObj.manageotherBoxHeight(self.querySelector('ul.g_lev2 > li .submenu2'));
        self.querySelector('ul.g_lev2 > li .submenu2').style.top = '-3px';
        self.querySelector('.gnb-nav-indctr').style.display = 'flex';
      }
    }else{
      clearTimeout(timer);
      let self_out = obj.currentTarget, submenu, mainmenu;
      mainmenu = document.querySelectorAll('.g_lev1');
      for (var i = 0; i < mainmenu.length; i++) {
        mainmenu[i].classList.remove('active');
      }
      self_out.classList.remove("active");
      setTimeout(function(){
        if(self_out.classList.contains('active')){
          document.getElementsByClassName('menu-overlay')[0].style.display = 'block';
          self_out.querySelector('.submenu').style.display = 'block';
        }else{
          document.getElementsByClassName('menu-overlay')[0].style.display = 'none';
          self_out.querySelector('.submenu').style.display = 'none';
        }
      },150);
    }
  }
  setGNBHeight(t){
  	var l2h       = parseInt(t.querySelectorAll('ul.g_lev2 > li').length * 40);
    var heightCal = parseInt(t.querySelector('li.activee .submenu2').offsetHeight);
  	var height    = (l2h > heightCal) ? l2h : heightCal;
  	var x         = t.querySelector('.g_lev2').style.minHeight != '' ? parseInt(t.querySelector('.g_lev2').style.minHeight.replace('px', '')) : 0;
  	if(height > x || t.querySelector('.g_lev2').style.minHeight == '0px'){
  		t.querySelector('.g_lev2').style.minHeight = (height+18)+'px';
  		t.querySelector('.submenu').style.minHeight = (height+21)+'px';

      if(t.classList.contains('nav-othercourses')){
  			t.querySelectorAll('.submenu').forEach(
          currObj => {
            currObj.style.minHeight = '523px';
            currObj.style.maxHeight = '523px';
            currObj.style.overflow  = 'hidden';
          }
        );
        t.querySelectorAll('.g_lev2').forEach(
          currObj => {
            currObj.style.minHeight = '720px';
            currObj.style.maxHeight = '720px';
            currObj.style.position  = 'relative';
          }
        );
  		}
  	}
  }
  manageotherBoxHeight(submenu2){
  	submenu2.querySelectorAll('table tr').forEach(
      trObj => {
        if(trObj.innerHTML == ''){
          trObj.parentNode.removeChild(trObj)
        }
    	}
    );
    var firstBoxHeight = submenu2.querySelector('table tr').offsetHeight;
  	var totalH = 523 - (62 + firstBoxHeight); // 62 is box heading height
  	submenu2.querySelectorAll('table tr ul.g_lev3').forEach(
      currObj => {
        currObj.style.maxHeight = totalH + 'px';
      }
    );
  }
  gnbTunneling(submenu, callbackObj) {
    var menu = submenu,
        activeRow = null,
        mouseLocs = [],
        lastDelayLoc = null,
        timeoutId = null,
        options = extend({
                    rowSelector: ".g_lev2 > li",
                    submenuSelector: "*",
                    submenuDirection: "right",
                    tolerance: 75,
                    enter: function(){},
                    exit: function(){},
                    activate: function(){},
                    deactivate: function(){},
                    exitMenu: function(){}
                  }, callbackObj);
    var MOUSE_LOCS_TRACKED = 3, DELAY = 300;
    var mousemoveDocument = function(e) {
      mouseLocs.push({x: e.pageX, y: e.pageY});
      if (mouseLocs.length > MOUSE_LOCS_TRACKED) {
        mouseLocs.shift();
      }
    };
    var mouseleaveMenu = function(event) {
      if (timeoutId) {
        clearTimeout(timeoutId);
      }
      if (options.exitMenu(event.target)) {
        if (activeRow) {
          options.deactivate(activeRow);
        }
        activeRow = null;
      }
    };
    var mouseenterRow = function(event) {
      if (timeoutId) {
        clearTimeout(timeoutId);
      }
      options.enter(event.target);
      possiblyActivate(event.target);
    };
    var mouseleaveRow = function(event) {
      options.exit(event.target);
    };
    var clickRow = function(event) {
      activate(event.currentTarget);
    };
    var activate = function(row) {
      if (row == activeRow) {
        return;
      }
      if (activeRow) {
        options.deactivate(activeRow);
      }
      options.activate(row);
      activeRow = row;
    };
    var activationDelay = function() {
      //if (!activeRow || !$j(activeRow).is(options.submenuSelector)) {
      if (!activeRow) {
          return 0;
      }
      var boundRect = submenu.getBoundingClientRect();
      var offset_top  = boundRect.y;
      var offset_left = boundRect.x;
      var upperLeft = {
              x: offset_left,
              y: offset_top - options.tolerance
          },
          upperRight = {
              x: offset_left + submenu.offsetWidth,
              y: upperLeft.y
          },
          lowerLeft = {
              x: offset_left,
              y: offset_top + submenu.offsetHeight + options.tolerance
          },
          lowerRight = {
              x: offset_left + submenu.offsetWidth,
              y: lowerLeft.y
          },
          loc = mouseLocs[mouseLocs.length - 1],
          prevLoc = mouseLocs[0];

      if (!loc) {
        return 0;
      }
      if (!prevLoc) {
        prevLoc = loc;
      }
      if (prevLoc.x < offset_left || prevLoc.x > lowerRight.x ||
          prevLoc.y < offset_top || prevLoc.y > lowerRight.y) {

          return 0;
      }

      if (lastDelayLoc &&
        loc.x == lastDelayLoc.x && loc.y == lastDelayLoc.y) {
        return 0;
      }

      var decreasingCorner = upperRight,
          increasingCorner = lowerRight;


      if (options.submenuDirection == "left") {
          decreasingCorner = lowerLeft;
          increasingCorner = upperLeft;
      } else if (options.submenuDirection == "below") {
          decreasingCorner = lowerRight;
          increasingCorner = lowerLeft;
      } else if (options.submenuDirection == "above") {
          decreasingCorner = upperLeft;
          increasingCorner = upperRight;
      }

      var decreasingSlope = slope(loc, decreasingCorner),
          increasingSlope = slope(loc, increasingCorner),
          prevDecreasingSlope = slope(prevLoc, decreasingCorner),
          prevIncreasingSlope = slope(prevLoc, increasingCorner);

      if (decreasingSlope < prevDecreasingSlope &&
              increasingSlope > prevIncreasingSlope) {

          lastDelayLoc = loc;
          return DELAY;
      }

      lastDelayLoc = null;
      return 0;
    };
    var possiblyActivate = function(row) {
      var delay = activationDelay();
      if (delay) {
        timeoutId = setTimeout(function() {
          possiblyActivate(row);
        }, delay);
      } else {
        activate(row);
      }
    };
    var slope = function(a, b) {
      return (b.y - a.y) / (b.x - a.x);
    }
    submenu.addEventListener('mouseleave', mouseleaveMenu);
    submenu.querySelectorAll(options.rowSelector).forEach(
      currObj => {
        currObj.addEventListener('mouseenter', mouseenterRow);
        currObj.addEventListener('mouseleave', mouseleaveRow);
        currObj.addEventListener('click', clickRow);
      }
    );
    document.addEventListener('mousemove', mousemoveDocument);
  }
}

function mapStateToProps(state)
{
    return {
        config : state.config,
        gnbConfig : state.gnbLinks
    }
}
export default connect(mapStateToProps)(HeaderGNB);
