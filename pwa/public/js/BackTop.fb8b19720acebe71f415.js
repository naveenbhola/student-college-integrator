(window["webpackJsonp"] = window["webpackJsonp"] || []).push([["BackTop"],{

/***/ "./application/modules/common/assets/BackTop.css":
/*!*******************************************************!*\
  !*** ./application/modules/common/assets/BackTop.css ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

eval("// extracted by mini-css-extract-plugin//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hcHBsaWNhdGlvbi9tb2R1bGVzL2NvbW1vbi9hc3NldHMvQmFja1RvcC5jc3M/ZmQ2ZSJdLCJuYW1lcyI6W10sIm1hcHBpbmdzIjoiQUFBQSIsImZpbGUiOiIuL2FwcGxpY2F0aW9uL21vZHVsZXMvY29tbW9uL2Fzc2V0cy9CYWNrVG9wLmNzcy5qcyIsInNvdXJjZXNDb250ZW50IjpbIi8vIGV4dHJhY3RlZCBieSBtaW5pLWNzcy1leHRyYWN0LXBsdWdpbiJdLCJzb3VyY2VSb290IjoiIn0=\n//# sourceURL=webpack-internal:///./application/modules/common/assets/BackTop.css\n");

/***/ }),

/***/ "./application/modules/common/components/BackTop.js":
/*!**********************************************************!*\
  !*** ./application/modules/common/components/BackTop.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

"use strict";
eval("\n\nObject.defineProperty(exports, \"__esModule\", {\n    value: true\n});\n\nvar _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if (\"value\" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();\n\nvar _react = __webpack_require__(/*! react */ \"./node_modules/react/index.js\");\n\nvar _react2 = _interopRequireDefault(_react);\n\n__webpack_require__(/*! ./../assets/BackTop.css */ \"./application/modules/common/assets/BackTop.css\");\n\nvar _AnalyticsTracking = __webpack_require__(/*! ./../../reusable/utils/AnalyticsTracking */ \"./application/modules/reusable/utils/AnalyticsTracking.js\");\n\nvar _AnalyticsTracking2 = _interopRequireDefault(_AnalyticsTracking);\n\nfunction _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { default: obj }; }\n\nfunction _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError(\"Cannot call a class as a function\"); } }\n\nfunction _possibleConstructorReturn(self, call) { if (!self) { throw new ReferenceError(\"this hasn't been initialised - super() hasn't been called\"); } return call && (typeof call === \"object\" || typeof call === \"function\") ? call : self; }\n\nfunction _inherits(subClass, superClass) { if (typeof superClass !== \"function\" && superClass !== null) { throw new TypeError(\"Super expression must either be null or a function, not \" + typeof superClass); } subClass.prototype = Object.create(superClass && superClass.prototype, { constructor: { value: subClass, enumerable: false, writable: true, configurable: true } }); if (superClass) Object.setPrototypeOf ? Object.setPrototypeOf(subClass, superClass) : subClass.__proto__ = superClass; }\n\nvar BackTop = function (_React$Component) {\n    _inherits(BackTop, _React$Component);\n\n    function BackTop() {\n        _classCallCheck(this, BackTop);\n\n        var _this = _possibleConstructorReturn(this, (BackTop.__proto__ || Object.getPrototypeOf(BackTop)).call(this));\n\n        _this.enableBackToTop = function () {\n            var ele = document.getElementById('backTop');\n            var wScroll = window.scrollY;\n            var wHeight = window.outerHeight > 0 ? window.outerHeight : window.innerHeight; // window.innerHeight for safari\n\n            if (!ele) {\n                return;\n            }\n\n            var bottom = _this.managePosition();\n            var chatIconBottom = bottom;\n            if (window.location.pathname == '/') {\n                // hide for homePage only\n                ele.style.bottom = '0px';\n                ele.classList.remove('backShow');\n                _this.manageChatIconPostion(chatIconBottom);\n                return true;\n            }\n\n            if (wScroll > wHeight * _this.afterFold) {\n                _this.show = true;\n                ele.style.bottom = bottom + 'px';\n                ele.classList.add('backShow'); // show back to top\n                chatIconBottom = bottom + 45;\n            } else if (wScroll < wHeight * _this.afterFold && wScroll > wHeight && _this.show) {\n                _this.show = true;\n                ele.style.bottom = bottom + 'px';;\n                ele.classList.add('backShow');\n                chatIconBottom = bottom + 45;\n            } else {\n                ele.style.bottom = '0px';\n                _this.show = false;\n                ele.classList.remove('backShow');\n                chatIconBottom = bottom;\n            }\n\n            _this.manageChatIconPostion(chatIconBottom);\n        };\n\n        _this.show = false;\n        _this.afterFold = 1.5;\n        _this.enableBackToTop = _this.enableBackToTop.bind(_this);\n        _this.defaultBottom = 50;\n        _this.bottomStickyList = ['examBtmCTA', 'clpBtmSticky', 'stickyBanner', 'cpSticky', 'cp-btmSticky', 'chpBtmCTA']; // these are the bottom sticky ID's    \n        return _this;\n    }\n\n    _createClass(BackTop, [{\n        key: 'componentDidMount',\n        value: function componentDidMount() {\n            window.addEventListener(\"scroll\", this.enableBackToTop);\n        }\n    }, {\n        key: 'componentWillUnmount',\n        value: function componentWillUnmount() {\n            window.removeEventListener(\"scroll\", this.enableBackToTop);\n        }\n    }, {\n        key: 'trackEvent',\n        value: function trackEvent() {\n            _AnalyticsTracking2.default.event({ category: 'SHIKSHA_PWA', action: 'BACK_TO_TOP_CLICK', label: 'SHIKSHA_PWA_BACK_TO_TOP' });\n        }\n    }, {\n        key: 'manageChatIconPostion',\n        value: function manageChatIconPostion(bottomPos) {\n            if (document.getElementsByClassName('primary-chat-icon') && document.getElementsByClassName('primary-chat-icon')[0] && bottomPos > 0) {\n                document.getElementsByClassName('primary-chat-icon')[0].style.bottom = bottomPos + 'px';\n            }\n        }\n    }, {\n        key: 'goToTop',\n        value: function goToTop() {\n            var _this2 = this;\n\n            window.scrollTo(0, 0);\n            this.trackEvent();\n            setTimeout(function () {\n                _this2.manageChatIconPostion(_this2.defaultBottom);\n            }, 100);\n        }\n    }, {\n        key: 'managePosition',\n        value: function managePosition() {\n            var elePos = document.getElementById('backTop');\n            var stikcyHeight = 0;\n            if (elePos) {\n                elePos = elePos.offsetHeight;\n                for (var i in this.bottomStickyList) {\n                    var ele = document.getElementById(this.bottomStickyList[i]);\n                    if (this.bottomStickyList[i] == 'clpBtmSticky' && ele) {\n                        stikcyHeight += ele.style.display == 'block' ? ele.offsetHeight : 0;\n                    } else if (this.bottomStickyList[i] == 'stickyBanner' && ele) {\n                        stikcyHeight += !ele.classList.contains('display-none') ? ele.offsetHeight : 0;\n                    } else if (this.bottomStickyList[i] == 'examBtmCTA' && ele) {\n                        stikcyHeight += ele.classList.contains('exm-BtmsSticky') ? ele.offsetHeight : 0;\n                    } else if ((this.bottomStickyList[i] == 'cpSticky' || this.bottomStickyList[i] == 'chpBtmCTA') && ele) {\n                        stikcyHeight += !ele.classList.contains('hide') ? ele.offsetHeight : 0;\n                    } else if (this.bottomStickyList[i] == 'cp-btmSticky' && ele) {\n                        stikcyHeight += ele.classList.contains('button-fixed') ? ele.offsetHeight : 0;\n                    }\n                }\n            }\n            return stikcyHeight ? stikcyHeight + elePos : this.defaultBottom;\n        }\n    }, {\n        key: 'render',\n        value: function render() {\n            return _react2.default.createElement(\n                _react2.default.Fragment,\n                null,\n                _react2.default.createElement('a', { className: 'back-toTop', id: 'backTop', onClick: this.goToTop.bind(this) })\n            );\n        }\n    }]);\n\n    return BackTop;\n}(_react2.default.Component);\n\nexports.default = BackTop;//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly8vLi9hcHBsaWNhdGlvbi9tb2R1bGVzL2NvbW1vbi9jb21wb25lbnRzL0JhY2tUb3AuanM/ODZjYSJdLCJuYW1lcyI6WyJCYWNrVG9wIiwiZW5hYmxlQmFja1RvVG9wIiwiZWxlIiwiZG9jdW1lbnQiLCJnZXRFbGVtZW50QnlJZCIsIndTY3JvbGwiLCJ3aW5kb3ciLCJzY3JvbGxZIiwid0hlaWdodCIsIm91dGVySGVpZ2h0IiwiaW5uZXJIZWlnaHQiLCJib3R0b20iLCJtYW5hZ2VQb3NpdGlvbiIsImNoYXRJY29uQm90dG9tIiwibG9jYXRpb24iLCJwYXRobmFtZSIsInN0eWxlIiwiY2xhc3NMaXN0IiwicmVtb3ZlIiwibWFuYWdlQ2hhdEljb25Qb3N0aW9uIiwiYWZ0ZXJGb2xkIiwic2hvdyIsImFkZCIsImJpbmQiLCJkZWZhdWx0Qm90dG9tIiwiYm90dG9tU3RpY2t5TGlzdCIsImFkZEV2ZW50TGlzdGVuZXIiLCJyZW1vdmVFdmVudExpc3RlbmVyIiwiQW5hbHl0aWNzIiwiZXZlbnQiLCJjYXRlZ29yeSIsImFjdGlvbiIsImxhYmVsIiwiYm90dG9tUG9zIiwiZ2V0RWxlbWVudHNCeUNsYXNzTmFtZSIsInNjcm9sbFRvIiwidHJhY2tFdmVudCIsInNldFRpbWVvdXQiLCJlbGVQb3MiLCJzdGlrY3lIZWlnaHQiLCJvZmZzZXRIZWlnaHQiLCJpIiwiZGlzcGxheSIsImNvbnRhaW5zIiwiZ29Ub1RvcCIsIlJlYWN0IiwiQ29tcG9uZW50Il0sIm1hcHBpbmdzIjoiOzs7Ozs7OztBQUFBOzs7O0FBQ0E7O0FBQ0E7Ozs7Ozs7Ozs7OztJQUVNQSxPOzs7QUFDTCx1QkFDQTtBQUFBOztBQUFBOztBQUFBLGNBcUJHQyxlQXJCSCxHQXFCcUIsWUFBSztBQUNuQixnQkFBSUMsTUFBVUMsU0FBU0MsY0FBVCxDQUF3QixTQUF4QixDQUFkO0FBQ0gsZ0JBQUlDLFVBQVVDLE9BQU9DLE9BQXJCO0FBQ0EsZ0JBQUlDLFVBQVdGLE9BQU9HLFdBQVAsR0FBbUIs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QixLQUE0QixXQUF2RSxLQUF1RnZDLEdBQTFGLEVBQThGO0FBQ2hHcUMsd0NBQWlCLENBQUNyQyxJQUFJZSxTQUFKLENBQWMwQixRQUFkLENBQXVCLE1BQXZCLENBQUYsR0FBb0N6QyxJQUFJc0MsWUFBeEMsR0FBdUQsQ0FBdkU7QUFDSCxxQkFGSyxNQUVBLElBQUcsS0FBS2YsZ0JBQUwsQ0FBc0JnQixDQUF0QixLQUE0QixjQUE1QixJQUE4Q3ZDLEdBQWpELEVBQXFEO0FBQ3ZEcUMsd0NBQWlCckMsSUFBSWUsU0FBSixDQUFjMEIsUUFBZCxDQUF1QixjQUF2QixDQUFELEdBQTJDekMsSUFBSXNDLFlBQS9DLEdBQThELENBQTlFO0FBQ0g7QUFDSjtBQUNKO0FBQ0QsbUJBQVFELFlBQUQsR0FBaUJBLGVBQWFELE1BQTlCLEdBQXVDLEtBQUtkLGFBQW5EO0FBQ0g7OztpQ0FHSjtBQUNDLG1CQUNDO0FBQUMsK0JBQUQsQ0FBTyxRQUFQO0FBQUE7QUFDQyxxREFBRyxXQUFVLFlBQWIsRUFBMEIsSUFBRyxTQUE3QixFQUF1QyxTQUFTLEtBQUtvQixPQUFMLENBQWFyQixJQUFiLENBQWtCLElBQWxCLENBQWhEO0FBREQsYUFERDtBQUtBOzs7O0VBdkdvQnNCLGdCQUFNQyxTOztrQkF5R2I5QyxPIiwiZmlsZSI6Ii4vYXBwbGljYXRpb24vbW9kdWxlcy9jb21tb24vY29tcG9uZW50cy9CYWNrVG9wLmpzLmpzIiwic291cmNlc0NvbnRlbnQiOlsiaW1wb3J0IFJlYWN0IGZyb20gJ3JlYWN0JztcbmltcG9ydCAnLi8uLi9hc3NldHMvQmFja1RvcC5jc3MnO1xuaW1wb3J0IEFuYWx5dGljcyBmcm9tICcuLy4uLy4uL3JldXNhYmxlL3V0aWxzL0FuYWx5dGljc1RyYWNraW5nJztcblxuY2xhc3MgQmFja1RvcCBleHRlbmRzIFJlYWN0LkNvbXBvbmVudHtcblx0Y29uc3RydWN0b3IoKVxuXHR7XG5cdFx0c3VwZXIoKTtcblx0XHR0aGlzLnNob3cgPSBmYWxzZTtcblx0XHR0aGlzLmFmdGVyRm9sZCA9IDEuNTtcblx0XHR0aGlzLmVuYWJsZUJhY2tUb1RvcCA9IHRoaXMuZW5hYmxlQmFja1RvVG9wLmJpbmQodGhpcyk7XG4gICAgICAgIFx0dGhpcy5kZWZhdWx0Qm90dG9tID0gNTA7XG5cdCAgICAgICAgdGhpcy5ib3R0b21TdGlja3lMaXN0ID0gWydleGFtQnRtQ1RBJywnY2xwQnRtU3RpY2t5Jywnc3RpY2t5QmFubmVyJywnY3BTdGlja3knLCdjcC1idG1TdGlja3knLCdjaHBCdG1DVEEnXTsgLy8gdGhlc2UgYXJlIHRoZSBib3R0b20gc3RpY2t5IElEJ3MgICAgXG5cdH1cblxuXHRjb21wb25lbnREaWRNb3VudCgpe1xuXHRcdHdpbmRvdy5hZGRFdmVudExpc3RlbmVyKFwic2Nyb2xsXCIsIHRoaXMuZW5hYmxlQmFja1RvVG9wKTtcblx0fVxuXG5cdGNvbXBvbmVudFdpbGxVbm1vdW50KCl7XG5cdFx0d2luZG93LnJlbW92ZUV2ZW50TGlzdGVuZXIoXCJzY3JvbGxcIiwgdGhpcy5lbmFibGVCYWNrVG9Ub3ApO1xuICAgIH1cblxuICAgIHRyYWNrRXZlbnQoKXsgICBcbiAgICAgICAgQW5hbHl0aWNzLmV2ZW50KHtjYXRlZ29yeSA6ICdTSElLU0hBX1BXQScsIGFjdGlvbiA6ICdCQUNLX1RPX1RPUF9DTElDSycsIGxhYmVsIDogJ1NISUtTSEFfUFdBX0JBQ0tfVE9fVE9QJ30pO1xuICAgIH1cblxuICAgIGVuYWJsZUJhY2tUb1RvcCA9ICgpID0+e1xuICAgICAgICBsZXQgZWxlICAgICA9IGRvY3VtZW50LmdldEVsZW1lbnRCeUlkKCdiYWNrVG9wJyk7XG4gICAgXHRsZXQgd1Njcm9sbCA9IHdpbmRvdy5zY3JvbGxZO1xuICAgIFx0bGV0IHdIZWlnaHQgPSAod2luZG93Lm91dGVySGVpZ2h0PjApID8gd2luZG93Lm91dGVySGVpZ2h0IDogd2luZG93LmlubmVySGVpZ2h0OyAvLyB3aW5kb3cuaW5uZXJIZWlnaHQgZm9yIHNhZmFyaVxuXG4gICAgICAgIGlmKCFlbGUpe1xuICAgICAgICAgICAgcmV0dXJuO1xuICAgICAgICB9XG5cbiAgICAgICAgbGV0IGJvdHRvbSA9IHRoaXMubWFuYWdlUG9zaXRpb24oKTtcbiAgICAgICAgbGV0IGNoYXRJY29uQm90dG9tID0gYm90dG9tO1xuICBcdFx0aWYod2luZG93LmxvY2F0aW9uLnBhdGhuYW1lID09ICcvJyl7IC8vIGhpZGUgZm9yIGhvbWVQYWdlIG9ubHlcbiAgICAgICAgICAgIGVsZS5zdHlsZS5ib3R0b20gPSAnMHB4JztcbiAgICAgICAgICAgIGVsZS5jbGFzc0xpc3QucmVtb3ZlKCdiYWNrU2hvdycpO1xuICAgICAgICAgICAgdGhpcy5tYW5hZ2VDaGF0SWNvblBvc3Rpb24oY2hhdEljb25Cb3R0b20pOyAgICAgICAgXG4gIFx0XHRcdHJldHVybiB0cnVlO1xuICBcdFx0fVxuICAgICAgICBcbiAgICBcdGlmKHdTY3JvbGwgPiAod0hlaWdodCp0aGlzLmFmdGVyRm9sZCkpe1xuICAgIFx0XHR0aGlzLnNob3cgPSB0cnVlO1xuICAgICAgICAgICAgZWxlLnN0eWxlLmJvdHRvbSA9IGJvdHRvbSsncHgnO1xuICAgIFx0XHRlbGUuY2xhc3NMaXN0LmFkZCgnYmFja1Nob3cnKTsgLy8gc2hvdyBiYWNrIHRvIHRvcFxuICAgICAgICAgICAgY2hhdEljb25Cb3R0b20gPSBib3R0b20gKyA0NTtcbiAgICBcdH1lbHNlIGlmKHdTY3JvbGwgPCAod0hlaWdodCp0aGlzLmFmdGVyRm9sZCkgJiYgd1Njcm9sbCA+IHdIZWlnaHQgJiYgdGhpcy5zaG93KXtcbiAgICBcdFx0dGhpcy5zaG93ID0gdHJ1ZTtcbiAgICAgICAgICAgIGVsZS5zdHlsZS5ib3R0b20gPSBib3R0b20rJ3B4Jzs7XG4gICAgXHRcdGVsZS5jbGFzc0xpc3QuYWRkKCdiYWNrU2hvdycpO1xuICAgICAgICAgICAgY2hhdEljb25Cb3R0b20gPSBib3R0b20gKyA0NTtcbiAgICBcdH1lbHNle1xuICAgICAgICAgICAgZWxlLnN0eWxlLmJvdHRvbSA9ICcwcHgnO1xuICAgICAgICAgICAgdGhpcy5zaG93ID0gZmFsc2U7XG4gICAgICAgICAgICBlbGUuY2xhc3NMaXN0LnJlbW92ZSgnYmFja1Nob3cnKTtcbiAgICAgICAgICAgIGNoYXRJY29uQm90dG9tID0gYm90dG9tO1xuICAgIFx0fVxuXG4gICAgICAgIHRoaXMubWFuYWdlQ2hhdEljb25Qb3N0aW9uKGNoYXRJY29uQm90dG9tKTsgICAgICAgIFxuICAgIH1cblxuICAgIG1hbmFnZUNoYXRJY29uUG9zdGlvbihib3R0b21Qb3Mpe1xuICAgICAgICBpZihkb2N1bWVudC5nZXRFbGVtZW50c0J5Q2xhc3NOYW1lKCdwcmltYXJ5LWNoYXQtaWNvbicpICYmIGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3ByaW1hcnktY2hhdC1pY29uJylbMF0gJiYgYm90dG9tUG9zID4wKXtcbiAgICAgICAgICAgIGRvY3VtZW50LmdldEVsZW1lbnRzQnlDbGFzc05hbWUoJ3ByaW1hcnktY2hhdC1pY29uJylbMF0uc3R5bGUuYm90dG9tID0gYm90dG9tUG9zICsgJ3B4JztcbiAgICAgICAgfVxuICAgIH1cblxuICAgIGdvVG9Ub3AoKXtcbiAgICBcdHdpbmRvdy5zY3JvbGxUbygwLDApO1xuICAgICAgICB0aGlzLnRyYWNrRXZlbnQoKTtcbiAgICAgICAgc2V0VGltZW91dCgoKT0+eyB0aGlzLm1hbmFnZUNoYXRJY29uUG9zdGlvbih0aGlzLmRlZmF1bHRCb3R0b20pOyB9LDEwMCk7ICAgICAgICAgICAgIFxuICAgIH1cblxuICAgIG1hbmFnZVBvc2l0aW9uKCl7XG4gICAgICAgIGxldCBlbGVQb3MgICAgICAgPSBkb2N1bWVudC5nZXRFbGVtZW50QnlJZCgnYmFja1RvcCcpO1xuICAgICAgICBsZXQgc3Rpa2N5SGVpZ2h0ID0gMDtcbiAgICAgICAgaWYoZWxlUG9zKXtcbiAgICAgICAgICAgIGVsZVBvcyA9IGVsZVBvcy5vZmZzZXRIZWlnaHQ7XG4gICAgICAgICAgICBmb3IodmFyIGkgaW4gdGhpcy5ib3R0b21TdGlja3lMaXN0KXtcbiAgICAgICAgICAgICAgICBsZXQgZWxlID0gZG9jdW1lbnQuZ2V0RWxlbWVudEJ5SWQodGhpcy5ib3R0b21TdGlja3lMaXN0W2ldKTtcbiAgICAgICAgICAgICAgICBpZih0aGlzLmJvdHRvbVN0aWNreUxpc3RbaV0gPT0gJ2NscEJ0bVN0aWNreScgJiYgZWxlKXtcbiAgICAgICAgICAgICAgICAgICAgc3Rpa2N5SGVpZ2h0ICs9IChlbGUuc3R5bGUuZGlzcGxheSA9PSAnYmxvY2snKSA/IGVsZS5vZmZzZXRIZWlnaHQgOiAwO1xuICAgICAgICAgICAgICAgIH1lbHNlIGlmKHRoaXMuYm90dG9tU3RpY2t5TGlzdFtpXSA9PSAnc3RpY2t5QmFubmVyJyAmJiBlbGUpe1xuICAgICAgICAgICAgICAgICAgICBzdGlrY3lIZWlnaHQgKz0gKCFlbGUuY2xhc3NMaXN0LmNvbnRhaW5zKCdkaXNwbGF5LW5vbmUnKSkgPyBlbGUub2Zmc2V0SGVpZ2h0IDogMDtcbiAgICAgICAgICAgICAgICB9ZWxzZSBpZih0aGlzLmJvdHRvbVN0aWNreUxpc3RbaV0gPT0gJ2V4YW1CdG1DVEEnICYmIGVsZSl7XG4gICAgICAgICAgICAgICAgICAgIHN0aWtjeUhlaWdodCArPSAoZWxlLmNsYXNzTGlzdC5jb250YWlucygnZXhtLUJ0bXNTdGlja3knKSkgPyBlbGUub2Zmc2V0SGVpZ2h0IDogMDtcbiAgICAgICAgICAgICAgICB9ZWxzZSBpZigodGhpcy5ib3R0b21TdGlja3lMaXN0W2ldID09ICdjcFN0aWNreScgfHwgdGhpcy5ib3R0b21TdGlja3lMaXN0W2ldID09ICdjaHBCdG1DVEEnKSAmJiBlbGUpe1xuICAgICAgICAgICAgICAgICAgICBzdGlrY3lIZWlnaHQgKz0gKCFlbGUuY2xhc3NMaXN0LmNvbnRhaW5zKCdoaWRlJykpID8gZWxlLm9mZnNldEhlaWdodCA6IDA7XG4gICAgICAgICAgICAgICAgfWVsc2UgaWYodGhpcy5ib3R0b21TdGlja3lMaXN0W2ldID09ICdjcC1idG1TdGlja3knICYmIGVsZSl7XG4gICAgICAgICAgICAgICAgICAgIHN0aWtjeUhlaWdodCArPSAoZWxlLmNsYXNzTGlzdC5jb250YWlucygnYnV0dG9uLWZpeGVkJykpID8gZWxlLm9mZnNldEhlaWdodCA6IDA7XG4gICAgICAgICAgICAgICAgfVxuICAgICAgICAgICAgfVxuICAgICAgICB9ICAgXG4gICAgICAgIHJldHVybiAoc3Rpa2N5SGVpZ2h0KSA/IHN0aWtjeUhlaWdodCtlbGVQb3MgOiB0aGlzLmRlZmF1bHRCb3R0b207XG4gICAgfVxuXG5cdHJlbmRlcigpXG5cdHtcblx0XHRyZXR1cm4gKFxuXHRcdFx0PFJlYWN0LkZyYWdtZW50PlxuXHRcdFx0XHQ8YSBjbGFzc05hbWU9XCJiYWNrLXRvVG9wXCIgaWQ9XCJiYWNrVG9wXCIgb25DbGljaz17dGhpcy5nb1RvVG9wLmJpbmQodGhpcyl9PjwvYT5cblx0XHRcdDwvUmVhY3QuRnJhZ21lbnQ+XG5cdFx0KVxuXHR9XG59XG5leHBvcnQgZGVmYXVsdCBCYWNrVG9wO1xuIl0sInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./application/modules/common/components/BackTop.js\n");

/***/ })

}]);