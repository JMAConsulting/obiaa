(function(angular, $, _) {
  angular.module('ng')
    .config(['$locationProvider', function($locationProvider) {
      $locationProvider.hashPrefix('');
    }]);


angular.module('afformStandalone', CRM.angular.modules)

    .controller('AfformStandalonePageCtrl', function($scope) {
      $scope.afformTitle = '';
    });

})(angular, CRM.$, CRM._);

/*
 angular-file-upload v2.6.1
 https://github.com/nervgh/angular-file-upload
*/

!function(e,t){"object"==typeof exports&&"object"==typeof module?module.exports=t():"function"==typeof define&&define.amd?define([],t):"object"==typeof exports?exports["angular-file-upload"]=t():e["angular-file-upload"]=t()}(this,function(){return function(e){function t(n){if(o[n])return o[n].exports;var r=o[n]={exports:{},id:n,loaded:!1};return e[n].call(r.exports,r,r.exports,t),r.loaded=!0,r.exports}var o={};return t.m=e,t.c=o,t.p="",t(0)}([function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}var r=o(1),i=n(r),s=o(2),a=n(s),u=o(3),l=n(u),p=o(4),c=n(p),f=o(5),d=n(f),h=o(6),y=n(h),m=o(7),v=n(m),_=o(8),g=n(_),b=o(9),F=n(b),O=o(10),C=n(O),T=o(11),I=n(T),w=o(12),A=n(w),U=o(13),x=n(U);angular.module(i.default.name,[]).value("fileUploaderOptions",a.default).factory("FileUploader",l.default).factory("FileLikeObject",c.default).factory("FileItem",d.default).factory("FileDirective",y.default).factory("FileSelect",v.default).factory("FileDrop",F.default).factory("FileOver",C.default).factory("Pipeline",g.default).directive("nvFileSelect",I.default).directive("nvFileDrop",A.default).directive("nvFileOver",x.default).run(["FileUploader","FileLikeObject","FileItem","FileDirective","FileSelect","FileDrop","FileOver","Pipeline",function(e,t,o,n,r,i,s,a){e.FileLikeObject=t,e.FileItem=o,e.FileDirective=n,e.FileSelect=r,e.FileDrop=i,e.FileOver=s,e.Pipeline=a}])},function(e,t){e.exports={name:"angularFileUpload"}},function(e,t){"use strict";Object.defineProperty(t,"__esModule",{value:!0}),t.default={url:"/",alias:"file",headers:{},queue:[],progress:0,autoUpload:!1,removeAfterUpload:!1,method:"POST",filters:[],formData:[],queueLimit:Number.MAX_VALUE,withCredentials:!1,disableMultipart:!1}},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t,o,n,i,a,u,g){var b=n.File,F=n.FormData,O=function(){function n(t){r(this,n);var o=p(e);c(this,o,t,{isUploading:!1,_nextIndex:0,_directives:{select:[],drop:[],over:[]}}),this.filters.unshift({name:"queueLimit",fn:this._queueLimitFilter}),this.filters.unshift({name:"folder",fn:this._folderFilter})}return n.prototype.addToQueue=function(e,t,o){var n=this,r=this.isArrayLikeObject(e)?Array.prototype.slice.call(e):[e],i=this._getFilters(o),l=this.queue.length,p=[],c=function e(){var o=r.shift();if(v(o))return f();var l=n.isFile(o)?o:new a(o),c=n._convertFiltersToPipes(i),d=new g(c),h=function(t){var o=t.pipe.originalFilter,r=s(t.args,2),i=r[0],a=r[1];n._onWhenAddingFileFailed(i,o,a),e()},y=function(t,o){var r=new u(n,t,o);p.push(r),n.queue.push(r),n._onAfterAddingFile(r),e()};d.onThrown=h,d.onSuccessful=y,d.exec(l,t)},f=function(){n.queue.length!==l&&(n._onAfterAddingAll(p),n.progress=n._getTotalProgress()),n._render(),n.autoUpload&&n.uploadAll()};c()},n.prototype.removeFromQueue=function(e){var t=this.getIndexOfItem(e),o=this.queue[t];o.isUploading&&o.cancel(),this.queue.splice(t,1),o._destroy(),this.progress=this._getTotalProgress()},n.prototype.clearQueue=function(){for(;this.queue.length;)this.queue[0].remove();this.progress=0},n.prototype.uploadItem=function(e){var t=this.getIndexOfItem(e),o=this.queue[t],n=this.isHTML5?"_xhrTransport":"_iframeTransport";o._prepareToUploading(),this.isUploading||(this._onBeforeUploadItem(o),o.isCancel||(o.isUploading=!0,this.isUploading=!0,this[n](o),this._render()))},n.prototype.cancelItem=function(e){var t=this,o=this.getIndexOfItem(e),n=this.queue[o],r=this.isHTML5?"_xhr":"_form";if(n)if(n.isCancel=!0,n.isUploading)n[r].abort();else{var s=[void 0,0,{}],a=function(){t._onCancelItem.apply(t,[n].concat(s)),t._onCompleteItem.apply(t,[n].concat(s))};i(a)}},n.prototype.uploadAll=function(){var e=this.getNotUploadedItems().filter(function(e){return!e.isUploading});e.length&&(f(e,function(e){return e._prepareToUploading()}),e[0].upload())},n.prototype.cancelAll=function(){var e=this.getNotUploadedItems();f(e,function(e){return e.cancel()})},n.prototype.isFile=function(e){return this.constructor.isFile(e)},n.prototype.isFileLikeObject=function(e){return this.constructor.isFileLikeObject(e)},n.prototype.isArrayLikeObject=function(e){return this.constructor.isArrayLikeObject(e)},n.prototype.getIndexOfItem=function(e){return h(e)?e:this.queue.indexOf(e)},n.prototype.getNotUploadedItems=function(){return this.queue.filter(function(e){return!e.isUploaded})},n.prototype.getReadyItems=function(){return this.queue.filter(function(e){return e.isReady&&!e.isUploading}).sort(function(e,t){return e.index-t.index})},n.prototype.destroy=function(){var e=this;f(this._directives,function(t){f(e._directives[t],function(e){e.destroy()})})},n.prototype.onAfterAddingAll=function(e){},n.prototype.onAfterAddingFile=function(e){},n.prototype.onWhenAddingFileFailed=function(e,t,o){},n.prototype.onBeforeUploadItem=function(e){},n.prototype.onProgressItem=function(e,t){},n.prototype.onProgressAll=function(e){},n.prototype.onSuccessItem=function(e,t,o,n){},n.prototype.onErrorItem=function(e,t,o,n){},n.prototype.onCancelItem=function(e,t,o,n){},n.prototype.onCompleteItem=function(e,t,o,n){},n.prototype.onTimeoutItem=function(e){},n.prototype.onCompleteAll=function(){},n.prototype._getTotalProgress=function(e){if(this.removeAfterUpload)return e||0;var t=this.getNotUploadedItems().length,o=t?this.queue.length-t:this.queue.length,n=100/this.queue.length,r=(e||0)*n/100;return Math.round(o*n+r)},n.prototype._getFilters=function(e){if(!e)return this.filters;if(m(e))return e;var t=e.match(/[^\s,]+/g);return this.filters.filter(function(e){return t.indexOf(e.name)!==-1})},n.prototype._convertFiltersToPipes=function(e){var t=this;return e.map(function(e){var o=l(t,e.fn);return o.isAsync=3===e.fn.length,o.originalFilter=e,o})},n.prototype._render=function(){t.$$phase||t.$apply()},n.prototype._folderFilter=function(e){return!(!e.size&&!e.type)},n.prototype._queueLimitFilter=function(){return this.queue.length<this.queueLimit},n.prototype._isSuccessCode=function(e){return e>=200&&e<300||304===e},n.prototype._transformResponse=function(e,t){var n=this._headersGetter(t);return f(o.defaults.transformResponse,function(t){e=t(e,n)}),e},n.prototype._parseHeaders=function(e){var t,o,n,r={};return e?(f(e.split("\n"),function(e){n=e.indexOf(":"),t=e.slice(0,n).trim().toLowerCase(),o=e.slice(n+1).trim(),t&&(r[t]=r[t]?r[t]+", "+o:o)}),r):r},n.prototype._headersGetter=function(e){return function(t){return t?e[t.toLowerCase()]||null:e}},n.prototype._xhrTransport=function(e){var t,o=this,n=e._xhr=new XMLHttpRequest;if(e.disableMultipart?t=e._file:(t=new F,f(e.formData,function(e){f(e,function(e,o){t.append(o,e)})}),t.append(e.alias,e._file,e.file.name)),"number"!=typeof e._file.size)throw new TypeError("The file specified is no longer valid");n.upload.onprogress=function(t){var n=Math.round(t.lengthComputable?100*t.loaded/t.total:0);o._onProgressItem(e,n)},n.onload=function(){var t=o._parseHeaders(n.getAllResponseHeaders()),r=o._transformResponse(n.response,t),i=o._isSuccessCode(n.status)?"Success":"Error",s="_on"+i+"Item";o[s](e,r,n.status,t),o._onCompleteItem(e,r,n.status,t)},n.onerror=function(){var t=o._parseHeaders(n.getAllResponseHeaders()),r=o._transformResponse(n.response,t);o._onErrorItem(e,r,n.status,t),o._onCompleteItem(e,r,n.status,t)},n.onabort=function(){var t=o._parseHeaders(n.getAllResponseHeaders()),r=o._transformResponse(n.response,t);o._onCancelItem(e,r,n.status,t),o._onCompleteItem(e,r,n.status,t)},n.ontimeout=function(t){var r=o._parseHeaders(n.getAllResponseHeaders()),i="Request Timeout.";o._onTimeoutItem(e),o._onCompleteItem(e,i,408,r)},n.open(e.method,e.url,!0),n.timeout=e.timeout||0,n.withCredentials=e.withCredentials,f(e.headers,function(e,t){n.setRequestHeader(t,e)}),n.send(t)},n.prototype._iframeTransport=function(e){var t=this,o=_('<form style="display: none;" />'),n=_('<iframe name="iframeTransport'+Date.now()+'">'),r=e._input,i=0,s=null,a=!1;e._form&&e._form.replaceWith(r),e._form=o,r.prop("name",e.alias),f(e.formData,function(e){f(e,function(e,t){var n=_('<input type="hidden" name="'+t+'" />');n.val(e),o.append(n)})}),o.prop({action:e.url,method:"POST",target:n.prop("name"),enctype:"multipart/form-data",encoding:"multipart/form-data"}),n.bind("load",function(){var o="",r=200;try{o=n[0].contentDocument.body.innerHTML}catch(e){r=500}if(s&&clearTimeout(s),s=null,a)return!1;var i={response:o,status:r,dummy:!0},u={},l=t._transformResponse(i.response,u);t._onSuccessItem(e,l,i.status,u),t._onCompleteItem(e,l,i.status,u)}),o.abort=function(){var i,s={status:0,dummy:!0},a={};n.unbind("load").prop("src","javascript:false;"),o.replaceWith(r),t._onCancelItem(e,i,s.status,a),t._onCompleteItem(e,i,s.status,a)},r.after(o),o.append(r).append(n),i=e.timeout||0,s=null,i&&(s=setTimeout(function(){a=!0,e.isCancel=!0,e.isUploading&&(n.unbind("load").prop("src","javascript:false;"),o.replaceWith(r));var i={},s="Request Timeout.";t._onTimeoutItem(e),t._onCompleteItem(e,s,408,i)},i)),o[0].submit()},n.prototype._onWhenAddingFileFailed=function(e,t,o){this.onWhenAddingFileFailed(e,t,o)},n.prototype._onAfterAddingFile=function(e){this.onAfterAddingFile(e)},n.prototype._onAfterAddingAll=function(e){this.onAfterAddingAll(e)},n.prototype._onBeforeUploadItem=function(e){e._onBeforeUpload(),this.onBeforeUploadItem(e)},n.prototype._onProgressItem=function(e,t){var o=this._getTotalProgress(t);this.progress=o,e._onProgress(t),this.onProgressItem(e,t),this.onProgressAll(o),this._render()},n.prototype._onSuccessItem=function(e,t,o,n){e._onSuccess(t,o,n),this.onSuccessItem(e,t,o,n)},n.prototype._onErrorItem=function(e,t,o,n){e._onError(t,o,n),this.onErrorItem(e,t,o,n)},n.prototype._onCancelItem=function(e,t,o,n){e._onCancel(t,o,n),this.onCancelItem(e,t,o,n)},n.prototype._onCompleteItem=function(e,t,o,n){e._onComplete(t,o,n),this.onCompleteItem(e,t,o,n);var r=this.getReadyItems()[0];return this.isUploading=!1,y(r)?void r.upload():(this.onCompleteAll(),this.progress=this._getTotalProgress(),void this._render())},n.prototype._onTimeoutItem=function(e){e._onTimeout(),this.onTimeoutItem(e)},n.isFile=function(e){return b&&e instanceof b},n.isFileLikeObject=function(e){return e instanceof a},n.isArrayLikeObject=function(e){return d(e)&&"length"in e},n.inherit=function(e,t){e.prototype=Object.create(t.prototype),e.prototype.constructor=e,e.super_=t},n}();return O.prototype.isHTML5=!(!b||!F),O.isHTML5=O.prototype.isHTML5,O}Object.defineProperty(t,"__esModule",{value:!0});var s=function(){function e(e,t){var o=[],n=!0,r=!1,i=void 0;try{for(var s,a=e[Symbol.iterator]();!(n=(s=a.next()).done)&&(o.push(s.value),!t||o.length!==t);n=!0);}catch(e){r=!0,i=e}finally{try{!n&&a.return&&a.return()}finally{if(r)throw i}}return o}return function(t,o){if(Array.isArray(t))return t;if(Symbol.iterator in Object(t))return e(t,o);throw new TypeError("Invalid attempt to destructure non-iterable instance")}}();t.default=i;var a=o(1),u=(n(a),angular),l=u.bind,p=u.copy,c=u.extend,f=u.forEach,d=u.isObject,h=u.isNumber,y=u.isDefined,m=u.isArray,v=u.isUndefined,_=u.element;i.$inject=["fileUploaderOptions","$rootScope","$http","$window","$timeout","FileLikeObject","FileItem","Pipeline"]},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(){return function(){function e(t){r(this,e);var o=l(t),n=o?t.value:t,i=p(n)?"FakePath":"Object",s="_createFrom"+i;this[s](n,t)}return e.prototype._createFromFakePath=function(e,t){this.lastModifiedDate=null,this.size=null,this.type="like/"+e.slice(e.lastIndexOf(".")+1).toLowerCase(),this.name=e.slice(e.lastIndexOf("/")+e.lastIndexOf("\\")+2),this.input=t},e.prototype._createFromObject=function(e){this.lastModifiedDate=u(e.lastModifiedDate),this.size=e.size,this.type=e.type,this.name=e.name,this.input=e.input},e}()}Object.defineProperty(t,"__esModule",{value:!0}),t.default=i;var s=o(1),a=(n(s),angular),u=a.copy,l=a.isElement,p=a.isString},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t){return function(){function o(e,n,i){r(this,o);var s=!!n.input,a=s?p(n.input):null,c=s?null:n;l(this,{url:e.url,alias:e.alias,headers:u(e.headers),formData:u(e.formData),removeAfterUpload:e.removeAfterUpload,withCredentials:e.withCredentials,disableMultipart:e.disableMultipart,method:e.method,timeout:e.timeout},i,{uploader:e,file:new t(n),isReady:!1,isUploading:!1,isUploaded:!1,isSuccess:!1,isCancel:!1,isError:!1,progress:0,index:null,_file:c,_input:a}),a&&this._replaceNode(a)}return o.prototype.upload=function(){try{this.uploader.uploadItem(this)}catch(t){var e=t.name+":"+t.message;this.uploader._onCompleteItem(this,e,t.code,[]),this.uploader._onErrorItem(this,e,t.code,[])}},o.prototype.cancel=function(){this.uploader.cancelItem(this)},o.prototype.remove=function(){this.uploader.removeFromQueue(this)},o.prototype.onBeforeUpload=function(){},o.prototype.onProgress=function(e){},o.prototype.onSuccess=function(e,t,o){},o.prototype.onError=function(e,t,o){},o.prototype.onCancel=function(e,t,o){},o.prototype.onComplete=function(e,t,o){},o.prototype.onTimeout=function(){},o.prototype._onBeforeUpload=function(){this.isReady=!0,this.isUploading=!1,this.isUploaded=!1,this.isSuccess=!1,this.isCancel=!1,this.isError=!1,this.progress=0,this.onBeforeUpload()},o.prototype._onProgress=function(e){this.progress=e,this.onProgress(e)},o.prototype._onSuccess=function(e,t,o){this.isReady=!1,this.isUploading=!1,this.isUploaded=!0,this.isSuccess=!0,this.isCancel=!1,this.isError=!1,this.progress=100,this.index=null,this.onSuccess(e,t,o)},o.prototype._onError=function(e,t,o){this.isReady=!1,this.isUploading=!1,this.isUploaded=!0,this.isSuccess=!1,this.isCancel=!1,this.isError=!0,this.progress=0,this.index=null,this.onError(e,t,o)},o.prototype._onCancel=function(e,t,o){this.isReady=!1,this.isUploading=!1,this.isUploaded=!1,this.isSuccess=!1,this.isCancel=!0,this.isError=!1,this.progress=0,this.index=null,this.onCancel(e,t,o)},o.prototype._onComplete=function(e,t,o){this.onComplete(e,t,o),this.removeAfterUpload&&this.remove()},o.prototype._onTimeout=function(){this.isReady=!1,this.isUploading=!1,this.isUploaded=!1,this.isSuccess=!1,this.isCancel=!1,this.isError=!0,this.progress=0,this.index=null,this.onTimeout()},o.prototype._destroy=function(){this._input&&this._input.remove(),this._form&&this._form.remove(),delete this._form,delete this._input},o.prototype._prepareToUploading=function(){this.index=this.index||++this.uploader._nextIndex,this.isReady=!0},o.prototype._replaceNode=function(t){var o=e(t.clone())(t.scope());o.prop("value",null),t.css("display","none"),t.after(o)},o}()}Object.defineProperty(t,"__esModule",{value:!0}),t.default=i;var s=o(1),a=(n(s),angular),u=a.copy,l=a.extend,p=a.element;a.isElement;i.$inject=["$compile","FileLikeObject"]},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(){var e=function(){function e(t){r(this,e),u(this,t),this.uploader._directives[this.prop].push(this),this._saveLinks(),this.bind()}return e.prototype.bind=function(){for(var e in this.events){var t=this.events[e];this.element.bind(e,this[t])}},e.prototype.unbind=function(){for(var e in this.events)this.element.unbind(e,this.events[e])},e.prototype.destroy=function(){var e=this.uploader._directives[this.prop].indexOf(this);this.uploader._directives[this.prop].splice(e,1),this.unbind()},e.prototype._saveLinks=function(){for(var e in this.events){var t=this.events[e];this[t]=this[t].bind(this)}},e}();return e.prototype.events={},e}Object.defineProperty(t,"__esModule",{value:!0}),t.default=i;var s=o(1),a=(n(s),angular),u=a.extend},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function s(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}function a(e,t){return function(t){function o(e){r(this,o);var n=p(e,{events:{$destroy:"destroy",change:"onChange"},prop:"select"}),s=i(this,t.call(this,n));return s.uploader.isHTML5||s.element.removeAttr("multiple"),s.element.prop("value",null),s}return s(o,t),o.prototype.getOptions=function(){},o.prototype.getFilters=function(){},o.prototype.isEmptyAfterSelection=function(){return!!this.element.attr("multiple")},o.prototype.onChange=function(){var t=this.uploader.isHTML5?this.element[0].files:this.element[0],o=this.getOptions(),n=this.getFilters();this.uploader.isHTML5||this.destroy(),this.uploader.addToQueue(t,o,n),this.isEmptyAfterSelection()&&(this.element.prop("value",null),this.element.replaceWith(e(this.element.clone())(this.scope)))},o}(t)}Object.defineProperty(t,"__esModule",{value:!0}),t.default=a;var u=o(1),l=(n(u),angular),p=l.extend;a.$inject=["$compile","FileDirective"]},function(e,t){"use strict";function o(e){if(Array.isArray(e)){for(var t=0,o=Array(e.length);t<e.length;t++)o[t]=e[t];return o}return Array.from(e)}function n(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function r(e){return function(){function t(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:[];n(this,t),this.pipes=e}return t.prototype.next=function(t){var n=this.pipes.shift();if(a(n))return void this.onSuccessful.apply(this,o(t));var r=new Error("The filter has not passed");if(r.pipe=n,r.args=t,n.isAsync){var i=e.defer(),u=s(this,this.next,t),l=s(this,this.onThrown,r);i.promise.then(u,l),n.apply(void 0,o(t).concat([i]))}else{var p=Boolean(n.apply(void 0,o(t)));p?this.next(t):this.onThrown(r)}},t.prototype.exec=function(){for(var e=arguments.length,t=Array(e),o=0;o<e;o++)t[o]=arguments[o];this.next(t)},t.prototype.onThrown=function(e){},t.prototype.onSuccessful=function(){},t}()}Object.defineProperty(t,"__esModule",{value:!0}),t.default=r;var i=angular,s=i.bind,a=i.isUndefined;r.$inject=["$q"]},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function s(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}function a(e){return function(e){function t(o){r(this,t);var n=p(o,{events:{$destroy:"destroy",drop:"onDrop",dragover:"onDragOver",dragleave:"onDragLeave"},prop:"drop"});return i(this,e.call(this,n))}return s(t,e),t.prototype.getOptions=function(){},t.prototype.getFilters=function(){},t.prototype.onDrop=function(e){var t=this._getTransfer(e);if(t){var o=this.getOptions(),n=this.getFilters();this._preventAndStop(e),c(this.uploader._directives.over,this._removeOverClass,this),this.uploader.addToQueue(t.files,o,n)}},t.prototype.onDragOver=function(e){var t=this._getTransfer(e);this._haveFiles(t.types)&&(t.dropEffect="copy",this._preventAndStop(e),c(this.uploader._directives.over,this._addOverClass,this))},t.prototype.onDragLeave=function(e){e.currentTarget!==this.element[0]&&(this._preventAndStop(e),c(this.uploader._directives.over,this._removeOverClass,this))},t.prototype._getTransfer=function(e){return e.dataTransfer?e.dataTransfer:e.originalEvent.dataTransfer},t.prototype._preventAndStop=function(e){e.preventDefault(),e.stopPropagation()},t.prototype._haveFiles=function(e){return!!e&&(e.indexOf?e.indexOf("Files")!==-1:!!e.contains&&e.contains("Files"))},t.prototype._addOverClass=function(e){e.addOverClass()},t.prototype._removeOverClass=function(e){e.removeOverClass()},t}(e)}Object.defineProperty(t,"__esModule",{value:!0}),t.default=a;var u=o(1),l=(n(u),angular),p=l.extend,c=l.forEach;a.$inject=["FileDirective"]},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}function i(e,t){if(!e)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return!t||"object"!=typeof t&&"function"!=typeof t?e:t}function s(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Super expression must either be null or a function, not "+typeof t);e.prototype=Object.create(t&&t.prototype,{constructor:{value:e,enumerable:!1,writable:!0,configurable:!0}}),t&&(Object.setPrototypeOf?Object.setPrototypeOf(e,t):e.__proto__=t)}function a(e){return function(e){function t(o){r(this,t);var n=p(o,{events:{$destroy:"destroy"},prop:"over",overClass:"nv-file-over"});return i(this,e.call(this,n))}return s(t,e),t.prototype.addOverClass=function(){this.element.addClass(this.getOverClass())},t.prototype.removeOverClass=function(){this.element.removeClass(this.getOverClass())},t.prototype.getOverClass=function(){return this.overClass},t}(e)}Object.defineProperty(t,"__esModule",{value:!0}),t.default=a;var u=o(1),l=(n(u),angular),p=l.extend;a.$inject=["FileDirective"]},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t,o){return{link:function(n,r,i){var s=n.$eval(i.uploader);if(!(s instanceof t))throw new TypeError('"Uploader" must be an instance of FileUploader');var a=new o({uploader:s,element:r,scope:n});a.getOptions=e(i.options).bind(a,n),a.getFilters=function(){return i.filters}}}}Object.defineProperty(t,"__esModule",{value:!0}),t.default=r;var i=o(1);n(i);r.$inject=["$parse","FileUploader","FileSelect"]},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t,o){return{link:function(n,r,i){var s=n.$eval(i.uploader);if(!(s instanceof t))throw new TypeError('"Uploader" must be an instance of FileUploader');if(s.isHTML5){var a=new o({uploader:s,element:r});a.getOptions=e(i.options).bind(a,n),a.getFilters=function(){return i.filters}}}}}Object.defineProperty(t,"__esModule",{value:!0}),t.default=r;var i=o(1);n(i);r.$inject=["$parse","FileUploader","FileDrop"]},function(e,t,o){"use strict";function n(e){return e&&e.__esModule?e:{default:e}}function r(e,t){return{link:function(o,n,r){var i=o.$eval(r.uploader);if(!(i instanceof e))throw new TypeError('"Uploader" must be an instance of FileUploader');var s=new t({uploader:i,element:n});s.getOverClass=function(){return r.overClass||s.overClass}}}}Object.defineProperty(t,"__esModule",{value:!0}),t.default=r;var i=o(1);n(i);r.$inject=["FileUploader","FileOver"]}])});

(function(angular, $, _) {
  angular.module('api4', CRM.angRequires('api4'));

angular.module('api4').factory('crmApi4', function($q) {
    var crmApi4 = function(entity, action, params, index) {
      var deferred = $q.defer();
      var p;
      var backend = crmApi4.backend || CRM.api4;
      if (_.isObject(entity)) {
        /*jshint -W061 */
        p = backend(eval('('+angular.toJson(entity)+')'), action);
      } else {
        /*jshint -W061 */
        p = backend(entity, action, eval('('+angular.toJson(params)+')'), index);
      }
      p.then(
        function(result) {
          deferred.resolve(result);
        },
        function(error) {
          deferred.reject(error);
        }
      );
      return deferred.promise;
    };
    crmApi4.backend = null;
    crmApi4.val = function(value) {
      var d = $.Deferred();
      d.resolve(value);
      return d.promise();
    };
    return crmApi4;
  });

})(angular, CRM.$, CRM._);

(function(angular, CRM) {
  var crmApp = angular.module('crmApp', CRM.angular.modules);

  crmApp.config(['$routeProvider',
    function($routeProvider) {

      if (CRM.crmApp.defaultRoute) {
        $routeProvider.when('/', {
          template: '<div></div>',
          controller: function($location) {
            $location.path(CRM.crmApp.defaultRoute);
          }
        });
      }

      $routeProvider.otherwise({
        template: ts('Unknown path')
      });
    }
  ]);
})(angular, CRM);

(function (angular, $, _) {

  angular.module('crmAttachment', CRM.angRequires('crmAttachment'));
  angular.module('crmAttachment').factory('CrmAttachments', function (crmApi, crmStatus, FileUploader, $q) {
    function CrmAttachments(target) {
      var crmAttachments = this;
      this._target = target;
      this.files = [];
      this.trash = [];
      this.uploader = new FileUploader({
        url: CRM.url('civicrm/ajax/attachment'),
        onAfterAddingFile: function onAfterAddingFile(item) {
          item.crmData = {
            description: ''
          };
        },
        onSuccessItem: function onSuccessItem(item, response, status, headers) {
          crmAttachments.files.push(response.file.values[response.file.id]);
          crmAttachments.uploader.removeFromQueue(item);
        },
        onErrorItem: function onErrorItem(item, response, status, headers) {
          var msg = (response && response.file && response.file.error_message) ? response.file.error_message : ts('Unknown error');
          CRM.alert(item.file.name + ' - ' + msg, ts('Attachment failed'));
          crmAttachments.uploader.removeFromQueue(item);
        }
      });
    }

    angular.extend(CrmAttachments.prototype, {
      getTarget: function () {
        return (angular.isFunction(this._target) ? this._target() : this._target);
      },
      load: function load() {
        var target = this.getTarget();
        var Attachment = this;

        if (target.entity_id) {
          var params = {
            entity_table: target.entity_table,
            entity_id: target.entity_id
          };
          return crmApi('Attachment', 'get', params).then(function (apiResult) {
            Attachment.files = _.values(apiResult.values);
            return Attachment;
          });
        }
        else {
          var dfr = $q.defer();
          Attachment.files = [];
          dfr.resolve(Attachment);
          return dfr.promise;
        }
      },
      save: function save() {
        var crmAttachments = this;
        var target = this.getTarget();
        if (!target.entity_table || !target.entity_id) {
          throw "Cannot save attachments: unknown entity_table or entity_id";
        }

        var params = _.extend({}, target);
        params.values = crmAttachments.files;
        return crmApi('Attachment', 'replace', params)
          .then(function () {
            var dfr = $q.defer();

            var newItems = crmAttachments.uploader.getNotUploadedItems();
            if (newItems.length > 0) {
              _.each(newItems, function (item) {
                item.formData = [_.extend({crm_attachment_token: CRM.crmAttachment.token}, target, item.crmData)];
              });
              crmAttachments.uploader.onCompleteAll = function onCompleteAll() {
                delete crmAttachments.uploader.onCompleteAll;
                dfr.resolve(crmAttachments);
              };
              crmAttachments.uploader.uploadAll();
            }
            else {
              dfr.resolve(crmAttachments);
            }

            return dfr.promise;
          });
      },
      getAutosaveSignature: function getAutosaveSignature() {
        var sig = [];
        angular.forEach(this.files, function(item) {
          sig.push({f: item.name.replace(/[^a-zA0-Z0-9\.]/, '_'), d: item.description});
        });
        angular.forEach(this.uploader.queue, function(item) {
          sig.push({f: item.file.name.replace(/[^a-zA0-Z0-9\.]/, '_'), d: item.crmData.description});
        });
        angular.forEach(this.trash, function(item) {
          sig.push({f: item.name.replace(/[^a-zA0-Z0-9\.]/, '_'), d: item.description});
        });
        return _.sortBy(sig, 'name');
      },
      deleteFile: function deleteFile(file) {
        var crmAttachments = this;

        var idx = _.indexOf(this.files, file);
        if (idx != -1) {
          this.files.splice(idx, 1);
        }

        this.trash.push(file);

        if (file.id) {
          var p = crmApi('Attachment', 'delete', {id: file.id}).then(
            function () { // success
            },
            function (response) { // error; restore the file
              var msg = angular.isObject(response) ? response.error_message : '';
              CRM.alert(msg, ts('Deletion failed'));
              crmAttachments.files.push(file);

              var trashIdx = _.indexOf(crmAttachments.trash, file);
              if (trashIdx != -1) {
                crmAttachments.trash.splice(trashIdx, 1);
              }
            }
          );
          return crmStatus({start: ts('Deleting...'), success: ts('Deleted')}, p);
        }
      }
    });

    return CrmAttachments;
  });
  angular.module('crmAttachment').directive('crmAttachments', function ($parse, $timeout) {
    return {
      scope: {
        crmAttachments: '@'
      },
      template: '<div ng-if="ready" ng-include="inclUrl"></div>',
      link: function (scope, elm, attr) {
        var model = $parse(attr.crmAttachments);
        scope.att = model(scope.$parent);
        scope.ts = CRM.ts(null);
        CRM.api4('Setting', 'get', {
          select: ["maxFileSize"]
        }).then(function(settings) {
          scope.max_size = settings[0].value;
        });
        scope.inclUrl = '~/crmAttachment/attachments.html';
        scope.ready = true;
      }
    };
  });


angular.module('crmAutosave', CRM.angRequires('crmAutosave'));
  angular.module('crmAutosave').service('CrmAutosaveCtrl', function($interval, $timeout, $q) {
    function CrmAutosaveCtrl(options) {
      var intervals = angular.extend({poll: 250, save: 5000}, options.interval);
      var jobs = {poll: null, save: null}; // job handles used ot cancel/reschedule timeouts/intervals
      var lastSeenModel = null;
      var saving = false;
      function checkChanges() {
        if (saving) {
          return;
        }
        var currentModel = _.isFunction(options.model) ? options.model() : options.model;
        if (!angular.equals(currentModel, lastSeenModel)) {
          lastSeenModel = angular.copy(currentModel);
          if (jobs.save) {
            $timeout.cancel(jobs.save);
          }
          jobs.save = $timeout(doAutosave, intervals.save);
        }
      }

      function doAutosave() {
        jobs.save = null;
        if (saving) {
          return;
        }

        var form = _.isFunction(options.form) ? options.form() : options.form;

        if (options.saveIf) {
          if (!options.saveIf()) {
            return;
          }
        }
        else if (form && form.$invalid) {
          return;
        }

        saving = true;
        lastSeenModel = angular.copy(_.isFunction(options.model) ? options.model() : options.model);
        if (form) {
          form.$setPristine();
        }
        var res = options.save();
        if (res && res.then) {
          res.then(
            function() {
              saving = false;
            },
            function() {
              saving = false;
              if (form) {
                form.$setDirty();
              }
            }
          );
        }
        else {
          saving = false;
        }
      }

      var self = this;

      this.start = function() {
        if (!jobs.poll) {
          lastSeenModel = angular.copy(_.isFunction(options.model) ? options.model() : options.model);
          jobs.poll = $interval(checkChanges, intervals.poll);
        }
      };

      this.stop = function() {
        if (jobs.poll) {
          $interval.cancel(jobs.poll);
          jobs.poll = null;
        }
        if (jobs.save) {
          $timeout.cancel(jobs.save);
          jobs.save = null;
        }
      };

      this.suspend = function(p) {
        self.stop();
        return p.finally(self.start);
      };
    }

    return CrmAutosaveCtrl;
  });


var crmCaseType = angular.module('crmCaseType', CRM.angRequires('crmCaseType'));
  var newCaseTypeTemplate = {
    title: "",
    name: "",
    is_active: "1",
    weight: "1",
    definition: {
      activityTypes: [
        {name: 'Open Case', max_instances: 1},
        {name: 'Email'},
        {name: 'Follow up'},
        {name: 'Meeting'},
        {name: 'Phone Call'}
      ],
      activitySets: [
        {
          name: 'standard_timeline',
          label: 'Standard Timeline',
          timeline: '1', // Angular won't bind checkbox correctly with numeric 1
          activityTypes: [
            {name: 'Open Case', status: 'Completed' }
          ]
        }
      ],
      caseRoles: [
        { name: 'Case Coordinator', creator: '1', manager: '1'}
      ]
    }
  };

  crmCaseType.config(['$routeProvider',
    function($routeProvider) {
      $routeProvider.when('/caseType', {
        templateUrl: '~/crmCaseType/list.html',
        controller: 'CaseTypeListCtrl',
        resolve: {
          caseTypes: function($route, crmApi) {
            return crmApi('CaseType', 'get', {options: {limit: 0}});
          }
        }
      });
      $routeProvider.when('/caseType/:id', {
        templateUrl: '~/crmCaseType/edit.html',
        controller: 'CaseTypeCtrl',
        resolve: {
          apiCalls: function($route, crmApi) {
            var reqs = {};
            reqs.actStatuses = ['OptionValue', 'get', {
              option_group_id: 'activity_status',
              sequential: 1,
              options: {limit: 0}
            }];
            reqs.caseStatuses = ['OptionValue', 'get', {
              option_group_id: 'case_status',
              sequential: 1,
              options: {
                sort: 'weight',
                limit: 0
              }
            }];
            reqs.actTypes = ['OptionValue', 'get', {
              option_group_id: 'activity_type',
              sequential: 1,
              options: {
                sort: 'name',
                limit: 0
              }
            }];
            reqs.defaultAssigneeTypes = ['OptionValue', 'get', {
              option_group_id: 'activity_default_assignee',
              sequential: 1,
              options: {
                limit: 0
              }
            }];
            reqs.relTypes = ['RelationshipType', 'get', {
              sequential: 1,
              options: {
                sort: 'label_a_b',
                limit: 0
              }
            }];
            if ($route.current.params.id !== 'new') {
              reqs.caseType = ['CaseType', 'getsingle', {
                id: $route.current.params.id
              }];
            }
            return crmApi(reqs);
          }
        }
      });
    }
  ]);
  crmCaseType.directive('crmAddName', function() {
    return {
      restrict: 'AE',
      template: '<input class="add-activity crm-action-menu fa-plus" type="hidden" />',
      link: function(scope, element, attrs) {

        var input = $('input', element);

        scope._resetSelection = function() {
          $(input).select2('close');
          $(input).select2('val', '');
          scope[attrs.crmVar] = '';
        };

        $(input).crmSelect2({
          data: function () {
            return { results: scope[attrs.crmOptions] };
          },
          createSearchChoice: function(term) {
            return {id: term, text: term + ' (' + ts('new') + ')'};
          },
          createSearchChoicePosition: 'bottom',
          placeholder: attrs.placeholder
        });
        $(input).on('select2-selecting', function(e) {
          scope[attrs.crmVar] = e.val;
          scope.$evalAsync(attrs.crmOnAdd);
          scope.$evalAsync('_resetSelection()');
          e.preventDefault();
        });
      }
    };
  });

  crmCaseType.directive('crmEditableTabTitle', function($timeout) {
    return {
      restrict: 'AE',
      link: function(scope, element, attrs) {
        element.addClass('crm-editable crm-editable-enabled');
        var titleLabel = $(element).find('span');
        var penIcon = $('<i class="crm-i fa-pencil crm-editable-placeholder" aria-hidden="true"></i>').prependTo(element);
        var saveButton = $('<button type="button"><i class="crm-i fa-check" aria-hidden="true"></i></button>').appendTo(element);
        var cancelButton = $('<button type="cancel"><i class="crm-i fa-times" aria-hidden="true"></i></button>').appendTo(element);
        $('button', element).wrapAll('<div class="crm-editable-form" style="display:none" />');
        var buttons = $('.crm-editable-form', element);
        titleLabel.on('click', startEditMode);
        penIcon.on('click', startEditMode);

        function detectEscapeKeyPress (event) {
          var isEscape = false;

          if ("key" in event) {
              isEscape = (event.key == "Escape" || event.key == "Esc");
          } else {
              isEscape = (event.keyCode == 27);
          }

          return isEscape;
        }

        function detectEnterKeyPress (event) {
          var isEnter = false;

          if ("key" in event) {
            isEnter = (event.key == "Enter");
          } else {
            isEnter = (event.keyCode == 13);
          }

          return isEnter;
        }

        function startEditMode () {
          if (titleLabel.is(":focus")) {
            return;
          }

          penIcon.hide();
          buttons.show();

          saveButton.click(function () {
            updateTextValue();
            stopEditMode();
          });

          cancelButton.click(function () {
            revertTextValue();
            stopEditMode();
          });

          $(element).addClass('crm-editable-editing');

          titleLabel
            .attr("contenteditable", "true")
            .focus()
            .focusout(function (event) {
              $timeout(function () {
                revertTextValue();
                stopEditMode();
              }, 500);
            })
            .keydown(function(event) {
              event.stopImmediatePropagation();

              if(detectEscapeKeyPress(event)) {
                revertTextValue();
                stopEditMode();
              } else if(detectEnterKeyPress(event)) {
                event.preventDefault();
                updateTextValue();
                stopEditMode();
              }
            });
        }

        function stopEditMode () {
          titleLabel.removeAttr("contenteditable").off("focusout");
          titleLabel.off("keydown");
          saveButton.off("click");
          cancelButton.off("click");
          $(element).removeClass('crm-editable-editing');

          penIcon.show();
          buttons.hide();
        }

        function revertTextValue () {
          titleLabel.text(scope.activitySet.label);
        }

        function updateTextValue () {
          var updatedTitle = titleLabel.text();

          scope.$evalAsync(function () {
            scope.activitySet.label = updatedTitle;
          });
        }
      }
    };
  });

  crmCaseType.controller('CaseTypeCtrl', function($scope, crmApi, apiCalls, crmUiHelp) {
    var defaultAssigneeDefaultValue, ts;

    (function init () {

      ts = $scope.ts = CRM.ts(null);
      $scope.hs = crmUiHelp({file: 'CRM/Case/CaseType'});
      $scope.locks = { caseTypeName: true, activitySetName: true };
      $scope.workflows = { timeline: 'Timeline', sequence: 'Sequence' };
      defaultAssigneeDefaultValue = _.find(apiCalls.defaultAssigneeTypes.values, { is_default: '1' }) || {};

      storeApiCallsResults();
      initCaseType();
      initCaseTypeDefinition();
      initSelectedStatuses();
    })();
    function storeApiCallsResults() {
      $scope.activityStatuses = apiCalls.actStatuses.values;
      $scope.caseStatuses = _.indexBy(apiCalls.caseStatuses.values, 'name');
      $scope.activityTypes = _.indexBy(apiCalls.actTypes.values, 'name');
      $scope.activityTypeOptions = _.map(apiCalls.actTypes.values, formatActivityTypeOption);
      $scope.defaultAssigneeTypes = apiCalls.defaultAssigneeTypes.values;
      $scope.relationshipTypeOptions = getRelationshipTypeOptions(true);
      $scope.relationshipTypeOptionsAll = getRelationshipTypeOptions(false);
      $scope.defaultAssigneeTypeValues = _.chain($scope.defaultAssigneeTypes)
        .indexBy('name').mapValues('value').value();
    }
    function getRelationshipTypeOptions(onlyActive) {
      var relationshipTypesToUse;
      if (onlyActive) {
        relationshipTypesToUse = _.filter(apiCalls.relTypes.values, {is_active: "1"});
      } else {
        relationshipTypesToUse = apiCalls.relTypes.values;
      }
      return _.transform(relationshipTypesToUse, function(result, relType) {
        var isBidirectionalRelationship = relType.label_a_b === relType.label_b_a;
        result.push({
          xmlName: relType.name_a_b,
          text: relType.label_b_a,
          id: relType.id + '_a_b'
        });

        if (!isBidirectionalRelationship) {
          result.push({
            xmlName: relType.name_b_a,
            text: relType.label_a_b,
            id: relType.id + '_b_a'
          });
        }
      }, []);
    }
    function initCaseType() {
      var isNewCaseType = !apiCalls.caseType;

      if (isNewCaseType) {
        $scope.caseType = _.cloneDeep(newCaseTypeTemplate);
      } else {
        $scope.caseType = apiCalls.caseType;
      }
    }
    function initCaseTypeDefinition() {
      $scope.caseType.definition = $scope.caseType.definition || [];
      $scope.caseType.definition.activityTypes = $scope.caseType.definition.activityTypes || [];
      $scope.caseType.definition.activitySets = $scope.caseType.definition.activitySets || [];
      $scope.caseType.definition.caseRoles = $scope.caseType.definition.caseRoles || [];
      $scope.caseType.definition.statuses = $scope.caseType.definition.statuses || [];
      $scope.caseType.definition.timelineActivityTypes = $scope.caseType.definition.timelineActivityTypes || [];
      $scope.caseType.definition.restrictActivityAsgmtToCmsUser = $scope.caseType.definition.restrictActivityAsgmtToCmsUser || 0;
      $scope.caseType.definition.activityAsgmtGrps = $scope.caseType.definition.activityAsgmtGrps || [];

      _.each($scope.caseType.definition.activitySets, function (set) {
        _.each(set.activityTypes, function (type, name) {
          var isDefaultAssigneeTypeUndefined = _.isUndefined(type.default_assignee_type);
          var typeDefinition = $scope.activityTypes[type.name];
          type.label = (typeDefinition && typeDefinition.label) || type.name;

          if (isDefaultAssigneeTypeUndefined) {
            type.default_assignee_type = defaultAssigneeDefaultValue.value;
          }
        });
      });
      _.each($scope.caseType.definition.caseRoles, function (set) {
        _.each($scope.relationshipTypeOptionsAll, function (relationshipTypeOption) {
          if (relationshipTypeOption.xmlName == set.name) {
            set.displayLabel = relationshipTypeOption.text;
            return false;
          }
        });
      });
    }
    function initSelectedStatuses() {
      $scope.selectedStatuses = {};

      _.each(apiCalls.caseStatuses.values, function (status) {
        $scope.selectedStatuses[status.name] = !$scope.caseType.definition.statuses.length || $scope.caseType.definition.statuses.indexOf(status.name) > -1;
      });
    }

    $scope.addActivitySet = function(workflow) {
      var activitySet = {};
      activitySet[workflow] = '1';
      activitySet.activityTypes = [];

      var offset = 1;
      var names = _.pluck($scope.caseType.definition.activitySets, 'name');
      while (_.contains(names, workflow + '_' + offset)) offset++;
      activitySet.name = workflow + '_' + offset;
      activitySet.label = (offset == 1  ) ? $scope.workflows[workflow] : ($scope.workflows[workflow] + ' #' + offset);

      $scope.caseType.definition.activitySets.push(activitySet);
      _.defer(function() {
        $('.crmCaseType-acttab').tabs('refresh').tabs({active: -1});
      });
    };

    function formatActivityTypeOption(type) {
      return {id: type.name, text: type.label, icon: type.icon};
    }

    function addActivityToSet(activitySet, activityTypeName) {
      activitySet.activityTypes = activitySet.activityTypes || [];
	    var activity = {
          name: activityTypeName,
          label: $scope.activityTypes[activityTypeName].label,
          status: 'Scheduled',
          reference_activity: 'Open Case',
          reference_offset: '1',
          reference_select: 'newest',
          default_assignee_type: $scope.defaultAssigneeTypeValues.NONE
      };
      activitySet.activityTypes.push(activity);
      if(typeof activitySet.timeline !== "undefined" && activitySet.timeline == "1") {
        $scope.caseType.definition.timelineActivityTypes.push(activity);
      }
    }

    function resetTimelineActivityTypes() {
        $scope.caseType.definition.timelineActivityTypes = [];
        angular.forEach($scope.caseType.definition.activitySets, function(activitySet) {
            angular.forEach(activitySet.activityTypes, function(activityType) {
                $scope.caseType.definition.timelineActivityTypes.push(activityType);
            });
        });
    }

    function createActivity(name, callback) {
      CRM.loadForm(CRM.url('civicrm/admin/options/activity_type', {action: 'add', reset: 1, label: name, component_id: 7}))
        .on('crmFormSuccess', function(e, data) {
          $scope.activityTypes[data.optionValue.name] = data.optionValue;
          $scope.activityTypeOptions.push(formatActivityTypeOption(data.optionValue));
          callback(data.optionValue);
          $scope.$digest();
        });
    }
    $scope.addActivity = function(activitySet, activityType) {
      if ($scope.activityTypes[activityType]) {
        addActivityToSet(activitySet, activityType);
      } else {
        createActivity(activityType, function(newActivity) {
          addActivityToSet(activitySet, newActivity.name);
        });
      }
    };
    $scope.addActivityType = function(activityType) {
      var names = _.pluck($scope.caseType.definition.activityTypes, 'name');
      if (!_.contains(names, activityType)) {
        if ($scope.activityTypes[activityType]) {
          $scope.caseType.definition.activityTypes.push({name: activityType});
        } else {
          createActivity(activityType, function(newActivity) {
            $scope.caseType.definition.activityTypes.push({name: newActivity.name});
          });
        }
      }
    };
    $scope.clearActivityDefaultAssigneeValues = function(activity) {
      activity.default_assignee_relationship = null;
      activity.default_assignee_contact = null;
    };
    $scope.addRole = function(roles, roleIdOrLabel) {
      var matchingRole;
      var matchingRoles = _.filter($scope.relationshipTypeOptions, {id: roleIdOrLabel});
      if (matchingRoles.length) {
        matchingRole = matchingRoles.shift();
      }
      var names = _.pluck($scope.caseType.definition.caseRoles, 'name');
      if (matchingRole) {
        if (!_.contains(names, matchingRole.xmlName)) {
          roles.push({name: matchingRole.xmlName, displayLabel: matchingRole.text});
        }
      } else {
         CRM.loadForm(CRM.url('civicrm/admin/reltype', {action: 'add', reset: 1, label_a_b: roleIdOrLabel}))
          .on('crmFormSuccess', function(e, data) {
            var newType = _.values(data.relationshipType)[0];
            $scope.$apply(function() {
              $scope.addRoleOnTheFly(roles, newType);
            });
          });
      }
    };
    $scope.addRoleOnTheFly = function(roles, newType) {
      roles.push({name: newType.name_b_a, displayLabel: newType.label_a_b});
      var newRelTypeOption = {
        xmlName: newType.name_a_b,
        text: newType.label_b_a,
        id: newType.id + '_a_b'
      };
      $scope.relationshipTypeOptions.push(newRelTypeOption);
      $scope.relationshipTypeOptionsAll.push(newRelTypeOption);
      if (newType.label_a_b != newType.label_b_a) {
        newRelTypeOption = {
          xmlName: newType.name_b_a,
          text: newType.label_a_b,
          id: newType.id + '_b_a'
        };
        $scope.relationshipTypeOptions.push(newRelTypeOption);
        $scope.relationshipTypeOptionsAll.push(newRelTypeOption);
      }
    };

    $scope.onManagerChange = function(managerRole) {
      angular.forEach($scope.caseType.definition.caseRoles, function(caseRole) {
        if (caseRole != managerRole) {
          caseRole.manager = '0';
        }
      });
    };

    $scope.removeItem = function(array, item) {
      var idx = _.indexOf(array, item);
      if (idx != -1) {
        array.splice(idx, 1);
        resetTimelineActivityTypes();
      }
    };

    $scope.isForkable = function() {
      return !$scope.caseType.id || $scope.caseType.is_forkable;
    };

    $scope.newStatus = function() {
      CRM.loadForm(CRM.url('civicrm/admin/options/case_status', {action: 'add', reset: 1}))
        .on('crmFormSuccess', function(e, data) {
          $scope.caseStatuses[data.optionValue.name] = data.optionValue;
          $scope.selectedStatuses[data.optionValue.name] = true;
          $scope.$digest();
        });
    };

    $scope.isNewActivitySetAllowed = function(workflow) {
      switch (workflow) {
        case 'timeline':
          return true;
        case 'sequence':
          return 0 === _.where($scope.caseType.definition.activitySets, {sequence: '1'}).length;
        default:
          CRM.console('warn', 'Denied access to unrecognized workflow: (' + workflow + ')');
          return false;
      }
    };

    $scope.isActivityRemovable = function(activitySet, activity) {
      return true;
    };

    $scope.isValidName = function(name) {
      return !name || name.match(/^[a-zA-Z0-9_]+$/);
    };

    $scope.getWorkflowName = function(activitySet) {
      var result = 'Unknown';
      _.each($scope.workflows, function(value, key) {
        if (activitySet[key]) result = value;
      });
      return result;
    };

    /**
     * Determine which HTML partial to use for a particular
     *
     * @return string URL of the HTML partial
     */
    $scope.activityTableTemplate = function(activitySet) {
      if (activitySet.timeline) {
        return '~/crmCaseType/timelineTable.html';
      } else if (activitySet.sequence) {
        return '~/crmCaseType/sequenceTable.html';
      } else {
        return '';
      }
    };

    $scope.dump = function() {
      console.log($scope.caseType);
    };

    $scope.save = function() {
      var selectedStatuses = [];
      _.each($scope.selectedStatuses, function(v, k) {
        if (v) selectedStatuses.push(k);
      });
      $scope.caseType.definition.statuses = selectedStatuses.length == _.size($scope.selectedStatuses) ? [] : selectedStatuses;

      if ($scope.caseType.definition.activityAsgmtGrps) {
        $scope.caseType.definition.activityAsgmtGrps = $scope.caseType.definition.activityAsgmtGrps.toString().split(",");
      }

      function dropDisplaylabel (v) {
        delete v.displayLabel;
      }
      _.map($scope.caseType.definition.caseRoles, dropDisplaylabel);

      var result = crmApi('CaseType', 'create', $scope.caseType, true);
      result.then(function(data) {
        if (data.is_error === 0 || data.is_error == '0') {
          $scope.caseType.id = data.id;
          window.location.href = '#/caseType';
        }
      });
    };

    $scope.$watchCollection('caseType.definition.activitySets', function() {
      _.defer(function() {
        $('.crmCaseType-acttab').tabs('refresh');
      });
    });

    var updateCaseTypeName = function () {
      if (!$scope.caseType.id && $scope.locks.caseTypeName) {
        var t = $scope.caseType.title ? $scope.caseType.title : '';
        $scope.caseType.name = t.replace(/ /g, '_').replace(/[^a-zA-Z0-9_]/g, '').toLowerCase();
      }
    };
    $scope.$watch('locks.caseTypeName', updateCaseTypeName);
    $scope.$watch('caseType.title', updateCaseTypeName);

    if (!$scope.isForkable()) {
      CRM.alert(ts('The CiviCase XML file for this case-type prohibits editing the definition.'));
    }

  });

  crmCaseType.controller('CaseTypeListCtrl', function($scope, crmApi, caseTypes) {
    var ts = $scope.ts = CRM.ts(null);

    $scope.caseTypes = caseTypes.values;
    $scope.toggleCaseType = function (caseType) {
      caseType.is_active = (caseType.is_active == '1') ? '0' : '1';
      crmApi('CaseType', 'create', caseType, true)
        .catch(function (data) {
          caseType.is_active = (caseType.is_active == '1') ? '0' : '1'; // revert
          $scope.$digest();
        });
    };
    $scope.deleteCaseType = function (caseType) {
      crmApi('CaseType', 'delete', {id: caseType.id}, {
        error: function (data) {
          CRM.alert(data.error_message, ts('Error'), 'error');
        }
      })
        .then(function (data) {
          delete caseTypes.values[caseType.id];
        });
    };
    $scope.revertCaseType = function (caseType) {
      caseType.definition = 'null';
      caseType.is_forked = '0';
      crmApi('CaseType', 'create', caseType, true)
        .catch(function (data) {
          caseType.is_forked = '1'; // restore
          $scope.$digest();
        });
    };
  });


angular.module('crmCxn', CRM.angRequires('crmCxn'));

  angular.module('crmCxn').config([
    '$routeProvider',
    function ($routeProvider) {
      $routeProvider.when('/cxn', {
        templateUrl: '~/crmCxn/ManageCtrl.html',
        controller: 'CrmCxnManageCtrl',
        resolve: {
          apiCalls: function(crmApi){
            var reqs = {};
            reqs.cxns = ['Cxn', 'get', {sequential: 1}];
            reqs.appMetas = ['CxnApp', 'get', {sequential: 1, return: ['id', 'title', 'desc', 'appId', 'appUrl', 'links', 'perm']}];
            reqs.cfg = ['Cxn', 'getcfg', {}];
            reqs.sysCheck = ['System', 'check', {}]; // FIXME: filter on checkCxnOverrides
            return crmApi(reqs);
          }
        }
      });
    }
  ]);


angular.module('crmCxn').controller('CrmCxnConfirmAboutCtrl', function($scope) {
    $scope.ts = CRM.ts(null);
  });

angular.module('crmCxn').directive('crmCxnAdvTable', function crmCxnAdvTable() {
    return {
      restrict: 'EA',
      scope: {
        crmCxnAdvTable: '='
      },
      templateUrl: '~/crmCxn/AdvTable.html',
      link: function(scope, element, attrs) {
        scope.ts = CRM.ts(null);
        scope.$watch('crmCxnAdvTable', function(crmCxnAdvTable){
          scope.appMeta = crmCxnAdvTable.appMeta;
        });
      }
    };
  });

angular.module('crmCxn').factory('crmCxnCheckAddr', function($q, $timeout) {
    var TIMEOUT = 6000, CHECK_ADDR = 'https://mycivi.org/check-addr';
    return function(url) {
      var dfr = $q.defer(), result = null;

      function onErr() {
        if (result !== null) return;
        result = {url: url, valid: false};
        dfr.resolve(result);
      }

      $.ajax({
        url: CHECK_ADDR,
        data: {url: url},
        jsonp: "callback",
        dataType: "jsonp"
      }).fail(onErr)
        .done(function(response) {
          if (result !== null) return;
          result = {url: url, valid: response.result};
          dfr.resolve(result);
        }
      );
      $timeout(onErr, TIMEOUT);

      return dfr.promise;
    };
  });


angular.module('crmCxn').controller('CrmCxnConfirmConnectCtrl', function($scope) {
    $scope.ts = CRM.ts(null);
  });

angular.module('crmCxn').controller('CrmCxnConfirmReconnectCtrl', function($scope) {
    $scope.ts = CRM.ts(null);
  });

angular.module('crmCxn').controller('CrmCxnLinkDialogCtrl', function CrmCxnLinkDialogCtrl($scope, dialogService) {
    var ts = $scope.ts = CRM.ts(null);
  });


angular.module('crmCxn').controller('CrmCxnManageCtrl', function CrmCxnManageCtrl($scope, apiCalls, crmApi, crmUiAlert, crmBlocker, crmStatus, $timeout, dialogService, crmCxnCheckAddr) {
    var ts = $scope.ts = CRM.ts(null);
    if (apiCalls.appMetas.is_error) {
      $scope.appMetas = [];
      CRM.alert(apiCalls.appMetas.error_message, ts('Application List Unavailable'), 'error');
    }
    else {
      $scope.appMetas = apiCalls.appMetas.values;
    }
    $scope.cxns = apiCalls.cxns.values;
    $scope.alerts = _.where(apiCalls.sysCheck.values, {name: 'checkCxnOverrides'});

    crmCxnCheckAddr(apiCalls.cfg.values.siteCallbackUrl).then(function(response) {
      if (response.valid) return;
      crmUiAlert({
        type: 'warning',
        title: ts('Internet Access Required'),
        templateUrl: '~/crmCxn/Connectivity.html',
        scope: $scope.$new(),
        options: {expires: false}
      });
    });

    $scope.filter = {};
    var block = $scope.block = crmBlocker();

    _.each($scope.alerts, function(alert){
      crmUiAlert({text: alert.message, title: alert.title, type: 'error'});
    });
    function asOne(result, msg) {
      switch (result.length) {
        case 0:
          return null;
        case 1:
          return result[0];
        default:
          throw msg;
      }
    }

    $scope.findCxnByAppId = function(appId) {
      var result = _.where($scope.cxns, {
        app_guid: appId
      });
      return asOne(result, "Error: Too many connections for appId: " + appId);
    };

    $scope.findAppByAppId = function(appId) {
      var result = _.where($scope.appMetas, {
        appId: appId
      });
      return asOne(result, "Error: Too many apps for appId: " + appId);
    };

    $scope.hasAvailApps = function() {
      for (var i = 0; i< $scope.appMetas.length; i++) {
        if (!$scope.findCxnByAppId($scope.appMetas[i].appId)) {
          return true;
        }
      }
      return false;
    };

    $scope.refreshCxns = function() {
      crmApi('Cxn', 'get', {sequential: 1}).then(function(result) {
        $timeout(function(){
          $scope.cxns = result.values;
        });
      });
    };

    $scope.register = function(appMeta) {
      var reg = crmApi('Cxn', 'register', {app_guid: appMeta.appId}).then($scope.refreshCxns).then(function() {
        if (appMeta.links.welcome) {
          return $scope.openLink(appMeta, 'welcome', {title: ts('%1: Welcome (External)', {1: appMeta.title})});
        }
      });
      return block(crmStatus({start: ts('Connecting...'), success: ts('Connected')}, reg));
    };

    $scope.reregister = function(appMeta) {
      var reg = crmApi('Cxn', 'register', {app_guid: appMeta.appId}).then($scope.refreshCxns).then(function() {
        if (appMeta.links.welcome) {
          return $scope.openLink(appMeta, 'welcome', {title: ts('%1: Welcome (External)', {1: appMeta.title})});
        }
      });
      return block(crmStatus({start: ts('Reconnecting...'), success: ts('Reconnected')}, reg));
    };

    $scope.unregister = function(appMeta) {
      var reg = crmApi('Cxn', 'unregister', {app_guid: appMeta.appId, debug: 1}).then($scope.refreshCxns);
      return block(crmStatus({start: ts('Disconnecting...'), success: ts('Disconnected')}, reg));
    };

    $scope.toggleCxn = function toggleCxn(cxn) {
      var is_active = (cxn.is_active=="1" ? 0 : 1); // we switch the flag
      var reg = crmApi('Cxn', 'create', {id: cxn.id, app_guid: cxn.app_meta.appId, is_active: is_active, debug: 1}).then(function(){
        cxn.is_active = is_active;
      });
      return block(crmStatus({start: ts('Saving...'), success: ts('Saved')}, reg));
    };

    $scope.openLink = function openLink(appMeta, page, options) {
      var promise = crmApi('Cxn', 'getlink', {app_guid: appMeta.appId, page_name: page}).then(function(result) {
        var mode = result.values.mode ? result.values.mode : 'popup';
        switch (result.values.mode) {
          case 'iframe':
            var passThrus = ['height', 'width']; // Options influenced by remote server.
            options = angular.extend(_.pick(result.values, passThrus), options);
            $scope.openIframe(result.values.url, options);
            break;
          case 'popup':
            CRM.alert(ts('The page "%1" will open in a popup. If it does not appear automatically, check your browser for notifications.', {1: options.title}), '', 'info');
            window.open(result.values.url, 'cxnSettings', 'resizable,scrollbars,status');
            break;
          case 'redirect':
            window.location = result.values.url;
            break;
          default:
            CRM.alert(ts('Cannot open link. Unrecognized mode.'), '', 'error');
        }
      });
      return block(crmStatus({start: ts('Opening...'), success: ''}, promise));
    };
    $scope.openIframe = function openIframe(url, options) {
      var model = {
        url: url
      };
      options = CRM.utils.adjustDialogDefaults(angular.extend(
        {
          autoOpen: false,
          height: 'auto',
          width: '40%',
          title: ts('External Link')
        },
        options
      ));
      return dialogService.open('cxnLinkDialog', '~/crmCxn/LinkDialogCtrl.html', model, options)
        .then(function(item) {
          mailing.msg_template_id = item.id;
          return item;
        });
    };
  });


angular.module('crmCxn').directive('crmCxnPermTable', function crmCxnPermTable() {
    return {
      restrict: 'EA',
      scope: {
        crmCxnPermTable: '='
      },
      templateUrl: '~/crmCxn/PermTable.html',
      link: function(scope, element, attrs) {
        scope.ts = CRM.ts(null);
        scope.hasRequiredFilters = function(api) {
          return !_.isEmpty(api.required);
        };
        scope.isString = function(v) {
          return _.isString(v);
        };
        scope.apiExplorerUrl = CRM.url('civicrm/api');
        scope.$watch('crmCxnPermTable', function(crmCxnPermTable){
          scope.perm = crmCxnPermTable.perm;
        });
      }
    };
  });

angular.module('crmD3', CRM.angRequires('crmD3'));
})(angular, CRM.$, CRM._);

!function(){function n(n,t){return t>n?-1:n>t?1:n>=t?0:0/0}function t(n){return null!=n&&!isNaN(n)}function e(n){return{left:function(t,e,r,u){for(arguments.length<3&&(r=0),arguments.length<4&&(u=t.length);u>r;){var i=r+u>>>1;n(t[i],e)<0?r=i+1:u=i}return r},right:function(t,e,r,u){for(arguments.length<3&&(r=0),arguments.length<4&&(u=t.length);u>r;){var i=r+u>>>1;n(t[i],e)>0?u=i:r=i+1}return r}}}function r(n){return n.length}function u(n){for(var t=1;n*t%1;)t*=10;return t}function i(n,t){try{for(var e in t)Object.defineProperty(n.prototype,e,{value:t[e],enumerable:!1})}catch(r){n.prototype=t}}function o(){}function a(n){return ia+n in this}function c(n){return n=ia+n,n in this&&delete this[n]}function s(){var n=[];return this.forEach(function(t){n.push(t)}),n}function l(){var n=0;for(var t in this)t.charCodeAt(0)===oa&&++n;return n}function f(){for(var n in this)if(n.charCodeAt(0)===oa)return!1;return!0}function h(){}function g(n,t,e){return function(){var r=e.apply(t,arguments);return r===t?n:r}}function p(n,t){if(t in n)return t;t=t.charAt(0).toUpperCase()+t.substring(1);for(var e=0,r=aa.length;r>e;++e){var u=aa[e]+t;if(u in n)return u}}function v(){}function d(){}function m(n){function t(){for(var t,r=e,u=-1,i=r.length;++u<i;)(t=r[u].on)&&t.apply(this,arguments);return n}var e=[],r=new o;return t.on=function(t,u){var i,o=r.get(t);return arguments.length<2?o&&o.on:(o&&(o.on=null,e=e.slice(0,i=e.indexOf(o)).concat(e.slice(i+1)),r.remove(t)),u&&e.push(r.set(t,{on:u})),n)},t}function y(){Zo.event.preventDefault()}function x(){for(var n,t=Zo.event;n=t.sourceEvent;)t=n;return t}function M(n){for(var t=new d,e=0,r=arguments.length;++e<r;)t[arguments[e]]=m(t);return t.of=function(e,r){return function(u){try{var i=u.sourceEvent=Zo.event;u.target=n,Zo.event=u,t[u.type].apply(e,r)}finally{Zo.event=i}}},t}function _(n){return sa(n,pa),n}function b(n){return"function"==typeof n?n:function(){return la(n,this)}}function w(n){return"function"==typeof n?n:function(){return fa(n,this)}}function S(n,t){function e(){this.removeAttribute(n)}function r(){this.removeAttributeNS(n.space,n.local)}function u(){this.setAttribute(n,t)}function i(){this.setAttributeNS(n.space,n.local,t)}function o(){var e=t.apply(this,arguments);null==e?this.removeAttribute(n):this.setAttribute(n,e)}function a(){var e=t.apply(this,arguments);null==e?this.removeAttributeNS(n.space,n.local):this.setAttributeNS(n.space,n.local,e)}return n=Zo.ns.qualify(n),null==t?n.local?r:e:"function"==typeof t?n.local?a:o:n.local?i:u}function k(n){return n.trim().replace(/\s+/g," ")}function E(n){return new RegExp("(?:^|\\s+)"+Zo.requote(n)+"(?:\\s+|$)","g")}function A(n){return(n+"").trim().split(/^|\s+/)}function C(n,t){function e(){for(var e=-1;++e<u;)n[e](this,t)}function r(){for(var e=-1,r=t.apply(this,arguments);++e<u;)n[e](this,r)}n=A(n).map(N);var u=n.length;return"function"==typeof t?r:e}function N(n){var t=E(n);return function(e,r){if(u=e.classList)return r?u.add(n):u.remove(n);var u=e.getAttribute("class")||"";r?(t.lastIndex=0,t.test(u)||e.setAttribute("class",k(u+" "+n))):e.setAttribute("class",k(u.replace(t," ")))}}function z(n,t,e){function r(){this.style.removeProperty(n)}function u(){this.style.setProperty(n,t,e)}function i(){var r=t.apply(this,arguments);null==r?this.style.removeProperty(n):this.style.setProperty(n,r,e)}return null==t?r:"function"==typeof t?i:u}function L(n,t){function e(){delete this[n]}function r(){this[n]=t}function u(){var e=t.apply(this,arguments);null==e?delete this[n]:this[n]=e}return null==t?e:"function"==typeof t?u:r}function T(n){return"function"==typeof n?n:(n=Zo.ns.qualify(n)).local?function(){return this.ownerDocument.createElementNS(n.space,n.local)}:function(){return this.ownerDocument.createElementNS(this.namespaceURI,n)}}function q(n){return{__data__:n}}function R(n){return function(){return ga(this,n)}}function D(t){return arguments.length||(t=n),function(n,e){return n&&e?t(n.__data__,e.__data__):!n-!e}}function P(n,t){for(var e=0,r=n.length;r>e;e++)for(var u,i=n[e],o=0,a=i.length;a>o;o++)(u=i[o])&&t(u,o,e);return n}function U(n){return sa(n,da),n}function j(n){var t,e;return function(r,u,i){var o,a=n[i].update,c=a.length;for(i!=e&&(e=i,t=0),u>=t&&(t=u+1);!(o=a[t])&&++t<c;);return o}}function H(){var n=this.__transition__;n&&++n.active}function F(n,t,e){function r(){var t=this[o];t&&(this.removeEventListener(n,t,t.$),delete this[o])}function u(){var u=c(t,Xo(arguments));r.call(this),this.addEventListener(n,this[o]=u,u.$=e),u._=t}function i(){var t,e=new RegExp("^__on([^.]+)"+Zo.requote(n)+"$");for(var r in this)if(t=r.match(e)){var u=this[r];this.removeEventListener(t[1],u,u.$),delete this[r]}}var o="__on"+n,a=n.indexOf("."),c=O;a>0&&(n=n.substring(0,a));var s=ya.get(n);return s&&(n=s,c=Y),a?t?u:r:t?v:i}function O(n,t){return function(e){var r=Zo.event;Zo.event=e,t[0]=this.__data__;try{n.apply(this,t)}finally{Zo.event=r}}}function Y(n,t){var e=O(n,t);return function(n){var t=this,r=n.relatedTarget;r&&(r===t||8&r.compareDocumentPosition(t))||e.call(t,n)}}function I(){var n=".dragsuppress-"+ ++Ma,t="click"+n,e=Zo.select(Wo).on("touchmove"+n,y).on("dragstart"+n,y).on("selectstart"+n,y);if(xa){var r=Bo.style,u=r[xa];r[xa]="none"}return function(i){function o(){e.on(t,null)}e.on(n,null),xa&&(r[xa]=u),i&&(e.on(t,function(){y(),o()},!0),setTimeout(o,0))}}function Z(n,t){t.changedTouches&&(t=t.changedTouches[0]);var e=n.ownerSVGElement||n;if(e.createSVGPoint){var r=e.createSVGPoint();if(0>_a&&(Wo.scrollX||Wo.scrollY)){e=Zo.select("body").append("svg").style({position:"absolute",top:0,left:0,margin:0,padding:0,border:"none"},"important");var u=e[0][0].getScreenCTM();_a=!(u.f||u.e),e.remove()}return _a?(r.x=t.pageX,r.y=t.pageY):(r.x=t.clientX,r.y=t.clientY),r=r.matrixTransform(n.getScreenCTM().inverse()),[r.x,r.y]}var i=n.getBoundingClientRect();return[t.clientX-i.left-n.clientLeft,t.clientY-i.top-n.clientTop]}function V(){return Zo.event.changedTouches[0].identifier}function X(){return Zo.event.target}function $(){return Wo}function B(n){return n>0?1:0>n?-1:0}function W(n,t,e){return(t[0]-n[0])*(e[1]-n[1])-(t[1]-n[1])*(e[0]-n[0])}function J(n){return n>1?0:-1>n?ba:Math.acos(n)}function G(n){return n>1?Sa:-1>n?-Sa:Math.asin(n)}function K(n){return((n=Math.exp(n))-1/n)/2}function Q(n){return((n=Math.exp(n))+1/n)/2}function nt(n){return((n=Math.exp(2*n))-1)/(n+1)}function tt(n){return(n=Math.sin(n/2))*n}function et(){}function rt(n,t,e){return this instanceof rt?(this.h=+n,this.s=+t,void(this.l=+e)):arguments.length<2?n instanceof rt?new rt(n.h,n.s,n.l):mt(""+n,yt,rt):new rt(n,t,e)}function ut(n,t,e){function r(n){return n>360?n-=360:0>n&&(n+=360),60>n?i+(o-i)*n/60:180>n?o:240>n?i+(o-i)*(240-n)/60:i}function u(n){return Math.round(255*r(n))}var i,o;return n=isNaN(n)?0:(n%=360)<0?n+360:n,t=isNaN(t)?0:0>t?0:t>1?1:t,e=0>e?0:e>1?1:e,o=.5>=e?e*(1+t):e+t-e*t,i=2*e-o,new gt(u(n+120),u(n),u(n-120))}function it(n,t,e){return this instanceof it?(this.h=+n,this.c=+t,void(this.l=+e)):arguments.length<2?n instanceof it?new it(n.h,n.c,n.l):n instanceof at?st(n.l,n.a,n.b):st((n=xt((n=Zo.rgb(n)).r,n.g,n.b)).l,n.a,n.b):new it(n,t,e)}function ot(n,t,e){return isNaN(n)&&(n=0),isNaN(t)&&(t=0),new at(e,Math.cos(n*=Aa)*t,Math.sin(n)*t)}function at(n,t,e){return this instanceof at?(this.l=+n,this.a=+t,void(this.b=+e)):arguments.length<2?n instanceof at?new at(n.l,n.a,n.b):n instanceof it?ot(n.l,n.c,n.h):xt((n=gt(n)).r,n.g,n.b):new at(n,t,e)}function ct(n,t,e){var r=(n+16)/116,u=r+t/500,i=r-e/200;return u=lt(u)*ja,r=lt(r)*Ha,i=lt(i)*Fa,new gt(ht(3.2404542*u-1.5371385*r-.4985314*i),ht(-.969266*u+1.8760108*r+.041556*i),ht(.0556434*u-.2040259*r+1.0572252*i))}function st(n,t,e){return n>0?new it(Math.atan2(e,t)*Ca,Math.sqrt(t*t+e*e),n):new it(0/0,0/0,n)}function lt(n){return n>.206893034?n*n*n:(n-4/29)/7.787037}function ft(n){return n>.008856?Math.pow(n,1/3):7.787037*n+4/29}function ht(n){return Math.round(255*(.00304>=n?12.92*n:1.055*Math.pow(n,1/2.4)-.055))}function gt(n,t,e){return this instanceof gt?(this.r=~~n,this.g=~~t,void(this.b=~~e)):arguments.length<2?n instanceof gt?new gt(n.r,n.g,n.b):mt(""+n,gt,ut):new gt(n,t,e)}function pt(n){return new gt(n>>16,255&n>>8,255&n)}function vt(n){return pt(n)+""}function dt(n){return 16>n?"0"+Math.max(0,n).toString(16):Math.min(255,n).toString(16)}function mt(n,t,e){var r,u,i,o=0,a=0,c=0;if(r=/([a-z]+)\((.*)\)/i.exec(n))switch(u=r[2].split(","),r[1]){case"hsl":return e(parseFloat(u[0]),parseFloat(u[1])/100,parseFloat(u[2])/100);case"rgb":return t(_t(u[0]),_t(u[1]),_t(u[2]))}return(i=Ia.get(n))?t(i.r,i.g,i.b):(null==n||"#"!==n.charAt(0)||isNaN(i=parseInt(n.substring(1),16))||(4===n.length?(o=(3840&i)>>4,o=o>>4|o,a=240&i,a=a>>4|a,c=15&i,c=c<<4|c):7===n.length&&(o=(16711680&i)>>16,a=(65280&i)>>8,c=255&i)),t(o,a,c))}function yt(n,t,e){var r,u,i=Math.min(n/=255,t/=255,e/=255),o=Math.max(n,t,e),a=o-i,c=(o+i)/2;return a?(u=.5>c?a/(o+i):a/(2-o-i),r=n==o?(t-e)/a+(e>t?6:0):t==o?(e-n)/a+2:(n-t)/a+4,r*=60):(r=0/0,u=c>0&&1>c?0:r),new rt(r,u,c)}function xt(n,t,e){n=Mt(n),t=Mt(t),e=Mt(e);var r=ft((.4124564*n+.3575761*t+.1804375*e)/ja),u=ft((.2126729*n+.7151522*t+.072175*e)/Ha),i=ft((.0193339*n+.119192*t+.9503041*e)/Fa);return at(116*u-16,500*(r-u),200*(u-i))}function Mt(n){return(n/=255)<=.04045?n/12.92:Math.pow((n+.055)/1.055,2.4)}function _t(n){var t=parseFloat(n);return"%"===n.charAt(n.length-1)?Math.round(2.55*t):t}function bt(n){return"function"==typeof n?n:function(){return n}}function wt(n){return n}function St(n){return function(t,e,r){return 2===arguments.length&&"function"==typeof e&&(r=e,e=null),kt(t,e,n,r)}}function kt(n,t,e,r){function u(){var n,t=c.status;if(!t&&c.responseText||t>=200&&300>t||304===t){try{n=e.call(i,c)}catch(r){return o.error.call(i,r),void 0}o.load.call(i,n)}else o.error.call(i,c)}var i={},o=Zo.dispatch("beforesend","progress","load","error"),a={},c=new XMLHttpRequest,s=null;return!Wo.XDomainRequest||"withCredentials"in c||!/^(http(s)?:)?\/\//.test(n)||(c=new XDomainRequest),"onload"in c?c.onload=c.onerror=u:c.onreadystatechange=function(){c.readyState>3&&u()},c.onprogress=function(n){var t=Zo.event;Zo.event=n;try{o.progress.call(i,c)}finally{Zo.event=t}},i.header=function(n,t){return n=(n+"").toLowerCase(),arguments.length<2?a[n]:(null==t?delete a[n]:a[n]=t+"",i)},i.mimeType=function(n){return arguments.length?(t=null==n?null:n+"",i):t},i.responseType=function(n){return arguments.length?(s=n,i):s},i.response=function(n){return e=n,i},["get","post"].forEach(function(n){i[n]=function(){return i.send.apply(i,[n].concat(Xo(arguments)))}}),i.send=function(e,r,u){if(2===arguments.length&&"function"==typeof r&&(u=r,r=null),c.open(e,n,!0),null==t||"accept"in a||(a.accept=t+",*/*"),c.setRequestHeader)for(var l in a)c.setRequestHeader(l,a[l]);return null!=t&&c.overrideMimeType&&c.overrideMimeType(t),null!=s&&(c.responseType=s),null!=u&&i.on("error",u).on("load",function(n){u(null,n)}),o.beforesend.call(i,c),c.send(null==r?null:r),i},i.abort=function(){return c.abort(),i},Zo.rebind(i,o,"on"),null==r?i:i.get(Et(r))}function Et(n){return 1===n.length?function(t,e){n(null==t?e:null)}:n}function At(){var n=Ct(),t=Nt()-n;t>24?(isFinite(t)&&(clearTimeout($a),$a=setTimeout(At,t)),Xa=0):(Xa=1,Wa(At))}function Ct(){var n=Date.now();for(Ba=Za;Ba;)n>=Ba.t&&(Ba.f=Ba.c(n-Ba.t)),Ba=Ba.n;return n}function Nt(){for(var n,t=Za,e=1/0;t;)t.f?t=n?n.n=t.n:Za=t.n:(t.t<e&&(e=t.t),t=(n=t).n);return Va=n,e}function zt(n,t){return t-(n?Math.ceil(Math.log(n)/Math.LN10):1)}function Lt(n,t){var e=Math.pow(10,3*ua(8-t));return{scale:t>8?function(n){return n/e}:function(n){return n*e},symbol:n}}function Tt(n){var t=n.decimal,e=n.thousands,r=n.grouping,u=n.currency,i=r?function(n){for(var t=n.length,u=[],i=0,o=r[0];t>0&&o>0;)u.push(n.substring(t-=o,t+o)),o=r[i=(i+1)%r.length];return u.reverse().join(e)}:wt;return function(n){var e=Ga.exec(n),r=e[1]||" ",o=e[2]||">",a=e[3]||"",c=e[4]||"",s=e[5],l=+e[6],f=e[7],h=e[8],g=e[9],p=1,v="",d="",m=!1;switch(h&&(h=+h.substring(1)),(s||"0"===r&&"="===o)&&(s=r="0",o="=",f&&(l-=Math.floor((l-1)/4))),g){case"n":f=!0,g="g";break;case"%":p=100,d="%",g="f";break;case"p":p=100,d="%",g="r";break;case"b":case"o":case"x":case"X":"#"===c&&(v="0"+g.toLowerCase());case"c":case"d":m=!0,h=0;break;case"s":p=-1,g="r"}"$"===c&&(v=u[0],d=u[1]),"r"!=g||h||(g="g"),null!=h&&("g"==g?h=Math.max(1,Math.min(21,h)):("e"==g||"f"==g)&&(h=Math.max(0,Math.min(20,h)))),g=Ka.get(g)||qt;var y=s&&f;return function(n){var e=d;if(m&&n%1)return"";var u=0>n||0===n&&0>1/n?(n=-n,"-"):a;if(0>p){var c=Zo.formatPrefix(n,h);n=c.scale(n),e=c.symbol+d}else n*=p;n=g(n,h);var x=n.lastIndexOf("."),M=0>x?n:n.substring(0,x),_=0>x?"":t+n.substring(x+1);!s&&f&&(M=i(M));var b=v.length+M.length+_.length+(y?0:u.length),w=l>b?new Array(b=l-b+1).join(r):"";return y&&(M=i(w+M)),u+=v,n=M+_,("<"===o?u+n+w:">"===o?w+u+n:"^"===o?w.substring(0,b>>=1)+u+n+w.substring(b):u+(y?n:w+n))+e}}}function qt(n){return n+""}function Rt(){this._=new Date(arguments.length>1?Date.UTC.apply(this,arguments):arguments[0])}function Dt(n,t,e){function r(t){var e=n(t),r=i(e,1);return r-t>t-e?e:r}function u(e){return t(e=n(new nc(e-1)),1),e}function i(n,e){return t(n=new nc(+n),e),n}function o(n,r,i){var o=u(n),a=[];if(i>1)for(;r>o;)e(o)%i||a.push(new Date(+o)),t(o,1);else for(;r>o;)a.push(new Date(+o)),t(o,1);return a}function a(n,t,e){try{nc=Rt;var r=new Rt;return r._=n,o(r,t,e)}finally{nc=Date}}n.floor=n,n.round=r,n.ceil=u,n.offset=i,n.range=o;var c=n.utc=Pt(n);return c.floor=c,c.round=Pt(r),c.ceil=Pt(u),c.offset=Pt(i),c.range=a,n}function Pt(n){return function(t,e){try{nc=Rt;var r=new Rt;return r._=t,n(r,e)._}finally{nc=Date}}}function Ut(n){function t(n){function t(t){for(var e,u,i,o=[],a=-1,c=0;++a<r;)37===n.charCodeAt(a)&&(o.push(n.substring(c,a)),null!=(u=ec[e=n.charAt(++a)])&&(e=n.charAt(++a)),(i=C[e])&&(e=i(t,null==u?"e"===e?" ":"0":u)),o.push(e),c=a+1);return o.push(n.substring(c,a)),o.join("")}var r=n.length;return t.parse=function(t){var r={y:1900,m:0,d:1,H:0,M:0,S:0,L:0,Z:null},u=e(r,n,t,0);if(u!=t.length)return null;"p"in r&&(r.H=r.H%12+12*r.p);var i=null!=r.Z&&nc!==Rt,o=new(i?Rt:nc);return"j"in r?o.setFullYear(r.y,0,r.j):"w"in r&&("W"in r||"U"in r)?(o.setFullYear(r.y,0,1),o.setFullYear(r.y,0,"W"in r?(r.w+6)%7+7*r.W-(o.getDay()+5)%7:r.w+7*r.U-(o.getDay()+6)%7)):o.setFullYear(r.y,r.m,r.d),o.setHours(r.H+Math.floor(r.Z/100),r.M+r.Z%100,r.S,r.L),i?o._:o},t.toString=function(){return n},t}function e(n,t,e,r){for(var u,i,o,a=0,c=t.length,s=e.length;c>a;){if(r>=s)return-1;if(u=t.charCodeAt(a++),37===u){if(o=t.charAt(a++),i=N[o in ec?t.charAt(a++):o],!i||(r=i(n,e,r))<0)return-1}else if(u!=e.charCodeAt(r++))return-1}return r}function r(n,t,e){b.lastIndex=0;var r=b.exec(t.substring(e));return r?(n.w=w.get(r[0].toLowerCase()),e+r[0].length):-1}function u(n,t,e){M.lastIndex=0;var r=M.exec(t.substring(e));return r?(n.w=_.get(r[0].toLowerCase()),e+r[0].length):-1}function i(n,t,e){E.lastIndex=0;var r=E.exec(t.substring(e));return r?(n.m=A.get(r[0].toLowerCase()),e+r[0].length):-1}function o(n,t,e){S.lastIndex=0;var r=S.exec(t.substring(e));return r?(n.m=k.get(r[0].toLowerCase()),e+r[0].length):-1}function a(n,t,r){return e(n,C.c.toString(),t,r)}function c(n,t,r){return e(n,C.x.toString(),t,r)}function s(n,t,r){return e(n,C.X.toString(),t,r)}function l(n,t,e){var r=x.get(t.substring(e,e+=2).toLowerCase());return null==r?-1:(n.p=r,e)}var f=n.dateTime,h=n.date,g=n.time,p=n.periods,v=n.days,d=n.shortDays,m=n.months,y=n.shortMonths;t.utc=function(n){function e(n){try{nc=Rt;var t=new nc;return t._=n,r(t)}finally{nc=Date}}var r=t(n);return e.parse=function(n){try{nc=Rt;var t=r.parse(n);return t&&t._}finally{nc=Date}},e.toString=r.toString,e},t.multi=t.utc.multi=re;var x=Zo.map(),M=Ht(v),_=Ft(v),b=Ht(d),w=Ft(d),S=Ht(m),k=Ft(m),E=Ht(y),A=Ft(y);p.forEach(function(n,t){x.set(n.toLowerCase(),t)});var C={a:function(n){return d[n.getDay()]},A:function(n){return v[n.getDay()]},b:function(n){return y[n.getMonth()]},B:function(n){return m[n.getMonth()]},c:t(f),d:function(n,t){return jt(n.getDate(),t,2)},e:function(n,t){return jt(n.getDate(),t,2)},H:function(n,t){return jt(n.getHours(),t,2)},I:function(n,t){return jt(n.getHours()%12||12,t,2)},j:function(n,t){return jt(1+Qa.dayOfYear(n),t,3)},L:function(n,t){return jt(n.getMilliseconds(),t,3)},m:function(n,t){return jt(n.getMonth()+1,t,2)},M:function(n,t){return jt(n.getMinutes(),t,2)},p:function(n){return p[+(n.getHours()>=12)]},S:function(n,t){return jt(n.getSeconds(),t,2)},U:function(n,t){return jt(Qa.sundayOfYear(n),t,2)},w:function(n){return n.getDay()},W:function(n,t){return jt(Qa.mondayOfYear(n),t,2)},x:t(h),X:t(g),y:function(n,t){return jt(n.getFullYear()%100,t,2)},Y:function(n,t){return jt(n.getFullYear()%1e4,t,4)},Z:te,"%":function(){return"%"}},N={a:r,A:u,b:i,B:o,c:a,d:Wt,e:Wt,H:Gt,I:Gt,j:Jt,L:ne,m:Bt,M:Kt,p:l,S:Qt,U:Yt,w:Ot,W:It,x:c,X:s,y:Vt,Y:Zt,Z:Xt,"%":ee};return t}function jt(n,t,e){var r=0>n?"-":"",u=(r?-n:n)+"",i=u.length;return r+(e>i?new Array(e-i+1).join(t)+u:u)}function Ht(n){return new RegExp("^(?:"+n.map(Zo.requote).join("|")+")","i")}function Ft(n){for(var t=new o,e=-1,r=n.length;++e<r;)t.set(n[e].toLowerCase(),e);return t}function Ot(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+1));return r?(n.w=+r[0],e+r[0].length):-1}function Yt(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e));return r?(n.U=+r[0],e+r[0].length):-1}function It(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e));return r?(n.W=+r[0],e+r[0].length):-1}function Zt(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+4));return r?(n.y=+r[0],e+r[0].length):-1}function Vt(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+2));return r?(n.y=$t(+r[0]),e+r[0].length):-1}function Xt(n,t,e){return/^[+-]\d{4}$/.test(t=t.substring(e,e+5))?(n.Z=-t,e+5):-1}function $t(n){return n+(n>68?1900:2e3)}function Bt(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+2));return r?(n.m=r[0]-1,e+r[0].length):-1}function Wt(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+2));return r?(n.d=+r[0],e+r[0].length):-1}function Jt(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+3));return r?(n.j=+r[0],e+r[0].length):-1}function Gt(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+2));return r?(n.H=+r[0],e+r[0].length):-1}function Kt(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+2));return r?(n.M=+r[0],e+r[0].length):-1}function Qt(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+2));return r?(n.S=+r[0],e+r[0].length):-1}function ne(n,t,e){rc.lastIndex=0;var r=rc.exec(t.substring(e,e+3));return r?(n.L=+r[0],e+r[0].length):-1}function te(n){var t=n.getTimezoneOffset(),e=t>0?"-":"+",r=~~(ua(t)/60),u=ua(t)%60;return e+jt(r,"0",2)+jt(u,"0",2)}function ee(n,t,e){uc.lastIndex=0;var r=uc.exec(t.substring(e,e+1));return r?e+r[0].length:-1}function re(n){for(var t=n.length,e=-1;++e<t;)n[e][0]=this(n[e][0]);return function(t){for(var e=0,r=n[e];!r[1](t);)r=n[++e];return r[0](t)}}function ue(){}function ie(n,t,e){var r=e.s=n+t,u=r-n,i=r-u;e.t=n-i+(t-u)}function oe(n,t){n&&cc.hasOwnProperty(n.type)&&cc[n.type](n,t)}function ae(n,t,e){var r,u=-1,i=n.length-e;for(t.lineStart();++u<i;)r=n[u],t.point(r[0],r[1],r[2]);t.lineEnd()}function ce(n,t){var e=-1,r=n.length;for(t.polygonStart();++e<r;)ae(n[e],t,1);t.polygonEnd()}function se(){function n(n,t){n*=Aa,t=t*Aa/2+ba/4;var e=n-r,o=e>=0?1:-1,a=o*e,c=Math.cos(t),s=Math.sin(t),l=i*s,f=u*c+l*Math.cos(a),h=l*o*Math.sin(a);lc.add(Math.atan2(h,f)),r=n,u=c,i=s}var t,e,r,u,i;fc.point=function(o,a){fc.point=n,r=(t=o)*Aa,u=Math.cos(a=(e=a)*Aa/2+ba/4),i=Math.sin(a)},fc.lineEnd=function(){n(t,e)}}function le(n){var t=n[0],e=n[1],r=Math.cos(e);return[r*Math.cos(t),r*Math.sin(t),Math.sin(e)]}function fe(n,t){return n[0]*t[0]+n[1]*t[1]+n[2]*t[2]}function he(n,t){return[n[1]*t[2]-n[2]*t[1],n[2]*t[0]-n[0]*t[2],n[0]*t[1]-n[1]*t[0]]}function ge(n,t){n[0]+=t[0],n[1]+=t[1],n[2]+=t[2]}function pe(n,t){return[n[0]*t,n[1]*t,n[2]*t]}function ve(n){var t=Math.sqrt(n[0]*n[0]+n[1]*n[1]+n[2]*n[2]);n[0]/=t,n[1]/=t,n[2]/=t}function de(n){return[Math.atan2(n[1],n[0]),G(n[2])]}function me(n,t){return ua(n[0]-t[0])<ka&&ua(n[1]-t[1])<ka}function ye(n,t){n*=Aa;var e=Math.cos(t*=Aa);xe(e*Math.cos(n),e*Math.sin(n),Math.sin(t))}function xe(n,t,e){++hc,pc+=(n-pc)/hc,vc+=(t-vc)/hc,dc+=(e-dc)/hc}function Me(){function n(n,u){n*=Aa;var i=Math.cos(u*=Aa),o=i*Math.cos(n),a=i*Math.sin(n),c=Math.sin(u),s=Math.atan2(Math.sqrt((s=e*c-r*a)*s+(s=r*o-t*c)*s+(s=t*a-e*o)*s),t*o+e*a+r*c);gc+=s,mc+=s*(t+(t=o)),yc+=s*(e+(e=a)),xc+=s*(r+(r=c)),xe(t,e,r)}var t,e,r;wc.point=function(u,i){u*=Aa;var o=Math.cos(i*=Aa);t=o*Math.cos(u),e=o*Math.sin(u),r=Math.sin(i),wc.point=n,xe(t,e,r)}}function _e(){wc.point=ye}function be(){function n(n,t){n*=Aa;var e=Math.cos(t*=Aa),o=e*Math.cos(n),a=e*Math.sin(n),c=Math.sin(t),s=u*c-i*a,l=i*o-r*c,f=r*a-u*o,h=Math.sqrt(s*s+l*l+f*f),g=r*o+u*a+i*c,p=h&&-J(g)/h,v=Math.atan2(h,g);Mc+=p*s,_c+=p*l,bc+=p*f,gc+=v,mc+=v*(r+(r=o)),yc+=v*(u+(u=a)),xc+=v*(i+(i=c)),xe(r,u,i)}var t,e,r,u,i;wc.point=function(o,a){t=o,e=a,wc.point=n,o*=Aa;var c=Math.cos(a*=Aa);r=c*Math.cos(o),u=c*Math.sin(o),i=Math.sin(a),xe(r,u,i)},wc.lineEnd=function(){n(t,e),wc.lineEnd=_e,wc.point=ye}}function we(){return!0}function Se(n,t,e,r,u){var i=[],o=[];if(n.forEach(function(n){if(!((t=n.length-1)<=0)){var t,e=n[0],r=n[t];if(me(e,r)){u.lineStart();for(var a=0;t>a;++a)u.point((e=n[a])[0],e[1]);return u.lineEnd(),void 0}var c=new Ee(e,n,null,!0),s=new Ee(e,null,c,!1);c.o=s,i.push(c),o.push(s),c=new Ee(r,n,null,!1),s=new Ee(r,null,c,!0),c.o=s,i.push(c),o.push(s)}}),o.sort(t),ke(i),ke(o),i.length){for(var a=0,c=e,s=o.length;s>a;++a)o[a].e=c=!c;for(var l,f,h=i[0];;){for(var g=h,p=!0;g.v;)if((g=g.n)===h)return;l=g.z,u.lineStart();do{if(g.v=g.o.v=!0,g.e){if(p)for(var a=0,s=l.length;s>a;++a)u.point((f=l[a])[0],f[1]);else r(g.x,g.n.x,1,u);g=g.n}else{if(p){l=g.p.z;for(var a=l.length-1;a>=0;--a)u.point((f=l[a])[0],f[1])}else r(g.x,g.p.x,-1,u);g=g.p}g=g.o,l=g.z,p=!p}while(!g.v);u.lineEnd()}}}function ke(n){if(t=n.length){for(var t,e,r=0,u=n[0];++r<t;)u.n=e=n[r],e.p=u,u=e;u.n=e=n[0],e.p=u}}function Ee(n,t,e,r){this.x=n,this.z=t,this.o=e,this.e=r,this.v=!1,this.n=this.p=null}function Ae(n,t,e,r){return function(u,i){function o(t,e){var r=u(t,e);n(t=r[0],e=r[1])&&i.point(t,e)}function a(n,t){var e=u(n,t);d.point(e[0],e[1])}function c(){y.point=a,d.lineStart()}function s(){y.point=o,d.lineEnd()}function l(n,t){v.push([n,t]);var e=u(n,t);M.point(e[0],e[1])}function f(){M.lineStart(),v=[]}function h(){l(v[0][0],v[0][1]),M.lineEnd();var n,t=M.clean(),e=x.buffer(),r=e.length;if(v.pop(),p.push(v),v=null,r)if(1&t){n=e[0];var u,r=n.length-1,o=-1;if(r>0){for(_||(i.polygonStart(),_=!0),i.lineStart();++o<r;)i.point((u=n[o])[0],u[1]);i.lineEnd()}}else r>1&&2&t&&e.push(e.pop().concat(e.shift())),g.push(e.filter(Ce))}var g,p,v,d=t(i),m=u.invert(r[0],r[1]),y={point:o,lineStart:c,lineEnd:s,polygonStart:function(){y.point=l,y.lineStart=f,y.lineEnd=h,g=[],p=[]},polygonEnd:function(){y.point=o,y.lineStart=c,y.lineEnd=s,g=Zo.merge(g);var n=Le(m,p);g.length?(_||(i.polygonStart(),_=!0),Se(g,ze,n,e,i)):n&&(_||(i.polygonStart(),_=!0),i.lineStart(),e(null,null,1,i),i.lineEnd()),_&&(i.polygonEnd(),_=!1),g=p=null},sphere:function(){i.polygonStart(),i.lineStart(),e(null,null,1,i),i.lineEnd(),i.polygonEnd()}},x=Ne(),M=t(x),_=!1;return y}}function Ce(n){return n.length>1}function Ne(){var n,t=[];return{lineStart:function(){t.push(n=[])},point:function(t,e){n.push([t,e])},lineEnd:v,buffer:function(){var e=t;return t=[],n=null,e},rejoin:function(){t.length>1&&t.push(t.pop().concat(t.shift()))}}}function ze(n,t){return((n=n.x)[0]<0?n[1]-Sa-ka:Sa-n[1])-((t=t.x)[0]<0?t[1]-Sa-ka:Sa-t[1])}function Le(n,t){var e=n[0],r=n[1],u=[Math.sin(e),-Math.cos(e),0],i=0,o=0;lc.reset();for(var a=0,c=t.length;c>a;++a){var s=t[a],l=s.length;if(l)for(var f=s[0],h=f[0],g=f[1]/2+ba/4,p=Math.sin(g),v=Math.cos(g),d=1;;){d===l&&(d=0),n=s[d];var m=n[0],y=n[1]/2+ba/4,x=Math.sin(y),M=Math.cos(y),_=m-h,b=_>=0?1:-1,w=b*_,S=w>ba,k=p*x;if(lc.add(Math.atan2(k*b*Math.sin(w),v*M+k*Math.cos(w))),i+=S?_+b*wa:_,S^h>=e^m>=e){var E=he(le(f),le(n));ve(E);var A=he(u,E);ve(A);var C=(S^_>=0?-1:1)*G(A[2]);(r>C||r===C&&(E[0]||E[1]))&&(o+=S^_>=0?1:-1)}if(!d++)break;h=m,p=x,v=M,f=n}}return(-ka>i||ka>i&&0>lc)^1&o}function Te(n){var t,e=0/0,r=0/0,u=0/0;return{lineStart:function(){n.lineStart(),t=1},point:function(i,o){var a=i>0?ba:-ba,c=ua(i-e);ua(c-ba)<ka?(n.point(e,r=(r+o)/2>0?Sa:-Sa),n.point(u,r),n.lineEnd(),n.lineStart(),n.point(a,r),n.point(i,r),t=0):u!==a&&c>=ba&&(ua(e-u)<ka&&(e-=u*ka),ua(i-a)<ka&&(i-=a*ka),r=qe(e,r,i,o),n.point(u,r),n.lineEnd(),n.lineStart(),n.point(a,r),t=0),n.point(e=i,r=o),u=a},lineEnd:function(){n.lineEnd(),e=r=0/0},clean:function(){return 2-t}}}function qe(n,t,e,r){var u,i,o=Math.sin(n-e);return ua(o)>ka?Math.atan((Math.sin(t)*(i=Math.cos(r))*Math.sin(e)-Math.sin(r)*(u=Math.cos(t))*Math.sin(n))/(u*i*o)):(t+r)/2}function Re(n,t,e,r){var u;if(null==n)u=e*Sa,r.point(-ba,u),r.point(0,u),r.point(ba,u),r.point(ba,0),r.point(ba,-u),r.point(0,-u),r.point(-ba,-u),r.point(-ba,0),r.point(-ba,u);else if(ua(n[0]-t[0])>ka){var i=n[0]<t[0]?ba:-ba;u=e*i/2,r.point(-i,u),r.point(0,u),r.point(i,u)}else r.point(t[0],t[1])}function De(n){function t(n,t){return Math.cos(n)*Math.cos(t)>i}function e(n){var e,i,c,s,l;return{lineStart:function(){s=c=!1,l=1},point:function(f,h){var g,p=[f,h],v=t(f,h),d=o?v?0:u(f,h):v?u(f+(0>f?ba:-ba),h):0;if(!e&&(s=c=v)&&n.lineStart(),v!==c&&(g=r(e,p),(me(e,g)||me(p,g))&&(p[0]+=ka,p[1]+=ka,v=t(p[0],p[1]))),v!==c)l=0,v?(n.lineStart(),g=r(p,e),n.point(g[0],g[1])):(g=r(e,p),n.point(g[0],g[1]),n.lineEnd()),e=g;else if(a&&e&&o^v){var m;d&i||!(m=r(p,e,!0))||(l=0,o?(n.lineStart(),n.point(m[0][0],m[0][1]),n.point(m[1][0],m[1][1]),n.lineEnd()):(n.point(m[1][0],m[1][1]),n.lineEnd(),n.lineStart(),n.point(m[0][0],m[0][1])))}!v||e&&me(e,p)||n.point(p[0],p[1]),e=p,c=v,i=d},lineEnd:function(){c&&n.lineEnd(),e=null},clean:function(){return l|(s&&c)<<1}}}function r(n,t,e){var r=le(n),u=le(t),o=[1,0,0],a=he(r,u),c=fe(a,a),s=a[0],l=c-s*s;if(!l)return!e&&n;var f=i*c/l,h=-i*s/l,g=he(o,a),p=pe(o,f),v=pe(a,h);ge(p,v);var d=g,m=fe(p,d),y=fe(d,d),x=m*m-y*(fe(p,p)-1);if(!(0>x)){var M=Math.sqrt(x),_=pe(d,(-m-M)/y);if(ge(_,p),_=de(_),!e)return _;var b,w=n[0],S=t[0],k=n[1],E=t[1];w>S&&(b=w,w=S,S=b);var A=S-w,C=ua(A-ba)<ka,N=C||ka>A;if(!C&&k>E&&(b=k,k=E,E=b),N?C?k+E>0^_[1]<(ua(_[0]-w)<ka?k:E):k<=_[1]&&_[1]<=E:A>ba^(w<=_[0]&&_[0]<=S)){var z=pe(d,(-m+M)/y);return ge(z,p),[_,de(z)]}}}function u(t,e){var r=o?n:ba-n,u=0;return-r>t?u|=1:t>r&&(u|=2),-r>e?u|=4:e>r&&(u|=8),u}var i=Math.cos(n),o=i>0,a=ua(i)>ka,c=sr(n,6*Aa);return Ae(t,e,c,o?[0,-n]:[-ba,n-ba])}function Pe(n,t,e,r){return function(u){var i,o=u.a,a=u.b,c=o.x,s=o.y,l=a.x,f=a.y,h=0,g=1,p=l-c,v=f-s;if(i=n-c,p||!(i>0)){if(i/=p,0>p){if(h>i)return;g>i&&(g=i)}else if(p>0){if(i>g)return;i>h&&(h=i)}if(i=e-c,p||!(0>i)){if(i/=p,0>p){if(i>g)return;i>h&&(h=i)}else if(p>0){if(h>i)return;g>i&&(g=i)}if(i=t-s,v||!(i>0)){if(i/=v,0>v){if(h>i)return;g>i&&(g=i)}else if(v>0){if(i>g)return;i>h&&(h=i)}if(i=r-s,v||!(0>i)){if(i/=v,0>v){if(i>g)return;i>h&&(h=i)}else if(v>0){if(h>i)return;g>i&&(g=i)}return h>0&&(u.a={x:c+h*p,y:s+h*v}),1>g&&(u.b={x:c+g*p,y:s+g*v}),u}}}}}}function Ue(n,t,e,r){function u(r,u){return ua(r[0]-n)<ka?u>0?0:3:ua(r[0]-e)<ka?u>0?2:1:ua(r[1]-t)<ka?u>0?1:0:u>0?3:2}function i(n,t){return o(n.x,t.x)}function o(n,t){var e=u(n,1),r=u(t,1);return e!==r?e-r:0===e?t[1]-n[1]:1===e?n[0]-t[0]:2===e?n[1]-t[1]:t[0]-n[0]}return function(a){function c(n){for(var t=0,e=d.length,r=n[1],u=0;e>u;++u)for(var i,o=1,a=d[u],c=a.length,s=a[0];c>o;++o)i=a[o],s[1]<=r?i[1]>r&&W(s,i,n)>0&&++t:i[1]<=r&&W(s,i,n)<0&&--t,s=i;return 0!==t}function s(i,a,c,s){var l=0,f=0;if(null==i||(l=u(i,c))!==(f=u(a,c))||o(i,a)<0^c>0){do s.point(0===l||3===l?n:e,l>1?r:t);while((l=(l+c+4)%4)!==f)}else s.point(a[0],a[1])}function l(u,i){return u>=n&&e>=u&&i>=t&&r>=i}function f(n,t){l(n,t)&&a.point(n,t)}function h(){N.point=p,d&&d.push(m=[]),S=!0,w=!1,_=b=0/0}function g(){v&&(p(y,x),M&&w&&A.rejoin(),v.push(A.buffer())),N.point=f,w&&a.lineEnd()}function p(n,t){n=Math.max(-kc,Math.min(kc,n)),t=Math.max(-kc,Math.min(kc,t));var e=l(n,t);if(d&&m.push([n,t]),S)y=n,x=t,M=e,S=!1,e&&(a.lineStart(),a.point(n,t));else if(e&&w)a.point(n,t);else{var r={a:{x:_,y:b},b:{x:n,y:t}};C(r)?(w||(a.lineStart(),a.point(r.a.x,r.a.y)),a.point(r.b.x,r.b.y),e||a.lineEnd(),k=!1):e&&(a.lineStart(),a.point(n,t),k=!1)}_=n,b=t,w=e}var v,d,m,y,x,M,_,b,w,S,k,E=a,A=Ne(),C=Pe(n,t,e,r),N={point:f,lineStart:h,lineEnd:g,polygonStart:function(){a=A,v=[],d=[],k=!0},polygonEnd:function(){a=E,v=Zo.merge(v);var t=c([n,r]),e=k&&t,u=v.length;(e||u)&&(a.polygonStart(),e&&(a.lineStart(),s(null,null,1,a),a.lineEnd()),u&&Se(v,i,t,s,a),a.polygonEnd()),v=d=m=null}};return N}}function je(n,t){function e(e,r){return e=n(e,r),t(e[0],e[1])}return n.invert&&t.invert&&(e.invert=function(e,r){return e=t.invert(e,r),e&&n.invert(e[0],e[1])}),e}function He(n){var t=0,e=ba/3,r=tr(n),u=r(t,e);return u.parallels=function(n){return arguments.length?r(t=n[0]*ba/180,e=n[1]*ba/180):[180*(t/ba),180*(e/ba)]},u}function Fe(n,t){function e(n,t){var e=Math.sqrt(i-2*u*Math.sin(t))/u;return[e*Math.sin(n*=u),o-e*Math.cos(n)]}var r=Math.sin(n),u=(r+Math.sin(t))/2,i=1+r*(2*u-r),o=Math.sqrt(i)/u;return e.invert=function(n,t){var e=o-t;return[Math.atan2(n,e)/u,G((i-(n*n+e*e)*u*u)/(2*u))]},e}function Oe(){function n(n,t){Ac+=u*n-r*t,r=n,u=t}var t,e,r,u;Tc.point=function(i,o){Tc.point=n,t=r=i,e=u=o},Tc.lineEnd=function(){n(t,e)}}function Ye(n,t){Cc>n&&(Cc=n),n>zc&&(zc=n),Nc>t&&(Nc=t),t>Lc&&(Lc=t)}function Ie(){function n(n,t){o.push("M",n,",",t,i)}function t(n,t){o.push("M",n,",",t),a.point=e}function e(n,t){o.push("L",n,",",t)}function r(){a.point=n}function u(){o.push("Z")}var i=Ze(4.5),o=[],a={point:n,lineStart:function(){a.point=t},lineEnd:r,polygonStart:function(){a.lineEnd=u},polygonEnd:function(){a.lineEnd=r,a.point=n},pointRadius:function(n){return i=Ze(n),a},result:function(){if(o.length){var n=o.join("");return o=[],n}}};return a}function Ze(n){return"m0,"+n+"a"+n+","+n+" 0 1,1 0,"+-2*n+"a"+n+","+n+" 0 1,1 0,"+2*n+"z"}function Ve(n,t){pc+=n,vc+=t,++dc}function Xe(){function n(n,r){var u=n-t,i=r-e,o=Math.sqrt(u*u+i*i);mc+=o*(t+n)/2,yc+=o*(e+r)/2,xc+=o,Ve(t=n,e=r)}var t,e;Rc.point=function(r,u){Rc.point=n,Ve(t=r,e=u)}}function $e(){Rc.point=Ve}function Be(){function n(n,t){var e=n-r,i=t-u,o=Math.sqrt(e*e+i*i);mc+=o*(r+n)/2,yc+=o*(u+t)/2,xc+=o,o=u*n-r*t,Mc+=o*(r+n),_c+=o*(u+t),bc+=3*o,Ve(r=n,u=t)}var t,e,r,u;Rc.point=function(i,o){Rc.point=n,Ve(t=r=i,e=u=o)},Rc.lineEnd=function(){n(t,e)}}function We(n){function t(t,e){n.moveTo(t,e),n.arc(t,e,o,0,wa)}function e(t,e){n.moveTo(t,e),a.point=r}function r(t,e){n.lineTo(t,e)}function u(){a.point=t}function i(){n.closePath()}var o=4.5,a={point:t,lineStart:function(){a.point=e},lineEnd:u,polygonStart:function(){a.lineEnd=i},polygonEnd:function(){a.lineEnd=u,a.point=t},pointRadius:function(n){return o=n,a},result:v};return a}function Je(n){function t(n){return(a?r:e)(n)}function e(t){return Qe(t,function(e,r){e=n(e,r),t.point(e[0],e[1])})}function r(t){function e(e,r){e=n(e,r),t.point(e[0],e[1])}function r(){x=0/0,S.point=i,t.lineStart()}function i(e,r){var i=le([e,r]),o=n(e,r);u(x,M,y,_,b,w,x=o[0],M=o[1],y=e,_=i[0],b=i[1],w=i[2],a,t),t.point(x,M)}function o(){S.point=e,t.lineEnd()}function c(){r(),S.point=s,S.lineEnd=l}function s(n,t){i(f=n,h=t),g=x,p=M,v=_,d=b,m=w,S.point=i}function l(){u(x,M,y,_,b,w,g,p,f,v,d,m,a,t),S.lineEnd=o,o()}var f,h,g,p,v,d,m,y,x,M,_,b,w,S={point:e,lineStart:r,lineEnd:o,polygonStart:function(){t.polygonStart(),S.lineStart=c},polygonEnd:function(){t.polygonEnd(),S.lineStart=r}};return S}function u(t,e,r,a,c,s,l,f,h,g,p,v,d,m){var y=l-t,x=f-e,M=y*y+x*x;if(M>4*i&&d--){var _=a+g,b=c+p,w=s+v,S=Math.sqrt(_*_+b*b+w*w),k=Math.asin(w/=S),E=ua(ua(w)-1)<ka||ua(r-h)<ka?(r+h)/2:Math.atan2(b,_),A=n(E,k),C=A[0],N=A[1],z=C-t,L=N-e,T=x*z-y*L;(T*T/M>i||ua((y*z+x*L)/M-.5)>.3||o>a*g+c*p+s*v)&&(u(t,e,r,a,c,s,C,N,E,_/=S,b/=S,w,d,m),m.point(C,N),u(C,N,E,_,b,w,l,f,h,g,p,v,d,m))}}var i=.5,o=Math.cos(30*Aa),a=16;
return t.precision=function(n){return arguments.length?(a=(i=n*n)>0&&16,t):Math.sqrt(i)},t}function Ge(n){var t=Je(function(t,e){return n([t*Ca,e*Ca])});return function(n){return er(t(n))}}function Ke(n){this.stream=n}function Qe(n,t){return{point:t,sphere:function(){n.sphere()},lineStart:function(){n.lineStart()},lineEnd:function(){n.lineEnd()},polygonStart:function(){n.polygonStart()},polygonEnd:function(){n.polygonEnd()}}}function nr(n){return tr(function(){return n})()}function tr(n){function t(n){return n=a(n[0]*Aa,n[1]*Aa),[n[0]*h+c,s-n[1]*h]}function e(n){return n=a.invert((n[0]-c)/h,(s-n[1])/h),n&&[n[0]*Ca,n[1]*Ca]}function r(){a=je(o=ir(m,y,x),i);var n=i(v,d);return c=g-n[0]*h,s=p+n[1]*h,u()}function u(){return l&&(l.valid=!1,l=null),t}var i,o,a,c,s,l,f=Je(function(n,t){return n=i(n,t),[n[0]*h+c,s-n[1]*h]}),h=150,g=480,p=250,v=0,d=0,m=0,y=0,x=0,M=Sc,_=wt,b=null,w=null;return t.stream=function(n){return l&&(l.valid=!1),l=er(M(o,f(_(n)))),l.valid=!0,l},t.clipAngle=function(n){return arguments.length?(M=null==n?(b=n,Sc):De((b=+n)*Aa),u()):b},t.clipExtent=function(n){return arguments.length?(w=n,_=n?Ue(n[0][0],n[0][1],n[1][0],n[1][1]):wt,u()):w},t.scale=function(n){return arguments.length?(h=+n,r()):h},t.translate=function(n){return arguments.length?(g=+n[0],p=+n[1],r()):[g,p]},t.center=function(n){return arguments.length?(v=n[0]%360*Aa,d=n[1]%360*Aa,r()):[v*Ca,d*Ca]},t.rotate=function(n){return arguments.length?(m=n[0]%360*Aa,y=n[1]%360*Aa,x=n.length>2?n[2]%360*Aa:0,r()):[m*Ca,y*Ca,x*Ca]},Zo.rebind(t,f,"precision"),function(){return i=n.apply(this,arguments),t.invert=i.invert&&e,r()}}function er(n){return Qe(n,function(t,e){n.point(t*Aa,e*Aa)})}function rr(n,t){return[n,t]}function ur(n,t){return[n>ba?n-wa:-ba>n?n+wa:n,t]}function ir(n,t,e){return n?t||e?je(ar(n),cr(t,e)):ar(n):t||e?cr(t,e):ur}function or(n){return function(t,e){return t+=n,[t>ba?t-wa:-ba>t?t+wa:t,e]}}function ar(n){var t=or(n);return t.invert=or(-n),t}function cr(n,t){function e(n,t){var e=Math.cos(t),a=Math.cos(n)*e,c=Math.sin(n)*e,s=Math.sin(t),l=s*r+a*u;return[Math.atan2(c*i-l*o,a*r-s*u),G(l*i+c*o)]}var r=Math.cos(n),u=Math.sin(n),i=Math.cos(t),o=Math.sin(t);return e.invert=function(n,t){var e=Math.cos(t),a=Math.cos(n)*e,c=Math.sin(n)*e,s=Math.sin(t),l=s*i-c*o;return[Math.atan2(c*i+s*o,a*r+l*u),G(l*r-a*u)]},e}function sr(n,t){var e=Math.cos(n),r=Math.sin(n);return function(u,i,o,a){var c=o*t;null!=u?(u=lr(e,u),i=lr(e,i),(o>0?i>u:u>i)&&(u+=o*wa)):(u=n+o*wa,i=n-.5*c);for(var s,l=u;o>0?l>i:i>l;l-=c)a.point((s=de([e,-r*Math.cos(l),-r*Math.sin(l)]))[0],s[1])}}function lr(n,t){var e=le(t);e[0]-=n,ve(e);var r=J(-e[1]);return((-e[2]<0?-r:r)+2*Math.PI-ka)%(2*Math.PI)}function fr(n,t,e){var r=Zo.range(n,t-ka,e).concat(t);return function(n){return r.map(function(t){return[n,t]})}}function hr(n,t,e){var r=Zo.range(n,t-ka,e).concat(t);return function(n){return r.map(function(t){return[t,n]})}}function gr(n){return n.source}function pr(n){return n.target}function vr(n,t,e,r){var u=Math.cos(t),i=Math.sin(t),o=Math.cos(r),a=Math.sin(r),c=u*Math.cos(n),s=u*Math.sin(n),l=o*Math.cos(e),f=o*Math.sin(e),h=2*Math.asin(Math.sqrt(tt(r-t)+u*o*tt(e-n))),g=1/Math.sin(h),p=h?function(n){var t=Math.sin(n*=h)*g,e=Math.sin(h-n)*g,r=e*c+t*l,u=e*s+t*f,o=e*i+t*a;return[Math.atan2(u,r)*Ca,Math.atan2(o,Math.sqrt(r*r+u*u))*Ca]}:function(){return[n*Ca,t*Ca]};return p.distance=h,p}function dr(){function n(n,u){var i=Math.sin(u*=Aa),o=Math.cos(u),a=ua((n*=Aa)-t),c=Math.cos(a);Dc+=Math.atan2(Math.sqrt((a=o*Math.sin(a))*a+(a=r*i-e*o*c)*a),e*i+r*o*c),t=n,e=i,r=o}var t,e,r;Pc.point=function(u,i){t=u*Aa,e=Math.sin(i*=Aa),r=Math.cos(i),Pc.point=n},Pc.lineEnd=function(){Pc.point=Pc.lineEnd=v}}function mr(n,t){function e(t,e){var r=Math.cos(t),u=Math.cos(e),i=n(r*u);return[i*u*Math.sin(t),i*Math.sin(e)]}return e.invert=function(n,e){var r=Math.sqrt(n*n+e*e),u=t(r),i=Math.sin(u),o=Math.cos(u);return[Math.atan2(n*i,r*o),Math.asin(r&&e*i/r)]},e}function yr(n,t){function e(n,t){o>0?-Sa+ka>t&&(t=-Sa+ka):t>Sa-ka&&(t=Sa-ka);var e=o/Math.pow(u(t),i);return[e*Math.sin(i*n),o-e*Math.cos(i*n)]}var r=Math.cos(n),u=function(n){return Math.tan(ba/4+n/2)},i=n===t?Math.sin(n):Math.log(r/Math.cos(t))/Math.log(u(t)/u(n)),o=r*Math.pow(u(n),i)/i;return i?(e.invert=function(n,t){var e=o-t,r=B(i)*Math.sqrt(n*n+e*e);return[Math.atan2(n,e)/i,2*Math.atan(Math.pow(o/r,1/i))-Sa]},e):Mr}function xr(n,t){function e(n,t){var e=i-t;return[e*Math.sin(u*n),i-e*Math.cos(u*n)]}var r=Math.cos(n),u=n===t?Math.sin(n):(r-Math.cos(t))/(t-n),i=r/u+n;return ua(u)<ka?rr:(e.invert=function(n,t){var e=i-t;return[Math.atan2(n,e)/u,i-B(u)*Math.sqrt(n*n+e*e)]},e)}function Mr(n,t){return[n,Math.log(Math.tan(ba/4+t/2))]}function _r(n){var t,e=nr(n),r=e.scale,u=e.translate,i=e.clipExtent;return e.scale=function(){var n=r.apply(e,arguments);return n===e?t?e.clipExtent(null):e:n},e.translate=function(){var n=u.apply(e,arguments);return n===e?t?e.clipExtent(null):e:n},e.clipExtent=function(n){var o=i.apply(e,arguments);if(o===e){if(t=null==n){var a=ba*r(),c=u();i([[c[0]-a,c[1]-a],[c[0]+a,c[1]+a]])}}else t&&(o=null);return o},e.clipExtent(null)}function br(n,t){return[Math.log(Math.tan(ba/4+t/2)),-n]}function wr(n){return n[0]}function Sr(n){return n[1]}function kr(n){for(var t=n.length,e=[0,1],r=2,u=2;t>u;u++){for(;r>1&&W(n[e[r-2]],n[e[r-1]],n[u])<=0;)--r;e[r++]=u}return e.slice(0,r)}function Er(n,t){return n[0]-t[0]||n[1]-t[1]}function Ar(n,t,e){return(e[0]-t[0])*(n[1]-t[1])<(e[1]-t[1])*(n[0]-t[0])}function Cr(n,t,e,r){var u=n[0],i=e[0],o=t[0]-u,a=r[0]-i,c=n[1],s=e[1],l=t[1]-c,f=r[1]-s,h=(a*(c-s)-f*(u-i))/(f*o-a*l);return[u+h*o,c+h*l]}function Nr(n){var t=n[0],e=n[n.length-1];return!(t[0]-e[0]||t[1]-e[1])}function zr(){Gr(this),this.edge=this.site=this.circle=null}function Lr(n){var t=Bc.pop()||new zr;return t.site=n,t}function Tr(n){Yr(n),Vc.remove(n),Bc.push(n),Gr(n)}function qr(n){var t=n.circle,e=t.x,r=t.cy,u={x:e,y:r},i=n.P,o=n.N,a=[n];Tr(n);for(var c=i;c.circle&&ua(e-c.circle.x)<ka&&ua(r-c.circle.cy)<ka;)i=c.P,a.unshift(c),Tr(c),c=i;a.unshift(c),Yr(c);for(var s=o;s.circle&&ua(e-s.circle.x)<ka&&ua(r-s.circle.cy)<ka;)o=s.N,a.push(s),Tr(s),s=o;a.push(s),Yr(s);var l,f=a.length;for(l=1;f>l;++l)s=a[l],c=a[l-1],Br(s.edge,c.site,s.site,u);c=a[0],s=a[f-1],s.edge=Xr(c.site,s.site,null,u),Or(c),Or(s)}function Rr(n){for(var t,e,r,u,i=n.x,o=n.y,a=Vc._;a;)if(r=Dr(a,o)-i,r>ka)a=a.L;else{if(u=i-Pr(a,o),!(u>ka)){r>-ka?(t=a.P,e=a):u>-ka?(t=a,e=a.N):t=e=a;break}if(!a.R){t=a;break}a=a.R}var c=Lr(n);if(Vc.insert(t,c),t||e){if(t===e)return Yr(t),e=Lr(t.site),Vc.insert(c,e),c.edge=e.edge=Xr(t.site,c.site),Or(t),Or(e),void 0;if(!e)return c.edge=Xr(t.site,c.site),void 0;Yr(t),Yr(e);var s=t.site,l=s.x,f=s.y,h=n.x-l,g=n.y-f,p=e.site,v=p.x-l,d=p.y-f,m=2*(h*d-g*v),y=h*h+g*g,x=v*v+d*d,M={x:(d*y-g*x)/m+l,y:(h*x-v*y)/m+f};Br(e.edge,s,p,M),c.edge=Xr(s,n,null,M),e.edge=Xr(n,p,null,M),Or(t),Or(e)}}function Dr(n,t){var e=n.site,r=e.x,u=e.y,i=u-t;if(!i)return r;var o=n.P;if(!o)return-1/0;e=o.site;var a=e.x,c=e.y,s=c-t;if(!s)return a;var l=a-r,f=1/i-1/s,h=l/s;return f?(-h+Math.sqrt(h*h-2*f*(l*l/(-2*s)-c+s/2+u-i/2)))/f+r:(r+a)/2}function Pr(n,t){var e=n.N;if(e)return Dr(e,t);var r=n.site;return r.y===t?r.x:1/0}function Ur(n){this.site=n,this.edges=[]}function jr(n){for(var t,e,r,u,i,o,a,c,s,l,f=n[0][0],h=n[1][0],g=n[0][1],p=n[1][1],v=Zc,d=v.length;d--;)if(i=v[d],i&&i.prepare())for(a=i.edges,c=a.length,o=0;c>o;)l=a[o].end(),r=l.x,u=l.y,s=a[++o%c].start(),t=s.x,e=s.y,(ua(r-t)>ka||ua(u-e)>ka)&&(a.splice(o,0,new Wr($r(i.site,l,ua(r-f)<ka&&p-u>ka?{x:f,y:ua(t-f)<ka?e:p}:ua(u-p)<ka&&h-r>ka?{x:ua(e-p)<ka?t:h,y:p}:ua(r-h)<ka&&u-g>ka?{x:h,y:ua(t-h)<ka?e:g}:ua(u-g)<ka&&r-f>ka?{x:ua(e-g)<ka?t:f,y:g}:null),i.site,null)),++c)}function Hr(n,t){return t.angle-n.angle}function Fr(){Gr(this),this.x=this.y=this.arc=this.site=this.cy=null}function Or(n){var t=n.P,e=n.N;if(t&&e){var r=t.site,u=n.site,i=e.site;if(r!==i){var o=u.x,a=u.y,c=r.x-o,s=r.y-a,l=i.x-o,f=i.y-a,h=2*(c*f-s*l);if(!(h>=-Ea)){var g=c*c+s*s,p=l*l+f*f,v=(f*g-s*p)/h,d=(c*p-l*g)/h,f=d+a,m=Wc.pop()||new Fr;m.arc=n,m.site=u,m.x=v+o,m.y=f+Math.sqrt(v*v+d*d),m.cy=f,n.circle=m;for(var y=null,x=$c._;x;)if(m.y<x.y||m.y===x.y&&m.x<=x.x){if(!x.L){y=x.P;break}x=x.L}else{if(!x.R){y=x;break}x=x.R}$c.insert(y,m),y||(Xc=m)}}}}function Yr(n){var t=n.circle;t&&(t.P||(Xc=t.N),$c.remove(t),Wc.push(t),Gr(t),n.circle=null)}function Ir(n){for(var t,e=Ic,r=Pe(n[0][0],n[0][1],n[1][0],n[1][1]),u=e.length;u--;)t=e[u],(!Zr(t,n)||!r(t)||ua(t.a.x-t.b.x)<ka&&ua(t.a.y-t.b.y)<ka)&&(t.a=t.b=null,e.splice(u,1))}function Zr(n,t){var e=n.b;if(e)return!0;var r,u,i=n.a,o=t[0][0],a=t[1][0],c=t[0][1],s=t[1][1],l=n.l,f=n.r,h=l.x,g=l.y,p=f.x,v=f.y,d=(h+p)/2,m=(g+v)/2;if(v===g){if(o>d||d>=a)return;if(h>p){if(i){if(i.y>=s)return}else i={x:d,y:c};e={x:d,y:s}}else{if(i){if(i.y<c)return}else i={x:d,y:s};e={x:d,y:c}}}else if(r=(h-p)/(v-g),u=m-r*d,-1>r||r>1)if(h>p){if(i){if(i.y>=s)return}else i={x:(c-u)/r,y:c};e={x:(s-u)/r,y:s}}else{if(i){if(i.y<c)return}else i={x:(s-u)/r,y:s};e={x:(c-u)/r,y:c}}else if(v>g){if(i){if(i.x>=a)return}else i={x:o,y:r*o+u};e={x:a,y:r*a+u}}else{if(i){if(i.x<o)return}else i={x:a,y:r*a+u};e={x:o,y:r*o+u}}return n.a=i,n.b=e,!0}function Vr(n,t){this.l=n,this.r=t,this.a=this.b=null}function Xr(n,t,e,r){var u=new Vr(n,t);return Ic.push(u),e&&Br(u,n,t,e),r&&Br(u,t,n,r),Zc[n.i].edges.push(new Wr(u,n,t)),Zc[t.i].edges.push(new Wr(u,t,n)),u}function $r(n,t,e){var r=new Vr(n,null);return r.a=t,r.b=e,Ic.push(r),r}function Br(n,t,e,r){n.a||n.b?n.l===e?n.b=r:n.a=r:(n.a=r,n.l=t,n.r=e)}function Wr(n,t,e){var r=n.a,u=n.b;this.edge=n,this.site=t,this.angle=e?Math.atan2(e.y-t.y,e.x-t.x):n.l===t?Math.atan2(u.x-r.x,r.y-u.y):Math.atan2(r.x-u.x,u.y-r.y)}function Jr(){this._=null}function Gr(n){n.U=n.C=n.L=n.R=n.P=n.N=null}function Kr(n,t){var e=t,r=t.R,u=e.U;u?u.L===e?u.L=r:u.R=r:n._=r,r.U=u,e.U=r,e.R=r.L,e.R&&(e.R.U=e),r.L=e}function Qr(n,t){var e=t,r=t.L,u=e.U;u?u.L===e?u.L=r:u.R=r:n._=r,r.U=u,e.U=r,e.L=r.R,e.L&&(e.L.U=e),r.R=e}function nu(n){for(;n.L;)n=n.L;return n}function tu(n,t){var e,r,u,i=n.sort(eu).pop();for(Ic=[],Zc=new Array(n.length),Vc=new Jr,$c=new Jr;;)if(u=Xc,i&&(!u||i.y<u.y||i.y===u.y&&i.x<u.x))(i.x!==e||i.y!==r)&&(Zc[i.i]=new Ur(i),Rr(i),e=i.x,r=i.y),i=n.pop();else{if(!u)break;qr(u.arc)}t&&(Ir(t),jr(t));var o={cells:Zc,edges:Ic};return Vc=$c=Ic=Zc=null,o}function eu(n,t){return t.y-n.y||t.x-n.x}function ru(n,t,e){return(n.x-e.x)*(t.y-n.y)-(n.x-t.x)*(e.y-n.y)}function uu(n){return n.x}function iu(n){return n.y}function ou(){return{leaf:!0,nodes:[],point:null,x:null,y:null}}function au(n,t,e,r,u,i){if(!n(t,e,r,u,i)){var o=.5*(e+u),a=.5*(r+i),c=t.nodes;c[0]&&au(n,c[0],e,r,o,a),c[1]&&au(n,c[1],o,r,u,a),c[2]&&au(n,c[2],e,a,o,i),c[3]&&au(n,c[3],o,a,u,i)}}function cu(n,t){n=Zo.rgb(n),t=Zo.rgb(t);var e=n.r,r=n.g,u=n.b,i=t.r-e,o=t.g-r,a=t.b-u;return function(n){return"#"+dt(Math.round(e+i*n))+dt(Math.round(r+o*n))+dt(Math.round(u+a*n))}}function su(n,t){var e,r={},u={};for(e in n)e in t?r[e]=hu(n[e],t[e]):u[e]=n[e];for(e in t)e in n||(u[e]=t[e]);return function(n){for(e in r)u[e]=r[e](n);return u}}function lu(n,t){return t-=n=+n,function(e){return n+t*e}}function fu(n,t){var e,r,u,i=Gc.lastIndex=Kc.lastIndex=0,o=-1,a=[],c=[];for(n+="",t+="";(e=Gc.exec(n))&&(r=Kc.exec(t));)(u=r.index)>i&&(u=t.substring(i,u),a[o]?a[o]+=u:a[++o]=u),(e=e[0])===(r=r[0])?a[o]?a[o]+=r:a[++o]=r:(a[++o]=null,c.push({i:o,x:lu(e,r)})),i=Kc.lastIndex;return i<t.length&&(u=t.substring(i),a[o]?a[o]+=u:a[++o]=u),a.length<2?c[0]?(t=c[0].x,function(n){return t(n)+""}):function(){return t}:(t=c.length,function(n){for(var e,r=0;t>r;++r)a[(e=c[r]).i]=e.x(n);return a.join("")})}function hu(n,t){for(var e,r=Zo.interpolators.length;--r>=0&&!(e=Zo.interpolators[r](n,t)););return e}function gu(n,t){var e,r=[],u=[],i=n.length,o=t.length,a=Math.min(n.length,t.length);for(e=0;a>e;++e)r.push(hu(n[e],t[e]));for(;i>e;++e)u[e]=n[e];for(;o>e;++e)u[e]=t[e];return function(n){for(e=0;a>e;++e)u[e]=r[e](n);return u}}function pu(n){return function(t){return 0>=t?0:t>=1?1:n(t)}}function vu(n){return function(t){return 1-n(1-t)}}function du(n){return function(t){return.5*(.5>t?n(2*t):2-n(2-2*t))}}function mu(n){return n*n}function yu(n){return n*n*n}function xu(n){if(0>=n)return 0;if(n>=1)return 1;var t=n*n,e=t*n;return 4*(.5>n?e:3*(n-t)+e-.75)}function Mu(n){return function(t){return Math.pow(t,n)}}function _u(n){return 1-Math.cos(n*Sa)}function bu(n){return Math.pow(2,10*(n-1))}function wu(n){return 1-Math.sqrt(1-n*n)}function Su(n,t){var e;return arguments.length<2&&(t=.45),arguments.length?e=t/wa*Math.asin(1/n):(n=1,e=t/4),function(r){return 1+n*Math.pow(2,-10*r)*Math.sin((r-e)*wa/t)}}function ku(n){return n||(n=1.70158),function(t){return t*t*((n+1)*t-n)}}function Eu(n){return 1/2.75>n?7.5625*n*n:2/2.75>n?7.5625*(n-=1.5/2.75)*n+.75:2.5/2.75>n?7.5625*(n-=2.25/2.75)*n+.9375:7.5625*(n-=2.625/2.75)*n+.984375}function Au(n,t){n=Zo.hcl(n),t=Zo.hcl(t);var e=n.h,r=n.c,u=n.l,i=t.h-e,o=t.c-r,a=t.l-u;return isNaN(o)&&(o=0,r=isNaN(r)?t.c:r),isNaN(i)?(i=0,e=isNaN(e)?t.h:e):i>180?i-=360:-180>i&&(i+=360),function(n){return ot(e+i*n,r+o*n,u+a*n)+""}}function Cu(n,t){n=Zo.hsl(n),t=Zo.hsl(t);var e=n.h,r=n.s,u=n.l,i=t.h-e,o=t.s-r,a=t.l-u;return isNaN(o)&&(o=0,r=isNaN(r)?t.s:r),isNaN(i)?(i=0,e=isNaN(e)?t.h:e):i>180?i-=360:-180>i&&(i+=360),function(n){return ut(e+i*n,r+o*n,u+a*n)+""}}function Nu(n,t){n=Zo.lab(n),t=Zo.lab(t);var e=n.l,r=n.a,u=n.b,i=t.l-e,o=t.a-r,a=t.b-u;return function(n){return ct(e+i*n,r+o*n,u+a*n)+""}}function zu(n,t){return t-=n,function(e){return Math.round(n+t*e)}}function Lu(n){var t=[n.a,n.b],e=[n.c,n.d],r=qu(t),u=Tu(t,e),i=qu(Ru(e,t,-u))||0;t[0]*e[1]<e[0]*t[1]&&(t[0]*=-1,t[1]*=-1,r*=-1,u*=-1),this.rotate=(r?Math.atan2(t[1],t[0]):Math.atan2(-e[0],e[1]))*Ca,this.translate=[n.e,n.f],this.scale=[r,i],this.skew=i?Math.atan2(u,i)*Ca:0}function Tu(n,t){return n[0]*t[0]+n[1]*t[1]}function qu(n){var t=Math.sqrt(Tu(n,n));return t&&(n[0]/=t,n[1]/=t),t}function Ru(n,t,e){return n[0]+=e*t[0],n[1]+=e*t[1],n}function Du(n,t){var e,r=[],u=[],i=Zo.transform(n),o=Zo.transform(t),a=i.translate,c=o.translate,s=i.rotate,l=o.rotate,f=i.skew,h=o.skew,g=i.scale,p=o.scale;return a[0]!=c[0]||a[1]!=c[1]?(r.push("translate(",null,",",null,")"),u.push({i:1,x:lu(a[0],c[0])},{i:3,x:lu(a[1],c[1])})):c[0]||c[1]?r.push("translate("+c+")"):r.push(""),s!=l?(s-l>180?l+=360:l-s>180&&(s+=360),u.push({i:r.push(r.pop()+"rotate(",null,")")-2,x:lu(s,l)})):l&&r.push(r.pop()+"rotate("+l+")"),f!=h?u.push({i:r.push(r.pop()+"skewX(",null,")")-2,x:lu(f,h)}):h&&r.push(r.pop()+"skewX("+h+")"),g[0]!=p[0]||g[1]!=p[1]?(e=r.push(r.pop()+"scale(",null,",",null,")"),u.push({i:e-4,x:lu(g[0],p[0])},{i:e-2,x:lu(g[1],p[1])})):(1!=p[0]||1!=p[1])&&r.push(r.pop()+"scale("+p+")"),e=u.length,function(n){for(var t,i=-1;++i<e;)r[(t=u[i]).i]=t.x(n);return r.join("")}}function Pu(n,t){return t=t-(n=+n)?1/(t-n):0,function(e){return(e-n)*t}}function Uu(n,t){return t=t-(n=+n)?1/(t-n):0,function(e){return Math.max(0,Math.min(1,(e-n)*t))}}function ju(n){for(var t=n.source,e=n.target,r=Fu(t,e),u=[t];t!==r;)t=t.parent,u.push(t);for(var i=u.length;e!==r;)u.splice(i,0,e),e=e.parent;return u}function Hu(n){for(var t=[],e=n.parent;null!=e;)t.push(n),n=e,e=e.parent;return t.push(n),t}function Fu(n,t){if(n===t)return n;for(var e=Hu(n),r=Hu(t),u=e.pop(),i=r.pop(),o=null;u===i;)o=u,u=e.pop(),i=r.pop();return o}function Ou(n){n.fixed|=2}function Yu(n){n.fixed&=-7}function Iu(n){n.fixed|=4,n.px=n.x,n.py=n.y}function Zu(n){n.fixed&=-5}function Vu(n,t,e){var r=0,u=0;if(n.charge=0,!n.leaf)for(var i,o=n.nodes,a=o.length,c=-1;++c<a;)i=o[c],null!=i&&(Vu(i,t,e),n.charge+=i.charge,r+=i.charge*i.cx,u+=i.charge*i.cy);if(n.point){n.leaf||(n.point.x+=Math.random()-.5,n.point.y+=Math.random()-.5);var s=t*e[n.point.index];n.charge+=n.pointCharge=s,r+=s*n.point.x,u+=s*n.point.y}n.cx=r/n.charge,n.cy=u/n.charge}function Xu(n,t){return Zo.rebind(n,t,"sort","children","value"),n.nodes=n,n.links=Ku,n}function $u(n,t){for(var e=[n];null!=(n=e.pop());)if(t(n),(u=n.children)&&(r=u.length))for(var r,u;--r>=0;)e.push(u[r])}function Bu(n,t){for(var e=[n],r=[];null!=(n=e.pop());)if(r.push(n),(i=n.children)&&(u=i.length))for(var u,i,o=-1;++o<u;)e.push(i[o]);for(;null!=(n=r.pop());)t(n)}function Wu(n){return n.children}function Ju(n){return n.value}function Gu(n,t){return t.value-n.value}function Ku(n){return Zo.merge(n.map(function(n){return(n.children||[]).map(function(t){return{source:n,target:t}})}))}function Qu(n){return n.x}function ni(n){return n.y}function ti(n,t,e){n.y0=t,n.y=e}function ei(n){return Zo.range(n.length)}function ri(n){for(var t=-1,e=n[0].length,r=[];++t<e;)r[t]=0;return r}function ui(n){for(var t,e=1,r=0,u=n[0][1],i=n.length;i>e;++e)(t=n[e][1])>u&&(r=e,u=t);return r}function ii(n){return n.reduce(oi,0)}function oi(n,t){return n+t[1]}function ai(n,t){return ci(n,Math.ceil(Math.log(t.length)/Math.LN2+1))}function ci(n,t){for(var e=-1,r=+n[0],u=(n[1]-r)/t,i=[];++e<=t;)i[e]=u*e+r;return i}function si(n){return[Zo.min(n),Zo.max(n)]}function li(n,t){return n.value-t.value}function fi(n,t){var e=n._pack_next;n._pack_next=t,t._pack_prev=n,t._pack_next=e,e._pack_prev=t}function hi(n,t){n._pack_next=t,t._pack_prev=n}function gi(n,t){var e=t.x-n.x,r=t.y-n.y,u=n.r+t.r;return.999*u*u>e*e+r*r}function pi(n){function t(n){l=Math.min(n.x-n.r,l),f=Math.max(n.x+n.r,f),h=Math.min(n.y-n.r,h),g=Math.max(n.y+n.r,g)}if((e=n.children)&&(s=e.length)){var e,r,u,i,o,a,c,s,l=1/0,f=-1/0,h=1/0,g=-1/0;if(e.forEach(vi),r=e[0],r.x=-r.r,r.y=0,t(r),s>1&&(u=e[1],u.x=u.r,u.y=0,t(u),s>2))for(i=e[2],yi(r,u,i),t(i),fi(r,i),r._pack_prev=i,fi(i,u),u=r._pack_next,o=3;s>o;o++){yi(r,u,i=e[o]);var p=0,v=1,d=1;for(a=u._pack_next;a!==u;a=a._pack_next,v++)if(gi(a,i)){p=1;break}if(1==p)for(c=r._pack_prev;c!==a._pack_prev&&!gi(c,i);c=c._pack_prev,d++);p?(d>v||v==d&&u.r<r.r?hi(r,u=a):hi(r=c,u),o--):(fi(r,i),u=i,t(i))}var m=(l+f)/2,y=(h+g)/2,x=0;for(o=0;s>o;o++)i=e[o],i.x-=m,i.y-=y,x=Math.max(x,i.r+Math.sqrt(i.x*i.x+i.y*i.y));n.r=x,e.forEach(di)}}function vi(n){n._pack_next=n._pack_prev=n}function di(n){delete n._pack_next,delete n._pack_prev}function mi(n,t,e,r){var u=n.children;if(n.x=t+=r*n.x,n.y=e+=r*n.y,n.r*=r,u)for(var i=-1,o=u.length;++i<o;)mi(u[i],t,e,r)}function yi(n,t,e){var r=n.r+e.r,u=t.x-n.x,i=t.y-n.y;if(r&&(u||i)){var o=t.r+e.r,a=u*u+i*i;o*=o,r*=r;var c=.5+(r-o)/(2*a),s=Math.sqrt(Math.max(0,2*o*(r+a)-(r-=a)*r-o*o))/(2*a);e.x=n.x+c*u+s*i,e.y=n.y+c*i-s*u}else e.x=n.x+r,e.y=n.y}function xi(n,t){return n.parent==t.parent?1:2}function Mi(n){var t=n.children;return t.length?t[0]:n.t}function _i(n){var t,e=n.children;return(t=e.length)?e[t-1]:n.t}function bi(n,t,e){var r=e/(t.i-n.i);t.c-=r,t.s+=e,n.c+=r,t.z+=e,t.m+=e}function wi(n){for(var t,e=0,r=0,u=n.children,i=u.length;--i>=0;)t=u[i],t.z+=e,t.m+=e,e+=t.s+(r+=t.c)}function Si(n,t,e){return n.a.parent===t.parent?n.a:e}function ki(n){return 1+Zo.max(n,function(n){return n.y})}function Ei(n){return n.reduce(function(n,t){return n+t.x},0)/n.length}function Ai(n){var t=n.children;return t&&t.length?Ai(t[0]):n}function Ci(n){var t,e=n.children;return e&&(t=e.length)?Ci(e[t-1]):n}function Ni(n){return{x:n.x,y:n.y,dx:n.dx,dy:n.dy}}function zi(n,t){var e=n.x+t[3],r=n.y+t[0],u=n.dx-t[1]-t[3],i=n.dy-t[0]-t[2];return 0>u&&(e+=u/2,u=0),0>i&&(r+=i/2,i=0),{x:e,y:r,dx:u,dy:i}}function Li(n){var t=n[0],e=n[n.length-1];return e>t?[t,e]:[e,t]}function Ti(n){return n.rangeExtent?n.rangeExtent():Li(n.range())}function qi(n,t,e,r){var u=e(n[0],n[1]),i=r(t[0],t[1]);return function(n){return i(u(n))}}function Ri(n,t){var e,r=0,u=n.length-1,i=n[r],o=n[u];return i>o&&(e=r,r=u,u=e,e=i,i=o,o=e),n[r]=t.floor(i),n[u]=t.ceil(o),n}function Di(n){return n?{floor:function(t){return Math.floor(t/n)*n},ceil:function(t){return Math.ceil(t/n)*n}}:ss}function Pi(n,t,e,r){var u=[],i=[],o=0,a=Math.min(n.length,t.length)-1;for(n[a]<n[0]&&(n=n.slice().reverse(),t=t.slice().reverse());++o<=a;)u.push(e(n[o-1],n[o])),i.push(r(t[o-1],t[o]));return function(t){var e=Zo.bisect(n,t,1,a)-1;return i[e](u[e](t))}}function Ui(n,t,e,r){function u(){var u=Math.min(n.length,t.length)>2?Pi:qi,c=r?Uu:Pu;return o=u(n,t,c,e),a=u(t,n,c,hu),i}function i(n){return o(n)}var o,a;return i.invert=function(n){return a(n)},i.domain=function(t){return arguments.length?(n=t.map(Number),u()):n},i.range=function(n){return arguments.length?(t=n,u()):t},i.rangeRound=function(n){return i.range(n).interpolate(zu)},i.clamp=function(n){return arguments.length?(r=n,u()):r},i.interpolate=function(n){return arguments.length?(e=n,u()):e},i.ticks=function(t){return Oi(n,t)},i.tickFormat=function(t,e){return Yi(n,t,e)},i.nice=function(t){return Hi(n,t),u()},i.copy=function(){return Ui(n,t,e,r)},u()}function ji(n,t){return Zo.rebind(n,t,"range","rangeRound","interpolate","clamp")}function Hi(n,t){return Ri(n,Di(Fi(n,t)[2]))}function Fi(n,t){null==t&&(t=10);var e=Li(n),r=e[1]-e[0],u=Math.pow(10,Math.floor(Math.log(r/t)/Math.LN10)),i=t/r*u;return.15>=i?u*=10:.35>=i?u*=5:.75>=i&&(u*=2),e[0]=Math.ceil(e[0]/u)*u,e[1]=Math.floor(e[1]/u)*u+.5*u,e[2]=u,e}function Oi(n,t){return Zo.range.apply(Zo,Fi(n,t))}function Yi(n,t,e){var r=Fi(n,t);if(e){var u=Ga.exec(e);if(u.shift(),"s"===u[8]){var i=Zo.formatPrefix(Math.max(ua(r[0]),ua(r[1])));return u[7]||(u[7]="."+Ii(i.scale(r[2]))),u[8]="f",e=Zo.format(u.join("")),function(n){return e(i.scale(n))+i.symbol}}u[7]||(u[7]="."+Zi(u[8],r)),e=u.join("")}else e=",."+Ii(r[2])+"f";return Zo.format(e)}function Ii(n){return-Math.floor(Math.log(n)/Math.LN10+.01)}function Zi(n,t){var e=Ii(t[2]);return n in ls?Math.abs(e-Ii(Math.max(ua(t[0]),ua(t[1]))))+ +("e"!==n):e-2*("%"===n)}function Vi(n,t,e,r){function u(n){return(e?Math.log(0>n?0:n):-Math.log(n>0?0:-n))/Math.log(t)}function i(n){return e?Math.pow(t,n):-Math.pow(t,-n)}function o(t){return n(u(t))}return o.invert=function(t){return i(n.invert(t))},o.domain=function(t){return arguments.length?(e=t[0]>=0,n.domain((r=t.map(Number)).map(u)),o):r},o.base=function(e){return arguments.length?(t=+e,n.domain(r.map(u)),o):t},o.nice=function(){var t=Ri(r.map(u),e?Math:hs);return n.domain(t),r=t.map(i),o},o.ticks=function(){var n=Li(r),o=[],a=n[0],c=n[1],s=Math.floor(u(a)),l=Math.ceil(u(c)),f=t%1?2:t;if(isFinite(l-s)){if(e){for(;l>s;s++)for(var h=1;f>h;h++)o.push(i(s)*h);o.push(i(s))}else for(o.push(i(s));s++<l;)for(var h=f-1;h>0;h--)o.push(i(s)*h);for(s=0;o[s]<a;s++);for(l=o.length;o[l-1]>c;l--);o=o.slice(s,l)}return o},o.tickFormat=function(n,t){if(!arguments.length)return fs;arguments.length<2?t=fs:"function"!=typeof t&&(t=Zo.format(t));var r,a=Math.max(.1,n/o.ticks().length),c=e?(r=1e-12,Math.ceil):(r=-1e-12,Math.floor);return function(n){return n/i(c(u(n)+r))<=a?t(n):""}},o.copy=function(){return Vi(n.copy(),t,e,r)},ji(o,n)}function Xi(n,t,e){function r(t){return n(u(t))}var u=$i(t),i=$i(1/t);return r.invert=function(t){return i(n.invert(t))},r.domain=function(t){return arguments.length?(n.domain((e=t.map(Number)).map(u)),r):e},r.ticks=function(n){return Oi(e,n)},r.tickFormat=function(n,t){return Yi(e,n,t)},r.nice=function(n){return r.domain(Hi(e,n))},r.exponent=function(o){return arguments.length?(u=$i(t=o),i=$i(1/t),n.domain(e.map(u)),r):t},r.copy=function(){return Xi(n.copy(),t,e)},ji(r,n)}function $i(n){return function(t){return 0>t?-Math.pow(-t,n):Math.pow(t,n)}}function Bi(n,t){function e(e){return i[((u.get(e)||("range"===t.t?u.set(e,n.push(e)):0/0))-1)%i.length]}function r(t,e){return Zo.range(n.length).map(function(n){return t+e*n})}var u,i,a;return e.domain=function(r){if(!arguments.length)return n;n=[],u=new o;for(var i,a=-1,c=r.length;++a<c;)u.has(i=r[a])||u.set(i,n.push(i));return e[t.t].apply(e,t.a)},e.range=function(n){return arguments.length?(i=n,a=0,t={t:"range",a:arguments},e):i},e.rangePoints=function(u,o){arguments.length<2&&(o=0);var c=u[0],s=u[1],l=(s-c)/(Math.max(1,n.length-1)+o);return i=r(n.length<2?(c+s)/2:c+l*o/2,l),a=0,t={t:"rangePoints",a:arguments},e},e.rangeBands=function(u,o,c){arguments.length<2&&(o=0),arguments.length<3&&(c=o);var s=u[1]<u[0],l=u[s-0],f=u[1-s],h=(f-l)/(n.length-o+2*c);return i=r(l+h*c,h),s&&i.reverse(),a=h*(1-o),t={t:"rangeBands",a:arguments},e},e.rangeRoundBands=function(u,o,c){arguments.length<2&&(o=0),arguments.length<3&&(c=o);var s=u[1]<u[0],l=u[s-0],f=u[1-s],h=Math.floor((f-l)/(n.length-o+2*c)),g=f-l-(n.length-o)*h;return i=r(l+Math.round(g/2),h),s&&i.reverse(),a=Math.round(h*(1-o)),t={t:"rangeRoundBands",a:arguments},e},e.rangeBand=function(){return a},e.rangeExtent=function(){return Li(t.a[0])},e.copy=function(){return Bi(n,t)},e.domain(n)}function Wi(e,r){function u(){var n=0,t=r.length;for(o=[];++n<t;)o[n-1]=Zo.quantile(e,n/t);return i}function i(n){return isNaN(n=+n)?void 0:r[Zo.bisect(o,n)]}var o;return i.domain=function(r){return arguments.length?(e=r.filter(t).sort(n),u()):e},i.range=function(n){return arguments.length?(r=n,u()):r},i.quantiles=function(){return o},i.invertExtent=function(n){return n=r.indexOf(n),0>n?[0/0,0/0]:[n>0?o[n-1]:e[0],n<o.length?o[n]:e[e.length-1]]},i.copy=function(){return Wi(e,r)},u()}function Ji(n,t,e){function r(t){return e[Math.max(0,Math.min(o,Math.floor(i*(t-n))))]}function u(){return i=e.length/(t-n),o=e.length-1,r}var i,o;return r.domain=function(e){return arguments.length?(n=+e[0],t=+e[e.length-1],u()):[n,t]},r.range=function(n){return arguments.length?(e=n,u()):e},r.invertExtent=function(t){return t=e.indexOf(t),t=0>t?0/0:t/i+n,[t,t+1/i]},r.copy=function(){return Ji(n,t,e)},u()}function Gi(n,t){function e(e){return e>=e?t[Zo.bisect(n,e)]:void 0}return e.domain=function(t){return arguments.length?(n=t,e):n},e.range=function(n){return arguments.length?(t=n,e):t},e.invertExtent=function(e){return e=t.indexOf(e),[n[e-1],n[e]]},e.copy=function(){return Gi(n,t)},e}function Ki(n){function t(n){return+n}return t.invert=t,t.domain=t.range=function(e){return arguments.length?(n=e.map(t),t):n},t.ticks=function(t){return Oi(n,t)},t.tickFormat=function(t,e){return Yi(n,t,e)},t.copy=function(){return Ki(n)},t}function Qi(n){return n.innerRadius}function no(n){return n.outerRadius}function to(n){return n.startAngle}function eo(n){return n.endAngle}function ro(n){function t(t){function o(){s.push("M",i(n(l),a))}for(var c,s=[],l=[],f=-1,h=t.length,g=bt(e),p=bt(r);++f<h;)u.call(this,c=t[f],f)?l.push([+g.call(this,c,f),+p.call(this,c,f)]):l.length&&(o(),l=[]);return l.length&&o(),s.length?s.join(""):null}var e=wr,r=Sr,u=we,i=uo,o=i.key,a=.7;return t.x=function(n){return arguments.length?(e=n,t):e},t.y=function(n){return arguments.length?(r=n,t):r},t.defined=function(n){return arguments.length?(u=n,t):u},t.interpolate=function(n){return arguments.length?(o="function"==typeof n?i=n:(i=xs.get(n)||uo).key,t):o},t.tension=function(n){return arguments.length?(a=n,t):a},t}function uo(n){return n.join("L")}function io(n){return uo(n)+"Z"}function oo(n){for(var t=0,e=n.length,r=n[0],u=[r[0],",",r[1]];++t<e;)u.push("H",(r[0]+(r=n[t])[0])/2,"V",r[1]);return e>1&&u.push("H",r[0]),u.join("")}function ao(n){for(var t=0,e=n.length,r=n[0],u=[r[0],",",r[1]];++t<e;)u.push("V",(r=n[t])[1],"H",r[0]);return u.join("")}function co(n){for(var t=0,e=n.length,r=n[0],u=[r[0],",",r[1]];++t<e;)u.push("H",(r=n[t])[0],"V",r[1]);return u.join("")}function so(n,t){return n.length<4?uo(n):n[1]+ho(n.slice(1,n.length-1),go(n,t))}function lo(n,t){return n.length<3?uo(n):n[0]+ho((n.push(n[0]),n),go([n[n.length-2]].concat(n,[n[1]]),t))}function fo(n,t){return n.length<3?uo(n):n[0]+ho(n,go(n,t))}function ho(n,t){if(t.length<1||n.length!=t.length&&n.length!=t.length+2)return uo(n);var e=n.length!=t.length,r="",u=n[0],i=n[1],o=t[0],a=o,c=1;if(e&&(r+="Q"+(i[0]-2*o[0]/3)+","+(i[1]-2*o[1]/3)+","+i[0]+","+i[1],u=n[1],c=2),t.length>1){a=t[1],i=n[c],c++,r+="C"+(u[0]+o[0])+","+(u[1]+o[1])+","+(i[0]-a[0])+","+(i[1]-a[1])+","+i[0]+","+i[1];for(var s=2;s<t.length;s++,c++)i=n[c],a=t[s],r+="S"+(i[0]-a[0])+","+(i[1]-a[1])+","+i[0]+","+i[1]}if(e){var l=n[c];r+="Q"+(i[0]+2*a[0]/3)+","+(i[1]+2*a[1]/3)+","+l[0]+","+l[1]}return r}function go(n,t){for(var e,r=[],u=(1-t)/2,i=n[0],o=n[1],a=1,c=n.length;++a<c;)e=i,i=o,o=n[a],r.push([u*(o[0]-e[0]),u*(o[1]-e[1])]);return r}function po(n){if(n.length<3)return uo(n);var t=1,e=n.length,r=n[0],u=r[0],i=r[1],o=[u,u,u,(r=n[1])[0]],a=[i,i,i,r[1]],c=[u,",",i,"L",xo(bs,o),",",xo(bs,a)];for(n.push(n[e-1]);++t<=e;)r=n[t],o.shift(),o.push(r[0]),a.shift(),a.push(r[1]),Mo(c,o,a);return n.pop(),c.push("L",r),c.join("")}function vo(n){if(n.length<4)return uo(n);for(var t,e=[],r=-1,u=n.length,i=[0],o=[0];++r<3;)t=n[r],i.push(t[0]),o.push(t[1]);for(e.push(xo(bs,i)+","+xo(bs,o)),--r;++r<u;)t=n[r],i.shift(),i.push(t[0]),o.shift(),o.push(t[1]),Mo(e,i,o);return e.join("")}function mo(n){for(var t,e,r=-1,u=n.length,i=u+4,o=[],a=[];++r<4;)e=n[r%u],o.push(e[0]),a.push(e[1]);for(t=[xo(bs,o),",",xo(bs,a)],--r;++r<i;)e=n[r%u],o.shift(),o.push(e[0]),a.shift(),a.push(e[1]),Mo(t,o,a);return t.join("")}function yo(n,t){var e=n.length-1;if(e)for(var r,u,i=n[0][0],o=n[0][1],a=n[e][0]-i,c=n[e][1]-o,s=-1;++s<=e;)r=n[s],u=s/e,r[0]=t*r[0]+(1-t)*(i+u*a),r[1]=t*r[1]+(1-t)*(o+u*c);return po(n)}function xo(n,t){return n[0]*t[0]+n[1]*t[1]+n[2]*t[2]+n[3]*t[3]}function Mo(n,t,e){n.push("C",xo(Ms,t),",",xo(Ms,e),",",xo(_s,t),",",xo(_s,e),",",xo(bs,t),",",xo(bs,e))}function _o(n,t){return(t[1]-n[1])/(t[0]-n[0])}function bo(n){for(var t=0,e=n.length-1,r=[],u=n[0],i=n[1],o=r[0]=_o(u,i);++t<e;)r[t]=(o+(o=_o(u=i,i=n[t+1])))/2;return r[t]=o,r}function wo(n){for(var t,e,r,u,i=[],o=bo(n),a=-1,c=n.length-1;++a<c;)t=_o(n[a],n[a+1]),ua(t)<ka?o[a]=o[a+1]=0:(e=o[a]/t,r=o[a+1]/t,u=e*e+r*r,u>9&&(u=3*t/Math.sqrt(u),o[a]=u*e,o[a+1]=u*r));for(a=-1;++a<=c;)u=(n[Math.min(c,a+1)][0]-n[Math.max(0,a-1)][0])/(6*(1+o[a]*o[a])),i.push([u||0,o[a]*u||0]);return i}function So(n){return n.length<3?uo(n):n[0]+ho(n,wo(n))}function ko(n){for(var t,e,r,u=-1,i=n.length;++u<i;)t=n[u],e=t[0],r=t[1]+ms,t[0]=e*Math.cos(r),t[1]=e*Math.sin(r);return n}function Eo(n){function t(t){function c(){v.push("M",a(n(m),f),l,s(n(d.reverse()),f),"Z")}for(var h,g,p,v=[],d=[],m=[],y=-1,x=t.length,M=bt(e),_=bt(u),b=e===r?function(){return g}:bt(r),w=u===i?function(){return p}:bt(i);++y<x;)o.call(this,h=t[y],y)?(d.push([g=+M.call(this,h,y),p=+_.call(this,h,y)]),m.push([+b.call(this,h,y),+w.call(this,h,y)])):d.length&&(c(),d=[],m=[]);return d.length&&c(),v.length?v.join(""):null}var e=wr,r=wr,u=0,i=Sr,o=we,a=uo,c=a.key,s=a,l="L",f=.7;return t.x=function(n){return arguments.length?(e=r=n,t):r},t.x0=function(n){return arguments.length?(e=n,t):e},t.x1=function(n){return arguments.length?(r=n,t):r},t.y=function(n){return arguments.length?(u=i=n,t):i},t.y0=function(n){return arguments.length?(u=n,t):u},t.y1=function(n){return arguments.length?(i=n,t):i},t.defined=function(n){return arguments.length?(o=n,t):o},t.interpolate=function(n){return arguments.length?(c="function"==typeof n?a=n:(a=xs.get(n)||uo).key,s=a.reverse||a,l=a.closed?"M":"L",t):c},t.tension=function(n){return arguments.length?(f=n,t):f},t}function Ao(n){return n.radius}function Co(n){return[n.x,n.y]}function No(n){return function(){var t=n.apply(this,arguments),e=t[0],r=t[1]+ms;return[e*Math.cos(r),e*Math.sin(r)]}}function zo(){return 64}function Lo(){return"circle"}function To(n){var t=Math.sqrt(n/ba);return"M0,"+t+"A"+t+","+t+" 0 1,1 0,"+-t+"A"+t+","+t+" 0 1,1 0,"+t+"Z"}function qo(n,t){return sa(n,Cs),n.id=t,n}function Ro(n,t,e,r){var u=n.id;return P(n,"function"==typeof e?function(n,i,o){n.__transition__[u].tween.set(t,r(e.call(n,n.__data__,i,o)))}:(e=r(e),function(n){n.__transition__[u].tween.set(t,e)}))}function Do(n){return null==n&&(n=""),function(){this.textContent=n}}function Po(n,t,e,r){var u=n.__transition__||(n.__transition__={active:0,count:0}),i=u[e];if(!i){var a=r.time;i=u[e]={tween:new o,time:a,ease:r.ease,delay:r.delay,duration:r.duration},++u.count,Zo.timer(function(r){function o(r){return u.active>e?s():(u.active=e,i.event&&i.event.start.call(n,l,t),i.tween.forEach(function(e,r){(r=r.call(n,l,t))&&v.push(r)}),Zo.timer(function(){return p.c=c(r||1)?we:c,1},0,a),void 0)}function c(r){if(u.active!==e)return s();for(var o=r/g,a=f(o),c=v.length;c>0;)v[--c].call(n,a);
return o>=1?(i.event&&i.event.end.call(n,l,t),s()):void 0}function s(){return--u.count?delete u[e]:delete n.__transition__,1}var l=n.__data__,f=i.ease,h=i.delay,g=i.duration,p=Ba,v=[];return p.t=h+a,r>=h?o(r-h):(p.c=o,void 0)},0,a)}}function Uo(n,t){n.attr("transform",function(n){return"translate("+t(n)+",0)"})}function jo(n,t){n.attr("transform",function(n){return"translate(0,"+t(n)+")"})}function Ho(n){return n.toISOString()}function Fo(n,t,e){function r(t){return n(t)}function u(n,e){var r=n[1]-n[0],u=r/e,i=Zo.bisect(Us,u);return i==Us.length?[t.year,Fi(n.map(function(n){return n/31536e6}),e)[2]]:i?t[u/Us[i-1]<Us[i]/u?i-1:i]:[Fs,Fi(n,e)[2]]}return r.invert=function(t){return Oo(n.invert(t))},r.domain=function(t){return arguments.length?(n.domain(t),r):n.domain().map(Oo)},r.nice=function(n,t){function e(e){return!isNaN(e)&&!n.range(e,Oo(+e+1),t).length}var i=r.domain(),o=Li(i),a=null==n?u(o,10):"number"==typeof n&&u(o,n);return a&&(n=a[0],t=a[1]),r.domain(Ri(i,t>1?{floor:function(t){for(;e(t=n.floor(t));)t=Oo(t-1);return t},ceil:function(t){for(;e(t=n.ceil(t));)t=Oo(+t+1);return t}}:n))},r.ticks=function(n,t){var e=Li(r.domain()),i=null==n?u(e,10):"number"==typeof n?u(e,n):!n.range&&[{range:n},t];return i&&(n=i[0],t=i[1]),n.range(e[0],Oo(+e[1]+1),1>t?1:t)},r.tickFormat=function(){return e},r.copy=function(){return Fo(n.copy(),t,e)},ji(r,n)}function Oo(n){return new Date(n)}function Yo(n){return JSON.parse(n.responseText)}function Io(n){var t=$o.createRange();return t.selectNode($o.body),t.createContextualFragment(n.responseText)}var Zo={version:"3.4.11"};Date.now||(Date.now=function(){return+new Date});var Vo=[].slice,Xo=function(n){return Vo.call(n)},$o=document,Bo=$o.documentElement,Wo=window;try{Xo(Bo.childNodes)[0].nodeType}catch(Jo){Xo=function(n){for(var t=n.length,e=new Array(t);t--;)e[t]=n[t];return e}}try{$o.createElement("div").style.setProperty("opacity",0,"")}catch(Go){var Ko=Wo.Element.prototype,Qo=Ko.setAttribute,na=Ko.setAttributeNS,ta=Wo.CSSStyleDeclaration.prototype,ea=ta.setProperty;Ko.setAttribute=function(n,t){Qo.call(this,n,t+"")},Ko.setAttributeNS=function(n,t,e){na.call(this,n,t,e+"")},ta.setProperty=function(n,t,e){ea.call(this,n,t+"",e)}}Zo.ascending=n,Zo.descending=function(n,t){return n>t?-1:t>n?1:t>=n?0:0/0},Zo.min=function(n,t){var e,r,u=-1,i=n.length;if(1===arguments.length){for(;++u<i&&!(null!=(e=n[u])&&e>=e);)e=void 0;for(;++u<i;)null!=(r=n[u])&&e>r&&(e=r)}else{for(;++u<i&&!(null!=(e=t.call(n,n[u],u))&&e>=e);)e=void 0;for(;++u<i;)null!=(r=t.call(n,n[u],u))&&e>r&&(e=r)}return e},Zo.max=function(n,t){var e,r,u=-1,i=n.length;if(1===arguments.length){for(;++u<i&&!(null!=(e=n[u])&&e>=e);)e=void 0;for(;++u<i;)null!=(r=n[u])&&r>e&&(e=r)}else{for(;++u<i&&!(null!=(e=t.call(n,n[u],u))&&e>=e);)e=void 0;for(;++u<i;)null!=(r=t.call(n,n[u],u))&&r>e&&(e=r)}return e},Zo.extent=function(n,t){var e,r,u,i=-1,o=n.length;if(1===arguments.length){for(;++i<o&&!(null!=(e=u=n[i])&&e>=e);)e=u=void 0;for(;++i<o;)null!=(r=n[i])&&(e>r&&(e=r),r>u&&(u=r))}else{for(;++i<o&&!(null!=(e=u=t.call(n,n[i],i))&&e>=e);)e=void 0;for(;++i<o;)null!=(r=t.call(n,n[i],i))&&(e>r&&(e=r),r>u&&(u=r))}return[e,u]},Zo.sum=function(n,t){var e,r=0,u=n.length,i=-1;if(1===arguments.length)for(;++i<u;)isNaN(e=+n[i])||(r+=e);else for(;++i<u;)isNaN(e=+t.call(n,n[i],i))||(r+=e);return r},Zo.mean=function(n,e){var r,u=0,i=n.length,o=-1,a=i;if(1===arguments.length)for(;++o<i;)t(r=n[o])?u+=r:--a;else for(;++o<i;)t(r=e.call(n,n[o],o))?u+=r:--a;return a?u/a:void 0},Zo.quantile=function(n,t){var e=(n.length-1)*t+1,r=Math.floor(e),u=+n[r-1],i=e-r;return i?u+i*(n[r]-u):u},Zo.median=function(e,r){return arguments.length>1&&(e=e.map(r)),e=e.filter(t),e.length?Zo.quantile(e.sort(n),.5):void 0};var ra=e(n);Zo.bisectLeft=ra.left,Zo.bisect=Zo.bisectRight=ra.right,Zo.bisector=function(t){return e(1===t.length?function(e,r){return n(t(e),r)}:t)},Zo.shuffle=function(n){for(var t,e,r=n.length;r;)e=0|Math.random()*r--,t=n[r],n[r]=n[e],n[e]=t;return n},Zo.permute=function(n,t){for(var e=t.length,r=new Array(e);e--;)r[e]=n[t[e]];return r},Zo.pairs=function(n){for(var t,e=0,r=n.length-1,u=n[0],i=new Array(0>r?0:r);r>e;)i[e]=[t=u,u=n[++e]];return i},Zo.zip=function(){if(!(u=arguments.length))return[];for(var n=-1,t=Zo.min(arguments,r),e=new Array(t);++n<t;)for(var u,i=-1,o=e[n]=new Array(u);++i<u;)o[i]=arguments[i][n];return e},Zo.transpose=function(n){return Zo.zip.apply(Zo,n)},Zo.keys=function(n){var t=[];for(var e in n)t.push(e);return t},Zo.values=function(n){var t=[];for(var e in n)t.push(n[e]);return t},Zo.entries=function(n){var t=[];for(var e in n)t.push({key:e,value:n[e]});return t},Zo.merge=function(n){for(var t,e,r,u=n.length,i=-1,o=0;++i<u;)o+=n[i].length;for(e=new Array(o);--u>=0;)for(r=n[u],t=r.length;--t>=0;)e[--o]=r[t];return e};var ua=Math.abs;Zo.range=function(n,t,e){if(arguments.length<3&&(e=1,arguments.length<2&&(t=n,n=0)),1/0===(t-n)/e)throw new Error("infinite range");var r,i=[],o=u(ua(e)),a=-1;if(n*=o,t*=o,e*=o,0>e)for(;(r=n+e*++a)>t;)i.push(r/o);else for(;(r=n+e*++a)<t;)i.push(r/o);return i},Zo.map=function(n){var t=new o;if(n instanceof o)n.forEach(function(n,e){t.set(n,e)});else for(var e in n)t.set(e,n[e]);return t},i(o,{has:a,get:function(n){return this[ia+n]},set:function(n,t){return this[ia+n]=t},remove:c,keys:s,values:function(){var n=[];return this.forEach(function(t,e){n.push(e)}),n},entries:function(){var n=[];return this.forEach(function(t,e){n.push({key:t,value:e})}),n},size:l,empty:f,forEach:function(n){for(var t in this)t.charCodeAt(0)===oa&&n.call(this,t.substring(1),this[t])}});var ia="\x00",oa=ia.charCodeAt(0);Zo.nest=function(){function n(t,a,c){if(c>=i.length)return r?r.call(u,a):e?a.sort(e):a;for(var s,l,f,h,g=-1,p=a.length,v=i[c++],d=new o;++g<p;)(h=d.get(s=v(l=a[g])))?h.push(l):d.set(s,[l]);return t?(l=t(),f=function(e,r){l.set(e,n(t,r,c))}):(l={},f=function(e,r){l[e]=n(t,r,c)}),d.forEach(f),l}function t(n,e){if(e>=i.length)return n;var r=[],u=a[e++];return n.forEach(function(n,u){r.push({key:n,values:t(u,e)})}),u?r.sort(function(n,t){return u(n.key,t.key)}):r}var e,r,u={},i=[],a=[];return u.map=function(t,e){return n(e,t,0)},u.entries=function(e){return t(n(Zo.map,e,0),0)},u.key=function(n){return i.push(n),u},u.sortKeys=function(n){return a[i.length-1]=n,u},u.sortValues=function(n){return e=n,u},u.rollup=function(n){return r=n,u},u},Zo.set=function(n){var t=new h;if(n)for(var e=0,r=n.length;r>e;++e)t.add(n[e]);return t},i(h,{has:a,add:function(n){return this[ia+n]=!0,n},remove:function(n){return n=ia+n,n in this&&delete this[n]},values:s,size:l,empty:f,forEach:function(n){for(var t in this)t.charCodeAt(0)===oa&&n.call(this,t.substring(1))}}),Zo.behavior={},Zo.rebind=function(n,t){for(var e,r=1,u=arguments.length;++r<u;)n[e=arguments[r]]=g(n,t,t[e]);return n};var aa=["webkit","ms","moz","Moz","o","O"];Zo.dispatch=function(){for(var n=new d,t=-1,e=arguments.length;++t<e;)n[arguments[t]]=m(n);return n},d.prototype.on=function(n,t){var e=n.indexOf("."),r="";if(e>=0&&(r=n.substring(e+1),n=n.substring(0,e)),n)return arguments.length<2?this[n].on(r):this[n].on(r,t);if(2===arguments.length){if(null==t)for(n in this)this.hasOwnProperty(n)&&this[n].on(r,null);return this}},Zo.event=null,Zo.requote=function(n){return n.replace(ca,"\\$&")};var ca=/[\\\^\$\*\+\?\|\[\]\(\)\.\{\}]/g,sa={}.__proto__?function(n,t){n.__proto__=t}:function(n,t){for(var e in t)n[e]=t[e]},la=function(n,t){return t.querySelector(n)},fa=function(n,t){return t.querySelectorAll(n)},ha=Bo.matches||Bo[p(Bo,"matchesSelector")],ga=function(n,t){return ha.call(n,t)};"function"==typeof Sizzle&&(la=function(n,t){return Sizzle(n,t)[0]||null},fa=Sizzle,ga=Sizzle.matchesSelector),Zo.selection=function(){return ma};var pa=Zo.selection.prototype=[];pa.select=function(n){var t,e,r,u,i=[];n=b(n);for(var o=-1,a=this.length;++o<a;){i.push(t=[]),t.parentNode=(r=this[o]).parentNode;for(var c=-1,s=r.length;++c<s;)(u=r[c])?(t.push(e=n.call(u,u.__data__,c,o)),e&&"__data__"in u&&(e.__data__=u.__data__)):t.push(null)}return _(i)},pa.selectAll=function(n){var t,e,r=[];n=w(n);for(var u=-1,i=this.length;++u<i;)for(var o=this[u],a=-1,c=o.length;++a<c;)(e=o[a])&&(r.push(t=Xo(n.call(e,e.__data__,a,u))),t.parentNode=e);return _(r)};var va={svg:"http://www.w3.org/2000/svg",xhtml:"http://www.w3.org/1999/xhtml",xlink:"http://www.w3.org/1999/xlink",xml:"http://www.w3.org/XML/1998/namespace",xmlns:"http://www.w3.org/2000/xmlns/"};Zo.ns={prefix:va,qualify:function(n){var t=n.indexOf(":"),e=n;return t>=0&&(e=n.substring(0,t),n=n.substring(t+1)),va.hasOwnProperty(e)?{space:va[e],local:n}:n}},pa.attr=function(n,t){if(arguments.length<2){if("string"==typeof n){var e=this.node();return n=Zo.ns.qualify(n),n.local?e.getAttributeNS(n.space,n.local):e.getAttribute(n)}for(t in n)this.each(S(t,n[t]));return this}return this.each(S(n,t))},pa.classed=function(n,t){if(arguments.length<2){if("string"==typeof n){var e=this.node(),r=(n=A(n)).length,u=-1;if(t=e.classList){for(;++u<r;)if(!t.contains(n[u]))return!1}else for(t=e.getAttribute("class");++u<r;)if(!E(n[u]).test(t))return!1;return!0}for(t in n)this.each(C(t,n[t]));return this}return this.each(C(n,t))},pa.style=function(n,t,e){var r=arguments.length;if(3>r){if("string"!=typeof n){2>r&&(t="");for(e in n)this.each(z(e,n[e],t));return this}if(2>r)return Wo.getComputedStyle(this.node(),null).getPropertyValue(n);e=""}return this.each(z(n,t,e))},pa.property=function(n,t){if(arguments.length<2){if("string"==typeof n)return this.node()[n];for(t in n)this.each(L(t,n[t]));return this}return this.each(L(n,t))},pa.text=function(n){return arguments.length?this.each("function"==typeof n?function(){var t=n.apply(this,arguments);this.textContent=null==t?"":t}:null==n?function(){this.textContent=""}:function(){this.textContent=n}):this.node().textContent},pa.html=function(n){return arguments.length?this.each("function"==typeof n?function(){var t=n.apply(this,arguments);this.innerHTML=null==t?"":t}:null==n?function(){this.innerHTML=""}:function(){this.innerHTML=n}):this.node().innerHTML},pa.append=function(n){return n=T(n),this.select(function(){return this.appendChild(n.apply(this,arguments))})},pa.insert=function(n,t){return n=T(n),t=b(t),this.select(function(){return this.insertBefore(n.apply(this,arguments),t.apply(this,arguments)||null)})},pa.remove=function(){return this.each(function(){var n=this.parentNode;n&&n.removeChild(this)})},pa.data=function(n,t){function e(n,e){var r,u,i,a=n.length,f=e.length,h=Math.min(a,f),g=new Array(f),p=new Array(f),v=new Array(a);if(t){var d,m=new o,y=new o,x=[];for(r=-1;++r<a;)d=t.call(u=n[r],u.__data__,r),m.has(d)?v[r]=u:m.set(d,u),x.push(d);for(r=-1;++r<f;)d=t.call(e,i=e[r],r),(u=m.get(d))?(g[r]=u,u.__data__=i):y.has(d)||(p[r]=q(i)),y.set(d,i),m.remove(d);for(r=-1;++r<a;)m.has(x[r])&&(v[r]=n[r])}else{for(r=-1;++r<h;)u=n[r],i=e[r],u?(u.__data__=i,g[r]=u):p[r]=q(i);for(;f>r;++r)p[r]=q(e[r]);for(;a>r;++r)v[r]=n[r]}p.update=g,p.parentNode=g.parentNode=v.parentNode=n.parentNode,c.push(p),s.push(g),l.push(v)}var r,u,i=-1,a=this.length;if(!arguments.length){for(n=new Array(a=(r=this[0]).length);++i<a;)(u=r[i])&&(n[i]=u.__data__);return n}var c=U([]),s=_([]),l=_([]);if("function"==typeof n)for(;++i<a;)e(r=this[i],n.call(r,r.parentNode.__data__,i));else for(;++i<a;)e(r=this[i],n);return s.enter=function(){return c},s.exit=function(){return l},s},pa.datum=function(n){return arguments.length?this.property("__data__",n):this.property("__data__")},pa.filter=function(n){var t,e,r,u=[];"function"!=typeof n&&(n=R(n));for(var i=0,o=this.length;o>i;i++){u.push(t=[]),t.parentNode=(e=this[i]).parentNode;for(var a=0,c=e.length;c>a;a++)(r=e[a])&&n.call(r,r.__data__,a,i)&&t.push(r)}return _(u)},pa.order=function(){for(var n=-1,t=this.length;++n<t;)for(var e,r=this[n],u=r.length-1,i=r[u];--u>=0;)(e=r[u])&&(i&&i!==e.nextSibling&&i.parentNode.insertBefore(e,i),i=e);return this},pa.sort=function(n){n=D.apply(this,arguments);for(var t=-1,e=this.length;++t<e;)this[t].sort(n);return this.order()},pa.each=function(n){return P(this,function(t,e,r){n.call(t,t.__data__,e,r)})},pa.call=function(n){var t=Xo(arguments);return n.apply(t[0]=this,t),this},pa.empty=function(){return!this.node()},pa.node=function(){for(var n=0,t=this.length;t>n;n++)for(var e=this[n],r=0,u=e.length;u>r;r++){var i=e[r];if(i)return i}return null},pa.size=function(){var n=0;return this.each(function(){++n}),n};var da=[];Zo.selection.enter=U,Zo.selection.enter.prototype=da,da.append=pa.append,da.empty=pa.empty,da.node=pa.node,da.call=pa.call,da.size=pa.size,da.select=function(n){for(var t,e,r,u,i,o=[],a=-1,c=this.length;++a<c;){r=(u=this[a]).update,o.push(t=[]),t.parentNode=u.parentNode;for(var s=-1,l=u.length;++s<l;)(i=u[s])?(t.push(r[s]=e=n.call(u.parentNode,i.__data__,s,a)),e.__data__=i.__data__):t.push(null)}return _(o)},da.insert=function(n,t){return arguments.length<2&&(t=j(this)),pa.insert.call(this,n,t)},pa.transition=function(){for(var n,t,e=Ss||++Ns,r=[],u=ks||{time:Date.now(),ease:xu,delay:0,duration:250},i=-1,o=this.length;++i<o;){r.push(n=[]);for(var a=this[i],c=-1,s=a.length;++c<s;)(t=a[c])&&Po(t,c,e,u),n.push(t)}return qo(r,e)},pa.interrupt=function(){return this.each(H)},Zo.select=function(n){var t=["string"==typeof n?la(n,$o):n];return t.parentNode=Bo,_([t])},Zo.selectAll=function(n){var t=Xo("string"==typeof n?fa(n,$o):n);return t.parentNode=Bo,_([t])};var ma=Zo.select(Bo);pa.on=function(n,t,e){var r=arguments.length;if(3>r){if("string"!=typeof n){2>r&&(t=!1);for(e in n)this.each(F(e,n[e],t));return this}if(2>r)return(r=this.node()["__on"+n])&&r._;e=!1}return this.each(F(n,t,e))};var ya=Zo.map({mouseenter:"mouseover",mouseleave:"mouseout"});ya.forEach(function(n){"on"+n in $o&&ya.remove(n)});var xa="onselectstart"in $o?null:p(Bo.style,"userSelect"),Ma=0;Zo.mouse=function(n){return Z(n,x())};var _a=/WebKit/.test(Wo.navigator.userAgent)?-1:0;Zo.touches=function(n,t){return arguments.length<2&&(t=x().touches),t?Xo(t).map(function(t){var e=Z(n,t);return e.identifier=t.identifier,e}):[]},Zo.behavior.drag=function(){function n(){this.on("mousedown.drag",u).on("touchstart.drag",i)}function t(n,t,u,i,o){return function(){function a(){var n,e,r=t(h,v);r&&(n=r[0]-x[0],e=r[1]-x[1],p|=n|e,x=r,g({type:"drag",x:r[0]+s[0],y:r[1]+s[1],dx:n,dy:e}))}function c(){t(h,v)&&(m.on(i+d,null).on(o+d,null),y(p&&Zo.event.target===f),g({type:"dragend"}))}var s,l=this,f=Zo.event.target,h=l.parentNode,g=e.of(l,arguments),p=0,v=n(),d=".drag"+(null==v?"":"-"+v),m=Zo.select(u()).on(i+d,a).on(o+d,c),y=I(),x=t(h,v);r?(s=r.apply(l,arguments),s=[s.x-x[0],s.y-x[1]]):s=[0,0],g({type:"dragstart"})}}var e=M(n,"drag","dragstart","dragend"),r=null,u=t(v,Zo.mouse,$,"mousemove","mouseup"),i=t(V,Zo.touch,X,"touchmove","touchend");return n.origin=function(t){return arguments.length?(r=t,n):r},Zo.rebind(n,e,"on")};var ba=Math.PI,wa=2*ba,Sa=ba/2,ka=1e-6,Ea=ka*ka,Aa=ba/180,Ca=180/ba,Na=Math.SQRT2,za=2,La=4;Zo.interpolateZoom=function(n,t){function e(n){var t=n*y;if(m){var e=Q(v),o=i/(za*h)*(e*nt(Na*t+v)-K(v));return[r+o*s,u+o*l,i*e/Q(Na*t+v)]}return[r+n*s,u+n*l,i*Math.exp(Na*t)]}var r=n[0],u=n[1],i=n[2],o=t[0],a=t[1],c=t[2],s=o-r,l=a-u,f=s*s+l*l,h=Math.sqrt(f),g=(c*c-i*i+La*f)/(2*i*za*h),p=(c*c-i*i-La*f)/(2*c*za*h),v=Math.log(Math.sqrt(g*g+1)-g),d=Math.log(Math.sqrt(p*p+1)-p),m=d-v,y=(m||Math.log(c/i))/Na;return e.duration=1e3*y,e},Zo.behavior.zoom=function(){function n(n){n.on(A,s).on(Ra+".zoom",f).on("dblclick.zoom",h).on(z,l)}function t(n){return[(n[0]-S.x)/S.k,(n[1]-S.y)/S.k]}function e(n){return[n[0]*S.k+S.x,n[1]*S.k+S.y]}function r(n){S.k=Math.max(E[0],Math.min(E[1],n))}function u(n,t){t=e(t),S.x+=n[0]-t[0],S.y+=n[1]-t[1]}function i(){_&&_.domain(x.range().map(function(n){return(n-S.x)/S.k}).map(x.invert)),w&&w.domain(b.range().map(function(n){return(n-S.y)/S.k}).map(b.invert))}function o(n){n({type:"zoomstart"})}function a(n){i(),n({type:"zoom",scale:S.k,translate:[S.x,S.y]})}function c(n){n({type:"zoomend"})}function s(){function n(){l=1,u(Zo.mouse(r),h),a(s)}function e(){f.on(C,null).on(N,null),g(l&&Zo.event.target===i),c(s)}var r=this,i=Zo.event.target,s=L.of(r,arguments),l=0,f=Zo.select(Wo).on(C,n).on(N,e),h=t(Zo.mouse(r)),g=I();H.call(r),o(s)}function l(){function n(){var n=Zo.touches(g);return h=S.k,n.forEach(function(n){n.identifier in v&&(v[n.identifier]=t(n))}),n}function e(){var t=Zo.event.target;Zo.select(t).on(M,i).on(_,f),b.push(t);for(var e=Zo.event.changedTouches,o=0,c=e.length;c>o;++o)v[e[o].identifier]=null;var s=n(),l=Date.now();if(1===s.length){if(500>l-m){var h=s[0],g=v[h.identifier];r(2*S.k),u(h,g),y(),a(p)}m=l}else if(s.length>1){var h=s[0],x=s[1],w=h[0]-x[0],k=h[1]-x[1];d=w*w+k*k}}function i(){for(var n,t,e,i,o=Zo.touches(g),c=0,s=o.length;s>c;++c,i=null)if(e=o[c],i=v[e.identifier]){if(t)break;n=e,t=i}if(i){var l=(l=e[0]-n[0])*l+(l=e[1]-n[1])*l,f=d&&Math.sqrt(l/d);n=[(n[0]+e[0])/2,(n[1]+e[1])/2],t=[(t[0]+i[0])/2,(t[1]+i[1])/2],r(f*h)}m=null,u(n,t),a(p)}function f(){if(Zo.event.touches.length){for(var t=Zo.event.changedTouches,e=0,r=t.length;r>e;++e)delete v[t[e].identifier];for(var u in v)return void n()}Zo.selectAll(b).on(x,null),w.on(A,s).on(z,l),k(),c(p)}var h,g=this,p=L.of(g,arguments),v={},d=0,x=".zoom-"+Zo.event.changedTouches[0].identifier,M="touchmove"+x,_="touchend"+x,b=[],w=Zo.select(g).on(A,null).on(z,e),k=I();H.call(g),e(),o(p)}function f(){var n=L.of(this,arguments);d?clearTimeout(d):(g=t(p=v||Zo.mouse(this)),H.call(this),o(n)),d=setTimeout(function(){d=null,c(n)},50),y(),r(Math.pow(2,.002*Ta())*S.k),u(p,g),a(n)}function h(){var n=L.of(this,arguments),e=Zo.mouse(this),i=t(e),s=Math.log(S.k)/Math.LN2;o(n),r(Math.pow(2,Zo.event.shiftKey?Math.ceil(s)-1:Math.floor(s)+1)),u(e,i),a(n),c(n)}var g,p,v,d,m,x,_,b,w,S={x:0,y:0,k:1},k=[960,500],E=qa,A="mousedown.zoom",C="mousemove.zoom",N="mouseup.zoom",z="touchstart.zoom",L=M(n,"zoomstart","zoom","zoomend");return n.event=function(n){n.each(function(){var n=L.of(this,arguments),t=S;Ss?Zo.select(this).transition().each("start.zoom",function(){S=this.__chart__||{x:0,y:0,k:1},o(n)}).tween("zoom:zoom",function(){var e=k[0],r=k[1],u=e/2,i=r/2,o=Zo.interpolateZoom([(u-S.x)/S.k,(i-S.y)/S.k,e/S.k],[(u-t.x)/t.k,(i-t.y)/t.k,e/t.k]);return function(t){var r=o(t),c=e/r[2];this.__chart__=S={x:u-r[0]*c,y:i-r[1]*c,k:c},a(n)}}).each("end.zoom",function(){c(n)}):(this.__chart__=S,o(n),a(n),c(n))})},n.translate=function(t){return arguments.length?(S={x:+t[0],y:+t[1],k:S.k},i(),n):[S.x,S.y]},n.scale=function(t){return arguments.length?(S={x:S.x,y:S.y,k:+t},i(),n):S.k},n.scaleExtent=function(t){return arguments.length?(E=null==t?qa:[+t[0],+t[1]],n):E},n.center=function(t){return arguments.length?(v=t&&[+t[0],+t[1]],n):v},n.size=function(t){return arguments.length?(k=t&&[+t[0],+t[1]],n):k},n.x=function(t){return arguments.length?(_=t,x=t.copy(),S={x:0,y:0,k:1},n):_},n.y=function(t){return arguments.length?(w=t,b=t.copy(),S={x:0,y:0,k:1},n):w},Zo.rebind(n,L,"on")};var Ta,qa=[0,1/0],Ra="onwheel"in $o?(Ta=function(){return-Zo.event.deltaY*(Zo.event.deltaMode?120:1)},"wheel"):"onmousewheel"in $o?(Ta=function(){return Zo.event.wheelDelta},"mousewheel"):(Ta=function(){return-Zo.event.detail},"MozMousePixelScroll");Zo.color=et,et.prototype.toString=function(){return this.rgb()+""},Zo.hsl=rt;var Da=rt.prototype=new et;Da.brighter=function(n){return n=Math.pow(.7,arguments.length?n:1),new rt(this.h,this.s,this.l/n)},Da.darker=function(n){return n=Math.pow(.7,arguments.length?n:1),new rt(this.h,this.s,n*this.l)},Da.rgb=function(){return ut(this.h,this.s,this.l)},Zo.hcl=it;var Pa=it.prototype=new et;Pa.brighter=function(n){return new it(this.h,this.c,Math.min(100,this.l+Ua*(arguments.length?n:1)))},Pa.darker=function(n){return new it(this.h,this.c,Math.max(0,this.l-Ua*(arguments.length?n:1)))},Pa.rgb=function(){return ot(this.h,this.c,this.l).rgb()},Zo.lab=at;var Ua=18,ja=.95047,Ha=1,Fa=1.08883,Oa=at.prototype=new et;Oa.brighter=function(n){return new at(Math.min(100,this.l+Ua*(arguments.length?n:1)),this.a,this.b)},Oa.darker=function(n){return new at(Math.max(0,this.l-Ua*(arguments.length?n:1)),this.a,this.b)},Oa.rgb=function(){return ct(this.l,this.a,this.b)},Zo.rgb=gt;var Ya=gt.prototype=new et;Ya.brighter=function(n){n=Math.pow(.7,arguments.length?n:1);var t=this.r,e=this.g,r=this.b,u=30;return t||e||r?(t&&u>t&&(t=u),e&&u>e&&(e=u),r&&u>r&&(r=u),new gt(Math.min(255,t/n),Math.min(255,e/n),Math.min(255,r/n))):new gt(u,u,u)},Ya.darker=function(n){return n=Math.pow(.7,arguments.length?n:1),new gt(n*this.r,n*this.g,n*this.b)},Ya.hsl=function(){return yt(this.r,this.g,this.b)},Ya.toString=function(){return"#"+dt(this.r)+dt(this.g)+dt(this.b)};var Ia=Zo.map({aliceblue:15792383,antiquewhite:16444375,aqua:65535,aquamarine:8388564,azure:15794175,beige:16119260,bisque:16770244,black:0,blanchedalmond:16772045,blue:255,blueviolet:9055202,brown:10824234,burlywood:14596231,cadetblue:6266528,chartreuse:8388352,chocolate:13789470,coral:16744272,cornflowerblue:6591981,cornsilk:16775388,crimson:14423100,cyan:65535,darkblue:139,darkcyan:35723,darkgoldenrod:12092939,darkgray:11119017,darkgreen:25600,darkgrey:11119017,darkkhaki:12433259,darkmagenta:9109643,darkolivegreen:5597999,darkorange:16747520,darkorchid:10040012,darkred:9109504,darksalmon:15308410,darkseagreen:9419919,darkslateblue:4734347,darkslategray:3100495,darkslategrey:3100495,darkturquoise:52945,darkviolet:9699539,deeppink:16716947,deepskyblue:49151,dimgray:6908265,dimgrey:6908265,dodgerblue:2003199,firebrick:11674146,floralwhite:16775920,forestgreen:2263842,fuchsia:16711935,gainsboro:14474460,ghostwhite:16316671,gold:16766720,goldenrod:14329120,gray:8421504,green:32768,greenyellow:11403055,grey:8421504,honeydew:15794160,hotpink:16738740,indianred:13458524,indigo:4915330,ivory:16777200,khaki:15787660,lavender:15132410,lavenderblush:16773365,lawngreen:8190976,lemonchiffon:16775885,lightblue:11393254,lightcoral:15761536,lightcyan:14745599,lightgoldenrodyellow:16448210,lightgray:13882323,lightgreen:9498256,lightgrey:13882323,lightpink:16758465,lightsalmon:16752762,lightseagreen:2142890,lightskyblue:8900346,lightslategray:7833753,lightslategrey:7833753,lightsteelblue:11584734,lightyellow:16777184,lime:65280,limegreen:3329330,linen:16445670,magenta:16711935,maroon:8388608,mediumaquamarine:6737322,mediumblue:205,mediumorchid:12211667,mediumpurple:9662683,mediumseagreen:3978097,mediumslateblue:8087790,mediumspringgreen:64154,mediumturquoise:4772300,mediumvioletred:13047173,midnightblue:1644912,mintcream:16121850,mistyrose:16770273,moccasin:16770229,navajowhite:16768685,navy:128,oldlace:16643558,olive:8421376,olivedrab:7048739,orange:16753920,orangered:16729344,orchid:14315734,palegoldenrod:15657130,palegreen:10025880,paleturquoise:11529966,palevioletred:14381203,papayawhip:16773077,peachpuff:16767673,peru:13468991,pink:16761035,plum:14524637,powderblue:11591910,purple:8388736,red:16711680,rosybrown:12357519,royalblue:4286945,saddlebrown:9127187,salmon:16416882,sandybrown:16032864,seagreen:3050327,seashell:16774638,sienna:10506797,silver:12632256,skyblue:8900331,slateblue:6970061,slategray:7372944,slategrey:7372944,snow:16775930,springgreen:65407,steelblue:4620980,tan:13808780,teal:32896,thistle:14204888,tomato:16737095,turquoise:4251856,violet:15631086,wheat:16113331,white:16777215,whitesmoke:16119285,yellow:16776960,yellowgreen:10145074});Ia.forEach(function(n,t){Ia.set(n,pt(t))}),Zo.functor=bt,Zo.xhr=St(wt),Zo.dsv=function(n,t){function e(n,e,i){arguments.length<3&&(i=e,e=null);var o=kt(n,t,null==e?r:u(e),i);return o.row=function(n){return arguments.length?o.response(null==(e=n)?r:u(n)):e},o}function r(n){return e.parse(n.responseText)}function u(n){return function(t){return e.parse(t.responseText,n)}}function i(t){return t.map(o).join(n)}function o(n){return a.test(n)?'"'+n.replace(/\"/g,'""')+'"':n}var a=new RegExp('["'+n+"\n]"),c=n.charCodeAt(0);return e.parse=function(n,t){var r;return e.parseRows(n,function(n,e){if(r)return r(n,e-1);var u=new Function("d","return {"+n.map(function(n,t){return JSON.stringify(n)+": d["+t+"]"}).join(",")+"}");r=t?function(n,e){return t(u(n),e)}:u})},e.parseRows=function(n,t){function e(){if(l>=s)return o;if(u)return u=!1,i;var t=l;if(34===n.charCodeAt(t)){for(var e=t;e++<s;)if(34===n.charCodeAt(e)){if(34!==n.charCodeAt(e+1))break;++e}l=e+2;var r=n.charCodeAt(e+1);return 13===r?(u=!0,10===n.charCodeAt(e+2)&&++l):10===r&&(u=!0),n.substring(t+1,e).replace(/""/g,'"')}for(;s>l;){var r=n.charCodeAt(l++),a=1;if(10===r)u=!0;else if(13===r)u=!0,10===n.charCodeAt(l)&&(++l,++a);else if(r!==c)continue;return n.substring(t,l-a)}return n.substring(t)}for(var r,u,i={},o={},a=[],s=n.length,l=0,f=0;(r=e())!==o;){for(var h=[];r!==i&&r!==o;)h.push(r),r=e();(!t||(h=t(h,f++)))&&a.push(h)}return a},e.format=function(t){if(Array.isArray(t[0]))return e.formatRows(t);var r=new h,u=[];return t.forEach(function(n){for(var t in n)r.has(t)||u.push(r.add(t))}),[u.map(o).join(n)].concat(t.map(function(t){return u.map(function(n){return o(t[n])}).join(n)})).join("\n")},e.formatRows=function(n){return n.map(i).join("\n")},e},Zo.csv=Zo.dsv(",","text/csv"),Zo.tsv=Zo.dsv("	","text/tab-separated-values"),Zo.touch=function(n,t,e){if(arguments.length<3&&(e=t,t=x().changedTouches),t)for(var r,u=0,i=t.length;i>u;++u)if((r=t[u]).identifier===e)return Z(n,r)};var Za,Va,Xa,$a,Ba,Wa=Wo[p(Wo,"requestAnimationFrame")]||function(n){setTimeout(n,17)};Zo.timer=function(n,t,e){var r=arguments.length;2>r&&(t=0),3>r&&(e=Date.now());var u=e+t,i={c:n,t:u,f:!1,n:null};Va?Va.n=i:Za=i,Va=i,Xa||($a=clearTimeout($a),Xa=1,Wa(At))},Zo.timer.flush=function(){Ct(),Nt()},Zo.round=function(n,t){return t?Math.round(n*(t=Math.pow(10,t)))/t:Math.round(n)};var Ja=["y","z","a","f","p","n","\xb5","m","","k","M","G","T","P","E","Z","Y"].map(Lt);Zo.formatPrefix=function(n,t){var e=0;return n&&(0>n&&(n*=-1),t&&(n=Zo.round(n,zt(n,t))),e=1+Math.floor(1e-12+Math.log(n)/Math.LN10),e=Math.max(-24,Math.min(24,3*Math.floor((e-1)/3)))),Ja[8+e/3]};var Ga=/(?:([^{])?([<>=^]))?([+\- ])?([$#])?(0)?(\d+)?(,)?(\.-?\d+)?([a-z%])?/i,Ka=Zo.map({b:function(n){return n.toString(2)},c:function(n){return String.fromCharCode(n)},o:function(n){return n.toString(8)},x:function(n){return n.toString(16)},X:function(n){return n.toString(16).toUpperCase()},g:function(n,t){return n.toPrecision(t)},e:function(n,t){return n.toExponential(t)},f:function(n,t){return n.toFixed(t)},r:function(n,t){return(n=Zo.round(n,zt(n,t))).toFixed(Math.max(0,Math.min(20,zt(n*(1+1e-15),t))))}}),Qa=Zo.time={},nc=Date;Rt.prototype={getDate:function(){return this._.getUTCDate()},getDay:function(){return this._.getUTCDay()},getFullYear:function(){return this._.getUTCFullYear()},getHours:function(){return this._.getUTCHours()},getMilliseconds:function(){return this._.getUTCMilliseconds()},getMinutes:function(){return this._.getUTCMinutes()},getMonth:function(){return this._.getUTCMonth()},getSeconds:function(){return this._.getUTCSeconds()},getTime:function(){return this._.getTime()},getTimezoneOffset:function(){return 0},valueOf:function(){return this._.valueOf()},setDate:function(){tc.setUTCDate.apply(this._,arguments)},setDay:function(){tc.setUTCDay.apply(this._,arguments)},setFullYear:function(){tc.setUTCFullYear.apply(this._,arguments)},setHours:function(){tc.setUTCHours.apply(this._,arguments)},setMilliseconds:function(){tc.setUTCMilliseconds.apply(this._,arguments)},setMinutes:function(){tc.setUTCMinutes.apply(this._,arguments)},setMonth:function(){tc.setUTCMonth.apply(this._,arguments)},setSeconds:function(){tc.setUTCSeconds.apply(this._,arguments)},setTime:function(){tc.setTime.apply(this._,arguments)}};var tc=Date.prototype;Qa.year=Dt(function(n){return n=Qa.day(n),n.setMonth(0,1),n},function(n,t){n.setFullYear(n.getFullYear()+t)},function(n){return n.getFullYear()}),Qa.years=Qa.year.range,Qa.years.utc=Qa.year.utc.range,Qa.day=Dt(function(n){var t=new nc(2e3,0);return t.setFullYear(n.getFullYear(),n.getMonth(),n.getDate()),t},function(n,t){n.setDate(n.getDate()+t)},function(n){return n.getDate()-1}),Qa.days=Qa.day.range,Qa.days.utc=Qa.day.utc.range,Qa.dayOfYear=function(n){var t=Qa.year(n);return Math.floor((n-t-6e4*(n.getTimezoneOffset()-t.getTimezoneOffset()))/864e5)},["sunday","monday","tuesday","wednesday","thursday","friday","saturday"].forEach(function(n,t){t=7-t;var e=Qa[n]=Dt(function(n){return(n=Qa.day(n)).setDate(n.getDate()-(n.getDay()+t)%7),n},function(n,t){n.setDate(n.getDate()+7*Math.floor(t))},function(n){var e=Qa.year(n).getDay();return Math.floor((Qa.dayOfYear(n)+(e+t)%7)/7)-(e!==t)});Qa[n+"s"]=e.range,Qa[n+"s"].utc=e.utc.range,Qa[n+"OfYear"]=function(n){var e=Qa.year(n).getDay();return Math.floor((Qa.dayOfYear(n)+(e+t)%7)/7)}}),Qa.week=Qa.sunday,Qa.weeks=Qa.sunday.range,Qa.weeks.utc=Qa.sunday.utc.range,Qa.weekOfYear=Qa.sundayOfYear;var ec={"-":"",_:" ",0:"0"},rc=/^\s*\d+/,uc=/^%/;Zo.locale=function(n){return{numberFormat:Tt(n),timeFormat:Ut(n)}};var ic=Zo.locale({decimal:".",thousands:",",grouping:[3],currency:["$",""],dateTime:"%a %b %e %X %Y",date:"%m/%d/%Y",time:"%H:%M:%S",periods:["AM","PM"],days:["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"],shortDays:["Sun","Mon","Tue","Wed","Thu","Fri","Sat"],months:["January","February","March","April","May","June","July","August","September","October","November","December"],shortMonths:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"]});Zo.format=ic.numberFormat,Zo.geo={},ue.prototype={s:0,t:0,add:function(n){ie(n,this.t,oc),ie(oc.s,this.s,this),this.s?this.t+=oc.t:this.s=oc.t},reset:function(){this.s=this.t=0},valueOf:function(){return this.s}};var oc=new ue;Zo.geo.stream=function(n,t){n&&ac.hasOwnProperty(n.type)?ac[n.type](n,t):oe(n,t)};var ac={Feature:function(n,t){oe(n.geometry,t)},FeatureCollection:function(n,t){for(var e=n.features,r=-1,u=e.length;++r<u;)oe(e[r].geometry,t)}},cc={Sphere:function(n,t){t.sphere()},Point:function(n,t){n=n.coordinates,t.point(n[0],n[1],n[2])},MultiPoint:function(n,t){for(var e=n.coordinates,r=-1,u=e.length;++r<u;)n=e[r],t.point(n[0],n[1],n[2])},LineString:function(n,t){ae(n.coordinates,t,0)},MultiLineString:function(n,t){for(var e=n.coordinates,r=-1,u=e.length;++r<u;)ae(e[r],t,0)},Polygon:function(n,t){ce(n.coordinates,t)},MultiPolygon:function(n,t){for(var e=n.coordinates,r=-1,u=e.length;++r<u;)ce(e[r],t)},GeometryCollection:function(n,t){for(var e=n.geometries,r=-1,u=e.length;++r<u;)oe(e[r],t)}};Zo.geo.area=function(n){return sc=0,Zo.geo.stream(n,fc),sc};var sc,lc=new ue,fc={sphere:function(){sc+=4*ba},point:v,lineStart:v,lineEnd:v,polygonStart:function(){lc.reset(),fc.lineStart=se},polygonEnd:function(){var n=2*lc;sc+=0>n?4*ba+n:n,fc.lineStart=fc.lineEnd=fc.point=v}};Zo.geo.bounds=function(){function n(n,t){x.push(M=[l=n,h=n]),f>t&&(f=t),t>g&&(g=t)}function t(t,e){var r=le([t*Aa,e*Aa]);if(m){var u=he(m,r),i=[u[1],-u[0],0],o=he(i,u);ve(o),o=de(o);var c=t-p,s=c>0?1:-1,v=o[0]*Ca*s,d=ua(c)>180;if(d^(v>s*p&&s*t>v)){var y=o[1]*Ca;y>g&&(g=y)}else if(v=(v+360)%360-180,d^(v>s*p&&s*t>v)){var y=-o[1]*Ca;f>y&&(f=y)}else f>e&&(f=e),e>g&&(g=e);d?p>t?a(l,t)>a(l,h)&&(h=t):a(t,h)>a(l,h)&&(l=t):h>=l?(l>t&&(l=t),t>h&&(h=t)):t>p?a(l,t)>a(l,h)&&(h=t):a(t,h)>a(l,h)&&(l=t)}else n(t,e);m=r,p=t}function e(){_.point=t}function r(){M[0]=l,M[1]=h,_.point=n,m=null}function u(n,e){if(m){var r=n-p;y+=ua(r)>180?r+(r>0?360:-360):r}else v=n,d=e;fc.point(n,e),t(n,e)}function i(){fc.lineStart()}function o(){u(v,d),fc.lineEnd(),ua(y)>ka&&(l=-(h=180)),M[0]=l,M[1]=h,m=null}function a(n,t){return(t-=n)<0?t+360:t}function c(n,t){return n[0]-t[0]}function s(n,t){return t[0]<=t[1]?t[0]<=n&&n<=t[1]:n<t[0]||t[1]<n}var l,f,h,g,p,v,d,m,y,x,M,_={point:n,lineStart:e,lineEnd:r,polygonStart:function(){_.point=u,_.lineStart=i,_.lineEnd=o,y=0,fc.polygonStart()},polygonEnd:function(){fc.polygonEnd(),_.point=n,_.lineStart=e,_.lineEnd=r,0>lc?(l=-(h=180),f=-(g=90)):y>ka?g=90:-ka>y&&(f=-90),M[0]=l,M[1]=h}};return function(n){g=h=-(l=f=1/0),x=[],Zo.geo.stream(n,_);var t=x.length;if(t){x.sort(c);for(var e,r=1,u=x[0],i=[u];t>r;++r)e=x[r],s(e[0],u)||s(e[1],u)?(a(u[0],e[1])>a(u[0],u[1])&&(u[1]=e[1]),a(e[0],u[1])>a(u[0],u[1])&&(u[0]=e[0])):i.push(u=e);
for(var o,e,p=-1/0,t=i.length-1,r=0,u=i[t];t>=r;u=e,++r)e=i[r],(o=a(u[1],e[0]))>p&&(p=o,l=e[0],h=u[1])}return x=M=null,1/0===l||1/0===f?[[0/0,0/0],[0/0,0/0]]:[[l,f],[h,g]]}}(),Zo.geo.centroid=function(n){hc=gc=pc=vc=dc=mc=yc=xc=Mc=_c=bc=0,Zo.geo.stream(n,wc);var t=Mc,e=_c,r=bc,u=t*t+e*e+r*r;return Ea>u&&(t=mc,e=yc,r=xc,ka>gc&&(t=pc,e=vc,r=dc),u=t*t+e*e+r*r,Ea>u)?[0/0,0/0]:[Math.atan2(e,t)*Ca,G(r/Math.sqrt(u))*Ca]};var hc,gc,pc,vc,dc,mc,yc,xc,Mc,_c,bc,wc={sphere:v,point:ye,lineStart:Me,lineEnd:_e,polygonStart:function(){wc.lineStart=be},polygonEnd:function(){wc.lineStart=Me}},Sc=Ae(we,Te,Re,[-ba,-ba/2]),kc=1e9;Zo.geo.clipExtent=function(){var n,t,e,r,u,i,o={stream:function(n){return u&&(u.valid=!1),u=i(n),u.valid=!0,u},extent:function(a){return arguments.length?(i=Ue(n=+a[0][0],t=+a[0][1],e=+a[1][0],r=+a[1][1]),u&&(u.valid=!1,u=null),o):[[n,t],[e,r]]}};return o.extent([[0,0],[960,500]])},(Zo.geo.conicEqualArea=function(){return He(Fe)}).raw=Fe,Zo.geo.albers=function(){return Zo.geo.conicEqualArea().rotate([96,0]).center([-.6,38.7]).parallels([29.5,45.5]).scale(1070)},Zo.geo.albersUsa=function(){function n(n){var i=n[0],o=n[1];return t=null,e(i,o),t||(r(i,o),t)||u(i,o),t}var t,e,r,u,i=Zo.geo.albers(),o=Zo.geo.conicEqualArea().rotate([154,0]).center([-2,58.5]).parallels([55,65]),a=Zo.geo.conicEqualArea().rotate([157,0]).center([-3,19.9]).parallels([8,18]),c={point:function(n,e){t=[n,e]}};return n.invert=function(n){var t=i.scale(),e=i.translate(),r=(n[0]-e[0])/t,u=(n[1]-e[1])/t;return(u>=.12&&.234>u&&r>=-.425&&-.214>r?o:u>=.166&&.234>u&&r>=-.214&&-.115>r?a:i).invert(n)},n.stream=function(n){var t=i.stream(n),e=o.stream(n),r=a.stream(n);return{point:function(n,u){t.point(n,u),e.point(n,u),r.point(n,u)},sphere:function(){t.sphere(),e.sphere(),r.sphere()},lineStart:function(){t.lineStart(),e.lineStart(),r.lineStart()},lineEnd:function(){t.lineEnd(),e.lineEnd(),r.lineEnd()},polygonStart:function(){t.polygonStart(),e.polygonStart(),r.polygonStart()},polygonEnd:function(){t.polygonEnd(),e.polygonEnd(),r.polygonEnd()}}},n.precision=function(t){return arguments.length?(i.precision(t),o.precision(t),a.precision(t),n):i.precision()},n.scale=function(t){return arguments.length?(i.scale(t),o.scale(.35*t),a.scale(t),n.translate(i.translate())):i.scale()},n.translate=function(t){if(!arguments.length)return i.translate();var s=i.scale(),l=+t[0],f=+t[1];return e=i.translate(t).clipExtent([[l-.455*s,f-.238*s],[l+.455*s,f+.238*s]]).stream(c).point,r=o.translate([l-.307*s,f+.201*s]).clipExtent([[l-.425*s+ka,f+.12*s+ka],[l-.214*s-ka,f+.234*s-ka]]).stream(c).point,u=a.translate([l-.205*s,f+.212*s]).clipExtent([[l-.214*s+ka,f+.166*s+ka],[l-.115*s-ka,f+.234*s-ka]]).stream(c).point,n},n.scale(1070)};var Ec,Ac,Cc,Nc,zc,Lc,Tc={point:v,lineStart:v,lineEnd:v,polygonStart:function(){Ac=0,Tc.lineStart=Oe},polygonEnd:function(){Tc.lineStart=Tc.lineEnd=Tc.point=v,Ec+=ua(Ac/2)}},qc={point:Ye,lineStart:v,lineEnd:v,polygonStart:v,polygonEnd:v},Rc={point:Ve,lineStart:Xe,lineEnd:$e,polygonStart:function(){Rc.lineStart=Be},polygonEnd:function(){Rc.point=Ve,Rc.lineStart=Xe,Rc.lineEnd=$e}};Zo.geo.path=function(){function n(n){return n&&("function"==typeof a&&i.pointRadius(+a.apply(this,arguments)),o&&o.valid||(o=u(i)),Zo.geo.stream(n,o)),i.result()}function t(){return o=null,n}var e,r,u,i,o,a=4.5;return n.area=function(n){return Ec=0,Zo.geo.stream(n,u(Tc)),Ec},n.centroid=function(n){return pc=vc=dc=mc=yc=xc=Mc=_c=bc=0,Zo.geo.stream(n,u(Rc)),bc?[Mc/bc,_c/bc]:xc?[mc/xc,yc/xc]:dc?[pc/dc,vc/dc]:[0/0,0/0]},n.bounds=function(n){return zc=Lc=-(Cc=Nc=1/0),Zo.geo.stream(n,u(qc)),[[Cc,Nc],[zc,Lc]]},n.projection=function(n){return arguments.length?(u=(e=n)?n.stream||Ge(n):wt,t()):e},n.context=function(n){return arguments.length?(i=null==(r=n)?new Ie:new We(n),"function"!=typeof a&&i.pointRadius(a),t()):r},n.pointRadius=function(t){return arguments.length?(a="function"==typeof t?t:(i.pointRadius(+t),+t),n):a},n.projection(Zo.geo.albersUsa()).context(null)},Zo.geo.transform=function(n){return{stream:function(t){var e=new Ke(t);for(var r in n)e[r]=n[r];return e}}},Ke.prototype={point:function(n,t){this.stream.point(n,t)},sphere:function(){this.stream.sphere()},lineStart:function(){this.stream.lineStart()},lineEnd:function(){this.stream.lineEnd()},polygonStart:function(){this.stream.polygonStart()},polygonEnd:function(){this.stream.polygonEnd()}},Zo.geo.projection=nr,Zo.geo.projectionMutator=tr,(Zo.geo.equirectangular=function(){return nr(rr)}).raw=rr.invert=rr,Zo.geo.rotation=function(n){function t(t){return t=n(t[0]*Aa,t[1]*Aa),t[0]*=Ca,t[1]*=Ca,t}return n=ir(n[0]%360*Aa,n[1]*Aa,n.length>2?n[2]*Aa:0),t.invert=function(t){return t=n.invert(t[0]*Aa,t[1]*Aa),t[0]*=Ca,t[1]*=Ca,t},t},ur.invert=rr,Zo.geo.circle=function(){function n(){var n="function"==typeof r?r.apply(this,arguments):r,t=ir(-n[0]*Aa,-n[1]*Aa,0).invert,u=[];return e(null,null,1,{point:function(n,e){u.push(n=t(n,e)),n[0]*=Ca,n[1]*=Ca}}),{type:"Polygon",coordinates:[u]}}var t,e,r=[0,0],u=6;return n.origin=function(t){return arguments.length?(r=t,n):r},n.angle=function(r){return arguments.length?(e=sr((t=+r)*Aa,u*Aa),n):t},n.precision=function(r){return arguments.length?(e=sr(t*Aa,(u=+r)*Aa),n):u},n.angle(90)},Zo.geo.distance=function(n,t){var e,r=(t[0]-n[0])*Aa,u=n[1]*Aa,i=t[1]*Aa,o=Math.sin(r),a=Math.cos(r),c=Math.sin(u),s=Math.cos(u),l=Math.sin(i),f=Math.cos(i);return Math.atan2(Math.sqrt((e=f*o)*e+(e=s*l-c*f*a)*e),c*l+s*f*a)},Zo.geo.graticule=function(){function n(){return{type:"MultiLineString",coordinates:t()}}function t(){return Zo.range(Math.ceil(i/d)*d,u,d).map(h).concat(Zo.range(Math.ceil(s/m)*m,c,m).map(g)).concat(Zo.range(Math.ceil(r/p)*p,e,p).filter(function(n){return ua(n%d)>ka}).map(l)).concat(Zo.range(Math.ceil(a/v)*v,o,v).filter(function(n){return ua(n%m)>ka}).map(f))}var e,r,u,i,o,a,c,s,l,f,h,g,p=10,v=p,d=90,m=360,y=2.5;return n.lines=function(){return t().map(function(n){return{type:"LineString",coordinates:n}})},n.outline=function(){return{type:"Polygon",coordinates:[h(i).concat(g(c).slice(1),h(u).reverse().slice(1),g(s).reverse().slice(1))]}},n.extent=function(t){return arguments.length?n.majorExtent(t).minorExtent(t):n.minorExtent()},n.majorExtent=function(t){return arguments.length?(i=+t[0][0],u=+t[1][0],s=+t[0][1],c=+t[1][1],i>u&&(t=i,i=u,u=t),s>c&&(t=s,s=c,c=t),n.precision(y)):[[i,s],[u,c]]},n.minorExtent=function(t){return arguments.length?(r=+t[0][0],e=+t[1][0],a=+t[0][1],o=+t[1][1],r>e&&(t=r,r=e,e=t),a>o&&(t=a,a=o,o=t),n.precision(y)):[[r,a],[e,o]]},n.step=function(t){return arguments.length?n.majorStep(t).minorStep(t):n.minorStep()},n.majorStep=function(t){return arguments.length?(d=+t[0],m=+t[1],n):[d,m]},n.minorStep=function(t){return arguments.length?(p=+t[0],v=+t[1],n):[p,v]},n.precision=function(t){return arguments.length?(y=+t,l=fr(a,o,90),f=hr(r,e,y),h=fr(s,c,90),g=hr(i,u,y),n):y},n.majorExtent([[-180,-90+ka],[180,90-ka]]).minorExtent([[-180,-80-ka],[180,80+ka]])},Zo.geo.greatArc=function(){function n(){return{type:"LineString",coordinates:[t||r.apply(this,arguments),e||u.apply(this,arguments)]}}var t,e,r=gr,u=pr;return n.distance=function(){return Zo.geo.distance(t||r.apply(this,arguments),e||u.apply(this,arguments))},n.source=function(e){return arguments.length?(r=e,t="function"==typeof e?null:e,n):r},n.target=function(t){return arguments.length?(u=t,e="function"==typeof t?null:t,n):u},n.precision=function(){return arguments.length?n:0},n},Zo.geo.interpolate=function(n,t){return vr(n[0]*Aa,n[1]*Aa,t[0]*Aa,t[1]*Aa)},Zo.geo.length=function(n){return Dc=0,Zo.geo.stream(n,Pc),Dc};var Dc,Pc={sphere:v,point:v,lineStart:dr,lineEnd:v,polygonStart:v,polygonEnd:v},Uc=mr(function(n){return Math.sqrt(2/(1+n))},function(n){return 2*Math.asin(n/2)});(Zo.geo.azimuthalEqualArea=function(){return nr(Uc)}).raw=Uc;var jc=mr(function(n){var t=Math.acos(n);return t&&t/Math.sin(t)},wt);(Zo.geo.azimuthalEquidistant=function(){return nr(jc)}).raw=jc,(Zo.geo.conicConformal=function(){return He(yr)}).raw=yr,(Zo.geo.conicEquidistant=function(){return He(xr)}).raw=xr;var Hc=mr(function(n){return 1/n},Math.atan);(Zo.geo.gnomonic=function(){return nr(Hc)}).raw=Hc,Mr.invert=function(n,t){return[n,2*Math.atan(Math.exp(t))-Sa]},(Zo.geo.mercator=function(){return _r(Mr)}).raw=Mr;var Fc=mr(function(){return 1},Math.asin);(Zo.geo.orthographic=function(){return nr(Fc)}).raw=Fc;var Oc=mr(function(n){return 1/(1+n)},function(n){return 2*Math.atan(n)});(Zo.geo.stereographic=function(){return nr(Oc)}).raw=Oc,br.invert=function(n,t){return[-t,2*Math.atan(Math.exp(n))-Sa]},(Zo.geo.transverseMercator=function(){var n=_r(br),t=n.center,e=n.rotate;return n.center=function(n){return n?t([-n[1],n[0]]):(n=t(),[n[1],-n[0]])},n.rotate=function(n){return n?e([n[0],n[1],n.length>2?n[2]+90:90]):(n=e(),[n[0],n[1],n[2]-90])},e([0,0,90])}).raw=br,Zo.geom={},Zo.geom.hull=function(n){function t(n){if(n.length<3)return[];var t,u=bt(e),i=bt(r),o=n.length,a=[],c=[];for(t=0;o>t;t++)a.push([+u.call(this,n[t],t),+i.call(this,n[t],t),t]);for(a.sort(Er),t=0;o>t;t++)c.push([a[t][0],-a[t][1]]);var s=kr(a),l=kr(c),f=l[0]===s[0],h=l[l.length-1]===s[s.length-1],g=[];for(t=s.length-1;t>=0;--t)g.push(n[a[s[t]][2]]);for(t=+f;t<l.length-h;++t)g.push(n[a[l[t]][2]]);return g}var e=wr,r=Sr;return arguments.length?t(n):(t.x=function(n){return arguments.length?(e=n,t):e},t.y=function(n){return arguments.length?(r=n,t):r},t)},Zo.geom.polygon=function(n){return sa(n,Yc),n};var Yc=Zo.geom.polygon.prototype=[];Yc.area=function(){for(var n,t=-1,e=this.length,r=this[e-1],u=0;++t<e;)n=r,r=this[t],u+=n[1]*r[0]-n[0]*r[1];return.5*u},Yc.centroid=function(n){var t,e,r=-1,u=this.length,i=0,o=0,a=this[u-1];for(arguments.length||(n=-1/(6*this.area()));++r<u;)t=a,a=this[r],e=t[0]*a[1]-a[0]*t[1],i+=(t[0]+a[0])*e,o+=(t[1]+a[1])*e;return[i*n,o*n]},Yc.clip=function(n){for(var t,e,r,u,i,o,a=Nr(n),c=-1,s=this.length-Nr(this),l=this[s-1];++c<s;){for(t=n.slice(),n.length=0,u=this[c],i=t[(r=t.length-a)-1],e=-1;++e<r;)o=t[e],Ar(o,l,u)?(Ar(i,l,u)||n.push(Cr(i,o,l,u)),n.push(o)):Ar(i,l,u)&&n.push(Cr(i,o,l,u)),i=o;a&&n.push(n[0]),l=u}return n};var Ic,Zc,Vc,Xc,$c,Bc=[],Wc=[];Ur.prototype.prepare=function(){for(var n,t=this.edges,e=t.length;e--;)n=t[e].edge,n.b&&n.a||t.splice(e,1);return t.sort(Hr),t.length},Wr.prototype={start:function(){return this.edge.l===this.site?this.edge.a:this.edge.b},end:function(){return this.edge.l===this.site?this.edge.b:this.edge.a}},Jr.prototype={insert:function(n,t){var e,r,u;if(n){if(t.P=n,t.N=n.N,n.N&&(n.N.P=t),n.N=t,n.R){for(n=n.R;n.L;)n=n.L;n.L=t}else n.R=t;e=n}else this._?(n=nu(this._),t.P=null,t.N=n,n.P=n.L=t,e=n):(t.P=t.N=null,this._=t,e=null);for(t.L=t.R=null,t.U=e,t.C=!0,n=t;e&&e.C;)r=e.U,e===r.L?(u=r.R,u&&u.C?(e.C=u.C=!1,r.C=!0,n=r):(n===e.R&&(Kr(this,e),n=e,e=n.U),e.C=!1,r.C=!0,Qr(this,r))):(u=r.L,u&&u.C?(e.C=u.C=!1,r.C=!0,n=r):(n===e.L&&(Qr(this,e),n=e,e=n.U),e.C=!1,r.C=!0,Kr(this,r))),e=n.U;this._.C=!1},remove:function(n){n.N&&(n.N.P=n.P),n.P&&(n.P.N=n.N),n.N=n.P=null;var t,e,r,u=n.U,i=n.L,o=n.R;if(e=i?o?nu(o):i:o,u?u.L===n?u.L=e:u.R=e:this._=e,i&&o?(r=e.C,e.C=n.C,e.L=i,i.U=e,e!==o?(u=e.U,e.U=n.U,n=e.R,u.L=n,e.R=o,o.U=e):(e.U=u,u=e,n=e.R)):(r=n.C,n=e),n&&(n.U=u),!r){if(n&&n.C)return n.C=!1,void 0;do{if(n===this._)break;if(n===u.L){if(t=u.R,t.C&&(t.C=!1,u.C=!0,Kr(this,u),t=u.R),t.L&&t.L.C||t.R&&t.R.C){t.R&&t.R.C||(t.L.C=!1,t.C=!0,Qr(this,t),t=u.R),t.C=u.C,u.C=t.R.C=!1,Kr(this,u),n=this._;break}}else if(t=u.L,t.C&&(t.C=!1,u.C=!0,Qr(this,u),t=u.L),t.L&&t.L.C||t.R&&t.R.C){t.L&&t.L.C||(t.R.C=!1,t.C=!0,Kr(this,t),t=u.L),t.C=u.C,u.C=t.L.C=!1,Qr(this,u),n=this._;break}t.C=!0,n=u,u=u.U}while(!n.C);n&&(n.C=!1)}}},Zo.geom.voronoi=function(n){function t(n){var t=new Array(n.length),r=a[0][0],u=a[0][1],i=a[1][0],o=a[1][1];return tu(e(n),a).cells.forEach(function(e,a){var c=e.edges,s=e.site,l=t[a]=c.length?c.map(function(n){var t=n.start();return[t.x,t.y]}):s.x>=r&&s.x<=i&&s.y>=u&&s.y<=o?[[r,o],[i,o],[i,u],[r,u]]:[];l.point=n[a]}),t}function e(n){return n.map(function(n,t){return{x:Math.round(i(n,t)/ka)*ka,y:Math.round(o(n,t)/ka)*ka,i:t}})}var r=wr,u=Sr,i=r,o=u,a=Jc;return n?t(n):(t.links=function(n){return tu(e(n)).edges.filter(function(n){return n.l&&n.r}).map(function(t){return{source:n[t.l.i],target:n[t.r.i]}})},t.triangles=function(n){var t=[];return tu(e(n)).cells.forEach(function(e,r){for(var u,i,o=e.site,a=e.edges.sort(Hr),c=-1,s=a.length,l=a[s-1].edge,f=l.l===o?l.r:l.l;++c<s;)u=l,i=f,l=a[c].edge,f=l.l===o?l.r:l.l,r<i.i&&r<f.i&&ru(o,i,f)<0&&t.push([n[r],n[i.i],n[f.i]])}),t},t.x=function(n){return arguments.length?(i=bt(r=n),t):r},t.y=function(n){return arguments.length?(o=bt(u=n),t):u},t.clipExtent=function(n){return arguments.length?(a=null==n?Jc:n,t):a===Jc?null:a},t.size=function(n){return arguments.length?t.clipExtent(n&&[[0,0],n]):a===Jc?null:a&&a[1]},t)};var Jc=[[-1e6,-1e6],[1e6,1e6]];Zo.geom.delaunay=function(n){return Zo.geom.voronoi().triangles(n)},Zo.geom.quadtree=function(n,t,e,r,u){function i(n){function i(n,t,e,r,u,i,o,a){if(!isNaN(e)&&!isNaN(r))if(n.leaf){var c=n.x,l=n.y;if(null!=c)if(ua(c-e)+ua(l-r)<.01)s(n,t,e,r,u,i,o,a);else{var f=n.point;n.x=n.y=n.point=null,s(n,f,c,l,u,i,o,a),s(n,t,e,r,u,i,o,a)}else n.x=e,n.y=r,n.point=t}else s(n,t,e,r,u,i,o,a)}function s(n,t,e,r,u,o,a,c){var s=.5*(u+a),l=.5*(o+c),f=e>=s,h=r>=l,g=(h<<1)+f;n.leaf=!1,n=n.nodes[g]||(n.nodes[g]=ou()),f?u=s:a=s,h?o=l:c=l,i(n,t,e,r,u,o,a,c)}var l,f,h,g,p,v,d,m,y,x=bt(a),M=bt(c);if(null!=t)v=t,d=e,m=r,y=u;else if(m=y=-(v=d=1/0),f=[],h=[],p=n.length,o)for(g=0;p>g;++g)l=n[g],l.x<v&&(v=l.x),l.y<d&&(d=l.y),l.x>m&&(m=l.x),l.y>y&&(y=l.y),f.push(l.x),h.push(l.y);else for(g=0;p>g;++g){var _=+x(l=n[g],g),b=+M(l,g);v>_&&(v=_),d>b&&(d=b),_>m&&(m=_),b>y&&(y=b),f.push(_),h.push(b)}var w=m-v,S=y-d;w>S?y=d+w:m=v+S;var k=ou();if(k.add=function(n){i(k,n,+x(n,++g),+M(n,g),v,d,m,y)},k.visit=function(n){au(n,k,v,d,m,y)},g=-1,null==t){for(;++g<p;)i(k,n[g],f[g],h[g],v,d,m,y);--g}else n.forEach(k.add);return f=h=n=l=null,k}var o,a=wr,c=Sr;return(o=arguments.length)?(a=uu,c=iu,3===o&&(u=e,r=t,e=t=0),i(n)):(i.x=function(n){return arguments.length?(a=n,i):a},i.y=function(n){return arguments.length?(c=n,i):c},i.extent=function(n){return arguments.length?(null==n?t=e=r=u=null:(t=+n[0][0],e=+n[0][1],r=+n[1][0],u=+n[1][1]),i):null==t?null:[[t,e],[r,u]]},i.size=function(n){return arguments.length?(null==n?t=e=r=u=null:(t=e=0,r=+n[0],u=+n[1]),i):null==t?null:[r-t,u-e]},i)},Zo.interpolateRgb=cu,Zo.interpolateObject=su,Zo.interpolateNumber=lu,Zo.interpolateString=fu;var Gc=/[-+]?(?:\d+\.?\d*|\.?\d+)(?:[eE][-+]?\d+)?/g,Kc=new RegExp(Gc.source,"g");Zo.interpolate=hu,Zo.interpolators=[function(n,t){var e=typeof t;return("string"===e?Ia.has(t)||/^(#|rgb\(|hsl\()/.test(t)?cu:fu:t instanceof et?cu:Array.isArray(t)?gu:"object"===e&&isNaN(t)?su:lu)(n,t)}],Zo.interpolateArray=gu;var Qc=function(){return wt},ns=Zo.map({linear:Qc,poly:Mu,quad:function(){return mu},cubic:function(){return yu},sin:function(){return _u},exp:function(){return bu},circle:function(){return wu},elastic:Su,back:ku,bounce:function(){return Eu}}),ts=Zo.map({"in":wt,out:vu,"in-out":du,"out-in":function(n){return du(vu(n))}});Zo.ease=function(n){var t=n.indexOf("-"),e=t>=0?n.substring(0,t):n,r=t>=0?n.substring(t+1):"in";return e=ns.get(e)||Qc,r=ts.get(r)||wt,pu(r(e.apply(null,Vo.call(arguments,1))))},Zo.interpolateHcl=Au,Zo.interpolateHsl=Cu,Zo.interpolateLab=Nu,Zo.interpolateRound=zu,Zo.transform=function(n){var t=$o.createElementNS(Zo.ns.prefix.svg,"g");return(Zo.transform=function(n){if(null!=n){t.setAttribute("transform",n);var e=t.transform.baseVal.consolidate()}return new Lu(e?e.matrix:es)})(n)},Lu.prototype.toString=function(){return"translate("+this.translate+")rotate("+this.rotate+")skewX("+this.skew+")scale("+this.scale+")"};var es={a:1,b:0,c:0,d:1,e:0,f:0};Zo.interpolateTransform=Du,Zo.layout={},Zo.layout.bundle=function(){return function(n){for(var t=[],e=-1,r=n.length;++e<r;)t.push(ju(n[e]));return t}},Zo.layout.chord=function(){function n(){var n,s,f,h,g,p={},v=[],d=Zo.range(i),m=[];for(e=[],r=[],n=0,h=-1;++h<i;){for(s=0,g=-1;++g<i;)s+=u[h][g];v.push(s),m.push(Zo.range(i)),n+=s}for(o&&d.sort(function(n,t){return o(v[n],v[t])}),a&&m.forEach(function(n,t){n.sort(function(n,e){return a(u[t][n],u[t][e])})}),n=(wa-l*i)/n,s=0,h=-1;++h<i;){for(f=s,g=-1;++g<i;){var y=d[h],x=m[y][g],M=u[y][x],_=s,b=s+=M*n;p[y+"-"+x]={index:y,subindex:x,startAngle:_,endAngle:b,value:M}}r[y]={index:y,startAngle:f,endAngle:s,value:(s-f)/n},s+=l}for(h=-1;++h<i;)for(g=h-1;++g<i;){var w=p[h+"-"+g],S=p[g+"-"+h];(w.value||S.value)&&e.push(w.value<S.value?{source:S,target:w}:{source:w,target:S})}c&&t()}function t(){e.sort(function(n,t){return c((n.source.value+n.target.value)/2,(t.source.value+t.target.value)/2)})}var e,r,u,i,o,a,c,s={},l=0;return s.matrix=function(n){return arguments.length?(i=(u=n)&&u.length,e=r=null,s):u},s.padding=function(n){return arguments.length?(l=n,e=r=null,s):l},s.sortGroups=function(n){return arguments.length?(o=n,e=r=null,s):o},s.sortSubgroups=function(n){return arguments.length?(a=n,e=null,s):a},s.sortChords=function(n){return arguments.length?(c=n,e&&t(),s):c},s.chords=function(){return e||n(),e},s.groups=function(){return r||n(),r},s},Zo.layout.force=function(){function n(n){return function(t,e,r,u){if(t.point!==n){var i=t.cx-n.x,o=t.cy-n.y,a=u-e,c=i*i+o*o;if(c>a*a/d){if(p>c){var s=t.charge/c;n.px-=i*s,n.py-=o*s}return!0}if(t.point&&c&&p>c){var s=t.pointCharge/c;n.px-=i*s,n.py-=o*s}}return!t.charge}}function t(n){n.px=Zo.event.x,n.py=Zo.event.y,a.resume()}var e,r,u,i,o,a={},c=Zo.dispatch("start","tick","end"),s=[1,1],l=.9,f=rs,h=us,g=-30,p=is,v=.1,d=.64,m=[],y=[];return a.tick=function(){if((r*=.99)<.005)return c.end({type:"end",alpha:r=0}),!0;var t,e,a,f,h,p,d,x,M,_=m.length,b=y.length;for(e=0;b>e;++e)a=y[e],f=a.source,h=a.target,x=h.x-f.x,M=h.y-f.y,(p=x*x+M*M)&&(p=r*i[e]*((p=Math.sqrt(p))-u[e])/p,x*=p,M*=p,h.x-=x*(d=f.weight/(h.weight+f.weight)),h.y-=M*d,f.x+=x*(d=1-d),f.y+=M*d);if((d=r*v)&&(x=s[0]/2,M=s[1]/2,e=-1,d))for(;++e<_;)a=m[e],a.x+=(x-a.x)*d,a.y+=(M-a.y)*d;if(g)for(Vu(t=Zo.geom.quadtree(m),r,o),e=-1;++e<_;)(a=m[e]).fixed||t.visit(n(a));for(e=-1;++e<_;)a=m[e],a.fixed?(a.x=a.px,a.y=a.py):(a.x-=(a.px-(a.px=a.x))*l,a.y-=(a.py-(a.py=a.y))*l);c.tick({type:"tick",alpha:r})},a.nodes=function(n){return arguments.length?(m=n,a):m},a.links=function(n){return arguments.length?(y=n,a):y},a.size=function(n){return arguments.length?(s=n,a):s},a.linkDistance=function(n){return arguments.length?(f="function"==typeof n?n:+n,a):f},a.distance=a.linkDistance,a.linkStrength=function(n){return arguments.length?(h="function"==typeof n?n:+n,a):h},a.friction=function(n){return arguments.length?(l=+n,a):l},a.charge=function(n){return arguments.length?(g="function"==typeof n?n:+n,a):g},a.chargeDistance=function(n){return arguments.length?(p=n*n,a):Math.sqrt(p)},a.gravity=function(n){return arguments.length?(v=+n,a):v},a.theta=function(n){return arguments.length?(d=n*n,a):Math.sqrt(d)},a.alpha=function(n){return arguments.length?(n=+n,r?r=n>0?n:0:n>0&&(c.start({type:"start",alpha:r=n}),Zo.timer(a.tick)),a):r},a.start=function(){function n(n,r){if(!e){for(e=new Array(c),a=0;c>a;++a)e[a]=[];for(a=0;s>a;++a){var u=y[a];e[u.source.index].push(u.target),e[u.target.index].push(u.source)}}for(var i,o=e[t],a=-1,s=o.length;++a<s;)if(!isNaN(i=o[a][n]))return i;return Math.random()*r}var t,e,r,c=m.length,l=y.length,p=s[0],v=s[1];for(t=0;c>t;++t)(r=m[t]).index=t,r.weight=0;for(t=0;l>t;++t)r=y[t],"number"==typeof r.source&&(r.source=m[r.source]),"number"==typeof r.target&&(r.target=m[r.target]),++r.source.weight,++r.target.weight;for(t=0;c>t;++t)r=m[t],isNaN(r.x)&&(r.x=n("x",p)),isNaN(r.y)&&(r.y=n("y",v)),isNaN(r.px)&&(r.px=r.x),isNaN(r.py)&&(r.py=r.y);if(u=[],"function"==typeof f)for(t=0;l>t;++t)u[t]=+f.call(this,y[t],t);else for(t=0;l>t;++t)u[t]=f;if(i=[],"function"==typeof h)for(t=0;l>t;++t)i[t]=+h.call(this,y[t],t);else for(t=0;l>t;++t)i[t]=h;if(o=[],"function"==typeof g)for(t=0;c>t;++t)o[t]=+g.call(this,m[t],t);else for(t=0;c>t;++t)o[t]=g;return a.resume()},a.resume=function(){return a.alpha(.1)},a.stop=function(){return a.alpha(0)},a.drag=function(){return e||(e=Zo.behavior.drag().origin(wt).on("dragstart.force",Ou).on("drag.force",t).on("dragend.force",Yu)),arguments.length?(this.on("mouseover.force",Iu).on("mouseout.force",Zu).call(e),void 0):e},Zo.rebind(a,c,"on")};var rs=20,us=1,is=1/0;Zo.layout.hierarchy=function(){function n(u){var i,o=[u],a=[];for(u.depth=0;null!=(i=o.pop());)if(a.push(i),(s=e.call(n,i,i.depth))&&(c=s.length)){for(var c,s,l;--c>=0;)o.push(l=s[c]),l.parent=i,l.depth=i.depth+1;r&&(i.value=0),i.children=s}else r&&(i.value=+r.call(n,i,i.depth)||0),delete i.children;return Bu(u,function(n){var e,u;t&&(e=n.children)&&e.sort(t),r&&(u=n.parent)&&(u.value+=n.value)}),a}var t=Gu,e=Wu,r=Ju;return n.sort=function(e){return arguments.length?(t=e,n):t},n.children=function(t){return arguments.length?(e=t,n):e},n.value=function(t){return arguments.length?(r=t,n):r},n.revalue=function(t){return r&&($u(t,function(n){n.children&&(n.value=0)}),Bu(t,function(t){var e;t.children||(t.value=+r.call(n,t,t.depth)||0),(e=t.parent)&&(e.value+=t.value)})),t},n},Zo.layout.partition=function(){function n(t,e,r,u){var i=t.children;if(t.x=e,t.y=t.depth*u,t.dx=r,t.dy=u,i&&(o=i.length)){var o,a,c,s=-1;for(r=t.value?r/t.value:0;++s<o;)n(a=i[s],e,c=a.value*r,u),e+=c}}function t(n){var e=n.children,r=0;if(e&&(u=e.length))for(var u,i=-1;++i<u;)r=Math.max(r,t(e[i]));return 1+r}function e(e,i){var o=r.call(this,e,i);return n(o[0],0,u[0],u[1]/t(o[0])),o}var r=Zo.layout.hierarchy(),u=[1,1];return e.size=function(n){return arguments.length?(u=n,e):u},Xu(e,r)},Zo.layout.pie=function(){function n(i){var o=i.map(function(e,r){return+t.call(n,e,r)}),a=+("function"==typeof r?r.apply(this,arguments):r),c=(("function"==typeof u?u.apply(this,arguments):u)-a)/Zo.sum(o),s=Zo.range(i.length);null!=e&&s.sort(e===os?function(n,t){return o[t]-o[n]}:function(n,t){return e(i[n],i[t])});var l=[];return s.forEach(function(n){var t;l[n]={data:i[n],value:t=o[n],startAngle:a,endAngle:a+=t*c}}),l}var t=Number,e=os,r=0,u=wa;return n.value=function(e){return arguments.length?(t=e,n):t},n.sort=function(t){return arguments.length?(e=t,n):e},n.startAngle=function(t){return arguments.length?(r=t,n):r},n.endAngle=function(t){return arguments.length?(u=t,n):u},n};var os={};Zo.layout.stack=function(){function n(a,c){var s=a.map(function(e,r){return t.call(n,e,r)}),l=s.map(function(t){return t.map(function(t,e){return[i.call(n,t,e),o.call(n,t,e)]})}),f=e.call(n,l,c);s=Zo.permute(s,f),l=Zo.permute(l,f);var h,g,p,v=r.call(n,l,c),d=s.length,m=s[0].length;for(g=0;m>g;++g)for(u.call(n,s[0][g],p=v[g],l[0][g][1]),h=1;d>h;++h)u.call(n,s[h][g],p+=l[h-1][g][1],l[h][g][1]);return a}var t=wt,e=ei,r=ri,u=ti,i=Qu,o=ni;return n.values=function(e){return arguments.length?(t=e,n):t},n.order=function(t){return arguments.length?(e="function"==typeof t?t:as.get(t)||ei,n):e},n.offset=function(t){return arguments.length?(r="function"==typeof t?t:cs.get(t)||ri,n):r},n.x=function(t){return arguments.length?(i=t,n):i},n.y=function(t){return arguments.length?(o=t,n):o},n.out=function(t){return arguments.length?(u=t,n):u},n};var as=Zo.map({"inside-out":function(n){var t,e,r=n.length,u=n.map(ui),i=n.map(ii),o=Zo.range(r).sort(function(n,t){return u[n]-u[t]}),a=0,c=0,s=[],l=[];for(t=0;r>t;++t)e=o[t],c>a?(a+=i[e],s.push(e)):(c+=i[e],l.push(e));return l.reverse().concat(s)},reverse:function(n){return Zo.range(n.length).reverse()},"default":ei}),cs=Zo.map({silhouette:function(n){var t,e,r,u=n.length,i=n[0].length,o=[],a=0,c=[];for(e=0;i>e;++e){for(t=0,r=0;u>t;t++)r+=n[t][e][1];r>a&&(a=r),o.push(r)}for(e=0;i>e;++e)c[e]=(a-o[e])/2;return c},wiggle:function(n){var t,e,r,u,i,o,a,c,s,l=n.length,f=n[0],h=f.length,g=[];for(g[0]=c=s=0,e=1;h>e;++e){for(t=0,u=0;l>t;++t)u+=n[t][e][1];for(t=0,i=0,a=f[e][0]-f[e-1][0];l>t;++t){for(r=0,o=(n[t][e][1]-n[t][e-1][1])/(2*a);t>r;++r)o+=(n[r][e][1]-n[r][e-1][1])/a;i+=o*n[t][e][1]}g[e]=c-=u?i/u*a:0,s>c&&(s=c)}for(e=0;h>e;++e)g[e]-=s;return g},expand:function(n){var t,e,r,u=n.length,i=n[0].length,o=1/u,a=[];for(e=0;i>e;++e){for(t=0,r=0;u>t;t++)r+=n[t][e][1];if(r)for(t=0;u>t;t++)n[t][e][1]/=r;else for(t=0;u>t;t++)n[t][e][1]=o}for(e=0;i>e;++e)a[e]=0;return a},zero:ri});Zo.layout.histogram=function(){function n(n,i){for(var o,a,c=[],s=n.map(e,this),l=r.call(this,s,i),f=u.call(this,l,s,i),i=-1,h=s.length,g=f.length-1,p=t?1:1/h;++i<g;)o=c[i]=[],o.dx=f[i+1]-(o.x=f[i]),o.y=0;if(g>0)for(i=-1;++i<h;)a=s[i],a>=l[0]&&a<=l[1]&&(o=c[Zo.bisect(f,a,1,g)-1],o.y+=p,o.push(n[i]));return c}var t=!0,e=Number,r=si,u=ai;return n.value=function(t){return arguments.length?(e=t,n):e},n.range=function(t){return arguments.length?(r=bt(t),n):r},n.bins=function(t){return arguments.length?(u="number"==typeof t?function(n){return ci(n,t)}:bt(t),n):u},n.frequency=function(e){return arguments.length?(t=!!e,n):t},n},Zo.layout.pack=function(){function n(n,i){var o=e.call(this,n,i),a=o[0],c=u[0],s=u[1],l=null==t?Math.sqrt:"function"==typeof t?t:function(){return t};if(a.x=a.y=0,Bu(a,function(n){n.r=+l(n.value)}),Bu(a,pi),r){var f=r*(t?1:Math.max(2*a.r/c,2*a.r/s))/2;Bu(a,function(n){n.r+=f}),Bu(a,pi),Bu(a,function(n){n.r-=f})}return mi(a,c/2,s/2,t?1:1/Math.max(2*a.r/c,2*a.r/s)),o}var t,e=Zo.layout.hierarchy().sort(li),r=0,u=[1,1];return n.size=function(t){return arguments.length?(u=t,n):u},n.radius=function(e){return arguments.length?(t=null==e||"function"==typeof e?e:+e,n):t},n.padding=function(t){return arguments.length?(r=+t,n):r},Xu(n,e)},Zo.layout.tree=function(){function n(n,u){var l=o.call(this,n,u),f=l[0],h=t(f);if(Bu(h,e),h.parent.m=-h.z,$u(h,r),s)$u(f,i);else{var g=f,p=f,v=f;$u(f,function(n){n.x<g.x&&(g=n),n.x>p.x&&(p=n),n.depth>v.depth&&(v=n)});var d=a(g,p)/2-g.x,m=c[0]/(p.x+a(p,g)/2+d),y=c[1]/(v.depth||1);$u(f,function(n){n.x=(n.x+d)*m,n.y=n.depth*y})}return l}function t(n){for(var t,e={A:null,children:[n]},r=[e];null!=(t=r.pop());)for(var u,i=t.children,o=0,a=i.length;a>o;++o)r.push((i[o]=u={_:i[o],parent:t,children:(u=i[o].children)&&u.slice()||[],A:null,a:null,z:0,m:0,c:0,s:0,t:null,i:o}).a=u);return e.children[0]}function e(n){var t=n.children,e=n.parent.children,r=n.i?e[n.i-1]:null;if(t.length){wi(n);var i=(t[0].z+t[t.length-1].z)/2;r?(n.z=r.z+a(n._,r._),n.m=n.z-i):n.z=i}else r&&(n.z=r.z+a(n._,r._));n.parent.A=u(n,r,n.parent.A||e[0])}function r(n){n._.x=n.z+n.parent.m,n.m+=n.parent.m}function u(n,t,e){if(t){for(var r,u=n,i=n,o=t,c=u.parent.children[0],s=u.m,l=i.m,f=o.m,h=c.m;o=_i(o),u=Mi(u),o&&u;)c=Mi(c),i=_i(i),i.a=n,r=o.z+f-u.z-s+a(o._,u._),r>0&&(bi(Si(o,n,e),n,r),s+=r,l+=r),f+=o.m,s+=u.m,h+=c.m,l+=i.m;o&&!_i(i)&&(i.t=o,i.m+=f-l),u&&!Mi(c)&&(c.t=u,c.m+=s-h,e=n)}return e}function i(n){n.x*=c[0],n.y=n.depth*c[1]}var o=Zo.layout.hierarchy().sort(null).value(null),a=xi,c=[1,1],s=null;return n.separation=function(t){return arguments.length?(a=t,n):a},n.size=function(t){return arguments.length?(s=null==(c=t)?i:null,n):s?null:c},n.nodeSize=function(t){return arguments.length?(s=null==(c=t)?null:i,n):s?c:null},Xu(n,o)},Zo.layout.cluster=function(){function n(n,i){var o,a=t.call(this,n,i),c=a[0],s=0;Bu(c,function(n){var t=n.children;t&&t.length?(n.x=Ei(t),n.y=ki(t)):(n.x=o?s+=e(n,o):0,n.y=0,o=n)});var l=Ai(c),f=Ci(c),h=l.x-e(l,f)/2,g=f.x+e(f,l)/2;return Bu(c,u?function(n){n.x=(n.x-c.x)*r[0],n.y=(c.y-n.y)*r[1]}:function(n){n.x=(n.x-h)/(g-h)*r[0],n.y=(1-(c.y?n.y/c.y:1))*r[1]}),a}var t=Zo.layout.hierarchy().sort(null).value(null),e=xi,r=[1,1],u=!1;return n.separation=function(t){return arguments.length?(e=t,n):e},n.size=function(t){return arguments.length?(u=null==(r=t),n):u?null:r},n.nodeSize=function(t){return arguments.length?(u=null!=(r=t),n):u?r:null},Xu(n,t)},Zo.layout.treemap=function(){function n(n,t){for(var e,r,u=-1,i=n.length;++u<i;)r=(e=n[u]).value*(0>t?0:t),e.area=isNaN(r)||0>=r?0:r}function t(e){var i=e.children;if(i&&i.length){var o,a,c,s=f(e),l=[],h=i.slice(),p=1/0,v="slice"===g?s.dx:"dice"===g?s.dy:"slice-dice"===g?1&e.depth?s.dy:s.dx:Math.min(s.dx,s.dy);for(n(h,s.dx*s.dy/e.value),l.area=0;(c=h.length)>0;)l.push(o=h[c-1]),l.area+=o.area,"squarify"!==g||(a=r(l,v))<=p?(h.pop(),p=a):(l.area-=l.pop().area,u(l,v,s,!1),v=Math.min(s.dx,s.dy),l.length=l.area=0,p=1/0);l.length&&(u(l,v,s,!0),l.length=l.area=0),i.forEach(t)}}function e(t){var r=t.children;if(r&&r.length){var i,o=f(t),a=r.slice(),c=[];for(n(a,o.dx*o.dy/t.value),c.area=0;i=a.pop();)c.push(i),c.area+=i.area,null!=i.z&&(u(c,i.z?o.dx:o.dy,o,!a.length),c.length=c.area=0);r.forEach(e)}}function r(n,t){for(var e,r=n.area,u=0,i=1/0,o=-1,a=n.length;++o<a;)(e=n[o].area)&&(i>e&&(i=e),e>u&&(u=e));return r*=r,t*=t,r?Math.max(t*u*p/r,r/(t*i*p)):1/0}function u(n,t,e,r){var u,i=-1,o=n.length,a=e.x,s=e.y,l=t?c(n.area/t):0;if(t==e.dx){for((r||l>e.dy)&&(l=e.dy);++i<o;)u=n[i],u.x=a,u.y=s,u.dy=l,a+=u.dx=Math.min(e.x+e.dx-a,l?c(u.area/l):0);u.z=!0,u.dx+=e.x+e.dx-a,e.y+=l,e.dy-=l}else{for((r||l>e.dx)&&(l=e.dx);++i<o;)u=n[i],u.x=a,u.y=s,u.dx=l,s+=u.dy=Math.min(e.y+e.dy-s,l?c(u.area/l):0);u.z=!1,u.dy+=e.y+e.dy-s,e.x+=l,e.dx-=l}}function i(r){var u=o||a(r),i=u[0];return i.x=0,i.y=0,i.dx=s[0],i.dy=s[1],o&&a.revalue(i),n([i],i.dx*i.dy/i.value),(o?e:t)(i),h&&(o=u),u}var o,a=Zo.layout.hierarchy(),c=Math.round,s=[1,1],l=null,f=Ni,h=!1,g="squarify",p=.5*(1+Math.sqrt(5));return i.size=function(n){return arguments.length?(s=n,i):s},i.padding=function(n){function t(t){var e=n.call(i,t,t.depth);return null==e?Ni(t):zi(t,"number"==typeof e?[e,e,e,e]:e)}function e(t){return zi(t,n)}if(!arguments.length)return l;var r;return f=null==(l=n)?Ni:"function"==(r=typeof n)?t:"number"===r?(n=[n,n,n,n],e):e,i},i.round=function(n){return arguments.length?(c=n?Math.round:Number,i):c!=Number},i.sticky=function(n){return arguments.length?(h=n,o=null,i):h},i.ratio=function(n){return arguments.length?(p=n,i):p},i.mode=function(n){return arguments.length?(g=n+"",i):g},Xu(i,a)},Zo.random={normal:function(n,t){var e=arguments.length;return 2>e&&(t=1),1>e&&(n=0),function(){var e,r,u;do e=2*Math.random()-1,r=2*Math.random()-1,u=e*e+r*r;while(!u||u>1);return n+t*e*Math.sqrt(-2*Math.log(u)/u)}},logNormal:function(){var n=Zo.random.normal.apply(Zo,arguments);return function(){return Math.exp(n())}},bates:function(n){var t=Zo.random.irwinHall(n);return function(){return t()/n}},irwinHall:function(n){return function(){for(var t=0,e=0;n>e;e++)t+=Math.random();return t}}},Zo.scale={};var ss={floor:wt,ceil:wt};Zo.scale.linear=function(){return Ui([0,1],[0,1],hu,!1)};var ls={s:1,g:1,p:1,r:1,e:1};Zo.scale.log=function(){return Vi(Zo.scale.linear().domain([0,1]),10,!0,[1,10])};var fs=Zo.format(".0e"),hs={floor:function(n){return-Math.ceil(-n)},ceil:function(n){return-Math.floor(-n)}};Zo.scale.pow=function(){return Xi(Zo.scale.linear(),1,[0,1])},Zo.scale.sqrt=function(){return Zo.scale.pow().exponent(.5)},Zo.scale.ordinal=function(){return Bi([],{t:"range",a:[[]]})},Zo.scale.category10=function(){return Zo.scale.ordinal().range(gs)},Zo.scale.category20=function(){return Zo.scale.ordinal().range(ps)},Zo.scale.category20b=function(){return Zo.scale.ordinal().range(vs)},Zo.scale.category20c=function(){return Zo.scale.ordinal().range(ds)};var gs=[2062260,16744206,2924588,14034728,9725885,9197131,14907330,8355711,12369186,1556175].map(vt),ps=[2062260,11454440,16744206,16759672,2924588,10018698,14034728,16750742,9725885,12955861,9197131,12885140,14907330,16234194,8355711,13092807,12369186,14408589,1556175,10410725].map(vt),vs=[3750777,5395619,7040719,10264286,6519097,9216594,11915115,13556636,9202993,12426809,15186514,15190932,8666169,11356490,14049643,15177372,8077683,10834324,13528509,14589654].map(vt),ds=[3244733,7057110,10406625,13032431,15095053,16616764,16625259,16634018,3253076,7652470,10607003,13101504,7695281,10394312,12369372,14342891,6513507,9868950,12434877,14277081].map(vt);Zo.scale.quantile=function(){return Wi([],[])},Zo.scale.quantize=function(){return Ji(0,1,[0,1])},Zo.scale.threshold=function(){return Gi([.5],[0,1])},Zo.scale.identity=function(){return Ki([0,1])},Zo.svg={},Zo.svg.arc=function(){function n(){var n=t.apply(this,arguments),i=e.apply(this,arguments),o=r.apply(this,arguments)+ms,a=u.apply(this,arguments)+ms,c=(o>a&&(c=o,o=a,a=c),a-o),s=ba>c?"0":"1",l=Math.cos(o),f=Math.sin(o),h=Math.cos(a),g=Math.sin(a);
return c>=ys?n?"M0,"+i+"A"+i+","+i+" 0 1,1 0,"+-i+"A"+i+","+i+" 0 1,1 0,"+i+"M0,"+n+"A"+n+","+n+" 0 1,0 0,"+-n+"A"+n+","+n+" 0 1,0 0,"+n+"Z":"M0,"+i+"A"+i+","+i+" 0 1,1 0,"+-i+"A"+i+","+i+" 0 1,1 0,"+i+"Z":n?"M"+i*l+","+i*f+"A"+i+","+i+" 0 "+s+",1 "+i*h+","+i*g+"L"+n*h+","+n*g+"A"+n+","+n+" 0 "+s+",0 "+n*l+","+n*f+"Z":"M"+i*l+","+i*f+"A"+i+","+i+" 0 "+s+",1 "+i*h+","+i*g+"L0,0"+"Z"}var t=Qi,e=no,r=to,u=eo;return n.innerRadius=function(e){return arguments.length?(t=bt(e),n):t},n.outerRadius=function(t){return arguments.length?(e=bt(t),n):e},n.startAngle=function(t){return arguments.length?(r=bt(t),n):r},n.endAngle=function(t){return arguments.length?(u=bt(t),n):u},n.centroid=function(){var n=(t.apply(this,arguments)+e.apply(this,arguments))/2,i=(r.apply(this,arguments)+u.apply(this,arguments))/2+ms;return[Math.cos(i)*n,Math.sin(i)*n]},n};var ms=-Sa,ys=wa-ka;Zo.svg.line=function(){return ro(wt)};var xs=Zo.map({linear:uo,"linear-closed":io,step:oo,"step-before":ao,"step-after":co,basis:po,"basis-open":vo,"basis-closed":mo,bundle:yo,cardinal:fo,"cardinal-open":so,"cardinal-closed":lo,monotone:So});xs.forEach(function(n,t){t.key=n,t.closed=/-closed$/.test(n)});var Ms=[0,2/3,1/3,0],_s=[0,1/3,2/3,0],bs=[0,1/6,2/3,1/6];Zo.svg.line.radial=function(){var n=ro(ko);return n.radius=n.x,delete n.x,n.angle=n.y,delete n.y,n},ao.reverse=co,co.reverse=ao,Zo.svg.area=function(){return Eo(wt)},Zo.svg.area.radial=function(){var n=Eo(ko);return n.radius=n.x,delete n.x,n.innerRadius=n.x0,delete n.x0,n.outerRadius=n.x1,delete n.x1,n.angle=n.y,delete n.y,n.startAngle=n.y0,delete n.y0,n.endAngle=n.y1,delete n.y1,n},Zo.svg.chord=function(){function n(n,a){var c=t(this,i,n,a),s=t(this,o,n,a);return"M"+c.p0+r(c.r,c.p1,c.a1-c.a0)+(e(c,s)?u(c.r,c.p1,c.r,c.p0):u(c.r,c.p1,s.r,s.p0)+r(s.r,s.p1,s.a1-s.a0)+u(s.r,s.p1,c.r,c.p0))+"Z"}function t(n,t,e,r){var u=t.call(n,e,r),i=a.call(n,u,r),o=c.call(n,u,r)+ms,l=s.call(n,u,r)+ms;return{r:i,a0:o,a1:l,p0:[i*Math.cos(o),i*Math.sin(o)],p1:[i*Math.cos(l),i*Math.sin(l)]}}function e(n,t){return n.a0==t.a0&&n.a1==t.a1}function r(n,t,e){return"A"+n+","+n+" 0 "+ +(e>ba)+",1 "+t}function u(n,t,e,r){return"Q 0,0 "+r}var i=gr,o=pr,a=Ao,c=to,s=eo;return n.radius=function(t){return arguments.length?(a=bt(t),n):a},n.source=function(t){return arguments.length?(i=bt(t),n):i},n.target=function(t){return arguments.length?(o=bt(t),n):o},n.startAngle=function(t){return arguments.length?(c=bt(t),n):c},n.endAngle=function(t){return arguments.length?(s=bt(t),n):s},n},Zo.svg.diagonal=function(){function n(n,u){var i=t.call(this,n,u),o=e.call(this,n,u),a=(i.y+o.y)/2,c=[i,{x:i.x,y:a},{x:o.x,y:a},o];return c=c.map(r),"M"+c[0]+"C"+c[1]+" "+c[2]+" "+c[3]}var t=gr,e=pr,r=Co;return n.source=function(e){return arguments.length?(t=bt(e),n):t},n.target=function(t){return arguments.length?(e=bt(t),n):e},n.projection=function(t){return arguments.length?(r=t,n):r},n},Zo.svg.diagonal.radial=function(){var n=Zo.svg.diagonal(),t=Co,e=n.projection;return n.projection=function(n){return arguments.length?e(No(t=n)):t},n},Zo.svg.symbol=function(){function n(n,r){return(ws.get(t.call(this,n,r))||To)(e.call(this,n,r))}var t=Lo,e=zo;return n.type=function(e){return arguments.length?(t=bt(e),n):t},n.size=function(t){return arguments.length?(e=bt(t),n):e},n};var ws=Zo.map({circle:To,cross:function(n){var t=Math.sqrt(n/5)/2;return"M"+-3*t+","+-t+"H"+-t+"V"+-3*t+"H"+t+"V"+-t+"H"+3*t+"V"+t+"H"+t+"V"+3*t+"H"+-t+"V"+t+"H"+-3*t+"Z"},diamond:function(n){var t=Math.sqrt(n/(2*As)),e=t*As;return"M0,"+-t+"L"+e+",0"+" 0,"+t+" "+-e+",0"+"Z"},square:function(n){var t=Math.sqrt(n)/2;return"M"+-t+","+-t+"L"+t+","+-t+" "+t+","+t+" "+-t+","+t+"Z"},"triangle-down":function(n){var t=Math.sqrt(n/Es),e=t*Es/2;return"M0,"+e+"L"+t+","+-e+" "+-t+","+-e+"Z"},"triangle-up":function(n){var t=Math.sqrt(n/Es),e=t*Es/2;return"M0,"+-e+"L"+t+","+e+" "+-t+","+e+"Z"}});Zo.svg.symbolTypes=ws.keys();var Ss,ks,Es=Math.sqrt(3),As=Math.tan(30*Aa),Cs=[],Ns=0;Cs.call=pa.call,Cs.empty=pa.empty,Cs.node=pa.node,Cs.size=pa.size,Zo.transition=function(n){return arguments.length?Ss?n.transition():n:ma.transition()},Zo.transition.prototype=Cs,Cs.select=function(n){var t,e,r,u=this.id,i=[];n=b(n);for(var o=-1,a=this.length;++o<a;){i.push(t=[]);for(var c=this[o],s=-1,l=c.length;++s<l;)(r=c[s])&&(e=n.call(r,r.__data__,s,o))?("__data__"in r&&(e.__data__=r.__data__),Po(e,s,u,r.__transition__[u]),t.push(e)):t.push(null)}return qo(i,u)},Cs.selectAll=function(n){var t,e,r,u,i,o=this.id,a=[];n=w(n);for(var c=-1,s=this.length;++c<s;)for(var l=this[c],f=-1,h=l.length;++f<h;)if(r=l[f]){i=r.__transition__[o],e=n.call(r,r.__data__,f,c),a.push(t=[]);for(var g=-1,p=e.length;++g<p;)(u=e[g])&&Po(u,g,o,i),t.push(u)}return qo(a,o)},Cs.filter=function(n){var t,e,r,u=[];"function"!=typeof n&&(n=R(n));for(var i=0,o=this.length;o>i;i++){u.push(t=[]);for(var e=this[i],a=0,c=e.length;c>a;a++)(r=e[a])&&n.call(r,r.__data__,a,i)&&t.push(r)}return qo(u,this.id)},Cs.tween=function(n,t){var e=this.id;return arguments.length<2?this.node().__transition__[e].tween.get(n):P(this,null==t?function(t){t.__transition__[e].tween.remove(n)}:function(r){r.__transition__[e].tween.set(n,t)})},Cs.attr=function(n,t){function e(){this.removeAttribute(a)}function r(){this.removeAttributeNS(a.space,a.local)}function u(n){return null==n?e:(n+="",function(){var t,e=this.getAttribute(a);return e!==n&&(t=o(e,n),function(n){this.setAttribute(a,t(n))})})}function i(n){return null==n?r:(n+="",function(){var t,e=this.getAttributeNS(a.space,a.local);return e!==n&&(t=o(e,n),function(n){this.setAttributeNS(a.space,a.local,t(n))})})}if(arguments.length<2){for(t in n)this.attr(t,n[t]);return this}var o="transform"==n?Du:hu,a=Zo.ns.qualify(n);return Ro(this,"attr."+n,t,a.local?i:u)},Cs.attrTween=function(n,t){function e(n,e){var r=t.call(this,n,e,this.getAttribute(u));return r&&function(n){this.setAttribute(u,r(n))}}function r(n,e){var r=t.call(this,n,e,this.getAttributeNS(u.space,u.local));return r&&function(n){this.setAttributeNS(u.space,u.local,r(n))}}var u=Zo.ns.qualify(n);return this.tween("attr."+n,u.local?r:e)},Cs.style=function(n,t,e){function r(){this.style.removeProperty(n)}function u(t){return null==t?r:(t+="",function(){var r,u=Wo.getComputedStyle(this,null).getPropertyValue(n);return u!==t&&(r=hu(u,t),function(t){this.style.setProperty(n,r(t),e)})})}var i=arguments.length;if(3>i){if("string"!=typeof n){2>i&&(t="");for(e in n)this.style(e,n[e],t);return this}e=""}return Ro(this,"style."+n,t,u)},Cs.styleTween=function(n,t,e){function r(r,u){var i=t.call(this,r,u,Wo.getComputedStyle(this,null).getPropertyValue(n));return i&&function(t){this.style.setProperty(n,i(t),e)}}return arguments.length<3&&(e=""),this.tween("style."+n,r)},Cs.text=function(n){return Ro(this,"text",n,Do)},Cs.remove=function(){return this.each("end.transition",function(){var n;this.__transition__.count<2&&(n=this.parentNode)&&n.removeChild(this)})},Cs.ease=function(n){var t=this.id;return arguments.length<1?this.node().__transition__[t].ease:("function"!=typeof n&&(n=Zo.ease.apply(Zo,arguments)),P(this,function(e){e.__transition__[t].ease=n}))},Cs.delay=function(n){var t=this.id;return arguments.length<1?this.node().__transition__[t].delay:P(this,"function"==typeof n?function(e,r,u){e.__transition__[t].delay=+n.call(e,e.__data__,r,u)}:(n=+n,function(e){e.__transition__[t].delay=n}))},Cs.duration=function(n){var t=this.id;return arguments.length<1?this.node().__transition__[t].duration:P(this,"function"==typeof n?function(e,r,u){e.__transition__[t].duration=Math.max(1,n.call(e,e.__data__,r,u))}:(n=Math.max(1,n),function(e){e.__transition__[t].duration=n}))},Cs.each=function(n,t){var e=this.id;if(arguments.length<2){var r=ks,u=Ss;Ss=e,P(this,function(t,r,u){ks=t.__transition__[e],n.call(t,t.__data__,r,u)}),ks=r,Ss=u}else P(this,function(r){var u=r.__transition__[e];(u.event||(u.event=Zo.dispatch("start","end"))).on(n,t)});return this},Cs.transition=function(){for(var n,t,e,r,u=this.id,i=++Ns,o=[],a=0,c=this.length;c>a;a++){o.push(n=[]);for(var t=this[a],s=0,l=t.length;l>s;s++)(e=t[s])&&(r=Object.create(e.__transition__[u]),r.delay+=r.duration,Po(e,s,i,r)),n.push(e)}return qo(o,i)},Zo.svg.axis=function(){function n(n){n.each(function(){var n,s=Zo.select(this),l=this.__chart__||e,f=this.__chart__=e.copy(),h=null==c?f.ticks?f.ticks.apply(f,a):f.domain():c,g=null==t?f.tickFormat?f.tickFormat.apply(f,a):wt:t,p=s.selectAll(".tick").data(h,f),v=p.enter().insert("g",".domain").attr("class","tick").style("opacity",ka),d=Zo.transition(p.exit()).style("opacity",ka).remove(),m=Zo.transition(p.order()).style("opacity",1),y=Ti(f),x=s.selectAll(".domain").data([0]),M=(x.enter().append("path").attr("class","domain"),Zo.transition(x));v.append("line"),v.append("text");var _=v.select("line"),b=m.select("line"),w=p.select("text").text(g),S=v.select("text"),k=m.select("text");switch(r){case"bottom":n=Uo,_.attr("y2",u),S.attr("y",Math.max(u,0)+o),b.attr("x2",0).attr("y2",u),k.attr("x",0).attr("y",Math.max(u,0)+o),w.attr("dy",".71em").style("text-anchor","middle"),M.attr("d","M"+y[0]+","+i+"V0H"+y[1]+"V"+i);break;case"top":n=Uo,_.attr("y2",-u),S.attr("y",-(Math.max(u,0)+o)),b.attr("x2",0).attr("y2",-u),k.attr("x",0).attr("y",-(Math.max(u,0)+o)),w.attr("dy","0em").style("text-anchor","middle"),M.attr("d","M"+y[0]+","+-i+"V0H"+y[1]+"V"+-i);break;case"left":n=jo,_.attr("x2",-u),S.attr("x",-(Math.max(u,0)+o)),b.attr("x2",-u).attr("y2",0),k.attr("x",-(Math.max(u,0)+o)).attr("y",0),w.attr("dy",".32em").style("text-anchor","end"),M.attr("d","M"+-i+","+y[0]+"H0V"+y[1]+"H"+-i);break;case"right":n=jo,_.attr("x2",u),S.attr("x",Math.max(u,0)+o),b.attr("x2",u).attr("y2",0),k.attr("x",Math.max(u,0)+o).attr("y",0),w.attr("dy",".32em").style("text-anchor","start"),M.attr("d","M"+i+","+y[0]+"H0V"+y[1]+"H"+i)}if(f.rangeBand){var E=f,A=E.rangeBand()/2;l=f=function(n){return E(n)+A}}else l.rangeBand?l=f:d.call(n,f);v.call(n,l),m.call(n,f)})}var t,e=Zo.scale.linear(),r=zs,u=6,i=6,o=3,a=[10],c=null;return n.scale=function(t){return arguments.length?(e=t,n):e},n.orient=function(t){return arguments.length?(r=t in Ls?t+"":zs,n):r},n.ticks=function(){return arguments.length?(a=arguments,n):a},n.tickValues=function(t){return arguments.length?(c=t,n):c},n.tickFormat=function(e){return arguments.length?(t=e,n):t},n.tickSize=function(t){var e=arguments.length;return e?(u=+t,i=+arguments[e-1],n):u},n.innerTickSize=function(t){return arguments.length?(u=+t,n):u},n.outerTickSize=function(t){return arguments.length?(i=+t,n):i},n.tickPadding=function(t){return arguments.length?(o=+t,n):o},n.tickSubdivide=function(){return arguments.length&&n},n};var zs="bottom",Ls={top:1,right:1,bottom:1,left:1};Zo.svg.brush=function(){function n(i){i.each(function(){var i=Zo.select(this).style("pointer-events","all").style("-webkit-tap-highlight-color","rgba(0,0,0,0)").on("mousedown.brush",u).on("touchstart.brush",u),o=i.selectAll(".background").data([0]);o.enter().append("rect").attr("class","background").style("visibility","hidden").style("cursor","crosshair"),i.selectAll(".extent").data([0]).enter().append("rect").attr("class","extent").style("cursor","move");var a=i.selectAll(".resize").data(p,wt);a.exit().remove(),a.enter().append("g").attr("class",function(n){return"resize "+n}).style("cursor",function(n){return Ts[n]}).append("rect").attr("x",function(n){return/[ew]$/.test(n)?-3:null}).attr("y",function(n){return/^[ns]/.test(n)?-3:null}).attr("width",6).attr("height",6).style("visibility","hidden"),a.style("display",n.empty()?"none":null);var l,f=Zo.transition(i),h=Zo.transition(o);c&&(l=Ti(c),h.attr("x",l[0]).attr("width",l[1]-l[0]),e(f)),s&&(l=Ti(s),h.attr("y",l[0]).attr("height",l[1]-l[0]),r(f)),t(f)})}function t(n){n.selectAll(".resize").attr("transform",function(n){return"translate("+l[+/e$/.test(n)]+","+f[+/^s/.test(n)]+")"})}function e(n){n.select(".extent").attr("x",l[0]),n.selectAll(".extent,.n>rect,.s>rect").attr("width",l[1]-l[0])}function r(n){n.select(".extent").attr("y",f[0]),n.selectAll(".extent,.e>rect,.w>rect").attr("height",f[1]-f[0])}function u(){function u(){32==Zo.event.keyCode&&(C||(x=null,z[0]-=l[1],z[1]-=f[1],C=2),y())}function p(){32==Zo.event.keyCode&&2==C&&(z[0]+=l[1],z[1]+=f[1],C=0,y())}function v(){var n=Zo.mouse(_),u=!1;M&&(n[0]+=M[0],n[1]+=M[1]),C||(Zo.event.altKey?(x||(x=[(l[0]+l[1])/2,(f[0]+f[1])/2]),z[0]=l[+(n[0]<x[0])],z[1]=f[+(n[1]<x[1])]):x=null),E&&d(n,c,0)&&(e(S),u=!0),A&&d(n,s,1)&&(r(S),u=!0),u&&(t(S),w({type:"brush",mode:C?"move":"resize"}))}function d(n,t,e){var r,u,a=Ti(t),c=a[0],s=a[1],p=z[e],v=e?f:l,d=v[1]-v[0];return C&&(c-=p,s-=d+p),r=(e?g:h)?Math.max(c,Math.min(s,n[e])):n[e],C?u=(r+=p)+d:(x&&(p=Math.max(c,Math.min(s,2*x[e]-r))),r>p?(u=r,r=p):u=p),v[0]!=r||v[1]!=u?(e?o=null:i=null,v[0]=r,v[1]=u,!0):void 0}function m(){v(),S.style("pointer-events","all").selectAll(".resize").style("display",n.empty()?"none":null),Zo.select("body").style("cursor",null),L.on("mousemove.brush",null).on("mouseup.brush",null).on("touchmove.brush",null).on("touchend.brush",null).on("keydown.brush",null).on("keyup.brush",null),N(),w({type:"brushend"})}var x,M,_=this,b=Zo.select(Zo.event.target),w=a.of(_,arguments),S=Zo.select(_),k=b.datum(),E=!/^(n|s)$/.test(k)&&c,A=!/^(e|w)$/.test(k)&&s,C=b.classed("extent"),N=I(),z=Zo.mouse(_),L=Zo.select(Wo).on("keydown.brush",u).on("keyup.brush",p);if(Zo.event.changedTouches?L.on("touchmove.brush",v).on("touchend.brush",m):L.on("mousemove.brush",v).on("mouseup.brush",m),S.interrupt().selectAll("*").interrupt(),C)z[0]=l[0]-z[0],z[1]=f[0]-z[1];else if(k){var T=+/w$/.test(k),q=+/^n/.test(k);M=[l[1-T]-z[0],f[1-q]-z[1]],z[0]=l[T],z[1]=f[q]}else Zo.event.altKey&&(x=z.slice());S.style("pointer-events","none").selectAll(".resize").style("display",null),Zo.select("body").style("cursor",b.style("cursor")),w({type:"brushstart"}),v()}var i,o,a=M(n,"brushstart","brush","brushend"),c=null,s=null,l=[0,0],f=[0,0],h=!0,g=!0,p=qs[0];return n.event=function(n){n.each(function(){var n=a.of(this,arguments),t={x:l,y:f,i:i,j:o},e=this.__chart__||t;this.__chart__=t,Ss?Zo.select(this).transition().each("start.brush",function(){i=e.i,o=e.j,l=e.x,f=e.y,n({type:"brushstart"})}).tween("brush:brush",function(){var e=gu(l,t.x),r=gu(f,t.y);return i=o=null,function(u){l=t.x=e(u),f=t.y=r(u),n({type:"brush",mode:"resize"})}}).each("end.brush",function(){i=t.i,o=t.j,n({type:"brush",mode:"resize"}),n({type:"brushend"})}):(n({type:"brushstart"}),n({type:"brush",mode:"resize"}),n({type:"brushend"}))})},n.x=function(t){return arguments.length?(c=t,p=qs[!c<<1|!s],n):c},n.y=function(t){return arguments.length?(s=t,p=qs[!c<<1|!s],n):s},n.clamp=function(t){return arguments.length?(c&&s?(h=!!t[0],g=!!t[1]):c?h=!!t:s&&(g=!!t),n):c&&s?[h,g]:c?h:s?g:null},n.extent=function(t){var e,r,u,a,h;return arguments.length?(c&&(e=t[0],r=t[1],s&&(e=e[0],r=r[0]),i=[e,r],c.invert&&(e=c(e),r=c(r)),e>r&&(h=e,e=r,r=h),(e!=l[0]||r!=l[1])&&(l=[e,r])),s&&(u=t[0],a=t[1],c&&(u=u[1],a=a[1]),o=[u,a],s.invert&&(u=s(u),a=s(a)),u>a&&(h=u,u=a,a=h),(u!=f[0]||a!=f[1])&&(f=[u,a])),n):(c&&(i?(e=i[0],r=i[1]):(e=l[0],r=l[1],c.invert&&(e=c.invert(e),r=c.invert(r)),e>r&&(h=e,e=r,r=h))),s&&(o?(u=o[0],a=o[1]):(u=f[0],a=f[1],s.invert&&(u=s.invert(u),a=s.invert(a)),u>a&&(h=u,u=a,a=h))),c&&s?[[e,u],[r,a]]:c?[e,r]:s&&[u,a])},n.clear=function(){return n.empty()||(l=[0,0],f=[0,0],i=o=null),n},n.empty=function(){return!!c&&l[0]==l[1]||!!s&&f[0]==f[1]},Zo.rebind(n,a,"on")};var Ts={n:"ns-resize",e:"ew-resize",s:"ns-resize",w:"ew-resize",nw:"nwse-resize",ne:"nesw-resize",se:"nwse-resize",sw:"nesw-resize"},qs=[["n","e","s","w","nw","ne","se","sw"],["e","w"],["n","s"],[]],Rs=Qa.format=ic.timeFormat,Ds=Rs.utc,Ps=Ds("%Y-%m-%dT%H:%M:%S.%LZ");Rs.iso=Date.prototype.toISOString&&+new Date("2000-01-01T00:00:00.000Z")?Ho:Ps,Ho.parse=function(n){var t=new Date(n);return isNaN(t)?null:t},Ho.toString=Ps.toString,Qa.second=Dt(function(n){return new nc(1e3*Math.floor(n/1e3))},function(n,t){n.setTime(n.getTime()+1e3*Math.floor(t))},function(n){return n.getSeconds()}),Qa.seconds=Qa.second.range,Qa.seconds.utc=Qa.second.utc.range,Qa.minute=Dt(function(n){return new nc(6e4*Math.floor(n/6e4))},function(n,t){n.setTime(n.getTime()+6e4*Math.floor(t))},function(n){return n.getMinutes()}),Qa.minutes=Qa.minute.range,Qa.minutes.utc=Qa.minute.utc.range,Qa.hour=Dt(function(n){var t=n.getTimezoneOffset()/60;return new nc(36e5*(Math.floor(n/36e5-t)+t))},function(n,t){n.setTime(n.getTime()+36e5*Math.floor(t))},function(n){return n.getHours()}),Qa.hours=Qa.hour.range,Qa.hours.utc=Qa.hour.utc.range,Qa.month=Dt(function(n){return n=Qa.day(n),n.setDate(1),n},function(n,t){n.setMonth(n.getMonth()+t)},function(n){return n.getMonth()}),Qa.months=Qa.month.range,Qa.months.utc=Qa.month.utc.range;var Us=[1e3,5e3,15e3,3e4,6e4,3e5,9e5,18e5,36e5,108e5,216e5,432e5,864e5,1728e5,6048e5,2592e6,7776e6,31536e6],js=[[Qa.second,1],[Qa.second,5],[Qa.second,15],[Qa.second,30],[Qa.minute,1],[Qa.minute,5],[Qa.minute,15],[Qa.minute,30],[Qa.hour,1],[Qa.hour,3],[Qa.hour,6],[Qa.hour,12],[Qa.day,1],[Qa.day,2],[Qa.week,1],[Qa.month,1],[Qa.month,3],[Qa.year,1]],Hs=Rs.multi([[".%L",function(n){return n.getMilliseconds()}],[":%S",function(n){return n.getSeconds()}],["%I:%M",function(n){return n.getMinutes()}],["%I %p",function(n){return n.getHours()}],["%a %d",function(n){return n.getDay()&&1!=n.getDate()}],["%b %d",function(n){return 1!=n.getDate()}],["%B",function(n){return n.getMonth()}],["%Y",we]]),Fs={range:function(n,t,e){return Zo.range(Math.ceil(n/e)*e,+t,e).map(Oo)},floor:wt,ceil:wt};js.year=Qa.year,Qa.scale=function(){return Fo(Zo.scale.linear(),js,Hs)};var Os=js.map(function(n){return[n[0].utc,n[1]]}),Ys=Ds.multi([[".%L",function(n){return n.getUTCMilliseconds()}],[":%S",function(n){return n.getUTCSeconds()}],["%I:%M",function(n){return n.getUTCMinutes()}],["%I %p",function(n){return n.getUTCHours()}],["%a %d",function(n){return n.getUTCDay()&&1!=n.getUTCDate()}],["%b %d",function(n){return 1!=n.getUTCDate()}],["%B",function(n){return n.getUTCMonth()}],["%Y",we]]);Os.year=Qa.year.utc,Qa.scale.utc=function(){return Fo(Zo.scale.linear(),Os,Ys)},Zo.text=St(function(n){return n.responseText}),Zo.json=function(n,t){return kt(n,"application/json",Yo,t)},Zo.html=function(n,t){return kt(n,"text/html",Io,t)},Zo.xml=St(function(n){return n.responseXML}),"function"==typeof define&&define.amd?define(Zo):"object"==typeof module&&module.exports&&(module.exports=Zo),this.d3=Zo}();
(function(angular, $, _) {
  "use strict";

  angular.module('crmDialog', CRM.angRequires('crmDialog'));
  angular.module('crmDialog').directive('crmDialogPopup', function(dialogService) {
    return {
      restrict: 'A',
      bindToController: {
        popupTpl: '@',
        popupName: '@crmDialogPopup',
        popupData: '<'
      },
      controller: function($scope, $element) {
        var ctrl = this;
        $element.on('click', function() {
          var options = CRM.utils.adjustDialogDefaults({
            autoOpen: false,
            title: _.trim($element.attr('title') || $element.text())
          });
          dialogService.open(ctrl.popupName, ctrl.popupTpl, ctrl.popupData || {}, options)
            .then(function(success) {
              $element.trigger('crmPopupFormSuccess');
            });
        });
      }
    };
  });
  angular.module('crmDialog').directive('crmDialog', function(dialogService) {
    return {
      restrict: 'A',
      controllerAs: '$dialog',
      controller: function($scope, $parse, $timeout) {
        var $dialog = this;
        $dialog.buttons = [];

        $dialog.close = function(result) {
          dialogService.close($dialog.name, result);
        };

        $dialog.cancel = function() {
          dialogService.cancel($dialog.name);
        };

        $dialog.loadButtons = function() {
          var buttons = [];
          angular.forEach($dialog.buttons, function (crmDialogButton) {
            var button = _.pick(crmDialogButton, ['icons', 'text', 'disabled']);
            button.click = function() {
              $scope.$apply(crmDialogButton.onClick);
            };
            buttons.push(button);
          });
          dialogService.setButtons($dialog.name, buttons);
        };

        $timeout(function() {
          $('.ui-dialog:last input:not([disabled]):not([type="submit"]):first').focus();
        });

      },
      link: function(scope, element, attrs, controller) {
        controller.name = attrs.crmDialog;
        scope[attrs.crmDialog] = controller;
      }
    };
  });
  angular.module('crmDialog').component('crmDialogButton', {
    bindings: {
      disabled: '<',
      icons: '<',
      text: '<',
      onClick: '&'
    },
    require: {
      crmDialog: '?^^crmDialog'
    },
    controller: function($scope) {
      var $ctrl = this;
      $ctrl.$onInit = function() {
        $ctrl.crmDialog.buttons.push(this);
        $scope.$watch('$ctrl.disabled', $ctrl.crmDialog.loadButtons);
        $scope.$watch('$ctrl.text', $ctrl.crmDialog.loadButtons);
        $scope.$watch('$ctrl.icons', $ctrl.crmDialog.loadButtons);
      };
    }
  });


angular.module('crmMailing', CRM.angRequires('crmMailing'));

  angular.module('crmMailing').config([
    '$routeProvider',
    function ($routeProvider) {
      $routeProvider.when('/mailing', {
        template: '<div></div>',
        controller: 'ListMailingsCtrl'
      });

      if (!CRM || !CRM.crmMailing) {
        return;
      }

      $routeProvider.when('/mailing/new', {
        template: '<p>' + ts('Initializing...') + '</p>',
        controller: 'CreateMailingCtrl',
        resolve: {
          selectedMail: function(crmMailingMgr) {
            var m = crmMailingMgr.create({
              template_type: CRM.crmMailing.templateTypes[0].name
            });
            return crmMailingMgr.save(m);
          }
        }
      });

      $routeProvider.when('/mailing/new/:templateType', {
        template: '<p>' + ts('Initializing...') + '</p>',
        controller: 'CreateMailingCtrl',
        resolve: {
          selectedMail: function($route, crmMailingMgr) {
            var m = crmMailingMgr.create({
              template_type: $route.current.params.templateType
            });
            return crmMailingMgr.save(m);
          }
        }
      });

      $routeProvider.when('/mailing/:id', {
        templateUrl: '~/crmMailing/EditMailingCtrl/base.html',
        controller: 'EditMailingCtrl',
        resolve: {
          selectedMail: function($route, crmMailingMgr) {
            return crmMailingMgr.get($route.current.params.id);
          },
          mailingFields: function(crmMetadata) {
            return crmMetadata.getFields('Mailing');
          },
          attachments: function($route, CrmAttachments) {
            var attachments = new CrmAttachments(function () {
              return {entity_table: 'civicrm_mailing', entity_id: $route.current.params.id};
            });
            return attachments.load();
          }
        }
      });
    }
  ]);


angular.module('crmMailing').directive('crmMailingBlockApprove', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockApprove', '~/crmMailing/BlockApprove.html');
  });

angular.module('crmMailing').directive('crmMailingBlockHeaderFooter', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockHeaderFooter', '~/crmMailing/BlockHeaderFooter.html');
  });

angular.module('crmMailing').directive('crmMailingBlockMailing', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockMailing', '~/crmMailing/BlockMailing.html');
  });

angular.module('crmMailing').directive('crmMailingBlockPreview', function(crmUiHelp) {
    return {
      templateUrl: '~/crmMailing/BlockPreview.html',
      link: function(scope, elm, attr) {
        scope.$watch(attr.crmMailing, function(newValue) {
          scope.mailing = newValue;
        });
        scope.crmMailingConst = CRM.crmMailing;
        scope.ts = CRM.ts(null);
        scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});
        scope.testContact = {email: CRM.crmMailing.defaultTestEmail};
        scope.testGroup = {gid: null};

        scope.doPreview = function(mode) {
          scope.$eval(attr.onPreview, {
            preview: {mode: mode}
          });
        };
        scope.doSend = function doSend(recipient) {
          recipient = JSON.parse(JSON.stringify(recipient).replace(/\,\s/g, ','));
          scope.$eval(attr.onSend, {
            preview: {recipient: recipient}
          });
        };

        scope.previewTestGroup = function(e) {
          var $dialog = $(this);
          $dialog.html('<div class="crm-loading-element"></div>').parent().find('button[data-op=yes]').prop('disabled', true);
          CRM.api3({
            contact: ['contact', 'get', {group: scope.testGroup.gid, options: {limit: 0}, return: 'display_name,email'}],
            group: ['group', 'getsingle', {id: scope.testGroup.gid, return: 'title'}]
          }).done(function(data) {
            $dialog.dialog('option', 'title', ts('Send to %1', {1: data.group.title}));
            var count = 0,
            markup = '<ol>';
            _.each(data.contact.values, function(row) {
              if (row.email) {
                count++;
                markup += '<li>' + row.display_name + ' - ' + row.email + '</li>';
              }
            });
            markup += '</ol>';
            markup = '<h4>' + ts('A test message will be sent to %1 people:', {1: count}) + '</h4>' + markup;
            if (!count) {
              markup = '<div class="messages status"><i class="crm-i fa-exclamation-triangle" aria-hidden="true"></i> ' +
              (data.contact.count ? ts('None of the contacts in this group have an email address.') : ts('Group is empty.')) +
              '</div>';
            }
            $dialog
              .html(markup)
              .trigger('crmLoad')
              .parent().find('button[data-op=yes]').prop('disabled', !count);
          });
        };
      }
    };
  });


angular.module('crmMailing').directive('crmMailingBlockPublication', function (crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockPublication', '~/crmMailing/BlockPublication.html');
  });

angular.module('crmMailing').directive('crmMailingBlockRecipients', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockRecipients', '~/crmMailing/BlockRecipients.html');
  });

angular.module('crmMailing').directive('crmMailingBlockResponses', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockResponses', '~/crmMailing/BlockResponses.html');
  });

angular.module('crmMailing').directive('crmMailingBlockReview', function (crmMailingPreviewMgr) {
    return {
      scope: {
        crmMailing: '@',
        crmMailingAttachments: '@'
      },
      templateUrl: '~/crmMailing/BlockReview.html',
      link: function (scope, elm, attr) {
        scope.$parent.$watch(attr.crmMailing, function(newValue){
          scope.mailing = newValue;
        });
        scope.$parent.$watch(attr.crmMailingAttachments, function(newValue){
          scope.attachments = newValue;
        });
        scope.crmMailingConst = CRM.crmMailing;
        scope.ts = CRM.ts(null);
        scope.previewMailing = function previewMailing(mailing, mode) {
          return crmMailingPreviewMgr.preview(mailing, mode);
        };
      }
    };
  });


angular.module('crmMailing').directive('crmMailingBlockSchedule', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockSchedule', '~/crmMailing/BlockSchedule.html');
  });

angular.module('crmMailing').directive('crmMailingBlockSummary', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockSummary', '~/crmMailing/BlockSummary.html');
  });

angular.module('crmMailing').directive('crmMailingBlockTemplates', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockTemplates', '~/crmMailing/BlockTemplates.html');
  });

angular.module('crmMailing').directive('crmMailingBlockTracking', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBlockTracking', '~/crmMailing/BlockTracking.html');
  });

angular.module('crmMailing').directive('crmMailingBodyHtml', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBodyHtml', '~/crmMailing/BodyHtml.html');
  });

angular.module('crmMailing').directive('crmMailingBodyText', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMailingBodyText', '~/crmMailing/BodyText.html');
  });

angular.module('crmMailing').controller('CreateMailingCtrl', function EditMailingCtrl($scope, selectedMail, $location) {
    $location.path("/mailing/" + selectedMail.id);
    $location.replace();
  });


angular.module('crmMailing').controller('EditMailingCtrl', function EditMailingCtrl($scope, selectedMail, $location, crmMailingMgr, crmStatus, attachments, crmMailingPreviewMgr, crmBlocker, CrmAutosaveCtrl, $timeout, crmUiHelp, mailingFields) {
    var APPROVAL_STATUSES = {'Approved': 1, 'Rejected': 2, 'None': 3};

    $scope.mailing = selectedMail;
    $scope.attachments = attachments;
    $scope.crmMailingConst = CRM.crmMailing;
    $scope.checkPerm = CRM.checkPerm;
    $scope.mailingFields = mailingFields;

    var ts = $scope.ts = CRM.ts(null);
    $scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});
    var block = $scope.block = crmBlocker();
    var myAutosave = null;

    var templateTypes = _.where(CRM.crmMailing.templateTypes, {name: selectedMail.template_type});
    if (!templateTypes[0]) throw 'Unrecognized template type: ' + selectedMail.template_type;
    $scope.mailingEditorUrl = templateTypes[0].editorUrl;

    $scope.isSubmitted = function isSubmitted() {
      return _.size($scope.mailing.jobs) > 0;
    };
    $scope.approve = function approve(status, options) {
      $scope.mailing.approval_status_id = APPROVAL_STATUSES[status];
      return myAutosave.suspend($scope.submit(options));
    };
    $scope.previewMailing = function previewMailing(mailing, mode) {
      return crmMailingPreviewMgr.preview(mailing, mode);
    };
    $scope.sendTest = function sendTest(mailing, attachments, recipient) {
      var savePromise = crmMailingMgr.save(mailing)
        .then(function() {
          return attachments.save();
        });
      return block(crmStatus({start: ts('Saving...'), success: ''}, savePromise)
        .then(function() {
          crmMailingPreviewMgr.sendTest(mailing, recipient);
        }));
    };
    $scope.submit = function submit(options) {
      options = options || {};
      if (block.check()) {
        return;
      }

      var promise = crmMailingMgr.save($scope.mailing)
          .then(function() {
            return $scope.attachments.save();
          })
          .then(function() {
            return crmMailingMgr.submit($scope.mailing);
          })
          .then(function() {
            if (!options.stay) {
              $scope.leave('scheduled');
            }
          })
        ;
      return block(crmStatus({start: ts('Submitting...'), success: ts('Submitted')}, promise));
    };
    $scope.save = function save() {
      return block(crmStatus(null,
        crmMailingMgr
          .save($scope.mailing)
          .then(function() {
            return $scope.attachments.save();
          })
      ));
    };
    $scope.delete = function cancel() {
      return block(crmStatus({start: ts('Deleting...'), success: ts('Deleted')},
        crmMailingMgr.delete($scope.mailing)
          .then(function() {
            $scope.leave('unscheduled');
          })
      ));
    };
    $scope.leave = function leave(listingScreen) {
      switch (listingScreen) {
        case 'archive':
          window.location = CRM.url('civicrm/mailing/browse/archived', {
            reset: 1
          });
          break;
        case 'scheduled':
          window.location = CRM.url('civicrm/mailing/browse/scheduled', {
            reset: 1,
            scheduled: 'true'
          });
          break;
        case 'unscheduled':
        /* falls through */
        default:
          window.location = CRM.url('civicrm/mailing/browse/unscheduled', {
            reset: 1,
            scheduled: 'false'
          });
      }
    };

    myAutosave = new CrmAutosaveCtrl({
      save: $scope.save,
      saveIf: function() {
        return true;
      },
      model: function() {
        var mailing = angular.copy($scope.mailing);
        mailing.modified_date = undefined;
        return [mailing, $scope.attachments.getAutosaveSignature()];
      },
      form: function() {
        return $scope.crmMailing;
      }
    });
    $timeout(myAutosave.start);
    $scope.$on('$destroy', myAutosave.stop);
  });


angular.module('crmMailing').controller('EditRecipCtrl', function EditRecipCtrl($scope, dialogService, crmApi, crmMailingMgr, $q, crmMetadata, crmStatus, crmMailingCache) {
    var RECIPIENTS_DEBOUNCE_MS = 100;
    var SETTING_DEBOUNCE_MS = 5000;
    var RECIPIENTS_PREVIEW_LIMIT = 50;

    var ts = $scope.ts = CRM.ts(null);

    $scope.isMailingList = function isMailingList(group) {
      var GROUP_TYPE_MAILING_LIST = '2';
      return _.contains(group.group_type, GROUP_TYPE_MAILING_LIST);
    };

    $scope.recipients = null;
    $scope.outdated = null;
    $scope.permitRecipientRebuild = null;

    $scope.getRecipientsEstimate = function() {
      var ts = $scope.ts;
      if ($scope.recipients === null) {
        return ts('Estimating...');
      }
      if ($scope.recipients === 0) {
        return ts('Estimate recipient count');
      }
      return ts('Refresh recipient count');
    };

    $scope.getRecipientCount = function() {
      var ts = $scope.ts;
      if ($scope.recipients === 0) {
        return ts('No Recipients');
      }
      else if ($scope.recipients > 0) {
        return ts('~%1 recipients', {1 : $scope.recipients});
      }
      else if ($scope.outdated) {
        return ts('(unknown)');
      }
      else {
        return $scope.permitRecipientRebuild ? ts('(unknown)') : ts('Estimating...');
      }
    };
    var refreshRecipients = _.debounce(function() {
      $scope.$apply(function() {
        if (!$scope.mailing) {
          return;
        }
        crmMailingMgr.previewRecipientCount($scope.mailing, crmMailingCache, !$scope.permitRecipientRebuild).then(function(recipients) {
          $scope.outdated = ($scope.permitRecipientRebuild && _.difference($scope.mailing.recipients, crmMailingCache.get('mailing-' + $scope.mailing.id + '-recipient-params')) !== 0);
          $scope.recipients = recipients;
        });
      });
    }, RECIPIENTS_DEBOUNCE_MS);
    $scope.$watchCollection("mailing.dedupe_email", refreshRecipients);
    $scope.$watchCollection("mailing.location_type_id", refreshRecipients);
    $scope.$watchCollection("mailing.email_selection_method", refreshRecipients);
    $scope.$watchCollection("mailing.recipients.groups.include", refreshRecipients);
    $scope.$watchCollection("mailing.recipients.groups.exclude", refreshRecipients);
    $scope.$watchCollection("mailing.recipients.mailings.include", refreshRecipients);
    $scope.$watchCollection("mailing.recipients.mailings.exclude", refreshRecipients);
    var refreshSetting = _.debounce(function() {
      $scope.$apply(function() {
        crmApi('Setting', 'getvalue', {"name": 'auto_recipient_rebuild', "return": "value"}).then(function(response) {
          $scope.permitRecipientRebuild = (response.result === 0);
        });
      });
    }, SETTING_DEBOUNCE_MS);
    $scope.$watchCollection("permitRecipientRebuild", refreshSetting);

    $scope.previewRecipients = function previewRecipients() {
      var model = {
        count: $scope.recipients,
        sample: crmMailingCache.get('mailing-' + $scope.mailing.id + '-recipient-list'),
        sampleLimit: RECIPIENTS_PREVIEW_LIMIT
      };
      var options = CRM.utils.adjustDialogDefaults({
        width: '40%',
        autoOpen: false,
        title: ts('Preview (%1)', {1: $scope.getRecipientCount()})
      });
      if ($scope.recipients !== 0 && !$scope.outdated) {
        if (!_.isEmpty(model.sample)) {
          dialogService.open('recipDialog', '~/crmMailing/PreviewRecipCtrl.html', model, options);
        }
        else {
          return crmStatus({start: ts('Previewing...'), success: ''}, crmMailingMgr.previewRecipients($scope.mailing, RECIPIENTS_PREVIEW_LIMIT).then(function(recipients) {
            model.sample = recipients;
            dialogService.open('recipDialog', '~/crmMailing/PreviewRecipCtrl.html', model, options);
          }));
        }
      }
    };

    $scope.rebuildRecipients = function rebuildRecipients() {
      $scope.recipients = null;
      return crmMailingMgr.previewRecipientCount($scope.mailing, crmMailingCache, true).then(function(recipients) {
        $scope.outdated = (recipients === 0) ? true : false;
        $scope.recipients = recipients;
      });
    };
    $scope.editOptions = function editOptions(mailing) {
      var options = CRM.utils.adjustDialogDefaults({
        autoOpen: false,
        width: '40%',
        height: 'auto',
        title: ts('Edit Options')
      });
      $q.when(crmMetadata.getFields('Mailing')).then(function(fields) {
        var model = {
          fields: fields,
          mailing: mailing
        };
        dialogService.open('previewComponentDialog', '~/crmMailing/EditRecipOptionsDialogCtrl.html', model, options);
      });
    };
  });


angular.module('crmMailing').controller('EditRecipOptionsDialogCtrl', function EditRecipOptionsDialogCtrl($scope, crmUiHelp) {
    $scope.ts = CRM.ts(null);
    $scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});
  });


angular.module('crmMailing').controller('EditUnsubGroupCtrl', function EditUnsubGroupCtrl($scope) {
    var mandatoryIds = [];

    $scope.isUnsubGroupRequired = function isUnsubGroupRequired(mailing) {
      if (!_.isEmpty(CRM.crmMailing.groupNames)) {
        _.each(CRM.crmMailing.groupNames, function(grp) {
          if (grp.is_hidden == "1") {
            mandatoryIds.push(parseInt(grp.id));
          }
        });
        return _.intersection(mandatoryIds, mailing.recipients.groups.include).length > 0;
      }
    };
  });


angular.module('crmMailing').controller('EmailAddrCtrl', function EmailAddrCtrl($scope, crmFromAddresses, crmUiAlert) {
    var ts = CRM.ts(null);

    function changeAlert(winnerField, loserField) {
      crmUiAlert({
        title: ts('Conflict'),
        text: ts('The "%1" option conflicts with the "%2" option. The "%2" option has been disabled.', {
          1: winnerField,
          2: loserField
        })
      });
    }

    $scope.crmFromAddresses = crmFromAddresses;
    $scope.checkReplyToChange = function checkReplyToChange(mailing) {
      if (!_.isEmpty(mailing.replyto_email) && mailing.override_verp == '0') {
        mailing.override_verp = '1';
        changeAlert(ts('Reply-To'), ts('Track Replies'));
      }
    };
    $scope.checkVerpChange = function checkVerpChange(mailing) {
      if (!_.isEmpty(mailing.replyto_email) && mailing.override_verp == '0') {
        mailing.replyto_email = '';
        changeAlert(ts('Track Replies'), ts('Reply-To'));
      }
    };
  });


var lastEmailTokenAlert = null;
  angular.module('crmMailing').controller('EmailBodyCtrl', function EmailBodyCtrl($scope, crmMailingMgr, crmUiAlert, $timeout) {
    var ts = CRM.ts(null);
    $scope.hasAllTokens = function hasAllTokens(mailing, field) {
      return _.isEmpty(crmMailingMgr.findMissingTokens(mailing, field));
    };
    $scope.checkTokens = function checkTokens(mailing, field, insertEvent) {
      if (lastEmailTokenAlert) {
        lastEmailTokenAlert.close();
      }
      var missing, insertable;
      if (field == '*') {
        insertable = false;
        missing = angular.extend({},
          crmMailingMgr.findMissingTokens(mailing, 'body_html'),
          crmMailingMgr.findMissingTokens(mailing, 'body_text')
        );
      }
      else {
        insertable = !_.isEmpty(insertEvent);
        missing = crmMailingMgr.findMissingTokens(mailing, field);
      }
      if (!_.isEmpty(missing)) {
        lastEmailTokenAlert = crmUiAlert({
          type: 'error',
          title: ts('Required tokens'),
          templateUrl: '~/crmMailing/EmailBodyCtrl/tokenAlert.html',
          scope: angular.extend($scope.$new(), {
            insertable: insertable,
            insertToken: function(token) {
              $timeout(function() {
                $scope.$broadcast(insertEvent, '{' + token + '}');
                $timeout(function() {
                  checkTokens(mailing, field, insertEvent);
                });
              });
            },
            missing: missing
          })
        });
      }
    };
  });


angular.module('crmMailing').directive('crmMailingFromAddress', function(crmFromAddresses) {
    return {
      link: function(scope, element, attrs) {
        var placeholder = attrs.crmMailingFromAddress;
        var mailing = null;
        scope.$watch(attrs.crmMailing, function(newValue) {
          mailing = newValue;
          scope[placeholder] = {
            label: crmFromAddresses.getByAuthorEmail(mailing.from_name, mailing.from_email, true).label
          };
        });
        scope.$watch(placeholder + '.label', function(newValue) {
          var addr = crmFromAddresses.getByLabel(newValue);
          mailing.from_name = addr.author;
          mailing.from_email = addr.email;
          if (!CRM.crmMailing.enableReplyTo) {
            mailing.replyto_email = crmFromAddresses.getByAuthorEmail(mailing.from_name, mailing.from_email, true).label;
          }
        });
      }
    };
  });

angular.module('crmMailing').controller('ListMailingsCtrl', ['crmLegacy', 'crmNavigator', function ListMailingsCtrl(crmLegacy, crmNavigator) {
    var new_url = crmLegacy.url('civicrm/mailing/browse/unscheduled', {reset: 1, scheduled: 'false'});
    crmNavigator.redirect(new_url);
  }]);


angular.module('crmMailing').controller('MsgTemplateCtrl', function MsgTemplateCtrl($scope, crmMsgTemplates, dialogService) {
    var ts = $scope.ts = CRM.ts(null);
    $scope.crmMsgTemplates = crmMsgTemplates;
    $scope.checkPerm = CRM.checkPerm;
    $scope.saveTemplate = function saveTemplate(mailing) {
      var model = {
        selected_id: mailing.msg_template_id,
        tpl: {
          msg_title: '',
          msg_subject: mailing.subject,
          msg_text: mailing.body_text,
          msg_html: mailing.body_html
        }
      };
      var options = CRM.utils.adjustDialogDefaults({
        autoOpen: false,
        height: 'auto',
        width: '40%',
        title: ts('Save Template')
      });
      return dialogService.open('saveTemplateDialog', '~/crmMailing/SaveMsgTemplateDialogCtrl.html', model, options)
        .then(function(item) {
          mailing.msg_template_id = item.id;
          return item;
        });
    };
    $scope.loadTemplate = function loadTemplate(mailing, id) {
      return crmMsgTemplates.get(id).then(function(tpl) {
        mailing.msg_template_id = tpl.id;
        mailing.subject = tpl.msg_subject;
        mailing.body_text = tpl.msg_text;
        mailing.body_html = tpl.msg_html;
      });
    };
  });


angular.module('crmMailing').controller('PreviewComponentCtrl', function PreviewComponentCtrl($scope, dialogService) {
    var ts = $scope.ts = CRM.ts(null);

    $scope.previewComponent = function previewComponent(title, componentId) {
      var component = _.where(CRM.crmMailing.headerfooterList, {id: "" + componentId});
      if (!component || !component[0]) {
        CRM.alert(ts('Invalid component ID (%1)', {
          1: componentId
        }));
        return;
      }
      var options = CRM.utils.adjustDialogDefaults({
        autoOpen: false,
        title: title // component[0].name
      });
      dialogService.open('previewComponentDialog', '~/crmMailing/PreviewComponentDialogCtrl.html', component[0], options);
    };
  });


angular.module('crmMailing').controller('PreviewComponentDialogCtrl', function PreviewComponentDialogCtrl($scope) {
    $scope.ts = CRM.ts(null);
  });


angular.module('crmMailing').controller('PreviewMailingDialogCtrl', function PreviewMailingDialogCtrl($scope) {
    $scope.ts = CRM.ts(null);
  });


angular.module('crmMailing').controller('PreviewRecipCtrl', function($scope) {
    $scope.ts = CRM.ts(null);
  });


function parseYmdHms(d) {
    var parts = d.split(/[\-: ]/);
    return new Date(parts[0], parts[1]-1, parts[2], parts[3], parts[4], parts[5]);
  }

  function isDateBefore(tgt, cutoff, tolerance) {
    var ad = parseYmdHms(tgt), bd = parseYmdHms(cutoff);
    return  ad < bd-tolerance;
  }
  angular.module('crmMailing').directive('crmMailingRadioDate', function(crmUiAlert) {
    return {
      require: 'ngModel',
      link: function($scope, element, attrs, ngModel) {
        var lastAlert = null;

        var schedule = $scope[attrs.crmMailingRadioDate] = {
          mode: 'now',
          datetime: ''
        };

        ngModel.$render = function $render() {
          var sched = ngModel.$viewValue;
          if (!_.isEmpty(sched)) {
            schedule.mode = 'at';
            schedule.datetime = sched;
          }
          else {
            schedule.mode = 'now';
            schedule.datetime = '';
          }
        };

        var updateParent = (function() {
          switch (schedule.mode) {
            case 'now':
              ngModel.$setViewValue(null);
              schedule.datetime = '';
              break;
            case 'at':
              schedule.datetime = schedule.datetime || '?';
              ngModel.$setViewValue(schedule.datetime);
              break;
            default:
              throw 'Unrecognized schedule mode: ' + schedule.mode;
          }
        });

        element
          .on('click', ':radio[value=at]', function() {
            $('.crm-form-date', element).focus();
          })
          .on('change', '.crm-hidden-date', function(e, context) {
            if (context === 'userInput' && $(this).val() === '' && $(this).siblings('.crm-form-date').val().length) {
              schedule.mode = 'at';
              schedule.datetime = '?';
            } else {
              var d = new Date(),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear(),
                hours = '' + d.getHours(),
                minutes = '' + d.getMinutes(),
                submittedDate = $(this).val();
              if (month.length < 2) month = '0' + month;
              if (day.length < 2) day = '0' + day;
              if (hours.length < 2) hours = '0' + hours;
              if (minutes.length < 2) minutes = '0' + minutes;
              var
                date = [year, month, day].join('-'),
                time = [hours, minutes, "00"].join(':'),
                currentDate = date + ' ' + time,
                isInPast = (submittedDate.length && submittedDate.match(/^[0-9\-]+ [0-9\:]+$/) && isDateBefore(submittedDate, currentDate, 4*60*60*1000));
              ngModel.$setValidity('dateTimeInThePast', !isInPast);
              if (lastAlert && lastAlert.isOpen) {
                lastAlert.close();
              }
              if (isInPast) {
                lastAlert = crmUiAlert({
                  text: ts('The scheduled date and time is in the past'),
                  title: ts('Error')
                });
              }
            }
          });

        $scope.$watch(attrs.crmMailingRadioDate + '.mode', updateParent);
        $scope.$watch(attrs.crmMailingRadioDate + '.datetime', function(newValue, oldValue) {
          if (typeof oldValue === 'undefined') {
            oldValue = '';
          }
          if (typeof newValue === 'undefined') {
            newValue = '';
          }
          if (oldValue !== newValue) {
            if (_.isEmpty(newValue)) {
              schedule.mode = 'now';
            }
            else {
              schedule.mode = 'at';
            }
          }
          updateParent();
        });
      }
    };
  });

angular.module('crmMailing').directive('crmMailingRecipients', function(crmUiAlert) {
    return {
      restrict: 'AE',
      require: 'ngModel',
      scope: {
        ngRequired: '@'
      },
      link: function(scope, element, attrs, ngModel) {
        scope.recips = ngModel.$viewValue;
        scope.groups = scope.$parent.$eval(attrs.crmAvailGroups);
        scope.mailings = scope.$parent.$eval(attrs.crmAvailMailings);
        refreshMandatory();

        var ts = scope.ts = CRM.ts(null);
        scope.parseDate = function(date) {
          if (!angular.isString(date)) {
            return date;
          }
          var p = date.split(/[\- :]/);
          return new Date(parseInt(p[0]), parseInt(p[1]) - 1, parseInt(p[2]), parseInt(p[3]), parseInt(p[4]), parseInt(p[5]));
        };
        function arrayRemove(array, value) {
          var idx = array.indexOf(value);
          if (idx >= 0) {
            array.splice(idx, 1);
          }
        }
        function convertValueToObj(id) {
          var a = id.split(" ");
          return {entity_id: parseInt(a[0]), entity_type: a[1], mode: a[2]};
        }
        function convertMailingToValues(recipients) {
          var r = [];
          angular.forEach(recipients.groups.include, function(v) {
            r.push(v + " civicrm_group include");
          });
          angular.forEach(recipients.groups.exclude, function(v) {
            r.push(v + " civicrm_group exclude");
          });
          angular.forEach(recipients.mailings.include, function(v) {
            r.push(v + " civicrm_mailing include");
          });
          angular.forEach(recipients.mailings.exclude, function(v) {
            r.push(v + " civicrm_mailing exclude");
          });
          return r;
        }

        function refreshMandatory() {
          if (ngModel.$viewValue && ngModel.$viewValue.groups) {
            scope.mandatoryGroups = _.filter(scope.$parent.$eval(attrs.crmMandatoryGroups), function(grp) {
              return _.contains(ngModel.$viewValue.groups.include, parseInt(grp.id));
            });
            scope.mandatoryIds = _.map(_.pluck(scope.$parent.$eval(attrs.crmMandatoryGroups), 'id'), function(n) {
              return parseInt(n);
            });
          }
          else {
            scope.mandatoryGroups = [];
            scope.mandatoryIds = [];
          }
        }

        function isMandatory(grpId) {
          return _.contains(scope.mandatoryIds, parseInt(grpId));
        }

        var refreshUI = ngModel.$render = function refresuhUI() {
          scope.recips = ngModel.$viewValue;
          if (ngModel.$viewValue) {
            $(element).select2('val', convertMailingToValues(ngModel.$viewValue));
            validate();
            refreshMandatory();
          }
        };
        function formatItem(item) {
          if (!item.id) {
            return item.text;
          }
          var option = convertValueToObj(item.id);
          var icon = (option.entity_type === 'civicrm_mailing') ? 'fa-envelope' : 'fa-users';
          var smartGroupMarker = item.is_smart ? '* ' : '';
          var spanClass = (option.mode == 'exclude') ? 'crmMailing-exclude' : 'crmMailing-include';
          if (option.entity_type != 'civicrm_mailing' && isMandatory(option.entity_id)) {
            spanClass = 'crmMailing-mandatory';
          }
          return '<i class="crm-i '+icon+'"></i> <span class="' + spanClass + '">' + smartGroupMarker + item.text + '</span>';
        }

        function validate() {
          if (scope.$parent.$eval(attrs.ngRequired)) {
            var empty = (_.isEmpty(ngModel.$viewValue.groups.include) && _.isEmpty(ngModel.$viewValue.mailings.include));
            ngModel.$setValidity('empty', !empty);
          }
          else {
            ngModel.$setValidity('empty', true);
          }
        }

        var rcpAjaxState = {
         input: '',
         entity: 'civicrm_group',
         type: 'include',
         page_n: 0,
         page_i: 0,
        };

        $(element).select2({
          width: '36em',
          dropdownAutoWidth: true,
          placeholder: "Groups or Past Recipients",
          formatResult: formatItem,
          formatSelection: formatItem,
          escapeMarkup: function(m) {
            return m;
          },
          multiple: true,
          initSelection: function(el, cb) {
            var values = el.val().split(',');

            var gids = [];
            var mids = [];

            for (var i = 0; i < values.length; i++) {
              var dv = convertValueToObj(values[i]);
              if (dv.entity_type == 'civicrm_group') {
                gids.push(dv.entity_id);
              }
              else if (dv.entity_type == 'civicrm_mailing') {
                mids.push(dv.entity_id);
              }
            }
            if (gids.length === 0) {
              gids.push(0);
            }
            if (mids.length === 0) {
              mids.push(0);
            }

            CRM.api3('Group', 'getlist', { params: { id: { IN: gids }, options: { limit: 0 } }, extra: ["is_hidden"] }).then(
              function(glist) {
                CRM.api3('Mailing', 'getlist', { params: { id: { IN: mids }, options: { limit: 0 } } }).then(
                  function(mlist) {
                    var datamap = [];

                    var groupNames = [];
                    var civiMails = [];

                    $(glist.values).each(function (idx, group) {
                      var key = group.id + ' civicrm_group include';

                      groupNames.push({id: parseInt(group.id), title: group.label, is_hidden: group.extra.is_hidden});
                      if (values.indexOf(key) >= 0) {
                        datamap.push({id: key, text: group.label});
                      }

                      key = group.id + ' civicrm_group exclude';
                      if (values.indexOf(key) >= 0) {
                        datamap.push({id: key, text: group.label});
                      }
                    });

                    $(mlist.values).each(function (idx, group) {
                      var key = group.id + ' civicrm_mailing include';
                      civiMails.push({id: parseInt(group.id), name: group.label});

                      if (values.indexOf(key) >= 0) {
                        datamap.push({id: key, text: group.label});
                      }

                      key = group.id + ' civicrm_mailing exclude';
                      if (values.indexOf(key) >= 0) {
                        datamap.push({id: key, text: group.label});
                      }
                    });

                    scope.$parent.crmMailingConst.groupNames = groupNames;
                    scope.$parent.crmMailingConst.civiMails = civiMails;

                    refreshMandatory();

                    cb(datamap);
                  });
              });
          },
          ajax: {
            url: CRM.url('civicrm/ajax/rest'),
            quietMillis: 300,
            data: function(input, page_num) {
              if (page_num <= 1) {
                rcpAjaxState = {
                  input: input,
                  entity: 'civicrm_group',
                  type: 'include',
                  page_n: 0,
                };
              }

              rcpAjaxState.page_i = page_num - rcpAjaxState.page_n;
              var filterParams = {};
              switch(rcpAjaxState.entity) {
              case 'civicrm_group':
                filterParams = { is_hidden: 0, is_active: 1, group_type: {"LIKE": "%2%"} };
                break;

              case 'civicrm_mailing':
                filterParams = { is_hidden: 0, is_active: 1 };
                break;
              }
              var params = {
                input: input,
                page_num: rcpAjaxState.page_i,
                params: filterParams,
              };

              if('civicrm_mailing' === rcpAjaxState.entity) {
                params["api.MailingRecipients.getcount"] = {};
              }
              else if ('civicrm_group' === rcpAjaxState.entity) {
                params.extra = ["saved_search_id"];
              }

              return params;
            },
            transport: function(params) {
              switch(rcpAjaxState.entity) {
              case 'civicrm_group':
                CRM.api3('Group', 'getlist', params.data).then(params.success, params.error);
                break;

              case 'civicrm_mailing':
                params.data.params.options = { sort: "is_archived asc, scheduled_date desc" };
                CRM.api3('Mailing', 'getlist', params.data).then(params.success, params.error);
                break;
              }
            },
            results: function(data) {
              var results = {
                children: $.map(data.values, function(obj) {
                  if('civicrm_mailing' === rcpAjaxState.entity) {
                    return obj["api.MailingRecipients.getcount"] > 0 ? {   id: obj.id + ' ' + rcpAjaxState.entity + ' ' + rcpAjaxState.type,
                               text: obj.label } : '';
                  }
                  else {
                    return {   id: obj.id + ' ' + rcpAjaxState.entity + ' ' + rcpAjaxState.type, text: obj.label,
                              is_smart: (!_.isEmpty(obj.extra.saved_search_id)) };
                  }
                })
              };

              if (rcpAjaxState.page_i == 1 && data.count && results.children.length > 0) {
                results.text = ts((rcpAjaxState.type == 'include'? 'Include ' : 'Exclude ') +
                  (rcpAjaxState.entity == 'civicrm_group'? 'Group' : 'Mailing'));
              }

              var more = data.more_results || !(rcpAjaxState.entity == 'civicrm_mailing' && rcpAjaxState.type == 'exclude');

              if (more && !data.more_results) {
                if (rcpAjaxState.type == 'include') {
                  rcpAjaxState.type = 'exclude';
                } else {
                 rcpAjaxState.type = 'include';
                 rcpAjaxState.entity = 'civicrm_mailing';
                }
                rcpAjaxState.page_n += rcpAjaxState.page_i;
              }

              return { more: more, results: [ results ] };
            },
          },
        });

        $(element).on('select2-selecting', function(e) {
          var option = convertValueToObj(e.val);
          var typeKey = option.entity_type == 'civicrm_mailing' ? 'mailings' : 'groups';
          if (option.mode == 'exclude') {
            ngModel.$viewValue[typeKey].exclude.push(option.entity_id);
            arrayRemove(ngModel.$viewValue[typeKey].include, option.entity_id);
          }
          else {
            ngModel.$viewValue[typeKey].include.push(option.entity_id);
            arrayRemove(ngModel.$viewValue[typeKey].exclude, option.entity_id);
          }
          scope.$apply();
          $(element).select2('close');
          validate();
          e.preventDefault();
        });

        $(element).on("select2-removing", function(e) {
          var option = convertValueToObj(e.val);
          var typeKey = option.entity_type == 'civicrm_mailing' ? 'mailings' : 'groups';
          if (typeKey == 'groups' && isMandatory(option.entity_id)) {
            crmUiAlert({
              text: ts('This mailing was generated based on search results. The search results cannot be removed.'),
              title: ts('Required')
            });
            e.preventDefault();
            return;
          }
          scope.$parent.$apply(function() {
            arrayRemove(ngModel.$viewValue[typeKey][option.mode], option.entity_id);
          });
          validate();
          e.preventDefault();
        });

        scope.$watchCollection("recips.groups.include", refreshUI);
        scope.$watchCollection("recips.groups.exclude", refreshUI);
        scope.$watchCollection("recips.mailings.include", refreshUI);
        scope.$watchCollection("recips.mailings.exclude", refreshUI);
        setTimeout(refreshUI, 50);

        scope.$watchCollection(attrs.crmAvailGroups, function() {
          scope.groups = scope.$parent.$eval(attrs.crmAvailGroups);
        });
        scope.$watchCollection(attrs.crmAvailMailings, function() {
          scope.mailings = scope.$parent.$eval(attrs.crmAvailMailings);
        });
        scope.$watchCollection(attrs.crmMandatoryGroups, function() {
          refreshMandatory();
        });
      }
    };
  });


angular.module('crmMailing').directive('crmMailingReviewBool', function() {
    return {
      scope: {
        crmOn: '@',
        crmTitle: '@'
      },
      template: '<span ng-class="spanClasses"><i class="crm-i" ng-class="iconClasses"></i> {{evalTitle}} </span>',
      link: function(scope, element, attrs) {
        function refresh() {
          if (scope.$parent.$eval(attrs.crmOn)) {
            scope.spanClasses = {'crmMailing-active': true};
            scope.iconClasses = {'fa-check': true};
          }
          else {
            scope.spanClasses = {'crmMailing-inactive': true};
            scope.iconClasses = {'fa-times': true};
          }
          scope.evalTitle = scope.$parent.$eval(attrs.crmTitle);
        }

        refresh();
        scope.$parent.$watch(attrs.crmOn, refresh);
        scope.$parent.$watch(attrs.crmTitle, refresh);
      }
    };
  });

angular.module('crmMailing').controller('SaveMsgTemplateDialogCtrl', function SaveMsgTemplateDialogCtrl($scope, crmMsgTemplates, dialogService) {
    var ts = $scope.ts = CRM.ts(null);
    $scope.saveOpt = {mode: '', newTitle: ''};
    $scope.selected = null;

    $scope.save = function save() {
      var tpl = _.extend({}, $scope.model.tpl);
      switch ($scope.saveOpt.mode) {
        case 'add':
          tpl.msg_title = $scope.saveOpt.newTitle;
          break;
        case 'update':
          tpl.id = $scope.selected.id;
          tpl.msg_title = $scope.selected.msg_title;
          break;
        default:
          throw 'SaveMsgTemplateDialogCtrl: Unrecognized mode: ' + $scope.saveOpt.mode;
      }
      return crmMsgTemplates.save(tpl)
        .then(function (item) {
          CRM.status(ts('Saved'));
          return item;
        });
    };

    function scopeApply(f) {
      return function () {
        var args = arguments;
        $scope.$apply(function () {
          f.apply(args);
        });
      };
    }

    function init() {
      crmMsgTemplates.get($scope.model.selected_id).then(
        function (tpl) {
          $scope.saveOpt.mode = 'update';
          $scope.selected = tpl;
        },
        function () {
          $scope.saveOpt.mode = 'add';
          $scope.selected = null;
        }
      );
      var buttons = [
        {
          text: ts('Save'),
          icons: {primary: 'fa-check'},
          click: function () {
            $scope.save().then(function (item) {
              dialogService.close('saveTemplateDialog', item);
            });
          }
        },
        {
          text: ts('Cancel'),
          icons: {primary: 'fa-times'},
          click: function () {
            dialogService.cancel('saveTemplateDialog');
          }
        }
      ];
      dialogService.setButtons('saveTemplateDialog', buttons);
    }

    setTimeout(scopeApply(init), 0);
  });


angular.module('crmMailing').directive('crmMailingTemplates', function(crmUiAlert) {
      return {
          restrict: 'AE',
          require: 'ngModel',
          scope: {
            ngRequired: '@'
          },
          link: function(scope, element, attrs, ngModel) {
            scope.template = ngModel.$viewValue;

            var refreshUI = ngModel.$render = function refresuhUI() {
              scope.template = ngModel.$viewValue;
              if (ngModel.$viewValue) {
                $(element).select2('val', ngModel.$viewValue);
              }
            };
            function formatItem(item) {
              if (!item.id) {
                return item.text;
              }
              return '<span class="crmMailing-template">' + item.text + '</span>';
            }

            var rcpAjaxState = {
              input: '',
              entity: 'civicrm_msg_templates',
              page_n: 0,
              page_i: 0,
            };

            $(element).select2({
              width: '36em',
              placeholder: "<i class='fa fa-clipboard'></i> Mailing Templates",
              formatResult: formatItem,
              escapeMarkup: function(m) {
                return m;
              },
              multiple: false,
              initSelection: function(el, cb) {

                  var value = el.val();

                  CRM.api3('MessageTemplate', 'getlist', { params: { id: value }, label_field: 'msg_title' }).then(function(tlist) {

                      var template = {};

                      if (tlist.count) {
                        $(tlist.values).each(function(id, val) {
                          template.id = val.id;
                          template.text = val.label;
                        });
                      }

                      cb(template);
                  });
              },
              ajax: {
                  url: CRM.url('civicrm/ajax/rest'),
                  quietMillis: 300,
                  data: function(input, page_num) {
                    if (page_num <= 1) {
                      rcpAjaxState = {
                        input: input,
                        entity: 'civicrm_msg_templates',
                        page_n: 0,
                      };
                    }

                    rcpAjaxState.page_i = page_num - rcpAjaxState.page_n;
                    var filterParams = { is_active: 1, workflow_name: { "IS NULL": 1 } };

                    var params = {
                      input: input,
                      page_num: rcpAjaxState.page_i,
                      label_field: 'msg_title',
                      search_field: 'msg_title',
                      params: filterParams,
                    };
                    return params;
                  },
                  transport: function(params) {
                    CRM.api3('MessageTemplate', 'getlist', params.data).then(params.success, params.error);
                  },
                  results: function(data) {

                    var results = {
                      children: $.map(data.values, function(obj) {
                        return { id: obj.id, text: obj.label };
                      })
                    };

                    if (rcpAjaxState.page_i == 1 && data.count) {
                      results.text = ts('Message Templates');
                    }

                    var more = data.more_results;

                    if (more && !data.more_results) {
                      rcpAjaxState.page_n += rcpAjaxState.page_i;
                    }

                    return { more: more, results: [ results ] };
                  },
                }
            });

            $(element).on('select2-selecting', function(e) {
              var entity_id = parseInt(e.val);
              ngModel.$viewValue = entity_id;

              scope.$parent.loadTemplate(scope.$parent.$parent.mailing, entity_id);
              scope.$apply();
              $(element).select2('close');
              e.preventDefault();
            });


            scope.$watchCollection("template", refreshUI);
            setTimeout(refreshUI, 50);
          }
      };


  });

angular.module('crmMailing').directive('crmMailingToken', function() {
    return {
      require: '^crmUiIdScope',
      scope: {
        onSelect: '@'
      },
      template: '<input type="text" class="crmMailingToken" />',
      link: function(scope, element, attrs, crmUiIdCtrl) {
        $(element).addClass('crm-action-menu fa-code').crmSelect2({
          width: "12em",
          dropdownAutoWidth: true,
          data: CRM.crmMailing.mailTokens,
          placeholder: ts('Tokens')
        });
        $(element).on('select2-selecting', function(e) {
          e.preventDefault();
          $(element).select2('close').select2('val', '');
          scope.$parent.$eval(attrs.onSelect, {
            token: {name: e.val}
          });
        });
      }
    };
  });

angular.module('crmMailing').controller('ViewRecipCtrl', function ViewRecipCtrl($scope) {
    var mids = [];
    var gids = [];
    var groupNames = [];
    var mailings = [];
    var civimailings = [];
    var civimails = [];

    function getGroupNames(mailing) {
      if (-1 == mailings.indexOf(mailing.id)) {
        mailings.push(mailing.id);
        _.each(mailing.recipients.groups.include, function(id) {
          if (-1 == gids.indexOf(id)) {
            gids.push(id);
          }
        });
        _.each(mailing.recipients.groups.exclude, function(id) {
          if (-1 == gids.indexOf(id)) {
            gids.push(id);
          }
        });
        _.each(mailing.recipients.groups.base, function(id) {
          if (-1 == gids.indexOf(id)) {
            gids.push(id);
          }
        });
        if (!_.isEmpty(gids)) {
          CRM.api3('Group', 'get', {'id': {"IN": gids}}).then(function(result) {
            _.each(result.values, function(grp) {
              if (_.isEmpty(_.where(groupNames, {id: parseInt(grp.id)}))) {
                groupNames.push({id: parseInt(grp.id), title: grp.title, is_hidden: grp.is_hidden});
              }
            });
            CRM.crmMailing.groupNames = groupNames;
            $scope.$parent.crmMailingConst.groupNames = groupNames;
          });
        }
      }
    }

    function getCiviMails(mailing) {
      if (-1 == civimailings.indexOf(mailing.id)) {
        civimailings.push(mailing.id);
        _.each(mailing.recipients.mailings.include, function(id) {
          if (-1 == mids.indexOf(id)) {
            mids.push(id);
          }
        });
        _.each(mailing.recipients.mailings.exclude, function(id) {
          if (-1 == mids.indexOf(id)) {
            mids.push(id);
          }
        });
        if (!_.isEmpty(mids)) {
          CRM.api3('Mailing', 'get', {'id': {"IN": mids}}).then(function(result) {
            _.each(result.values, function(mail) {
              if (_.isEmpty(_.where(civimails, {id: parseInt(mail.id)}))) {
                civimails.push({id: parseInt(mail.id), name: mail.label});
              }
            });
            CRM.crmMailing.civiMails = civimails;
            $scope.$parent.crmMailingConst.civiMails = civimails;
          });
        }
      }
    }

    $scope.getIncludesAsString = function(mailing) {
      var first = true;
      var names = '';
      if (_.isEmpty(CRM.crmMailing.groupNames)) {
        getGroupNames(mailing);
      }
      if (_.isEmpty(CRM.crmMailing.civiMails)) {
        getCiviMails(mailing);
      }
      _.each(mailing.recipients.groups.include, function(id) {
        var group = _.where(CRM.crmMailing.groupNames, {id: parseInt(id)});
        if (group.length) {
          if (!first) {
            names = names + ', ';
          }
          names = names + group[0].title;
          first = false;
        }
      });
      _.each(mailing.recipients.mailings.include, function(id) {
        var oldMailing = _.where(CRM.crmMailing.civiMails, {id: parseInt(id)});
        if (oldMailing.length) {
          if (!first) {
            names = names + ', ';
          }
          names = names + oldMailing[0].name;
          first = false;
        }
      });
      return names;
    };
    $scope.getExcludesAsString = function(mailing) {
      var first = true;
      var names = '';
      _.each(mailing.recipients.groups.exclude, function(id) {
        var group = _.where(CRM.crmMailing.groupNames, {id: parseInt(id)});
        if (group.length) {
          if (!first) {
            names = names + ', ';
          }
          names = names + group[0].title;
          first = false;
        }
      });
      _.each(mailing.recipients.mailings.exclude, function(id) {
        var oldMailing = _.where(CRM.crmMailing.civiMails, {id: parseInt(id)});
        if (oldMailing.length) {
          if (!first) {
            names = names + ', ';
          }
          names = names + oldMailing[0].name;
          first = false;
        }
      });
      return names;
    };
  });


angular.module('crmMailing').factory('crmFromAddresses', function ($q, crmApi) {
    var emailRegex = /^"(.*)" *<([^@>]*@[^@>]*)>$/;
    var addrs = _.map(CRM.crmMailing.fromAddress, function (addr) {
      var match = emailRegex.exec(addr.label);
      return angular.extend({}, addr, {
        email: match ? match[2] : '(INVALID)',
        author: match ? match[1] : '(INVALID)'
      });
    });

    function first(array) {
      return (array.length === 0) ? null : array[0];
    }

    return {
      getAll: function getAll() {
        return addrs;
      },
      getByAuthorEmail: function getByAuthorEmail(author, email, autocreate) {
        var result = null;
        _.each(addrs, function (addr) {
          if (addr.author == author && addr.email == email) {
            result = addr;
          }
        });
        if (!result && autocreate) {
          result = {
            label: '(INVALID) "' + author + '" <' + email + '>',
            author: author,
            email: email
          };
          addrs.push(result);
        }
        return result;
      },
      getByEmail: function getByEmail(email) {
        return first(_.where(addrs, {email: email}));
      },
      getByLabel: function (label) {
        return first(_.where(addrs, {label: label}));
      },
      getDefault: function getDefault() {
        return first(_.where(addrs, {is_default: "1"}));
      }
    };
  });

  angular.module('crmMailing').factory('crmMsgTemplates', function ($q, crmApi) {
    var tpls = _.map(CRM.crmMailing.mesTemplate, function (tpl) {
      return angular.extend({}, tpl, {
      });
    });
    window.tpls = tpls;
    var lastModifiedTpl = null;
    return {
      get: function get(id) {
        return crmApi('MessageTemplate', 'getsingle', {
           "return": "id,msg_subject,msg_html,msg_title,msg_text",
           "id": id
        });
      },
      save: function (tpl) {
        return crmApi('MessageTemplate', 'create', tpl).then(function (response) {
          if (!tpl.id) {
            tpl.id = '' + response.id; //parseInt(response.id);
            tpls.push(tpl);
          }
          lastModifiedTpl = tpl;
          return tpl;
        });
      },
      getLastModifiedTpl: function () {
        return lastModifiedTpl;
      },
      getAll: function getAll() {
        return tpls;
      }
    };
  });
  angular.module('crmMailing').factory('crmMailingMgr', function ($q, crmApi, crmFromAddresses, crmQueue) {
    var qApi = crmQueue(crmApi);
    var pickDefaultMailComponent = function pickDefaultMailComponent(type) {
      var mcs = _.where(CRM.crmMailing.headerfooterList, {
        component_type: type,
        is_default: "1"
      });
      return (mcs.length >= 1) ? mcs[0].id : null;
    };

    return {
      getOrCreate: function getOrCreate(idExpr) {
        return (idExpr == 'new') ? this.create() : this.get(idExpr);
      },
      get: function get(id) {
        var crmMailingMgr = this;
        var mailing;
        return qApi('Mailing', 'getsingle', {id: id})
          .then(function (getResult) {
            mailing = getResult;
            return $q.all([
              crmMailingMgr._loadGroups(mailing),
              crmMailingMgr._loadJobs(mailing)
            ]);
          })
          .then(function () {
            return mailing;
          });
      },
      _loadGroups: function (mailing) {
        return crmApi('MailingGroup', 'get', {mailing_id: mailing.id, 'options': {'limit':0}})
          .then(function (groupResult) {
            mailing.recipients = {};
            mailing.recipients.groups = {include: [], exclude: [], base: []};
            mailing.recipients.mailings = {include: [], exclude: []};
            _.each(groupResult.values, function (mailingGroup) {
              var bucket = (/^civicrm_group/.test(mailingGroup.entity_table)) ? 'groups' : 'mailings';
              var entityId = parseInt(mailingGroup.entity_id);
              mailing.recipients[bucket][mailingGroup.group_type.toLowerCase()].push(entityId);
            });
          });
      },
      _loadJobs: function (mailing) {
        return crmApi('MailingJob', 'get', {mailing_id: mailing.id, is_test: 0})
          .then(function (jobResult) {
            mailing.jobs = mailing.jobs || {};
            angular.extend(mailing.jobs, jobResult.values);
          });
      },
      create: function create(params) {
        var defaults = {
          jobs: {}, // {jobId: JobRecord}
          recipients: {
            groups: {include: [], exclude: [], base: []},
            mailings: {include: [], exclude: []}
          },
          template_type: "traditional",
          template_options: {nonce: 1},
          name: "",
          campaign_id: null,
          replyto_email: "",
          subject: "",
          body_html: "",
          body_text: ""
        };
        return angular.extend({}, defaults, params);
      },
      'delete': function (mailing) {
        if (mailing.id) {
          return qApi('Mailing', 'delete', {id: mailing.id});
        }
        else {
          var d = $q.defer();
          d.resolve();
          return d.promise;
        }
      },
      findMissingTokens: function(mailing, field) {
        var missing = {};
        if (!_.isEmpty(mailing[field]) && !CRM.crmMailing.disableMandatoryTokensCheck) {
          var body = '';
          if (mailing.footer_id) {
            var footer = _.where(CRM.crmMailing.headerfooterList, {id: mailing.footer_id});
            body = body + footer[0][field];

          }
          body = body + mailing[field];
          if (mailing.header_id) {
            var header = _.where(CRM.crmMailing.headerfooterList, {id: mailing.header_id});
            body = body + header[0][field];
          }

          angular.forEach(CRM.crmMailing.requiredTokens, function(value, token) {
            if (!_.isObject(value)) {
              if (body.indexOf('{' + token + '}') < 0) {
                missing[token] = value;
              }
            }
            else {
              var count = 0;
              angular.forEach(value, function(nestedValue, nestedToken) {
                if (body.indexOf('{' + nestedToken + '}') >= 0) {
                  count++;
                }
              });
              if (count === 0) {
                angular.extend(missing, value);
              }
            }
          });
        }
        return missing;
      },
      mergeInto: function mergeInto(mailingTgt, mailingFrom, excludes) {
        var MAILING_FIELDS = [
          'name',
          'campaign_id',
          'from_name',
          'from_email',
          'replyto_email',
          'subject',
          'dedupe_email',
          'recipients',
          'body_html',
          'body_text',
          'footer_id',
          'header_id',
          'visibility',
          'url_tracking',
          'dedupe_email',
          'forward_replies',
          'auto_responder',
          'open_tracking',
          'override_verp',
          'optout_id',
          'reply_id',
          'resubscribe_id',
          'unsubscribe_id'
        ];
        if (!excludes) {
          excludes = [];
        }
        _.each(MAILING_FIELDS, function (field) {
          if (!_.contains(excludes, field)) {
            mailingTgt[field] = mailingFrom[field];
          }
        });
      },
      preview: function preview(mailing) {
        return this.getPreviewContent(qApi, mailing);
      },
      getPreviewContent: function getPreviewContent(backend, mailing) {
        if (CRM.crmMailing.workflowEnabled && !CRM.checkPerm('create mailings') && !CRM.checkPerm('access CiviMail')) {
          return backend('Mailing', 'preview', {id: mailing.id}).then(function(result) {
            return result.values;
          });
        }
        else {
          var params = angular.extend({}, mailing);
          delete params.id;
          return backend('Mailing', 'preview', params).then(function(result) {
            return result.values;
          });
        }
      },
      previewRecipients: function previewRecipients(mailing, previewLimit) {
        var params = angular.extend({}, mailing.recipients, {
          id: mailing.id,
          'api.MailingRecipients.get': {
            mailing_id: '$value.id',
            options: {limit: previewLimit},
            'api.contact.getvalue': {'return': 'display_name'},
            'api.email.getvalue': {'return': 'email'}
          }
        });
        delete params.scheduled_date;
        delete params.recipients; // the content was merged in
        return qApi('Mailing', 'create', params).then(function (recipResult) {
          mailing.modified_date = recipResult.values[recipResult.id].modified_date;
          return recipResult.values[recipResult.id]['api.MailingRecipients.get'].values;
        });
      },

      previewRecipientCount: function previewRecipientCount(mailing, crmMailingCache, rebuild) {
        var cachekey = 'mailing-' + mailing.id + '-recipient-count';
        var recipientCount = crmMailingCache.get(cachekey);
        if (rebuild || _.isEmpty(recipientCount)) {
          var params = angular.extend({}, mailing, mailing.recipients, {
            id: mailing.id,
            'api.MailingRecipients.getcount': {
              mailing_id: '$value.id'
            }
          });
          if (rebuild) {
            params = angular.extend(params, {
              'api.MailingRecipients.get': {
                mailing_id: '$value.id',
                options: {limit: 50},
                'api.contact.getvalue': {'return': 'display_name'},
                'api.email.getvalue': {'return': 'email'}
              }
            });
            crmMailingCache.put('mailing-' + mailing.id + '-recipient-params', params.recipients);
          }
          delete params.scheduled_date;
          delete params.recipients; // the content was merged in
          recipientCount = qApi('Mailing', 'create', params).then(function (recipResult) {
            mailing.modified_date = recipResult.values[recipResult.id].modified_date;
            if (rebuild) {
              crmMailingCache.put('mailing-' + mailing.id + '-recipient-list', recipResult.values[recipResult.id]['api.MailingRecipients.get'].values);
            }
            return recipResult.values[recipResult.id]['api.MailingRecipients.getcount'];
          });
          crmMailingCache.put(cachekey, recipientCount);
        }

        return recipientCount;
      },
      save: function(mailing) {
        var params = angular.extend({}, mailing, mailing.recipients);
        angular.forEach(mailing, function(value, key) {
          if (value === undefined || value === null) {
            mailing[key] = '';
          }
        });
        delete params.scheduled_date;

        delete params.jobs;

        delete params.recipients; // the content was merged in
        params._skip_evil_bao_auto_recipients_ = 1; // skip recipient rebuild on simple save
        return qApi('Mailing', 'create', params).then(function(result) {
          if (result.id && !mailing.id) {
            mailing.id = result.id;
          }  // no rollback, so update mailing.id
          mailing.modified_date = result.values[result.id].modified_date;
          return mailing;
        });
      },
      submit: function (mailing) {
        var crmMailingMgr = this;
        var params = {
          id: mailing.id,
          approval_date: 'now',
          scheduled_date: mailing.scheduled_date ? mailing.scheduled_date : 'now'
        };
        return qApi('Mailing', 'submit', params)
          .then(function (result) {
            angular.extend(mailing, result.values[result.id]); // Perhaps we should reload mailing based on result?
            return crmMailingMgr._loadJobs(mailing);
          })
          .then(function () {
            return mailing;
          });
      },
      sendTest: function (mailing, recipient) {
        var params = angular.extend({}, mailing, mailing.recipients, {
          'api.Mailing.send_test': {
            mailing_id: '$value.id',
            test_email: recipient.email,
            test_group: recipient.gid
          }
        });
        delete params.scheduled_date;

        delete params.jobs;

        delete params.recipients; // the content was merged in

        params._skip_evil_bao_auto_recipients_ = 1; // skip recipient rebuild while sending test mail

        return qApi('Mailing', 'create', params).then(function (result) {
          if (result.id && !mailing.id) {
            mailing.id = result.id;
          }  // no rollback, so update mailing.id
          mailing.modified_date = result.values[result.id].modified_date;
          return result.values[result.id]['api.Mailing.send_test'].values;
        });
      }
    };
  });
  angular.module('crmMailing').factory('crmMailingPreviewMgr', function (dialogService, crmMailingMgr, crmStatus) {
    return {
      preview: function preview(mailing, mode) {
        var templates = {
          html: '~/crmMailing/PreviewMgr/html.html',
          text: '~/crmMailing/PreviewMgr/text.html',
          full: '~/crmMailing/PreviewMgr/full.html'
        };
        var result = null;
        var p = crmMailingMgr
          .getPreviewContent(CRM.api3, mailing)
          .then(function (content) {
            var options = CRM.utils.adjustDialogDefaults({
              autoOpen: false,
              title: ts('Subject: %1', {
                1: content.subject
              })
            });
            result = dialogService.open('previewDialog', templates[mode], content, options);
          });
        crmStatus({start: ts('Previewing...'), success: ''}, p);
        return result;
      },
      sendTest: function sendTest(mailing, recipient) {
        var promise = crmMailingMgr.sendTest(mailing, recipient)
            .then(function (deliveryInfos) {
              var count = Object.keys(deliveryInfos).length;
              if (count === 0) {
                CRM.alert(ts('Could not identify any recipients. Perhaps your test group is empty, or you tried sending to contacts that do not exist and you have no permission to add contacts.'));
              }
            })
          ;
        return crmStatus({start: ts('Sending...'), success: ts('Sent')}, promise);
      }
    };
  });

  angular.module('crmMailing').factory('crmMailingStats', function (crmApi, crmLegacy) {
    var statTypes = [
      {name: 'Recipients',    title: ts('Intended Recipients'),     searchFilter: '',                           eventsFilter: '&event=queue', reportType: 'detail', reportFilter: ''},
      {name: 'Delivered',     title: ts('Successful Deliveries'),   searchFilter: '&mailing_delivery_status=Y', eventsFilter: '&event=delivered', reportType: 'detail', reportFilter: '&delivery_status_value=successful'},
      {name: 'Opened',        title: ts('Unique Opens'),           searchFilter: '&mailing_open_status=Y',     eventsFilter: '&event=opened', reportType: 'opened', reportFilter: ''},
      {name: 'Unique Clicks', title: ts('Unique Clicks'),          searchFilter: '&mailing_click_status=Y',    eventsFilter: '&event=click&distinct=1', reportType: 'clicks', reportFilter: ''},
      {name: 'Bounces',       title: ts('Bounces'),                 searchFilter: '&mailing_delivery_status=N', eventsFilter: '&event=bounce', reportType: 'bounce', reportFilter: ''},
      {name: 'Unsubscribers', title: ts('Unsubscribes & Opt-outs'), searchFilter: '&mailing_unsubscribe=1',     eventsFilter: '&event=unsubscribe', reportType: 'detail', reportFilter: '&is_unsubscribed_value=1'},
    ];

    return {
      getStatTypes: function() {
        return statTypes;
      },

      /**
       * @param mailingIds object
       *   List of mailing IDs ({a: 123, b: 456})
       * @return Promise
       *   List of stats for each mailing
       *   ({a: ...object..., b: ...object...})
       */
      getStats: function(mailingIds) {
        var params = {};
        angular.forEach(mailingIds, function(mailingId, name) {
          params[name] = ['Mailing', 'stats', {mailing_id: mailingId, is_distinct: 1}];
        });
        return crmApi(params).then(function(result) {
          var stats = {};
          angular.forEach(mailingIds, function(mailingId, name) {
            stats[name] = result[name].values[mailingId];
          });
          return stats;
        });
      },

      /**
       * Determine the legacy URL for a report about a given mailing and stat.
       *
       * @param mailing object
       * @param statType object (see statTypes above)
       * @param view string ('search', 'event', 'report')
       * @param returnPath string|null Return path (relative to Angular base)
       * @return string|null
       */
      getUrl: function getUrl(mailing, statType, view, returnPath) {
        switch (view) {
          case 'events':
            var retParams = returnPath ? '&context=angPage&angPage=' + returnPath : '';
            return crmLegacy.url('civicrm/mailing/report/event',
              'reset=1&mid=' + mailing.id + statType.eventsFilter + retParams);
          case 'search':
            return crmLegacy.url('civicrm/contact/search/advanced',
              'force=1&mailing_id=' + mailing.id + statType.searchFilter);
          case 'report':
            var reportIds = CRM.crmMailing.reportIds;
            return crmLegacy.url('civicrm/report/instance/' + reportIds[statType.reportType],
                'reset=1&mailing_id_value=' + mailing.id + statType.reportFilter);
          default:
            return null;
        }
      }
    };
  });
  angular.module('crmMailing').factory('crmMailingSimpleDirective', function ($q, crmMetadata, crmUiHelp) {
    return function crmMailingSimpleDirective(directiveName, templateUrl) {
      return {
        scope: {
          crmMailing: '@'
        },
        templateUrl: templateUrl,
        link: function (scope, elm, attr) {
          scope.$parent.$watch(attr.crmMailing, function(newValue){
            scope.mailing = newValue;
          });
          scope.crmMailingConst = CRM.crmMailing;
          scope.ts = CRM.ts(null);
          scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});
          scope.checkPerm = CRM.checkPerm;
          scope[directiveName] = attr[directiveName] ? scope.$parent.$eval(attr[directiveName]) : {};
          $q.when(crmMetadata.getFields('Mailing'), function(fields) {
            scope.mailingFields = fields;
          });
        }
      };
    };
  });

  angular.module('crmMailing').factory('crmMailingCache', ['$cacheFactory', function($cacheFactory) {
    return $cacheFactory('crmMailingCache');
  }]);


angular.module('crmMailingAB', CRM.angRequires('crmMailingAB'));
  angular.module('crmMailingAB').config([
    '$routeProvider',
    function($routeProvider) {
      $routeProvider.when('/abtest', {
        templateUrl: '~/crmMailingAB/ListCtrl.html',
        controller: 'CrmMailingABListCtrl',
        resolve: {
          mailingABList: function($route, crmApi) {
            return crmApi('MailingAB', 'get', {rowCount: 0});
          },
          fields: function(crmMetadata) {
            return crmMetadata.getFields('MailingAB');
          }
        }
      });
      $routeProvider.when('/abtest/new', {
        template: '<p>' + ts('Initializing...') + '</p>',
        controller: 'CrmMailingABNewCtrl',
        resolve: {
          abtest: function($route, CrmMailingAB) {
            var abtest = new CrmMailingAB(null);
            return abtest.load().then(function() {
              return abtest.save();
            });
          }
        }
      });
      $routeProvider.when('/abtest/:id', {
        templateUrl: '~/crmMailingAB/EditCtrl/main.html',
        controller: 'CrmMailingABEditCtrl',
        resolve: {
          mailingFields: function(crmMetadata) {
            return crmMetadata.getFields('Mailing');
          },
          abtest: function($route, CrmMailingAB) {
            var abtest = new CrmMailingAB($route.current.params.id == 'new' ? null : $route.current.params.id);
            return abtest.load();
          }
        }
      });
    }
  ]);


var simpleDirectives = {
    crmMailingAbBlockMailing: '~/crmMailingAB/BlockMailing.html'
  };
  _.each(simpleDirectives, function(templateUrl, directiveName) {
    angular.module('crmMailingAB').directive(directiveName, function($parse, crmMailingABCriteria, crmUiHelp) {
      var scopeDesc = {crmAbtest: '@'};
      scopeDesc[directiveName] = '@';

      return {
        scope: scopeDesc,
        templateUrl: templateUrl,
        link: function(scope, elm, attr) {
          var model = $parse(attr.crmAbtest);
          scope.abtest = model(scope.$parent);
          scope.crmMailingConst = CRM.crmMailing;
          scope.crmMailingABCriteria = crmMailingABCriteria;
          scope.ts = CRM.ts(null);
          scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});

          var fieldsModel = $parse(attr[directiveName]);
          scope.fields = fieldsModel(scope.$parent);
        }
      };
    });
  });


var simpleDirectives = {
    crmMailingAbBlockSetup: '~/crmMailingAB/BlockSetup.html'
  };
  _.each(simpleDirectives, function(templateUrl, directiveName) {
    angular.module('crmMailingAB').directive(directiveName, function($parse, crmMailingABCriteria, crmUiHelp) {
      var scopeDesc = {crmAbtest: '@'};
      scopeDesc[directiveName] = '@';

      return {
        scope: scopeDesc,
        templateUrl: templateUrl,
        link: function(scope, elm, attr) {
          var model = $parse(attr.crmAbtest);
          scope.abtest = model(scope.$parent);
          scope.crmMailingConst = CRM.crmMailing;
          scope.crmMailingABCriteria = crmMailingABCriteria;
          scope.ts = CRM.ts(null);
          scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});

          var fieldsModel = $parse(attr[directiveName]);
          scope.fields = fieldsModel(scope.$parent);
        }
      };
    });
  });


angular.module('crmMailingAB').controller('CrmMailingABEditCtrl', function($scope, abtest, crmMailingABCriteria, crmMailingMgr, crmMailingPreviewMgr, crmStatus, $q, $location, crmBlocker, $interval, $timeout, CrmAutosaveCtrl, dialogService, mailingFields) {
    $scope.abtest = abtest;
    var ts = $scope.ts = CRM.ts(null);
    var block = $scope.block = crmBlocker();
    $scope.crmUrl = CRM.url;
    var myAutosave = null;
    $scope.crmMailingABCriteria = crmMailingABCriteria;
    $scope.crmMailingConst = CRM.crmMailing;
    $scope.checkPerm = CRM.checkPerm;
    $scope.mailingFields = mailingFields;

    $scope.isSubmitted = function isSubmitted() {
      return _.size(abtest.mailings.a.jobs) > 0 || _.size(abtest.mailings.b.jobs) > 0;
    };

    $scope.sync = function sync() {
      abtest.mailings.a.name = ts('Test A (%1)', {1: abtest.ab.name});
      abtest.mailings.b.name = ts('Test B (%1)', {1: abtest.ab.name});
      abtest.mailings.c.name = ts('Final (%1)', {1: abtest.ab.name});

      if (abtest.ab.testing_criteria) {
        switch (abtest.ab.testing_criteria) {
          case 'subject':
            var exclude_subject = [
              'name',
              'recipients',
              'subject'
            ];
            crmMailingMgr.mergeInto(abtest.mailings.b, abtest.mailings.a, exclude_subject);
            crmMailingMgr.mergeInto(abtest.mailings.c, abtest.mailings.a, exclude_subject);
            break;
          case 'from':
            var exclude_from = [
              'name',
              'recipients',
              'from_name',
              'from_email'
            ];
            crmMailingMgr.mergeInto(abtest.mailings.b, abtest.mailings.a, exclude_from);
            crmMailingMgr.mergeInto(abtest.mailings.c, abtest.mailings.a, exclude_from);
            break;
          case 'full_email':
            var exclude_full_email = [
              'name',
              'recipients',
              'subject',
              'from_name',
              'from_email',
              'replyto_email',
              'override_verp', // keep override_verp and replyto_Email linked
              'body_html',
              'body_text'
            ];
            crmMailingMgr.mergeInto(abtest.mailings.b, abtest.mailings.a, exclude_full_email);
            crmMailingMgr.mergeInto(abtest.mailings.c, abtest.mailings.a, exclude_full_email);
            break;
          default:
            throw "Unrecognized testing_criteria";
        }
      }
      return true;
    };
    $scope.save = function save() {
      return block(crmStatus({start: ts('Saving...'), success: ts('Saved')}, abtest.save()));
    };
    $scope.previewMailing = function previewMailing(mailingName, mode) {
      return crmMailingPreviewMgr.preview(abtest.mailings[mailingName], mode);
    };
    $scope.sendTest = function sendTest(mailingName, recipient) {
      return block(crmStatus({start: ts('Saving...'), success: ''}, abtest.save())
        .then(function() {
          crmMailingPreviewMgr.sendTest(abtest.mailings[mailingName], recipient);
        }));
    };
    $scope.delete = function() {
      return block(crmStatus({start: ts('Deleting...'), success: ts('Deleted')}, abtest.delete().then($scope.leave)));
    };
    $scope.submit = function submit() {
      if (block.check() || $scope.crmMailingAB.$invalid) {
        return;
      }
      return block(crmStatus({start: ts('Saving...'), success: ''}, abtest.save())
          .then(function() {
            return crmStatus({
              start: ts('Submitting...'),
              success: ts('Submitted')
            }, myAutosave.suspend(abtest.submitTest()));
          })
      );
    };

    $scope.leave = function leave() {
      $location.path('abtest');
      $location.replace();
    };

    $scope.selectWinner = function selectWinner(mailingName) {
      var model = {
        abtest: $scope.abtest,
        mailingName: mailingName
      };
      var options = CRM.utils.adjustDialogDefaults({
        autoOpen: false,
        height: 'auto',
        width: '40%',
        title: ts('Select Final Mailing (Test %1)', {
          1: mailingName.toUpperCase()
        })
      });
      return myAutosave.suspend(dialogService.open('selectWinnerDialog', '~/crmMailingAB/WinnerDialogCtrl.html', model, options));
    };
    var syncJob = $interval($scope.sync, 333);
    $scope.$on('$destroy', function() {
      $interval.cancel(syncJob);
    });

    myAutosave = new CrmAutosaveCtrl({
      save: $scope.save,
      saveIf: function() {
        return abtest.ab.status == 'Draft' && $scope.sync();
      },
      model: function() {
        return abtest.getAutosaveSignature();
      },
      form: function() {
        return $scope.crmMailingAB;
      }
    });
    $timeout(myAutosave.start);
    $scope.$on('$destroy', myAutosave.stop);
  });


angular.module('crmMailingAB').controller('CrmMailingABListCtrl', function($scope, mailingABList, crmMailingABCriteria, crmMailingABStatus, fields) {
    var ts = $scope.ts = CRM.ts(null);
    $scope.mailingABList = _.values(mailingABList.values);
    $scope.crmMailingABCriteria = crmMailingABCriteria;
    $scope.crmMailingABStatus = crmMailingABStatus;
    $scope.fields = fields;
    $scope.filter = {};
  });


angular.module('crmMailingAB').controller('CrmMailingABNewCtrl', function($scope, abtest, $location) {
    var parts = $location.path().split('/'); // e.g. "/mailing/new" or "/mailing/123/wizard"
    parts[2] = abtest.id;
    $location.path(parts.join('/'));
    $location.replace();
  });


angular.module('crmMailingAB').controller('CrmMailingABReportCtrl', function($scope, crmApi, crmMailingStats) {
    var ts = $scope.ts = CRM.ts(null);

    var CrmMailingABReportCnt = 1, activeMailings = null;
    $scope.getActiveMailings = function() {
      if ($scope.abtest.$CrmMailingABReportCnt != CrmMailingABReportCnt) {
        $scope.abtest.$CrmMailingABReportCnt = ++CrmMailingABReportCnt;
        activeMailings = [
          {
            name: 'a',
            title: ts('Mailing A'),
            mailing: $scope.abtest.mailings.a,
            attachments: $scope.abtest.attachments.a
          },
          {
            name: 'b',
            title: ts('Mailing B'),
            mailing: $scope.abtest.mailings.b,
            attachments: $scope.abtest.attachments.b
          }
        ];
        if ($scope.abtest.ab.status == 'Final') {
          activeMailings.push({
            name: 'c',
            title: ts('Final'),
            mailing: $scope.abtest.mailings.c,
            attachments: $scope.abtest.attachments.c
          });
        }
      }
      return activeMailings;
    };

    crmMailingStats.getStats({
      a: $scope.abtest.ab.mailing_id_a,
      b: $scope.abtest.ab.mailing_id_b,
      c: $scope.abtest.ab.mailing_id_c
    }).then(function(stats) {
      $scope.stats = stats;
    });
    $scope.rateStats = {
      'Unique Clicks': 'clickthrough_rate',
      'Delivered': 'delivered_rate',
      'Opened': 'opened_rate',
    };
    $scope.statTypes = crmMailingStats.getStatTypes();
    $scope.statUrl = function statUrl(mailing, statType, view) {
      return crmMailingStats.getUrl(mailing, statType, view, 'abtest/' + $scope.abtest.ab.id);
    };

    $scope.checkPerm = CRM.checkPerm;
  });


angular.module('crmMailingAB').directive('crmMailingAbSlider', function() {
    return {
      require: '?ngModel',
      scope: {},
      templateUrl: '~/crmMailingAB/Slider.html',
      link: function(scope, element, attrs, ngModel) {
        var TEST_MIN = 1, TEST_MAX = 50;
        var sliders = $('.slider-test,.slider-win', element);
        var sliderTests = $('.slider-test', element);
        var sliderWin = $('.slider-win', element);

        scope.ts = CRM.ts(null);
        scope.testValue = 0;
        scope.winValue = 100;
        function setValue(value) {
          value = Math.min(TEST_MAX, Math.max(TEST_MIN, value));
          scope.$apply(function() {
            ngModel.$setViewValue(value);
            scope.testValue = value;
            scope.winValue = 100 - (2 * scope.testValue);
            sliderTests.slider('value', scope.testValue);
            sliderWin.slider('value', scope.winValue);
          });
        }

        sliders.slider({
          min: 0,
          max: 100,
          range: 'min',
          step: 1
        });
        sliderTests.slider({
          slide: function slideTest(event, ui) {
            event.preventDefault();
            setValue(ui.value);
          }
        });
        sliderWin.slider({
          slide: function slideWinner(event, ui) {
            event.preventDefault();
            setValue(Math.round((100 - ui.value) / 2));
          }
        });

        ngModel.$render = function() {
          scope.testValue = ngModel.$viewValue;
          scope.winValue = 100 - (2 * scope.testValue);
          sliderTests.slider('value', scope.testValue);
          sliderWin.slider('value', scope.winValue);
        };
      }
    };
  });


angular.module('crmMailingAB').directive('crmMailingAbStats', function (crmApi, $parse) {
    return {
      scope: {
        crmMailingAbStats: '@',
        crmAbtest: '@'
      },
      template: '<div class="crm-mailing-ab-stats"></div>',
      link: function (scope, element, attrs) {
        var abtestModel = $parse(attrs.crmAbtest);
        var optionModel = $parse(attrs.crmMailingAbStats);
        var options = angular.extend({}, optionModel(scope.$parent), {
          criteria: 'Open', // e.g. 'Open', 'Total Unique Clicks'
          split_count: 5
        });

        scope.$watch(attrs.crmAbtest, refresh);
        function refresh() {
          var abtest = abtestModel(scope.$parent);
          if (!abtest) {
            console.log('failed to draw stats - missing abtest');
            return;
          }

          scope.graph_data = [
            {},
            {},
            {},
            {},
            {}
          ];
          var keep_cnt = 0;

          for (var i = 1; i <= options.split_count; i++) {
            var result = crmApi('MailingAB', 'graph_stats', {
              id: abtest.ab.id,
              target_date: abtest.ab.declare_winning_time ? abtest.ab.declare_winning_time : 'now',
              target_url: null, // FIXME
              criteria: options.criteria,
              split_count: options.split_count,
              split_count_select: i
            });
            /*jshint -W083 */
            result.then(function (data) {
              var temp = 0;
              keep_cnt++;
              for (var key in data.values.A) {
                temp = key;
              }
              var t = data.values.A[temp].time.split(" ");
              var m = t[0];
              var year = t[2];
              var day = t[1].substr(0, t[1].length - 3);
              var t1, hur, hour, min;
              if (_.isEmpty(t[3])) {
                t1 = t[4].split(":");
                hur = t1[0];
                if (t[5] == "AM") {
                  hour = hur;
                  if (hour == 12) {
                    hour = 0;
                  }
                }
                if (t[5] == "PM") {
                  hour = parseInt(hur) + 12;
                }
                min = t1[1];
              }
              else {
                t1 = t[3].split(":");
                hur = t1[0];
                if (t[4] == "AM") {
                  hour = hur;
                  if (hour == 12) {
                    hour = 0;
                  }
                }
                if (t[4] == "PM") {
                  hour = parseInt(hur) + 12;
                }
                min = t1[1];
              }
              var month = 0;
              switch (m) {
                case "January":
                  month = 0;
                  break;
                case "February":
                  month = 1;
                  break;
                case "March":
                  month = 2;
                  break;
                case "April":
                  month = 3;
                  break;
                case "May":
                  month = 4;
                  break;
                case "June":
                  month = 5;
                  break;
                case "July":
                  month = 6;
                  break;
                case "August":
                  month = 7;
                  break;
                case "September":
                  month = 8;
                  break;
                case "October":
                  month = 9;
                  break;
                case "November":
                  month = 10;
                  break;
                case "December":
                  month = 11;
                  break;

              }
              var tp = new Date(year, month, day, hour, min, 0, 0);
              scope.graph_data[temp - 1] = {
                time: tp,
                x: data.values.A[temp].count,
                y: data.values.B[temp].count
              };

              if (keep_cnt == options.split_count) {
                scope.graphload = true;
                data = scope.graph_data;
                var color = d3.scale.category10();
                color.domain(d3.keys(data[0]).filter(function (key) {
                  return key !== "time";
                }));
                var series = color.domain().map(function (name) {
                  return {
                    name: name,
                    values: data.map(function (d) {
                      return {
                        time: d.time,
                        score: +d[name]
                      };
                    })
                  };
                });
                var margin = {
                    top: 30,
                    right: 20,
                    bottom: 40,
                    left: 75
                  },
                  width = 550 - margin.left - margin.right,
                  height = 350 - margin.top - margin.bottom;
                var x = d3.time.scale().range([0, width]);
                var y = d3.scale.linear().range([height, 0]);
                var xAxis = d3.svg.axis().scale(x)
                  .orient("bottom").ticks(10);

                var yAxis = d3.svg.axis().scale(y)
                  .orient("left").ticks(5);
                var valueline = d3.svg.line()
                  .x(function (d) {
                    return x(d.time);
                  })
                  .y(function (d) {
                    return y(d.score);
                  });
                var svg = d3.select($('.crm-mailing-ab-stats', element)[0])
                  .append("svg")
                  .attr("width", width + margin.left + margin.right)
                  .attr("height", height + margin.top + margin.bottom)
                  .append("g")
                  .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
                x.domain(d3.extent(data, function (d) {
                  return d.time;
                }));
                y.domain([
                  d3.min(series, function (c) {
                    return d3.min(c.values, function (v) {
                      return v.score;
                    });
                  }),
                  d3.max(series, function (c) {
                    return d3.max(c.values, function (v) {
                      return v.score;
                    });
                  })
                ]);
                svg.append("text")      // text label for the x axis
                  .attr("x", width / 2)
                  .attr("y", height + margin.bottom)
                  .style("text-anchor", "middle")
                  .text("Time");

                svg.append("text")      // text label for the x axis
                  .style("text-anchor", "middle")
                  .text(scope.winnercriteria).attr("transform",function (d) {
                    return "rotate(-90)";
                  }).attr("x", -height / 2)
                  .attr("y", -30);
                series = svg.selectAll(".series")
                  .data(series)
                  .enter().append("g")
                  .attr("class", "series");
                series.append("path")
                  .attr("class", "line")
                  .attr("d", function (d) {
                    return valueline(d.values);
                  })
                  .style("stroke", function (d) {
                    return color(d.name);
                  });
                svg.append("g") // Add the X Axis
                  .attr("class", "x axis")
                  .attr("transform", "translate(0," + height + ")")
                  .call(xAxis)
                  .selectAll("text")
                  .attr("transform", function (d) {
                    return "rotate(-30)";
                  });
                svg.append("g") // Add the Y Axis
                  .attr("class", "y axis")
                  .call(yAxis);
              }
            });
          }
        }
      } // link()
    };
  });

angular.module('crmMailingAB').controller('CrmMailingABWinnerDialogCtrl', function($scope, $timeout, dialogService, crmMailingMgr, crmStatus) {
    var ts = $scope.ts = CRM.ts(null);
    var abtest = $scope.abtest = $scope.model.abtest;
    var mailingName = $scope.model.mailingName;

    var titles = {a: ts('Mailing A'), b: ts('Mailing B')};
    $scope.mailingTitle = titles[mailingName];

    function init() {
      var buttons = [
        {
          text: ts('Submit final mailing'),
          icons: {primary: 'fa-paper-plane'},
          click: function() {
            crmStatus({start: ts('Submitting...'), success: ts('Submitted')},
              abtest.submitFinal(abtest.mailings[mailingName].id).then(function (r) {
                delete abtest.$CrmMailingABReportCnt;
              }))
              .then(function () {
                dialogService.close('selectWinnerDialog', abtest);
              });
          }
        },
        {
          text: ts('Cancel'),
          icons: {primary: 'fa-times'},
          click: function() {
            dialogService.cancel('selectWinnerDialog');
          }
        }
      ];
      dialogService.setButtons('selectWinnerDialog', buttons);
    }

    $timeout(init);
  });


function OptionGroup(values) {
    this.get = function get(value) {
      var r = _.where(values, {value: '' + value});
      return r.length > 0 ? r[0] : null;
    };
    this.getByName = function get(name) {
      var r = _.where(values, {name: '' + name});
      return r.length > 0 ? r[0] : null;
    };
    this.getAll = function getAll() {
      return values;
    };
  }

  angular.module('crmMailingAB').factory('crmMailingABCriteria', function () {
    var values = {
      '1': {value: 'subject', name: 'subject', label: ts('Test different "Subject" lines')},
      '2': {value: 'from', name: 'from', label: ts('Test different "From" lines')},
      '3': {value: 'full_email', name: 'full_email', label: ts('Test entirely different emails')}
    };
    return new OptionGroup(values);
  });

  angular.module('crmMailingAB').factory('crmMailingABStatus', function () {
    var values = {
      '1': {value: '1', name: 'Draft', label: ts('Draft')},
      '2': {value: '2', name: 'Testing', label: ts('Testing')},
      '3': {value: '3', name: 'Final', label: ts('Final')}
    };
    return new OptionGroup(values);
  });
  angular.module('crmMailingAB').factory('CrmMailingAB', function (crmApi, crmMailingMgr, $q, CrmAttachments) {
    function CrmMailingAB(id) {
      this.id = id;
      this.mailings = {};
      this.attachments = {};
    }

    angular.extend(CrmMailingAB.prototype, {
      getAutosaveSignature: function() {
        var mailings = angular.copy(this.mailings);
        _.each(mailings, function(mailing) {
          mailing.modified_date = undefined;
        });
        return [
          this.ab,
          mailings,
          this.attachments.a.getAutosaveSignature(),
          this.attachments.b.getAutosaveSignature(),
          this.attachments.c.getAutosaveSignature()
        ];
      },
      load: function load() {
        var crmMailingAB = this;
        if (!crmMailingAB.id) {
          crmMailingAB.ab = {
            name: '',
            status: 'Draft',
            mailing_id_a: null,
            mailing_id_b: null,
            mailing_id_c: null,
            testing_criteria: 'subject',
            winner_criteria: null,
            specific_url: '',
            declare_winning_time: null,
            group_percentage: 10
          };
          var mailingDefaults = {
            open_tracking: "1",
            url_tracking: "1",
            mailing_type:"experiment"
          };
          crmMailingAB.mailings.a = crmMailingMgr.create(mailingDefaults);
          crmMailingAB.mailings.b = crmMailingMgr.create(mailingDefaults);
          mailingDefaults.mailing_type = 'winner';
          crmMailingAB.mailings.c = crmMailingMgr.create(mailingDefaults);
          crmMailingAB.attachments.a = new CrmAttachments(function () {
            return {entity_table: 'civicrm_mailing', entity_id: crmMailingAB.ab.mailing_id_a};
          });
          crmMailingAB.attachments.b = new CrmAttachments(function () {
            return {entity_table: 'civicrm_mailing', entity_id: crmMailingAB.ab.mailing_id_b};
          });
          crmMailingAB.attachments.c = new CrmAttachments(function () {
            return {entity_table: 'civicrm_mailing', entity_id: crmMailingAB.ab.mailing_id_c};
          });

          var dfr = $q.defer();
          dfr.resolve(crmMailingAB);
          return dfr.promise;
        }
        else {
          return crmApi('MailingAB', 'get', {id: crmMailingAB.id})
            .then(function (abResult) {
              if (abResult.count != 1) {
                throw "Failed to load AB Test";
              }
              crmMailingAB.ab = abResult.values[abResult.id];
              return crmMailingAB._loadMailings();
            });
        }
      },
      save: function save() {
        var crmMailingAB = this;
        return crmMailingAB._saveMailings()
          .then(function () {
            return crmApi('MailingAB', 'create', crmMailingAB.ab)
              .then(function (abResult) {
                if (!crmMailingAB.id) {
                  crmMailingAB.id = crmMailingAB.ab.id = abResult.id;
                }
              });
          })
          .then(function () {
            return crmMailingAB;
          });
      },
      submitTest: function submitTest() {
        var crmMailingAB = this;
        var params = {
          id: this.ab.id,
          status: 'Testing',
          approval_date: 'now',
          scheduled_date: this.mailings.a.scheduled_date ? this.mailings.a.scheduled_date : 'now'
        };
        return crmApi('MailingAB', 'submit', params)
          .then(function () {
            return crmMailingAB.load();
          });
      },
      submitFinal: function submitFinal(winner_id) {
        var crmMailingAB = this;
        var params = {
          id: this.ab.id,
          status: 'Final',
          winner_id: winner_id,
          approval_date: 'now',
          scheduled_date: this.mailings.c.scheduled_date ? this.mailings.c.scheduled_date : 'now'
        };
        return crmApi('MailingAB', 'submit', params)
          .then(function () {
            return crmMailingAB.load();
          });
      },
      'delete': function () {
        if (this.id) {
          return crmApi('MailingAB', 'delete', {id: this.id});
        }
        else {
          var d = $q.defer();
          d.resolve();
          return d.promise;
        }
      },
      _loadMailings: function _loadMailings() {
        var crmMailingAB = this;
        var todos = {};
        _.each(['a', 'b', 'c'], function (mkey) {
          if (crmMailingAB.ab['mailing_id_' + mkey]) {
            todos[mkey] = crmMailingMgr.get(crmMailingAB.ab['mailing_id_' + mkey])
              .then(function (mailing) {
                crmMailingAB.mailings[mkey] = mailing;
                crmMailingAB.attachments[mkey] = new CrmAttachments(function () {
                  return {entity_table: 'civicrm_mailing', entity_id: crmMailingAB.ab['mailing_id_' + mkey]};
                });
                return crmMailingAB.attachments[mkey].load();
              });
          }
          else {
            crmMailingAB.mailings[mkey] = crmMailingMgr.create();
            crmMailingAB.attachments[mkey] = new CrmAttachments(function () {
              return {entity_table: 'civicrm_mailing', entity_id: crmMailingAB.ab['mailing_id_' + mkey]};
            });
          }
        });
        return $q.all(todos).then(function () {
          return crmMailingAB;
        });
      },
      _saveMailings: function _saveMailings() {
        var crmMailingAB = this;
        var todos = {};
        var p = $q.when(true);
        _.each(['a', 'b', 'c'], function (mkey) {
          if (!crmMailingAB.mailings[mkey]) {
            return;
          }
          if (crmMailingAB.ab['mailing_id_' + mkey]) {
            crmMailingAB.mailings[mkey].id = crmMailingAB.ab['mailing_id_' + mkey];
          }
          p = p.then(function(){
            return crmMailingMgr.save(crmMailingAB.mailings[mkey])
              .then(function () {
                crmMailingAB.ab['mailing_id_' + mkey] = crmMailingAB.mailings[mkey].id;
                return crmMailingAB.attachments[mkey].save();
              });
          });
        });
        return p.then(function () {
          return crmMailingAB;
        });
      }

    });
    return CrmMailingAB;
  });


angular.module('crmMosaico', CRM.angRequires('crmMosaico'));

angular.module('crmMosaico').controller('CrmMosaicoAdvancedDialogCtrl', function CrmMosaicoAdvancedDialogCtrl($scope, dialogService) {
    var ts = $scope.ts = CRM.ts(null);
  });


angular.module('crmMosaico').directive('crmMosaicoBlockDesign', function($q, crmUiHelp) {
    return {
      scope: {
        crmMosaicoCtrl: '@',
        crmMailing: '@'
      },
      templateUrl: '~/crmMosaico/BlockDesign.html',
      link: function (scope, elm, attr) {
        scope.$parent.$watch(attr.crmMailing, function(newValue){
          scope.mailing = newValue;
        });
        scope.$parent.$watch(attr.crmMosaicoCtrl, function(newValue){
          scope.mosaicoCtrl = newValue;
        });
        scope.crmMailingConst = CRM.crmMailing;
        scope.ts = CRM.ts(null);
        scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});
      }
    };
  });

angular.module('crmMosaico').directive('crmMosaicoBlockMailing', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMosaicoBlockMailing', '~/crmMosaico/BlockMailing.html');
  });

angular.module('crmMosaico').directive('crmMosaicoBlockPreview', function(crmUiHelp) {
    return {
      templateUrl: '~/crmMosaico/BlockPreview.html',
      link: function(scope, elm, attr) {
        scope.$watch(attr.crmMailing, function(newValue) {
          scope.mailing = newValue;
        });
        scope.ts = CRM.ts(null);
        scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});
        scope.testContact = {email: CRM.crmMailing.defaultTestEmail};
        scope.testGroup = {gid: null};

        scope.doPreview = function(mode) {
          scope.$eval(attr.onPreview, {
            preview: {mode: mode}
          });
        };
        scope.doSend = function doSend(recipient) {
          scope.$eval(attr.onSend, {
            preview: {recipient: recipient}
          });
        };

        scope.previewTestGroup = function(e) {
          var $dialog = $(this);
          $dialog.html('<div class="crm-loading-element"></div>').parent().find('button[data-op=yes]').prop('disabled', true);
          CRM.api3({
            contact: ['contact', 'get', {group: scope.testGroup.gid, options: {limit: 0}, return: 'display_name,email'}],
            group: ['group', 'getsingle', {id: scope.testGroup.gid, return: 'title'}]
          }).done(function(data) {
            $dialog.dialog('option', 'title', ts('Send to %1', {1: data.group.title}));
            var count = 0,
            markup = '<ol>';
            _.each(data.contact.values, function(row) {
              if (row.email) {
                count++;
                markup += '<li>' + row.display_name + ' - ' + row.email + '</li>';
              }
            });
            markup += '</ol>';
            markup = '<h4>' + ts('A test message will be sent to %1 people:', {1: count}) + '</h4>' + markup;
            if (!count) {
              markup = '<div class="messages status"><i class="crm-i fa-exclamation-triangle"></i> ' +
              (data.contact.count ? ts('None of the contacts in this group have an email address.') : ts('Group is empty.')) +
              '</div>';
            }
            $dialog
              .html(markup)
              .trigger('crmLoad')
              .parent().find('button[data-op=yes]').prop('disabled', !count);
          });
        };
      }
    };
  });


angular.module('crmMosaico').directive('crmMosaicoBlockSchedule', function(crmMailingSimpleDirective) {
    return crmMailingSimpleDirective('crmMosaicoBlockSchedule', '~/crmMosaico/BlockSchedule.html');
  });

angular.module('crmMosaico').controller('CrmMosaicoCreateFromBaseTemplate', function($scope, crmStatus, dialogService, crmMosaicoTemplates) {
    var ts = $scope.ts = CRM.ts('mosaico');
    var ctrl = $scope.$ctrl = this;

    this.model = {};

    crmMosaicoTemplates.whenLoaded().then(function() {
      ctrl.bases = _.sortBy(crmMosaicoTemplates.getBases(), 'type');
      ctrl.model.base = ctrl.bases[0].type;
    });

    this.create = function() {
      crmStatus({},
        crmMosaicoTemplates.create(ctrl.model)
      )
      .then(function(tpl) {
        crmMosaicoTemplates.edit(tpl);
        dialogService.close('crmMosaicoCreatePopup');
      });
    };
  });


angular.module('crmMosaico').directive('crmMosaicoDistribution', function(crmUiHelp) {
    return {
      scope: {
        crmMailing: '@'
      },
      templateUrl: '~/crmMosaico/Distribution.html',
      link: function (scope, elm, attr) {
        scope.$parent.$watch(attr.crmMailing, function(newValue){
          scope.mailing = newValue;
          if (!scope.mailing.template_options.variantsPct) {
            scope.mailing.template_options.variantsPct = CRM.crmMosaico.variantsPct;
          }
        });
        scope.ts = CRM.ts(null);
        scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});
      }
    };

  });

angular.module('crmMosaico').controller('CrmMosaicoEditorDialogCtrl', function CrmMosaicoEditorDialogCtrl($scope, dialogService) {
    var ts = $scope.ts = CRM.ts(null);
  });


/**
   * The class CrmMosaicoIframe allows you to instantiate and manage a
   * full-screen IFRAME with embedded Mosaico runtime.
   */
  angular.module('crmMosaico').factory('CrmMosaicoIframe', function(crmUiAlert, $q, $timeout, $rootScope, CrmMosaicoSyncMonitor) {

    /**
     * @param Object newOptions
     *  - dimensions: function()
     *  - url: string (optional)
     *  - actions: Object
     *    - save: function(ko, viewModel)
     *    - test: function(ko, viewModel)
     *    - close: function(ko, viewModel) (an alias for "save")
     *  - model: Object. Must have "metadata+content" or "template"
     *    - metadata: string, JSON-encoded
     *    - content: string, JSON-encoded
     *    - template: string, relative file path
     */
    return function CrmMosaicoIframe(options){
      var cfg = {
        url: CRM.url('civicrm/mosaico/iframe', 'snippet=1'),
        dimensions: function resize() {
          var c = CRM.crmMosaico || {};
          var top = 0, left = 0, width = $(window).width(), height = $(window).height();
          if (c.topNav && $(c.topNav).length > 0) {
            top = $(c.topNav).outerHeight() + $(c.topNav).position().top;
            height -= top;
          }
          if (c.leftNav && $(c.leftNav).length > 0) {
            left = $(c.leftNav).width();
            width -= left;
          }
          return {position: 'fixed', left: left + 'px', top: top + 'px', width: width + 'px', height: height + 'px'};
        }
      };
      angular.extend(cfg, options);

      var model = cfg.model, actions = cfg.actions;
      var isVisible = false, $iframe = null, iframe = null;
      var syncMonitor = new CrmMosaicoSyncMonitor();

      if (actions.save && actions.close) {
        throw "Error: Save and Close actions are mutually exclusive";
      }

      var oldOverflow = null;
      function scrollHide() {
        if (oldOverflow === null) {
          oldOverflow = $('body').css('overflow');
          $(document).on('dialogclose', scrollRefresh); // jQuery dialog bug
        }
        $('body').css('overflow', 'hidden');
      }
      function scrollRestore() {
        if (oldOverflow !== null) {
          $(document).off('dialogclose', scrollRefresh); // jQuery dialog bug
          $('body').css('overflow', oldOverflow);
        }
        oldOverflow = null;
      }
      function scrollRefresh() { $('body').css('overflow', 'hidden'); }

      function onResize() {
        if ($iframe) $iframe.css(cfg.dimensions());
      }

      this.render = function render() {
        $iframe = $('<iframe id="crm-mosaico" frameborder="0" class="ui-front">');
        $('body').append($iframe);
        onResize();
        $(window).on('resize', onResize);

        iframe = $iframe[0];
        iframe.setAttribute('src', cfg.url);

        return this;
      };
      this.open = function open() {
        var dfr = $q.defer();

        if ($iframe) {
          dfr.resolve();
          return dfr.promise;
        }

        window.top.crmMosaicoIframe = function(newWindow, Mosaico, config, plugins) {
          plugins.push(function(viewModel) {
            mosaicoPlugin(newWindow.ko, viewModel);
          });

          var ok = true;
          if (model.content) {
            Mosaico.start(config, undefined, JSON.parse(model.metadata), JSON.parse(model.content), plugins);
          } else if (model.template) {
            Mosaico.start(config, model.template, undefined, undefined, plugins);
          } else {
            crmUiAlert({text: 'Cannot edit mailing'});
            ok = false;
          }

          $timeout(function(){
            if (ok) dfr.resolve();
            else dfr.reject();
          }, 150);
        };

        this.render();
        this.show();
        return dfr.promise;
      };

      this.hide = function hide() {
        isVisible = false;
        if ($iframe) {
          syncMonitor.stop($iframe);
          scrollRestore();
          $iframe.hide();
        }
        return this;
      };

      this.show = function show() {
        isVisible = true;
        if ($iframe) {
          scrollHide();
          onResize();
          $iframe.show();
          syncMonitor.start($iframe);
        }
        return this;
      };

      this.destroy = function destroy() {
        this.hide();
        $(window).off('resize', onResize);
        if ($iframe) $iframe.remove();
        iframe = $iframe = null;
        return this;
      };
      function mosaicoPlugin(ko, viewModel) {
        viewModel.logoUrl = null;

        function mkCmd(name, callback) {
          var cmd = {
            name: name, // l10n happens in the template
            enabled: ko.observable(true)
          };
          cmd.execute = function() {
            cmd.enabled(false);
            $rootScope.$apply(function(){
              callback(ko, viewModel);
            });
            cmd.enabled(true);
          };
          return cmd;
        }

        if (actions.save) {
          viewModel.save = mkCmd("Save", actions.save);
        }
        if (actions.close) { // pretend like Mosaico has a "Close" action.
          viewModel.save = mkCmd("Close", actions.close);
        }
        if (actions.test) {
          viewModel.test = mkCmd("Test", actions.test);
        }

        syncMonitor.onSync = function () {
          if (actions.sync) actions.sync(ko, viewModel);
        };
      }

    };
  });



angular.module('crmMosaico').controller('CrmMosaicoMixinCtrl', function CrmMosaicoMixinCtrl($scope, dialogService, crmMosaicoTemplates, crmStatus, CrmMosaicoIframe, $timeout) {

    var crmMosaicoIframe = null, activeDialogs = {};
    $scope.mosaicoCtrl = {
      templates: [],
      select: function(mailing, template) {
        var topt = mailing.template_options = mailing.template_options || {};
        var promise = crmMosaicoTemplates.getFull(template).then(function(tplCtnt){
          topt.mosaicoTemplate = template.id;
          topt.mosaicoMetadata = tplCtnt.metadata;
          topt.mosaicoContent = tplCtnt.content;
          mailing.body_html = tplCtnt.html;
          $scope.mosaicoCtrl.edit(mailing);
        });
        return crmStatus({start: ts('Loading...'), success: null}, promise);
      },
      getTemplate: function(mailing) {
        if (!mailing || !mailing.template_options || !mailing.template_options.mosaicoTemplate) {
          return null;
        }
        var matches = _.where($scope.mosaicoCtrl.templates, {
          id: mailing.template_options.mosaicoTemplate
        });
        return matches.length > 0 ? matches[0] : null;
      },
      reset: function(mailing) {
        if (crmMosaicoIframe) crmMosaicoIframe.destroy();
        crmMosaicoIframe = null;
        delete mailing.template_options.mosaicoTemplate;
        delete mailing.template_options.mosaicoMetadata;
        delete mailing.template_options.mosaicoContent;
        mailing.body_html = '';
      },
      edit: function(mailing) {
        if (crmMosaicoIframe) {
          crmMosaicoIframe.show();
          return;
        }

        function syncModel(viewModel) {
          mailing.body_html = viewModel.exportHTML();
          mailing.template_options = mailing.template_options || {};
          mailing.template_options.mosaicoMetadata = viewModel.exportMetadata();
          mailing.template_options.mosaicoContent = viewModel.exportJSON();
        }

        crmMosaicoIframe = new CrmMosaicoIframe({
          model: {
            template: $scope.mosaicoCtrl.getTemplate(mailing).path,
            metadata: mailing.template_options.mosaicoMetadata,
            content: mailing.template_options.mosaicoContent
          },
          actions: {
            sync: function(ko, viewModel) {
              syncModel(viewModel);
            },
            close: function(ko, viewModel) {
              viewModel.metadata.changed = Date.now();
              syncModel(viewModel);
              $timeout(function(){$scope.save();}, 100);
              crmMosaicoIframe.hide('crmMosaicoEditorDialog');
            },
            test: function(ko, viewModel) {
              syncModel(viewModel);

              var model = {mailing: $scope.mailing, attachments: $scope.attachments};
              var options = CRM.utils.adjustDialogDefaults(angular.extend(
                {autoOpen: false, title: ts('Preview / Test'), width: 550},
                options
              ));
              activeDialogs.crmMosaicoPreviewDialog = 1;
              var pr = dialogService.open('crmMosaicoPreviewDialog', '~/crmMosaico/PreviewDialogCtrl.html', model, options)
                .finally(function(){ delete activeDialogs.crmMosaicoPreviewDialog; });
              return pr;
            }
          }
        });

        return crmStatus({start: ts('Loading...'), success: null}, crmMosaicoIframe.open());
      }
    };
    $scope.openAdvancedOptions = function() {
      var model = {mailing: $scope.mailing, attachments: $scope.attachments};
      var options = CRM.utils.adjustDialogDefaults(angular.extend(
        {
          autoOpen: false,
          title: ts('Advanced Settings'),
          width: 600,
          height: 'auto'
        },
        options
      ));
      activeDialogs.crmMosaicoAdvancedDialog = 1;
      return dialogService.open('crmMosaicoAdvancedDialog', '~/crmMosaico/AdvancedDialogCtrl.html', model, options)
        .finally(function(){ delete activeDialogs.crmMosaicoAdvancedDialog; });
    };

    crmMosaicoTemplates.whenLoaded().then(function(){
      $scope.mosaicoCtrl.templates = crmMosaicoTemplates.getAll();
      $scope.mosaicoCtrl.categoryFilters = _.transform(crmMosaicoTemplates.getCategories(), function(filters, category) {
        filters.push({id: filters.length, text: category.label, filter: {category_id: category.value}});
      }, [{id: 0, text: ts('Base Template'), filter: {isBase: true}}]);
    });

    $scope.$on("$destroy", function() {
      angular.forEach(activeDialogs, function(v,name){
        dialogService.cancel(name);
      });
      if (crmMosaicoIframe) {
        crmMosaicoIframe.destroy();
        crmMosaicoIframe = null;
      }
    });

  });


angular.module('crmMosaico').controller('CrmMosaicoPreviewDialogCtrl', function CrmMosaicoPreviewDialogCtrl($scope, crmMailingMgr, crmMailingPreviewMgr, crmBlocker, crmStatus) {
    var ts = $scope.ts = CRM.ts(null);
    var block = $scope.block = crmBlocker();
    $scope.previewMailing = function previewMailing(mailing, mode) {
      return crmMailingPreviewMgr.preview(mailing, mode);
    };
    $scope.sendTest = function sendTest(mailing, attachments, recipient) {
      var savePromise = crmMailingMgr.save(mailing)
        .then(function() {
          return attachments.save();
        });
      return block(crmStatus({start: ts('Saving...'), success: ''}, savePromise)
        .then(function() {
          crmMailingPreviewMgr.sendTest(mailing, recipient);
        }));
    };
  });


angular.module('crmMosaico').factory('crmMosaicoPrompt', function($q) {
    return function crmMosaicoPrompt(question, defaultName) {
      var dfr = $q.defer();
      var answer = window.prompt(question, defaultName);

      if (!_.isEmpty(answer)) dfr.resolve(answer);
      else dfr.reject();

      return dfr.promise;
    };
  });

angular.module('crmMosaico').component('crmMosaicoSearchTemplateCreateButton', {
    bindings: {
      tpl: '<',
      refresh: '&'
    },
    templateUrl: '~/crmMosaico/SearchTemplateListButtons.html',
    controller: function($scope, crmApi4, crmStatus, crmMosaicoTemplates) {
      var ts = $scope.ts = CRM.ts('mosaico'),
        ctrl = this;

      this.copyTpl = function(tpl) {
        crmStatus(
          {start: ts('Copying...'), success: ts('Copied')},
          crmMosaicoTemplates.clone({id: tpl.id, title: tpl.title + ' ' + ts('(copy)')})
        )
        .then(function(newTpl) {
          return ctrl.editTpl(newTpl);
        });
      };

      this.deleteTpl = function(tpl) {
        return crmStatus(
          {start: ts('Deleting...'), success: ts('Deleted')},
          crmMosaicoTemplates.delete(tpl)
        ).then(ctrl.refresh);
      };

      this.editTpl = function(tpl) {
        crmMosaicoTemplates.edit(tpl)
          .then(ctrl.refresh);
      };

    }
  });

angular.module('crmMosaico').component('crmMosaicoSearchTemplateListButtons', {
    bindings: {
      tpl: '<',
      refresh: '&'
    },
    templateUrl: '~/crmMosaico/SearchTemplateListButtons.html',
    controller: function($scope, crmApi4, crmStatus, crmMosaicoTemplates) {
      var ts = $scope.ts = CRM.ts('mosaico'),
        ctrl = this;

      this.copyTpl = function(tpl) {
        crmStatus(
          {start: ts('Copying...'), success: ts('Copied')},
          crmMosaicoTemplates.clone({id: tpl.id, title: tpl.title + ' ' + ts('(copy)')})
        )
        .then(function(newTpl) {
          return ctrl.editTpl(newTpl);
        });
      };

      this.deleteTpl = function(tpl) {
        return crmStatus(
          {start: ts('Deleting...'), success: ts('Deleted')},
          crmMosaicoTemplates.delete(tpl)
        ).then(ctrl.refresh);
      };

      this.editTpl = function(tpl) {
        crmMosaicoTemplates.edit(tpl)
          .then(ctrl.refresh);
      };

    }
  });

angular.module('crmMosaico').directive('crmMosaicoSubjectList', function(crmUiHelp) {
    return {
      scope: {
        crmMailing: '@'
      },
      templateUrl: '~/crmMosaico/SubjectList.html',
      link: function (scope, elm, attr) {
        scope.$parent.$watch(attr.crmMailing, function(newValue){
          scope.mailing = newValue;
        });
        scope.ts = CRM.ts(null);
        scope.hs = crmUiHelp({file: 'CRM/Mailing/MailingUI'});

        scope.addSubj = function addSubj() {
          scope.mailing.template_options.variants = [
            {subject: scope.mailing.subject},
            {subject: scope.mailing.subject}
          ]
        };

        scope.rmSubj = function rmSubj(vid) {
          var m = scope.mailing;
          m.template_options.variants.splice(vid, 1);
          if (m.template_options.variants.length === 1) {
            m.subject = m.template_options.variants[0].subject;
            delete m.template_options.variants;
          }
        };

        scope.labels = ['A', 'B'];
      }
    };

  });

/**
   * The class CrmMosaicoSyncMonitor monitors the Mosaico IFRAME and decides when to fire synchronization events.
   * It follows these rules:
   *
   * - The user alternates between periods of being "idle" (no clicking) or "active" (clicking).
   * - Most clicks are interpreted as activity.
   * - Some clicks (arrow keys, home, end, mouse-scrolling) do not count as activity.
   * - Fire `onSync()` after a (moderately) short period of idleness (eg `idleTimeout=5000`).
   * - Fire `onSync()` after a (moderately) long period of activity (eg `activeTimeout=60000`).
   * - Do not fire `onSync()` while there is active input (mouse-down or key-down).
   */
  angular.module('crmMosaico').factory('CrmMosaicoSyncMonitor', function($interval) {
    var verbose = function() {};

    /**
     * @param Object options
     * - interval (int, ms): How frequently to poll and decide about whether to sync.
     * - idleTimeout (int, ms): After $X period of idle usage, fire a sync.
     * - activeTimeout (int, ms): After $X period of active usage, fire a sync.
     * - onSync (function): The function to call periodically.
     */
    return function CrmMosaicoSyncMonitor(options) {
      var defaults = {
        interval: 500,
        idleTimeout: 4 * 1000,
        activeTimeout: 2 * 60 * 1000,
        onSync: function() {}
      };
      angular.extend(this, defaults, options);

      var monitor = this, intervalHandle = null, startCount = 0;
      var isKeyDown, isMouseDown, lastInput, lastSync;

      function ding(e) {
        lastInput = (new Date()).getTime();
        verbose('CrmMosaicoSyncMonitor: activity: ', lastInput, e.type);
      }
      function onMouseDown(e) { isMouseDown = true; ding(e); }
      function onMouseUp(e) { isMouseDown = false; ding(e); }
      function onKeyDown(e) { isKeyDown = true; ding(e); }
      function onKeyUp(e) {
        isKeyDown = false;
        if (e.keyCode < 35 || e.keyCode > 40) {
          ding(e);
        }
      }

      function onPoll() {
        if (isKeyDown || isMouseDown) {
          verbose('CrmMosaicoSyncMonitor: onPoll: defer pending input:', isKeyDown, isMouseDown);
          return;
        }
        if (lastInput === null) {
          return;
        }
        var now = (new Date()).getTime();
        if (now > lastSync + monitor.activeTimeout || now > lastInput + monitor.idleTimeout) {
          lastSync = now;
          lastInput = null;
          verbose('CrmMosaicoSyncMonitor: onPoll: fire', now);
          monitor.onSync();
        }
      }

      this.start = function start($iframe) {
        switch (++startCount) {
          case 1:
            return $iframe.load(function () {_start($iframe);});
          default:
            return _start($iframe);
        }
      };

      function _start($iframe) {
        verbose('CrmMosaicoSyncMonitor: start');
        intervalHandle = $interval(onPoll, monitor.interval);

        isKeyDown = isMouseDown = false;
        lastInput = null;
        lastSync = 0;

        var tgt = $iframe[0].contentDocument;
        tgt.addEventListener('mousedown', onMouseDown, true);
        tgt.addEventListener('mouseup', onMouseUp, true);
        tgt.addEventListener('keydown', onKeyDown, true);
        tgt.addEventListener('keyup', onKeyUp, true);
      }

      this.stop = function stop($iframe) {
        verbose('CrmMosaicoSyncMonitor: stop');
        $interval.cancel(intervalHandle);
        intervalHandle = null;

        var tgt = $iframe[0].contentDocument;
        tgt.removeEventListener('mousedown', onMouseDown, true);
        tgt.removeEventListener('mouseup', onMouseUp, true);
        tgt.removeEventListener('keydown', onKeyDown, true);
        tgt.removeEventListener('keyup', onKeyUp, true);
      };
    };
  });


angular.module('crmMosaico').directive('crmMosaicoTemplateItem', function () {
    return {
      restrict: 'AE',
      templateUrl: '~/crmMosaico/TemplateItem.html',
      scope: {
        crmMosaicoTemplateItem: '='
      },
      link: function ($scope, $el, $attr) {
        var mainActionLabels = {
          select: "Select",
          selected: "Edit",
          configure: "Edit",
          new: "New"
        };

        var ts = $scope.ts = CRM.ts('mosaico');
        $scope.mainActionLabel = "";

        $scope.$watch('crmMosaicoTemplateItem', function (newValue) {
          $scope.myOptions = newValue;
          if (newValue.state) {
            $scope.mainActionLabel = mainActionLabels[newValue.state];
          }
        });
        $scope.hasAction = function hasAction(action) {
          if (!$attr['on' + action]) return false;
          if ($attr['check' + action]) {
            if (!$scope.$parent.$eval($attr['check' + action])) {
              return false;
            }
          }
          return true;
        };
        $scope.fireAction = function fireAction(action) {
          $scope.$parent.$eval($attr[action], {});
        };
      }
    };
  });

angular.module('crmMosaico').factory('crmMosaicoTemplates', function ($q, crmApi, $timeout, CrmMosaicoIframe, crmBlocker, crmStatus) {
    var ts = CRM.ts(null);
    var cache = {};
    var block = crmBlocker();
    var currentIframe, warmTplId;

    function filterBase(base) {
      return {
        id: 'base-' + base.name,
        baseDetails: base,
        title: ts('Empty Template'),
        type: base.title,
        thumbnail: base.thumbnail,
        path: base.path,
        isBase: true,
        category_id: null,
        category: ts('Base Template'),
        isHidden: base.is_hidden
      };
    }
    function filterTemplate(tpl) {
      var base = cache.basesByName[tpl.base];
      return {
        id: tpl.id,
        baseDetails: base,
        title: tpl.title,
        type: base.title,
        thumbnail: base.thumbnail,
        path: base.path,
        base: tpl.base,
        category_id: tpl.category_id,
        category: tpl.category_id && cache.categoriesByValue[tpl.category_id] ? cache.categoriesByValue[tpl.category_id].label : '',
        isBase: false
      };
    }

    crmApi({
      bases: ['MosaicoBaseTemplate', 'get', {sequential: 1, options: {sort: 'title ASC', limit: 0}}],
      templates: ['MosaicoTemplate', 'get', {sequential: 1, options: {sort: 'title ASC', limit: 0}, return: ['title', 'base', 'category_id']}],
      categories: ['OptionValue', 'get', {sequential: 1, is_active: 1, option_group_id: 'mailing_template_category', options: {sort: 'weight ASC', limit: 0}, return: ['value', 'label']}]
    }).then(function(r){
      cache.categories = r.categories.values;
      cache.categoriesByValue = _.indexBy(r.categories.values, 'value');
      cache.basesByName = _.indexBy(r.bases.values, 'name');
      cache.bases = _.map(r.bases.values, filterBase);
      cache.configured = _.map(r.templates.values, filterTemplate);
      cache.all = _.union(cache.bases, cache.configured);
    });

    function arrayDel(array, item) {
      var p = _.indexOf(array, item);
      if (p >= 0) array.splice(p, 1);
    }

    function register(tpl) {
      cache.configured.push(tpl);
      cache.all.push(tpl);
      return tpl;
    }

    function getFull(template) {
      if (!template.isBase) {
        return crmApi('MosaicoTemplate', 'getsingle', {id: template.id});
      }
      return $q(function(resolve){
        $timeout(function(){resolve({});}, 100);
      });
    }

    return {
      whenLoaded: function whenLoaded() {
        return $q(function(resolve) {
          var poll = function() {
            if (cache.all !== undefined) resolve();
            else $timeout(poll, 100);
          };
          poll();
        });
      },
      create: function(params) {
        return crmApi('MosaicoTemplate', 'create', params).then(function(r){
          return register(filterTemplate(r.values[r.id]));
        });
      },
      clone: function(params) {
        return crmApi('MosaicoTemplate', 'clone', params).then(function(r){
          return register(filterTemplate(r.values[r.id]));
        });
      },
      'delete': function(tpl) {
        if (tpl.isBase) throw "Cannot delete base template";
        return crmApi('MosaicoTemplate', 'delete', {id: tpl.id}).then(function(){
          arrayDel(cache.configured, tpl);
          arrayDel(cache.all, tpl);
        });
      },
      getFull: getFull,
      getBases: function() {
        return cache.bases.filter((template) => !template.isHidden);
      },
      getConfigured: function(){ return cache.configured; },
      getAll: function() {
        return cache.all.filter((template) => !template.isHidden);
      },
      getCategories: function() {
        return cache.categories;
      },
      save: function(tplId, viewModel) {
        viewModel.metadata.changed = Date.now();
        return crmApi('MosaicoTemplate', 'create', {
          id: tplId,
          html: viewModel.exportHTML(),
          metadata: viewModel.exportMetadata(),
          content: viewModel.exportJSON()
        });
      },
      edit: function(tpl) {
        function save(tplId, viewModel) {
          viewModel.metadata.changed = Date.now();
          return crmApi('MosaicoTemplate', 'create', {
            id: tplId,
            html: viewModel.exportHTML(),
            metadata: viewModel.exportMetadata(),
            content: viewModel.exportJSON()
          });
        }

        return $q(function(resolve, reject) {
          if (block.check()) {
            return reject({});
          }

          if (warmTplId === tpl.id) {
            currentIframe.show();
            return reject({});
          }

          warmTplId = tpl.id;
          var openPromise = getFull(tpl).then(function(fullTpl) {
            if (currentIframe) currentIframe.destroy();
            var base = tpl.baseDetails || cache.basesByName[tpl.base];
            currentIframe = new CrmMosaicoIframe({
              model: {
                template: base.path,
                metadata: fullTpl.metadata,
                content: fullTpl.content
              },
              actions: {
                sync: function(ko, viewModel) {
                  var savePromise = save(tpl.id, viewModel);
                  crmStatus({start: ts('Saving'), success: ts('Saved')}, savePromise);
                },
                save: function(ko, viewModel) {
                  var savePromise = save(tpl.id, viewModel).then(function(result) {
                    currentIframe.hide();
                    resolve(result);
                  });
                  crmStatus({start: ts('Saving'), success: ts('Saved')}, savePromise);
                }
              }
            });
            return currentIframe.open();
          });

          block(crmStatus({start: ts('Loading...'), success: null}, openPromise));
        });
      }
    };
  });


angular.module('crmResource', []);

  angular.module('crmResource').factory('crmResource', function($q, $http) {
    var deferreds = {}; // null|object; deferreds[url][idx] = Deferred;
    var templates = null; // null|object; templates[url] = HTML;

    var notify = function notify() {
      var oldDfrds = deferreds;
      deferreds = null;

      angular.forEach(oldDfrds, function(dfrs, url) {
        if (templates[url]) {
          angular.forEach(dfrs, function(dfr) {
            dfr.resolve({
              status: 200,
              headers: function(name) {
                var headers = {'Content-type': 'text/html'};
                return name ? headers[name] : headers;
              },
              data: templates[url]
            });
          });
        }
        else {
          angular.forEach(dfrs, function(dfr) {
            dfr.reject({status: 500}); // FIXME
          });
        }
      });
    };

    var moduleUrl = CRM.angular.bundleUrl;
    $http.get(moduleUrl)
      .then(function httpSuccess(response) {
        templates = [];
        angular.forEach(response.data, function(module) {
          if (module.partials) {
            angular.extend(templates, module.partials);
          }
          if (module.strings) {
            CRM.addStrings(module.domain, module.strings);
          }
        });
        notify();
      }, function httpError() {
        templates = [];
        notify();
      });

    return {
      getUrl: function getUrl(url) {
        if (templates !== null) {
          return templates[url];
        }
        else {
          var deferred = $q.defer();
          if (!deferreds[url]) {
            deferreds[url] = [];
          }
          deferreds[url].push(deferred);
          return deferred.promise;
        }
      }
    };
  });

  angular.module('crmResource').config(function($provide) {
    $provide.decorator('$templateCache', function($delegate, $http, $q, crmResource) {
      var origGet = $delegate.get;
      var urlPat = /^~\//;
      $delegate.get = function(url) {
        if (urlPat.test(url)) {
          return crmResource.getUrl(url);
        }
        else {
          return origGet.call(this, url);
        }
      };
      return $delegate;
    });
  });


angular.module('crmRouteBinder', CRM.angRequires('crmRouteBinder'));
  var pendingUpdates = null, activeTimer = null, registered = false, ignorable = {};

  function registerGlobalListener($injector) {
    if (registered) return;
    registered = true;

    $injector.get('$rootScope').$on('$routeUpdate', function () {
      if (null === pendingUpdates) {
        $injector.get('$route').reload();
      }
    });
  }

  var formats = {
    json: {
      watcher: '$watchCollection',
      decode: angular.fromJson,
      encode: angular.toJson,
      default: {}
    },
    raw: {
      watcher: '$watch',
      decode: function(v) { return v; },
      encode: function(v) { return v; },
      default: ''
    },
    int: {
      watcher: '$watch',
      decode: function(v) { return parseInt(v); },
      encode: function(v) { return v; },
      default: 0
    },
    bool: {
      watcher: '$watch',
      decode: function(v) { return v === '1'; },
      encode: function(v) { return v ? '1' : '0'; },
      default: false
    }
  };

  angular.module('crmRouteBinder').config(function ($provide) {
    $provide.decorator('$rootScope', function ($delegate, $injector, $parse) {
      Object.getPrototypeOf($delegate).$bindToRoute = function (options) {
        registerGlobalListener($injector);

        options.format = options.format || 'json';
        var fmt = _.clone(formats[options.format]);
        if (options.deep) {
          fmt.watcher = '$watch';
        }
        if (options.default === undefined) {
          options.default = fmt.default;
        }
        var value,
          _scope = this,
          $route = $injector.get('$route'),
          $timeout = $injector.get('$timeout');

        if (options.param in $route.current.params) {
          value = fmt.decode($route.current.params[options.param]);
        }
        else {
          value = _.cloneDeep(options.default);
          ignorable[options.param] = fmt.encode(options.default);
        }
        $parse(options.expr).assign(_scope, value);
        _scope[fmt.watcher](options.expr, function (newValue) {
          var encValue = fmt.encode(newValue);
          if (!_.isEqual(newValue, options.default) && $route.current.params[options.param] === encValue) {
            return;
          }

          pendingUpdates = pendingUpdates || {};
          pendingUpdates[options.param] = encValue;
          var p = angular.extend({}, $route.current.params, pendingUpdates);

          angular.forEach(ignorable, function(v, k) {
            if (p[k] === v) {
              delete p[k];
            }
          });
          if (_.isEqual(newValue, options.default)) {
            p[options.param] = null;
          }

          $route.updateParams(p);

          if (activeTimer) $timeout.cancel(activeTimer);
          activeTimer = $timeout(function () {
            pendingUpdates = null;
            activeTimer = null;
            ignorable = {};
          }, 50);
        }, options.deep);
      };

      return $delegate;
    });
  });


angular.module('crmStatusPage', CRM.angRequires('crmStatusPage'));
  angular.module('crmStatusPage').config( function($routeProvider) {
    $routeProvider.when('/status', {
      controller: 'crmStatusPageCtrl',
      templateUrl: '~/crmStatusPage/StatusPage.html',

      resolve: {
        statusData: function(crmApi) {
          return crmApi('System', 'check', {sequential: 1, options: {limit: 0, sort: 'severity_id DESC'}});
        }
      }
    });

  }
);

angular.module('crmStatusPage').controller('crmStatusPageCtrl',
    function($scope, crmApi, crmStatus, statusData) {
      $scope.ts = CRM.ts();
      $scope.help = CRM.help;
      $scope.formatDate = CRM.utils.formatDate;
      $scope.statuses = statusData.values;
      function refresh(apiCalls, title) {
        title = title || 'Untitled operation';
        apiCalls = (apiCalls || []).concat([['System', 'check', {sequential: 1, options: {limit: 0, sort: 'severity_id DESC'}}]]);
        $('#crm-status-list').block();
        crmApi(apiCalls, true)
          .then(function(results) {
            $scope.statuses = results[results.length - 1].values;
            results.forEach(function(result) {
              if (result.is_error) {
                var error_message = ts(result.error_message);
                if (typeof(result.debug_information) !== 'undefined') {
                  error_message += '<div class="status-debug-information">' +
                      '<b>' + ts('Debug information') + ':</b><br>' +
                      result.debug_information + '</div>';
                }
                CRM.alert(error_message, ts('Operation failed: ' + title), 'error');
                }
              });
            $('#crm-status-list').unblock();
          });
      }
      $scope.setPref = function(status, until, visible) {
        refresh([
          ['StatusPreference', 'create', {
            name: status.name,
            ignore_severity: visible ? 0 : status.severity,
            hush_until: until
          }]
        ], 'Set preference');
      };

      $scope.countVisible = function(visibility) {
        return _.filter($scope.statuses, function(s) {
          return s.is_visible == visibility && s.severity_id >= 2;
        }).length;
      };

      $scope.doAction = function(action) {
        function run() {
          switch (action.type) {
            case 'href':
              window.location = CRM.url(action.params.path, action.params.query, action.params.mode);
              break;

            case 'api3':
              refresh([action.params], action.title);
              break;
          }
        }

        if (action.confirm) {
          CRM.confirm({
            title: action.title,
            message: action.confirm
          }).on('crmConfirm:yes', run);
        } else {
          run();
        }
      };
    });


angular.module('crmStatusPage')
    .filter('trusted', function($sce){ return $sce.trustAsHtml; })
    .directive('statuspagePopupMenu', function($timeout) {
      return {
        templateUrl: '~/crmStatusPage/SnoozeOptions.html',
        transclude: true,

        link: function(scope, element, attr) {
          element.on('click', '.hush-menu-button', function() {
            $timeout(function() {
              $('ul', element).show().menu();
              element.closest('h3').addClass('menuopen');
              $('body').one('click', function() {
                $('ul', element).menu('destroy').hide();
                element.closest('h3').removeClass('menuopen');
              });
            });
          });
          element.on('click', 'button:not(.hush-menu-button), li', function() {
            $(this).closest('div.crm-status-item').slideUp();
          });
        }
      };
    });


var uidCount = 0,
    pageTitle = 'CiviCRM',
    documentTitle = 'CiviCRM';

  angular.module('crmUi', CRM.angRequires('crmUi'))
    .directive('crmUiAccordion', function() {
      return {
        scope: {
          crmUiAccordion: '='
        },
        template: '<div ng-class="cssClasses"><div class="crm-accordion-header">{{crmUiAccordion.title}} <a crm-ui-help="help" ng-if="help"></a></div><div class="crm-accordion-body" ng-transclude></div></div>',
        transclude: true,
        link: function (scope, element, attrs) {
          scope.cssClasses = {
            'crm-accordion-wrapper': true,
            collapsed: scope.crmUiAccordion.collapsed
          };
          scope.help = null;
          scope.$watch('crmUiAccordion', function(crmUiAccordion) {
            if (crmUiAccordion && crmUiAccordion.help) {
              scope.help = crmUiAccordion.help.clone({}, {
                title: crmUiAccordion.title
              });
            }
          });
        }
      };
    })
    .service('crmUiAlert', function($compile, $rootScope, $templateRequest, $q) {
      var count = 0;
      return function crmUiAlert(params) {
        var id = 'crmUiAlert_' + (++count);
        var tpl = null;
        if (params.templateUrl) {
          tpl = $templateRequest(params.templateUrl);
        }
        else if (params.template) {
          tpl = params.template;
        }
        if (tpl) {
          params.text = '<div id="' + id + '"></div>'; // temporary stub
        }
        var result = CRM.alert(params.text, params.title, params.type, params.options);
        if (tpl) {
          $q.when(tpl, function(html) {
            var scope = params.scope || $rootScope.$new();
            var linker = $compile(html);
            $('#' + id).append($(linker(scope)));
          });
        }
        return result;
      };
    })
    .directive('crmUiDatepicker', function () {
      return {
        restrict: 'AE',
        require: 'ngModel',
        scope: {
          crmUiDatepicker: '='
        },
        link: function (scope, element, attrs, ngModel) {
          ngModel.$render = function () {
            element.val(ngModel.$viewValue).change();
          };

          element
            .crmDatepicker(scope.crmUiDatepicker)
            .on('change', function() {
              var requiredLength = 19;
              if (scope.crmUiDatepicker && scope.crmUiDatepicker.time === false) {
                requiredLength = 10;
              }
              if (scope.crmUiDatepicker && scope.crmUiDatepicker.date === false) {
                requiredLength = 8;
              }
              ngModel.$setValidity('incompleteDateTime', !($(this).val().length && $(this).val().length !== requiredLength));
            });
        }
      };
    })
    .directive('crmUiDebug', function ($location) {
      return {
        restrict: 'AE',
        scope: {
          crmUiDebug: '@'
        },
        template: function() {
          var args = $location.search();
          if (args && args.angularDebug) {
            var jsonTpl = (CRM.angular.modules.indexOf('jsonFormatter') < 0) ? '<pre>{{data|json}}</pre>' : '<json-formatter json="data" open="1"></json-formatter>';
            return '<div crm-ui-accordion=\'{title: ts("Debug (%1)", {1: crmUiDebug}), collapsed: true}\'>' + jsonTpl + '</div>';
          }
          return '';
        },
        link: function(scope, element, attrs) {
          var args = $location.search();
          if (args && args.angularDebug) {
            scope.ts = CRM.ts(null);
            scope.$parent.$watch(attrs.crmUiDebug, function(data) {
              scope.data = data;
            });
          }
        }
      };
    })
    .directive('crmUiField', function() {
      var templateUrls = {
        default: '~/crmUi/field.html',
        checkbox: '~/crmUi/field-cb.html'
      };

      return {
        require: '^crmUiIdScope',
        restrict: 'EA',
        scope: {
          crmUiField: '='
        },
        templateUrl: function(tElement, tAttrs){
          var layout = tAttrs.crmLayout ? tAttrs.crmLayout : 'default';
          return templateUrls[layout];
        },
        transclude: true,
        link: function (scope, element, attrs, crmUiIdCtrl) {
          $(element).addClass('crm-section');
          scope.help = null;
          scope.$watch('crmUiField', function(crmUiField) {
            if (crmUiField && crmUiField.help) {
              scope.help = crmUiField.help.clone({}, {
                title: crmUiField.title
              });
            }
          });
        }
      };
    })
    .directive('crmUiId', function () {
      return {
        require: '^crmUiIdScope',
        restrict: 'EA',
        link: {
          pre: function (scope, element, attrs, crmUiIdCtrl) {
            var id = crmUiIdCtrl.get(attrs.crmUiId);
            element.attr('id', id);
          }
        }
      };
    })
    .service('crmUiHelp', function(){
      function FieldHelp(options) {
        this.options = options;
      }
      angular.extend(FieldHelp.prototype, {
        get: function(n) {
          return this.options[n];
        },
        open: function open() {
          CRM.help(this.options.title, {id: this.options.id, file: this.options.file});
        },
        clone: function clone(options, defaults) {
          return new FieldHelp(angular.extend({}, defaults, this.options, options));
        }
      });
      return function(defaults){
        return function(options) {
          if (_.isString(options)) {
            options = {id: options};
          }
          return new FieldHelp(angular.extend({}, defaults, options));
        };
      };
    })
    .directive('crmUiHelp', function() {
      return {
        restrict: 'EA',
        link: function(scope, element, attrs) {
          setTimeout(function() {
            var crmUiHelp = scope.$eval(attrs.crmUiHelp);
            var title = crmUiHelp && crmUiHelp.get('title') ? ts('%1 Help', {1: crmUiHelp.get('title')}) : ts('Help');
            element.attr('title', title);
          }, 50);

          element
            .addClass('helpicon')
            .attr('href', '#')
            .on('click', function(e) {
              e.preventDefault();
              scope.$eval(attrs.crmUiHelp).open();
            });
        }
      };
    })
    .directive('crmUiFor', function ($parse, $timeout) {
      return {
        require: '^crmUiIdScope',
        restrict: 'EA',
        template: '<span ng-class="cssClasses"><span ng-transclude/><span crm-ui-visible="crmIsRequired" class="crm-marker" title="This field is required.">*</span></span>',
        transclude: true,
        link: function (scope, element, attrs, crmUiIdCtrl) {
          scope.crmIsRequired = false;
          scope.cssClasses = {};

          if (!attrs.crmUiFor) return;

          var id = crmUiIdCtrl.get(attrs.crmUiFor);
          element.attr('for', id);
          var ngModel = null;

          var updateCss = function () {
            scope.cssClasses['crm-error'] = !ngModel.$valid && !ngModel.$pristine;
          };
          var init = function (retries, retryDelay) {
            var input = $('#' + id);
            if (input.length === 0 && !attrs.crmUiForceRequired) {
              if (retries) {
                $timeout(function(){
                  init(retries-1, retryDelay);
                }, retryDelay);
              }
              return;
            }

            if (attrs.crmUiForceRequired) {
              scope.crmIsRequired = true;
              return;
            }

            var tgtScope = scope;//.$parent;
            if (attrs.crmDepth) {
              for (var i = attrs.crmDepth; i > 0; i--) {
                tgtScope = tgtScope.$parent;
              }
            }

            if (input.attr('ng-required')) {
              scope.crmIsRequired = scope.$parent.$eval(input.attr('ng-required'));
              scope.$parent.$watch(input.attr('ng-required'), function (isRequired) {
                scope.crmIsRequired = isRequired;
              });
            }
            else {
              scope.crmIsRequired = input.prop('required');
            }

            ngModel = $parse(attrs.crmUiFor)(tgtScope);
            if (ngModel) {
              ngModel.$viewChangeListeners.push(updateCss);
            }
          };

          $timeout(function(){
            init(3, 100);
          });
        }
      };
    })
    .directive('crmUiIdScope', function () {
      return {
        restrict: 'EA',
        scope: {},
        controllerAs: 'crmUiIdCtrl',
        controller: function($scope) {
          var ids = {};
          this.get = function(name) {
            if (!ids[name]) {
              ids[name] = "crmUiId_" + (++uidCount);
            }
            return ids[name];
          };
        },
        link: function (scope, element, attrs) {}
      };
    })
    .directive('crmUiIframe', function ($parse) {
      return {
        scope: {
          crmUiIframeSrc: '@', // expression which evaluates to a URL
          crmUiIframe: '@' // expression which evaluates to HTML content
        },
        link: function (scope, elm, attrs) {
          var iframe = $(elm)[0];
          iframe.setAttribute('width', '100%');
          iframe.setAttribute('height', '250px');
          iframe.setAttribute('frameborder', '0');

          var refresh = function () {
            if (attrs.crmUiIframeSrc) {
              iframe.setAttribute('src', scope.$parent.$eval(attrs.crmUiIframeSrc));
            }
            else {
              var iframeHtml = scope.$parent.$eval(attrs.crmUiIframe);

              var doc = iframe.document;
              if (iframe.contentDocument) {
                doc = iframe.contentDocument;
              }
              else if (iframe.contentWindow) {
                doc = iframe.contentWindow.document;
              }

              doc.open();
              doc.writeln(iframeHtml);
              doc.close();
            }
          };
          $(elm).parent().on('dialogresize dialogopen', function(e, ui) {
            $(this).css({padding: '0', margin: '0', overflow: 'hidden'});
            iframe.setAttribute('height', '' + $(this).innerHeight() + 'px');
          });

          $(elm).parent().on('dialogresize', function(e, ui) {
            iframe.setAttribute('class', 'resized');
          });

          scope.$parent.$watch(attrs.crmUiIframe, refresh);
        }
      };
    })
    .directive('crmUiInsertRx', function() {
      return {
        link: function(scope, element, attrs) {
          scope.$on(attrs.crmUiInsertRx, function(e, tokenName) {
            CRM.wysiwyg.insert(element, tokenName);
            $(element).select2('close').select2('val', '');
            CRM.wysiwyg.focus(element);
          });
        }
      };
    })
    .directive('crmUiRichtext', function ($timeout) {
      return {
        require: '?ngModel',
        link: function (scope, elm, attr, ngModel) {

          var editor = CRM.wysiwyg.create(elm);
          if (!ngModel) {
            return;
          }

          if (attr.ngBlur) {
            $(elm).on('blur', function() {
              $timeout(function() {
                scope.$eval(attr.ngBlur);
              });
            });
          }

          ngModel.$render = function(value) {
            editor.done(function() {
              CRM.wysiwyg.setVal(elm, ngModel.$viewValue || '');
            });
          };
        }
      };
    })
    .directive('crmUiLock', function ($parse, $rootScope) {
      var defaultVal = function (defaultValue) {
        var f = function (scope) {
          return defaultValue;
        };
        f.assign = function (scope, value) {
        };
        return f;
      };
      var parse = function (expr, defaultValue) {
        return expr ? $parse(expr) : defaultVal(defaultValue);
      };

      return {
        template: '',
        link: function (scope, element, attrs) {
          var binding = parse(attrs.binding, true);
          var titleLocked = parse(attrs.titleLocked, ts('Locked'));
          var titleUnlocked = parse(attrs.titleUnlocked, ts('Unlocked'));

          $(element).addClass('crm-i lock-button');
          var refresh = function () {
            var locked = binding(scope);
            if (locked) {
              $(element)
                .removeClass('fa-unlock')
                .addClass('fa-lock')
                .prop('title', titleLocked(scope))
              ;
            }
            else {
              $(element)
                .removeClass('fa-lock')
                .addClass('fa-unlock')
                .prop('title', titleUnlocked(scope))
              ;
            }
          };

          $(element).click(function () {
            binding.assign(scope, !binding(scope));
            $rootScope.$digest();
          });

          scope.$watch(attrs.binding, refresh);
          scope.$watch(attrs.titleLocked, refresh);
          scope.$watch(attrs.titleUnlocked, refresh);

          refresh();
        }
      };
    })
    .service('CrmUiOrderCtrl', function(){
      function CrmUiOrderCtrl(defaults){
        this.values = defaults;
      }
      angular.extend(CrmUiOrderCtrl.prototype, {
        get: function get() {
          return this.values;
        },
        getDir: function getDir(name) {
          if (this.values.indexOf(name) >= 0 || this.values.indexOf('+' + name) >= 0) {
            return '+';
          }
          if (this.values.indexOf('-' + name) >= 0) {
            return '-';
          }
          return '';
        },
        remove: function remove(name) {
          var idx = this.values.indexOf(name);
          if (idx >= 0) {
            this.values.splice(idx, 1);
            return true;
          }
          else {
            return false;
          }
        },
        setDir: function setDir(name, dir) {
          return this.toggle(name, dir);
        },
        toggle: function toggle(name, next) {
          if (!next && next !== '') {
            next = '+';
            if (this.remove(name) || this.remove('+' + name)) {
              next = '-';
            }
            if (this.remove('-' + name)) {
              next = '';
            }
          }

          if (next == '+') {
            this.values.unshift('+' + name);
          }
          else if (next == '-') {
            this.values.unshift('-' + name);
          }
        }
      });
      return CrmUiOrderCtrl;
    })
    .directive('crmUiOrder', function(CrmUiOrderCtrl) {
      return {
        link: function(scope, element, attrs){
          var options = angular.extend({var: 'crmUiOrderBy'}, scope.$eval(attrs.crmUiOrder));
          scope[options.var] = new CrmUiOrderCtrl(options.defaults);
        }
      };
    })
    .directive('crmUiOrderBy', function() {
      return {
        link: function(scope, element, attrs) {
          function updateClass(crmUiOrderCtrl, name) {
            var dir = crmUiOrderCtrl.getDir(name);
            element
              .toggleClass('sorting_asc', dir === '+')
              .toggleClass('sorting_desc', dir === '-')
              .toggleClass('sorting', dir === '');
          }

          element.on('click', function(e){
            var tgt = scope.$eval(attrs.crmUiOrderBy);
            tgt[0].toggle(tgt[1]);
            updateClass(tgt[0], tgt[1]);
            e.preventDefault();
            scope.$digest();
          });

          var tgt = scope.$eval(attrs.crmUiOrderBy);
          updateClass(tgt[0], tgt[1]);
        }
      };
    })
    .directive('crmUiSelect', function ($parse, $timeout) {
      return {
        require: '?ngModel',
        priority: 1,
        scope: {
          crmUiSelect: '='
        },
        link: function (scope, element, attrs, ngModel) {

          if (ngModel) {
            ngModel.$render = function () {
              $timeout(function () {
                var newVal = _.cloneDeep(ngModel.$modelValue);
                if (typeof newVal === 'string' && element.select2('container').hasClass('select2-container-multi')) {
                  newVal = newVal.length ? newVal.split(',') : [];
                }
                element.select2('val', newVal);
              });
            };
          }
          function refreshModel() {
            var oldValue = ngModel.$viewValue, newValue = element.select2('val');
            if (oldValue != newValue) {
              scope.$parent.$apply(function () {
                ngModel.$setViewValue(newValue);
              });
            }
          }

          function init() {
            element.crmSelect2(scope.crmUiSelect || {});
            if (ngModel) {
              element.on('change', refreshModel);
            }
          }

          init();
        }
      };
    })
    .directive('onCrmUiSelect', function () {
      return {
        priority: 10,
        link: function (scope, element, attrs) {
          element.on('select2-selecting', function(e) {
            e.preventDefault();
            element.select2('close').select2('val', '');
            scope.$apply(function() {
              scope.$eval(attrs.onCrmUiSelect, {selection: e.val});
            });
          });
        }
      };
    })
    .directive('crmEntityref', function ($parse, $timeout) {
      return {
        require: '?ngModel',
        scope: {
          crmEntityref: '='
        },
        link: function (scope, element, attrs, ngModel) {

          ngModel.$render = function () {
            $timeout(function () {
              var newVal = _.cloneDeep(ngModel.$modelValue);
              if (typeof newVal === 'string' && element.select2('container').hasClass('select2-container-multi')) {
                newVal = newVal.length ? newVal.split(',') : [];
              }
              element.select2('val', newVal);
            });
          };
          function refreshModel() {
            var oldValue = ngModel.$viewValue, newValue = element.select2('val');
            if (oldValue != newValue) {
              scope.$parent.$apply(function () {
                ngModel.$setViewValue(newValue);
              });
            }
          }

          function init() {
            element.crmEntityRef(scope.crmEntityref || {});
            element.on('change', refreshModel);
            $timeout(ngModel.$render);
          }

          init();
        }
      };
    })
    .directive('crmMultipleEmail', function ($parse, $timeout) {
      return {
        require: 'ngModel',
        link: function(scope, element, attrs, ctrl) {
          ctrl.$parsers.unshift(function(viewValue) {
            if (_.isEmpty(viewValue)) {
              ctrl.$setValidity('crmMultipleEmail', true);
              return viewValue;
            }
            var emails = viewValue.split(',');
            var emailRegex = /\S+@\S+\.\S+/;

            var validityArr = emails.map(function(str){
              return emailRegex.test(str.trim());
            });

            if ($.inArray(false, validityArr) > -1) {
              ctrl.$setValidity('crmMultipleEmail', false);
            } else {
              ctrl.$setValidity('crmMultipleEmail', true);
            }
            return viewValue;
          });
        }
      };
    })
    .directive('crmUiTab', function($parse) {
      return {
        require: '^crmUiTabSet',
        restrict: 'EA',
        scope: {
          crmTitle: '@',
          crmIcon: '@',
          count: '@',
          id: '@'
        },
        template: '<div ng-transclude></div>',
        transclude: true,
        link: function (scope, element, attrs, crmUiTabSetCtrl) {
          crmUiTabSetCtrl.add(scope);
        }
      };
    })
    .directive('crmUiTabSet', function() {
      return {
        restrict: 'EA',
        scope: {
          crmUiTabSet: '@',
          tabSetOptions: '@'
        },
        templateUrl: '~/crmUi/tabset.html',
        transclude: true,
        controllerAs: 'crmUiTabSetCtrl',
        controller: function($scope, $parse) {
          var tabs = $scope.tabs = []; // array<$scope>
          this.add = function(tab) {
            if (!tab.id) throw "Tab is missing 'id'";
            tabs.push(tab);
          };
        },
        link: function (scope, element, attrs) {}
      };
    })
    .directive('crmUiValidate', function() {
      return {
        restrict: 'EA',
        require: 'ngModel',
        link: function(scope, element, attrs, ngModel) {
          var validationKey = attrs.crmUiValidateName ? attrs.crmUiValidateName : 'crmUiValidate';
          scope.$watch(attrs.crmUiValidate, function(newValue){
            ngModel.$setValidity(validationKey, !!newValue);
          });
        }
      };
    })
    .directive('crmUiVisible', function($parse) {
      return {
        restrict: 'EA',
        scope: {
          crmUiVisible: '@'
        },
        link: function (scope, element, attrs) {
          var model = $parse(attrs.crmUiVisible);
          function updatecChildren() {
            element.css('visibility', model(scope.$parent) ? 'inherit' : 'hidden');
          }
          updatecChildren();
          scope.$parent.$watch(attrs.crmUiVisible, updatecChildren);
        }
      };
    })
    .directive('crmUiWizard', function() {
      return {
        restrict: 'EA',
        scope: {
          crmUiWizard: '@',
          crmUiWizardNavClass: '@' // string, A list of classes that will be added to the nav items
        },
        templateUrl: '~/crmUi/wizard.html',
        transclude: true,
        controllerAs: 'crmUiWizardCtrl',
        controller: function($scope, $parse) {
          var steps = $scope.steps = []; // array<$scope>
          var crmUiWizardCtrl = this;
          var maxVisited = 0;
          var selectedIndex = null;

          var findIndex = function() {
            var found = null;
            angular.forEach(steps, function(step, stepKey) {
              if (step.selected) found = stepKey;
            });
            return found;
          };
          this.$index = function() { return selectedIndex; };
          this.$first = function() { return this.$index() === 0; };
          this.$last = function() { return this.$index() === steps.length -1; };
          this.$maxVisit = function() { return maxVisited; };
          this.$validStep = function() {
            return steps[selectedIndex] && steps[selectedIndex].isStepValid();
          };
          this.iconFor = function(index) {
            if (index < this.$index()) return 'crm-i fa-check';
            if (index === this.$index()) return 'crm-i fa-angle-double-right';
            return '';
          };
          this.isSelectable = function(step) {
            if (step.selected) return false;
            return this.$validStep();
          };

          /*** @param Object step the $scope of the step */
          this.select = function(step) {
            angular.forEach(steps, function(otherStep, otherKey) {
              otherStep.selected = (otherStep === step);
              if (otherStep === step && maxVisited < otherKey) maxVisited = otherKey;
            });
            selectedIndex = findIndex();
          };
          /*** @param Object step the $scope of the step */
          this.add = function(step) {
            if (steps.length === 0) {
              step.selected = true;
              selectedIndex = 0;
            }
            steps.push(step);
            steps.sort(function(a,b){
              return a.crmUiWizardStep - b.crmUiWizardStep;
            });
            selectedIndex = findIndex();
          };
          this.remove = function(step) {
            var key = null;
            angular.forEach(steps, function(otherStep, otherKey) {
              if (otherStep === step) key = otherKey;
            });
            if (key !== null) {
              steps.splice(key, 1);
            }
          };
          this.goto = function(index) {
            if (index < 0) index = 0;
            if (index >= steps.length) index = steps.length-1;
            this.select(steps[index]);
          };
          this.previous = function() { this.goto(this.$index()-1); };
          this.next = function() { this.goto(this.$index()+1); };
          if ($scope.crmUiWizard) {
            $parse($scope.crmUiWizard).assign($scope.$parent, this);
          }
        },
        link: function (scope, element, attrs) {
          scope.ts = CRM.ts(null);

          element.find('.crm-wizard-buttons button[ng-click^=crmUiWizardCtrl]').click(function () {
            var topOfWizard = element.offset().top;
            var heightOfMenu = $('#civicrm-menu').height() || 0;

            $('html')
              .stop()
              .animate({scrollTop: topOfWizard - heightOfMenu}, 1000);
          });
        }
      };
    })
    .directive('crmUiWizardButtons', function() {
      return {
        require: '^crmUiWizard',
        restrict: 'EA',
        scope: {},
        template: '<span ng-transclude></span>',
        transclude: true,
        link: function (scope, element, attrs, crmUiWizardCtrl) {
          var realButtonsEl = $(element).closest('.crm-wizard').find('.crm-wizard-buttons');
          $(element).appendTo(realButtonsEl);
        }
      };
    })
    .directive('crmIcon', function() {
      return {
        restrict: 'EA',
        link: function (scope, element, attrs) {
          if (element.is('[crm-ui-tab]')) {
            return;
          }
          if (attrs.crmIcon.substring(0,3) == 'fa-') {
            $(element).prepend('<i class="crm-i ' + attrs.crmIcon + '" aria-hidden="true"></i> ');
          }
          else {
            $(element).prepend('<span class="icon ui-icon-' + attrs.crmIcon + '"></span> ');
          }
          if ($(element).is('button:not(.btn)')) {
            $(element).addClass('crm-button');
          }
        }
      };
    })
    .directive('crmUiWizardStep', function() {
      var nextWeight = 1;
      return {
        require: ['^crmUiWizard', 'form'],
        restrict: 'EA',
        scope: {
          crmTitle: '@', // expression, evaluates to a printable string
          crmUiWizardStep: '@', // int, a weight which determines the ordering of the steps
          crmUiWizardStepClass: '@' // string, A list of classes that will be added to the template
        },
        template: '<div class="crm-wizard-step {{crmUiWizardStepClass}}" ng-show="selected" ng-transclude/></div>',
        transclude: true,
        link: function (scope, element, attrs, ctrls) {
          var crmUiWizardCtrl = ctrls[0], form = ctrls[1];
          if (scope.crmUiWizardStep) {
            scope.crmUiWizardStep = parseInt(scope.crmUiWizardStep);
          } else {
            scope.crmUiWizardStep = nextWeight++;
          }
          scope.isStepValid = function() {
            return form.$valid;
          };
          crmUiWizardCtrl.add(scope);
          scope.$on('$destroy', function(){
            crmUiWizardCtrl.remove(scope);
          });
        }
      };
    })
    .directive('crmConfirm', function ($compile, $rootScope, $templateRequest, $q) {
      var defaultFuncs = {
        'disable': function (options) {
          return {
            message: ts('Are you sure you want to disable this?'),
            options: {no: ts('Cancel'), yes: ts('Disable')},
            width: 300,
            title: ts('Disable %1?', {
              1: options.obj.title || options.obj.label || options.obj.name || ts('the record')
            })
          };
        },
        'revert': function (options) {
          return {
            message: ts('Are you sure you want to revert this?'),
            options: {no: ts('Cancel'), yes: ts('Revert')},
            width: 300,
            title: ts('Revert %1?', {
              1: options.obj.title || options.obj.label || options.obj.name || ts('the record')
            })
          };
        },
        'delete': function (options) {
          return {
            message: ts('Are you sure you want to delete this?'),
            options: {no: ts('Cancel'), yes: ts('Delete')},
            width: 300,
            title: ts('Delete %1?', {
              1: options.obj.title || options.obj.label || options.obj.name || ts('the record')
            })
          };
        }
      };
      var confirmCount = 0;
      return {
        link: function (scope, element, attrs) {
          $(element).click(function () {
            var options = scope.$eval(attrs.crmConfirm);
            if (attrs.title && !options.title) {
              options.title = attrs.title;
            }
            var defaults = (options.type) ? defaultFuncs[options.type](options) : {};

            var tpl = null, stubId = null;
            if (!options.message) {
              if (options.templateUrl) {
                tpl = $templateRequest(options.templateUrl);
              }
              else if (options.template) {
                tpl = options.template;
              }
              if (tpl) {
                stubId = 'crmUiConfirm_' + (++confirmCount);
                options.message = '<div id="' + stubId + '"></div>';
              }
            }

            CRM.confirm(_.extend(defaults, options))
              .on('crmConfirm:yes', function() { scope.$apply(attrs.onYes); })
              .on('crmConfirm:no', function() { scope.$apply(attrs.onNo); });

            if (tpl && stubId) {
              $q.when(tpl, function(html) {
                var scope = options.scope || $rootScope.$new();
                if (options.export) {
                  angular.extend(scope, options.export);
                }
                var linker = $compile(html);
                $('#' + stubId).append($(linker(scope)));
              });
            }
          });
        }
      };
    })
    .directive('crmPageTitle', function($timeout) {
      return {
        scope: {
          crmDocumentTitle: '='
        },
        link: function(scope, $el, attrs) {
          function update() {
            $timeout(function() {
              var newPageTitle = _.trim($el.html()),
                newDocumentTitle = scope.crmDocumentTitle || $el.text(),
                h1Count = 0;
              document.title = $('title').text().replace(documentTitle, newDocumentTitle);
              $('h1').not('.crm-container h1').each(function() {
                if ($(this).hasClass('crm-page-title') || _.trim($(this).html()) === pageTitle) {
                  $(this).addClass('crm-page-title').html(newPageTitle);
                  $el.hide();
                  ++h1Count;
                }
              });
              if (!h1Count) {
                $el.show();
              }
              pageTitle = newPageTitle;
              documentTitle = newDocumentTitle;
            });
          }

          scope.$watch(function() {return scope.crmDocumentTitle + $el.html();}, update);
        }
      };
    })
    .directive("crmUiEditable", function() {
      return {
        restrict: "A",
        require: "ngModel",
        scope: {
          defaultValue: '='
        },
        link: function(scope, element, attrs, ngModel) {
          var ts = CRM.ts();

          function read() {
            var htmlVal = element.html();
            if (!htmlVal) {
              htmlVal = scope.defaultValue || '';
              element.text(htmlVal);
            }
            ngModel.$setViewValue(htmlVal);
          }

          ngModel.$render = function() {
            element.text(ngModel.$viewValue || scope.defaultValue || '');
          };
          element.on('keydown', function(e) {
            if (e.which === 13) {
              e.preventDefault();
              element.blur();
            }
            if (e.which === 27) {
              element.text(ngModel.$viewValue || scope.defaultValue || '');
              element.blur();
            }
          });

          element.on("blur change", function() {
            scope.$apply(read);
          });

          element.attr('contenteditable', 'true');
        }
      };
    })

    .run(function($rootScope, $location) {
      $rootScope.goto = function(path) {
        $location.path(path);
      };
    });


angular.module('crmUtil', CRM.angRequires('crmUtil'));
  angular.module('crmUtil').factory('crmApi', function($q) {
    var crmApi = function(entity, action, params, message) {
      var deferred = $q.defer();
      var p;
      var backend = crmApi.backend || CRM.api3;
      if (params && params.body_html) {
        params.body_html = params.body_html.replace(/([\u2028]|[\u2029])/g, '\n');
      }
      if (_.isObject(entity)) {
        /*jshint -W061 */
        p = backend(eval('('+angular.toJson(entity)+')'), action);
      } else {
        /*jshint -W061 */
        p = backend(entity, action, eval('('+angular.toJson(params)+')'), message);
      }
      p.then(
        function(result) {
          if (result.is_error) {
            deferred.reject(result);
          } else {
            deferred.resolve(result);
          }
        },
        function(error) {
          deferred.reject(error);
        }
      );
      return deferred.promise;
    };
    crmApi.backend = null;
    crmApi.val = function(value) {
      var d = $.Deferred();
      d.resolve(value);
      return d.promise();
    };
    return crmApi;
  });
  angular.module('crmUtil').factory('crmMetadata', function($q, crmApi) {
    function convertOptionsToMap(options) {
      var result = {};
      angular.forEach(options, function(o) {
        result[o.key] = o.value;
      });
      return result;
    }

    var cache = {}; // cache[entityName+'::'+action][fieldName].title
    var deferreds = {}; // deferreds[cacheKey].push($q.defer())
    var crmMetadata = {
      getField: function getField(entity, field) {
        return $q.when(crmMetadata.getFields(entity)).then(function(fields){
          return fields[field];
        });
      },
      getFields: function getFields(entity) {
        var action = '', cacheKey;
        if (_.isArray(entity)) {
          action = entity[1];
          entity = entity[0];
          cacheKey = entity + '::' + action;
        } else {
          cacheKey = entity;
        }

        if (_.isObject(cache[cacheKey])) {
          return cache[cacheKey];
        }

        var needFetch = _.isEmpty(deferreds[cacheKey]);
        deferreds[cacheKey] = deferreds[cacheKey] || [];
        var deferred = $q.defer();
        deferreds[cacheKey].push(deferred);

        if (needFetch) {
          crmApi(entity, 'getfields', {action: action, sequential: 1, options: {get_options: 'all'}})
            .then(
            function(fields) {
              cache[cacheKey] = _.indexBy(fields.values, 'name');
              angular.forEach(cache[cacheKey],function (field){
                if (field.options) {
                  field.optionsMap = convertOptionsToMap(field.options);
                }
              });
              angular.forEach(deferreds[cacheKey], function(dfr) {
                dfr.resolve(cache[cacheKey]);
              });
              delete deferreds[cacheKey];
            },
            function() {
              cache[cacheKey] = {}; // cache nack
              angular.forEach(deferreds[cacheKey], function(dfr) {
                dfr.reject();
              });
              delete deferreds[cacheKey];
            }
          );
        }

        return deferred.promise;
      }
    };

    return crmMetadata;
  });
  angular.module('crmUtil').factory('crmBlocker', function() {
    return function() {
      var blocks = 0;
      var result = function(promise) {
        blocks++;
        return promise.finally(function() {
          blocks--;
        });
      };
      result.check = function() {
        return blocks > 0;
      };
      return result;
    };
  });

  angular.module('crmUtil').factory('crmLegacy', function() {
    return CRM;
  });
  angular.module('crmUtil').factory('crmLog', function(){
    var level = 0;
    var write = console.log;
    function indent() {
      var s = '>';
      for (var i = 0; i < level; i++) s = s + '  ';
      return s;
    }
    var crmLog = {
      log: function(msg, vars) {
        write(indent() + msg, vars);
      },
      wrap: function(label, f) {
        return function(){
          level++;
          crmLog.log(label + ": start", arguments);
          var r;
          try {
            r = f.apply(this, arguments);
          } finally {
            crmLog.log(label + ": end");
            level--;
          }
          return r;
        };
      }
    };
    return crmLog;
  });

  angular.module('crmUtil').factory('crmNavigator', ['$window', function($window) {
    return {
      redirect: function(path) {
        $window.location.href = path;
      }
    };
  }]);
  angular.module('crmUtil').factory('crmQueue', function($q) {
    return function crmQueue(worker) {
      var queue = [];
      function next() {
        var task = queue[0];
        worker.apply(null, task.a).then(
          function onOk(data) {
            queue.shift();
            task.dfr.resolve(data);
            if (queue.length > 0) next();
          },
          function onErr(err) {
            queue.shift();
            task.dfr.reject(err);
            if (queue.length > 0) next();
          }
        );
      }
      function enqueue() {
        var dfr = $q.defer();
        queue.push({a: arguments, dfr: dfr});
        if (queue.length === 1) {
          next();
        }
        return dfr.promise;
      }
      return enqueue;
    };
  });
  angular.module('crmUtil').factory('crmStatus', function($q){
    return function(options, aPromise){
      if (aPromise) {
        return CRM.toAPromise($q, CRM.status(options, CRM.toJqPromise(aPromise)));
      } else {
        return CRM.toAPromise($q, CRM.status(options));
      }
    };
  });
  angular.module('crmUtil').factory('crmWatcher', function(){
    return function() {
      var unwatches = {}, watchFactories = {}, suspends = {};
      this.setup = function(name, newWatchFactory) {
        watchFactories[name] = newWatchFactory;
        unwatches[name] = watchFactories[name]();
        suspends[name] = 0;
        return this;
      };
      this.suspend = function(name, f) {
        suspends[name]++;
        this.teardown(name);
        var r;
        try {
          r = f.apply(this, []);
        } finally {
          if (suspends[name] === 1) {
            unwatches[name] = watchFactories[name]();
            if (!angular.isArray(unwatches[name])) {
              unwatches[name] = [unwatches[name]];
            }
          }
          suspends[name]--;
        }
        return r;
      };

      this.teardown = function(name) {
        if (!unwatches[name]) return;
        _.each(unwatches[name], function(unwatch){
          unwatch();
        });
        delete unwatches[name];
      };

      return this;
    };
  });
  angular.module('crmUtil').factory('crmThrottle', function($q) {
    var pending = [],
      executing = [];
    return function(func) {
      var deferred = $q.defer();

      function checkResult(result, success) {
        _.pull(executing, func);
        if (_.includes(pending, func)) {
          runNext();
        } else if (success) {
          deferred.resolve(result);
        } else {
          deferred.reject(result);
        }
      }

      function runNext() {
        executing.push(func);
        _.pull(pending, func);
        func().then(function(result) {
          checkResult(result, true);
        }, function(result) {
          checkResult(result, false);
        });
      }

      if (!_.includes(executing, func)) {
        runNext();
      } else if (!_.includes(pending, func)) {
        pending.push(func);
      }
      return deferred.promise;
    };
  });

  angular.module('crmUtil').factory('crmLoadScript', function($q) {
    return function(url) {
      var deferred = $q.defer();

      CRM.loadScript(url).done(function() {
        deferred.resolve(true);
      });

      return deferred.promise;
    };
  });


angular.module('crmbWizard', [
    'crmUi', 'crmUtil'
  ]);
  angular.module('crmbWizard').directive('crmbWizard', function() {
      return {
        restrict: 'EA',
        scope: {
          crmbWizard: '@',
          crmbWizardCtrl: '=',
          crmbWizardNavClass: '@' // string, A list of classes that will be added to the nav items
        },
        templateUrl: '~/crmbWizard/layout.html',
        transclude: true,
        controllerAs: 'crmbWizardCtrl',
        controller: function($scope, $parse) {
          var steps = $scope.steps = []; // array<$scope>
          var crmbWizardCtrl = this;
          var maxVisited = 0;
          var selectedIndex = null;

          var findIndex = function() {
            var found = null;
            angular.forEach(steps, function(step, stepKey) {
              if (step.selected) found = stepKey;
            });
            return found;
          };
          this.$index = function() { return selectedIndex; };
          this.$first = function() { return this.$index() === 0; };
          this.$last = function() { return this.$index() === steps.length -1; };
          this.$maxVisit = function() { return maxVisited; };
          this.$validStep = function() {
            return steps[selectedIndex] && steps[selectedIndex].isStepValid();
          };
          this.iconFor = function(index) {
            if (index < this.$index()) return '√';
            if (index === this.$index()) return '»';
            return ' ';
          };
          this.isSelectable = function(step) {
            var stepIndex;
            for (stepIndex = 0; steps[stepIndex] !== step; stepIndex++) {}

            if (stepIndex <= selectedIndex) return true;
            for (var i = 0; i < stepIndex; i++) {
              if (!steps[i].isStepValid()) return false;
            }
            return true;
          };
          /*** @param Object step the $scope of the step */
          this.isStepDone = function(step) {
            var stepIndex;
            for (stepIndex = 0; steps[stepIndex] !== step; stepIndex++) {}

            if (stepIndex < selectedIndex) {
              return true;
            }
            return false;
          };

          /*** @param Object step the $scope of the step */
          this.select = function(step) {
            angular.forEach(steps, function(otherStep, otherKey) {
              otherStep.selected = (otherStep === step);
              if (otherStep === step && maxVisited < otherKey) maxVisited = otherKey;
            });
            selectedIndex = findIndex();
          };
          /*** @param Object step the $scope of the step */
          this.add = function(step) {
            if (steps.length === 0) {
              step.selected = true;
              selectedIndex = 0;
            }
            steps.push(step);
            steps.sort(function(a,b){
              return a.crmbWizardStep - b.crmbWizardStep;
            });
            selectedIndex = findIndex();
          };
          this.remove = function(step) {
            var key = null;
            angular.forEach(steps, function(otherStep, otherKey) {
              if (otherStep === step) key = otherKey;
            });
            if (key !== null) {
              steps.splice(key, 1);
            }
          };
          this.goto = function(index) {
            if (index < 0) index = 0;
            if (index >= steps.length) index = steps.length-1;
            this.select(steps[index]);
          };
          this.previous = function() { this.goto(this.$index()-1); };
          this.next = function() { this.goto(this.$index()+1); };
          if ($scope.crmbWizard) {
            $parse($scope.crmbWizard).assign($scope.$parent, this);
          }

          $scope.crmbWizardCtrl = this;
        },
        link: function (scope, element, attrs) {
          scope.ts = CRM.ts(null);
        }
      };
    });
  angular.module('crmbWizard').directive('crmbWizardButtonPosition', function() {
    return {
      require: '^crmbWizard',
      restrict: 'EA',
      scope: {
        crmbWizardButtonPosition: '@'
      },
      template: '<span ng-transclude></span>',
      transclude: true,
      link: function (scope, element, attrs, crmbWizardCtrl) {
        var pos = scope.crmbWizardButtonPosition;
        var realButtonsEl = $(element).closest('.crmb-wizard').find('.crmb-wizard-button-' + pos);
        if (pos === 'right') realButtonsEl.append(' ');
        element.appendTo(realButtonsEl);
        if (pos === 'left') realButtonsEl.append(' ');
      }
    };
  });
  angular.module('crmbWizard').directive('crmbWizardStep', function() {
    var nextWeight = 1;
    return {
      require: ['^crmbWizard', 'form'],
      restrict: 'EA',
      scope: {
        crmTitle: '@', // expression, evaluates to a printable string
        crmbWizardStep: '@', // int, a weight which determines the ordering of the steps
        crmbWizardStepClass: '@' // string, A list of classes that will be added to the template
      },
      template: '<div class="crmb-wizard-step {{crmbWizardStepClass}}" ng-show="selected" ng-transclude/></div>',
      transclude: true,
      link: function (scope, element, attrs, ctrls) {
        var crmbWizardCtrl = ctrls[0], form = ctrls[1];
        if (scope.crmbWizardStep) {
          scope.crmbWizardStep = parseInt(scope.crmbWizardStep);
        } else {
          scope.crmbWizardStep = nextWeight++;
        }
        scope.isStepValid = function() {
          return form.$valid;
        };
        crmbWizardCtrl.add(scope);
        scope.$on('$destroy', function(){
          crmbWizardCtrl.remove(scope);
        });
      }
    };
  });

})(angular, CRM.$, CRM._);

(function($, angular){
angular.module('dialogService', []).service('dialogService',
	['$rootScope', '$q', '$compile', '$templateCache', '$http',
	function($rootScope, $q, $compile, $templateCache, $http) {

			var _this = this;
			_this.dialogs = {};

			this.open = function(id, template, model, options) {
				if (!angular.isDefined(id)) {
					throw "dialogService requires id in call to open";
				}

				if (!angular.isDefined(template)) {
					throw "dialogService requires template in call to open";
				}
				if (!angular.isDefined(model)) {
					model = null;
				}
				var dialogOptions = {};
				if (angular.isDefined(options)) {
					angular.extend(dialogOptions, options);
				}
				var dialog = { scope: null, ref: null, deferred: $q.defer() };
				loadTemplate(template).then(
					function(dialogTemplate) {
						dialog.scope = $rootScope.$new();
						dialog.scope.model = model;
						var dialogLinker = $compile(dialogTemplate);
						dialog.ref = $(dialogLinker(dialog.scope));
						var customCloseFn = dialogOptions.close;
						dialogOptions.close = function(event, ui) {
							if (customCloseFn) {
								customCloseFn(event, ui);
							}
							cleanup(id);
						};
						dialog.ref.dialog(dialogOptions);
						dialog.ref.dialog("open");
						_this.dialogs[id] = dialog;

					}, function(error) {
						throw error;
					}
				);
				return dialog.deferred.promise;
			};

			this.close = function(id, result) {
				var dialog = getExistingDialog(id);
				dialog.deferred.resolve(result);
				dialog.ref.dialog("close");
			};

			this.cancel = function(id) {
				var dialog = getExistingDialog(id);
				dialog.deferred.reject();
				dialog.ref.dialog("close");
			};

			this.setButtons = function(id, buttons) {
				var dialog = getExistingDialog(id);
				dialog.ref.dialog("option", 'buttons', buttons);
			};

			function cleanup (id) {
				var dialog = getExistingDialog(id);
				dialog.deferred.reject();
				dialog.scope.$destroy();
				dialog.ref.remove();
				delete _this.dialogs[id];
			};

			function getExistingDialog(id) {
				var dialog = _this.dialogs[id];
				if (!angular.isDefined(dialog)) {
					throw "DialogService does not have a reference to dialog id " + id;
				}
				return dialog;
			};
			function loadTemplate(template) {

				var deferred = $q.defer();
				var html = $templateCache.get(template);

				if (angular.isDefined(html)) {
					html = html.trim();
					deferred.resolve(html);
				} else {
					return $http.get(template, { cache : $templateCache }).then(
						function(response) {
							var html = response.data;
							if(!html || !html.length) {
								return $q.reject("Template " + template + " was not found");
							}
							html = html.trim();
							$templateCache.put(template, html);
							return html;
						}, function() {
							return $q.reject("Template " + template + " was not found");
			        	}
			        );
				}
			    return deferred.promise;
			}
		}
]);
})(jQuery, angular);

/*!
 * jsonformatter
 * 
 * Version: 0.6.0 - 2016-08-27T12:58:03.306Z
 * License: Apache-2.0
 */
"use strict";angular.module("jsonFormatter",["RecursionHelper"]).provider("JSONFormatterConfig",function(){var n=!1,e=100,t=5;return{get hoverPreviewEnabled(){return n},set hoverPreviewEnabled(e){n=!!e},get hoverPreviewArrayCount(){return e},set hoverPreviewArrayCount(n){e=parseInt(n,10)},get hoverPreviewFieldCount(){return t},set hoverPreviewFieldCount(n){t=parseInt(n,10)},$get:function(){return{hoverPreviewEnabled:n,hoverPreviewArrayCount:e,hoverPreviewFieldCount:t}}}}).directive("jsonFormatter",["RecursionHelper","JSONFormatterConfig",function(n,e){function t(n){return n.replace('"','"')}function r(n){if(void 0===n)return"";if(null===n)return"Object";if("object"==typeof n&&!n.constructor)return"Object";if(void 0!==n.__proto__&&void 0!==n.__proto__.constructor&&void 0!==n.__proto__.constructor.name)return n.__proto__.constructor.name;var e=/function (.{1,})\(/,t=e.exec(n.constructor.toString());return t&&t.length>1?t[1]:""}function o(n){return null===n?"null":typeof n}function s(n,e){var r=o(n);return"null"===r||"undefined"===r?r:("string"===r&&(e='"'+t(e)+'"'),"function"===r?n.toString().replace(/[\r\n]/g,"").replace(/\{.*\}/,"")+"{…}":e)}function i(n){var e="";return angular.isObject(n)?(e=r(n),angular.isArray(n)&&(e+="["+n.length+"]")):e=s(n,n),e}function a(n){n.isArray=function(){return angular.isArray(n.json)},n.isObject=function(){return angular.isObject(n.json)},n.getKeys=function(){if(n.isObject())return Object.keys(n.json).map(function(n){return""===n?'""':n})},n.type=o(n.json),n.hasKey="undefined"!=typeof n.key,n.getConstructorName=function(){return r(n.json)},"string"===n.type&&("Invalid Date"!==new Date(n.json).toString()&&(n.isDate=!0),0===n.json.indexOf("http")&&(n.isUrl=!0)),n.isEmptyObject=function(){return n.getKeys()&&!n.getKeys().length&&n.isOpen&&!n.isArray()},n.isOpen=!!n.open,n.toggleOpen=function(){n.isOpen=!n.isOpen},n.childrenOpen=function(){return n.open>1?n.open-1:0},n.openLink=function(e){e&&(window.location.href=n.json)},n.parseValue=function(e){return s(n.json,e)},n.showThumbnail=function(){return!!e.hoverPreviewEnabled&&n.isObject()&&!n.isOpen},n.getThumbnail=function(){if(n.isArray())return n.json.length>e.hoverPreviewArrayCount?"Array["+n.json.length+"]":"["+n.json.map(i).join(", ")+"]";var t=n.getKeys(),r=t.slice(0,e.hoverPreviewFieldCount),o=r.map(function(e){return e+":"+i(n.json[e])}),s=t.length>=5?"…":"";return"{"+o.join(", ")+s+"}"}}return{templateUrl:"json-formatter.html",restrict:"E",replace:!0,scope:{json:"=",key:"=",open:"="},compile:function(e){return n.compile(e,a)}}}]),"object"==typeof module&&(module.exports="jsonFormatter"),angular.module("RecursionHelper",[]).factory("RecursionHelper",["$compile",function(n){return{compile:function(e,t){angular.isFunction(t)&&(t={post:t});var r,o=e.contents().remove();return{pre:t&&t.pre?t.pre:null,post:function(e,s){r||(r=n(o)),r(e,function(n){s.append(n)}),t&&t.post&&t.post.apply(null,arguments)}}}}}]),angular.module("jsonFormatter").run(["$templateCache",function(n){n.put("json-formatter.html",'<div ng-init="isOpen = open && open > 0" class="json-formatter-row"><a ng-click="toggleOpen()"><span class="toggler {{isOpen ? \'open\' : \'\'}}" ng-if="isObject()"></span> <span class="key" ng-if="hasKey"><span class="key-text">{{key}}</span><span class="colon">:</span></span> <span class="value"><span ng-if="isObject()"><span class="constructor-name">{{getConstructorName(json)}}</span> <span ng-if="isArray()"><span class="bracket">[</span><span class="number">{{json.length}}</span><span class="bracket">]</span></span></span> <span ng-if="!isObject()" ng-click="openLink(isUrl)" class="{{type}}" ng-class="{date: isDate, url: isUrl}">{{parseValue(json)}}</span></span> <span ng-if="showThumbnail()" class="thumbnail-text">{{getThumbnail()}}</span></a><div class="children" ng-if="getKeys().length && isOpen"><json-formatter ng-repeat="key in getKeys() track by $index" json="json[key]" key="key" open="childrenOpen()"></json-formatter></div><div class="children empty object" ng-if="isEmptyObject()"></div><div class="children empty array" ng-if="getKeys() && !getKeys().length && isOpen && isArray()"></div></div>')}]);
(function(angular, $, _) {
  angular.module('mjwshared', CRM.angRequires('mjwshared'));

angular.module('mjwshared').config(function($routeProvider) {
      $routeProvider.when('/paymentprocessorWebhook', {
        controller: 'MjwsharedPaymentprocessorWebhook',
        controllerAs: '$ctrl',
        templateUrl: '~/mjwshared/PaymentprocessorWebhook.html',
        resolve: {
          processors: function(crmApi4) {
            return crmApi4('PaymentProcessor', 'get', {
              select: ["id", "MIN(name) AS processorName", "MIN(payment_processor_type_id:label) AS processorType", "MIN(is_test) AS isTest", "COUNT(paymentprocessor_webhook.id) AS webhooksCount"],
              join: [["PaymentprocessorWebhook AS paymentprocessor_webhook", "LEFT", ["id", "=", "paymentprocessor_webhook.payment_processor_id"]]],
              groupBy: ["id"],
              where: [["is_test", "IS NOT EMPTY"]],
              orderBy: {"payment_processor_type_id:label":"ASC", "is_test":"ASC"}
            });
          }
        }
      });
    }
  );
  angular.module('mjwshared').controller('MjwsharedPaymentprocessorWebhook', function($scope, crmApi4, crmStatus, crmUiHelp, processors) {
    var ts = $scope.ts = CRM.ts('mjwshared');
    var hs = $scope.hs = crmUiHelp({file: 'CRM/mjwshared/PaymentprocessorWebhook'}); // See: templates/CRM/mjwshared/PaymentprocessorWebhook.hlp
    var ctrl = this;

    this.processors = processors;
    this.events = [];
    this.statusFilter = '';
    this.processorFilter = '';
    this.eventFilter = '';
    this.paymentProcessor = [];
    this.offset = 0;
    this.limit = 25;
    this.selectedRow = null;
    this.resultsCount = 0;
    this.lastQuery = '';

    this.statusMap = {
      'new': {content : '🔵 ' + ts('New'), title: ts('This event is awaiting processing')},
      'processing': {content : '🟡 ' + ts('Processing'), title: ts('This event is currently being processed by the Scheduled Job')},
      'success': {content : '🟢 ' + ts('Success'), title: ts('This event was successfully processed.')},
      'error': {content : '🔴 ' + ts('Error'), title: ts('There was an error processing this event.')},
    };

    this.abbreviate = function (text) {
     return text.replace(/^(.{80}).+$/s, '$1 ...');
    }

    this.load = function() {
      const params = {
          select: ["*", 'row_count', "payment_processor.name", "payment_processor.is_test"],
          join: [["PaymentProcessor AS payment_processor", true, null, ["payment_processor_id", "=", "payment_processor.id"]]],
          where: [],
          orderBy: {"id":"DESC"}
        };
      if (ctrl.statusFilter) {
        params.where.push(['status', '=', ctrl.statusFilter]);
      }
      if (ctrl.processorFilter) {
        params.where.push(['payment_processor_id', '=', ctrl.processorFilter]);
      }
      if (ctrl.eventFilter) {
        params.where.push(['event_id', 'LIKE', '%' + ctrl.eventFilter + '%']);
      }
      if (JSON.stringify(params) !== ctrl.lastQuery) {
        ctrl.offset = 0;
        ctrl.lastQuery = JSON.stringify(params);
      }
      Object.assign(params, {
          offset: ctrl.offset,
          limit: ctrl.limit,
      });

      return crmStatus(
        {start: ts('Loading...'), success: ts('Loaded')},
        crmApi4('PaymentprocessorWebhook', 'get', params)
        .then(r => {
          ctrl.resultsCount = r.count;
          ctrl.events = r;
        })
      );
    };

    this.changePage = function(dir) {
      let newOffset = Math.min(Math.max(ctrl.offset + dir*ctrl.limit, 0), ctrl.resultsCount - 1);
      if (newOffset != ctrl.offset) {
        ctrl.offset = newOffset;
        ctrl.load();
      }
    };

    this.delete = function(id) {
      if (!(parseInt(id) > 0)) return;
      if (!confirm(ts("Deleting a received webhook event is not un-do-able, and you may not be able to generate it again. Are you sure?"))) {
        return;
      }

      return crmStatus(
        {start: ts('Deleting...'), success: ts('Gone')},
        crmApi4('PaymentprocessorWebhook', 'delete', {
          where: [['id', '=', id]],
          limit: 1
        }))
        .then(r => {
          return ctrl.load();
        });
    };

    this.retry = function(id) {
      if (!(parseInt(id) > 0)) return;
      if (!confirm(ts("Retrying an event could cause bad things to happen, depending on the event and the processor, so please be confident in your understanding of both. Schedule retry of this event?"))) {
        return;
      }

      return crmStatus(
        {start: ts('Updating...'), success: ts('Updated')},
        crmApi4('PaymentprocessorWebhook', 'update', {
          where: [['id', '=', id]],
          values: {status: 'new', processed_date: null, message: ts('Scheduled for retry')},
          limit: 1
        }))
        .then(r => {
          return ctrl.load();
        });
    }


    this.load();
  });


})(angular, CRM.$, CRM._);

/*
 AngularJS v1.8.0
 (c) 2010-2020 Google, Inc. http://angularjs.org
 License: MIT
*/
(function(I,b){'use strict';function z(b,h){var d=[],c=b.replace(/([().])/g,"\\$1").replace(/(\/)?:(\w+)(\*\?|[?*])?/g,function(b,c,h,k){b="?"===k||"*?"===k;k="*"===k||"*?"===k;d.push({name:h,optional:b});c=c||"";return(b?"(?:"+c:c+"(?:")+(k?"(.+?)":"([^/]+)")+(b?"?)?":")")}).replace(/([/$*])/g,"\\$1");h.ignoreTrailingSlashes&&(c=c.replace(/\/+$/,"")+"/*");return{keys:d,regexp:new RegExp("^"+c+"(?:[?#]|$)",h.caseInsensitiveMatch?"i":"")}}function A(b){p&&b.get("$route")}function v(u,h,d){return{restrict:"ECA",
terminal:!0,priority:400,transclude:"element",link:function(c,f,g,l,k){function q(){r&&(d.cancel(r),r=null);m&&(m.$destroy(),m=null);s&&(r=d.leave(s),r.done(function(b){!1!==b&&(r=null)}),s=null)}function C(){var g=u.current&&u.current.locals;if(b.isDefined(g&&g.$template)){var g=c.$new(),l=u.current;s=k(g,function(g){d.enter(g,null,s||f).done(function(d){!1===d||!b.isDefined(w)||w&&!c.$eval(w)||h()});q()});m=l.scope=g;m.$emit("$viewContentLoaded");m.$eval(p)}else q()}var m,s,r,w=g.autoscroll,p=g.onload||
"";c.$on("$routeChangeSuccess",C);C()}}}function x(b,h,d){return{restrict:"ECA",priority:-400,link:function(c,f){var g=d.current,l=g.locals;f.html(l.$template);var k=b(f.contents());if(g.controller){l.$scope=c;var q=h(g.controller,l);g.controllerAs&&(c[g.controllerAs]=q);f.data("$ngControllerController",q);f.children().data("$ngControllerController",q)}c[g.resolveAs||"$resolve"]=l;k(c)}}}var D,E,F,G,y=b.module("ngRoute",[]).info({angularVersion:"1.8.0"}).provider("$route",function(){function u(d,
c){return b.extend(Object.create(d),c)}D=b.isArray;E=b.isObject;F=b.isDefined;G=b.noop;var h={};this.when=function(d,c){var f;f=void 0;if(D(c)){f=f||[];for(var g=0,l=c.length;g<l;g++)f[g]=c[g]}else if(E(c))for(g in f=f||{},c)if("$"!==g.charAt(0)||"$"!==g.charAt(1))f[g]=c[g];f=f||c;b.isUndefined(f.reloadOnUrl)&&(f.reloadOnUrl=!0);b.isUndefined(f.reloadOnSearch)&&(f.reloadOnSearch=!0);b.isUndefined(f.caseInsensitiveMatch)&&(f.caseInsensitiveMatch=this.caseInsensitiveMatch);h[d]=b.extend(f,{originalPath:d},
d&&z(d,f));d&&(g="/"===d[d.length-1]?d.substr(0,d.length-1):d+"/",h[g]=b.extend({originalPath:d,redirectTo:d},z(g,f)));return this};this.caseInsensitiveMatch=!1;this.otherwise=function(b){"string"===typeof b&&(b={redirectTo:b});this.when(null,b);return this};p=!0;this.eagerInstantiationEnabled=function(b){return F(b)?(p=b,this):p};this.$get=["$rootScope","$location","$routeParams","$q","$injector","$templateRequest","$sce","$browser",function(d,c,f,g,l,k,q,p){function m(a){var e=t.current;n=A();(x=
!B&&n&&e&&n.$$route===e.$$route&&(!n.reloadOnUrl||!n.reloadOnSearch&&b.equals(n.pathParams,e.pathParams)))||!e&&!n||d.$broadcast("$routeChangeStart",n,e).defaultPrevented&&a&&a.preventDefault()}function s(){var a=t.current,e=n;if(x)a.params=e.params,b.copy(a.params,f),d.$broadcast("$routeUpdate",a);else if(e||a){B=!1;t.current=e;var c=g.resolve(e);p.$$incOutstandingRequestCount("$route");c.then(r).then(w).then(function(g){return g&&c.then(y).then(function(c){e===t.current&&(e&&(e.locals=c,b.copy(e.params,
f)),d.$broadcast("$routeChangeSuccess",e,a))})}).catch(function(b){e===t.current&&d.$broadcast("$routeChangeError",e,a,b)}).finally(function(){p.$$completeOutstandingRequest(G,"$route")})}}function r(a){var e={route:a,hasRedirection:!1};if(a)if(a.redirectTo)if(b.isString(a.redirectTo))e.path=v(a.redirectTo,a.params),e.search=a.params,e.hasRedirection=!0;else{var d=c.path(),f=c.search();a=a.redirectTo(a.pathParams,d,f);b.isDefined(a)&&(e.url=a,e.hasRedirection=!0)}else if(a.resolveRedirectTo)return g.resolve(l.invoke(a.resolveRedirectTo)).then(function(a){b.isDefined(a)&&
(e.url=a,e.hasRedirection=!0);return e});return e}function w(a){var b=!0;if(a.route!==t.current)b=!1;else if(a.hasRedirection){var g=c.url(),d=a.url;d?c.url(d).replace():d=c.path(a.path).search(a.search).replace().url();d!==g&&(b=!1)}return b}function y(a){if(a){var e=b.extend({},a.resolve);b.forEach(e,function(a,c){e[c]=b.isString(a)?l.get(a):l.invoke(a,null,null,c)});a=z(a);b.isDefined(a)&&(e.$template=a);return g.all(e)}}function z(a){var e,c;b.isDefined(e=a.template)?b.isFunction(e)&&(e=e(a.params)):
b.isDefined(c=a.templateUrl)&&(b.isFunction(c)&&(c=c(a.params)),b.isDefined(c)&&(a.loadedTemplateUrl=q.valueOf(c),e=k(c)));return e}function A(){var a,e;b.forEach(h,function(d,g){var f;if(f=!e){var h=c.path();f=d.keys;var l={};if(d.regexp)if(h=d.regexp.exec(h)){for(var k=1,p=h.length;k<p;++k){var m=f[k-1],n=h[k];m&&n&&(l[m.name]=n)}f=l}else f=null;else f=null;f=a=f}f&&(e=u(d,{params:b.extend({},c.search(),a),pathParams:a}),e.$$route=d)});return e||h[null]&&u(h[null],{params:{},pathParams:{}})}function v(a,
c){var d=[];b.forEach((a||"").split(":"),function(a,b){if(0===b)d.push(a);else{var f=a.match(/(\w+)(?:[?*])?(.*)/),g=f[1];d.push(c[g]);d.push(f[2]||"");delete c[g]}});return d.join("")}var B=!1,n,x,t={routes:h,reload:function(){B=!0;var a={defaultPrevented:!1,preventDefault:function(){this.defaultPrevented=!0;B=!1}};d.$evalAsync(function(){m(a);a.defaultPrevented||s()})},updateParams:function(a){if(this.current&&this.current.$$route)a=b.extend({},this.current.params,a),c.path(v(this.current.$$route.originalPath,
a)),c.search(a);else throw H("norout");}};d.$on("$locationChangeStart",m);d.$on("$locationChangeSuccess",s);return t}]}).run(A),H=b.$$minErr("ngRoute"),p;A.$inject=["$injector"];y.provider("$routeParams",function(){this.$get=function(){return{}}});y.directive("ngView",v);y.directive("ngView",x);v.$inject=["$route","$anchorScroll","$animate"];x.$inject=["$compile","$controller","$route"]})(window,window.angular);

/*
 AngularJS v1.8.0
 (c) 2010-2020 Google, Inc. http://angularjs.org
 License: MIT
*/
(function(s,c){'use strict';function P(c){var h=[];C(h,E).chars(c);return h.join("")}var D=c.$$minErr("$sanitize"),F,h,G,H,I,q,E,J,K,C;c.module("ngSanitize",[]).provider("$sanitize",function(){function f(a,e){return B(a.split(","),e)}function B(a,e){var d={},b;for(b=0;b<a.length;b++)d[e?q(a[b]):a[b]]=!0;return d}function t(a,e){e&&e.length&&h(a,B(e))}function Q(a){for(var e={},d=0,b=a.length;d<b;d++){var k=a[d];e[k.name]=k.value}return e}function L(a){return a.replace(/&/g,"&amp;").replace(z,function(a){var d=
a.charCodeAt(0);a=a.charCodeAt(1);return"&#"+(1024*(d-55296)+(a-56320)+65536)+";"}).replace(u,function(a){return"&#"+a.charCodeAt(0)+";"}).replace(/</g,"&lt;").replace(/>/g,"&gt;")}function A(a){for(;a;){if(a.nodeType===s.Node.ELEMENT_NODE)for(var e=a.attributes,d=0,b=e.length;d<b;d++){var k=e[d],g=k.name.toLowerCase();if("xmlns:ns1"===g||0===g.lastIndexOf("ns1:",0))a.removeAttributeNode(k),d--,b--}(e=a.firstChild)&&A(e);a=v("nextSibling",a)}}function v(a,e){var d=e[a];if(d&&J.call(e,d))throw D("elclob",
e.outerHTML||e.outerText);return d}var y=!1,g=!1;this.$get=["$$sanitizeUri",function(a){y=!0;g&&h(m,l);return function(e){var d=[];K(e,C(d,function(b,d){return!/^unsafe:/.test(a(b,d))}));return d.join("")}}];this.enableSvg=function(a){return I(a)?(g=a,this):g};this.addValidElements=function(a){y||(H(a)&&(a={htmlElements:a}),t(l,a.svgElements),t(r,a.htmlVoidElements),t(m,a.htmlVoidElements),t(m,a.htmlElements));return this};this.addValidAttrs=function(a){y||h(M,B(a,!0));return this};F=c.bind;h=c.extend;
G=c.forEach;H=c.isArray;I=c.isDefined;q=c.$$lowercase;E=c.noop;K=function(a,e){null===a||void 0===a?a="":"string"!==typeof a&&(a=""+a);var d=N(a);if(!d)return"";var b=5;do{if(0===b)throw D("uinput");b--;a=d.innerHTML;d=N(a)}while(a!==d.innerHTML);for(b=d.firstChild;b;){switch(b.nodeType){case 1:e.start(b.nodeName.toLowerCase(),Q(b.attributes));break;case 3:e.chars(b.textContent)}var k;if(!(k=b.firstChild)&&(1===b.nodeType&&e.end(b.nodeName.toLowerCase()),k=v("nextSibling",b),!k))for(;null==k;){b=
v("parentNode",b);if(b===d)break;k=v("nextSibling",b);1===b.nodeType&&e.end(b.nodeName.toLowerCase())}b=k}for(;b=d.firstChild;)d.removeChild(b)};C=function(a,e){var d=!1,b=F(a,a.push);return{start:function(a,g){a=q(a);!d&&w[a]&&(d=a);d||!0!==m[a]||(b("<"),b(a),G(g,function(d,g){var c=q(g),f="img"===a&&"src"===c||"background"===c;!0!==M[c]||!0===O[c]&&!e(d,f)||(b(" "),b(g),b('="'),b(L(d)),b('"'))}),b(">"))},end:function(a){a=q(a);d||!0!==m[a]||!0===r[a]||(b("</"),b(a),b(">"));a==d&&(d=!1)},chars:function(a){d||
b(L(a))}}};J=s.Node.prototype.contains||function(a){return!!(this.compareDocumentPosition(a)&16)};var z=/[\uD800-\uDBFF][\uDC00-\uDFFF]/g,u=/([^#-~ |!])/g,r=f("area,br,col,hr,img,wbr"),x=f("colgroup,dd,dt,li,p,tbody,td,tfoot,th,thead,tr"),p=f("rp,rt"),n=h({},p,x),x=h({},x,f("address,article,aside,blockquote,caption,center,del,dir,div,dl,figure,figcaption,footer,h1,h2,h3,h4,h5,h6,header,hgroup,hr,ins,map,menu,nav,ol,pre,section,table,ul")),p=h({},p,f("a,abbr,acronym,b,bdi,bdo,big,br,cite,code,del,dfn,em,font,i,img,ins,kbd,label,map,mark,q,ruby,rp,rt,s,samp,small,span,strike,strong,sub,sup,time,tt,u,var")),
l=f("circle,defs,desc,ellipse,font-face,font-face-name,font-face-src,g,glyph,hkern,image,linearGradient,line,marker,metadata,missing-glyph,mpath,path,polygon,polyline,radialGradient,rect,stop,svg,switch,text,title,tspan"),w=f("script,style"),m=h({},r,x,p,n),O=f("background,cite,href,longdesc,src,xlink:href,xml:base"),n=f("abbr,align,alt,axis,bgcolor,border,cellpadding,cellspacing,class,clear,color,cols,colspan,compact,coords,dir,face,headers,height,hreflang,hspace,ismap,lang,language,nohref,nowrap,rel,rev,rows,rowspan,rules,scope,scrolling,shape,size,span,start,summary,tabindex,target,title,type,valign,value,vspace,width"),
p=f("accent-height,accumulate,additive,alphabetic,arabic-form,ascent,baseProfile,bbox,begin,by,calcMode,cap-height,class,color,color-rendering,content,cx,cy,d,dx,dy,descent,display,dur,end,fill,fill-rule,font-family,font-size,font-stretch,font-style,font-variant,font-weight,from,fx,fy,g1,g2,glyph-name,gradientUnits,hanging,height,horiz-adv-x,horiz-origin-x,ideographic,k,keyPoints,keySplines,keyTimes,lang,marker-end,marker-mid,marker-start,markerHeight,markerUnits,markerWidth,mathematical,max,min,offset,opacity,orient,origin,overline-position,overline-thickness,panose-1,path,pathLength,points,preserveAspectRatio,r,refX,refY,repeatCount,repeatDur,requiredExtensions,requiredFeatures,restart,rotate,rx,ry,slope,stemh,stemv,stop-color,stop-opacity,strikethrough-position,strikethrough-thickness,stroke,stroke-dasharray,stroke-dashoffset,stroke-linecap,stroke-linejoin,stroke-miterlimit,stroke-opacity,stroke-width,systemLanguage,target,text-anchor,to,transform,type,u1,u2,underline-position,underline-thickness,unicode,unicode-range,units-per-em,values,version,viewBox,visibility,width,widths,x,x-height,x1,x2,xlink:actuate,xlink:arcrole,xlink:role,xlink:show,xlink:title,xlink:type,xml:base,xml:lang,xml:space,xmlns,xmlns:xlink,y,y1,y2,zoomAndPan",
!0),M=h({},O,p,n),N=function(a,e){function d(b){b="<remove></remove>"+b;try{var d=(new a.DOMParser).parseFromString(b,"text/html").body;d.firstChild.remove();return d}catch(e){}}function b(a){c.innerHTML=a;e.documentMode&&A(c);return c}var g;if(e&&e.implementation)g=e.implementation.createHTMLDocument("inert");else throw D("noinert");var c=(g.documentElement||g.getDocumentElement()).querySelector("body");c.innerHTML='<svg><g onload="this.parentNode.remove()"></g></svg>';return c.querySelector("svg")?
(c.innerHTML='<svg><p><style><img src="</style><img src=x onerror=alert(1)//">',c.querySelector("svg img")?d:b):function(b){b="<remove></remove>"+b;try{b=encodeURI(b)}catch(d){return}var e=new a.XMLHttpRequest;e.responseType="document";e.open("GET","data:text/html;charset=utf-8,"+b,!1);e.send(null);b=e.response.body;b.firstChild.remove();return b}}(s,s.document)}).info({angularVersion:"1.8.0"});c.module("ngSanitize").filter("linky",["$sanitize",function(f){var h=/((s?ftp|https?):\/\/|(www\.)|(mailto:)?[A-Za-z0-9._%+-]+@)\S*[^\s.;,(){}<>"\u201d\u2019]/i,
t=/^mailto:/i,q=c.$$minErr("linky"),s=c.isDefined,A=c.isFunction,v=c.isObject,y=c.isString;return function(c,z,u){function r(c){c&&l.push(P(c))}function x(c,g){var f,a=p(c);l.push("<a ");for(f in a)l.push(f+'="'+a[f]+'" ');!s(z)||"target"in a||l.push('target="',z,'" ');l.push('href="',c.replace(/"/g,"&quot;"),'">');r(g);l.push("</a>")}if(null==c||""===c)return c;if(!y(c))throw q("notstring",c);for(var p=A(u)?u:v(u)?function(){return u}:function(){return{}},n=c,l=[],w,m;c=n.match(h);)w=c[0],c[2]||
c[4]||(w=(c[3]?"http://":"mailto:")+w),m=c.index,r(n.substr(0,m)),x(w,c[0].replace(t,"")),n=n.substring(m+c[0].length);r(n);return f(l.join(""))}}])})(window,window.angular);

/**
 * angular-ui-sortable - This directive allows you to jQueryUI Sortable.
 * @version v0.19.0 - 2018-01-14
 * @link http://angular-ui.github.com
 * @license MIT
 */

!function(a,b,c){"use strict";b.module("ui.sortable",[]).value("uiSortableConfig",{items:"> [ng-repeat],> [data-ng-repeat],> [x-ng-repeat]"}).directive("uiSortable",["uiSortableConfig","$timeout","$log",function(a,d,e){return{require:"?ngModel",scope:{ngModel:"=",uiSortable:"=",create:"&uiSortableCreate",start:"&uiSortableStart",activate:"&uiSortableActivate",beforeStop:"&uiSortableBeforeStop",update:"&uiSortableUpdate",remove:"&uiSortableRemove",receive:"&uiSortableReceive",deactivate:"&uiSortableDeactivate",stop:"&uiSortableStop"},link:function(f,g,h,i){function j(a,b){var c="function"==typeof a,d="function"==typeof b;return c&&d?function(){a.apply(this,arguments),b.apply(this,arguments)}:d?b:a}function k(a){var b=a.data("ui-sortable");return b&&"object"==typeof b&&"ui-sortable"===b.widgetFullName?b:null}function l(a){a.children().each(function(){var a=b.element(this);a.width(a.width())})}function m(a,b){return b}function n(b,c){return E[b]?("stop"===b&&(c=j(c,function(){f.$apply()}),c=j(c,v)),c=j(E[b],c)):F[b]&&(c=F[b](c)),c||"items"!==b&&"ui-model-items"!==b||(c=a.items),c}function o(a,d,e){function f(a,b){b in C||(C[b]=null)}b.forEach(E,f);var g=null;if(d){var h;b.forEach(d,function(d,e){if(!(a&&e in a)){if(e in D)return void("ui-floating"===e?C[e]="auto":C[e]=n(e,c));h||(h=b.element.ui.sortable().options);var f=h[e];f=n(e,f),g||(g={}),g[e]=f,C[e]=f}})}return a=b.extend({},a),b.forEach(a,function(b,c){if(c in D){if("ui-floating"!==c||b!==!1&&b!==!0||!e||(e.floating=b),"ui-preserve-size"===c&&(b===!1||b===!0)){var d=C.helper;a.helper=function(a,b){return C["ui-preserve-size"]===!0&&l(b),(d||m).apply(this,arguments)}}C[c]=n(c,b)}}),b.forEach(a,function(a,b){b in D||(a=n(b,a),g||(g={}),g[b]=a,C[b]=a)}),g}function p(a){var c=a.sortable("option","placeholder");if(c&&c.element&&"function"==typeof c.element){var d=c.element();return d=b.element(d)}return null}function q(a,b){var c=C["ui-model-items"].replace(/[^,]*>/g,""),d=a.find('[class="'+b.attr("class")+'"]:not('+c+")");return d}function r(a,b){var c=a.sortable("option","helper");return"clone"===c||"function"==typeof c&&b.item.sortable.isCustomHelperUsed()}function s(a,b){var c=null;return r(a,b)&&"parent"===a.sortable("option","appendTo")&&(c=B),c}function t(a){return/left|right/.test(a.css("float"))||/inline|table-cell/.test(a.css("display"))}function u(a,b){for(var c=0;c<a.length;c++){var d=a[c];if(d.element[0]===b[0])return d}}function v(a,b){b.item.sortable._destroy()}function w(a){return a.parent().find(C["ui-model-items"]).index(a)}function x(){f.$watchCollection("ngModel",function(){d(function(){k(g)&&g.sortable("refresh")},0,!1)}),E.start=function(a,d){if("auto"===C["ui-floating"]){var e=d.item.siblings(),f=k(b.element(a.target));f.floating=t(e)}var h=w(d.item);d.item.sortable={model:i.$modelValue[h],index:h,source:g,sourceList:d.item.parent(),sourceModel:i.$modelValue,cancel:function(){d.item.sortable._isCanceled=!0},isCanceled:function(){return d.item.sortable._isCanceled},isCustomHelperUsed:function(){return!!d.item.sortable._isCustomHelperUsed},_isCanceled:!1,_isCustomHelperUsed:d.item.sortable._isCustomHelperUsed,_destroy:function(){b.forEach(d.item.sortable,function(a,b){d.item.sortable[b]=c})},_connectedSortables:[],_getElementContext:function(a){return u(this._connectedSortables,a)}}},E.activate=function(a,b){var c=b.item.sortable.source===g,d=c?b.item.sortable.sourceList:g,e={element:g,scope:f,isSourceContext:c,savedNodesOrigin:d};b.item.sortable._connectedSortables.push(e),A=d.contents(),B=b.helper;var h=p(g);if(h&&h.length){var i=q(g,h);A=A.not(i)}},E.update=function(a,b){if(!b.item.sortable.received){b.item.sortable.dropindex=w(b.item);var c=b.item.parent().closest("[ui-sortable], [data-ui-sortable], [x-ui-sortable]");b.item.sortable.droptarget=c,b.item.sortable.droptargetList=b.item.parent();var d=b.item.sortable._getElementContext(c);b.item.sortable.droptargetModel=d.scope.ngModel,g.sortable("cancel")}var e=!b.item.sortable.received&&s(g,b,A);e&&e.length&&(A=A.not(e));var h=b.item.sortable._getElementContext(g);A.appendTo(h.savedNodesOrigin),b.item.sortable.received&&(A=null),b.item.sortable.received&&!b.item.sortable.isCanceled()&&(f.$apply(function(){i.$modelValue.splice(b.item.sortable.dropindex,0,b.item.sortable.moved)}),f.$emit("ui-sortable:moved",b))},E.stop=function(a,c){var d="dropindex"in c.item.sortable&&!c.item.sortable.isCanceled();if(d&&!c.item.sortable.received)f.$apply(function(){i.$modelValue.splice(c.item.sortable.dropindex,0,i.$modelValue.splice(c.item.sortable.index,1)[0])}),f.$emit("ui-sortable:moved",c);else if(!d&&!b.equals(g.contents().toArray(),A.toArray())){var e=s(g,c,A);e&&e.length&&(A=A.not(e));var h=c.item.sortable._getElementContext(g);A.appendTo(h.savedNodesOrigin)}A=null,B=null},E.receive=function(a,b){b.item.sortable.received=!0},E.remove=function(a,b){"dropindex"in b.item.sortable||(g.sortable("cancel"),b.item.sortable.cancel()),b.item.sortable.isCanceled()||f.$apply(function(){b.item.sortable.moved=i.$modelValue.splice(b.item.sortable.index,1)[0]})},b.forEach(E,function(a,b){E[b]=j(E[b],function(){var a,c=f[b];"function"==typeof c&&("uiSortable"+b.substring(0,1).toUpperCase()+b.substring(1)).length&&"function"==typeof(a=c())&&a.apply(this,arguments)})}),F.helper=function(a){return a&&"function"==typeof a?function(d,e){var f=e.sortable,h=w(e);e.sortable={model:i.$modelValue[h],index:h,source:g,sourceList:e.parent(),sourceModel:i.$modelValue,_restore:function(){b.forEach(e.sortable,function(a,b){e.sortable[b]=c}),e.sortable=f}};var j=a.apply(this,arguments);return e.sortable._restore(),e.sortable._isCustomHelperUsed=e!==j,j}:a},f.$watchCollection("uiSortable",function(a,b){var c=k(g);if(c){var d=o(a,b,c);d&&g.sortable("option",d)}},!0),o(C)}function y(){i?x():e.info("ui.sortable: ngModel not provided!",g),g.sortable(C)}function z(){return f.uiSortable&&f.uiSortable.disabled?!1:(y(),z.cancelWatcher(),z.cancelWatcher=b.noop,!0)}var A,B,C={},D={"ui-floating":c,"ui-model-items":a.items,"ui-preserve-size":c},E={create:null,start:null,activate:null,beforeStop:null,update:null,remove:null,receive:null,deactivate:null,stop:null},F={helper:null};return b.extend(C,D,a,f.uiSortable),b.element.fn&&b.element.fn.jquery?(z.cancelWatcher=b.noop,void(z()||(z.cancelWatcher=f.$watch("uiSortable.disabled",z)))):void e.error("ui.sortable: jQuery should be included before AngularJS!")}}}])}(window,window.angular);

/**
 * angular-ui-utils - Swiss-Army-Knife of AngularJS tools (with no external dependencies!)
 * @version v0.1.1 - 2014-02-05
 * @link http://angular-ui.github.com
 * @license MIT License, http://www.opensource.org/licenses/MIT
 */
"use strict";angular.module("ui.alias",[]).config(["$compileProvider","uiAliasConfig",function(a,b){b=b||{},angular.forEach(b,function(b,c){angular.isString(b)&&(b={replace:!0,template:b}),a.directive(c,function(){return b})})}]),angular.module("ui.event",[]).directive("uiEvent",["$parse",function(a){return function(b,c,d){var e=b.$eval(d.uiEvent);angular.forEach(e,function(d,e){var f=a(d);c.bind(e,function(a){var c=Array.prototype.slice.call(arguments);c=c.splice(1),f(b,{$event:a,$params:c}),b.$$phase||b.$apply()})})}}]),angular.module("ui.format",[]).filter("format",function(){return function(a,b){var c=a;if(angular.isString(c)&&void 0!==b)if(angular.isArray(b)||angular.isObject(b)||(b=[b]),angular.isArray(b)){var d=b.length,e=function(a,c){return c=parseInt(c,10),c>=0&&d>c?b[c]:a};c=c.replace(/\$([0-9]+)/g,e)}else angular.forEach(b,function(a,b){c=c.split(":"+b).join(a)});return c}}),angular.module("ui.highlight",[]).filter("highlight",function(){return function(a,b,c){return b||angular.isNumber(b)?(a=a.toString(),b=b.toString(),c?a.split(b).join('<span class="ui-match">'+b+"</span>"):a.replace(new RegExp(b,"gi"),'<span class="ui-match">$&</span>')):a}}),angular.module("ui.include",[]).directive("uiInclude",["$http","$templateCache","$anchorScroll","$compile",function(a,b,c,d){return{restrict:"ECA",terminal:!0,compile:function(e,f){var g=f.uiInclude||f.src,h=f.fragment||"",i=f.onload||"",j=f.autoscroll;return function(e,f){function k(){var k=++m,o=e.$eval(g),p=e.$eval(h);o?a.get(o,{cache:b}).success(function(a){if(k===m){l&&l.$destroy(),l=e.$new();var b;b=p?angular.element("<div/>").html(a).find(p):angular.element("<div/>").html(a).contents(),f.html(b),d(b)(l),!angular.isDefined(j)||j&&!e.$eval(j)||c(),l.$emit("$includeContentLoaded"),e.$eval(i)}}).error(function(){k===m&&n()}):n()}var l,m=0,n=function(){l&&(l.$destroy(),l=null),f.html("")};e.$watch(h,k),e.$watch(g,k)}}}}]),angular.module("ui.indeterminate",[]).directive("uiIndeterminate",[function(){return{compile:function(a,b){return b.type&&"checkbox"===b.type.toLowerCase()?function(a,b,c){a.$watch(c.uiIndeterminate,function(a){b[0].indeterminate=!!a})}:angular.noop}}}]),angular.module("ui.inflector",[]).filter("inflector",function(){function a(a){return a.replace(/^([a-z])|\s+([a-z])/g,function(a){return a.toUpperCase()})}function b(a,b){return a.replace(/[A-Z]/g,function(a){return b+a})}var c={humanize:function(c){return a(b(c," ").split("_").join(" "))},underscore:function(a){return a.substr(0,1).toLowerCase()+b(a.substr(1),"_").toLowerCase().split(" ").join("_")},variable:function(b){return b=b.substr(0,1).toLowerCase()+a(b.split("_").join(" ")).substr(1).split(" ").join("")}};return function(a,b){return b!==!1&&angular.isString(a)?(b=b||"humanize",c[b](a)):a}}),angular.module("ui.jq",[]).value("uiJqConfig",{}).directive("uiJq",["uiJqConfig","$timeout",function(a,b){return{restrict:"A",compile:function(c,d){if(!angular.isFunction(c[d.uiJq]))throw new Error('ui-jq: The "'+d.uiJq+'" function does not exist');var e=a&&a[d.uiJq];return function(a,c,d){function f(){b(function(){c[d.uiJq].apply(c,g)},0,!1)}var g=[];d.uiOptions?(g=a.$eval("["+d.uiOptions+"]"),angular.isObject(e)&&angular.isObject(g[0])&&(g[0]=angular.extend({},e,g[0]))):e&&(g=[e]),d.ngModel&&c.is("select,input,textarea")&&c.bind("change",function(){c.trigger("input")}),d.uiRefresh&&a.$watch(d.uiRefresh,function(){f()}),f()}}}}]),angular.module("ui.keypress",[]).factory("keypressHelper",["$parse",function(a){var b={8:"backspace",9:"tab",13:"enter",27:"esc",32:"space",33:"pageup",34:"pagedown",35:"end",36:"home",37:"left",38:"up",39:"right",40:"down",45:"insert",46:"delete"},c=function(a){return a.charAt(0).toUpperCase()+a.slice(1)};return function(d,e,f,g){var h,i=[];h=e.$eval(g["ui"+c(d)]),angular.forEach(h,function(b,c){var d,e;e=a(b),angular.forEach(c.split(" "),function(a){d={expression:e,keys:{}},angular.forEach(a.split("-"),function(a){d.keys[a]=!0}),i.push(d)})}),f.bind(d,function(a){var c=!(!a.metaKey||a.ctrlKey),f=!!a.altKey,g=!!a.ctrlKey,h=!!a.shiftKey,j=a.keyCode;"keypress"===d&&!h&&j>=97&&122>=j&&(j-=32),angular.forEach(i,function(d){var i=d.keys[b[j]]||d.keys[j.toString()],k=!!d.keys.meta,l=!!d.keys.alt,m=!!d.keys.ctrl,n=!!d.keys.shift;i&&k===c&&l===f&&m===g&&n===h&&e.$apply(function(){d.expression(e,{$event:a})})})})}}]),angular.module("ui.keypress").directive("uiKeydown",["keypressHelper",function(a){return{link:function(b,c,d){a("keydown",b,c,d)}}}]),angular.module("ui.keypress").directive("uiKeypress",["keypressHelper",function(a){return{link:function(b,c,d){a("keypress",b,c,d)}}}]),angular.module("ui.keypress").directive("uiKeyup",["keypressHelper",function(a){return{link:function(b,c,d){a("keyup",b,c,d)}}}]),angular.module("ui.mask",[]).value("uiMaskConfig",{maskDefinitions:{9:/\d/,A:/[a-zA-Z]/,"*":/[a-zA-Z0-9]/}}).directive("uiMask",["uiMaskConfig",function(a){return{priority:100,require:"ngModel",restrict:"A",compile:function(){var b=a;return function(a,c,d,e){function f(a){return angular.isDefined(a)?(s(a),N?(k(),l(),!0):j()):j()}function g(a){angular.isDefined(a)&&(D=a,N&&w())}function h(a){return N?(G=o(a||""),I=n(G),e.$setValidity("mask",I),I&&G.length?p(G):void 0):a}function i(a){return N?(G=o(a||""),I=n(G),e.$viewValue=G.length?p(G):"",e.$setValidity("mask",I),""===G&&void 0!==e.$error.required&&e.$setValidity("required",!1),I?G:void 0):a}function j(){return N=!1,m(),angular.isDefined(P)?c.attr("placeholder",P):c.removeAttr("placeholder"),angular.isDefined(Q)?c.attr("maxlength",Q):c.removeAttr("maxlength"),c.val(e.$modelValue),e.$viewValue=e.$modelValue,!1}function k(){G=K=o(e.$modelValue||""),H=J=p(G),I=n(G);var a=I&&G.length?H:"";d.maxlength&&c.attr("maxlength",2*B[B.length-1]),c.attr("placeholder",D),c.val(a),e.$viewValue=a}function l(){O||(c.bind("blur",t),c.bind("mousedown mouseup",u),c.bind("input keyup click focus",w),O=!0)}function m(){O&&(c.unbind("blur",t),c.unbind("mousedown",u),c.unbind("mouseup",u),c.unbind("input",w),c.unbind("keyup",w),c.unbind("click",w),c.unbind("focus",w),O=!1)}function n(a){return a.length?a.length>=F:!0}function o(a){var b="",c=C.slice();return a=a.toString(),angular.forEach(E,function(b){a=a.replace(b,"")}),angular.forEach(a.split(""),function(a){c.length&&c[0].test(a)&&(b+=a,c.shift())}),b}function p(a){var b="",c=B.slice();return angular.forEach(D.split(""),function(d,e){a.length&&e===c[0]?(b+=a.charAt(0)||"_",a=a.substr(1),c.shift()):b+=d}),b}function q(a){var b=d.placeholder;return"undefined"!=typeof b&&b[a]?b[a]:"_"}function r(){return D.replace(/[_]+/g,"_").replace(/([^_]+)([a-zA-Z0-9])([^_])/g,"$1$2_$3").split("_")}function s(a){var b=0;if(B=[],C=[],D="","string"==typeof a){F=0;var c=!1,d=a.split("");angular.forEach(d,function(a,d){R.maskDefinitions[a]?(B.push(b),D+=q(d),C.push(R.maskDefinitions[a]),b++,c||F++):"?"===a?c=!0:(D+=a,b++)})}B.push(B.slice().pop()+1),E=r(),N=B.length>1?!0:!1}function t(){L=0,M=0,I&&0!==G.length||(H="",c.val(""),a.$apply(function(){e.$setViewValue("")}))}function u(a){"mousedown"===a.type?c.bind("mouseout",v):c.unbind("mouseout",v)}function v(){M=A(this),c.unbind("mouseout",v)}function w(b){b=b||{};var d=b.which,f=b.type;if(16!==d&&91!==d){var g,h=c.val(),i=J,j=o(h),k=K,l=!1,m=y(this)||0,n=L||0,q=m-n,r=B[0],s=B[j.length]||B.slice().shift(),t=M||0,u=A(this)>0,v=t>0,w=h.length>i.length||t&&h.length>i.length-t,C=h.length<i.length||t&&h.length===i.length-t,D=d>=37&&40>=d&&b.shiftKey,E=37===d,F=8===d||"keyup"!==f&&C&&-1===q,G=46===d||"keyup"!==f&&C&&0===q&&!v,H=(E||F||"click"===f)&&m>r;if(M=A(this),!D&&(!u||"click"!==f&&"keyup"!==f)){if("input"===f&&C&&!v&&j===k){for(;F&&m>r&&!x(m);)m--;for(;G&&s>m&&-1===B.indexOf(m);)m++;var I=B.indexOf(m);j=j.substring(0,I)+j.substring(I+1),l=!0}for(g=p(j),J=g,K=j,c.val(g),l&&a.$apply(function(){e.$setViewValue(j)}),w&&r>=m&&(m=r+1),H&&m--,m=m>s?s:r>m?r:m;!x(m)&&m>r&&s>m;)m+=H?-1:1;(H&&s>m||w&&!x(n))&&m++,L=m,z(this,m)}}}function x(a){return B.indexOf(a)>-1}function y(a){if(!a)return 0;if(void 0!==a.selectionStart)return a.selectionStart;if(document.selection){a.focus();var b=document.selection.createRange();return b.moveStart("character",-a.value.length),b.text.length}return 0}function z(a,b){if(!a)return 0;if(0!==a.offsetWidth&&0!==a.offsetHeight)if(a.setSelectionRange)a.focus(),a.setSelectionRange(b,b);else if(a.createTextRange){var c=a.createTextRange();c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",b),c.select()}}function A(a){return a?void 0!==a.selectionStart?a.selectionEnd-a.selectionStart:document.selection?document.selection.createRange().text.length:0:0}var B,C,D,E,F,G,H,I,J,K,L,M,N=!1,O=!1,P=d.placeholder,Q=d.maxlength,R={};d.uiOptions?(R=a.$eval("["+d.uiOptions+"]"),angular.isObject(R[0])&&(R=function(a,b){for(var c in a)Object.prototype.hasOwnProperty.call(a,c)&&(b[c]?angular.extend(b[c],a[c]):b[c]=angular.copy(a[c]));return b}(b,R[0]))):R=b,d.$observe("uiMask",f),d.$observe("placeholder",g),e.$formatters.push(h),e.$parsers.push(i),c.bind("mousedown mouseup",u),Array.prototype.indexOf||(Array.prototype.indexOf=function(a){if(null===this)throw new TypeError;var b=Object(this),c=b.length>>>0;if(0===c)return-1;var d=0;if(arguments.length>1&&(d=Number(arguments[1]),d!==d?d=0:0!==d&&1/0!==d&&d!==-1/0&&(d=(d>0||-1)*Math.floor(Math.abs(d)))),d>=c)return-1;for(var e=d>=0?d:Math.max(c-Math.abs(d),0);c>e;e++)if(e in b&&b[e]===a)return e;return-1})}}}}]),angular.module("ui.reset",[]).value("uiResetConfig",null).directive("uiReset",["uiResetConfig",function(a){var b=null;return void 0!==a&&(b=a),{require:"ngModel",link:function(a,c,d,e){var f;f=angular.element('<a class="ui-reset" />'),c.wrap('<span class="ui-resetwrap" />').after(f),f.bind("click",function(c){c.preventDefault(),a.$apply(function(){e.$setViewValue(d.uiReset?a.$eval(d.uiReset):b),e.$render()})})}}}]),angular.module("ui.route",[]).directive("uiRoute",["$location","$parse",function(a,b){return{restrict:"AC",scope:!0,compile:function(c,d){var e;if(d.uiRoute)e="uiRoute";else if(d.ngHref)e="ngHref";else{if(!d.href)throw new Error("uiRoute missing a route or href property on "+c[0]);e="href"}return function(c,d,f){function g(b){var d=b.indexOf("#");d>-1&&(b=b.substr(d+1)),(j=function(){i(c,a.path().indexOf(b)>-1)})()}function h(b){var d=b.indexOf("#");d>-1&&(b=b.substr(d+1)),(j=function(){var d=new RegExp("^"+b+"$",["i"]);i(c,d.test(a.path()))})()}var i=b(f.ngModel||f.routeModel||"$uiRoute").assign,j=angular.noop;switch(e){case"uiRoute":f.uiRoute?h(f.uiRoute):f.$observe("uiRoute",h);break;case"ngHref":f.ngHref?g(f.ngHref):f.$observe("ngHref",g);break;case"href":g(f.href)}c.$on("$routeChangeSuccess",function(){j()}),c.$on("$stateChangeSuccess",function(){j()})}}}}]),angular.module("ui.scroll.jqlite",["ui.scroll"]).service("jqLiteExtras",["$log","$window",function(a,b){return{registerFor:function(a){var c,d,e,f,g,h,i;return d=angular.element.prototype.css,a.prototype.css=function(a,b){var c,e;return e=this,c=e[0],c&&3!==c.nodeType&&8!==c.nodeType&&c.style?d.call(e,a,b):void 0},h=function(a){return a&&a.document&&a.location&&a.alert&&a.setInterval},i=function(a,b,c){var d,e,f,g,i;return d=a[0],i={top:["scrollTop","pageYOffset","scrollLeft"],left:["scrollLeft","pageXOffset","scrollTop"]}[b],e=i[0],g=i[1],f=i[2],h(d)?angular.isDefined(c)?d.scrollTo(a[f].call(a),c):g in d?d[g]:d.document.documentElement[e]:angular.isDefined(c)?d[e]=c:d[e]},b.getComputedStyle?(f=function(a){return b.getComputedStyle(a,null)},c=function(a,b){return parseFloat(b)}):(f=function(a){return a.currentStyle},c=function(a,b){var c,d,e,f,g,h,i;return c=/[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,f=new RegExp("^("+c+")(?!px)[a-z%]+$","i"),f.test(b)?(i=a.style,d=i.left,g=a.runtimeStyle,h=g&&g.left,g&&(g.left=i.left),i.left=b,e=i.pixelLeft,i.left=d,h&&(g.left=h),e):parseFloat(b)}),e=function(a,b){var d,e,g,i,j,k,l,m,n,o,p,q,r;return h(a)?(d=document.documentElement[{height:"clientHeight",width:"clientWidth"}[b]],{base:d,padding:0,border:0,margin:0}):(r={width:[a.offsetWidth,"Left","Right"],height:[a.offsetHeight,"Top","Bottom"]}[b],d=r[0],l=r[1],m=r[2],k=f(a),p=c(a,k["padding"+l])||0,q=c(a,k["padding"+m])||0,e=c(a,k["border"+l+"Width"])||0,g=c(a,k["border"+m+"Width"])||0,i=k["margin"+l],j=k["margin"+m],n=c(a,i)||0,o=c(a,j)||0,{base:d,padding:p+q,border:e+g,margin:n+o})},g=function(a,b,c){var d,g,h;return g=e(a,b),g.base>0?{base:g.base-g.padding-g.border,outer:g.base,outerfull:g.base+g.margin}[c]:(d=f(a),h=d[b],(0>h||null===h)&&(h=a.style[b]||0),h=parseFloat(h)||0,{base:h-g.padding-g.border,outer:h,outerfull:h+g.padding+g.border+g.margin}[c])},angular.forEach({before:function(a){var b,c,d,e,f,g,h;if(f=this,c=f[0],e=f.parent(),b=e.contents(),b[0]===c)return e.prepend(a);for(d=g=1,h=b.length-1;h>=1?h>=g:g>=h;d=h>=1?++g:--g)if(b[d]===c)return void angular.element(b[d-1]).after(a);throw new Error("invalid DOM structure "+c.outerHTML)},height:function(a){var b;return b=this,angular.isDefined(a)?(angular.isNumber(a)&&(a+="px"),d.call(b,"height",a)):g(this[0],"height","base")},outerHeight:function(a){return g(this[0],"height",a?"outerfull":"outer")},offset:function(a){var b,c,d,e,f,g;return f=this,arguments.length?void 0===a?f:a:(b={top:0,left:0},e=f[0],(c=e&&e.ownerDocument)?(d=c.documentElement,e.getBoundingClientRect&&(b=e.getBoundingClientRect()),g=c.defaultView||c.parentWindow,{top:b.top+(g.pageYOffset||d.scrollTop)-(d.clientTop||0),left:b.left+(g.pageXOffset||d.scrollLeft)-(d.clientLeft||0)}):void 0)},scrollTop:function(a){return i(this,"top",a)},scrollLeft:function(a){return i(this,"left",a)}},function(b,c){return a.prototype[c]?void 0:a.prototype[c]=b})}}}]).run(["$log","$window","jqLiteExtras",function(a,b,c){return b.jQuery?void 0:c.registerFor(angular.element)}]),angular.module("ui.scroll",[]).directive("ngScrollViewport",["$log",function(){return{controller:["$scope","$element",function(a,b){return b}]}}]).directive("ngScroll",["$log","$injector","$rootScope","$timeout",function(a,b,c,d){return{require:["?^ngScrollViewport"],transclude:"element",priority:1e3,terminal:!0,compile:function(e,f,g){return function(f,h,i,j){var k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T;if(H=i.ngScroll.match(/^\s*(\w+)\s+in\s+(\w+)\s*$/),!H)throw new Error('Expected ngScroll in form of "item_ in _datasource_" but got "'+i.ngScroll+'"');if(F=H[1],v=H[2],D=function(a){return angular.isObject(a)&&a.get&&angular.isFunction(a.get)},u=f[v],!D(u)&&(u=b.get(v),!D(u)))throw new Error(v+" is not a valid datasource");return r=Math.max(3,+i.bufferSize||10),q=function(){return T.height()*Math.max(.1,+i.padding||.1)},O=function(a){return a[0].scrollHeight||a[0].document.documentElement.scrollHeight},k=null,g(R=f.$new(),function(a){var b,c,d,f,g,h;if(f=a[0].localName,"dl"===f)throw new Error("ng-scroll directive does not support <"+a[0].localName+"> as a repeating tag: "+a[0].outerHTML);return"li"!==f&&"tr"!==f&&(f="div"),h=j[0]||angular.element(window),h.css({"overflow-y":"auto",display:"block"}),d=function(a){var b,c,d;switch(a){case"tr":return d=angular.element("<table><tr><td><div></div></td></tr></table>"),b=d.find("div"),c=d.find("tr"),c.paddingHeight=function(){return b.height.apply(b,arguments)},c;default:return c=angular.element("<"+a+"></"+a+">"),c.paddingHeight=c.height,c}},c=function(a,b,c){return b[{top:"before",bottom:"after"}[c]](a),{paddingHeight:function(){return a.paddingHeight.apply(a,arguments)},insert:function(b){return a[{top:"after",bottom:"before"}[c]](b)}}},g=c(d(f),e,"top"),b=c(d(f),e,"bottom"),R.$destroy(),k={viewport:h,topPadding:g.paddingHeight,bottomPadding:b.paddingHeight,append:b.insert,prepend:g.insert,bottomDataPos:function(){return O(h)-b.paddingHeight()},topDataPos:function(){return g.paddingHeight()}}}),T=k.viewport,B=1,I=1,p=[],J=[],x=!1,n=!1,G=u.loading||function(){},E=!1,L=function(a,b){var c,d;for(c=d=a;b>=a?b>d:d>b;c=b>=a?++d:--d)p[c].scope.$destroy(),p[c].element.remove();return p.splice(a,b-a)},K=function(){return B=1,I=1,L(0,p.length),k.topPadding(0),k.bottomPadding(0),J=[],x=!1,n=!1,l(!1)},o=function(){return T.scrollTop()+T.height()},S=function(){return T.scrollTop()},P=function(){return!x&&k.bottomDataPos()<o()+q()},s=function(){var b,c,d,e,f,g;for(b=0,e=0,c=f=g=p.length-1;(0>=g?0>=f:f>=0)&&(d=p[c].element.outerHeight(!0),k.bottomDataPos()-b-d>o()+q());c=0>=g?++f:--f)b+=d,e++,x=!1;return e>0?(k.bottomPadding(k.bottomPadding()+b),L(p.length-e,p.length),I-=e,a.log("clipped off bottom "+e+" bottom padding "+k.bottomPadding())):void 0},Q=function(){return!n&&k.topDataPos()>S()-q()},t=function(){var b,c,d,e,f,g;for(e=0,d=0,f=0,g=p.length;g>f&&(b=p[f],c=b.element.outerHeight(!0),k.topDataPos()+e+c<S()-q());f++)e+=c,d++,n=!1;return d>0?(k.topPadding(k.topPadding()+e),L(0,d),B+=d,a.log("clipped off top "+d+" top padding "+k.topPadding())):void 0},w=function(a,b){return E||(E=!0,G(!0)),1===J.push(a)?z(b):void 0},C=function(a,b){var c,d,e;return c=f.$new(),c[F]=b,d=a>B,c.$index=a,d&&c.$index--,e={scope:c},g(c,function(b){return e.element=b,d?a===I?(k.append(b),p.push(e)):(p[a-B].element.after(b),p.splice(a-B+1,0,e)):(k.prepend(b),p.unshift(e))}),{appended:d,wrapper:e}},m=function(a,b){var c;return a?k.bottomPadding(Math.max(0,k.bottomPadding()-b.element.outerHeight(!0))):(c=k.topPadding()-b.element.outerHeight(!0),c>=0?k.topPadding(c):T.scrollTop(T.scrollTop()+b.element.outerHeight(!0)))},l=function(b,c,e){var f;return f=function(){return a.log("top {actual="+k.topDataPos()+" visible from="+S()+" bottom {visible through="+o()+" actual="+k.bottomDataPos()+"}"),P()?w(!0,b):Q()&&w(!1,b),e?e():void 0},c?d(function(){var a,b,d;for(b=0,d=c.length;d>b;b++)a=c[b],m(a.appended,a.wrapper);return f()}):f()},A=function(a,b){return l(a,b,function(){return J.shift(),0===J.length?(E=!1,G(!1)):z(a)})},z=function(b){var c;return c=J[0],c?p.length&&!P()?A(b):u.get(I,r,function(c){var d,e,f,g;if(e=[],0===c.length)x=!0,k.bottomPadding(0),a.log("appended: requested "+r+" records starting from "+I+" recieved: eof");else{for(t(),f=0,g=c.length;g>f;f++)d=c[f],e.push(C(++I,d));a.log("appended: requested "+r+" received "+c.length+" buffer size "+p.length+" first "+B+" next "+I)}return A(b,e)}):p.length&&!Q()?A(b):u.get(B-r,r,function(c){var d,e,f,g;if(e=[],0===c.length)n=!0,k.topPadding(0),a.log("prepended: requested "+r+" records starting from "+(B-r)+" recieved: bof");else{for(s(),d=f=g=c.length-1;0>=g?0>=f:f>=0;d=0>=g?++f:--f)e.unshift(C(--B,c[d]));a.log("prepended: requested "+r+" received "+c.length+" buffer size "+p.length+" first "+B+" next "+I)}return A(b,e)})},M=function(){return c.$$phase||E?void 0:(l(!1),f.$apply())},T.bind("resize",M),N=function(){return c.$$phase||E?void 0:(l(!0),f.$apply())},T.bind("scroll",N),f.$watch(u.revision,function(){return K()}),y=u.scope?u.scope.$new():f.$new(),f.$on("$destroy",function(){return y.$destroy(),T.unbind("resize",M),T.unbind("scroll",N)}),y.$on("update.items",function(a,b,c){var d,e,f,g,h;if(angular.isFunction(b))for(e=function(a){return b(a.scope)},f=0,g=p.length;g>f;f++)d=p[f],e(d);else 0<=(h=b-B-1)&&h<p.length&&(p[b-B-1].scope[F]=c);return null}),y.$on("delete.items",function(a,b){var c,d,e,f,g,h,i,j,k,m,n,o;if(angular.isFunction(b)){for(e=[],h=0,k=p.length;k>h;h++)d=p[h],e.unshift(d);for(g=function(a){return b(a.scope)?(L(e.length-1-c,e.length-c),I--):void 0},c=i=0,m=e.length;m>i;c=++i)f=e[c],g(f)}else 0<=(o=b-B-1)&&o<p.length&&(L(b-B-1,b-B),I--);for(c=j=0,n=p.length;n>j;c=++j)d=p[c],d.scope.$index=B+c;return l(!1)}),y.$on("insert.item",function(a,b,c){var d,e,f,g,h,i,j,k,m,n,o,q;if(e=[],angular.isFunction(b)){for(f=[],i=0,m=p.length;m>i;i++)c=p[i],f.unshift(c);for(h=function(a){var f,g,h,i,j;if(g=b(a.scope)){if(C=function(a,b){return C(a,b),I++},angular.isArray(g)){for(j=[],f=h=0,i=g.length;i>h;f=++h)c=g[f],j.push(e.push(C(d+f,c)));return j}return e.push(C(d,g))}},d=j=0,n=f.length;n>j;d=++j)g=f[d],h(g)}else 0<=(q=b-B-1)&&q<p.length&&(e.push(C(b,c)),I++);for(d=k=0,o=p.length;o>k;d=++k)c=p[d],c.scope.$index=B+d;return l(!1,e)})}}}}]),angular.module("ui.scrollfix",[]).directive("uiScrollfix",["$window",function(a){return{require:"^?uiScrollfixTarget",link:function(b,c,d,e){function f(){var b;if(angular.isDefined(a.pageYOffset))b=a.pageYOffset;else{var e=document.compatMode&&"BackCompat"!==document.compatMode?document.documentElement:document.body;b=e.scrollTop}!c.hasClass("ui-scrollfix")&&b>d.uiScrollfix?c.addClass("ui-scrollfix"):c.hasClass("ui-scrollfix")&&b<d.uiScrollfix&&c.removeClass("ui-scrollfix")}var g=c[0].offsetTop,h=e&&e.$element||angular.element(a);d.uiScrollfix?"string"==typeof d.uiScrollfix&&("-"===d.uiScrollfix.charAt(0)?d.uiScrollfix=g-parseFloat(d.uiScrollfix.substr(1)):"+"===d.uiScrollfix.charAt(0)&&(d.uiScrollfix=g+parseFloat(d.uiScrollfix.substr(1)))):d.uiScrollfix=g,h.on("scroll",f),b.$on("$destroy",function(){h.off("scroll",f)})}}}]).directive("uiScrollfixTarget",[function(){return{controller:["$element",function(a){this.$element=a}]}}]),angular.module("ui.showhide",[]).directive("uiShow",[function(){return function(a,b,c){a.$watch(c.uiShow,function(a){a?b.addClass("ui-show"):b.removeClass("ui-show")})}}]).directive("uiHide",[function(){return function(a,b,c){a.$watch(c.uiHide,function(a){a?b.addClass("ui-hide"):b.removeClass("ui-hide")})}}]).directive("uiToggle",[function(){return function(a,b,c){a.$watch(c.uiToggle,function(a){a?b.removeClass("ui-hide").addClass("ui-show"):b.removeClass("ui-show").addClass("ui-hide")})}}]),angular.module("ui.unique",[]).filter("unique",["$parse",function(a){return function(b,c){if(c===!1)return b;if((c||angular.isUndefined(c))&&angular.isArray(b)){var d=[],e=angular.isString(c)?a(c):function(a){return a},f=function(a){return angular.isObject(a)?e(a):a};angular.forEach(b,function(a){for(var b=!1,c=0;c<d.length;c++)if(angular.equals(f(d[c]),f(a))){b=!0;break}b||d.push(a)}),b=d}return b}}]),angular.module("ui.validate",[]).directive("uiValidate",function(){return{restrict:"A",require:"ngModel",link:function(a,b,c,d){function e(b){return angular.isString(b)?void a.$watch(b,function(){angular.forEach(g,function(a){a(d.$modelValue)})}):angular.isArray(b)?void angular.forEach(b,function(b){a.$watch(b,function(){angular.forEach(g,function(a){a(d.$modelValue)})})}):void(angular.isObject(b)&&angular.forEach(b,function(b,c){angular.isString(b)&&a.$watch(b,function(){g[c](d.$modelValue)}),angular.isArray(b)&&angular.forEach(b,function(b){a.$watch(b,function(){g[c](d.$modelValue)})})}))}var f,g={},h=a.$eval(c.uiValidate);h&&(angular.isString(h)&&(h={validator:h}),angular.forEach(h,function(b,c){f=function(e){var f=a.$eval(b,{$value:e});return angular.isObject(f)&&angular.isFunction(f.then)?(f.then(function(){d.$setValidity(c,!0)},function(){d.$setValidity(c,!1)}),e):f?(d.$setValidity(c,!0),e):(d.$setValidity(c,!1),e)},g[c]=f,d.$formatters.push(f),d.$parsers.push(f)}),c.uiValidateWatch&&e(a.$eval(c.uiValidateWatch)))}}}),angular.module("ui.utils",["ui.event","ui.format","ui.highlight","ui.include","ui.indeterminate","ui.inflector","ui.jq","ui.keypress","ui.mask","ui.reset","ui.route","ui.scrollfix","ui.scroll","ui.scroll.jqlite","ui.showhide","ui.unique","ui.validate"]);
"use strict";angular.module("unsavedChanges",["lazyModel"]).provider("unsavedWarningsConfig",function(){var f=this;var e=false;var b=true;var d=["$locationChangeStart","$stateChangeStart"];var c="You will lose unsaved changes if you leave this page";var a="You will lose unsaved changes if you reload this page";Object.defineProperty(f,"navigateMessage",{get:function(){return c},set:function(g){c=g}});Object.defineProperty(f,"reloadMessage",{get:function(){return a},set:function(g){a=g}});Object.defineProperty(f,"useTranslateService",{get:function(){return b},set:function(g){b=!!(g)}});Object.defineProperty(f,"routeEvent",{get:function(){return d},set:function(g){if(typeof g==="string"){g=[g]}d=g}});Object.defineProperty(f,"logEnabled",{get:function(){return e},set:function(g){e=!!(g)}});this.$get=["$injector",function(h){function i(j){if(h.has("$translate")&&b){return h.get("$translate")(j)}else{return false}}var g={log:function(){if(console.log&&e&&arguments.length){var j=[].slice.call(arguments);if(typeof console.log==="object"){log.apply.call(console.log,console,j)}else{console.log.apply(console,j)}}}};Object.defineProperty(g,"useTranslateService",{get:function(){return b}});Object.defineProperty(g,"reloadMessage",{get:function(){return i(a)||a}});Object.defineProperty(g,"navigateMessage",{get:function(){return i(c)||c}});Object.defineProperty(g,"routeEvent",{get:function(){return d}});Object.defineProperty(g,"logEnabled",{get:function(){return e}});return g}]}).service("unsavedWarningSharedService",["$rootScope","unsavedWarningsConfig","$injector",function(j,c,k){var i=this;var a=[];var g=true;var d=[angular.noop];this.allForms=function(){return a};var f={navigate:c.navigateMessage,reload:c.reloadMessage};function h(){g=true;angular.forEach(a,function(m,l){c.log("Form : "+m.$name+" dirty : "+m.$dirty);if(m.$dirty){g=false}});return g}this.init=function(l){if(a.length===0){e()}c.log("Registering form",l);a.push(l)};this.removeForm=function(m){var l=a.indexOf(m);if(l===-1){return}a.splice(l,1);c.log("Removing form from watch list",m);if(a.length===0){b()}};function b(){c.log("No more forms, tearing down");angular.forEach(d,function(l){l()});window.onbeforeunload=null}this.confirmExit=function(){if(!h()){return f.reload}b()};function e(){c.log("Setting up");window.onbeforeunload=i.confirmExit;var l=c.routeEvent;angular.forEach(l,function(m){var n=j.$on(m,function(p,o,q){c.log("user is moving with "+m);if(!h()){c.log("a form is dirty");if(!confirm(f.navigate)){c.log("user wants to cancel leaving");p.preventDefault()}else{c.log("user doesn't care about loosing stuff")}}else{c.log("all forms are clean")}});d.push(n)})}}]).directive("unsavedWarningClear",["unsavedWarningSharedService",function(a){return{scope:true,require:"^form",priority:3000,link:function(d,c,b,e){c.bind("click",function(f){e.$setPristine()})}}}]).directive("unsavedWarningForm",["unsavedWarningSharedService",function(a){return{require:"form",link:function(d,c,b,e){a.init(e);c.bind("submit",function(f){if(e.$valid){e.$setPristine()}});d.$on("$destroy",function(){a.removeForm(e)})}}}]);angular.module("lazyModel",[]).directive("lazyModel",["$parse","$compile",function(b,a){return{restrict:"A",priority:500,terminal:true,require:"^form",scope:true,compile:function c(g,e){var f=b(e.lazyModel);var h=f.assign;g.attr("ng-model","buffer");g.removeAttr("lazy-model");return{pre:function(i,j){i.buffer=f(i.$parent);a(j)(i)},post:function d(j,l,i,m){var k=l.parent();while(k[0].tagName!=="FORM"){k=k.parent()}k.bind("submit",function(){if(m.$valid){j.$apply(function(){h(j.$parent,j.buffer)})}});k.bind("reset",function(n){n.preventDefault();j.$apply(function(){j.buffer=f(j.$parent)})})}}}}}]);