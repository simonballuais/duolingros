var Study=function(t){var e={};function n(o){if(e[o])return e[o].exports;var r=e[o]={i:o,l:!1,exports:{}};return t[o].call(r.exports,r,r.exports,n),r.l=!0,r.exports}return n.m=t,n.c=e,n.d=function(t,e,o){n.o(t,e)||Object.defineProperty(t,e,{enumerable:!0,get:o})},n.r=function(t){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(t,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(t,"__esModule",{value:!0})},n.t=function(t,e){if(1&e&&(t=n(t)),8&e)return t;if(4&e&&"object"==typeof t&&t&&t.__esModule)return t;var o=Object.create(null);if(n.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:t}),2&e&&"string"!=typeof t)for(var r in t)n.d(o,r,function(e){return t[e]}.bind(null,r));return o},n.n=function(t){var e=t&&t.__esModule?function(){return t.default}:function(){return t};return n.d(e,"a",e),e},n.o=function(t,e){return Object.prototype.hasOwnProperty.call(t,e)},n.p="",n(n.s=28)}({28:function(t,e){let n;function o(t,e){let n=t.data("prototype"),o=t.data("index"),r=n;r=r.replace(/__name__/g,o),t.data("index",o+1);let i=$("<li></li>").append(r);e.before(i)}$(document).ready(function(t){let e=$("<button>+<button>"),r=$("<li></li>").append(e);(n=$("ul.question-list")).append(r),n.data("index",n.find("ul.proposition-list").length),e.on("click",function(t){o(n,r)}),n.find("ul.proposition-list").each(function(t){let e=$("<button>+<button>"),n=$("<li></li>").append(e),r=$(this);r.append(n),r.data("index",r.find("li").length),e.on("click",function(t){o(r,n)})})})}});