var TrexMessage=function(){function e(e){return e.indexOf("#iconpath")>-1?TrexConfig.getIconPath(e):e}function n(e){return e.indexOf("#decopath")>-1?TrexConfig.getDecoPath(e):e}var r={};return{getMsg:function(t){var o=r[t]||"";return e(n(o))},addMsg:function(e){$tx.deepcopy(r,e)},printAll:function(){for(var e in r)r.hasOwnProperty(e)&&console.log(e+"="+r[e])}}}();_WIN.TXMSG=TrexMessage.getMsg,_WIN.TrexMessage=TrexMessage;