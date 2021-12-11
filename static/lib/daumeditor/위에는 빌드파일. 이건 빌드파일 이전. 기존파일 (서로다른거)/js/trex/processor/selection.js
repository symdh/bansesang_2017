Trex.I.Selection={},Trex.I.Selection.Standard={getSel:function(){return this.win.getSelection()},getText:function(){return this.getSel().toString()},getNode:function(){var e=this.getRange();if(e){var t=e.startContainer;return 1==t.nodeType?$tom.isBody(t)?t:t.childNodes[e.startOffset]:t.parentNode}return _NULL},createRange:function(){return this.doc.createRange()},createTextRange:function(){return this.doc.createRange()},getRange:function(e){var t=this.getSel();if(t&&t.rangeCount>0){if(e==_NULL)return 1==t.rangeCount?t.getRangeAt(0):this.mergeRange(t);var n=t.getRangeAt(0);return n.collapse(e),n}return this.doc.createRange()},isCollapsed:function(){var e=this.getSel();return e&&e.isCollapsed},collapse:function(e){var t=this.getSel();if(t&&t.rangeCount>0){var n=t.getRangeAt(0);n.collapse(e)}},getControl:function(){var e,t=this.getSel();if($tx.opera){if(e=t.anchorNode.childNodes[t.anchorOffset],e==_NULL)return _NULL;if(t.isCollapsed&&"IMG"!=e.tagName)return _NULL}else{if(t.isCollapsed)return _NULL;e=t.anchorNode.childNodes[t.anchorOffset]}return $tom.kindOf(e,"%control")?e:_NULL},hasControl:function(){return this.getControl()!=_NULL},selectControl:function(e){var t=this.createRange();t.selectNode(e);var n=this.getSel();n.removeAllRanges(),n.addRange(t)},compareTextPos:function(){var e=this.getRange();if(e){var t=e.startContainer;if(3==t.nodeType)return 0==t.textContent.trim().length?$tom.__POSITION.__EMPTY_TEXT:0==e.startOffset?$tom.__POSITION.__START_OF_TEXT:e.startOffset==t.textContent.length?$tom.__POSITION.__END_OF_TEXT:$tom.__POSITION.__MIDDLE_OF_TEXT}return $tom.__POSITION.__END_OF_TEXT},mergeRange:function(e){try{for(var t=[],n=0,r=e.rangeCount;n<r;n++)t.push(e.getRangeAt(n));e.removeAllRanges();var o=t[0].startContainer.childNodes[t[0].startOffset],a=t[r-1].endContainer.childNodes[t[r-1].endOffset-1],c=this.doc.createRange();try{c.setStart(o,0)}catch(e){c.collapse(_TRUE)}try{c.setEnd(a,a.childNodes.length)}catch(e){}return e.addRange(c),e.getRangeAt(0)}catch(t){return e.getRangeAt(0)}},setStart:function(e,t,n){try{e.setStart(t,n)}catch(r){e.collapse(_TRUE),e.setStart(t,n)}},setEnd:function(e,t,n){try{e.setEnd(t,n)}catch(r){e.collapse(_FALSE),e.setEnd(t,n)}},selectRange:function(e){var t=this.getSel();t.removeAllRanges(),t.addRange(e)}},Trex.I.Selection.Trident={getSel:function(){return this.doc.selection},getText:function(){return this.getSel().createRange().text},getNode:function(){var e=this.getSel(),t=e.type.toLowerCase();return"control"===t?e.createRange().item(0):e.createRange().parentElement()},createRange:function(){var e=this.getSel();return e.createRange()},createTextRange:function(){return this.doc.body.createTextRange()},getRange:function(e){var t=this.getSel(),n=t.type.toLowerCase();if("none"==n)return t.createRange()?t.createRange():function(){var e=this.doc.body.createTextRange();return e.collapse(_TRUE),e.select(),e}();if(e==_NULL)return t.createRange();if("text"===n){var r=t.createRange();return r.collapse(e),r.select(),t.createRange()}return"control"===n&&t.empty(),t.createRange()},isCollapsed:function(){var e=this.getSel(),t=e.type.toLowerCase();if("none"===t)return _TRUE;if("control"===t)return _TRUE;if("text"===t){var n=e.createRange();return 0==n.compareEndPoints("StartToEnd",n)}return _TRUE},collapse:function(e){var t=this.getSel(),n=t.type.toLowerCase();if("text"===n){var r=t.createRange();return r.collapse(e),r.select(),t.createRange()}return"control"===n&&t.empty(),t.createRange()},getControl:function(){var e=this.getSel(),t=e.type.toLowerCase();if("control"===t){var n=e.createRange().item(0);return $tom.kindOf(n,"%control")?n:_NULL}return _NULL},hasControl:function(){var e=this.getSel(),t=e.type.toLowerCase();return"control"===t?_TRUE:_FALSE},selectControl:function(e){var t=this.doc.body.createControlRange();t.add(e),t.select()},compareTextPos:function(){var e=this.getSel(),t=e.type.toLowerCase();if("none"===t){var n=e.createRange(),r=n.duplicate();return r.moveToElementText(n.parentElement()),0==r.text.trim().replace(Trex.__WORD_JOINER_REGEXP,"").length?$tom.__POSITION.__EMPTY_TEXT:0==n.compareEndPoints("StartToStart",r)?$tom.__POSITION.__START_OF_TEXT:0==n.compareEndPoints("EndToEnd",r)?$tom.__POSITION.__END_OF_TEXT:$tom.__POSITION.__MIDDLE_OF_TEXT}return $tom.__POSITION.__END_OF_TEXT},transTextRange:function(e,t,n,r){var o=this.createTextRange(),a=this.win.span(Trex.__WORD_JOINER);return $tom.insertAt(a,t),o.moveToElementText(a),$tom.remove(a),o.collapse(_TRUE),o.moveStart("character",n),r?e.setEndPoint("StartToStart",o):e.setEndPoint("EndToEnd",o),e},setStart:function(e,t,n){try{this.transTextRange(e,t,n,_TRUE)}catch(e){console.log(e)}return e},setEnd:function(e,t,n){try{this.transTextRange(e,t,n,_FALSE)}catch(e){console.log(e)}return e},selectRange:function(e){e.select()}},Trex.I.Selection.TridentStandard={getControl:function(){var e=this.getSel();if(e.isCollapsed)return null;if($tom.isElement(e.anchorNode)){var t=e.anchorNode.childNodes[e.anchorOffset];return $tom.kindOf(t,"%control")?t:null}var n=$tom.previous(e.focusNode),r=$tom.next(e.anchorNode);return n==r?$tom.first(n,"%control"):null},selectControl:function(e){var t=this.createRange();t.selectNode(e);var n=this.getSel();n.removeAllRanges(),n.addRange(t)}},Trex.I.Selection.Gecko={},Trex.I.Selection.Webkit={getControl:function(){var e=this.getSel();if(e.isCollapsed)return _NULL;if($tom.isElement(e.anchorNode)){var t=e.anchorNode.childNodes[e.anchorOffset];return $tom.kindOf(t,"%control")?t:_NULL}var n=$tom.previous(e.focusNode),r=$tom.next(e.anchorNode);return n==r?$tom.first(n,"%control"):_NULL},selectControl:function(e){var t=this.createRange();t.selectNode(e);var n=this.getSel();n.removeAllRanges(),n.addRange(t)}},Trex.I.Selection.Presto={},Trex.Canvas.Selection=Trex.Class.create({$mixins:[Trex.I.Selection.Standard,$tx.msie_nonstd?Trex.I.Selection.Trident:{},$tx.msie_std?Trex.I.Selection.TridentStandard:{},$tx.gecko?Trex.I.Selection.Gecko:{},$tx.webkit?Trex.I.Selection.Webkit:{},$tx.presto?Trex.I.Selection.Presto:{}],initialize:function(e){this.processor=e,this.win=e.win,this.doc=e.doc}});