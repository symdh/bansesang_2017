Trex.I.FontTool=Trex.Mixin.create({initialize:function(e,t,n){this.$super.initialize(e,t,n)},handler:function(e){this.onBeforeHandler(e),this.doHandle(e),this.onAfterHandler(e)},onBeforeHandler:function(){},doHandle:function(e){var t,n=this,r=n.computeNewStyle(e);n.canvas.execute(function(e){var o=e.table?e.table.getTdArr():[];o.length>0?(t=goog.dom.Range.createFromNodeContents(o[0]),e.executeUsingCaret(function(){n.tableCellsExecutor(e,r,o)})):(t=e.createGoogRange(),t&&n.rangeExecutor(e,r,t))})},onAfterHandler:function(){},tableCellsExecutor:function(e,t,n){var r=this;n.each(function(n){var o=goog.dom.Range.createFromNodeContents(n);o.select(),r.rangeExecutor(e,t,o)})},findQueryingNode:function(e){if(e){var t;try{t=this.findFirst(e.__iterator__(),function(e){return 3==e.nodeType&&e.nodeValue.trim()})}catch(e){}if(t)return t.parentNode;var n=e.getStartNode();return n&&3==n.nodeType?n.parentNode:n}},findFirst:function(e,t){try{return goog.iter.filter(e,t).next()}catch(e){return null}}}),Trex.I.WrappingSpanFontTool=Trex.Mixin.create({wrapTextAsStyledSpan:function(e,t,n){function r(e){var t=e.getCaret(_TRUE),n=e.getCaret(_FALSE);return new goog.dom.TextRangeIterator(t,0,n,0)}function o(e){var t=[];return goog.iter.forEach(e,function(e){3!=e.nodeType||$tom.kindOf(e.parentNode,"table,thead,tbody,tr,ul,ol")||t.push(e)}),t}function a(t){var n=[];return t.each(function(t){var r=t.parentNode;if("SPAN"==r.nodeName&&i(r))n.push(r);else{var o=e.create("span");$tom.wrap(o,t),n.push(o)}}),n}function i(e){var t=e.childNodes,n=t.length;if(n>3)return _FALSE;for(var r=0,o=n;r<o;r++)$tom.isGoogRangeCaret(t[r])&&(n-=1);return 1==n}var u;if(e.isCollapsed()){var l=n.getStartNode();3==l.nodeType&&(l=l.parentNode);var c=this.findOrCreateDummySpan(l,e,n),d=c.firstChild;e.createGoogRangeFromNodes(d,d.length,d,d.length).select(),u=[c]}else e.executeUsingCaret(function(e,t){var n=r(t),i=o(n);u=a(i)});e.apply(u,{style:t})},findOrCreateDummySpan:function(e,t,n){var r="SPAN"==e.tagName&&1==e.childNodes.length&&3==e.firstChild.nodeType&&e.firstChild.nodeValue==Trex.__WORD_JOINER;return r?e:this.createDummySpan(e,t,n)},createDummySpan:function(e,t,n){var r=null;return r="SPAN"==e.tagName?$tom.clone(e):t.create("span"),r.appendChild(t.newDummy()),r=n.insertNode(r),$tom.removeEmptyTextNode(r.previousSibling),$tom.removeEmptyTextNode(r.nextSibling),r}}),Trex.I.WrappingDummyFontTool=Trex.Mixin.create({wrapDummy:function(e,t){var n=this.createDummySpan(e,t),r=n.firstChild;return $tom.unwrap(n),e.createGoogRangeFromNodes(r,0,r,r.length).select(),r},createDummySpan:function(e,t){var n=null;return n=e.create("span"),n.appendChild(e.newDummy()),n=t.insertNode(n),$tom.removeEmptyTextNode(n.previousSibling),$tom.removeEmptyTextNode(n.nextSibling),n}});