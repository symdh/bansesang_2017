Trex.Table.Delete=Trex.Class.create({initialize:function(e){var t;t=e.getCanvas(),this.wysiwygPanel=t.getPanel(Trex.Canvas.__WYSIWYG_MODE)},deleteRow:function(e){var t;t=e.getSelected(),t.isValid()&&(this.deleteRowOneByOne(e),e.reset(),this.deleteEmptyTableByTableSelector(e))},deleteRowOneByOne:function(e){var t,r,o,n;for(o=e.getSelected(),t=o.top,r=o.bottom-o.top+1;0<r;)e.reloadIndexer(),n=e.getIndexer(),this.deleteRowByIndex(n,t),r-=1;0===t&&this.drawTopBorder(e)},drawTopBorder:function(e){var t,r,o,n,l;for(e.reloadIndexer(),t=e.getIndexer(),r=t.getTdArrHasTop(0),o=r.length,n=0;n<o;n+=1)l=r[n],""===l.style.borderTop&&""!==l.style.borderBottom&&(l.style.borderTop=l.style.borderBottom)},deleteRowByIndex:function(e,t){var r,o,n,l;r=this.getTdArrByRowIndex(e,t),o=this.getTdArrByHasTop(e,t),n=r.length,0<n&&(l=$tom.parent(r[0]),this.deleteTdInDeleteRow(r,o,l,e),$tom.remove(l))},getTdArrByRowIndex:function(e,t){return e.getTdArr(new Trex.TableUtil.Boundary({top:t,right:e.getColSize()-1,bottom:t,left:0}))},getTdArrByHasTop:function(e,t){return e.getTdArrHasTop(t)},deleteTdInDeleteRow:function(e,t,r,o){var n,l,d;for(n=e.length,l=0;l<n;l+=1)d=e[l],1<d.rowSpan?(d.rowSpan-=1,this.reduceHeightAsRow(d,r),t.contains(d)&&this.shiftRowOfTd(d,o)):$tom.remove(d)},reduceHeightAsRow:function(e,t){var r,o;e.style.height&&(r=parseInt(e.style.height,10),o=r-t.offsetHeight,0<o&&$tom.setStyles(e,{height:o+"px"},_TRUE))},shiftRowOfTd:function(e,t){var r,o,n;r=$tom.parent(e),o=$tom.next(r,"tr"),n=this.getTdForInsert(e,o,t),n?$tom.insertAt(e,n):$tom.append(o,e)},getTdForInsert:function(e,t,r){var o,n,l,d,i,a,s;for(o=r.getBoundary(e),n=o.left,l=t.cells,d=l.length,i=0;i<d;i+=1)if(a=l[i],s=r.getBoundary(a),n<=s.left)return a;return _NULL},deleteEmptyTableByTableSelector:function(e){var t,r;t=e.getIndexer(),r=t.table,0===r.rows.length&&$tom.remove(r)},deleteCol:function(e){var t;t=e.getSelected(),t.isValid()&&(this.deleteColOneByOne(e),e.reset(),this.deleteEmptyTableByTableSelector(e))},deleteColOneByOne:function(e){var t,r,o,n;for(o=e.getSelected(),t=o.left,r=o.right-o.left+1;0<r;)e.reloadIndexer(),n=e.getIndexer(),this.deleteColByIndex(n,t),r-=1;0===t&&this.drawLeftBorder(e)},drawLeftBorder:function(e){var t,r,o,n,l;for(e.reloadIndexer(),t=e.getIndexer(),r=t.getTdArrHasLeft(0),o=r.length,n=0;n<o;n+=1)l=r[n],""===l.style.borderLeft&&""!==l.style.borderRight&&(l.style.borderLeft=l.style.borderRight)},deleteColByIndex:function(e,t){var r,o,n,l;for(r=this.getTdArrByColIndex(e,t),o=r.length,n=0;n<o;n+=1)l=r[n],1<l.colSpan?l.colSpan-=1:$tom.remove(l)},getTdArrByColIndex:function(e,t){return e.getTdArr(new Trex.TableUtil.Boundary({top:0,right:t,bottom:e.getRowSize()-1,left:t}))}});