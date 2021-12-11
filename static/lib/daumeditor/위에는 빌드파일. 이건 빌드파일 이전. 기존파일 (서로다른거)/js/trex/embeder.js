TrexMessage.addMsg({"@embeder.alert":"에디터 상태에서만 삽입할 수 있습니다."}),Trex.EmbedBox=Trex.Class.create({$extend:Trex.EntryBox,initialize:function(){}}),Trex.install("editor.getEmbedBox & sidebar.getEmbeder & sidebar.getEmbeddedData",function(t,e,n,a,o){var r=new Trex.EmbedBox(o,a,t);n.entryboxRegistry.embedbox=r,t.getEmbedBox=function(){return r},n.getEmbeddedData=r.getEntries.bind(r);var i=n.embeders={};n.getEmbeder=function(t){return i[t]!=_NULL?i[t]:0==arguments.length?i:_NULL}}),Trex.register("new embeders",function(t,e,n,a,o){var r=t.getEmbedBox(),i=n.embeders;for(var d in Trex.Embeder){var s=Trex.Embeder[d].__Identity;s&&(!e.tools[s],i[s]=new Trex.Embeder[d](t,r,o))}}),Trex.Embeder=Trex.Class.draft({$extend:Trex.Actor,canResized:_FALSE,initialize:function(t,e,n){this.editor=t,this.canvas=t.getCanvas(),this.entryBox=e;var a=this.config=TrexConfig.getEmbeder(this.constructor.__Identity,n);n.pvpage&&a.usepvpage&&(this.pvUrl=TrexConfig.getUrl(n.pvpage,{pvname:this.name})),this.wysiwygonly=a.wysiwygonly!=_NULL?a.wysiwygonly:_TRUE,this.pastescope=a.pastescope,this.embedHandler=this.embedHandler.bind(this),this.oninitialized&&this.oninitialized.bind(this)(n)},execute:function(){if(this.wysiwygonly&&!this.canvas.isWYSIWYG())return void alert(TXMSG("@embeder.alert"));if(this.clickHandler)this.clickHandler();else try{var t=this.config.popPageUrl,e=document.location.hostname!=document.domain;e&&(t=t+(t.indexOf("?")>-1?"&":"?")+"xssDomain="+document.domain),t=this.pvUrl?this.pvUrl+(this.pvUrl.indexOf("?")>-1?"&":"?")+"u="+escape(t):t;var n=_WIN.open(t,"at"+this.name,this.config.features);n.focus()}catch(t){}},embedHandler:function(t){this.execAttach(t)},createEntry:function(t,e){var n=this.constructor.__Identity;return e&&(n=e),new(Trex.EmbedEntry[n.capitalize()])(this,t)},execAttach:function(t){var e=this.pastescope,n=this.getCreatedHtml(t),a=this.config.parastyle||this.config.defaultstyle||{};this.canvas.execute(function(t){t.moveCaretWith(e),t.pasteContent(n,_TRUE,a)})},execReattach:function(){},execReload:function(){},getReloadContent:function(t,e){if(!t.dispElId)return e;var n=this.getCreatedHtml(t),a=new RegExp('<(?:img|IMG)[^>]*id="?'+t.dispElId+'"?[^>]*/?>',"gm");return e.search(a)>-1?e.replace(a,n):e}}),Trex.register("filter > embeder",function(t){var e=t.getEmbedBox(),n=t.getDocParser();n.registerFilter("filter/embeder",{"text@load":function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("text@load",t),t=e.getChangedContent(t,e.regLoad,"")}),t},"source@load":function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("source@load",t),t=e.getChangedContent(t,e.regLoad,e.dispText)}),t},"html@load":function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("html@load",t),t=e.getChangedContent(t,e.regLoad,e.dispHtml)}),t},text4save:function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("text4save",t),t=e.getChangedContent(t,e.regText,"")}),t},source4save:function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("source4save",t),t=e.getChangedContent(t,e.regText,e.saveHtml,["id","class"])}),t},html4save:function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("html4save",t),t=e.getChangedContent(t,e.regHtml,e.saveHtml,["id","class"])}),t},text2source:function(t){return t},text2html:function(t){return t},source2text:function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("source2text",t),t=e.getChangedContent(t,e.regText,"")}),t},source2html:function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("source2html",t),t=e.getChangedContent(t,e.regText,e.dispHtml)}),t},html2text:function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("html2text",t),t=e.getChangedContent(t,e.regHtml,"")}),t},html2source:function(t){var n=e.datalist;return n.each(function(e){e.loadDataByContent&&e.loadDataByContent("html2source",t),t=e.getChangedContent(t,e.regHtml,e.dispText,["id","class"])}),t}})}),Trex.module("embad entry data",function(t,e,n,a,o){var r=t.getEmbedBox(),i=n.embeders;t.observeJob(Trex.Ev.__EDITOR_LOAD_DATA_BEGIN,function(t){function e(t,e){t=t||[],e=e||"",t.each(function(t){try{var n=i[t.embeder];n&&n.execReload(t.data,e,t.type)}catch(t){console.error("첨부데이터 일부를 정상적으로 불러오지 못했습니다:",t)}})}r.empty();var n=t.content;for(var a in i)i[a].onloadData&&e(i[a].onloadData(n),n)})});