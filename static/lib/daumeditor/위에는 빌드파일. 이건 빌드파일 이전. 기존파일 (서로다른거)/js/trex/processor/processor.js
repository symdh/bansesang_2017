!function(){var t=Trex.Class.create({initialize:function(t,r,e,n){this.processor=t,this.start=e,this.end=n||this.start,this.current=this.start,this.wTranslator=$tom.translate(r).extract("%wrapper"),this.pTranslator=$tom.translate(r).extract("%paragraph")},hasNext:function(){return!!this.current},next:function(){var t=this.current;t=this.find(t);var r=t;if($tom.include(t,this.end))r=_NULL;else{for(;r&&!$tom.next(r);)r=$tom.parent(r),$tom.isBody(r)&&(r=_NULL);r&&(r=$tom.next(r))}return r==this.end&&(r=_NULL),this.current=r,t},find:function(t){var r,e=t;if(!$tom.hasContent(e))return e;for(;e&&(r=e,!$tom.isBody(e));){if($tom.kindOf(e,this.wTranslator.getExpression()))return e;if($tom.kindOf(e,"%wrapper,%outergroup")){if(e=$tom.descendant(r,this.pTranslator.getExpression()))return e;if(e=$tom.descendant(r,"%paragraph")){r=e;break}}if($tom.kindOf(e,this.pTranslator.getExpression()))return e;e=e.nextSibling&&3==e.nodeType?e.nextSibling:e.parentNode}var n=$tom.paragraphOf($tom.getName(r)),o=this.processor.newNode(n),s=$tom.extract(r,t,"%text,%inline,img,object,embed,hr");return $tom.wrap(o,s),this.processor.stuffNode(o),o}});Object.extend(Trex.I.Processor.Standard,{getBlockRangeIterator:function(r,e,n){return new t(this,r,e,n)}})}(),function(){var t=Trex.Class.create({initialize:function(t,r,e,n){this.processor=t,this.start=e,this.end=n||this.start,this.current=this.start,this.iTranslator=$tom.translate(r).extract("%inline")},hasNext:function(){return!!this.current},next:function(){var t=this.current;t=this.find(t);var r=t;if(t==this.end)r=_NULL;else{for(;r&&!$tom.next(r);)r=$tom.parent(r),$tom.isBody(r)&&(r=_NULL);r&&(r=$tom.next(r))}return $tom.include(r,this.end)&&(r=$tom.top(r,_TRUE)),this.current=r,t},find:function(t){var r=t;if($tom.kindOf(r,"%paragraph,%outergroup,%block")||$tom.isBody(r)){var e=r;if(r=$tom.top(e,_TRUE),!r){var n=$tom.inlineOf(),o=this.processor.create(n);return $tom.append(e,o),o}}if($tom.kindOf(r,"br"))return r;if(!$tom.hasContent(r))return r;if($tom.kindOf(r,this.iTranslator.getExpression()))return r;var n=$tom.inlineOf(),o=this.processor.create(n);return $tom.insertAt(o,r),r&&$tom.append(o,r),o}});Object.extend(Trex.I.Processor.Standard,{getInlineRangeIterator:function(r,e,n){return new t(this,r,e,n)}})}(),function(){var t=_NULL,r={},e={};Object.extend(Trex.I.Processor.Standard,{newNode:function(e){return t!=this.doc&&(r={},t=this.doc),r[e]||(r[e]=this.win[e]()),$tom.clone(r[e],_FALSE)},newText:function(t){return this.doc.createTextNode(t)},newParagraph:function(r){return t!=this.doc&&(e={},t=this.doc),e[r]||(e[r]=this.stuffNode(this.newNode(r))),$tom.clone(e[r],_TRUE)}})}(),function(){var t=_NULL,r=_NULL,e=_FALSE,n=[];Object.extend(Trex.I.Processor.Standard,{newDummy:function(o){t!=this.doc&&(r=_NULL,n=[],t=this.doc),r||(r=this.doc.createTextNode(Trex.__WORD_JOINER));var s=$tom.clone(r);return o||(n.push(s),e=_TRUE),s},clearDummy:function(){if(e){var t,r;try{t=this.createGoogRange(),r=t&&t.getStartNode()}catch(t){}for(var o=_NULL,s=0,i=n.length-1;s<i;s++)try{var a=n.shift();a&&a.nodeValue&&(a.nodeValue==Trex.__WORD_JOINER?r!=a?$tom.remove(a):o=a:a.nodeValue=a.nodeValue.replace(Trex.__WORD_JOINER_REGEXP,""))}catch(t){}o&&n.splice(0,0,o),e=_FALSE}}})}(),Trex.Canvas.Processor=Trex.Class.draft({$mixins:[Trex.I.Processor.Standard,$tx.msie_nonstd?Trex.I.Processor.Trident:{},$tx.msie_std?Trex.I.Processor.TridentStandard:{},$tx.gecko?Trex.I.Processor.Gecko:{},$tx.webkit?Trex.I.Processor.Webkit:{},$tx.presto?Trex.I.Processor.Presto:{}]}),Trex.Canvas.ProcessorP=Trex.Class.create({$extend:Trex.Canvas.Processor,$mixins:[Trex.I.Processor.StandardP,$tx.msie_nonstd?Trex.I.Processor.TridentP:{},$tx.msie_std?Trex.I.Processor.TridentStandardP:{},$tx.gecko?Trex.I.Processor.GeckoP:{},$tx.webkit?Trex.I.Processor.WebkitP:{},$tx.presto?Trex.I.Processor.PrestoP:{}]});