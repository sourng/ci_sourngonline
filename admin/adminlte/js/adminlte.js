/*! AdminLTE app.js
* ================
* Main JS application file for AdminLTE v2. This file
* should be included in all pages. It controls some layout
* options and implements exclusive AdminLTE plugins.
*
* @Author  Almsaeed Studio
* @Support <https://www.almsaeedstudio.com>
* @Email   <abdullah@almsaeedstudio.com>
* @version 2.4.0
* @repository git://github.com/almasaeed2010/AdminLTE.git
* @license MIT <http://opensource.org/licenses/MIT>
*/
if(typeof jQuery==="undefined"){throw new Error("AdminLTE requires jQuery")}+function(e){"use strict";var t="lte.boxrefresh";var n={source:"",params:{},trigger:".refresh-btn",content:".box-body",loadInContent:true,responseType:"",overlayTemplate:'<div class="overlay"><div class="fa fa-refresh fa-spin"></div></div>',onLoadStart:function(){},onLoadDone:function(e){return e}};var o={data:'[data-widget="box-refresh"]'};var i=function(t,n){this.element=t;this.options=n;this.$overlay=e(n.overlay);if(n.source===""){throw new Error("Source url was not defined. Please specify a url in your BoxRefresh source option.")}this._setUpListeners();this.load()};i.prototype.load=function(){this._addOverlay();this.options.onLoadStart.call(e(this));e.get(this.options.source,this.options.params,function(t){if(this.options.loadInContent){e(this.options.content).html(t)}this.options.onLoadDone.call(e(this),t);this._removeOverlay()}.bind(this),this.options.responseType!==""&&this.options.responseType)};i.prototype._setUpListeners=function(){e(this.element).on("click",o.trigger,function(e){if(e)e.preventDefault();this.load()}.bind(this))};i.prototype._addOverlay=function(){e(this.element).append(this.$overlay)};i.prototype._removeOverlay=function(){e(this.element).remove(this.$overlay)};function a(o){return this.each(function(){var a=e(this);var r=a.data(t);if(!r){var s=e.extend({},n,a.data(),typeof o=="object"&&o);a.data(t,r=new i(a,s))}if(typeof r=="string"){if(typeof r[o]=="undefined"){throw new Error("No method named "+o)}r[o]()}})}var r=e.fn.boxRefresh;e.fn.boxRefresh=a;e.fn.boxRefresh.Constructor=i;e.fn.boxRefresh.noConflict=function(){e.fn.boxRefresh=r;return this};e(window).on("load",function(){e(o.data).each(function(){a.call(e(this))})})}(jQuery)+function(e){"use strict";var t="lte.boxwidget";var n={animationSpeed:500,collapseTrigger:'[data-widget="collapse"]',removeTrigger:'[data-widget="remove"]',collapseIcon:"fa-minus",expandIcon:"fa-plus",removeIcon:"fa-times"};var o={data:".box",collapsed:".collapsed-box",body:".box-body",footer:".box-footer",tools:".box-tools"};var i={collapsed:"collapsed-box"};var a={collapsed:"collapsed.boxwidget",expanded:"expanded.boxwidget",removed:"removed.boxwidget"};var r=function(e,t){this.element=e;this.options=t;this._setUpListeners()};r.prototype.toggle=function(){var t=!e(this.element).is(o.collapsed);if(t){this.collapse()}else{this.expand()}};r.prototype.expand=function(){var t=e.Event(a.expanded);var n=this.options.collapseIcon;var r=this.options.expandIcon;e(this.element).removeClass(i.collapsed);e(this.element).find(o.tools).find("."+r).removeClass(r).addClass(n);e(this.element).find(o.body+", "+o.footer).slideDown(this.options.animationSpeed,function(){e(this.element).trigger(t)}.bind(this))};r.prototype.collapse=function(){var t=e.Event(a.collapsed);var n=this.options.collapseIcon;var r=this.options.expandIcon;e(this.element).find(o.tools).find("."+n).removeClass(n).addClass(r);e(this.element).find(o.body+", "+o.footer).slideUp(this.options.animationSpeed,function(){e(this.element).addClass(i.collapsed);e(this.element).trigger(t)}.bind(this))};r.prototype.remove=function(){var t=e.Event(a.removed);e(this.element).slideUp(this.options.animationSpeed,function(){e(this.element).trigger(t);e(this.element).remove()}.bind(this))};r.prototype._setUpListeners=function(){var t=this;e(this.element).on("click",this.options.collapseTrigger,function(e){if(e)e.preventDefault();t.toggle()});e(this.element).on("click",this.options.removeTrigger,function(e){if(e)e.preventDefault();t.remove()})};function s(o){return this.each(function(){var i=e(this);var a=i.data(t);if(!a){var s=e.extend({},n,i.data(),typeof o=="object"&&o);i.data(t,a=new r(i,s))}if(typeof o=="string"){if(typeof a[o]=="undefined"){throw new Error("No method named "+o)}a[o]()}})}var d=e.fn.boxWidget;e.fn.boxWidget=s;e.fn.boxWidget.Constructor=r;e.fn.boxWidget.noConflict=function(){e.fn.boxWidget=d;return this};e(window).on("load",function(){e(o.data).each(function(){s.call(e(this))})})}(jQuery)+function(e){"use strict";var t="lte.controlsidebar";var n={slide:true};var o={sidebar:".control-sidebar",data:'[data-toggle="control-sidebar"]',open:".control-sidebar-open",bg:".control-sidebar-bg",wrapper:".wrapper",content:".content-wrapper",boxed:".layout-boxed"};var i={open:"control-sidebar-open",fixed:"fixed"};var a={collapsed:"collapsed.controlsidebar",expanded:"expanded.controlsidebar"};var r=function(e,t){this.element=e;this.options=t;this.hasBindedResize=false;this.init()};r.prototype.init=function(){if(!e(this.element).is(o.data)){e(this).on("click",this.toggle)}this.fix();e(window).resize(function(){this.fix()}.bind(this))};r.prototype.toggle=function(t){if(t)t.preventDefault();this.fix();if(!e(o.sidebar).is(o.open)&&!e("body").is(o.open)){this.expand()}else{this.collapse()}};r.prototype.expand=function(){if(!this.options.slide){e("body").addClass(i.open)}else{e(o.sidebar).addClass(i.open)}e(this.element).trigger(e.Event(a.expanded))};r.prototype.collapse=function(){e("body, "+o.sidebar).removeClass(i.open);e(this.element).trigger(e.Event(a.collapsed))};r.prototype.fix=function(){if(e("body").is(o.boxed)){this._fixForBoxed(e(o.bg))}};r.prototype._fixForBoxed=function(t){t.css({position:"absolute",height:e(o.wrapper).height()})};function s(o){return this.each(function(){var i=e(this);var a=i.data(t);if(!a){var s=e.extend({},n,i.data(),typeof o=="object"&&o);i.data(t,a=new r(i,s))}if(typeof o=="string")a.toggle()})}var d=e.fn.controlSidebar;e.fn.controlSidebar=s;e.fn.controlSidebar.Constructor=r;e.fn.controlSidebar.noConflict=function(){e.fn.controlSidebar=d;return this};e(document).on("click",o.data,function(t){if(t)t.preventDefault();s.call(e(this),"toggle")})}(jQuery)+function(e){"use strict";var t="lte.directchat";var n={data:'[data-widget="chat-pane-toggle"]',box:".direct-chat"};var o={open:"direct-chat-contacts-open"};var i=function(e){this.element=e};i.prototype.toggle=function(e){e.parents(n.box).first().toggleClass(o.open)};function a(n){return this.each(function(){var o=e(this);var a=o.data(t);if(!a){o.data(t,a=new i(o))}if(typeof n=="string")a.toggle(o)})}var r=e.fn.directChat;e.fn.directChat=a;e.fn.directChat.Constructor=i;e.fn.directChat.noConflict=function(){e.fn.directChat=r;return this};e(document).on("click",n.data,function(t){if(t)t.preventDefault();a.call(e(this),"toggle")})}(jQuery)+function(e){"use strict";var t="lte.layout";var n={slimscroll:true,resetHeight:true};var o={wrapper:".wrapper",contentWrapper:".content-wrapper",layoutBoxed:".layout-boxed",mainFooter:".main-footer",mainHeader:".main-header",sidebar:".sidebar",controlSidebar:".control-sidebar",fixed:".fixed",sidebarMenu:".sidebar-menu",logo:".main-header .logo"};var i={fixed:"fixed",holdTransition:"hold-transition"};var a=function(e){this.options=e;this.bindedResize=false;this.activate()};a.prototype.activate=function(){this.fix();this.fixSidebar();e("body").removeClass(i.holdTransition);if(this.options.resetHeight){e("body, html, "+o.wrapper).css({height:"auto","min-height":"100%"})}if(!this.bindedResize){e(window).resize(function(){this.fix();this.fixSidebar();e(o.logo+", "+o.sidebar).one("webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend",function(){this.fix();this.fixSidebar()}.bind(this))}.bind(this));this.bindedResize=true}e(o.sidebarMenu).on("expanded.tree",function(){this.fix();this.fixSidebar()}.bind(this));e(o.sidebarMenu).on("collapsed.tree",function(){this.fix();this.fixSidebar()}.bind(this))};a.prototype.fix=function(){e(o.layoutBoxed+" > "+o.wrapper).css("overflow","hidden");var t=e(o.mainFooter).outerHeight()||0;var n=e(o.mainHeader).outerHeight()+t;var a=e(window).height();var r=e(o.sidebar).height()||0;if(e("body").hasClass(i.fixed)){e(o.contentWrapper).css("min-height",a-t)}else{var s;if(a>=r){e(o.contentWrapper).css("min-height",a-n);s=a-n}else{e(o.contentWrapper).css("min-height",r);s=r}var d=e(o.controlSidebar);if(typeof d!=="undefined"){if(d.height()>s)e(o.contentWrapper).css("min-height",d.height())}}};a.prototype.fixSidebar=function(){if(!e("body").hasClass(i.fixed)){if(typeof e.fn.slimScroll!=="undefined"){e(o.sidebar).slimScroll({destroy:true}).height("auto")}return}if(this.options.slimscroll){if(typeof e.fn.slimScroll!=="undefined"){e(o.sidebar).slimScroll({height:e(window).height()-e(o.mainHeader).height()+"px",color:"rgba(0,0,0,0.2)",size:"3px"})}}};function r(o){return this.each(function(){var i=e(this);var r=i.data(t);if(!r){var s=e.extend({},n,i.data(),typeof o==="object"&&o);i.data(t,r=new a(s))}if(typeof o==="string"){if(typeof r[o]==="undefined"){throw new Error("No method named "+o)}r[o]()}})}var s=e.fn.layout;e.fn.layout=r;e.fn.layout.Constuctor=a;e.fn.layout.noConflict=function(){e.fn.layout=s;return this};e(window).on("load",function(){r.call(e("body"))})}(jQuery)+function(e){"use strict";var t="lte.pushmenu";var n={collapseScreenSize:767,expandOnHover:false,expandTransitionDelay:200};var o={collapsed:".sidebar-collapse",open:".sidebar-open",mainSidebar:".main-sidebar",contentWrapper:".content-wrapper",searchInput:".sidebar-form .form-control",button:'[data-toggle="push-menu"]',mini:".sidebar-mini",expanded:".sidebar-expanded-on-hover",layoutFixed:".fixed"};var i={collapsed:"sidebar-collapse",open:"sidebar-open",mini:"sidebar-mini",expanded:"sidebar-expanded-on-hover",expandFeature:"sidebar-mini-expand-feature",layoutFixed:"fixed"};var a={expanded:"expanded.pushMenu",collapsed:"collapsed.pushMenu"};var r=function(e){this.options=e;this.init()};r.prototype.init=function(){if(this.options.expandOnHover||e("body").is(o.mini+o.layoutFixed)){this.expandOnHover();e("body").addClass(i.expandFeature)}e(o.contentWrapper).click(function(){if(e(window).width()<=this.options.collapseScreenSize&&e("body").hasClass(i.open)){this.close()}}.bind(this));e(o.searchInput).click(function(e){e.stopPropagation()})};r.prototype.toggle=function(){var t=e(window).width();var n=!e("body").hasClass(i.collapsed);if(t<=this.options.collapseScreenSize){n=e("body").hasClass(i.open)}if(!n){this.open()}else{this.close()}};r.prototype.open=function(){var t=e(window).width();if(t>this.options.collapseScreenSize){e("body").removeClass(i.collapsed).trigger(e.Event(a.expanded))}else{e("body").addClass(i.open).trigger(e.Event(a.expanded))}};r.prototype.close=function(){var t=e(window).width();if(t>this.options.collapseScreenSize){e("body").addClass(i.collapsed).trigger(e.Event(a.collapsed))}else{e("body").removeClass(i.open+" "+i.collapsed).trigger(e.Event(a.collapsed))}};r.prototype.expandOnHover=function(){e(o.mainSidebar).hover(function(){if(e("body").is(o.mini+o.collapsed)&&e(window).width()>this.options.collapseScreenSize){this.expand()}}.bind(this),function(){if(e("body").is(o.expanded)){this.collapse()}}.bind(this))};r.prototype.expand=function(){setTimeout(function(){e("body").removeClass(i.collapsed).addClass(i.expanded)},this.options.expandTransitionDelay)};r.prototype.collapse=function(){setTimeout(function(){e("body").removeClass(i.expanded).addClass(i.collapsed)},this.options.expandTransitionDelay)};function s(o){return this.each(function(){var i=e(this);var a=i.data(t);if(!a){var s=e.extend({},n,i.data(),typeof o=="object"&&o);i.data(t,a=new r(s))}if(o==="toggle")a.toggle()})}var d=e.fn.pushMenu;e.fn.pushMenu=s;e.fn.pushMenu.Constructor=r;e.fn.pushMenu.noConflict=function(){e.fn.pushMenu=d;return this};e(document).on("click",o.button,function(t){t.preventDefault();s.call(e(this),"toggle")});e(window).on("load",function(){s.call(e(o.button))})}(jQuery)+function(e){"use strict";var t="lte.todolist";var n={onCheck:function(e){return e},onUnCheck:function(e){return e}};var o={data:'[data-widget="todo-list"]'};var i={done:"done"};var a=function(e,t){this.element=e;this.options=t;this._setUpListeners()};a.prototype.toggle=function(e){e.parents(o.li).first().toggleClass(i.done);if(!e.prop("checked")){this.unCheck(e);return}this.check(e)};a.prototype.check=function(e){this.options.onCheck.call(e)};a.prototype.unCheck=function(e){this.options.onUnCheck.call(e)};a.prototype._setUpListeners=function(){var t=this;e(this.element).on("change ifChanged","input:checkbox",function(){t.toggle(e(this))})};function r(o){return this.each(function(){var i=e(this);var r=i.data(t);if(!r){var s=e.extend({},n,i.data(),typeof o=="object"&&o);i.data(t,r=new a(i,s))}if(typeof r=="string"){if(typeof r[o]=="undefined"){throw new Error("No method named "+o)}r[o]()}})}var s=e.fn.todoList;e.fn.todoList=r;e.fn.todoList.Constructor=a;e.fn.todoList.noConflict=function(){e.fn.todoList=s;return this};e(window).on("load",function(){e(o.data).each(function(){r.call(e(this))})})}(jQuery)+function(e){"use strict";var t="lte.tree";var n={animationSpeed:500,accordion:true,followLink:false,trigger:".treeview a"};var o={tree:".tree",treeview:".treeview",treeviewMenu:".treeview-menu",open:".menu-open, .active",li:"li",data:'[data-widget="tree"]',active:".active"};var i={open:"menu-open",tree:"tree"};var a={collapsed:"collapsed.tree",expanded:"expanded.tree"};var r=function(t,n){this.element=t;this.options=n;e(this.element).addClass(i.tree);e(o.treeview+o.active,this.element).addClass(i.open);this._setUpListeners()};r.prototype.toggle=function(t,n){var a=t.next(o.treeviewMenu);var r=t.parent();var s=r.hasClass(i.open);if(!r.is(o.treeview)){return}if(!this.options.followLink||t.attr("href")==="#"){n.preventDefault()}if(!e(n.target).is("span:not(.pull-right-container)"))n.preventDefault();if(s){this.collapse(a,r)}else{this.expand(a,r)}};r.prototype.expand=function(t,n){var r=e.Event(a.expanded);if(this.options.accordion){var s=n.siblings(o.open);var d=s.children(o.treeviewMenu);this.collapse(d,s)}n.addClass(i.open);t.slideDown(this.options.animationSpeed,function(){e(this.element).trigger(r)}.bind(this))};r.prototype.collapse=function(t,n){var r=e.Event(a.collapsed);t.find(o.open).removeClass(i.open);n.removeClass(i.open);t.slideUp(this.options.animationSpeed,function(){t.find(o.open+" > "+o.treeview).slideUp();e(this.element).trigger(r)}.bind(this))};r.prototype._setUpListeners=function(){var t=this;e(this.element).on("click",this.options.trigger,function(n){t.toggle(e(this),n)})};function s(o){return this.each(function(){var i=e(this);var a=i.data(t);if(!a){var s=e.extend({},n,i.data(),typeof o=="object"&&o);i.data(t,new r(i,s))}})}var d=e.fn.tree;e.fn.tree=s;e.fn.tree.Constructor=r;e.fn.tree.noConflict=function(){e.fn.tree=d;return this};e(window).on("load",function(){e(o.data).each(function(){s.call(e(this))})})}(jQuery);