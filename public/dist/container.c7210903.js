(window.webpackJsonp=window.webpackJsonp||[]).push([["container"],{AMWX:function(e,t,n){"use strict";n("pNMO"),n("4Brf"),n("0oug"),n("ma9I"),n("yXV3"),n("4mDm"),n("2B1R"),n("pDQq"),n("wLYn"),n("sMBO"),n("zKZe"),n("uL8W"),n("eoL8"),n("NBAS"),n("tkto"),n("ExoC"),n("07d7"),n("4l63"),n("rB9j"),n("PKPk"),n("UxlC"),n("3bBZ");var r=n("q1tI"),a=n.n(r),o=(n("17x9"),n("76ZC")),l=n.n(o),i=n("GTV5");n("Bcp6");function c(e){return(c="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function s(e,t){var n={};return n.className=t,null!==e.row_class&&(n.className=e.row_class),!1!==e.row_id&&(n.id=e.row_id),n}function u(e,t){var n=!1===e.column_attr?{}:e.column_attr;return void 0!==n.class&&(n.className=n.class,delete n.class),void 0===n.className&&(n.className=t),n}function m(e,t,n){var r="object"!==c(e.attr)?{}:e.attr;return void 0!==r.class&&(r.className=r.class,delete r.class),void 0===r.className&&(r.className=t),r.id=e.id,r.name=e.full_name,r.onChange=null,!1===e.on_change&&"function"==typeof n.onElementChange?r.onChange=function(t){return n.onElementChange(t,e)}:"function"==typeof n[e.on_change]&&(r.onChange=function(t){return n[e.on_change](t,e)}),r.onClick=null,!1===e.on_click&&"function"==typeof n.onElementClick?r.onClick=function(t){return n.onElementClick(t,e)}:"function"==typeof n[e.on_click]&&(r.onClick=function(t){return n[e.on_click](t,e)}),!1!==e.multiple&&(r.multiple=!0),"string"==typeof r.inputmode&&(r.inputMode=r.inputmode,delete r.inputmode),r["aria-describedby"]=e.id+"_help",r}function f(e,t){var n={};return n.className=t,null!==e.wrapper_class&&(n.className=e.wrapper_class),n}function d(e){var t=e.form,n=(e.functions,e.columns),r=s(t,"break flex flex-col sm:flex-row justify-between content-center p-0"),o=u(t,"flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0");n>1&&(o.colSpan=n);var i=a.a.createElement("h3",null,t.label);"h4"===t.header_type&&(i=a.a.createElement("h4",null,t.label));var c="";return"string"==typeof t.help&&(c=t.help),a.a.createElement("tr",r,a.a.createElement("td",o,i,l()(c)))}function p(e){var t=e.form,n=(e.functions,e.columns),r=s(t,"break flex flex-col sm:flex-row justify-between content-center p-0"),o=u(t,"flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0");n>1&&(o.colSpan=n);var i=f(t,"");return a.a.createElement("tr",r,a.a.createElement("td",o,a.a.createElement("div",i,l()(t.help))))}var h=n("AEIT"),y=n.n(h),b=n("+z1p"),v=n.n(b);function g(){return(g=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function E(e){var t=e.form,n=e.functions;if(!1===t.header_row)return null;if(!0===t.header_row){var r=Object.keys(t.prototype.children).map(function(e){var n=t.prototype.children[e];if("hidden"!==n.type)return a.a.createElement("th",{className:"text-xxs sm:text-xs p-2 sm:py-3",key:n.name},n.label)});return r.push(a.a.createElement("th",{className:"shortWidth text-xxs sm:text-xs p-2 sm:py-3 text-center",key:"actions"},n.translate("Actions"))),a.a.createElement("thead",null,a.a.createElement("tr",null,r))}if("array"==typeof t.header_row){var o=t.header_row.map(function(e,t){return a.a.createElement("th",g({},e.attr,{key:e.name}),e.label)});return a.a.createElement("thead",null,a.a.createElement("tr",null,o))}}function w(e){var t=e.message,n=e.cancelMessage;return a.a.createElement("div",{className:t.class},t.message,a.a.createElement("button",{className:"button close "+t.class,onClick:function(){return n(t.id)},title:"Close Message",type:"button"},a.a.createElement("span",{className:"fas fa-times-circle fa-fw "+t.class})))}function O(e){return(O="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function k(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function _(e){return(_=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function P(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function S(e,t){return(S=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var j=function(e){function t(e){var n,r,a;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(a=_(t).call(this,e))||"object"!==O(a)&&"function"!=typeof a?P(r):a).cancelMessage=n.cancelMessage.bind(P(n)),n}var n,o,l;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&S(e,t)}(t,r["Component"]),n=t,(o=[{key:"cancelMessage",value:function(e){var t=this.props.messages;t.splice(e,1),this.setState({messages:t})}},{key:"render",value:function(){var e=this,t=this.props.messages.map(function(t,n){return t.id=n,a.a.createElement(w,{message:t,key:"message_"+t.id,cancelMessage:e.cancelMessage})});return 0===t.length?null:a.a.createElement("div",null,t)}}])&&k(n.prototype,o),l&&k(n,l),t}();function F(){return(F=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function N(e){var t=e.form,n=e.functions,r=e.columnCount,o=m(t,"leftIndent smallIntBorder standardForm striped",{});delete o.name;var l=t.errors,i=a.a.createElement(E,{form:t,functions:n}),c=[];return void 0!==t.children&&t.children.length>0?Object.keys(t.children).map(function(e){var r=t.children[e],o=[],l=[];Object.keys(r.children).map(function(e){var t=F({},r.children[e]);"hidden"!==t.type?o.push(a.a.createElement("td",{key:e},a.a.createElement(V,{form:F({},t),functions:n}))):l.push(a.a.createElement(V,{form:F({},t),functions:n,key:e}))});var i=[];t.allow_delete&&i.push(a.a.createElement("button",{title:n.translate("Delete"),onClick:function(){return n.deleteElement(r)},className:"button text-gray-800",type:"button",key:"one"},a.a.createElement("span",{className:"far fa-trash-alt fa-fw"}))),o.push(a.a.createElement("td",{key:"actions"},l,a.a.createElement("div",{className:"text-center"},i))),c.push(a.a.createElement("tr",{key:e},o))}):c.push(a.a.createElement("tr",{key:"emptyWarning"},a.a.createElement("td",{colSpan:r},a.a.createElement("div",{className:"warning"},n.translate("There are no records to display."))))),c.push(a.a.createElement("tr",{key:"addRow"},a.a.createElement("td",{colSpan:r-1}),a.a.createElement("td",null,a.a.createElement("div",{className:"text-center"},a.a.createElement("button",{title:n.translate("Add"),onClick:function(){return n.addElement(t)},className:"button text-gray-800",type:"button",key:"one"},a.a.createElement("span",{className:"fas fa-plus-circle fa-fw"})))))),a.a.createElement("div",{className:"collection"},a.a.createElement(j,{messages:l}),a.a.createElement("table",o,i,a.a.createElement("tbody",null,c)))}function x(e){var t=e.functions,n=e.form,r=0;return Object.keys(n.prototype.children).map(function(e){"hidden"!==n.prototype.children[e].type&&r++}),r++,a.a.createElement(N,{form:n,functions:t,columnCount:r,key:n.collection_key})}function C(e){return void 0===e||(""===e||(null===e||(e===[]||e==={})))}function M(){return(M=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function R(e){var t=e.form,n=e.wrapper_attr,r=e.widget_attr,o=[];return void 0!==t.placeholder&&!1!==t.placeholder&&o.push(a.a.createElement("option",{key:"placeholder",className:"text-gray-500"},t.placeholder)),Object.keys(t.choices).map(function(e){o.push(a.a.createElement("option",{value:t.choices[e].value,key:t.choices[e].value},t.choices[e].label))}),a.a.createElement("div",n,a.a.createElement("select",M({multiple:t.multiple},r,{defaultValue:t.value}),o))}function B(){return(B=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function V(e){var t=e.form,n=e.functions,r=f(t,"flex-1 relative"),o="form element "+t.type,l=m(t,"w-full",n),i=[];if(t.errors.length>0&&(r.className+=" errors",i=t.errors.map(function(e,t){return a.a.createElement("li",{key:t},e)})),"ckeditor"===t.type)return a.a.createElement("div",r,a.a.createElement(y.a,{editor:v.a,data:t.value,"aria-describedby":t.id+"_help",onChange:function(e,r){return n.onCKEditorChange(e,r,t)}}),t.errors.length>0?a.a.createElement("ul",null,i):"");if("submit"===t.type)return l.type="button",l.style={float:"right"},l.className="btn-gibbon",l.onClick=function(e){return n.submitForm(e,t)},a.a.createElement("div",r,a.a.createElement("span",{className:"emphasis small"},"* ",t.help),a.a.createElement("button",l,t.label));if("hidden"===t.type)return l.type="hidden",a.a.createElement("input",l);if("email"===t.type)return l.type="email",a.a.createElement("div",r,a.a.createElement("input",B({},l,{defaultValue:t.value})),t.errors.length>0?a.a.createElement("ul",null,i):"");if("password_generator"===t.type){l.type="password";return a.a.createElement("div",r,a.a.createElement("input",B({},l,{defaultValue:t.value})),a.a.createElement("button",B({type:"button",title:t.generateButton.title,className:t.generateButton.class},{},{onClick:function(){return n[t.generateButton.onClick](t)}}),t.generateButton.title),t.errors.length>0?a.a.createElement("ul",null,i):"")}if("password"===t.type)return l.type="password",a.a.createElement("div",r,a.a.createElement("input",B({},l,{defaultValue:t.value})),t.errors.length>0?a.a.createElement("ul",null,i):"");if("url"===t.type){l.type="url";var c={};return C(t.value)&&(c.disabled=!0),a.a.createElement("div",r,a.a.createElement("input",B({},l,{defaultValue:t.value})),a.a.createElement("button",B({type:"button",title:n.translate("Open Link"),className:"button button-right"},c,{onClick:function(){return n.openUrl(t.value)}}),a.a.createElement("span",{className:"fa-fw fas fa-external-link-alt"})),t.errors.length>0?a.a.createElement("ul",null,i):"")}if("file"===t.type){l.type="file";var s={};return C(t.value)&&(s.disabled=!0),a.a.createElement("div",r,a.a.createElement("input",l),a.a.createElement("div",{className:"button-right"},a.a.createElement("button",B({type:"button",title:n.translate("File Download"),className:"button"},s,{onClick:function(){return n.downloadFile(t)}}),a.a.createElement("span",{className:"fa-fw fas fa-file-download"})),a.a.createElement("button",B({type:"button",title:n.translate("File Delete"),className:"button"},s,{onClick:function(){return n.deleteFile(t)}}),a.a.createElement("span",{className:"fa-fw fas fa-trash"}))),t.errors.length>0?a.a.createElement("ul",null,i):"")}if("text"===t.type)return l.type="text",a.a.createElement("div",r,a.a.createElement("input",B({},l,{defaultValue:t.value})),t.errors.length>0?a.a.createElement("ul",null,i):"");if("collection"===t.type)return void 0===t.children&&(t.children=[]),a.a.createElement(x,{form:t,functions:n,key:t.collection_key});if("choice"===t.type)return a.a.createElement(R,{form:t,wrapper_attr:r,widget_attr:l});if("textarea"===t.type)return a.a.createElement("div",r,a.a.createElement("textarea",B({},l,{defaultValue:t.value})),t.errors.length>0?a.a.createElement("ul",null,i):"");if("toggle"===t.type){l.type="hidden",r.className+=" right";var u={onClick:function(e){return n.onElementChange(e,t)},className:"button"};delete l.onChange;var d={className:"fa-fw far fa-thumbs-down"};return"Y"===t.value||"1"===t.value?(d.className="fa-fw far fa-thumbs-up",u.className+=" success",t.value="Y"):t.value="N",a.a.createElement("div",r,a.a.createElement("input",B({},l,{defaultValue:t.value})),a.a.createElement("button",B({type:"button",title:n.translate("Yes/No")},u),a.a.createElement("span",d)),t.errors.length>0?a.a.createElement("ul",null,i):"")}return"integer"===t.type?(l.type="number",a.a.createElement("div",r,a.a.createElement("input",B({},l,{defaultValue:t.value})),t.errors.length>0?a.a.createElement("ul",null,i):"")):(console.log(t),a.a.createElement("div",r,o))}function T(e){var t=e.form,n=e.functions,r=e.columns;!1===t.column_attr&&r>1&&(t.column_attr={}),r>1&&(t.column_attr.colSpan=r);var o=s(t,"flex flex-col sm:flex-row justify-between content-center p-0"),l=u(t,"flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0");return a.a.createElement("tr",o,a.a.createElement("td",l,a.a.createElement(V,{form:t,functions:n})))}function L(){return(L=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function I(e){var t=e.form,n=[],r=function(e,t){var n={};return n.className=t,n}(0,"inline-block mt-4 sm:my-1 sm:max-w-xs font-bold text-sm sm:text-xs");"string"==typeof t.help&&(n.push(a.a.createElement("br",{key:"newLine"})),n.push(a.a.createElement("span",{key:"message",className:"text-xxs text-gray-600 italic font-normal mt-1 sm:mt-0"},l()(t.help))));var o="";return!0===t.required&&(o=" *"),a.a.createElement("label",L({htmlFor:t.id},r),t.label,o,n)}function A(e){var t=e.form,n=e.functions;return a.a.createElement("tr",{className:"flex flex-col sm:flex-row justify-between content-center p-0"},a.a.createElement("td",{className:"flex flex-col flex-grow justify-center -mb-1 sm:mb-0  px-2 border-b-0 sm:border-b border-t-0"},a.a.createElement(I,{form:t})),a.a.createElement("td",{className:"w-full max-w-full sm:max-w-xs flex justify-end items-center px-2 border-b-0 sm:border-b border-t-0"},a.a.createElement(V,{form:t,functions:n})))}function D(e){var t=e.form,n=e.functions,r=e.columns;return t.columns=r,"hidden"===t.type&&"hidden"!==t.row_style&&(t.row_style="hidden"),"header"===t.type?a.a.createElement(d,{form:t,functions:n,columns:r}):"paragraph"===t.type?a.a.createElement(p,{form:t,functions:n,columns:r}):"single"===t.row_style||"submit"===t.type?a.a.createElement(T,{form:t,functions:n,columns:r}):"hidden"===t.row_style?a.a.createElement("tr",{style:{display:"none"}},a.a.createElement("td",null,a.a.createElement(V,{form:t,functions:n}))):"standard"===t.row_style?a.a.createElement(A,{form:t,functions:n}):"transparent"===t.row_style||"repeated"===t.row_style?Object.keys(t.children).map(function(e){var o=t.children[e];return"password_generator"===o.type&&"second"===e&&(o.type="password"),a.a.createElement(D,{form:o,key:o.name,functions:n,columns:r})}):(console.log(t),a.a.createElement("tr",null,a.a.createElement("td",null," Form Row ",t.row_style)))}function K(){return(K=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function U(e){var t=e.functions,n=e.form;e.formName;if("table"===n.template){for(var r=Object.keys(n.children).map(function(e){var r=n.children[e];return a.a.createElement(D,{key:e,form:r,functions:t,columns:n.columns})}),o=[],l=0;l<o;l++)o.push(a.a.createElement("td",{key:l}));var i=a.a.createElement("tr",{style:{display:"none"}},o),c={className:"smallIntBorder fullWidth standardForm relative"};return null!==n.row_class&&(c.className=n.row_class),a.a.createElement("form",K({action:n.action,id:n.id},n.attr,{method:void 0!==n.method?n.method:"POST"}),a.a.createElement(j,{messages:n.errors}),a.a.createElement("table",c,a.a.createElement("tbody",null,i,r)))}return null}function W(){return(W=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function Z(e){var t=e.panels,n=e.selectedIndex,r=e.functions,o=Object.keys(t).map(function(e){var n=t[e];return a.a.createElement(i.a,{key:n.name,disabled:n.disabled},n.label)}),c=Object.keys(t).map(function(n){var r=function(e,t){if(null!==e.content)return l()(e.content);var n=t.forms[e.name];return a.a.createElement(U,W({},t,{formName:e.name,form:n}))}(t[n],e);return a.a.createElement(i.c,{key:n},r)});return a.a.createElement(i.d,{selectedIndex:n,onSelect:function(e){return r.onSelectTab(e)}},a.a.createElement(i.b,null,o),c)}function J(){return(J=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function Y(e){var t=e.panels,n=e.forms,r=e.selectedPanel,o=(e.actionRoute,e.functions),i=t[r].index;if(1===Object.keys(t).length){var c=Object.keys(t)[0],s=t[c];return null!==s.content?l()(s.content):a.a.createElement(U,J({},e,{form:n[c],functions:o,formName:c}))}return a.a.createElement(Z,J({},e,{panels:t,selectedIndex:i,functions:o}))}N.defaultProps={errors:[]};var q=n("ohO+"),z=n("J5mc");n("TWNs"),n("JfAA");function X(e){return(X="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function G(){return(G=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function $(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function Q(e){return(Q=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function H(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function ee(e,t){return(ee=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}n.d(t,"a",function(){return te});var te=function(e){function t(e){var n,r,a;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(a=Q(t).call(this,e))||"object"!==X(a)&&"function"!=typeof a?H(r):a).panels=e.panels?e.panels:{},n.content=e.content?e.content:null,n.functions=e.functions,n.translations=e.translations,n.actionRoute=e.actionRoute,0===Object.keys(n.panels).length&&null!==n.content&&(n.panels.default={},n.panels.default.name="default",n.panels.default.disabled=!0,n.panels.default.content=n.content),n.functions.translate=n.translate.bind(H(n)),n.functions.openUrl=n.openUrl.bind(H(n)),n.functions.downloadFile=n.downloadFile.bind(H(n)),n.functions.onSelectTab=n.onSelectTab.bind(H(n)),n.functions.deleteFile=n.deleteFile.bind(H(n)),n.functions.submitForm=n.submitForm.bind(H(n)),n.functions.onElementChange=n.onElementChange.bind(H(n)),n.functions.deleteElement=n.deleteElement.bind(H(n)),n.functions.addElement=n.addElement.bind(H(n)),n.functions.onCKEditorChange=n.onCKEditorChange.bind(H(n)),n.functions.generateNewPassword=n.generateNewPassword.bind(H(n)),n.functions.deleteFile=n.deleteFile.bind(H(n)),n.functions.calcFormCount=n.calcFormCount.bind(H(n)),n.getParentForm=n.getParentForm.bind(H(n)),n.setParentState=n.setParentState.bind(H(n)),n.getParentFormName=n.getParentFormName.bind(H(n)),n.mergeParentForm=n.mergeParentForm.bind(H(n)),n.state={selectedPanel:e.selectedPanel,forms:e.forms},n.formNames={},n.submit={},n}var o,l,i;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&ee(e,t)}(t,r["Component"]),o=t,(l=[{key:"componentDidMount",value:function(){var e=this;Object.keys(this.state.forms).map(function(t){var n=e.state.forms[t];e.formNames[n.name]=t,e.submit[n.name]=!1})}},{key:"setParentState",value:function(e){"function"==typeof this.functions.setParentState?this.functions.setParentState(e):this.setState({forms:e})}},{key:"translate",value:function(e){return C(this.translations[e])?(console.error("Unable to translate: "+e),e):this.translations[e]}},{key:"onSelectTab",value:function(e){var t=this.state.selectedPanel,n=0;Object.keys(this.panels).map(function(r){n===e&&(t=r),n++}),this.setState({selectedPanel:t})}},{key:"downloadFile",value:function(e){var t=e.value,n="/resource/"+btoa(t)+"/"+this.actionRoute+"/download/";void 0!==e.delete_security&&!1!==e.delete_security&&(n="/resource/"+btoa(e.value)+"/"+e.delete_security+"/download/"),Object(q.a)(n,{target:"_blank"},!1)}},{key:"openUrl",value:function(e){window.open(e,"_blank")}},{key:"deleteFile",value:function(e){var t=this,n="/resource/"+btoa(e.value)+"/"+this.actionRoute+"/delete/";void 0!==e.delete_security&&!1!==e.delete_security&&(n="/resource/"+btoa(e.value)+"/"+e.delete_security+"/delete/");var r=this.getParentForm(e);Object(z.a)(n,{},!1).then(function(n){if("success"===n.status){var a=r.errors;a=a.concat(n.errors),r.errors=a,t.setParentState(t.mergeParentForm(t.getParentFormName(e),t.changeFormValue(r,e,"")))}else{var o=r.errors;o=o.concat(n.errors),r.errors=o,t.setParentState(t.mergeParentForm(t.getParentFormName(e),r))}}).catch(function(n){var a=r.errors;a.push({class:"error",message:n}),r.errors=a,t.setParentState(t.mergeParentForm(t.getParentFormName(e),r))})}},{key:"generateNewPassword",value:function(e){var t=function(e){var t="abcdefghijklmnopqrstuvwxyz";t+="ABCDEFGHIJKLMNOPQRSTUVWXYZ",t+="0123456789",t+="#?!@$%^+=&*-";var n="^(.*(?=.*[a-z])";e.alpha&&(n+="(?=.*[A-Z])"),e.numeric&&(n+="(?=.*[0-9])"),e.punctuation&&(n+="(?=.*?[#?!@$%^+=&*-])"),n+=".*){"+e.minLength+",}$",n=new RegExp(n);for(var r=e.minLength<12?12:e.minLength,a="",o=0;o<r;o++)a+=t.charAt(Math.floor(Math.random()*t.length));for(;!1===n.test(a);){a="";for(var l=0;l<r;l++)a+=t.charAt(Math.floor(Math.random()*t.length))}return a}(e.generateButton.passwordPolicy),n=this.getParentForm(e),r=e.id.replace("first","second");n=G({},this.changeFormValue(n,e,t));var a=this.findElementById(n,r,{});alert(e.generateButton.alertPrompt+": "+t),n=this.changeFormValue(n,a,t),this.setParentState(this.mergeParentForm(this.getParentFormName(e),n))}},{key:"onCKEditorChange",value:function(e,t,n){var r=t.getData();this.setParentState(this.mergeParentForm(this.getParentFormName(n),this.changeFormValue(this.getParentForm(n),n,r)))}},{key:"calcFormCount",value:function(e,t){var n=this;return"array"==typeof e.children&&e.children.length>0?this.state.form.children.map(function(e){t=n.calcFormCount(e,t)}):"object"===X(e.children)&&Object.keys(e.children).length>0&&Object.keys(e.children).map(function(r){var a=e.children[r];t=n.calcFormCount(a,t)}),++t}},{key:"changeFormValue",value:function(e,t,n){var r=this;return"array"==typeof e.children&&e.children.length>0?(e.children.map(function(a,o){a.id===t.id&&(a.value=n),e.children[o]=r.changeFormValue(a,t,n)}),e):"object"===X(e.children)&&Object.keys(e.children).length>0?(Object.keys(e.children).map(function(a){var o=e.children[a];o.id===t.id&&(o.value=n),e.children[a]=r.changeFormValue(o,t,n)}),e):e}},{key:"getParentForm",value:function(e){return this.state.forms[this.getParentFormName(e)]}},{key:"getParentFormName",value:function(e){return this.formNames[e.full_name.substring(0,e.full_name.indexOf("["))]}},{key:"mergeParentForm",value:function(e,t){var n=this.state.forms;return n[e]=G({},t),n}},{key:"onElementChange",value:function(e,t){var n=this,r=this.getParentForm(t),a=this.getParentFormName(t);if("toggle"!==t.type){if("file"===t.type){var o=e.target.files[0],l=new FileReader;return l.readAsDataURL(o),l.onerror=function(e){r.errors.push({class:"error",message:n.functions.translations("A problem occurred loading the file.")}),n.setState({forms:n.mergeParentForm(a,n.changeFormValue(r,t,o))})},void(l.onload=function(e){o=e.target.result,n.setState({forms:n.mergeParentForm(a,n.changeFormValue(r,t,o))})})}var i=e.target.value;this.setState({forms:this.mergeParentForm(a,this.changeFormValue(r,t,i))})}else{var c="Y"===t.value?"N":"Y";this.setState({forms:this.mergeParentForm(a,this.changeFormValue(r,t,c))})}}},{key:"buildFormData",value:function(e,t){var n=this;return"array"==typeof t.children&&t.children.length>0?(t.children.map(function(t){e[t.name]=n.buildFormData({},t)}),e):"object"===X(t.children)&&Object.keys(t.children).length>0?(Object.keys(t.children).map(function(r){var a=t.children[r];e[a.name]=n.buildFormData({},a)}),e):t.value}},{key:"submitForm",value:function(e,t){var n=this,r=this.getParentFormName(t);if(!this.submit[r]){this.submit[r]=!0,this.setParentState(G({},this.state.forms));var a=this.getParentForm(t),o=this.buildFormData({},a);Object(z.a)(a.action,{method:a.method,body:JSON.stringify(o)},!1).then(function(e){var t=a.errors;t=t.concat(e.errors);var o="function"==typeof n.functions.submitFormCallable?n.functions.submitFormCallable(e.form):e.form;o.errors=t,n.submit[r]=!1,n.setParentState(n.mergeParentForm(r,o))}).catch(function(e){a.errors.push({class:"error",message:e}),n.submit[r]=!1,n.setParentState(n.mergeParentForm(r,a))})}}},{key:"deleteFormElement",value:function(e,t){var n=this;return"object"===X(e.children)&&Object.keys(e.children).map(function(r){n.deleteFormElement(e.children[r],t).id===t.id&&delete e.children[r]}),"array"==typeof e.children&&e.children.map(function(r,a){(r=n.deleteFormElement(r,t)).id===t.id&&e.children.splice(a,1)}),e}},{key:"findElementById",value:function(e,t,n){var r=this;return"string"==typeof n.id&&n.id===t?n:"object"===X(e.children)?(Object.keys(e.children).map(function(a){var o=e.children[a];o.id===t&&(n=o),n=r.findElementById(e.children[a],t,n)}),n):"array"==typeof e.children?(e.children.map(function(e,a){e.id===t&&(n=e),n=r.findElementById(e,t,n)}),n):n}},{key:"deleteElement",value:function(e){var t=this,n=this.getParentForm(e),r=n;if(n=this.deleteFormElement(n,e),this.setParentState(this.mergeParentForm(this.getParentFormName(e),n)),"object"===X(e.children.id)){var a=e.id.replace("_"+e.name,""),o=this.findElementById(n,a,{}),l=o.element_delete_route;"object"!==X(o.element_delete_options)&&(o.element_delete_options={});var i=!0;if(Object.keys(o.element_delete_options).map(function(t){var n=o.element_delete_options[t];l=l.replace(t,e.children[n].value),parseInt(e.children[n].value)<1&&(i=!1)}),!1===i)return;Object(z.a)(l,[],!1).then(function(a){var o=n.errors;o=o.concat(a.errors),n.errors=o,"success"===a.status?("function"==typeof t.functions.deleteElementCallable&&(e=t.functions.deleteElementCallable(a,e)),t.setParentState(t.mergeParentForm(t.getParentFormName(e),n))):t.setParentState(t.mergeParentForm(t.getParentFormName(e),G({},r)))}).catch(function(e){var a=(n=G({},r)).errors;a.push({class:"error",message:e}),n.errors=a,t.setParentState(t.mergeParentForm(t.getParentFormName(form),n))})}}},{key:"replaceName",value:function(e,t){var n=this;return"object"===X(e.children)&&Object.keys(e.children).map(function(r){var a=n.replaceName(G({},e.children[r]),t);e.children[r]=a}),e.name=e.name.replace("__name__",t),e.id=e.id.replace("__name__",t),e.full_name=e.full_name.replace("__name__",t),"string"==typeof e.label&&(e.label=e.label.replace("__name__",t)),e}},{key:"replaceFormElement",value:function(e,t){var n=this;return"object"===X(e.children)&&Object.keys(e.children).map(function(r){n.replaceFormElement(e.children[r],t).id===t.id&&(e.children[r]=t)}),"array"==typeof e.children&&e.children.map(function(r,a){(r=n.replaceFormElement(r,t)).id===t.id&&(e.children[a]=t)}),e.id===t.id&&(e=t),e}},{key:"addElement",value:function(e){var t=n("xk4V")(),r=this.replaceName(G({},e.prototype),t),a=this.getParentForm(e),o=this.getParentFormName(e);delete r.children.id,void 0===e.children&&(e.children=[]),"function"==typeof this.functions.addElementCallable&&(r=this.functions.addElementCallable(r)),e.children.push(r),a=this.replaceFormElement(a,e),this.setState({forms:this.mergeParentForm(o,a)})}},{key:"isSubmit",value:function(){var e=this,t=!1;return Object.keys(this.submit).map(function(n){e.submit[n]&&(t=!0)}),t}},{key:"render",value:function(){return a.a.createElement("section",null,this.isSubmit()?a.a.createElement("div",{className:"waitOne info"},this.functions.translate("Let me ponder your request"),"..."):"",a.a.createElement(Y,{panels:this.panels,selectedPanel:this.state.selectedPanel,functions:this.functions,forms:this.state.forms,actionRoute:this.actionRoute}))}}])&&$(o.prototype,l),i&&$(o,i),t}();te.defaultProps={functions:{},translations:{},forms:{}}},Bcp6:function(e,t,n){},J5mc:function(e,t,n){"use strict";n.d(t,"a",function(){return a});n("yq1k"),n("zKZe"),n("07d7"),n("5s+n"),n("JTJg");function r(){return(r=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function a(e,t,n){var a={};t&&t.headers&&(a=t.headers,delete t.headers),a=r({},a,{"Content-Type":"application/json; charset=utf-8"}),null===n&&(n="en_GB"),!1!==n&&""!==e||(n="");var l=window.location.protocol+"//"+window.location.hostname+"/";return"/"===e[0]&&(e=e.substring(1)),fetch(l+n+e,r({},t,{credentials:"same-origin",headers:a})).then(o).then(function(e){return e.text().then(function(e){return e.includes("window.Sfdump")?(console.log(e),""):"string"==typeof e?JSON.parse(e):""})})}function o(e){if(e.status>=200&&e.status<400)return e;var t=new Error(e.statusText);throw t.response=e,t}},Ngpf:function(e,t,n){"use strict";n.r(t),n.d(t,"default",function(){return f});n("pNMO"),n("4Brf"),n("0oug"),n("4mDm"),n("2B1R"),n("wLYn"),n("zKZe"),n("uL8W"),n("eoL8"),n("NBAS"),n("tkto"),n("ExoC"),n("07d7"),n("PKPk"),n("3bBZ");var r=n("q1tI"),a=n.n(r),o=(n("17x9"),n("AMWX"));function l(e){return(l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function i(){return(i=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function c(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function s(e){return(s=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function u(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function m(e,t){return(m=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var f=function(e){function t(e){var n,r,a;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(a=s(t).call(this,e))||"object"!==l(a)&&"function"!=typeof a?u(r):a).otherProps=i({},e),n.extras=e.extras,n.functions={toggleSMSRows:n.toggleSMSRows.bind(u(n)),toggleMailerRows:n.toggleMailerRows.bind(u(n)),setParentState:n.setMyState.bind(u(n))},n.toggleSMSRowsOnValue=n.toggleSMSRowsOnValue.bind(u(n)),n.toggleMailerRowsOnValue=n.toggleMailerRowsOnValue.bind(u(n)),n.state={forms:e.forms},n}var n,f,d;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&m(e,t)}(t,r["Component"]),n=t,(f=[{key:"componentDidMount",value:function(){this.setMyState(i({},this.state.forms))}},{key:"setMyState",value:function(e){var t=e.SMS.children.smsSettings.children.Messenger__smsGateway.value;t=(e=this.toggleSMSRowsOnValue(t,i({},e)))["E-Mail"].children.emailSettings.children.System__enableMailerSMTP.value,e=this.toggleMailerRowsOnValue(t,e),this.setState({forms:i({},e)})}},{key:"toggleSMSRows",value:function(e,t){this.setMyState(this.toggleSMSRowsOnValue(e.target.value,i({},this.state.forms)))}},{key:"toggleMailerRows",value:function(e,t){this.setMyState(this.toggleMailerRowsOnValue(e.target.value,i({},this.state.forms)))}},{key:"toggleSMSRowsOnValue",value:function(e,t){""!==e&&null!==e||(e="No");var n=i({},t.SMS),r=this.extras[e],a=n.children.smsSettings;return Object.keys(r).map(function(e){var t=r[e];a.children[e].row_style="hidden",!0===t.visible&&(a.children[e].row_style="standard",a.children[e].label=t.label,a.children[e].help=null,"string"==typeof t.help&&(a.children[e].help=t.help))}),n.children.smsSettings=a,t.SMS=n,t}},{key:"toggleMailerRowsOnValue",value:function(e,t){""!==e&&null!==e||(e="No"),"Y"===e&&(e="SMTP");var n=i({},t["E-Mail"]),r=this.extras.mailer[e],a=n.children.emailSettings;return a.children.System__enableMailerSMTP.value=e,Object.keys(r).map(function(e){var t=r[e];a.children[e].row_style="hidden",!0===t.visible&&(a.children[e].row_style="standard",a.children[e].label=t.label,a.children[e].help=null,"string"==typeof t.help&&(a.children[e].help=t.help))}),n.children.emailSettings=a,t["E-Mail"]=n,t}},{key:"render",value:function(){return a.a.createElement(o.a,i({},this.otherProps,{forms:this.state.forms,functions:this.functions}))}}])&&c(n.prototype,f),d&&c(n,d),t}()},RrZK:function(e,t,n){"use strict";n.r(t),n.d(t,"default",function(){return f});n("pNMO"),n("4Brf"),n("0oug"),n("4mDm"),n("2B1R"),n("wLYn"),n("sMBO"),n("zKZe"),n("uL8W"),n("eoL8"),n("NBAS"),n("ExoC"),n("07d7"),n("rB9j"),n("PKPk"),n("UxlC"),n("3bBZ");var r=n("q1tI"),a=n.n(r),o=n("AMWX");function l(e){return(l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function i(){return(i=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function c(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function s(e){return(s=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function u(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function m(e,t){return(m=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var f=function(e){function t(e){var n,r,a;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(a=s(t).call(this,e))||"object"!==l(a)&&"function"!=typeof a?u(r):a).otherProps=i({},e),n.functions={manageLinkOrFile:n.manageLinkOrFile.bind(u(n)),addElementCallable:n.addElement.bind(u(n)),setParentState:n.setMyState.bind(u(n))},n.manageURLTypes=n.manageURLTypes.bind(u(n)),n.state={forms:e.forms},n}var n,f,d;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&m(e,t)}(t,r["Component"]),n=t,(f=[{key:"componentDidMount",value:function(){this.manageURLTypes(i({},this.state.forms.single))}},{key:"setMyState",value:function(e){this.manageURLTypes(i({},e.single))}},{key:"manageURLTypes",value:function(e){e.children.resources.children.map(function(t,n){"File"===t.children.type.value?e.children.resources.children[n].children.url.type="file":(e.children.resources.children[n].children.url.type="url",e.children.resources.children[n].children.type.value="Link")}),this.setState({forms:{single:e}})}},{key:"manageLinkOrFile",value:function(e,t){var n=t.id.replace("department_edit_resources_","");n=n.replace("_type","");var r={},a=null,o=i({},this.state.forms.single);o.children.resources.children.map(function(e,t){n===e.name&&(r=e,a=t)}),"File"===e.target.value?(r.children.url.type="file",r.children.type.value="File"):(r.children.url.type="url",r.children.type.value="Link"),o.children.resources.children[a]=r,this.setMyState({single:o})}},{key:"addElement",value:function(e){return e.children.type.value="Link",e.children.url.type="url",e.children.department.value=this.state.forms.single.children.id.value,e}},{key:"render",value:function(){return a.a.createElement(o.a,i({},this.otherProps,{forms:this.state.forms,functions:this.functions}))}}])&&c(n.prototype,f),d&&c(n,d),t}()},"ohO+":function(e,t,n){"use strict";function r(e,t,n){var r="_self";t&&"string"==typeof t.target&&(r=t.target);var a="";t&&"string"==typeof t.specs&&(a=t.specs),"boolean"==typeof n&&!1===n&&(n=""),null==n&&(n="en_GB"),""!==n&&(n="/"+n),window.open(window.location.protocol+"//"+window.location.hostname+n+e,r,a)}n.d(t,"a",function(){return r})},vSyi:function(e,t,n){"use strict";n.r(t);var r=n("q1tI"),a=n.n(r),o=n("i8i4"),l=n("AMWX"),i={};i.DepartmentEdit=n("RrZK").default,i.ThirdParty=n("Ngpf").default;var c=i;if("undefined"!==window.CONTAINER_PROPS){var s=window.CONTAINER_PROPS;for(var u in s){var m=s[u],f=document.getElementById(m.target);if(null!==m.application&&void 0!==c[m.application]&&null!==f){var d=c[m.application];Object(o.render)(a.a.createElement(d,{content:m.content,panels:m.panels,selectedPanel:m.selectedPanel,translations:m.translations,actionRoute:m.actionRoute,forms:m.forms,extras:m.extras}),f)}else null!==f&&Object(o.render)(a.a.createElement(l.a,{content:m.content,panels:m.panels,selectedPanel:m.selectedPanel,actionRoute:m.actionRoute,translations:m.translations,forms:m.forms}),f)}}}},[["vSyi","runtime",0,2]]]);