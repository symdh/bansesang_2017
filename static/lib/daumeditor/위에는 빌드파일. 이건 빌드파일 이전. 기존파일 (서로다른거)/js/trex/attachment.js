TrexMessage.addMsg({"@attacher.only.wysiwyg.alert":"에디터 상태에서만 본문에 삽입할 수 있습니다.\n에디터모드에서 첨부박스의 썸네일을 클릭해서 삽입할 수 있습니다."}),Trex.Attachment=Trex.Class.draft({$extend:Trex.Entry,isChecked:_FALSE,focused:_FALSE,attrs:{align:"left"},initialize:function(t,e){this.actor=t,this.canvas=t.canvas,this.entryBox=t.entryBox,this.type=this.constructor.__Identity,this.setProperties(e),this.oninitialized&&this.oninitialized(t,e)},setFocused:function(t){this.focused!==t&&(this.focused=t)},setExistStage:function(t){this.existStage=t,this.entryBox.changeState&&this.entryBox.changeState(this)},remove:function(){var t=this.canvas.getContent();this.canvas.isWYSIWYG()?t.search(this.regHtml)>-1&&(t=t.replace(this.regHtml,""),this.canvas.setContent(t)):t.search(this.regText)>-1&&(t=t.replace(this.regText,""),this.canvas.setContent(t))},register:function(){if(!Editor.getSidebar().addOnlyBox){var t=this.actor;if(!t.boxonly)if(this.canvas.isWYSIWYG()){var e=this.pastescope,i=this.dispHtml,s="img",r=this.matchRegexStartTag,a=i.match(r);if(a&&a[1]&&(s=a[1]),this.objectStyle){var n=new RegExp("<"+s+" ","i");i=i.replace(n,"<"+s+' style="'+Trex.Util.toStyleString(this.objectStyle)+'" ')}this.objectAttr&&(i=i.replace(n,"<"+s+" "+Trex.Util.toAttrString(this.objectAttr)+" "));var h=this.paragraphStyle||{};$tx.webkit&&this.canvas.getPanel("html").el.focus(),this.canvas.execute(function(t){t.moveCaretWith(e),t.pasteContent(i,_TRUE,{style:h})})}else this.actor.wysiwygonly?alert(TXMSG("@attacher.only.wysiwyg.alert")):this.canvas.getProcessor().insertTag("",this.dispText)}},replace:function(t){var e=this.canvas,i=e.getContent(),s=this.actor;s.boxonly||(e.isWYSIWYG()?i.search(t.regHtml)>-1?(i=i.replace(t.regHtml,this.dispHtml),e.setContent(i)):e.pasteContent(this.dispHtml,_TRUE):(i.search(t.regText)>-1&&(i=i.replace(t.regText,""),e.setContent(i)),alert(TXMSG("@attacher.only.wysiwyg.alert"))))},setProperties:function(t){var e=t;this.data=e,this.key=this.actor.getKey(e)||"K"+Trex.Util.generateKey(),this.field=this.getFieldAttr(e),this.boxAttr=this.getBoxAttr(e),this.objectAttr=this.getObjectAttr.bind(this)(e),this.objectStyle=this.getObjectStyle.bind(this)(e),this.paragraphStyle=this.getParaStyle.bind(this)(e),this.saveHtml=this.getSaveHtml.bind(this)(e),this.dispHtml=this.getDispHtml.bind(this)(e),this.dispText=this.getDispText.bind(this)(e),this.regLoad=this.getRegLoad.bind(this)(e),this.regHtml=this.getRegHtml.bind(this)(e),this.regText=this.getRegText.bind(this)(e)},refreshProperties:function(){this.setProperties(this.data)},getObjectAttr:function(){return this.actor.config.objattr},getObjectStyle:function(){var t={};return this.actor.config.objstyle&&(t=Object.extend(t,this.actor.config.objstyle)),t},getParaStyle:function(t){var e=Object.extend({},this.actor.config.parastyle||this.actor.config.defaultstyle);return e},updateEntryElement:function(t){if(t){var e=_DOC.createElement("div");e.innerHTML=this.dispHtml,t.innerHTML=$tom.first(e).innerHTML}}});