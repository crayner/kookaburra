(window.webpackJsonp=window.webpackJsonp||[]).push([["container"],{AMWX:function(e,t,n){"use strict";n("pNMO"),n("4Brf"),n("0oug"),n("4mDm"),n("wLYn"),n("sMBO"),n("uL8W"),n("eoL8"),n("NBAS"),n("tkto"),n("ExoC"),n("07d7"),n("PKPk"),n("3bBZ");var r=n("q1tI"),o=n.n(r),a=(n("17x9"),n("zKZe"),n("76ZC")),l=n.n(a),i=(n("2B1R"),n("GTV5"));n("Bcp6"),n("ma9I"),n("pDQq"),n("rB9j"),n("UxlC");function c(e){return(c="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function s(e,t){var n={};return n.className=t,null!==e.row_class&&(n.className=e.row_class),!1!==e.row_id&&(n.id=e.row_id),n}function u(e,t){var n=!1===e.column_attr?{}:e.column_attr;return void 0!==n.class&&(n.className=n.class,delete n.class),void 0===n.className&&(n.className=t),n}function f(e,t,n){var r="object"!==c(e.attr)?{}:e.attr;return void 0!==r.class&&(r.className=r.class,delete r.class),void 0===r.className&&(r.className=t),r.id=e.id,r.name=e.full_name,r.onChange=null,!1===e.on_change&&"function"==typeof n.onElementChange?r.onChange=function(t){return n.onElementChange(t,e)}:"function"==typeof n[e.on_change]&&(r.onChange=function(t){return n[e.on_change](t,e)}),r.onClick=null,!1===e.on_click&&"function"==typeof n.onElementClick?r.onClick=function(t){return n.onElementClick(t,e)}:"function"==typeof n[e.on_click]&&(r.onClick=function(t){return n[e.on_click](t,e)}),!1!==e.multiple&&(r.multiple=!0),r["aria-describedby"]=e.id+"_help",r}function m(e){var t=e.form,n=(e.functions,e.columns,s(t,"break flex flex-col sm:flex-row justify-between content-center p-0")),r=u(t,"flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0");return o.a.createElement("tr",n,o.a.createElement("td",r,o.a.createElement("h3",null,t.label)))}var p=n("AEIT"),d=n.n(p),h=n("+z1p"),y=n.n(h);function b(){return(b=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function v(e){var t=e.form,n=e.functions;if(!1===t.header_row)return null;if(!0===t.header_row){var r=Object.keys(t.prototype.children).map(function(e){var n=t.prototype.children[e];if("hidden"!==n.type)return o.a.createElement("th",{className:"text-xxs sm:text-xs p-2 sm:py-3",key:n.name},n.label)});return r.push(o.a.createElement("th",{className:"shortWidth text-xxs sm:text-xs p-2 sm:py-3 text-center",key:"actions"},n.translate("Actions"))),o.a.createElement("thead",null,o.a.createElement("tr",null,r))}if("array"==typeof t.header_row){var a=t.header_row.map(function(e,t){return o.a.createElement("th",b({},e.attr,{key:e.name}),e.label)});return o.a.createElement("thead",null,o.a.createElement("tr",null,a))}}function E(e){var t=e.message,n=e.cancelMessage;return o.a.createElement("div",{className:t.class},t.message,o.a.createElement("button",{className:"button close "+t.class,onClick:function(){return n(t.id)},title:"Close Message",type:"button"},o.a.createElement("span",{className:"fas fa-times-circle fa-fw "+t.class})))}function g(e){return(g="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function O(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function w(e){return(w=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function _(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function k(e,t){return(k=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var j=function(e){function t(e){var n,r,o;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(o=w(t).call(this,e))||"object"!==g(o)&&"function"!=typeof o?_(r):o).cancelMessage=n.cancelMessage.bind(_(n)),n}var n,a,l;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&k(e,t)}(t,r["Component"]),n=t,(a=[{key:"cancelMessage",value:function(e){var t=this.props.messages;t.splice(e,1),this.setState({messages:t})}},{key:"render",value:function(){var e=this,t=this.props.messages.map(function(t,n){return t.id=n,o.a.createElement(E,{message:t,key:"message_"+t.id,cancelMessage:e.cancelMessage})});return 0===t.length?null:o.a.createElement("div",null,t)}}])&&O(n.prototype,a),l&&O(n,l),t}();function C(){return(C=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function S(e){var t=e.form,n=e.functions,r=e.errors,a=f(t,"leftIndent smallIntBorder standardForm striped",{});delete a.name;var l=o.a.createElement(v,{form:t,functions:n}),i=[];return Object.keys(t.children).map(function(e){var r=t.children[e],a=[],l=[];Object.keys(r.children).map(function(e){var t=C({},r.children[e]);"hidden"!==t.type?a.push(o.a.createElement("td",{key:e},o.a.createElement(L,{form:C({},t),functions:n}))):l.push(o.a.createElement(L,{form:C({},t),functions:n,key:e}))});var c=[];t.allow_delete&&c.push(o.a.createElement("button",{onClick:function(){return n.deleteElement(r)},className:"button text-gray-800",type:"button",key:"one"},o.a.createElement("span",{className:"far fa-trash-alt fa-fw"}))),a.push(o.a.createElement("td",{key:"actions"},l,o.a.createElement("div",{className:"text-center"},c))),i.push(o.a.createElement("tr",{key:e},a))}),i.push(o.a.createElement("tr",{key:"addRow"},o.a.createElement("td",{colSpan:n.getColumnCount()-1}),o.a.createElement("td",null,o.a.createElement("div",{className:"text-center"},o.a.createElement("button",{onClick:function(){return n.addElement(t)},className:"button text-gray-800",type:"button",key:"one"},o.a.createElement("span",{className:"fas fa-plus-circle fa-fw"})))))),o.a.createElement("div",{className:"collection"},o.a.createElement(j,{messages:r}),o.a.createElement("table",a,l,o.a.createElement("tbody",null,i)))}function P(e){return(P="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function F(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function x(e){return(x=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function N(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function T(e,t){return(T=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}S.defaultProps={errors:[]};var B=function(e){function t(e){var n,r,o;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(o=x(t).call(this,e))||"object"!==P(o)&&"function"!=typeof o?N(r):o).functions=e.functions,n.form=e.form,n.columnCount=0,n.functions.incColumnCount=n.incColumnCount.bind(N(n)),n.functions.getColumnCount=n.getColumnCount.bind(N(n)),n.state={errors:[],count:0,formCount:0},n}var n,a,l;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&T(e,t)}(t,r["Component"]),n=t,(a=[{key:"componentDidMount",value:function(){var e=this;Object.keys(this.form.prototype.children).map(function(t){"hidden"!==e.form.prototype.children[t].type&&e.functions.incColumnCount()}),this.functions.incColumnCount(),this.setState({count:I(this.form),formCount:this.functions.calcFormCount(this.form,0)})}},{key:"incColumnCount",value:function(){this.columnCount++}},{key:"getColumnCount",value:function(){return this.columnCount}},{key:"render",value:function(){return o.a.createElement(S,{form:this.form,functions:this.functions,errors:this.state.errors})}}])&&F(n.prototype,a),l&&F(n,l),t}();function I(e){var t=0;return Object.keys(e.children).map(function(n){"object"===P(e.children[n])&&t++}),t}function R(e){return void 0===e||(""===e||(null===e||(e===[]||e==={})))}function M(){return(M=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function V(e){var t=e.form,n=e.wrapper_attr,r=e.widget_attr,a=[];return R(t.placeholder)||a.push(o.a.createElement("option",{key:"placeholder",className:"text-gray-500",value:""},t.placeholder)),Object.keys(t.choices).map(function(e){a.push(o.a.createElement("option",{value:t.choices[e].value,key:t.choices[e].value},t.choices[e].label))}),o.a.createElement("div",n,o.a.createElement("select",M({multiple:t.multiple},r,{defaultValue:t.value}),a))}function D(){return(D=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function L(e){var t=e.form,n=e.functions,r=function(e,t){var n={};return n.className=t,null!==e.wrapper_class&&(n.className=e.wrapper_class),n}(t,"flex-1 relative"),a="form element "+t.type,l=f(t,"w-full",n),i=[];if(t.errors.length>0&&(r.className+=" errors",i=t.errors.map(function(e,t){return o.a.createElement("li",{key:t},e)})),"ckeditor"===t.type)return o.a.createElement("div",r,o.a.createElement(d.a,{editor:y.a,data:t.value,"aria-describedby":t.id+"_help",onChange:function(e,r){return n.onCKEditorChange(e,r,t)}}),t.errors.length>0?o.a.createElement("ul",null,i):"");if("submit"===t.type)return l.type="button",l.style={float:"right"},l.className="btn-gibbon",l.onClick=function(){return n.submitForm()},o.a.createElement("div",r,o.a.createElement("span",{className:"emphasis small"},"* ",t.help),o.a.createElement("button",l,t.label));if("hidden"===t.type)return l.type="hidden",o.a.createElement("input",l);if("text"===t.type)return l.type="text",o.a.createElement("div",r,o.a.createElement("input",D({},l,{defaultValue:t.value})),t.errors.length>0?o.a.createElement("ul",null,i):"");if("url"===t.type){l.type="url";var c={};return R(t.value)&&(c.disabled=!0),o.a.createElement("div",r,o.a.createElement("input",D({},l,{defaultValue:t.value})),o.a.createElement("button",D({type:"button",title:n.translate("Open Link"),className:"button button-right"},c,{onClick:function(){return n.openUrl(t.value)}}),o.a.createElement("span",{className:"fa-fw fas fa-external-link-alt"})),t.errors.length>0?o.a.createElement("ul",null,i):"")}if("file"===t.type){l.type="file";var s={};return R(t.value)&&(s.disabled=!0),o.a.createElement("div",r,o.a.createElement("input",l),o.a.createElement("button",D({type:"button",title:n.translate("File Download"),className:"button button-right"},s,{onClick:function(){return n.downloadFile(t.value)}}),o.a.createElement("span",{className:"fa-fw fas fa-file-download"})),t.errors.length>0?o.a.createElement("ul",null,i):"")}return"collection"===t.type?o.a.createElement(B,{form:t,functions:n,key:t.collection_key}):"choice"===t.type?o.a.createElement(V,{form:t,wrapper_attr:r,widget_attr:l}):(console.log(t),o.a.createElement("div",r,a))}function K(e){var t=e.form,n=e.functions,r=e.columns;!1===t.column_attr&&r>1&&(t.column_attr={}),r>1&&(t.column_attr.colSpan=r);var a=s(t,"flex flex-col sm:flex-row justify-between content-center p-0"),l=u(t,"flex-grow justify-center px-2 border-b-0 sm:border-b border-t-0");return o.a.createElement("tr",a,o.a.createElement("td",l,o.a.createElement(L,{form:t,functions:n})))}function A(e){var t=e.form,n=e.functions,r=e.columns;return t.columns=r,"hidden"===t.type&&"hidden"!==t.row_style&&(t.row_style="hidden"),"header"===t.row_style?o.a.createElement(m,{form:t,functions:n,columns:r}):"single"===t.row_style?o.a.createElement(K,{form:t,functions:n,columns:r}):"hidden"===t.row_style?o.a.createElement("tr",{style:{display:"none"}},o.a.createElement("td",null,o.a.createElement(L,{form:t,functions:n}))):(console.log(t),o.a.createElement("tr",null,o.a.createElement("td",null," Form Row ",t.row_style)))}var J=n("J5mc");function Z(e){return(Z="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function U(){return(U=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function W(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function q(e){return(q=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function z(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function G(e,t){return(G=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var X=function(e){function t(e){var n,r,o;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(o=q(t).call(this,e))||"object"!==Z(o)&&"function"!=typeof o?z(r):o).columnCount=e.form.column_count,n.functions=e.functions,n.columns=e.form.columns,n.functions.submitForm=n.submitForm.bind(z(n)),n.functions.onElementChange=n.onElementChange.bind(z(n)),n.functions.deleteElement=n.deleteElement.bind(z(n)),n.functions.addElement=n.addElement.bind(z(n)),n.functions.onCKEditorChange=n.onCKEditorChange.bind(z(n)),n.replaceName=n.replaceName.bind(z(n)),n.buildFormData=n.buildFormData.bind(z(n)),n.replaceFormElement=n.replaceFormElement.bind(z(n)),n.deleteFormElement=n.deleteFormElement.bind(z(n)),n.findElementById=n.findElementById.bind(z(n)),n.functions.calcFormCount=n.calcFormCount.bind(z(n)),n.calcFormCount=n.calcFormCount.bind(z(n)),n.state={errors:[],form:e.form,formCount:0},n.submit=!1,n}var a,l,i;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&G(e,t)}(t,r["Component"]),a=t,(l=[{key:"componentDidMount",value:function(){this.setState({formCount:this.calcFormCount(U({},this.state.form),0)})}},{key:"onCKEditorChange",value:function(e,t,n){var r=t.getData();this.setState({errors:this.state.errors,form:U({},this.changeFormValue(this.state.form,n,r))})}},{key:"calcFormCount",value:function(e,t){var n=this;return"array"==typeof e.children&&e.children.length>0?this.state.form.children.map(function(e){t=n.calcFormCount(e,t)}):"object"===Z(e.children)&&Object.keys(e.children).length>0&&Object.keys(e.children).map(function(r){var o=e.children[r];t=n.calcFormCount(o,t)}),++t}},{key:"changeFormValue",value:function(e,t,n){var r=this;return"array"==typeof e.children&&e.children.length>0?(e.children.map(function(o,a){o.id===t.id&&(o.value=n),e.children[a]=r.changeFormValue(o,t,n)}),e):"object"===Z(e.children)&&Object.keys(e.children).length>0?(Object.keys(e.children).map(function(o){var a=e.children[o];a.id===t.id&&(a.value=n),e.children[o]=r.changeFormValue(a,t,n)}),e):e}},{key:"onElementChange",value:function(e,t){this.setState({errors:this.state.errors,form:U({},this.changeFormValue(this.state.form,t,e.target.value))})}},{key:"buildFormData",value:function(e,t){var n=this;return"array"==typeof t.children&&t.children.length>0?(t.children.map(function(t){e[t.name]=n.buildFormData({},t)}),e):"object"===Z(t.children)&&Object.keys(t.children).length>0?(Object.keys(t.children).map(function(r){var o=t.children[r];e[o.name]=n.buildFormData({},o)}),e):t.value}},{key:"submitForm",value:function(){var e=this;if(!this.submit){this.submit=!0;var t=this.buildFormData({},this.state.form);Object(J.a)(this.state.form.action,{method:this.state.form.method,body:JSON.stringify(t)},!1).then(function(t){var n=e.state.errors;n=n.concat(t.errors);var r="function"==typeof e.functions.submitFormCallable?e.functions.submitFormCallable(t.form):t.form;e.submit=!1,e.setState({errors:n,form:r})}).catch(function(t){var n=e.state.errors;n.push({class:"error",message:t}),e.submit=!1,e.setState({errors:n})})}}},{key:"deleteFormElement",value:function(e,t){var n=this;return"object"===Z(e.children)&&Object.keys(e.children).map(function(r){n.deleteFormElement(e.children[r],t).id===t.id&&delete e.children[r]}),"array"==typeof e.children&&e.children.map(function(r,o){(r=n.deleteFormElement(r,t)).id===t.id&&e.children.splice(o,1)}),e}},{key:"findElementById",value:function(e,t,n){var r=this;return"string"==typeof n.id?n:("object"===Z(e.children)&&Object.keys(e.children).map(function(o){var a=e.children[o];a.id===t&&(n=a),r.findElementById(e.children[o],t,n)}),"array"==typeof e.children&&e.children.map(function(e,o){e.id===t&&(n=e),r.findElementById(e,t,n)}),n)}},{key:"deleteElement",value:function(e){var t=this,n=this.deleteFormElement(U({},this.state.form),e);if(this.setState({form:n,formCount:this.calcFormCount(U({},n),0)}),"object"===Z(e.children.id)){var r=e.id.replace("_"+e.name,""),o=this.findElementById(U({},this.state.form),r,{}),a=o.element_delete_route;"object"!==Z(o.element_delete_options)&&(o.element_delete_options={}),Object.keys(o.element_delete_options).map(function(t){var n=o.element_delete_options[t];a=a.replace(t,e.children[n].value)}),Object(J.a)(a,[],!1).then(function(n){var r=t.state.form;"function"==typeof t.functions.deleteElementCallable&&(r=t.functions.deleteElementCallable(n,e)),t.setState({errors:n.errors,form:r,formCount:t.calcFormCount(r,0)})})}}},{key:"replaceName",value:function(e,t){var n=this;return"object"===Z(e.children)&&Object.keys(e.children).map(function(r){var o=n.replaceName(U({},e.children[r]),t);e.children[r]=o}),e.name=e.name.replace("__name__",t),e.id=e.id.replace("__name__",t),e.full_name=e.full_name.replace("__name__",t),"string"==typeof e.label&&(e.label=e.label.replace("__name__",t)),e}},{key:"replaceFormElement",value:function(e,t){var n=this;return"object"===Z(e.children)&&Object.keys(e.children).map(function(r){n.replaceFormElement(e.children[r],t).id===t.id&&(e.children[r]=t)}),"array"==typeof e.children&&e.children.map(function(r,o){(r=n.replaceFormElement(r,t)).id===t.id&&(e.children[o]=t)}),e.id===t.id&&(e=t),e}},{key:"addElement",value:function(e){var t=n("xk4V")(),r=this.replaceName(U({},e.prototype),t);delete r.children.id,"function"==typeof this.functions.addElementCallable?r=this.functions.addElementCallable(r):e.children.id=r,this.setState({form:this.replaceFormElement(U({},this.state.form),U({},e))})}},{key:"render",value:function(){var e=this;if("table"===this.state.form.template){var t=Object.keys(this.state.form.children).map(function(t){var n=e.state.form.children[t];return o.a.createElement(A,{key:t,form:n,functions:e.functions,columns:e.columns})}),n={className:"smallIntBorder fullWidth standardForm relative"};return null!==this.state.form.row_class&&(n.className=this.state.form.row_class),o.a.createElement("form",U({action:this.state.form.action,id:this.state.form.id},this.state.form.attr,{method:void 0!==this.state.form.method?this.state.form.method:"POST"}),o.a.createElement(j,{messages:this.state.errors}),o.a.createElement("table",n,o.a.createElement("tbody",null,t)))}return""}}])&&W(a.prototype,l),i&&W(a,i),t}();function Y(){return(Y=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function Q(e){var t=e.panels,n=(e.forms,e.onSelectTab),r=e.selectedIndex,a=Object.keys(t).map(function(e){var n=t[e];return o.a.createElement(i.a,{key:n.name,disabled:n.disabled},n.label)}),c=Object.keys(t).map(function(n){var r=function(e,t){if(null!==e.content)return l()(e.content);if(null!==e.form)return o.a.createElement(X,Y({},t,{form:e.form}))}(t[n],e);return o.a.createElement(i.c,{key:n},r)});return o.a.createElement(i.d,{selectedIndex:r,onSelect:function(e){return n(e)}},o.a.createElement(i.b,null,a),c)}function H(e){return(H="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function $(){return($=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function ee(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function te(e){return(te=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function ne(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function re(e,t){return(re=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var oe=function(e){function t(e){var n,r,o;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(o=te(t).call(this,e))||"object"!==H(o)&&"function"!=typeof o?ne(r):o).panels=e.panels,n.forms=e.forms,n.selectedPanel=e.selectedPanel,n.state={tabIndex:n.panels[n.selectedPanel].index},n.onSelectTab=n.onSelectTab.bind(ne(n)),n}var n,a,i;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&re(e,t)}(t,r["Component"]),n=t,(a=[{key:"onSelectTab",value:function(e){this.setState({tabIndex:e})}},{key:"render",value:function(){if(1===Object.keys(this.panels).length){var e=Object.keys(this.panels)[0],t=this.panels[e];return null!==t.content?l()(t.content):o.a.createElement(X,$({},this.props,{form:this.forms[e]}))}return o.a.createElement(Q,$({},this.props,{panels:this.panels,selectedIndex:this.state.tabIndex,onSelectTab:this.onSelectTab}))}}])&&ee(n.prototype,a),i&&ee(n,i),t}(),ae=n("ohO+");function le(e){return(le="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function ie(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function ce(e){return(ce=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function se(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function ue(e,t){return(ue=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}n.d(t,"a",function(){return fe});var fe=function(e){function t(e){var n,r,o;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(o=ce(t).call(this,e))||"object"!==le(o)&&"function"!=typeof o?se(r):o).panels=e.panels?e.panels:{},n.content=e.content?e.content:null,n.selectedPanel=e.selectedPanel,n.functions=e.functions,n.translations=e.translations,n.actionRoute=e.actionRoute,n.forms=e.forms,0===Object.keys(n.panels).length&&null!==n.content&&(n.panels.default={},n.panels.default.name="default",n.panels.default.disabled=!0,n.panels.default.content=n.content),n.functions.translate=n.translate.bind(se(n)),n.functions.openUrl=n.openUrl.bind(se(n)),n.functions.downloadFile=n.downloadFile.bind(se(n)),n}var n,a,l;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&ue(e,t)}(t,r["Component"]),n=t,(a=[{key:"translate",value:function(e){return R(this.translations[e])?(console.error("Unable to translate: "+e),e):this.translations[e]}},{key:"downloadFile",value:function(e){var t="/resource/"+btoa(e)+"/"+this.actionRoute+"/download/";Object(ae.a)(t,{target:"_blank"},!1)}},{key:"openUrl",value:function(e){window.open(e,"_blank")}},{key:"render",value:function(){return o.a.createElement(oe,{panels:this.panels,selectedPanel:this.selectedPanel,functions:this.functions,forms:this.forms})}}])&&ie(n.prototype,a),l&&ie(n,l),t}();fe.defaultProps={functions:{},translations:{},forms:{}}},Bcp6:function(e,t,n){},J5mc:function(e,t,n){"use strict";n.d(t,"a",function(){return o});n("yq1k"),n("zKZe"),n("07d7"),n("5s+n"),n("JTJg");function r(){return(r=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function o(e,t,n){var o={};t&&t.headers&&(o=t.headers,delete t.headers),o=r({},o,{"Content-Type":"application/json; charset=utf-8"}),null===n&&(n="en_GB"),!1!==n&&""!==e||(n="");var l=window.location.protocol+"//"+window.location.hostname+"/";return"/"===e[0]&&(e=e.substring(1)),fetch(l+n+e,r({},t,{credentials:"same-origin",headers:o})).then(a).then(function(e){return e.text().then(function(e){return e.includes("window.Sfdump")?(console.log(e),""):"string"==typeof e?JSON.parse(e):""})})}function a(e){if(e.status>=200&&e.status<400)return e;var t=new Error(e.statusText);throw t.response=e,t}},RrZK:function(e,t,n){"use strict";n.r(t),n.d(t,"default",function(){return m});n("pNMO"),n("4Brf"),n("0oug"),n("4mDm"),n("2B1R"),n("wLYn"),n("sMBO"),n("zKZe"),n("uL8W"),n("eoL8"),n("NBAS"),n("tkto"),n("ExoC"),n("07d7"),n("rB9j"),n("PKPk"),n("UxlC"),n("3bBZ");var r=n("q1tI"),o=n.n(r),a=n("AMWX");function l(e){return(l="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function i(){return(i=Object.assign||function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var r in n)Object.prototype.hasOwnProperty.call(n,r)&&(e[r]=n[r])}return e}).apply(this,arguments)}function c(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,r.key,r)}}function s(e){return(s=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function u(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function f(e,t){return(f=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var m=function(e){function t(e){var n,r,o;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),r=this,(n=!(o=s(t).call(this,e))||"object"!==l(o)&&"function"!=typeof o?u(r):o).otherProps=i({},e),n.functions={manageLinkOrFile:n.manageLinkOrFile.bind(u(n)),addElementCallable:n.addElement.bind(u(n)),deleteElementCallable:n.deleteElement.bind(u(n)),submitFormCallable:n.submitForm.bind(u(n))},n.mapTypeValues=n.mapTypeValues.bind(u(n)),n.state={values:{}},n}var n,m,p;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&f(e,t)}(t,r["Component"]),n=t,(m=[{key:"componentDidMount",value:function(){this.setState({values:this.mapTypeValues(this.otherProps.forms.single)})}},{key:"mapTypeValues",value:function(e){var t=e.children.resources,n={};if("object"===l(t.children)){var r=[];Object.keys(t.children).map(function(e){r.push(t.children[e])}),t.children=r}return t.children.map(function(e,t){var r="File"===e.children.type.value?"File":"Link";e.children.url.type="File"===r?"file":"url",e.children.type.value=r,n[t]=r}),this.otherProps.forms.single.children.resources=i({},t),n}},{key:"manageLinkOrFile",value:function(e,t){var n=t.id.replace("department_edit_resources_","");n=n.replace("_type","");var r=this.otherProps.forms.single.children.resources.children[n];t.value=e.target.value,"File"===t.value?r.children.url.type="file":r.children.url.type="url",this.otherProps.forms.single.children.resources.children[n]=r;var o=this.state.values;o[n]=t.value,this.setState({values:o})}},{key:"addElement",value:function(e){e.children.type.value="Link",e.children.url.type="url",this.otherProps.forms.single.children.resources.children[e.name]=e;var t=this.state.values;return t[e.name]=e.children.type.value,this.setState({values:t}),e}},{key:"deleteElement",value:function(e,t){return"object"!==l(e.form)||"error"===e.status?(this.otherProps.forms.single.children.resources.children[t.name]=t,this.setState({values:this.mapTypeValues(this.otherProps.forms.single)}),this.otherProps.forms.single):(this.setState({values:this.mapTypeValues(e.form)}),this.otherProps.forms.single=i({},e.form),e.form)}},{key:"submitForm",value:function(e){return this.setState({values:this.mapTypeValues(e)}),e}},{key:"render",value:function(){return o.a.createElement(a.a,i({},this.otherProps,{functions:this.functions}))}}])&&c(n.prototype,m),p&&c(n,p),t}()},"ohO+":function(e,t,n){"use strict";function r(e,t,n){var r="_self";t&&"string"==typeof t.target&&(r=t.target);var o="";t&&"string"==typeof t.specs&&(o=t.specs),"boolean"==typeof n&&!1===n&&(n=""),null==n&&(n="en_GB"),""!==n&&(n="/"+n),window.open(window.location.protocol+"//"+window.location.hostname+n+e,r,o)}n.d(t,"a",function(){return r})},vSyi:function(e,t,n){"use strict";n.r(t);var r=n("q1tI"),o=n.n(r),a=n("i8i4"),l=n("AMWX"),i={};i.DepartmentEdit=n("RrZK").default;var c=i;if("undefined"!==window.CONTAINER_PROPS){var s=window.CONTAINER_PROPS;for(var u in s){var f=s[u],m=document.getElementById(f.target);if(null!==f.application&&void 0!==c[f.application]&&null!==m){var p=c[f.application];Object(a.render)(o.a.createElement(p,{content:f.content,panels:f.panels,selectedPanel:f.selectedPanel,translations:f.translations,actionRoute:f.actionRoute,forms:f.forms}),m)}else null!==m&&Object(a.render)(o.a.createElement(l.a,{content:f.content,panels:f.panels,selectedPanel:f.selectedPanel,actionRoute:f.actionRoute,translations:f.translations,forms:f.forms}),m)}}}},[["vSyi","runtime",0,2]]]);