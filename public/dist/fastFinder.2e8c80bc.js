(window.webpackJsonp=window.webpackJsonp||[]).push([["fastFinder"],{"8xe5":function(e,t,n){"use strict";n.r(t);var s=n("q1tI"),a=n.n(s),o=n("i8i4"),r=(n("pNMO"),n("4Brf"),n("0oug"),n("ma9I"),n("TeQF"),n("yq1k"),n("4mDm"),n("2B1R"),n("wLYn"),n("uL8W"),n("eoL8"),n("NBAS"),n("ExoC"),n("07d7"),n("rB9j"),n("JTJg"),n("PKPk"),n("UxlC"),n("hByQ"),n("SYor"),n("3bBZ"),n("17x9"),n("1h/R")),i=n.n(r),l=n("ohO+");function u(e){return(u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}function c(e,t){for(var n=0;n<t.length;n++){var s=t[n];s.enumerable=s.enumerable||!1,s.configurable=!0,"value"in s&&(s.writable=!0),Object.defineProperty(e,s.key,s)}}function d(e){return(d=Object.setPrototypeOf?Object.getPrototypeOf:function(e){return e.__proto__||Object.getPrototypeOf(e)})(e)}function g(e){if(void 0===e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return e}function f(e,t){return(f=Object.setPrototypeOf||function(e,t){return e.__proto__=t,e})(e,t)}var p=function(e){function t(e){var n,s,a;return function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,t),s=this,(n=!(a=d(t).call(this,e))||"object"!==u(a)&&"function"!=typeof a?g(s):a).fastFindChoices=e.fastFindChoices,n.state={value:"",suggestions:[],fastFinderClass:"md:block absolute md:static top-0 right-0 w-full md:max-w-md p-2 sm:p-4 hidden"},n.onSuggestionsFetchRequested=n.onSuggestionsFetchRequested.bind(g(n)),n.onSuggestionsClearRequested=n.onSuggestionsClearRequested.bind(g(n)),n.onChange=n.onChange.bind(g(n)),n.getSuggestionValue=n.getSuggestionValue.bind(g(n)),n.renderSuggestion=n.renderSuggestion.bind(g(n)),n.toggleFastFinderClass=n.toggleFastFinderClass.bind(g(n)),n}var n,s,o;return function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function");e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,writable:!0,configurable:!0}}),t&&f(e,t)}(t,e),n=t,(s=[{key:"onSuggestionsFetchRequested",value:function(e){e.value.length>0&&this.setState({suggestions:this.getSuggestions(e.value)})}},{key:"onSuggestionsClearRequested",value:function(){this.setState({suggestions:[]})}},{key:"onChange",value:function(e){void 0!==e.target.value&&this.setState({value:e.target.value})}},{key:"getSuggestions",value:function(e){var t=[];return(e=e.trim().toLowerCase()).length>1&&this.fastFindChoices.filter((function(n){var s=n.suggestions.filter((function(t){var n=t.text+" "+t.search;return(n=n.trim().toLowerCase()).includes(e)}));if(s.length>0){var a=s.map((function(e){return{id:e.id,text:n.prefix+" - "+e.text}}));t=t.concat(a)}})),t}},{key:"getSuggestionValue",value:function(e){var t="/finder/{id}/redirect/",n=btoa(e.id);t=t.replace("{id}",n),Object(l.a)(t,[],!1),this.setState({value:e.text})}},{key:"renderSuggestion",value:function(e){return a.a.createElement("span",null,e.text)}},{key:"toggleFastFinderClass",value:function(){var e="md:block absolute md:static top-0 right-0 w-full md:max-w-md p-2 sm:p-4";this.state.fastFinderClass===e&&(e="md:block absolute md:static top-0 right-0 w-full md:max-w-md p-2 sm:p-4 hidden"),this.setState({fastFinderClass:e})}},{key:"render",value:function(){return a.a.createElement("div",null,a.a.createElement("button",{"data-toggle":"#fastFinder",className:"flex md:hidden items-center rounded bg-gray-300 mr-4 px-4 py-3 text-base active",onClick:this.toggleFastFinderClass},a.a.createElement("span",{className:"hidden sm:inline text-gray-600 text-xs font-bold uppercase pr-2"},this.props.trans_fastFind," "),a.a.createElement("span",{className:"fas fa-search fa-fw fa-2x text-gray-600",title:this.props.trans_fastFind})),a.a.createElement("div",{id:"fastFinder",className:this.state.fastFinderClass,style:{maxWidth:"350px"}},a.a.createElement("div",{className:"z-10 rounded border border-solid border-gray-300",style:{backgroundColor:"#fbfbfb"}},a.a.createElement("a",{"data-toggle":"#fastFinder",className:"p-2 pl-4 float-right text-xs underline md:hidden text-gray-600 ",href:"#",onClick:this.toggleFastFinderClass},a.a.createElement("span",{className:"far fa-times-circle fa-fw",title:this.props.trans_close})),a.a.createElement("div",{className:"py-2 md:py-1 px-2 border-solid border-0 border-b border-gray-300 md:text-right text-gray-700 text-xxs font-bold uppercase"},this.props.trans_fastFind,": ",this.props.trans_fastFindActions),a.a.createElement("div",{className:"w-full px-2 sm:py-2"},a.a.createElement("div",{className:"flex-1 relative"},a.a.createElement(i.a,{id:"token-input-fastFinderSearch",suggestions:this.state.suggestions,onSuggestionsFetchRequested:this.onSuggestionsFetchRequested,onSuggestionsClearRequested:this.onSuggestionsClearRequested,getSuggestionValue:this.getSuggestionValue,renderSuggestion:this.renderSuggestion,inputProps:{value:this.state.value,placeholder:this.props.trans_placeholder,onChange:this.onChange}}))),"Staff"===this.props.roleCategory?a.a.createElement("div",{className:"py-1 px-2 text-right text-gray-500 text-xxs font-normal italic"},this.props.trans_enrolmentCount):"")))}}])&&c(n.prototype,s),o&&c(n,o),t}(s.Component);n("qy9/");window.onload=function(){var e=document.getElementById("fastFinderWrapper");null===e?Object(o.render)(a.a.createElement("div",null," "),document.getElementById("dumpStuff")):Object(o.render)(a.a.createElement(p,window.FASTFINDER_PROPS),e)}},"ohO+":function(e,t,n){"use strict";function s(e,t,n){var s="_self";t&&"string"==typeof t.target&&(s=t.target);var a="";t&&"string"==typeof t.specs&&(a=t.specs),"boolean"==typeof n&&!1===n&&(n=""),null==n&&(n="en_GB"),""!==n&&(n="/"+n),window.open(window.location.protocol+"//"+window.location.hostname+n+e,s,a)}n.d(t,"a",(function(){return s}))},"qy9/":function(e,t,n){}},[["8xe5","runtime",0,3]]]);