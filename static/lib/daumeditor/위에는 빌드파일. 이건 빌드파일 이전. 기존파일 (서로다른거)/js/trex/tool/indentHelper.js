!function(){Trex.Tool.Indent.Helper={findBlocksToIndentFromRange:function(t,e,n){var o=n.getCaret(_TRUE),r=n.getCaret(_FALSE);if(e.isCollapsed()){t.getStartNode(),t.getStartOffset();var i=this.findBlockToIndent(o,e),l="P"==i.tagName&&i.firstChild==o&&i.lastChild==r;return l&&e.stuffNode(i),n.restoreInternal(),[i]}var d=new goog.dom.TextRangeIterator(o,0,r,0);return this.findBlocksToIndentFromIterator(e,d)},findBlocksToIndentFromIterator:function(t,e){var n=this,o=n.collectAllNodes(e),r=n.selectLeafNodes(o),i=n.filterUnableToIndent(r),l=i.map(function(e){return n.findBlockToIndent(e,t)});return l=l.compact().uniq()},collectAllNodes:function(t){var e=[];return goog.iter.forEach(t,function(t){e.contains(t)||e.push(t)}),e},selectLeafNodes:function(t){var e=[];return t.each(function(t){0==t.childNodes.length&&e.push(t)}),e},filterUnableToIndent:function(t){var e=[];return t.each(function(t){$tom.kindOf(t,"ul,ol,dl")?$tom.removeListIfEmpty(t):$tom.kindOf(t.parentNode,"table")&&$tom.isText(t)||$tom.kindOf(t.parentNode,"thead,tbody,tfooter")&&!$tom.kindOf(t,"tr")||$tom.kindOf(t.parentNode,"tr")&&!$tom.kindOf(t,"th,td")||$tom.kindOf(t.parentNode,"ul,ol,dl")&&!$tom.kindOf(t,"li,dd,dt")||e.push(t)}),e},findBlockToIndent:function(t){var e=this.findOrCreateBlockForNode(t);return this.findIndentableHigherBlock(e)},findOrCreateBlockForNode:function(t){if($tom.isText(t)||$tom.kindOf(t,"%inline,img")){var e=$tom.ancestor(t,"p,li,dd,dt,h1,h2,h3,h4,h5,h6,div");return e&&0==$tom.children(e,"%block").length?e:(e=$tom.ancestor(t,"%paragraph,pre,noscript,form,hr,address,fieldset,blockquote"),$tom.wrapInlinesWithP(t,e))}return t},findIndentableHigherBlock:function(t){for(var e=_NULL,n=t;n&&"BODY"!=n.tagName;){if(!e&&$tom.kindOf(n,"p,div,h1,h2,h3,h4,h5,h6"))e=n;else{if($tom.kindOf(n,"li,dd,dt"))return n;if(e&&$tom.kindOf(n,"td,th"))return e}n=n.parentNode}return e},findAncestorTableCell:function(t){return $tom.ancestor(t,"td,th")},findNextCell:function(t){var e=this.findCurrentCell(t),n=$tom.next(e,"td,th");if(!n){var o=$tom.next($tom.parent(e),"tr");o&&(n=$tom.first(o,"td,th"))}return n},findPreviousCell:function(t){var e=this.findCurrentCell(t),n=$tom.previous(e,"td,th");if(!n){var o=$tom.previous($tom.parent(e),"tr");o&&(n=$tom.last(o,"td,th"))}return n},findCurrentCell:function(t){return $tom.kindOf(t,"td,th")?t:this.findAncestorTableCell(t)},isCaretOnStartOf:function(t,e){for(var n=e.getStartNode(),o=e.getStartOffset();$tom.isElement(n)&&n.childNodes.length>0;)n=n.childNodes[o],o=0;if(!n)return _TRUE;var r=new goog.dom.TextRangeIterator(t,0,n,o),i=_FALSE;return goog.iter.forEach(r,function(t){if(3!=t.nodeType||$tom.kindOf(t.parentNode,"script,style"))$tom.isElement(t)&&$tom.kindOf(t,"img,embed,iframe")&&(i=_TRUE);else{var e=t===n?t.nodeValue.substring(0,o):t.nodeValue;e=e.replace(Trex.__WORD_JOINER_REGEXP,""),i=$tom.removeMeaninglessSpace(e).length>0}if(i)throw goog.iter.StopIteration}),!i}};var t=Trex.Tool.Indent.Helper,e={};Trex.Tool.Indent.RangeIndenter=Trex.Class.create({initialize:function(t){this.handler=t},indent:function(n){var o=this;n.executeUsingCaret(function(r,i){var l=t.findBlocksToIndentFromRange(r,n,i);l.each(function(t){try{o.handler.handle(t,n,r)}catch(t){if(t!=e)throw t;i.dispose()}})})}}),Trex.Tool.Indent.TableCellIndenter=Trex.Class.create({initialize:function(t){this.handler=t},indent:function(e){var n=this,o=e.table?e.table.getTdArr():[];o.each(function(o){var r=new goog.dom.TagIterator(o),i=t.findBlocksToIndentFromIterator(e,r);i.each(function(t){n.handler.handle(t,e,_NULL)})})}}),Trex.Tool.Indent.Judge={ChildOfFirstTableCell:function(e){var n=t.findAncestorTableCell(e);return n&&!t.findPreviousCell(n)},ChildOfLastTableCell:function(e){var n=t.findAncestorTableCell(e);return n&&!t.findNextCell(n)},ChildOfTableCell:function(e){return t.findAncestorTableCell(e)},ListItem:function(t){return $tom.kindOf(t,"li")&&$tom.kindOf(t.parentNode,"ol,ul")},OneDepthList:function(t){if($tom.kindOf(t,"li")){var e=new Trex.Tool.StyledList.ListBuilder;if(1==e.countDepthOfList(t))return _TRUE}return _FALSE},IndentedBlockNode:function(t){return $tom.kindOf(t,"%block")&&t.style&&""!=t.style.marginLeft},BlockNode:function(t){return $tom.kindOf(t,"%block")},HeadOfParagraph:function(e,n,o){return t.isCaretOnStartOf(e,o)},And:function(t,e){return function(){return t.apply(this,arguments)&&e.apply(this,arguments)}},AlwaysTrue:function(){return _TRUE}},Trex.Tool.Indent.Operation={GoToBelowTable:function(t,n){var o=$tom.ancestor(t,"table");throw n.bookmarkToNext(o),e},GoToNextCell:function(n,o){var r=t.findNextCell(n);if(r)throw o.selectFirstText(r),e},IndentListItem:function(t){var e=$tom.ancestor(t,"ul,ol,dl");if(e){var n=$tom.previous(t),o=$tom.next(t);if($tom.kindOf(n,"ul,ol,dl"))$tom.append(n,t);else{var r=$tom.clone(e);$tom.applyStyles(r,{marginLeft:_NULL,paddingLeft:_NULL}),$tom.wrap(r,t)}$tom.kindOf(o,"ul,ol,dl")&&($tom.moveChild(o,t.parentNode),$tom.remove(o))}},getChildrenAsElement:function(t){for(var e=[],n=t.childNodes,o=0,r=n.length;o<r;o++){var i=n[o];if($tom.isText(i)){var l=$tom.wrapInlinesWithP(i,t);e.push(l)}else $tom.isElement(i)&&e.push(i)}return e},IndentBlockNode:function(t){$tom.applyStyles(t,{marginLeft:"+2em"})},GoToAboveTable:function(t,n){var o=$tom.ancestor(t,"table");throw n.bookmarkToPrevious(o),e},GoToPreviousCell:function(n,o){var r=t.findPreviousCell(n);if(r)throw o.moveCaretTo(r,_TRUE),e},OutdentListItem:function(t,e){var n=$tom.ancestor(t,"ul,ol,dl");if(n){var o=n.parentNode;$tom.kindOf(o,"li")&&($tom.unwrap(o),o=n.parentNode);var r,i=$tom.kindOf(o,"ul,ol,dl")?o:_NULL;if(i)r=$tom.divideNode(n,$tom.indexOf(t)),$tom.insertAt(t,r);else{r=$tom.divideNode(n,$tom.indexOf(t));var l=$tom.getStyleText(t),d=e.newNode("p");$tom.setStyleText(d,l),$tom.replace(t,d),$tom.insertAt(d,r)}$tom.removeListIfEmpty(n),$tom.removeListIfEmpty(r)}},OutdentBlockNode:function(t){$tom.applyStyles(t,{marginLeft:"-2em"})},Propagate:function(){throw $propagate}}}();