Trex.module("Add layer to display notice message on editor area before editing",function(e,i,t,o,n){n.initializedMessage&&o.observeJob(Trex.Ev.__IFRAME_LOAD_COMPLETE,function(){var e=n.initializedId,t=tx.div({id:"tx-canvas-notice"+e,className:"tx-canvas-notice"},n.initializedMessage),a=$tx("tx_loading"+e),s=a.parentNode;s.insertBefore(t,a);var r=!1,c=function(){!r&&$tx("tx-canvas-notice"+e)&&(r=!0,s.removeChild(t),o.focus())};setTimeout(function(){$tx.observe(o.getPanel("html").getWindow(),"focus",c)},30),$tx.observe(t,"click",c),o.observeJob(Trex.Ev.__CANVAS_DATA_INITIALIZE,c),i.observeJob(Trex.Ev.__TOOL_CLICK,c)})});