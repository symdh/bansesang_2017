Trex.I.Processor.TridentStandard={stuffNode:function(e){return $tom.stuff(e,this.newNode("br"))},controlEnterByParagraph:function(e){throw $propagate},controlEnterByLinebreak:function(e){var t=this,a=this.getRange(!1),n=a.endContainer.parentNode;if(n&&("P"==n.tagName||"DIV"==n.tagName||"BODY"==n.tagName||"BLOCKQUOTE"==n.tagName)&&("BLOCKQUOTE"==n.tagName||$tx.hasClassName(n,"txc-textbox")||$tx.hasClassName(n,"txc-moreless"))){$tx.stop(e);var r=t.win.br();a.insertNode(r),a.selectNode(r),a.collapse(!1),r=t.win.br(),a.insertNode(r),a.selectNode(r),a.collapse(!1);var a=t.getRange(!1);a.selectNodeContents(r.nextSibling);var s=t.getSel();s.removeAllRanges(),s.addRange(a),s.collapseToStart()}},queryCommandState:function(e){var t=this.getRange();if(this.hasControl()&&t.collapsed===_FALSE&&t.endOffset-t.startOffset===1&&("bold"===e||"underline"===e||"italic"===e||"strikethrough"===e)){var a=this.getControl();if("IMG"===a.tagName||"BUTTON"===a.tagName)return _FALSE}try{return this.doc.queryCommandState(e)}catch(e){return _FALSE}},addDummyNbsp:function(e){var t;1===e.length&&(t=e[0],"span"===t.tagName.toLowerCase()&&1===t.childNodes.length&&3===t.firstChild.nodeType&&""===t.firstChild.data&&(t.firstChild.data=" "))}},Object.extend(Trex.I.Processor.TridentStandard,{restoreRange:function(){if(!this.isRangeInsideWysiwyg&&this.lastRange){var e=this.getSel();e.removeAllRanges(),e.addRange(this.lastRange)}}});