var CORE_FILES=["scopeVariable.js","lib/txlib.js","lib/hyperscript.js","lib/template.js","lib/dgetty.js","lib/xgetty.js","lib/rubber.js","trex/trex.js","trex/config.js","trex/event.js","trex/lib/markup.js","trex/lib/domutil.js","trex/lib/utils.js","trex/mixins/ajax.js","trex/mixins/observable.js","popuputil.js"];try{var urlParams={};!function(){for(var r,t=/\+/g,e=/([^&=]+)=?([^&]*)/g,s=function(r){return decodeURIComponent(r.replace(t," "))},a=window.location.search.substring(1);r=e.exec(a);)urlParams[s(r[1])]=s(r[2])}(),urlParams.xssDomain&&(document.domain=urlParams.xssDomain)}catch(r){}try{var basePath=opener.EditorJSLoader.getJSBasePath()}catch(r){}for(var i=0;i<CORE_FILES.length;i++)if(CORE_FILES[i]){var src=basePath+CORE_FILES[i]+"?v="+(new Date).getTime();document.write('<script type="text/javascript" src="'+src+'" charset="utf-8"></script>')}