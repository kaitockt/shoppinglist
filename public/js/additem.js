/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!*********************************!*\
  !*** ./resources/js/additem.js ***!
  \*********************************/
window.addEventListener('DOMContentLoaded', function (e) {
  document.getElementById('cb-repeat').onchange = function () {
    document.getElementById('repeat-number').disabled = !this.checked;
    document.getElementById('repeat-unit').disabled = !this.checked;
  };
});
/******/ })()
;