(window.webpackJsonp=window.webpackJsonp||[]).push([["notificationTray"],{"4nQW":function(t,e,n){"use strict";n.r(e);var o=n("q1tI"),r=n.n(o),i=n("i8i4");n("pNMO"),n("4Brf"),n("0oug"),n("4mDm"),n("wLYn"),n("zKZe"),n("uL8W"),n("eoL8"),n("NBAS"),n("ExoC"),n("07d7"),n("PKPk"),n("3bBZ"),n("R5XZ"),n("17x9");function a(t){var e=t.messengerCount,n=t.showMessenger,o=t.messengerTitle,i=e,a=0===i?"black":"white";return r.a.createElement("div",{id:"messageWall",className:"relative"},r.a.createElement("a",{href:"#",title:o,className:0===i?"inactive inline-block relative mr-4 fa-layers fa-fw fa-3x":"inline-block relative mr-4 fa-layers fa-fw fa-3x",onClick:n},0===i?r.a.createElement("span",{className:"far fa-comment-dots text-gray-500"}):r.a.createElement("span",{className:"fas fa-comment-dots  text-gray-800"},r.a.createElement("span",{className:"fa-layers-counter",style:{color:a,fontSize:"0.8rem",position:"absolute",top:"18px",left:"6px"}},i))))}function c(t){var e=t.notificationCount,n=t.showNotifications,o=t.notificationTitle,i=e,a=0===i?"black":"white";return r.a.createElement("div",{id:"notifications"},r.a.createElement("a",{className:0===i?"inactive inline-block relative mr-4 fa-layers fa-fw fa-3x":"inline-block relative mr-4 fa-layers fa-fw fa-3x",title:o,onClick:n},0===i?r.a.createElement("span",{className:"far fa-sticky-note text-gray-500"}):r.a.createElement("span",{className:"fas fa-sticky-note text-gray-800"},r.a.createElement("span",{className:"fa-layers-counter",style:{color:a,fontSize:"0.8rem",position:"absolute",top:"22px",left:"9px"}},i))))}a.defaultProps={messengerCount:0,messengerTitle:"Message Wall"},c.defaultProps={notificationCount:0,notificationTitle:"Notifications"};var s=n("ohO+");n("yq1k"),n("5s+n"),n("JTJg");function u(){return(u=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(t[o]=n[o])}return t}).apply(this,arguments)}function f(t,e,n){var o={};e&&e.headers&&(o=e.headers,delete e.headers),o=u({},o,{"Content-Type":"application/json; charset=utf-8"}),null===n&&(n="en_GB"),!1!==n&&""!==t||(n="");var r=window.location.protocol+"//"+window.location.hostname+"/";return fetch(r+n+t,u({},e,{credentials:"same-origin",headers:o})).then(l).then(function(t){return t.text().then(function(t){return t.includes("window.Sfdump")?(console.log(t),""):"string"==typeof t?JSON.parse(t):""})})}function l(t){if(t.status>=200&&t.status<400)return t;var e=new Error(t.statusText);throw e.response=t,e}function p(t){return(p="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function h(){return(h=Object.assign||function(t){for(var e=1;e<arguments.length;e++){var n=arguments[e];for(var o in n)Object.prototype.hasOwnProperty.call(n,o)&&(t[o]=n[o])}return t}).apply(this,arguments)}function v(t,e){for(var n=0;n<e.length;n++){var o=e[n];o.enumerable=o.enumerable||!1,o.configurable=!0,"value"in o&&(o.writable=!0),Object.defineProperty(t,o.key,o)}}function d(t){return(d=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}function m(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}function y(t,e){return(y=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}var g=function(t){function e(t){var n,o,r,i;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,e),o=this,(n=!(r=d(e).call(this,t))||"object"!==p(r)&&"function"!=typeof r?m(o):r).otherProps=h({},t),n.state={notificationCount:0,messengerCount:0,notificationTitle:"Notifications",messengerTitle:"Message Wall"},n.timeout=!0===n.isStaff?1e4:12e4,n.showNotifications=n.showNotifications.bind(m(n)),n.showMessenger=n.showMessenger.bind(m(n)),n.handleLogout=n.handleLogout.bind(m(n)),n.displayTray=!0,n.delay=(i=0,function(t,e){clearTimeout(i),i=setTimeout(t,e)}),n}var n,i,u;return function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&y(t,e)}(e,o["Component"]),n=e,(i=[{key:"componentDidMount",value:function(){this.displayTray&&(this.loadNotification(250+2e3*Math.random()),this.loadMessenger(250+2e3*Math.random()))}},{key:"componentWillUnmount",value:function(){clearTimeout(this.notificationTime),clearTimeout(this.messengerTime)}},{key:"loadNotification",value:function(t){var e=this;this.notificationTime=setTimeout(function(){f("api/notification/refresh/",{method:"GET"},!1).then(function(t){t.count!==e.state.notiificationCount&&e.setState({notificationCount:t.count,notificationTitle:t.title}),t.redirect&&Object(s.a)("/")}),e.loadNotification(e.timeout)},t)}},{key:"loadMessenger",value:function(t){var e=this;this.messengerTime=setTimeout(function(){f("api/messenger/refresh/",{method:"GET"},!1).then(function(t){t.count!==e.state.messengerCount&&e.setState({messengerCount:t.count,messengerTitle:t.title}),t.redirect&&Object(s.a)("/")}),e.loadMessenger(e.timeout)},t)}},{key:"showNotifications",value:function(){this.state.notificationCount>0&&Object(s.a)("/notifications/manage/",{method:"GET"},!1)}},{key:"showMessenger",value:function(){this.state.messengerCount>0&&Object(s.a)("/messenger/today/show/",{method:"GET"},!1)}},{key:"handleLogout",value:function(){Object(s.a)("/logout/",{method:"GET"},!1)}},{key:"render",value:function(){return r.a.createElement("div",{className:"flex flex-row-reverse mb-1"},r.a.createElement(a,{messengerCount:this.state.messengerCount,showMessenger:this.showMessenger,title:this.state.messengerTitle}),r.a.createElement(c,{notificationCount:this.state.notificationCount,showNotifications:this.showNotifications,title:this.state.notificationTitle}))}}])&&v(n.prototype,i),u&&v(n,u),e}();g.defaultProps={displayTray:!1,locale:!1};var b=document.getElementById("notificationTray");null===b?Object(i.render)(r.a.createElement("div",null," "),document.getElementById("dumpStuff")):Object(i.render)(r.a.createElement(g,null),b)},"4syw":function(t,e,n){var o=n("busE");t.exports=function(t,e,n){for(var r in e)o(t,r,e[r],n);return t}},"5mdu":function(t,e){t.exports=function(t){try{return{error:!1,value:t()}}catch(t){return{error:!0,value:t}}}},"5s+n":function(t,e,n){"use strict";var o,r,i,a=n("I+eb"),c=n("xDBR"),s=n("2oRo"),u=n("Qo9l"),f=n("4syw"),l=n("1E5z"),p=n("JiZb"),h=n("hh1v"),v=n("HAuM"),d=n("GarU"),m=n("xrYK"),y=n("ImZN"),g=n("HH4o"),b=n("SEBh"),w=n("LPSS").set,x=n("tXUg"),E=n("zfnd"),T=n("RN6c"),j=n("8GlL"),O=n("5mdu"),k=n("s5pE"),N=n("afO8"),P=n("lMq5"),M=n("tiKp")("species"),S="Promise",C=N.get,R=N.set,I=N.getterFor(S),K=s.Promise,L=s.TypeError,G=s.document,W=s.process,B=s.fetch,Z=W&&W.versions,_=Z&&Z.v8||"",A=j.f,J=A,Y="process"==m(W),q=!!(G&&G.createEvent&&s.dispatchEvent),H=P(S,function(){var t=K.resolve(1),e=function(){},n=(t.constructor={})[M]=function(t){t(e,e)};return!((Y||"function"==typeof PromiseRejectionEvent)&&(!c||t.finally)&&t.then(e)instanceof n&&0!==_.indexOf("6.6")&&-1===k.indexOf("Chrome/66"))}),z=H||!g(function(t){K.all(t).catch(function(){})}),D=function(t){var e;return!(!h(t)||"function"!=typeof(e=t.then))&&e},U=function(t,e,n){if(!e.notified){e.notified=!0;var o=e.reactions;x(function(){for(var r=e.value,i=1==e.state,a=0;o.length>a;){var c,s,u,f=o[a++],l=i?f.ok:f.fail,p=f.resolve,h=f.reject,v=f.domain;try{l?(i||(2===e.rejection&&X(t,e),e.rejection=1),!0===l?c=r:(v&&v.enter(),c=l(r),v&&(v.exit(),u=!0)),c===f.promise?h(L("Promise-chain cycle")):(s=D(c))?s.call(c,p,h):p(c)):h(r)}catch(t){v&&!u&&v.exit(),h(t)}}e.reactions=[],e.notified=!1,n&&!e.rejection&&Q(t,e)})}},F=function(t,e,n){var o,r;q?((o=G.createEvent("Event")).promise=e,o.reason=n,o.initEvent(t,!1,!0),s.dispatchEvent(o)):o={promise:e,reason:n},(r=s["on"+t])?r(o):"unhandledrejection"===t&&T("Unhandled promise rejection",n)},Q=function(t,e){w.call(s,function(){var n,o=e.value;if(V(e)&&(n=O(function(){Y?W.emit("unhandledRejection",o,t):F("unhandledrejection",t,o)}),e.rejection=Y||V(e)?2:1,n.error))throw n.value})},V=function(t){return 1!==t.rejection&&!t.parent},X=function(t,e){w.call(s,function(){Y?W.emit("rejectionHandled",t):F("rejectionhandled",t,e.value)})},$=function(t,e,n,o){return function(r){t(e,n,r,o)}},tt=function(t,e,n,o){e.done||(e.done=!0,o&&(e=o),e.value=n,e.state=2,U(t,e,!0))},et=function(t,e,n,o){if(!e.done){e.done=!0,o&&(e=o);try{if(t===n)throw L("Promise can't be resolved itself");var r=D(n);r?x(function(){var o={done:!1};try{r.call(n,$(et,t,o,e),$(tt,t,o,e))}catch(n){tt(t,o,n,e)}}):(e.value=n,e.state=1,U(t,e,!1))}catch(n){tt(t,{done:!1},n,e)}}};H&&(K=function(t){d(this,K,S),v(t),o.call(this);var e=C(this);try{t($(et,this,e),$(tt,this,e))}catch(t){tt(this,e,t)}},(o=function(t){R(this,{type:S,done:!1,notified:!1,parent:!1,reactions:[],rejection:!1,state:0,value:void 0})}).prototype=f(K.prototype,{then:function(t,e){var n=I(this),o=A(b(this,K));return o.ok="function"!=typeof t||t,o.fail="function"==typeof e&&e,o.domain=Y?W.domain:void 0,n.parent=!0,n.reactions.push(o),0!=n.state&&U(this,n,!1),o.promise},catch:function(t){return this.then(void 0,t)}}),r=function(){var t=new o,e=C(t);this.promise=t,this.resolve=$(et,t,e),this.reject=$(tt,t,e)},j.f=A=function(t){return t===K||t===i?new r(t):J(t)},c||"function"!=typeof B||a({global:!0,enumerable:!0,forced:!0},{fetch:function(t){return E(K,B.apply(s,arguments))}})),a({global:!0,wrap:!0,forced:H},{Promise:K}),l(K,S,!1,!0),p(S),i=u.Promise,a({target:S,stat:!0,forced:H},{reject:function(t){var e=A(this);return e.reject.call(void 0,t),e.promise}}),a({target:S,stat:!0,forced:c||H},{resolve:function(t){return E(c&&this===i?K:this,t)}}),a({target:S,stat:!0,forced:z},{all:function(t){var e=this,n=A(e),o=n.resolve,r=n.reject,i=O(function(){var n=v(e.resolve),i=[],a=0,c=1;y(t,function(t){var s=a++,u=!1;i.push(void 0),c++,n.call(e,t).then(function(t){u||(u=!0,i[s]=t,--c||o(i))},r)}),--c||o(i)});return i.error&&r(i.value),n.promise},race:function(t){var e=this,n=A(e),o=n.reject,r=O(function(){var r=v(e.resolve);y(t,function(t){r.call(e,t).then(n.resolve,o)})});return r.error&&o(r.value),n.promise}})},"6VoE":function(t,e,n){var o=n("tiKp"),r=n("P4y1"),i=o("iterator"),a=Array.prototype;t.exports=function(t){return void 0!==t&&(r.Array===t||a[i]===t)}},"8GlL":function(t,e,n){"use strict";var o=n("HAuM"),r=function(t){var e,n;this.promise=new t(function(t,o){if(void 0!==e||void 0!==n)throw TypeError("Bad Promise constructor");e=t,n=o}),this.resolve=o(e),this.reject=o(n)};t.exports.f=function(t){return new r(t)}},GarU:function(t,e){t.exports=function(t,e,n){if(!(t instanceof e))throw TypeError("Incorrect "+(n?n+" ":"")+"invocation");return t}},HH4o:function(t,e,n){var o=n("tiKp")("iterator"),r=!1;try{var i=0,a={next:function(){return{done:!!i++}},return:function(){r=!0}};a[o]=function(){return this},Array.from(a,function(){throw 2})}catch(t){}t.exports=function(t,e){if(!e&&!r)return!1;var n=!1;try{var i={};i[o]=function(){return{next:function(){return{done:n=!0}}}},t(i)}catch(t){}return n}},ImZN:function(t,e,n){var o=n("glrk"),r=n("6VoE"),i=n("UMSQ"),a=n("+MLx"),c=n("NaFW"),s=n("m92n"),u=function(t,e){this.stopped=t,this.result=e};(t.exports=function(t,e,n,f,l){var p,h,v,d,m,y,g=a(e,n,f?2:1);if(l)p=t;else{if("function"!=typeof(h=c(t)))throw TypeError("Target is not iterable");if(r(h)){for(v=0,d=i(t.length);d>v;v++)if((m=f?g(o(y=t[v])[0],y[1]):g(t[v]))&&m instanceof u)return m;return new u(!1)}p=h.call(t)}for(;!(y=p.next()).done;)if((m=s(p,g,y.value,f))&&m instanceof u)return m;return new u(!1)}).stop=function(t){return new u(!0,t)}},JTJg:function(t,e,n){"use strict";var o=n("I+eb"),r=n("WjRb"),i=n("HYAF");o({target:"String",proto:!0,forced:!n("qxPZ")("includes")},{includes:function(t){return!!~String(i(this)).indexOf(r(t),arguments.length>1?arguments[1]:void 0)}})},JiZb:function(t,e,n){"use strict";var o=n("0GbY"),r=n("m/L8"),i=n("tiKp"),a=n("g6v/"),c=i("species");t.exports=function(t){var e=o(t),n=r.f;a&&e&&!e[c]&&n(e,c,{configurable:!0,get:function(){return this}})}},LPSS:function(t,e,n){var o,r,i,a=n("2oRo"),c=n("0Dky"),s=n("xrYK"),u=n("+MLx"),f=n("G+Rx"),l=n("zBJ4"),p=a.location,h=a.setImmediate,v=a.clearImmediate,d=a.process,m=a.MessageChannel,y=a.Dispatch,g=0,b={},w=function(t){if(b.hasOwnProperty(t)){var e=b[t];delete b[t],e()}},x=function(t){return function(){w(t)}},E=function(t){w(t.data)},T=function(t){a.postMessage(t+"",p.protocol+"//"+p.host)};h&&v||(h=function(t){for(var e=[],n=1;arguments.length>n;)e.push(arguments[n++]);return b[++g]=function(){("function"==typeof t?t:Function(t)).apply(void 0,e)},o(g),g},v=function(t){delete b[t]},"process"==s(d)?o=function(t){d.nextTick(x(t))}:y&&y.now?o=function(t){y.now(x(t))}:m?(i=(r=new m).port2,r.port1.onmessage=E,o=u(i.postMessage,i,1)):!a.addEventListener||"function"!=typeof postMessage||a.importScripts||c(T)?o="onreadystatechange"in l("script")?function(t){f.appendChild(l("script")).onreadystatechange=function(){f.removeChild(this),w(t)}}:function(t){setTimeout(x(t),0)}:(o=T,a.addEventListener("message",E,!1))),t.exports={set:h,clear:v}},NaFW:function(t,e,n){var o=n("9d/t"),r=n("P4y1"),i=n("tiKp")("iterator");t.exports=function(t){if(null!=t)return t[i]||t["@@iterator"]||r[o(t)]}},R5XZ:function(t,e,n){var o=n("I+eb"),r=n("2oRo"),i=n("s5pE"),a=[].slice,c=function(t){return function(e,n){var o=arguments.length>2,r=o?a.call(arguments,2):void 0;return t(o?function(){("function"==typeof e?e:Function(e)).apply(this,r)}:e,n)}};o({global:!0,bind:!0,forced:/MSIE .\./.test(i)},{setTimeout:c(r.setTimeout),setInterval:c(r.setInterval)})},RN6c:function(t,e,n){var o=n("2oRo");t.exports=function(t,e){var n=o.console;n&&n.error&&(1===arguments.length?n.error(t):n.error(t,e))}},ROdP:function(t,e,n){var o=n("hh1v"),r=n("xrYK"),i=n("tiKp")("match");t.exports=function(t){var e;return o(t)&&(void 0!==(e=t[i])?!!e:"RegExp"==r(t))}},SEBh:function(t,e,n){var o=n("glrk"),r=n("HAuM"),i=n("tiKp")("species");t.exports=function(t,e){var n,a=o(t).constructor;return void 0===a||null==(n=o(a)[i])?e:r(n)}},WjRb:function(t,e,n){var o=n("ROdP");t.exports=function(t){if(o(t))throw TypeError("The method doesn't accept regular expressions");return t}},YNrV:function(t,e,n){"use strict";var o=n("g6v/"),r=n("0Dky"),i=n("33Wh"),a=n("dBg+"),c=n("0eef"),s=n("ewvW"),u=n("RK3t"),f=Object.assign;t.exports=!f||r(function(){var t={},e={},n=Symbol();return t[n]=7,"abcdefghijklmnopqrst".split("").forEach(function(t){e[t]=t}),7!=f({},t)[n]||"abcdefghijklmnopqrst"!=i(f({},e)).join("")})?function(t,e){for(var n=s(t),r=arguments.length,f=1,l=a.f,p=c.f;r>f;)for(var h,v=u(arguments[f++]),d=l?i(v).concat(l(v)):i(v),m=d.length,y=0;m>y;)h=d[y++],o&&!p.call(v,h)||(n[h]=v[h]);return n}:f},m92n:function(t,e,n){var o=n("glrk");t.exports=function(t,e,n,r){try{return r?e(o(n)[0],n[1]):e(n)}catch(e){var i=t.return;throw void 0!==i&&o(i.call(t)),e}}},"ohO+":function(t,e,n){"use strict";function o(t,e,n){var o="_self";e&&"string"==typeof e.target&&(o=e.target);var r="";e&&"string"==typeof e.specs&&(r=e.specs),"boolean"==typeof n&&!1===n&&(n=""),null==n&&(n="en_GB"),""!==n&&(n="/"+n),window.open(window.location.protocol+"//"+window.location.hostname+n+t,o,r)}n.d(e,"a",function(){return o})},qxPZ:function(t,e,n){var o=n("tiKp")("match");t.exports=function(t){var e=/./;try{"/./"[t](e)}catch(n){try{return e[o]=!1,"/./"[t](e)}catch(t){}}return!1}},s5pE:function(t,e,n){var o=n("0GbY");t.exports=o("navigator","userAgent")||""},tXUg:function(t,e,n){var o,r,i,a,c,s,u,f=n("2oRo"),l=n("Bs8V").f,p=n("xrYK"),h=n("LPSS").set,v=n("s5pE"),d=f.MutationObserver||f.WebKitMutationObserver,m=f.process,y=f.Promise,g="process"==p(m),b=l(f,"queueMicrotask"),w=b&&b.value;w||(o=function(){var t,e;for(g&&(t=m.domain)&&t.exit();r;){e=r.fn,r=r.next;try{e()}catch(t){throw r?a():i=void 0,t}}i=void 0,t&&t.enter()},g?a=function(){m.nextTick(o)}:d&&!/(iphone|ipod|ipad).*applewebkit/i.test(v)?(c=!0,s=document.createTextNode(""),new d(o).observe(s,{characterData:!0}),a=function(){s.data=c=!c}):y&&y.resolve?(u=y.resolve(void 0),a=function(){u.then(o)}):a=function(){h.call(f,o)}),t.exports=w||function(t){var e={fn:t,next:void 0};i&&(i.next=e),r||(r=e,a()),i=e}},yq1k:function(t,e,n){"use strict";var o=n("I+eb"),r=n("TWQb").includes,i=n("RNIs");o({target:"Array",proto:!0},{includes:function(t){return r(this,t,arguments.length>1?arguments[1]:void 0)}}),i("includes")},zKZe:function(t,e,n){var o=n("I+eb"),r=n("YNrV");o({target:"Object",stat:!0,forced:Object.assign!==r},{assign:r})},zfnd:function(t,e,n){var o=n("glrk"),r=n("hh1v"),i=n("8GlL");t.exports=function(t,e){if(o(t),r(e)&&e.constructor===t)return e;var n=i.f(t);return(0,n.resolve)(e),n.promise}}},[["4nQW","runtime",0]]]);