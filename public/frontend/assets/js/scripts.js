/**
 * simplebar - v6.2.5
 * Scrollbars, simpler.
 * https://grsmto.github.io/simplebar/
 *
 * Made by Adrien Denat from a fork by Jonathan Nicol
 * Under MIT License
 */

var SimpleBar=function(){"use strict";var e=function(t,i){return e=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(e,t){e.__proto__=t}||function(e,t){for(var i in t)Object.prototype.hasOwnProperty.call(t,i)&&(e[i]=t[i])},e(t,i)};var t=!("undefined"==typeof window||!window.document||!window.document.createElement),i="object"==typeof global&&global&&global.Object===Object&&global,s="object"==typeof self&&self&&self.Object===Object&&self,r=i||s||Function("return this")(),l=r.Symbol,o=Object.prototype,n=o.hasOwnProperty,a=o.toString,c=l?l.toStringTag:void 0;var h=Object.prototype.toString;var u=l?l.toStringTag:void 0;function d(e){return null==e?void 0===e?"[object Undefined]":"[object Null]":u&&u in Object(e)?function(e){var t=n.call(e,c),i=e[c];try{e[c]=void 0;var s=!0}catch(e){}var r=a.call(e);return s&&(t?e[c]=i:delete e[c]),r}(e):function(e){return h.call(e)}(e)}var p=/\s/;var v=/^\s+/;function f(e){return e?e.slice(0,function(e){for(var t=e.length;t--&&p.test(e.charAt(t)););return t}(e)+1).replace(v,""):e}function m(e){var t=typeof e;return null!=e&&("object"==t||"function"==t)}var b=/^[-+]0x[0-9a-f]+$/i,g=/^0b[01]+$/i,x=/^0o[0-7]+$/i,y=parseInt;function E(e){if("number"==typeof e)return e;if(function(e){return"symbol"==typeof e||function(e){return null!=e&&"object"==typeof e}(e)&&"[object Symbol]"==d(e)}(e))return NaN;if(m(e)){var t="function"==typeof e.valueOf?e.valueOf():e;e=m(t)?t+"":t}if("string"!=typeof e)return 0===e?e:+e;e=f(e);var i=g.test(e);return i||x.test(e)?y(e.slice(2),i?2:8):b.test(e)?NaN:+e}var O=function(){return r.Date.now()},w=Math.max,S=Math.min;function A(e,t,i){var s,r,l,o,n,a,c=0,h=!1,u=!1,d=!0;if("function"!=typeof e)throw new TypeError("Expected a function");function p(t){var i=s,l=r;return s=r=void 0,c=t,o=e.apply(l,i)}function v(e){return c=e,n=setTimeout(b,t),h?p(e):o}function f(e){var i=e-a;return void 0===a||i>=t||i<0||u&&e-c>=l}function b(){var e=O();if(f(e))return g(e);n=setTimeout(b,function(e){var i=t-(e-a);return u?S(i,l-(e-c)):i}(e))}function g(e){return n=void 0,d&&s?p(e):(s=r=void 0,o)}function x(){var e=O(),i=f(e);if(s=arguments,r=this,a=e,i){if(void 0===n)return v(a);if(u)return clearTimeout(n),n=setTimeout(b,t),p(a)}return void 0===n&&(n=setTimeout(b,t)),o}return t=E(t)||0,m(i)&&(h=!!i.leading,l=(u="maxWait"in i)?w(E(i.maxWait)||0,t):l,d="trailing"in i?!!i.trailing:d),x.cancel=function(){void 0!==n&&clearTimeout(n),c=0,s=a=r=n=void 0},x.flush=function(){return void 0===n?o:g(O())},x}var k=function(){return k=Object.assign||function(e){for(var t,i=1,s=arguments.length;i<s;i++)for(var r in t=arguments[i])Object.prototype.hasOwnProperty.call(t,r)&&(e[r]=t[r]);return e},k.apply(this,arguments)},W=null,M=null;function N(){if(null===W){if("undefined"==typeof document)return W=0;var e=document.body,t=document.createElement("div");t.classList.add("simplebar-hide-scrollbar"),e.appendChild(t);var i=t.getBoundingClientRect().right;e.removeChild(t),W=i}return W}function L(e){return e&&e.ownerDocument&&e.ownerDocument.defaultView?e.ownerDocument.defaultView:window}function z(e){return e&&e.ownerDocument?e.ownerDocument:document}t&&window.addEventListener("resize",(function(){M!==window.devicePixelRatio&&(M=window.devicePixelRatio,W=null)}));var C=function(e){return Array.prototype.reduce.call(e,(function(e,t){var i=t.name.match(/data-simplebar-(.+)/);if(i){var s=i[1].replace(/\W+(.)/g,(function(e,t){return t.toUpperCase()}));switch(t.value){case"true":e[s]=!0;break;case"false":e[s]=!1;break;case void 0:e[s]=!0;break;default:e[s]=t.value}}return e}),{})};function T(e,t){var i;e&&(i=e.classList).add.apply(i,t.split(" "))}function R(e,t){e&&t.split(" ").forEach((function(t){e.classList.remove(t)}))}function D(e){return".".concat(e.split(" ").join("."))}var V=Object.freeze({__proto__:null,getElementWindow:L,getElementDocument:z,getOptions:C,addClasses:T,removeClasses:R,classNamesToQuery:D}),H=L,j=z,B=C,_=T,q=R,P=D,X=function(){function e(t,i){void 0===i&&(i={});var s=this;if(this.removePreventClickId=null,this.minScrollbarWidth=20,this.stopScrollDelay=175,this.isScrolling=!1,this.isMouseEntering=!1,this.scrollXTicking=!1,this.scrollYTicking=!1,this.wrapperEl=null,this.contentWrapperEl=null,this.contentEl=null,this.offsetEl=null,this.maskEl=null,this.placeholderEl=null,this.heightAutoObserverWrapperEl=null,this.heightAutoObserverEl=null,this.rtlHelpers=null,this.scrollbarWidth=0,this.resizeObserver=null,this.mutationObserver=null,this.elStyles=null,this.isRtl=null,this.mouseX=0,this.mouseY=0,this.onMouseMove=function(){},this.onWindowResize=function(){},this.onStopScrolling=function(){},this.onMouseEntered=function(){},this.onScroll=function(){var e=H(s.el);s.scrollXTicking||(e.requestAnimationFrame(s.scrollX),s.scrollXTicking=!0),s.scrollYTicking||(e.requestAnimationFrame(s.scrollY),s.scrollYTicking=!0),s.isScrolling||(s.isScrolling=!0,_(s.el,s.classNames.scrolling)),s.showScrollbar("x"),s.showScrollbar("y"),s.onStopScrolling()},this.scrollX=function(){s.axis.x.isOverflowing&&s.positionScrollbar("x"),s.scrollXTicking=!1},this.scrollY=function(){s.axis.y.isOverflowing&&s.positionScrollbar("y"),s.scrollYTicking=!1},this._onStopScrolling=function(){q(s.el,s.classNames.scrolling),s.options.autoHide&&(s.hideScrollbar("x"),s.hideScrollbar("y")),s.isScrolling=!1},this.onMouseEnter=function(){s.isMouseEntering||(_(s.el,s.classNames.mouseEntered),s.showScrollbar("x"),s.showScrollbar("y"),s.isMouseEntering=!0),s.onMouseEntered()},this._onMouseEntered=function(){q(s.el,s.classNames.mouseEntered),s.options.autoHide&&(s.hideScrollbar("x"),s.hideScrollbar("y")),s.isMouseEntering=!1},this._onMouseMove=function(e){s.mouseX=e.clientX,s.mouseY=e.clientY,(s.axis.x.isOverflowing||s.axis.x.forceVisible)&&s.onMouseMoveForAxis("x"),(s.axis.y.isOverflowing||s.axis.y.forceVisible)&&s.onMouseMoveForAxis("y")},this.onMouseLeave=function(){s.onMouseMove.cancel(),(s.axis.x.isOverflowing||s.axis.x.forceVisible)&&s.onMouseLeaveForAxis("x"),(s.axis.y.isOverflowing||s.axis.y.forceVisible)&&s.onMouseLeaveForAxis("y"),s.mouseX=-1,s.mouseY=-1},this._onWindowResize=function(){s.scrollbarWidth=s.getScrollbarWidth(),s.hideNativeScrollbar()},this.onPointerEvent=function(e){var t,i;s.axis.x.track.el&&s.axis.y.track.el&&s.axis.x.scrollbar.el&&s.axis.y.scrollbar.el&&(s.axis.x.track.rect=s.axis.x.track.el.getBoundingClientRect(),s.axis.y.track.rect=s.axis.y.track.el.getBoundingClientRect(),(s.axis.x.isOverflowing||s.axis.x.forceVisible)&&(t=s.isWithinBounds(s.axis.x.track.rect)),(s.axis.y.isOverflowing||s.axis.y.forceVisible)&&(i=s.isWithinBounds(s.axis.y.track.rect)),(t||i)&&(e.stopPropagation(),"pointerdown"===e.type&&"touch"!==e.pointerType&&(t&&(s.axis.x.scrollbar.rect=s.axis.x.scrollbar.el.getBoundingClientRect(),s.isWithinBounds(s.axis.x.scrollbar.rect)?s.onDragStart(e,"x"):s.onTrackClick(e,"x")),i&&(s.axis.y.scrollbar.rect=s.axis.y.scrollbar.el.getBoundingClientRect(),s.isWithinBounds(s.axis.y.scrollbar.rect)?s.onDragStart(e,"y"):s.onTrackClick(e,"y")))))},this.drag=function(t){var i,r,l,o,n,a,c,h,u,d,p;if(s.draggedAxis&&s.contentWrapperEl){var v=s.axis[s.draggedAxis].track,f=null!==(r=null===(i=v.rect)||void 0===i?void 0:i[s.axis[s.draggedAxis].sizeAttr])&&void 0!==r?r:0,m=s.axis[s.draggedAxis].scrollbar,b=null!==(o=null===(l=s.contentWrapperEl)||void 0===l?void 0:l[s.axis[s.draggedAxis].scrollSizeAttr])&&void 0!==o?o:0,g=parseInt(null!==(a=null===(n=s.elStyles)||void 0===n?void 0:n[s.axis[s.draggedAxis].sizeAttr])&&void 0!==a?a:"0px",10);t.preventDefault(),t.stopPropagation();var x=("y"===s.draggedAxis?t.pageY:t.pageX)-(null!==(h=null===(c=v.rect)||void 0===c?void 0:c[s.axis[s.draggedAxis].offsetAttr])&&void 0!==h?h:0)-s.axis[s.draggedAxis].dragOffset,y=(x="x"===s.draggedAxis&&s.isRtl?(null!==(d=null===(u=v.rect)||void 0===u?void 0:u[s.axis[s.draggedAxis].sizeAttr])&&void 0!==d?d:0)-m.size-x:x)/(f-m.size)*(b-g);"x"===s.draggedAxis&&s.isRtl&&(y=(null===(p=e.getRtlHelpers())||void 0===p?void 0:p.isScrollingToNegative)?-y:y),s.contentWrapperEl[s.axis[s.draggedAxis].scrollOffsetAttr]=y}},this.onEndDrag=function(e){var t=j(s.el),i=H(s.el);e.preventDefault(),e.stopPropagation(),q(s.el,s.classNames.dragging),t.removeEventListener("mousemove",s.drag,!0),t.removeEventListener("mouseup",s.onEndDrag,!0),s.removePreventClickId=i.setTimeout((function(){t.removeEventListener("click",s.preventClick,!0),t.removeEventListener("dblclick",s.preventClick,!0),s.removePreventClickId=null}))},this.preventClick=function(e){e.preventDefault(),e.stopPropagation()},this.el=t,this.options=k(k({},e.defaultOptions),i),this.classNames=k(k({},e.defaultOptions.classNames),i.classNames),this.axis={x:{scrollOffsetAttr:"scrollLeft",sizeAttr:"width",scrollSizeAttr:"scrollWidth",offsetSizeAttr:"offsetWidth",offsetAttr:"left",overflowAttr:"overflowX",dragOffset:0,isOverflowing:!0,forceVisible:!1,track:{size:null,el:null,rect:null,isVisible:!1},scrollbar:{size:null,el:null,rect:null,isVisible:!1}},y:{scrollOffsetAttr:"scrollTop",sizeAttr:"height",scrollSizeAttr:"scrollHeight",offsetSizeAttr:"offsetHeight",offsetAttr:"top",overflowAttr:"overflowY",dragOffset:0,isOverflowing:!0,forceVisible:!1,track:{size:null,el:null,rect:null,isVisible:!1},scrollbar:{size:null,el:null,rect:null,isVisible:!1}}},"object"!=typeof this.el||!this.el.nodeName)throw new Error("Argument passed to SimpleBar must be an HTML element instead of ".concat(this.el));this.onMouseMove=function(e,t,i){var s=!0,r=!0;if("function"!=typeof e)throw new TypeError("Expected a function");return m(i)&&(s="leading"in i?!!i.leading:s,r="trailing"in i?!!i.trailing:r),A(e,t,{leading:s,maxWait:t,trailing:r})}(this._onMouseMove,64),this.onWindowResize=A(this._onWindowResize,64,{leading:!0}),this.onStopScrolling=A(this._onStopScrolling,this.stopScrollDelay),this.onMouseEntered=A(this._onMouseEntered,this.stopScrollDelay),this.init()}return e.getRtlHelpers=function(){if(e.rtlHelpers)return e.rtlHelpers;var t=document.createElement("div");t.innerHTML='<div class="simplebar-dummy-scrollbar-size"><div></div></div>';var i=t.firstElementChild,s=null==i?void 0:i.firstElementChild;if(!s)return null;document.body.appendChild(i),i.scrollLeft=0;var r=e.getOffset(i),l=e.getOffset(s);i.scrollLeft=-999;var o=e.getOffset(s);return document.body.removeChild(i),e.rtlHelpers={isScrollOriginAtZero:r.left!==l.left,isScrollingToNegative:l.left!==o.left},e.rtlHelpers},e.prototype.getScrollbarWidth=function(){try{return this.contentWrapperEl&&"none"===getComputedStyle(this.contentWrapperEl,"::-webkit-scrollbar").display||"scrollbarWidth"in document.documentElement.style||"-ms-overflow-style"in document.documentElement.style?0:N()}catch(e){return N()}},e.getOffset=function(e){var t=e.getBoundingClientRect(),i=j(e),s=H(e);return{top:t.top+(s.pageYOffset||i.documentElement.scrollTop),left:t.left+(s.pageXOffset||i.documentElement.scrollLeft)}},e.prototype.init=function(){t&&(this.initDOM(),this.rtlHelpers=e.getRtlHelpers(),this.scrollbarWidth=this.getScrollbarWidth(),this.recalculate(),this.initListeners())},e.prototype.initDOM=function(){var e,t;this.wrapperEl=this.el.querySelector(P(this.classNames.wrapper)),this.contentWrapperEl=this.options.scrollableNode||this.el.querySelector(P(this.classNames.contentWrapper)),this.contentEl=this.options.contentNode||this.el.querySelector(P(this.classNames.contentEl)),this.offsetEl=this.el.querySelector(P(this.classNames.offset)),this.maskEl=this.el.querySelector(P(this.classNames.mask)),this.placeholderEl=this.findChild(this.wrapperEl,P(this.classNames.placeholder)),this.heightAutoObserverWrapperEl=this.el.querySelector(P(this.classNames.heightAutoObserverWrapperEl)),this.heightAutoObserverEl=this.el.querySelector(P(this.classNames.heightAutoObserverEl)),this.axis.x.track.el=this.findChild(this.el,"".concat(P(this.classNames.track)).concat(P(this.classNames.horizontal))),this.axis.y.track.el=this.findChild(this.el,"".concat(P(this.classNames.track)).concat(P(this.classNames.vertical))),this.axis.x.scrollbar.el=(null===(e=this.axis.x.track.el)||void 0===e?void 0:e.querySelector(P(this.classNames.scrollbar)))||null,this.axis.y.scrollbar.el=(null===(t=this.axis.y.track.el)||void 0===t?void 0:t.querySelector(P(this.classNames.scrollbar)))||null,this.options.autoHide||(_(this.axis.x.scrollbar.el,this.classNames.visible),_(this.axis.y.scrollbar.el,this.classNames.visible))},e.prototype.initListeners=function(){var e,t=this,i=H(this.el);if(this.el.addEventListener("mouseenter",this.onMouseEnter),this.el.addEventListener("pointerdown",this.onPointerEvent,!0),this.el.addEventListener("mousemove",this.onMouseMove),this.el.addEventListener("mouseleave",this.onMouseLeave),null===(e=this.contentWrapperEl)||void 0===e||e.addEventListener("scroll",this.onScroll),i.addEventListener("resize",this.onWindowResize),this.contentEl){if(window.ResizeObserver){var s=!1,r=i.ResizeObserver||ResizeObserver;this.resizeObserver=new r((function(){s&&i.requestAnimationFrame((function(){t.recalculate()}))})),this.resizeObserver.observe(this.el),this.resizeObserver.observe(this.contentEl),i.requestAnimationFrame((function(){s=!0}))}this.mutationObserver=new i.MutationObserver((function(){i.requestAnimationFrame((function(){t.recalculate()}))})),this.mutationObserver.observe(this.contentEl,{childList:!0,subtree:!0,characterData:!0})}},e.prototype.recalculate=function(){if(this.heightAutoObserverEl&&this.contentEl&&this.contentWrapperEl&&this.wrapperEl&&this.placeholderEl){var e=H(this.el);this.elStyles=e.getComputedStyle(this.el),this.isRtl="rtl"===this.elStyles.direction;var t=this.contentEl.offsetWidth,i=this.heightAutoObserverEl.offsetHeight<=1,s=this.heightAutoObserverEl.offsetWidth<=1||t>0,r=this.contentWrapperEl.offsetWidth,l=this.elStyles.overflowX,o=this.elStyles.overflowY;this.contentEl.style.padding="".concat(this.elStyles.paddingTop," ").concat(this.elStyles.paddingRight," ").concat(this.elStyles.paddingBottom," ").concat(this.elStyles.paddingLeft),this.wrapperEl.style.margin="-".concat(this.elStyles.paddingTop," -").concat(this.elStyles.paddingRight," -").concat(this.elStyles.paddingBottom," -").concat(this.elStyles.paddingLeft);var n=this.contentEl.scrollHeight,a=this.contentEl.scrollWidth;this.contentWrapperEl.style.height=i?"auto":"100%",this.placeholderEl.style.width=s?"".concat(t||a,"px"):"auto",this.placeholderEl.style.height="".concat(n,"px");var c=this.contentWrapperEl.offsetHeight;this.axis.x.isOverflowing=0!==t&&a>t,this.axis.y.isOverflowing=n>c,this.axis.x.isOverflowing="hidden"!==l&&this.axis.x.isOverflowing,this.axis.y.isOverflowing="hidden"!==o&&this.axis.y.isOverflowing,this.axis.x.forceVisible="x"===this.options.forceVisible||!0===this.options.forceVisible,this.axis.y.forceVisible="y"===this.options.forceVisible||!0===this.options.forceVisible,this.hideNativeScrollbar();var h=this.axis.x.isOverflowing?this.scrollbarWidth:0,u=this.axis.y.isOverflowing?this.scrollbarWidth:0;this.axis.x.isOverflowing=this.axis.x.isOverflowing&&a>r-u,this.axis.y.isOverflowing=this.axis.y.isOverflowing&&n>c-h,this.axis.x.scrollbar.size=this.getScrollbarSize("x"),this.axis.y.scrollbar.size=this.getScrollbarSize("y"),this.axis.x.scrollbar.el&&(this.axis.x.scrollbar.el.style.width="".concat(this.axis.x.scrollbar.size,"px")),this.axis.y.scrollbar.el&&(this.axis.y.scrollbar.el.style.height="".concat(this.axis.y.scrollbar.size,"px")),this.positionScrollbar("x"),this.positionScrollbar("y"),this.toggleTrackVisibility("x"),this.toggleTrackVisibility("y")}},e.prototype.getScrollbarSize=function(e){var t,i;if(void 0===e&&(e="y"),!this.axis[e].isOverflowing||!this.contentEl)return 0;var s,r=this.contentEl[this.axis[e].scrollSizeAttr],l=null!==(i=null===(t=this.axis[e].track.el)||void 0===t?void 0:t[this.axis[e].offsetSizeAttr])&&void 0!==i?i:0,o=l/r;return s=Math.max(~~(o*l),this.options.scrollbarMinSize),this.options.scrollbarMaxSize&&(s=Math.min(s,this.options.scrollbarMaxSize)),s},e.prototype.positionScrollbar=function(t){var i,s,r;void 0===t&&(t="y");var l=this.axis[t].scrollbar;if(this.axis[t].isOverflowing&&this.contentWrapperEl&&l.el&&this.elStyles){var o=this.contentWrapperEl[this.axis[t].scrollSizeAttr],n=(null===(i=this.axis[t].track.el)||void 0===i?void 0:i[this.axis[t].offsetSizeAttr])||0,a=parseInt(this.elStyles[this.axis[t].sizeAttr],10),c=this.contentWrapperEl[this.axis[t].scrollOffsetAttr];c="x"===t&&this.isRtl&&(null===(s=e.getRtlHelpers())||void 0===s?void 0:s.isScrollOriginAtZero)?-c:c,"x"===t&&this.isRtl&&(c=(null===(r=e.getRtlHelpers())||void 0===r?void 0:r.isScrollingToNegative)?c:-c);var h=c/(o-a),u=~~((n-l.size)*h);u="x"===t&&this.isRtl?-u+(n-l.size):u,l.el.style.transform="x"===t?"translate3d(".concat(u,"px, 0, 0)"):"translate3d(0, ".concat(u,"px, 0)")}},e.prototype.toggleTrackVisibility=function(e){void 0===e&&(e="y");var t=this.axis[e].track.el,i=this.axis[e].scrollbar.el;t&&i&&this.contentWrapperEl&&(this.axis[e].isOverflowing||this.axis[e].forceVisible?(t.style.visibility="visible",this.contentWrapperEl.style[this.axis[e].overflowAttr]="scroll",this.el.classList.add("".concat(this.classNames.scrollable,"-").concat(e))):(t.style.visibility="hidden",this.contentWrapperEl.style[this.axis[e].overflowAttr]="hidden",this.el.classList.remove("".concat(this.classNames.scrollable,"-").concat(e))),this.axis[e].isOverflowing?i.style.display="block":i.style.display="none")},e.prototype.showScrollbar=function(e){void 0===e&&(e="y"),this.axis[e].isOverflowing&&!this.axis[e].scrollbar.isVisible&&(_(this.axis[e].scrollbar.el,this.classNames.visible),this.axis[e].scrollbar.isVisible=!0)},e.prototype.hideScrollbar=function(e){void 0===e&&(e="y"),this.axis[e].isOverflowing&&this.axis[e].scrollbar.isVisible&&(q(this.axis[e].scrollbar.el,this.classNames.visible),this.axis[e].scrollbar.isVisible=!1)},e.prototype.hideNativeScrollbar=function(){this.offsetEl&&(this.offsetEl.style[this.isRtl?"left":"right"]=this.axis.y.isOverflowing||this.axis.y.forceVisible?"-".concat(this.scrollbarWidth,"px"):"0px",this.offsetEl.style.bottom=this.axis.x.isOverflowing||this.axis.x.forceVisible?"-".concat(this.scrollbarWidth,"px"):"0px")},e.prototype.onMouseMoveForAxis=function(e){void 0===e&&(e="y");var t=this.axis[e];t.track.el&&t.scrollbar.el&&(t.track.rect=t.track.el.getBoundingClientRect(),t.scrollbar.rect=t.scrollbar.el.getBoundingClientRect(),this.isWithinBounds(t.track.rect)?(this.showScrollbar(e),_(t.track.el,this.classNames.hover),this.isWithinBounds(t.scrollbar.rect)?_(t.scrollbar.el,this.classNames.hover):q(t.scrollbar.el,this.classNames.hover)):(q(t.track.el,this.classNames.hover),this.options.autoHide&&this.hideScrollbar(e)))},e.prototype.onMouseLeaveForAxis=function(e){void 0===e&&(e="y"),q(this.axis[e].track.el,this.classNames.hover),q(this.axis[e].scrollbar.el,this.classNames.hover),this.options.autoHide&&this.hideScrollbar(e)},e.prototype.onDragStart=function(e,t){var i;void 0===t&&(t="y");var s=j(this.el),r=H(this.el),l=this.axis[t].scrollbar,o="y"===t?e.pageY:e.pageX;this.axis[t].dragOffset=o-((null===(i=l.rect)||void 0===i?void 0:i[this.axis[t].offsetAttr])||0),this.draggedAxis=t,_(this.el,this.classNames.dragging),s.addEventListener("mousemove",this.drag,!0),s.addEventListener("mouseup",this.onEndDrag,!0),null===this.removePreventClickId?(s.addEventListener("click",this.preventClick,!0),s.addEventListener("dblclick",this.preventClick,!0)):(r.clearTimeout(this.removePreventClickId),this.removePreventClickId=null)},e.prototype.onTrackClick=function(e,t){var i,s,r,l,o=this;void 0===t&&(t="y");var n=this.axis[t];if(this.options.clickOnTrack&&n.scrollbar.el&&this.contentWrapperEl){e.preventDefault();var a=H(this.el);this.axis[t].scrollbar.rect=n.scrollbar.el.getBoundingClientRect();var c=null!==(s=null===(i=this.axis[t].scrollbar.rect)||void 0===i?void 0:i[this.axis[t].offsetAttr])&&void 0!==s?s:0,h=parseInt(null!==(l=null===(r=this.elStyles)||void 0===r?void 0:r[this.axis[t].sizeAttr])&&void 0!==l?l:"0px",10),u=this.contentWrapperEl[this.axis[t].scrollOffsetAttr],d=("y"===t?this.mouseY-c:this.mouseX-c)<0?-1:1,p=-1===d?u-h:u+h,v=function(){o.contentWrapperEl&&(-1===d?u>p&&(u-=40,o.contentWrapperEl[o.axis[t].scrollOffsetAttr]=u,a.requestAnimationFrame(v)):u<p&&(u+=40,o.contentWrapperEl[o.axis[t].scrollOffsetAttr]=u,a.requestAnimationFrame(v)))};v()}},e.prototype.getContentElement=function(){return this.contentEl},e.prototype.getScrollElement=function(){return this.contentWrapperEl},e.prototype.removeListeners=function(){var e=H(this.el);this.el.removeEventListener("mouseenter",this.onMouseEnter),this.el.removeEventListener("pointerdown",this.onPointerEvent,!0),this.el.removeEventListener("mousemove",this.onMouseMove),this.el.removeEventListener("mouseleave",this.onMouseLeave),this.contentWrapperEl&&this.contentWrapperEl.removeEventListener("scroll",this.onScroll),e.removeEventListener("resize",this.onWindowResize),this.mutationObserver&&this.mutationObserver.disconnect(),this.resizeObserver&&this.resizeObserver.disconnect(),this.onMouseMove.cancel(),this.onWindowResize.cancel(),this.onStopScrolling.cancel(),this.onMouseEntered.cancel()},e.prototype.unMount=function(){this.removeListeners()},e.prototype.isWithinBounds=function(e){return this.mouseX>=e.left&&this.mouseX<=e.left+e.width&&this.mouseY>=e.top&&this.mouseY<=e.top+e.height},e.prototype.findChild=function(e,t){var i=e.matches||e.webkitMatchesSelector||e.mozMatchesSelector||e.msMatchesSelector;return Array.prototype.filter.call(e.children,(function(e){return i.call(e,t)}))[0]},e.rtlHelpers=null,e.defaultOptions={forceVisible:!1,clickOnTrack:!0,scrollbarMinSize:25,scrollbarMaxSize:0,ariaLabel:"scrollable content",classNames:{contentEl:"simplebar-content",contentWrapper:"simplebar-content-wrapper",offset:"simplebar-offset",mask:"simplebar-mask",wrapper:"simplebar-wrapper",placeholder:"simplebar-placeholder",scrollbar:"simplebar-scrollbar",track:"simplebar-track",heightAutoObserverWrapperEl:"simplebar-height-auto-observer-wrapper",heightAutoObserverEl:"simplebar-height-auto-observer",visible:"simplebar-visible",horizontal:"simplebar-horizontal",vertical:"simplebar-vertical",hover:"simplebar-hover",dragging:"simplebar-dragging",scrolling:"simplebar-scrolling",scrollable:"simplebar-scrollable",mouseEntered:"simplebar-mouse-entered"},scrollableNode:null,contentNode:null,autoHide:!0},e.getOptions=B,e.helpers=V,e}(),Y=X.helpers,F=Y.getOptions,I=Y.addClasses,$=function(t){function i(){for(var e=[],s=0;s<arguments.length;s++)e[s]=arguments[s];var r=t.apply(this,e)||this;return i.instances.set(e[0],r),r}return function(t,i){if("function"!=typeof i&&null!==i)throw new TypeError("Class extends value "+String(i)+" is not a constructor or null");function s(){this.constructor=t}e(t,i),t.prototype=null===i?Object.create(i):(s.prototype=i.prototype,new s)}(i,t),i.initDOMLoadedElements=function(){document.removeEventListener("DOMContentLoaded",this.initDOMLoadedElements),window.removeEventListener("load",this.initDOMLoadedElements),Array.prototype.forEach.call(document.querySelectorAll("[data-simplebar]"),(function(e){"init"===e.getAttribute("data-simplebar")||i.instances.has(e)||new i(e,F(e.attributes))}))},i.removeObserver=function(){var e;null===(e=i.globalObserver)||void 0===e||e.disconnect()},i.prototype.initDOM=function(){var e,t,i,s=this;if(!Array.prototype.filter.call(this.el.children,(function(e){return e.classList.contains(s.classNames.wrapper)})).length){for(this.wrapperEl=document.createElement("div"),this.contentWrapperEl=document.createElement("div"),this.offsetEl=document.createElement("div"),this.maskEl=document.createElement("div"),this.contentEl=document.createElement("div"),this.placeholderEl=document.createElement("div"),this.heightAutoObserverWrapperEl=document.createElement("div"),this.heightAutoObserverEl=document.createElement("div"),I(this.wrapperEl,this.classNames.wrapper),I(this.contentWrapperEl,this.classNames.contentWrapper),I(this.offsetEl,this.classNames.offset),I(this.maskEl,this.classNames.mask),I(this.contentEl,this.classNames.contentEl),I(this.placeholderEl,this.classNames.placeholder),I(this.heightAutoObserverWrapperEl,this.classNames.heightAutoObserverWrapperEl),I(this.heightAutoObserverEl,this.classNames.heightAutoObserverEl);this.el.firstChild;)this.contentEl.appendChild(this.el.firstChild);this.contentWrapperEl.appendChild(this.contentEl),this.offsetEl.appendChild(this.contentWrapperEl),this.maskEl.appendChild(this.offsetEl),this.heightAutoObserverWrapperEl.appendChild(this.heightAutoObserverEl),this.wrapperEl.appendChild(this.heightAutoObserverWrapperEl),this.wrapperEl.appendChild(this.maskEl),this.wrapperEl.appendChild(this.placeholderEl),this.el.appendChild(this.wrapperEl),null===(e=this.contentWrapperEl)||void 0===e||e.setAttribute("tabindex","0"),null===(t=this.contentWrapperEl)||void 0===t||t.setAttribute("role","region"),null===(i=this.contentWrapperEl)||void 0===i||i.setAttribute("aria-label",this.options.ariaLabel)}if(!this.axis.x.track.el||!this.axis.y.track.el){var r=document.createElement("div"),l=document.createElement("div");I(r,this.classNames.track),I(l,this.classNames.scrollbar),r.appendChild(l),this.axis.x.track.el=r.cloneNode(!0),I(this.axis.x.track.el,this.classNames.horizontal),this.axis.y.track.el=r.cloneNode(!0),I(this.axis.y.track.el,this.classNames.vertical),this.el.appendChild(this.axis.x.track.el),this.el.appendChild(this.axis.y.track.el)}X.prototype.initDOM.call(this),this.el.setAttribute("data-simplebar","init")},i.prototype.unMount=function(){X.prototype.unMount.call(this),i.instances.delete(this.el)},i.initHtmlApi=function(){this.initDOMLoadedElements=this.initDOMLoadedElements.bind(this),"undefined"!=typeof MutationObserver&&(this.globalObserver=new MutationObserver(i.handleMutations),this.globalObserver.observe(document,{childList:!0,subtree:!0})),"complete"===document.readyState||"loading"!==document.readyState&&!document.documentElement.doScroll?window.setTimeout(this.initDOMLoadedElements):(document.addEventListener("DOMContentLoaded",this.initDOMLoadedElements),window.addEventListener("load",this.initDOMLoadedElements))},i.handleMutations=function(e){e.forEach((function(e){e.addedNodes.forEach((function(e){1===e.nodeType&&(e.hasAttribute("data-simplebar")?!i.instances.has(e)&&document.documentElement.contains(e)&&new i(e,F(e.attributes)):e.querySelectorAll("[data-simplebar]").forEach((function(e){"init"!==e.getAttribute("data-simplebar")&&!i.instances.has(e)&&document.documentElement.contains(e)&&new i(e,F(e.attributes))})))})),e.removedNodes.forEach((function(e){1===e.nodeType&&("init"===e.getAttribute("data-simplebar")?i.instances.has(e)&&!document.documentElement.contains(e)&&i.instances.get(e).unMount():Array.prototype.forEach.call(e.querySelectorAll('[data-simplebar="init"]'),(function(e){i.instances.has(e)&&!document.documentElement.contains(e)&&i.instances.get(e).unMount()})))}))}))},i.instances=new WeakMap,i}(X);return t&&$.initHtmlApi(),$}();

(function ($) {
    'use strict';

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll('.dropdown-submenu').forEach(function (submenu) {
            submenu.addEventListener('mouseenter', function () {
                let menu = this.querySelector('.dropdown-menu');
                let rect = menu.getBoundingClientRect();
    
                if (rect.right > window.innerWidth) {
                    menu.style.right = "100%";
                    menu.style.left = "auto";
                }
            });
        });
    });

    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    toastr.options = {
        "preventDuplicates": true,
        "preventOpenDuplicates": true
    };

    $(document).on('click', '.change-global-method', function () {
        let global_country_id = $(this).data('id');
        // let global_currency_id = $('#global_currency_id').val();

        $('.location-loader').show();
        $(this).attr('disabled');
        
        $.post('/currency/change', {
            global_country_id: global_country_id,
            // global_currency_id: global_currency_id
        }, function (data) {
            location.reload();
        });
    })

    /*===================================*
    02. BACKGROUND IMAGE JS
    *===================================*/
    /*data image src*/
    $(".background_bg").each(function () {
        var attr = $(this).attr('data-img-src');
        if (typeof attr !== typeof undefined && attr !== false) {
            $(this).css('background-image', 'url(' + attr + ')');
        }
    });

// Global function to adjust font size based on available width
    function adjustFontSize() {
        var $productPrice = $('.product_price');
        var $delTag = $productPrice.find('del');
        var $priceSpan = $productPrice.find('.price');

        if ($productPrice.length === 0 || $delTag.length === 0 || $priceSpan.length === 0) {
            console.log("Element(s) not found.");
            return;
        }

        // Get container and price widths
        var containerWidth = $productPrice.width();
        var priceWidth = $priceSpan.outerWidth();
        var availableWidth = containerWidth - priceWidth - 10;

        var fontSize = 13;
        var minFontSize = 8;

        // Check if <del> tag content is overflowing
        function isOverflowing() {
            $delTag.css('font-size', fontSize + 'px'); // Set the current font size
            var scrollWidth = $delTag[0].scrollWidth; // Get the scrollWidth
            return scrollWidth > availableWidth; // Return true if overflowing
        }

        // Reduce font size dynamically until content fits
        while (isOverflowing() && fontSize > minFontSize) {
            fontSize -= 1;
        }

        // Apply the final font size
        $delTag.css('font-size', fontSize + 'px');
    }

    // Call adjustFontSize function on page load
    $(document).ready(function () {
        setTimeout(adjustFontSize, 2800);
    });

    // Adjust font size when window is resized
    $(window).resize(function () {
        adjustFontSize();
    });

    /*===================================*
    03. ANIMATION JS
    *===================================*/
    $(function () {

        function ckScrollInit(items, trigger) {
            items.each(function () {
                var ckElement = $(this),
                    AnimationClass = ckElement.attr('data-animation'),
                    AnimationDelay = ckElement.attr('data-animation-delay');

                ckElement.css({
                    '-webkit-animation-delay': AnimationDelay,
                    '-moz-animation-delay': AnimationDelay,
                    'animation-delay': AnimationDelay,
                    opacity: 0
                });

                var ckTrigger = (trigger) ? trigger : ckElement;

                ckTrigger.waypoint(function () {
                    ckElement.addClass("animated").css("opacity", "1");
                    ckElement.addClass('animated').addClass(AnimationClass);
                }, {
                    triggerOnce: true,
                    offset: '90%',
                });
            });
        }

        ckScrollInit($('.animation'));
        ckScrollInit($('.staggered-animation'), $('.staggered-animation-wrap'));

    });

    /*===================================*
    04. MENU JS
    *===================================*/
    //Main navigation scroll spy for shadow
    $(window).on('scroll', function () {
        var scroll = $(window).scrollTop();

        if (scroll >= 150) {
            $('header.fixed-top').addClass('nav-fixed');
        } else {
            $('header.fixed-top').removeClass('nav-fixed');
        }

    });

    

    //Show Hide dropdown-menu Main navigation
    // $(document).ready(function () {
    //     $('.dropdown-menu a.dropdown-toggler').on('click', function () {
    //         //var $el = $( this );
    //         //var $parent = $( this ).offsetParent( ".dropdown-menu" );
    //         if (!$(this).next().hasClass('show')) {
    //             $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
    //         }
    //         var $subMenu = $(this).next(".dropdown-menu");
    //         $subMenu.toggleClass('show');

    //         $(this).parent("li").toggleClass('show');

    //         $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function () {
    //             $('.dropdown-menu .show').removeClass("show");
    //         });

    //         return false;
    //     });
    // });

    //Hide Navbar Dropdown After Click On Links
    // var navBar = $(".header_wrap");
    // var navbarLinks = navBar.find(".navbar-collapse ul li a.page-scroll");

    // $.each(navbarLinks, function () {

    //     var navbarLink = $(this);

    //     navbarLink.on('click', function () {
    //         navBar.find(".navbar-collapse").collapse('hide');
    //         $("header").removeClass("active");
    //     });

    // });

    //Main navigation Active Class Add Remove
    $('.navbar-toggler').on('click', function () {
        $("header").toggleClass("active");
        if ($('.search-overlay').hasClass('open')) {
            $(".search-overlay").removeClass('open');
            $(".search_trigger").removeClass('open');
        }
    });

    $(window).on('load', function () {
        if ($(".header_wrap").length > 0) {
            if ($('.header_wrap').hasClass("fixed-top") && !$('.header_wrap').hasClass("transparent_header") && !$('.header_wrap').hasClass("no-sticky")) {
                $(".header_wrap").before('<div class="header_sticky_bar d-none"></div>');
            }
        }
    });

    var homePage = $('#isHomePage').val();

    // $(window).on('scroll', function () {
    //     var scroll = $(window).scrollTop();

    //     if (scroll >= 250) {
    //         $('.header_sticky_bar').removeClass('d-none');
    //         $('header.no-sticky').removeClass('nav-fixed');

    //         if (homePage == 1) {
    //             $('#navCatContent').removeClass('nav_cat');
    //         }

    //     } else {
    //         $('.header_sticky_bar').addClass('d-none');

    //         if (homePage == 1) {
    //             $('#navCatContent').addClass('nav_cat');
    //         }
    //     }
    // });

    $(document).on('click', '#logout', function (e) {
        e.preventDefault();
        var url = $(this).data('url');
        $(this).html('Loading...');

        $.ajax({
            url: url,
            method: 'POST',
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {
                setTimeout(function () {
                    window.location.href = data.goto;
                }, 2000);
            },
            error: function (data) {
                var jsonValue = $.parseJSON(data.responseText);
                const errors = jsonValue.errors
                var i = 0;
                $.each(errors, function (key, value) {
                    toastr.success(value);
                    i++;
                });
            }
        });
    });

    $(document).on('keyup', '.number', function () {
        let value = $(this).val();
        $(this).val(allowOnlyNumbers(value));
    });

    $('.select').select2({
        width: '100%'
    });

    $(document).on('click', '.system-selector', function () {
        $('#globalSelector').css('display', 'block');
    })

    $(document).on('click', '.close-global-selector', function () {
        $('#globalSelector').css('display', 'none');
    })

    function allowOnlyNumbers(input) {
        return input.replace(/\D/g, '');
    }

    $(document).on('click', '.close', function () {
        $('#m-cart').removeClass('open');
        $('#m-cart').fadeOut();
        $('.overlay-loader').removeClass('open');
        $("body").removeClass('no-scroll')
    })

    var setHeight = function () {
        var height_header = $(".header_wrap").height();
        $('.header_sticky_bar').css({'height': height_header});
    };

    $(window).on('load', function () {
        setHeight();
    });

    $(window).on('resize', function () {
        setHeight();
    });

    $('.sidetoggle').on('click', function () {
        $(this).addClass('open');
        $('body').addClass('sidetoggle_active');
        $('.sidebar_menu').addClass('active');
        $("body").append('<div id="header-overlay" class="header-overlay"></div>');
    });

    $(document).on('click', '#header-overlay, .sidemenu_close', function () {
        $('.sidetoggle').removeClass('open');
        $('body').removeClass('sidetoggle_active');
        $('.sidebar_menu').removeClass('active');
        $('#header-overlay').fadeOut('3000', function () {
            $('#header-overlay').remove();
        });
        return false;
    });

    $(".categories_btn").on('click', function () {
        $('.side_navbar_toggler').attr('aria-expanded', 'false');
        $('#navbarSidetoggle').removeClass('show');
    });

    $(".side_navbar_toggler").on('click', function () {
        $('.categories_btn').attr('aria-expanded', 'false');
        $('#navCatContent').removeClass('show');
    });

    $(".pr_search_trigger").on('click', function () {
        $(this).toggleClass('show');
        $('.product_search_form').toggleClass('show');
    });

    var rclass = true;

    $("html").on('click', function () {
        if (rclass) {
            $('.categories_btn').addClass('collapsed');
            $('.categories_btn,.side_navbar_toggler').attr('aria-expanded', 'false');
            $('#navCatContent,#navbarSidetoggle').removeClass('show');
        }
        rclass = true;
    });

    $(".categories_btn,#navCatContent,#navbarSidetoggle .navbar-nav,.side_navbar_toggler").on('click', function () {
        rclass = false;
    });

    /*===================================*
    05. SMOOTH SCROLLING JS
    *===================================*/
    // Select all links with hashes

    var topheaderHeight = $(".top-header").innerHeight();
    var mainheaderHeight = $(".header_wrap").innerHeight();
    var headerHeight = mainheaderHeight - topheaderHeight - 20;
    $('a.page-scroll[href*="#"]:not([href="#"])').on('click', function () {
        $('a.page-scroll.active').removeClass('active');
        $(this).closest('.page-scroll').addClass('active');
        // On-page links
        if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
            // Figure out element to scroll to
            var target = $(this.hash),
                speed = $(this).data("speed") || 800;
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

            // Does a scroll target exist?
            if (target.length) {
                // Only prevent default if animation is actually gonna happen
                event.preventDefault();
                $('html, body').animate({
                    scrollTop: target.offset().top - headerHeight
                }, speed);
            }
        }
    });
    $(window).on('scroll', function () {
        var lastId,
            // All list items
            menuItems = $(".header_wrap").find("a.page-scroll"),
            topMenuHeight = $(".header_wrap").innerHeight() + 20,
            // Anchors corresponding to menu items
            scrollItems = menuItems.map(function () {
                var items = $($(this).attr("href"));
                if (items.length) {
                    return items;
                }
            });
        var fromTop = $(this).scrollTop() + topMenuHeight;

        // Get id of current scroll item
        var cur = scrollItems.map(function () {
            if ($(this).offset().top < fromTop)
                return this;
        });
        // Get the id of the current element
        cur = cur[cur.length - 1];
        var id = cur && cur.length ? cur[0].id : "";

        if (lastId !== id) {
            lastId = id;
            // Set/remove active class
            menuItems.closest('.page-scroll').removeClass("active").end().filter("[href='#" + id + "']").closest('.page-scroll').addClass("active");
        }

    });

    $('.more_slide_open').slideUp();
    $('.more_categories').on('click', function () {
        $(this).toggleClass('show');
        $('.more_slide_open').slideToggle();
    });

    /*===================================*
    06. SEARCH JS
    *===================================*/

    $(".close-search").on("click", function () {
        $(".search_wrap,.search_overlay").removeClass('open');
        $("body").removeClass('search_open');
    });

    var removeClass = true;
    $(".search_wrap").after('<div class="search_overlay"></div>');
    $(".search_trigger").on('click', function () {
        $(".search_wrap,.search_overlay").toggleClass('open');
        $("body").toggleClass('search_open');
        removeClass = false;
        if ($('.navbar-collapse').hasClass('show')) {
            $(".navbar-collapse").removeClass('show');
            $(".navbar-toggler").addClass('collapsed');
            $(".navbar-toggler").attr("aria-expanded", false);
        }
    });
    $(".search_wrap form").on('click', function () {
        removeClass = false;
    });
    $("html").on('click', function () {
        if (removeClass) {
            $("body").removeClass('open');
            $(".search_wrap,.search_overlay").removeClass('open');
            $("body").removeClass('search_open');
        }
        removeClass = true;
    });

    /*===================================*
    07. SCROLLUP JS
    *===================================*/
    $(window).on('scroll', function () {
        if ($(this).scrollTop() > 150) {
            $('.scrollup').fadeIn();
        } else {
            $('.scrollup').fadeOut();
        }
    });

    $(".scrollup").on('click', function (e) {
        e.preventDefault();
        $('html, body').animate({
            scrollTop: 0
        }, 600);
        return false;
    });

    /*===================================*
    08. PARALLAX JS
    *===================================*/
    // $(window).on('load', function() {
    //     $('.parallax_bg').parallaxBackground();
    // });

    /*===================================*
    09. MASONRY JS
    *===================================*/
    // $( window ).on( "load", function() {
    // 	var $grid_selectors  = $(".grid_container");
    // 	var filter_selectors = ".grid_filter > li > a";
    // 	if( $grid_selectors.length > 0 ) {
    // 		$grid_selectors.imagesLoaded(function(){
    // 			if ($grid_selectors.hasClass("masonry")){
    // 				$grid_selectors.isotope({
    // 					itemSelector: '.grid_item',
    // 					percentPosition: true,
    // 					layoutMode: "masonry",
    // 					masonry: {
    // 						columnWidth: '.grid-sizer'
    // 					},
    // 				});
    // 			}
    // 			else {
    // 				$grid_selectors.isotope({
    // 					itemSelector: '.grid_item',
    // 					percentPosition: true,
    // 					layoutMode: "fitRows",
    // 				});
    // 			}
    // 		});
    // 	}

    // 	//isotope filter
    // 	$(document).on( "click", filter_selectors, function() {
    // 		$(filter_selectors).removeClass("current");
    // 		$(this).addClass("current");
    // 		var dfselector = $(this).data('filter');
    // 		if ($grid_selectors.hasClass("masonry")){
    // 			$grid_selectors.isotope({
    // 				itemSelector: '.grid_item',
    // 				layoutMode: "masonry",
    // 				masonry: {
    // 					columnWidth: '.grid_item'
    // 				},
    // 				filter: dfselector
    // 			});
    // 		}
    // 		else {
    // 			$grid_selectors.isotope({
    // 				itemSelector: '.grid_item',
    // 				layoutMode: "fitRows",
    // 				filter: dfselector
    // 			});
    // 		}
    // 		return false;
    // 	});

    $('#search').on('keyup', function () {
        search();
    });

    $('#search').on('focus', function () {
        search();
    });
    
    $(document).on('keyup', '#mobile-search', function () {
        search(true);
    });

    $(document).on('focus', '#mobile-search', function () {
        search(true);
    });

    //Voice Search
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('search');
        const speechBtn = document.getElementById('speechBtn');
        const audio = document.getElementById('notificationAudio');

        // Check if SpeechRecognition is supported
        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

        if (!SpeechRecognition) {
            toastr.warning('Speech Recognition is not supported by your browser.', 'Warning');
            return;
        }

        const recognition = new SpeechRecognition();
        recognition.lang = 'en-US';
        recognition.interimResults = false;
        recognition.maxAlternatives = 1;

        speechBtn.addEventListener('click', function () {
            if (speechBtn.classList.contains('active')) {
                recognition.stop();
                speechBtn.classList.remove('active');

                if (audio) {
                    audio.currentTime = 0.05;
                    audio.play().catch(error => console.error('Audio play error:', error));
                }

                speechBtn.style.setProperty('color', 'var(--primary-color)');
                speechBtn.style.setProperty('background-color', 'darkslategrey');
                setTimeout(() => {
                    speechBtn.style.removeProperty('background-color');
                    speechBtn.style.removeProperty('color');
                }, 1200);

                // toastr.success('Speech recognition stopped.');
            } else {
                recognition.start();
                if (audio) {
                    audio.currentTime = 0.05;
                    audio.play().catch(error => console.error('Audio play error:', error));
                }
                speechBtn.classList.add('active');
            }
        });


        recognition.onresult = function (event) {
            speechBtn.classList.remove('active');


            const transcript = event.results[0][0].transcript;


            searchInput.value = transcript;
            if (transcript) {
                search();
            }
        };

        recognition.onspeechend = function () {
            recognition.stop();
            speechBtn.classList.remove('active');

        };

        recognition.onerror = function (event) {
            speechBtn.classList.remove('active');
            // toastr.error('Speech recognition failed. Please try again.', 'Error');
            console.error('Speech recognition error:', event.error);
        };
    });

    function search(mobile = false) {
        var searchKey = $('#search').val();

        if(mobile == true) {
            var searchKey = $('#mobile-search').val();
        }

        if (searchKey.length > 0) {

            $('.search-content').html(null);
            $('.typed-search-box').removeClass('d-none');
            $('.searching-preloader').removeClass('d-none');
            $.post('/ajax-search', {
                search_module: 'ajax_search',
                search: searchKey
            }, function (data) {
                if (data == '0') {
                    $('.search-content').html(null);
                    $('.typed-search-box .search-nothing').removeClass('d-none').html('Sorry, nothing found for <strong>"' + searchKey + '"</strong>');
                    $('.searching-preloader').addClass('d-none');

                } else {
                    $('.typed-search-box .search-nothing').addClass('d-none').html(null);
                    $('.search-content').html(data);
                    $('.searching-preloader').addClass('d-none');
                }
            });
        } else {
            $('.typed-search-box').addClass('d-none');
        }
    }

    // 	$('.portfolio_filter').on('change', function() {
    // 		$grid_selectors.isotope({
    // 		  filter: this.value
    // 		});
    // 	});

    // 	$(window).on("resize", function () {
    // 		setTimeout(function () {
    // 			$grid_selectors.find('.grid_item').removeClass('animation').removeClass('animated'); // avoid problem to filter after window resize
    // 			$grid_selectors.isotope('layout');
    // 		}, 300);
    // 	});
    // });

    $('.link_container').each(function () {
        $(this).magnificPopup({
            delegate: '.image_popup',
            type: 'image',
            mainClass: 'mfp-zoom-in',
            removalDelay: 500,
            gallery: {
                enabled: true
            }
        });
    });

    /*===================================*
    10. SLIDER JS
    *===================================*/
    function carousel_slider() {
        $('.carousel_slider').each(function () {
            var $carousel = $(this);
            $carousel.owlCarousel({
                dots: $carousel.data("dots"),
                loop: $carousel.data("loop"),
                items: $carousel.data("items"),
                margin: $carousel.data("margin"),
                mouseDrag: $carousel.data("mouse-drag"),
                touchDrag: $carousel.data("touch-drag"),
                autoHeight: $carousel.data("autoheight"),
                center: $carousel.data("center"),
                nav: $carousel.data("nav"),
                rewind: $carousel.data("rewind"),
                navText: ['<i class="fas fa-angle-left"></i>', '<i class="fas fa-angle-right"></i>'],
                autoplay: $carousel.data("autoplay"),
                animateIn: $carousel.data("animate-in"),
                animateOut: $carousel.data("animate-out"),
                autoplayTimeout: $carousel.data("autoplay-timeout"),
                smartSpeed: $carousel.data("smart-speed"),
                responsive: $carousel.data("responsive")
            });
        });
    }

    $('.features').each(function () {
        var $slick_carousel = $(this);
        $slick_carousel.not('.slick-initialized').slick({
            arrows: $slick_carousel.data("arrows"),
            dots: $slick_carousel.data("dots"),
            infinite: $slick_carousel.data("infinite"),
            centerMode: $slick_carousel.data("center-mode"),
            vertical: $slick_carousel.data("vertical"),
            fade: $slick_carousel.data("fade"),
            cssEase: $slick_carousel.data("css-ease"),
            autoplay: $slick_carousel.data("autoplay"),
            verticalSwiping: $slick_carousel.data("vertical-swiping"),
            autoplaySpeed: $slick_carousel.data("autoplay-speed"),
            speed: $slick_carousel.data("speed"),
            pauseOnHover: $slick_carousel.data("pause-on-hover"),
            draggable: $slick_carousel.data("draggable"),
            slidesToShow: $slick_carousel.data("slides-to-show"),
            slidesToScroll: $slick_carousel.data("slides-to-scroll"),
            asNavFor: $slick_carousel.data("as-nav-for"),
            focusOnSelect: $slick_carousel.data("focus-on-select"),
            responsive: $slick_carousel.data("responsive")
        });
    });

    function slick_slider() {
        $('.slick_slider').each(function () {
            var $slick_carousel = $(this);
            $slick_carousel.not('.slick-initialized').slick({
                arrows: $slick_carousel.data("arrows"),
                dots: $slick_carousel.data("dots"),
                infinite: $slick_carousel.data("infinite"),
                centerMode: $slick_carousel.data("center-mode"),
                vertical: $slick_carousel.data("vertical"),
                fade: $slick_carousel.data("fade"),
                cssEase: $slick_carousel.data("css-ease"),
                autoplay: $slick_carousel.data("autoplay"),
                verticalSwiping: $slick_carousel.data("vertical-swiping"),
                autoplaySpeed: $slick_carousel.data("autoplay-speed"),
                speed: $slick_carousel.data("speed"),
                pauseOnHover: $slick_carousel.data("pause-on-hover"),
                draggable: $slick_carousel.data("draggable"),
                slidesToShow: $slick_carousel.data("slides-to-show"),
                slidesToScroll: $slick_carousel.data("slides-to-scroll"),
                asNavFor: $slick_carousel.data("as-nav-for"),
                focusOnSelect: $slick_carousel.data("focus-on-select"),
                responsive: $slick_carousel.data("responsive")
            });
        });
    }

    /*===================================*
    11. CONTACT FORM JS
    *===================================*/
    $("#submitButton").on("click", function (event) {
        event.preventDefault();
        var mydata = $("form").serialize();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "contact.php",
            data: mydata,
            success: function (data) {
                if (data.type === "error") {
                    $("#alert-msg").removeClass("alert, alert-success");
                    $("#alert-msg").addClass("alert, alert-danger");
                } else {
                    $("#alert-msg").addClass("alert, alert-success");
                    $("#alert-msg").removeClass("alert, alert-danger");
                    $("#first-name").val("Enter Name");
                    $("#email").val("Enter Email");
                    $("#phone").val("Enter Phone Number");
                    $("#subject").val("Enter Subject");
                    $("#description").val("Enter Message");

                }
                $("#alert-msg").html(data.msg);
                $("#alert-msg").show();
            },
            error: function (xhr, textStatus) {
                alert(textStatus);
            }
        });
    });

    /*===================================*
    12. POPUP JS
    *===================================*/
    // $('.content-popup').magnificPopup({
    // 	type: 'inline',
    // 	preloader: true,
    // 	mainClass: 'mfp-zoom-in',
    // });

    $('.image_gallery').each(function () { // the containers for all your galleries
        $(this).magnificPopup({
            delegate: 'a', // the selector for gallery item
            type: 'image',
            gallery: {
                enabled: true,
            },
        });
    });

    // $('.video_popup, .iframe_popup').magnificPopup({
    // 	type: 'iframe',
    // 	removalDelay: 160,
    // 	mainClass: 'mfp-zoom-in',
    // 	preloader: false,
    // 	fixedContentPos: false
    // });

    /*===================================*
    13. Select dropdowns
    *===================================*/

    if ($('select').length) {
        // Traverse through all dropdowns
        $.each($('select'), function (i, val) {
            var $el = $(val);

            if ($el.val() === "") {
                $el.addClass('first_null');
            }

            if (!$el.val()) {
                $el.addClass('not_chosen');
            }

            $el.on('change', function () {
                if (!$el.val())
                    $el.addClass('not_chosen');
                else
                    $el.removeClass('not_chosen');
            });

        });
    }

    /*==============================================================
    14. FIT VIDEO JS
    ==============================================================*/
    if ($(".fit-videos").length > 0) {
        $(".fit-videos").fitVids({
            customSelector: "iframe[src^='https://w.soundcloud.com']"
        });
    }

    /*==============================================================
    15. DROPDOWN JS
    ==============================================================*/
    if ($(".custome_select").length > 0) {
        $(document).ready(function () {
            $(".custome_select").msDropdown();
        });
    }

    /*===================================*
    16.MAP JS
    *===================================*/
    if ($("#map").length > 0) {
        google.maps.event.addDomListener(window, 'load', init);
    }

    var map_selector = $('#map');

    function init() {

        var mapOptions = {
            zoom: map_selector.data("zoom"),
            mapTypeControl: false,
            center: new google.maps.LatLng(map_selector.data("latitude"), map_selector.data("longitude")), // New York
        };
        var mapElement = document.getElementById('map');
        var map = new google.maps.Map(mapElement, mapOptions);
        var marker = new google.maps.Marker({
            position: new google.maps.LatLng(map_selector.data("latitude"), map_selector.data("longitude")),
            map: map,
            icon: map_selector.data("icon"),

            title: map_selector.data("title"),
        });
        marker.setAnimation(google.maps.Animation.BOUNCE);
    }


    /*===================================*
    17. COUNTDOWN JS
    *===================================*/
    // $('.countdown_time').each(function() {
    //     var endTime = $(this).data('time');
    //     $(this).countdown(endTime, function(tm) {
    //         $(this).html(tm.strftime('<div class="countdown_box"><div class="countdown-wrap"><span class="countdown days">%D </span><span class="cd_text">Days</span></div></div><div class="countdown_box"><div class="countdown-wrap"><span class="countdown hours">%H</span><span class="cd_text">Hours</span></div></div><div class="countdown_box"><div class="countdown-wrap"><span class="countdown minutes">%M</span><span class="cd_text">Minutes</span></div></div><div class="countdown_box"><div class="countdown-wrap"><span class="countdown seconds">%S</span><span class="cd_text">Seconds</span></div></div>'));
    //     });
    // });

    /*===================================*
    18. List Grid JS
    *===================================*/
    $('.shorting_icon').on('click', function () {
        if ($(this).hasClass('grid')) {
            $('.shop_container').removeClass('list').addClass('grid');
            $(this).addClass('active').siblings().removeClass('active');
        } else if ($(this).hasClass('list')) {
            $('.shop_container').removeClass('grid').addClass('list');
            $(this).addClass('active').siblings().removeClass('active');
        }
        $(".shop_container").append('<div class="loading_pr"><div class="mfp-preloader"></div></div>');
        setTimeout(function () {
            $('.loading_pr').remove();
            //$container.isotope('layout');
        }, 800);
    });

    /*===================================*
    19. TOOLTIP JS
    *===================================*/
    $(function () {
        $('[data-toggle="tooltip"]').tooltip({
            trigger: 'hover',
        });
    });
    $(function () {
        $('[data-toggle="popover"]').popover();
    });

    /*===================================*
    20. PRODUCT COLOR JS
    *===================================*/
    function product_color_switch() {
        $('.product_color_switch span').each(function () {
            var get_color = $(this).attr('data-color');
            $(this).css("background-color", get_color);
        });

        $('.product_color_switch span,.product_size_switch span').on("click", function () {
            $(this).siblings(this).removeClass('active').end().addClass('active');
        });
    }

    /*Product quantity js*/
    function pluseminus() {
        $('.plus').on('click', function () {
            if ($(this).prev().val()) {
                $(this).prev().val(+$(this).prev().val() + 1);
            }
        });
        $('.minus').on('click', function () {
            if ($(this).next().val() > 1) {
                if ($(this).next().val() > 1) $(this).next().val(+$(this).next().val() - 1);
            }
        });
    }


    /*===================================*
    21. QUICKVIEW POPUP + ZOOM IMAGE + PRODUCT SLIDER JS
    *===================================*/
    function galleryZoomProduct() {
        var image = $('#product_img');
        //var zoomConfig = {};
        var zoomActive = false;

        zoomActive = !zoomActive;
        if (zoomActive) {
            if ($(image).length > 0) {
                $(image).elevateZoom({
                    cursor: "crosshair",
                    easing: true,
                    gallery: 'pr_item_gallery',
                    zoomType: "inner",
                    galleryActiveClass: "active"
                });
            }
        } else {
            $.removeData(image, 'elevateZoom');//remove zoom instance from image
            $('.zoomContainer:last-child').remove();// remove zoom container from DOM
        }

        $.magnificPopup.defaults.callbacks = {
            open: function () {
                $('body').addClass('zoom_image');
            },
            close: function () {
                // Wait until overflow:hidden has been removed from the html tag
                setTimeout(function () {
                    $('body').removeClass('zoom_image');
                    $('body').removeClass('zoom_gallery_image');
                    //$('.zoomContainer:last-child').remove();// remove zoom container from DOM
                    $('.zoomContainer').slice(1).remove();
                }, 100);
            }
        };

        // Set up gallery on click
        var galleryZoom = $('#pr_item_gallery');
        galleryZoom.magnificPopup({
            delegate: 'a',
            type: 'image',
            gallery: {
                enabled: true
            },
            callbacks: {
                elementParse: function (item) {
                    item.src = item.el.attr('data-zoom-image');
                }
            }
        });

        // Zoom image when click on icon
        $('.product_img_zoom').on('click', function () {
            var actual = $('.pr_item_gallery a').attr('data-zoom-image');
            $('body').addClass('zoom_gallery_image');
            $('#pr_item_gallery .item').each(function () {
                if (actual == $(this).find('.product_gallery_item').attr('data-zoom-image')) {
                    return galleryZoom.magnificPopup('open', $(this).index());
                }
            });
        });
    }


    /*INIT JS*/
    $(document).ready(function () {
        pluseminus();
        product_color_switch();
        galleryZoomProduct();
        carousel_slider();
        slick_slider();
        ajax_magnificPopup();
    });


    /*===================================*
    22. PRICE FILTER JS
    *===================================*/
    $('#price_filter').each(function () {
        var $filter_selector = $(this);
        var a = $filter_selector.data("min-value");
        var b = $filter_selector.data("max-value");
        var c = $filter_selector.data("price-sign");
        $filter_selector.slider({
            range: true,
            min: $filter_selector.data("min"),
            max: $filter_selector.data("max"),
            values: [a, b],
            slide: function (event, ui) {
                $("#flt_price").html(c + ui.values[0] + " - " + c + ui.values[1]);
                $("#price_first").val(ui.values[0]);
                $("#price_second").val(ui.values[1]);
            }
        });
        $("#flt_price").html(c + $filter_selector.slider("values", 0) + " - " + c + $filter_selector.slider("values", 1));
    });

    /*===================================*
    23. RATING STAR JS
    *===================================*/
    $(document).ready(function () {
        $('.star_rating span').on('click', function () {
            var onStar = parseFloat($(this).data('value'), 10); // The star currently selected
            $('.star_rating_field').val(onStar);
            var stars = $(this).parent().children('.star_rating span');
            for (var i = 0; i < stars.length; i++) {
                $(stars[i]).removeClass('selected');
            }
            for (i = 0; i < onStar; i++) {
                $(stars[i]).addClass('selected');
            }
        });
    });

    /*===================================*
    24. CHECKBOX CHECK THEN ADD CLASS JS
    *===================================*/
    $('.create-account,.different_address').hide();
    $('#createaccount:checkbox').on('change', function () {
        if ($(this).is(":checked")) {
            $('.create-account').slideDown();
        } else {
            $('.create-account').slideUp();
        }
    });
    $('#differentaddress:checkbox').on('change', function () {
        if ($(this).is(":checked")) {
            $('.different_address').slideDown();
        } else {
            $('.different_address').slideUp();
        }
    });

    /*===================================*
    25. Cart Page Payment option
    *===================================*/
    $(document).ready(function () {
        $('[name="payment_option"]').on('change', function () {
            var $value = $(this).attr('value');
            $('.payment-text').slideUp();
            $('[data-method="' + $value + '"]').slideDown();
        });
    });

    /*===================================*
    26. ONLOAD POPUP JS
    *===================================*/

    $(window).on('load',function(){
        $("#login_popup").modal('show', {}, 500);
    });

    // $(".login_popup").modal('show', {}, 500);

    // mobile listing menu open
    $(document).on('click', '#lc-toggle', function () {
        $('#column-left').addClass('open');
        $('.overlay-loader').addClass('open');
        $("body").addClass('no-scroll')
    })

    // mobile listing menu close
    $(document).on('click', '.lc-close', function () {
        $('#column-left').removeClass('open');
        $('.overlay-loader').removeClass('open');
        $("body").removeClass('no-scroll');
    });

    // Product Add to Compare List
    $(document).on('click', '.add_compare', function () {
        let id = $(this).data('id');
        $(this).html('<i class="fas fa-spin fa-spinner"></i>');
        $.ajax({
            url: '/add-to-compare-list',
            method: 'POST',
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (data) {
                if (data.status) {
                    $('.compare_counter').html(data.counter);
                    toastr.success(data.message);
                } else {
                    toastr.warning(data.message);
                }
                $('.add_compare').html('<i class="fas fa-random"></i>');
            }
        });
    });

    // Product Add to Wish List
    $(document).on('click', '.add_wishlist', function () {
        let id = $(this).data('id');
        let wish_list_counter = parseInt($('#wish_list_counter').html());
        $(this).html('<i class="fas fa-spin fa-spinner"></i>');
        $.ajax({
            url: '/add-to-wish-list',
            method: 'POST',
            data: {
                id: id
            },
            dataType: 'JSON',
            success: function (data) {
                if (data.status) {
                    $('#wish_list_counter').html(parseInt(wish_list_counter) + 1);
                    toastr.success(data.message);
                } else {
                    toastr.warning(data.message);
                }
                $('.add_wishlist').html('<i class="far fa-heart"></i>');
            }
        })
    })

    $(document).on('click', '.add-to-cart', function () {
        // Get product data
        let productSlug = $(this).data('id');
        let quantity = $('#product-' + productSlug).val();

        if (quantity === undefined) {
            quantity = 1;
        }

        $(this).html('<i class="fas fa-spin fa-spinner"></i>');

        $.ajax({
            url: '/cart/add',
            method: 'POST',
            data: {
                slug: productSlug,
                quantity: quantity
            },
            dataType: 'JSON',
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    $('.cart_count').show();
                    $('.cart_count').html(response.counter);
                    $('#cart-total-quantity').text(response.total_quantity);
                    $('#cart-total-price').text(response.total_price);

                    if (response.counter) {
                        let counterDiv = $('.cart-container .counter');
                        counterDiv.text(response.counter);
                    }
                } else {
                    toastr.warning(response.message);
                }

                $('.add-to-cart').html('<i class="fas fa-shopping-bag"></i> Add to Cart');
            },
            error: function (error) {
                toastr.error("Something went wrong! Please try again");

                $('.add-to-cart').html('<i class="fas fa-shopping-bag"></i> Add to Cart');
            }
        });
    });

    function ajax_magnificPopup() {
        $('.popup-ajax').magnificPopup({
            type: 'ajax',
            callbacks: {
                ajaxContentAdded: function () {
                    pluseminus();
                    product_color_switch();
                    galleryZoomProduct();
                    slick_slider();
                    carousel_slider();
                }
            }
        });
    }

    $(document).on('click', '.cart-container', function () {
        // $('#m-cart').addClass('open');
        // $('#m-cart').fadeIn();
        // $('.overlay-loader').addClass('open');

        getCartItems('main-cart-area');
    });

    function getCartItems(showArea) {
        $.ajax({
            url: '/get-cart-items',
            method: 'POST',
            data: {
                show: showArea
            },
            dataType: 'JSON',
            success: function (data) {
                if (showArea == 'main-cart-area') {
                    $('.cart-content').html(data.content);
                    $('.amount').html(data.total_price);
                    if (data.counter > 0) {
                        $('.checkout-btn').show();
                    }
                } else {
                    $('.cart_count').show();
                    $('.cart_total_price').html(data.total_price);
                    $('.cart_count').html(data.counter);
                    $('.cart-container .counter').html(data.counter);
                    $('.mobile_cart_list').html(data.content);
                    if (data.counter > 0) {
                        $('.cart_footer').show();
                    }
                    $('.cart-loader').hide();
                }
            }
        })
    }

    getCartItems();

    function removeCartItems(id, showArea = null, load = false, isCartPage = false) {
        $.ajax({
            url: '/remove-cart-items',
            method: 'DELETE',
            data: {
                show: showArea,
                id: id
            },
            dataType: 'JSON',
            success: function (data) {
                if (data.status) {
                    toastr.success(data.message);
                    if (showArea == 'main-cart-area') {
                        $('.cart_count').show();
                        $('.cart_count').html(data.counter);
                        $('.cart-content').html(data.content);
                        $('.amount').html(data.total_price);
                        if (data.counter > 0) {
                            $('.checkout-btn').show();
                        } else {
                            $('.cart-buttons-area').hide();
                        }
                        if (data.counter) {
                            let counterDiv = $('.cart-container .counter');
                            counterDiv.text(data.counter);
                        }
                        if (isCartPage) {
                            $(`.maincartPage a[data-id="${id}"]`).closest('tr').remove();
                        }
                    } else {
                        $('.cart_total_price').html(data.total_price);
                        $('.cart-container .counter').html(data.counter);
                        $('.mobile_cart_list').html(data.content);
                        if (data.counter > 0) {
                            $('.cart_footer').show();
                        }
                        $('.cart-loader').hide();
                    }
                } else {
                    toastr.error(data.message);
                }

                if (data.load) {
                    window.location.href = "";
                }
            }
        })
    }

    $(document).on('click', '.remove-item-from-cart', function () {
        let id = $(this).data('id');
        let load = false;
        if ($(this).data('load')) {
            load = true;
        }
        $(this).html('<i class="fas fa-spin fa-spinner"></i>')
        removeCartItems(id, 'main-cart-area', load);
    });

    $(document).on('click', '.remove-item-from-cart-main', function () {
        let id = $(this).data('id');
        let load = false;
        if ($(this).data('load')) {
            load = true;
        }
        $(this).html('<i class="fas fa-spin fa-spinner"></i>')
        removeCartItems(id, 'main-cart-area', load, true);
    });

    document.addEventListener('DOMContentLoaded', function () {
        const dropdownLinks = document.querySelectorAll('.dropdown-item.dropdown-toggler');

        dropdownLinks.forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault(); // Default behavior (dropdown) বন্ধ করা
                window.location.href = this.getAttribute('href'); // লিংকে রিডাইরেক্ট করা
            });
        });
    });


})(jQuery);

var _newsletterFormValidation = function () {
    $('#newsletter_submit').show();
    $('#newsletter_submitting').hide();

    if ($('#newsletter-form').length > 0) {
        $('#newsletter-form').parsley().on('field:validated', function () {
            var ok = $('.parsley-error').length === 0;
            $('.bs-callout-info').toggleClass('hidden', !ok);
            $('.bs-callout-warning').toggleClass('hidden', ok);
        });
    }

    $('#newsletter-form').on('submit', function (e) {
        e.preventDefault();

        $('#newsletter_submit').hide();
        $('#newsletter_submitting').show();

        $(".ajax_error").remove();

        var submit_url = $('#newsletter-form').attr('action');
        var formData = new FormData($("#newsletter-form")[0]);

        $.ajax({
            url: submit_url,
            type: 'POST',
            data: formData,
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'JSON',
            success: function (data) {
                if (!data.status) {
                    if (data.validator) {
                        for (const [key, messages] of Object.entries(data.message)) {
                            messages.forEach(message => {
                                toastr.error(message);
                            });
                        }
                    } else {
                        toastr.warning(data.message);
                    }
                } else {
                    toastr.success(data.message);

                    $('#newsletter-form')[0].reset();
                    if (data.load) {
                        setTimeout(function () {

                            window.location.href = "";
                        }, 500);
                    }
                }

                $('#newsletter_submit').show();
                $('#newsletter_submitting').hide();
            },
            error: function (data) {
                var jsonValue = $.parseJSON(data.responseText);
                const errors = jsonValue.errors;
                if (errors) {
                    var i = 0;
                    $.each(errors, function (key, value) {
                        const first_item = Object.keys(errors)[i]
                        const message = errors[first_item][0];
                        if ($('#' + first_item).length > 0) {
                            $('#' + first_item).parsley().removeError('required', {
                                updateClass: true
                            });
                            $('#' + first_item).parsley().addError('required', {
                                message: value,
                                updateClass: true
                            });
                        }
                        toastr.error(value);
                        i++;

                    });
                } else {
                    toastr.warning(jsonValue.message);
                }

                $('#newsletter_submit').show();
                $('#newsletter_submitting').hide();
            }
        });
    });
};

_newsletterFormValidation();

$(function () {
    // AJAX call to load categories
    $(document).on('click', '.categories_btn', function () {
        $.ajax({
            url: '/get-categories',
            method: 'get',
            dataType: 'HTML',
            beforeSend: function () {
                $('#category-navbar').html('<div class="loader-container"><i class="fas fa-circle-notch fa-spin"></i></div>');
            },
            success: function (response) {
                if (response) {
                    $('#category-navbar').html(response);
                    initializeDropdown(); // Initialize dropdown after content load
                } else {
                    $('#category-navbar').html('<div class="loader-container"><div class="alert alert-danger">No Content found for category.</div></div>');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                $('#category-navbar').html('<div class="loader-container"><div class="alert alert-danger">No Content found for category.</div></div>');
            }
        });
    });

    // Dropdown Initialization
    function initializeDropdown() {
        $(document).on('click', '.dropdown-menu a.dropdown-toggle', function (e) {
            e.preventDefault();
            const $this = $(this);
            const $submenu = $this.next('.dropdown-menu');

            // Close other open submenus
            if (!$submenu.hasClass('show')) {
                $this.parents('.dropdown-menu').first().find('.show').removeClass('show');
            }

            // Toggle current submenu
            $submenu.toggleClass('show');

            // Add event listener for closing parent dropdown
            $this.closest('.nav-item.dropdown').on('hidden.bs.dropdown', function () {
                $('.dropdown-menu .show').removeClass('show');
            });

            e.stopPropagation();
        });
    }

    // Call initialization for preloaded dropdowns
    initializeDropdown();
});

document.addEventListener("DOMContentLoaded", function () {
    const icons = document.querySelectorAll(".nav-link i");
    icons.forEach(icon => {
        const randomClass = `random-color-${Math.floor(Math.random() * 15) + 1}`;
        icon.classList.add(randomClass);
    });
    const iconsMenu = document.querySelectorAll(".dropdown-item i");
    iconsMenu.forEach(icon => {
        const randomClass = `random-color-${Math.floor(Math.random() * 15) + 1}`;
        icon.classList.add(randomClass);
    });
});

$('#search').jqueryInputTypeWriting({
    speed: 50,
    delay: 2000,
    keywords: ['Asus ExpertBook B1', 'Havit TW976 True Wireless Stereo Earbuds', 'Apple MacBook Pro 16', 'AMD Ryzen 9 9900X', 'Intel Core i7 14700K', 'GIGABYTE B760M', 'MaxGreen M19 ARGB'],
});