exports.ids = ["BackTop"];
exports.modules = {

/***/ "./application/modules/common/assets/BackTop.css":
/*!*******************************************************!*\
  !*** ./application/modules/common/assets/BackTop.css ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("exports = module.exports = __webpack_require__(/*! ../../../../node_modules/css-loader/lib/css-base.js */ \"./node_modules/css-loader/lib/css-base.js\")(true);\n// imports\n\n\n// module\nexports.push([module.i, \"a.back-toTop{z-index:2;position:fixed;right:25px;bottom:0px;width:35px;height:35px;background:rgba(255,255,255,0.95);border-radius:50%;box-shadow:0 3px 12px 0 rgba(0, 0, 0, 0.48), 0 0 1px 1px rgba(0, 0, 0, 0.34);cursor:pointer;transition:all 600ms cubic-bezier(0.175, 0.885, 0.32, 1);opacity: 0;}\\na.back-toTop:after{content:'';display:block;width:8px;height:8px;border-bottom:2px solid #000;border-right:2px solid #000;transform:rotate(223deg);margin:-3px auto 0;text-align:center;position:absolute;top:48%;left:-1px;right:0}\\na.back-toTop.backShow{opacity: 1;bottom: 50px;}\", \"\", {\"version\":3,\"sources\":[\"/var/www/html/shiksha/pwa/application/modules/common/assets/BackTop.css\"],\"names\":[],\"mappings\":\"AAAA,aAAa,UAAU,eAAe,WAAW,WAAW,WAAW,YAAY,kCAAkC,kBAAkB,6EAA6E,eAAe,yDAAyD,WAAW,CAAC;AACxS,mBAAmB,WAAW,cAAc,UAAU,WAAW,6BAA6B,4BAA4B,yBAAyB,mBAAmB,kBAAkB,kBAAkB,QAAQ,UAAU,OAAO,CAAC;AACpO,sBAAsB,WAAW,aAAa,CAAC\",\"file\":\"BackTop.css\",\"sourcesContent\":[\"a.back-toTop{z-index:2;position:fixed;right:25px;bottom:0px;width:35px;height:35px;background:rgba(255,255,255,0.95);border-radius:50%;box-shadow:0 3px 12px 0 rgba(0, 0, 0, 0.48), 0 0 1px 1px rgba(0, 0, 0, 0.34);cursor:pointer;transition:all 600ms cubic-bezier(0.175, 0.885, 0.32, 1);opacity: 0;}\\na.back-toTop:after{content:'';display:block;width:8px;height:8px;border-bottom:2px solid #000;border-right:2px solid #000;transform:rotate(223deg);margin:-3px auto 0;text-align:center;position:absolute;top:48%;left:-1px;right:0}\\na.back-toTop.backShow{opacity: 1;bottom: 50px;}\"],\"sourceRoot\":\"\"}]);\n\n// exports\nexports.locals = {\n\t\"back-toTop\": \"back-toTop\",\n\t\"backShow\": \"backShow\"\n};\n\n//# sourceURL=webpack:///./application/modules/common/assets/BackTop.css?");

/***/ }),

/***/ "./application/modules/common/components/BackTop.js":
/*!**********************************************************!*\
  !*** ./application/modules/common/components/BackTop.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nObject.defineProperty(exports, \"__esModule\", {\n    value: true\n});\n\nvar _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();\n\nvar _react = __webpack_require__(/*! react */ \"react\");\n\nvar _react2 = _interopRequireDefault(_react);\n\n__webpack_require__(/*! ./../assets/BackTop.css */ \"./application/modules/common/assets/BackTop.css\");\n\nvar _AnalyticsTracking = __webpack_require__(/*! ./../../reusable/utils/AnalyticsTracking */ \"./application/modules/reusable/utils/AnalyticsTracking.js\");\n\nvar _AnalyticsTracking2 = _interopRequireDefault(_AnalyticsTracking);\n\nfunction _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); } return call && (typeof call === \"object\" || typeof call === \"function\") ? call : self; }\n\nfunction _inherits(subClass, superClass) { if (typeof superClass !== \"function\" && superClass !== null) { throw new TypeError(\"Super expression must either be null or a function, not \" + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }\n\nvar BackTop = function (_React$Component) {\n    _inherits(BackTop, _React$Component);\n\n    function BackTop() {\n        _classCallCheck(this, BackTop);\n\n        var _this = _possibleConstructorReturn(this, (BackTop.__proto__ || Object.getPrototypeOf(BackTop)).call(this));\n\n        _this.enableBackToTop = function () {\n            var ele = document.getElementById('backTop');\n            var wScroll = window.scrollY;\n            var wHeight = window.outerHeight > 0 ? window.outerHeight : window.innerHeight; // window.innerHeight for safari\n\n            if (!ele) {\n                return;\n            }\n\n            var bottom = _this.managePosition();\n            var chatIconBottom = bottom;\n            if (window.location.pathname == '/') {\n                // hide for homePage only\n                ele.style.bottom = '0px';\n                ele.classList.remove('backShow');\n                _this.manageChatIconPostion(chatIconBottom);\n                return true;\n            }\n\n            if (wScroll > wHeight * _this.afterFold) {\n                _this.show = true;\n                ele.style.bottom = bottom + 'px';\n                ele.classList.add('backShow'); // show back to top\n                chatIconBottom = bottom + 45;\n            } else if (wScroll < wHeight * _this.afterFold && wScroll > wHeight && _this.show) {\n                _this.show = true;\n                ele.style.bottom = bottom + 'px';;\n                ele.classList.add('backShow');\n                chatIconBottom = bottom + 45;\n            } else {\n                ele.style.bottom = '0px';\n                _this.show = false;\n                ele.classList.remove('backShow');\n                chatIconBottom = bottom;\n            }\n\n            _this.manageChatIconPostion(chatIconBottom);\n        };\n\n        _this.show = false;\n        _this.afterFold = 1.5;\n        _this.enableBackToTop = _this.enableBackToTop.bind(_this);\n        _this.defaultBottom = 50;\n        _this.bottomStickyList = ['examBtmCTA', 'clpBtmSticky', 'stickyBanner', 'cpSticky', 'cp-btmSticky', 'chpBtmCTA']; // these are the bottom sticky ID's    \n        return _this;\n    }\n\n    _createClass(BackTop, [{\n        key: 'componentDidMount',\n        value: function componentDidMount() {\n            window.addEventListener(\"scroll\", this.enableBackToTop);\n        }\n    }, {\n        key: 'componentWillUnmount',\n        value: function componentWillUnmount() {\n            window.removeEventListener(\"scroll\", this.enableBackToTop);\n        }\n    }, {\n        key: 'trackEvent',\n        value: function trackEvent() {\n            _AnalyticsTracking2.default.event({ category: 'SHIKSHA_PWA', action: 'BACK_TO_TOP_CLICK', label: 'SHIKSHA_PWA_BACK_TO_TOP' });\n        }\n    }, {\n        key: 'manageChatIconPostion',\n        value: function manageChatIconPostion(bottomPos) {\n            if (document.getElementsByClassName('primary-chat-icon') && document.getElementsByClassName('primary-chat-icon')[0] && bottomPos > 0) {\n                document.getElementsByClassName('primary-chat-icon')[0].style.bottom = bottomPos + 'px';\n            }\n        }\n    }, {\n        key: 'goToTop',\n        value: function goToTop() {\n            var _this2 = this;\n\n            window.scrollTo(0, 0);\n            this.trackEvent();\n            setTimeout(function () {\n                _this2.manageChatIconPostion(_this2.defaultBottom);\n            }, 100);\n        }\n    }, {\n        key: 'managePosition',\n        value: function managePosition() {\n            var elePos = document.getElementById('backTop');\n            var stikcyHeight = 0;\n            if (elePos) {\n                elePos = elePos.offsetHeight;\n                for (var i in this.bottomStickyList) {\n                    var ele = document.getElementById(this.bottomStickyList[i]);\n                    if (this.bottomStickyList[i] == 'clpBtmSticky' && ele) {\n                        stikcyHeight += ele.style.display == 'block' ? ele.offsetHeight : 0;\n                    } else if (this.bottomStickyList[i] == 'stickyBanner' && ele) {\n                        stikcyHeight += !ele.classList.contains('display-none') ? ele.offsetHeight : 0;\n                    } else if (this.bottomStickyList[i] == 'examBtmCTA' && ele) {\n                        stikcyHeight += ele.classList.contains('exm-BtmsSticky') ? ele.offsetHeight : 0;\n                    } else if ((this.bottomStickyList[i] == 'cpSticky' || this.bottomStickyList[i] == 'chpBtmCTA') && ele) {\n                        stikcyHeight += !ele.classList.contains('hide') ? ele.offsetHeight : 0;\n                    } else if (this.bottomStickyList[i] == 'cp-btmSticky' && ele) {\n                        stikcyHeight += ele.classList.contains('button-fixed') ? ele.offsetHeight : 0;\n                    }\n                }\n            }\n            return stikcyHeight ? stikcyHeight + elePos : this.defaultBottom;\n        }\n    }, {\n        key: 'render',\n        value: function render() {\n            return _react2.default.createElement(\n                _react2.default.Fragment,\n                null,\n                _react2.default.createElement('a', { className: 'back-toTop', id: 'backTop', onClick: this.goToTop.bind(this) })\n            );\n        }\n    }]);\n\n    return BackTop;\n}(_react2.default.Component);\n\nexports.default = BackTop;\n\n//# sourceURL=webpack:///./application/modules/common/components/BackTop.js?");

/***/ })

};;