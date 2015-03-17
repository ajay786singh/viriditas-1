!function(){function e(e,t){t=t||{bubbles:!1,cancelable:!1,detail:void 0};var s=document.createEvent("CustomEvent");return s.initCustomEvent(e,t.bubbles,t.cancelable,t.detail),s}if(Array.prototype.indexOf||(Array.prototype.indexOf=function(e,t){var s,i,a=t?t:0;if(!this)throw new TypeError;if(i=this.length,0===i||a>=i)return-1;for(0>a&&(a=i-Math.abs(a)),s=a;i>s;s++)if(this[s]===e)return s;return-1}),Array.prototype.forEach||(Array.prototype.forEach=function(e){if(void 0===this||null===this)throw new TypeError;var t=Object(this),s=t.length>>>0;if("function"!=typeof e)throw new TypeError;for(var i=arguments.length>=2?arguments[1]:void 0,a=0;s>a;a++)a in t&&e.call(i,t[a],a,t)}),Event.prototype.preventDefault||(Event.prototype.preventDefault=function(){this.returnValue=!1}),Event.prototype.stopPropagation||(Event.prototype.stopPropagation=function(){this.cancelBubble=!0}),!Element.prototype.addEventListener){var t=[],s=function(e,s){var i=this,a=function(e){e.target=e.srcElement,e.currentTarget=i,s.handleEvent?s.handleEvent(e):s.call(i,e)};if("DOMContentLoaded"==e){var n=function(e){"complete"==document.readyState&&a(e)};if(document.attachEvent("onreadystatechange",n),t.push({object:this,type:e,listener:s,wrapper:n}),"complete"==document.readyState){var o=new Event;o.srcElement=window,n(o)}}else this.attachEvent("on"+e,a),t.push({object:this,type:e,listener:s,wrapper:a})},i=function(e,s){for(var i=0;i<t.length;){var a=t[i];if(a.object==this&&a.type==e&&a.listener==s){"DOMContentLoaded"==e?this.detachEvent("onreadystatechange",a.wrapper):this.detachEvent("on"+e,a.wrapper);break}++i}};Element.prototype.addEventListener=s,Element.prototype.removeEventListener=i,HTMLDocument&&(HTMLDocument.prototype.addEventListener=s,HTMLDocument.prototype.removeEventListener=i),Window&&(Window.prototype.addEventListener=s,Window.prototype.removeEventListener=i)}e.prototype=window.Event.prototype,window.CustomEvent=e}(),function(e,t,s){var i,a=/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),n=e.parent!=e.self&&location.host===parent.location.host,o=-1!=navigator.appVersion.indexOf("MSIE"),l=function(s,i){var a;if(this===e)return new l(s,i);for("string"==typeof s&&"#"===s[0]&&(s=t.getElementById(s.substr(1))),a=0;a<l.uid;a++)if(l.cache[a].data.select===s)return h.extend(l.cache[a].data.settings,i),l.cache[a];return"SELECT"===s.nodeName?this.init(s,i):void 0},r=function(){},d={initialize:r,change:r,open:r,close:r,search:"strict"},h={hasClass:function(e,t){var s=new RegExp("(^|\\s+)"+t+"(\\s+|$)");return e&&s.test(e.className)},addClass:function(e,t){e&&!h.hasClass(e,t)&&(e.className+=" "+t)},removeClass:function(e,t){var s=new RegExp("(^|\\s+)"+t+"(\\s+|$)");e&&(e.className=e.className.replace(s," "))},toggleClass:function(e,t){var s=h.hasClass(e,t)?"remove":"add";h[s+"Class"](e,t)},extend:function(e){return Array.prototype.slice.call(arguments,1).forEach(function(t){if(t)for(var s in t)e[s]=t[s]}),e},offset:function(s){var i=s.getBoundingClientRect()||{top:0,left:0},a=t.documentElement,n=o?a.scrollTop:e.pageYOffset,l=o?a.scrollLeft:e.pageXOffset;return{top:i.top+n-a.clientTop,left:i.left+l-a.clientLeft}},position:function(e,t){for(var s={top:0,left:0};e!==t;)s.top+=e.offsetTop,s.left+=e.offsetLeft,e=e.parentNode;return s},closest:function(e,t){for(;e;){if(e===t)return e;e=e.parentNode}return!1},create:function(e,s){var i,a=t.createElement(e);s||(s={});for(i in s)s.hasOwnProperty(i)&&("innerHTML"==i?a.innerHTML=s[i]:a.setAttribute(i,s[i]));return a},deferred:function(t){return function(){var s=arguments,i=this;e.setTimeout(function(){t.apply(i,s)},1)}}};l.cache={},l.uid=0,l.prototype={add:function(e,s){var i,a,n;"string"==typeof e&&(i=e,e=t.createElement("option"),e.text=i),"OPTION"===e.nodeName&&(a=h.create("li",{"class":"dk-option","data-value":e.value,innerHTML:e.text,role:"option","aria-selected":"false",id:"dk"+this.data.cacheID+"-"+(e.id||e.value.replace(" ","-"))}),h.addClass(a,e.className),this.length+=1,e.disabled&&(h.addClass(a,"dk-option-disabled"),a.setAttribute("aria-disabled","true")),this.data.select.add(e,s),"number"==typeof s&&(s=this.item(s)),this.options.indexOf(s)>-1?s.parentNode.insertBefore(a,s):this.data.elem.lastChild.appendChild(a),a.addEventListener("mouseover",this),n=this.options.indexOf(s),this.options.splice(n,0,a),e.selected&&this.select(n))},item:function(e){return e=0>e?this.options.length+e:e,this.options[e]||null},remove:function(e){var t=this.item(e);t.parentNode.removeChild(t),this.options.splice(e,1),this.data.select.remove(e),this.select(this.data.select.selectedIndex),this.length-=1},init:function(e,s){var o,r=l.build(e,"dk"+l.uid);if(this.data={},this.data.select=e,this.data.elem=r.elem,this.data.settings=h.extend({},d,s),this.disabled=e.disabled,this.form=e.form,this.length=e.length,this.multiple=e.multiple,this.options=r.options.slice(0),this.selectedIndex=e.selectedIndex,this.selectedOptions=r.selected.slice(0),this.value=e.value,this.data.cacheID=l.uid,l.cache[this.data.cacheID]=this,this.data.settings.initialize.call(this),l.uid+=1,this._changeListener||(e.addEventListener("change",this),this._changeListener=!0),!a||this.data.settings.mobile){if(e.parentNode.insertBefore(this.data.elem,e),e.setAttribute("data-dkCacheId",this.data.cacheID),this.data.elem.addEventListener("click",this),this.data.elem.addEventListener("keydown",this),this.data.elem.addEventListener("keypress",this),this.form&&this.form.addEventListener("reset",this),!this.multiple)for(o=0;o<this.options.length;o++)this.options[o].addEventListener("mouseover",this);i||(t.addEventListener("click",l.onDocClick),n&&parent.document.addEventListener("click",l.onDocClick),i=!0)}return this},close:function(){var e,t=this.data.elem;if(!this.isOpen||this.multiple)return!1;for(e=0;e<this.options.length;e++)h.removeClass(this.options[e],"dk-option-highlight");t.lastChild.setAttribute("aria-expanded","false"),h.removeClass(t.lastChild,"dk-select-options-highlight"),h.removeClass(t,"dk-select-open-(up|down)"),this.isOpen=!1,this.data.settings.close.call(this)},open:h.deferred(function(){var s,i,a,n,l,r,d=this.data.elem,c=d.lastChild;return l=o?h.offset(d).top-t.documentElement.scrollTop:h.offset(d).top-e.scrollY,r=e.innerHeight-(l+d.offsetHeight),this.isOpen||this.multiple?!1:(c.style.display="block",s=c.offsetHeight,c.style.display="",i=l>s,a=r>s,n=i&&!a?"-up":"-down",this.isOpen=!0,h.addClass(d,"dk-select-open"+n),c.setAttribute("aria-expanded","true"),this._scrollTo(this.options.length-1),this._scrollTo(this.selectedIndex),this.data.settings.open.call(this),void 0)}),disable:function(e,t){var i="dk-option-disabled";(0===arguments.length||"boolean"==typeof e)&&(t=e===s?!0:!1,e=this.data.elem,i="dk-select-disabled",this.disabled=t),t===s&&(t=!0),"number"==typeof e&&(e=this.item(e)),h[t?"addClass":"removeClass"](e,i)},select:function(e,t){var s,i,a,n,o=this.data.select;if("number"==typeof e&&(e=this.item(e)),"string"==typeof e)for(s=0;s<this.length;s++)this.options[s].getAttribute("data-value")==e&&(e=this.options[s]);return!e||"string"==typeof e||!t&&h.hasClass(e,"dk-option-disabled")?!1:h.hasClass(e,"dk-option")?(i=this.options.indexOf(e),a=o.options[i],this.multiple?(h.toggleClass(e,"dk-option-selected"),a.selected=!a.selected,h.hasClass(e,"dk-option-selected")?(e.setAttribute("aria-selected","true"),this.selectedOptions.push(e)):(e.setAttribute("aria-selected","false"),i=this.selectedOptions.indexOf(e),this.selectedOptions.splice(i,1))):(n=this.data.elem.firstChild,this.selectedOptions.length&&(h.removeClass(this.selectedOptions[0],"dk-option-selected"),this.selectedOptions[0].setAttribute("aria-selected","false")),h.addClass(e,"dk-option-selected"),e.setAttribute("aria-selected","true"),n.setAttribute("aria-activedescendant",e.id),n.className="dk-selected "+a.className,n.innerHTML=a.text,this.selectedOptions[0]=e,a.selected=!0),this.selectedIndex=o.selectedIndex,this.value=o.value,t||this.data.select.dispatchEvent(new CustomEvent("change")),e):void 0},selectOne:function(e,t){return this.reset(!0),this._scrollTo(e),this.select(e,t)},search:function(e,t){var s,i,a,n,o,l,r,d,h=this.data.select.options,c=[];if(!e)return this.options;for(t=t?t.toLowerCase():"strict",t="fuzzy"==t?2:"partial"==t?1:0,d=new RegExp((t?"":"^")+e,"i"),s=0;s<h.length;s++)if(a=h[s].text.toLowerCase(),2==t){for(i=e.toLowerCase().split(""),n=o=l=r=0;o<a.length;)a[o]===i[n]?(l+=1+l,n++):l=0,r+=l,o++;n==i.length&&c.push({e:this.options[s],s:r,i:s})}else d.test(a)&&c.push(this.options[s]);return 2==t&&(c=c.sort(function(e,t){return t.s-e.s||e.i-t.i}).reduce(function(e,t){return e[e.length]=t.e,e},[])),c},focus:function(){this.disabled||(this.multiple?this.data.elem:this.data.elem.children[0]).focus()},reset:function(e){var t,s=this.data.select;for(this.selectedOptions.length=0,t=0;t<s.options.length;t++)s.options[t].selected=!1,h.removeClass(this.options[t],"dk-option-selected"),this.options[t].setAttribute("aria-selected","false"),!e&&s.options[t].defaultSelected&&this.select(t,!0);this.selectedOptions.length||this.multiple||this.select(0,!0)},refresh:function(){this.dispose().init(this.data.select,this.data.settings)},dispose:function(){return delete l.cache[this.data.cacheID],this.data.elem.parentNode.removeChild(this.data.elem),this.data.select.removeAttribute("data-dkCacheId"),this},handleEvent:function(e){if(!this.disabled)switch(e.type){case"click":this._delegate(e);break;case"keydown":this._keyHandler(e);break;case"keypress":this._searchOptions(e);break;case"mouseover":this._highlight(e);break;case"reset":this.reset();break;case"change":this.data.settings.change.call(this)}},_delegate:function(t){var s,i,a,n,o=t.target;if(h.hasClass(o,"dk-option-disabled"))return!1;if(this.multiple){if(h.hasClass(o,"dk-option"))if(s=e.getSelection(),"Range"==s.type&&s.collapseToStart(),t.shiftKey)if(a=this.options.indexOf(this.selectedOptions[0]),n=this.options.indexOf(this.selectedOptions[this.selectedOptions.length-1]),i=this.options.indexOf(o),i>a&&n>i&&(i=a),i>n&&n>a&&(n=a),this.reset(!0),n>i)for(;n+1>i;)this.select(i++);else for(;i>n-1;)this.select(i--);else t.ctrlKey||t.metaKey?this.select(o):(this.reset(!0),this.select(o))}else this[this.isOpen?"close":"open"](),h.hasClass(o,"dk-option")&&this.select(o)},_highlight:function(e){var t,s=e.target;if(!this.multiple){for(t=0;t<this.options.length;t++)h.removeClass(this.options[t],"dk-option-highlight");h.addClass(this.data.elem.lastChild,"dk-select-options-highlight"),h.addClass(s,"dk-option-highlight")}},_keyHandler:function(e){var t,s,i=this.selectedOptions,a=this.options,n=1,o={tab:9,enter:13,esc:27,space:32,up:38,down:40};switch(e.keyCode){case o.up:n=-1;case o.down:if(e.preventDefault(),t=i[i.length-1],h.hasClass(this.data.elem.lastChild,"dk-select-options-highlight"))for(h.removeClass(this.data.elem.lastChild,"dk-select-options-highlight"),s=0;s<a.length;s++)h.hasClass(a[s],"dk-option-highlight")&&(h.removeClass(a[s],"dk-option-highlight"),t=a[s]);n=a.indexOf(t)+n,n>a.length-1?n=a.length-1:0>n&&(n=0),this.data.select.options[n].disabled||(this.reset(!0),this.select(n),this._scrollTo(n));break;case o.space:if(!this.isOpen){e.preventDefault(),this.open();break}case o.tab:case o.enter:for(n=0;n<a.length;n++)h.hasClass(a[n],"dk-option-highlight")&&this.select(n);case o.esc:this.isOpen&&(e.preventDefault(),this.close())}},_searchOptions:function(e){var t,i=this,a=String.fromCharCode(e.keyCode||e.which),n=function(){i.data.searchTimeout&&clearTimeout(i.data.searchTimeout),i.data.searchTimeout=setTimeout(function(){i.data.searchString=""},1e3)};this.data.searchString===s&&(this.data.searchString=""),n(),this.data.searchString+=a,t=this.search(this.data.searchString,this.data.settings.search),t.length&&(h.hasClass(t[0],"dk-option-disabled")||this.selectOne(t[0]))},_scrollTo:function(e){var t,s,i,a=this.data.elem.lastChild;return-1===e||"number"!=typeof e&&!e||!this.isOpen&&!this.multiple?!1:("number"==typeof e&&(e=this.item(e)),t=h.position(e,a).top,s=t-a.scrollTop,i=s+e.offsetHeight,i>a.offsetHeight?(t+=e.offsetHeight,a.scrollTop=t-a.offsetHeight):0>s&&(a.scrollTop=t),void 0)}},l.build=function(e,t){var s,i,a,n=[],o={elem:null,options:[],selected:[]},l=function(e){var s,i,a,n,r=[];switch(e.nodeName){case"OPTION":s=h.create("li",{"class":"dk-option ","data-value":e.value,innerHTML:e.text,role:"option","aria-selected":"false",id:t+"-"+(e.id||e.value.replace(" ","-"))}),h.addClass(s,e.className),e.disabled&&(h.addClass(s,"dk-option-disabled"),s.setAttribute("aria-disabled","true")),e.selected&&(h.addClass(s,"dk-option-selected"),s.setAttribute("aria-selected","true"),o.selected.push(s)),o.options.push(this.appendChild(s));break;case"OPTGROUP":for(i=h.create("li",{"class":"dk-optgroup"}),e.label&&i.appendChild(h.create("div",{"class":"dk-optgroup-label",innerHTML:e.label})),a=h.create("ul",{"class":"dk-optgroup-options"}),n=e.children.length;n--;r.unshift(e.children[n]));r.forEach(l,a),this.appendChild(i).appendChild(a)}};for(o.elem=h.create("div",{"class":"dk-select"+(e.multiple?"-multi":"")}),i=h.create("ul",{"class":"dk-select-options",id:t+"-listbox",role:"listbox"}),e.disabled&&h.addClass(o.elem,"dk-select-disabled"),o.elem.id=t+(e.id?"-"+e.id:""),h.addClass(o.elem,e.className),e.multiple?(o.elem.setAttribute("tabindex",e.getAttribute("tabindex")||"0"),i.setAttribute("aria-multiselectable","true")):(s=e.options[e.selectedIndex],o.elem.appendChild(h.create("div",{"class":"dk-selected "+s.className,tabindex:e.tabindex||0,innerHTML:s?s.text:"&nbsp;",id:t+"-combobox","aria-live":"assertive","aria-owns":i.id,role:"combobox"})),i.setAttribute("aria-expanded","false")),a=e.children.length;a--;n.unshift(e.children[a]));return n.forEach(l,o.elem.appendChild(i)),o},l.onDocClick=function(e){var t,s;if(1!==e.target.nodeType)return isClicked=!1,!1;null!==(t=e.target.getAttribute("data-dkcacheid"))&&l.cache[t].focus();for(s in l.cache)h.closest(e.target,l.cache[s].data.elem)||s===t||l.cache[s].disabled||l.cache[s].close()},e.Dropkick=l,e.jQuery!==s&&(e.jQuery.fn.dropkick=function(){var t=Array.prototype.slice.call(arguments);return e.jQuery(this).each(function(){t[0]&&"object"!=typeof t[0]?"string"==typeof t[0]&&l.prototype[t[0]].apply(new l(this),t.slice(1)):new l(this,t[0]||{})})})}(window,document);