Trex.I.MenuFontTool=Trex.Mixin.create({oninitialized:function(e){var t=this;t.beforeOnInitialized(e);var r=t.menuInitHandler&&t.menuInitHandler.bind(t);t.weave(t.createButton(),t.createMenu(),t.handler,r),e.sync&&t.startSyncButtonWithStyle()},rangeExecutor:function(e,t,r){this.wrapTextAsStyledSpan(e,t,r)},startSyncButtonWithStyle:function(){var e=this;e.canvas.observeJob(Trex.Ev.__CANVAS_PANEL_QUERY_STATUS,function(t){e.syncButton(e.queryCurrentStyle(t))})},queryCurrentStyle:function(e){var t=this,r=t.queryCommandValue();if(t.reliableQueriedValue(r)&&r&&t.getTextByValue(r))return t.getTextByValue(r);var n=t.canvas.query(function(r){var n;return n=$tx.msie&&e.isCollapsed()?r.getNode():t.findQueryingNode(e),t.queryElementCurrentStyle(n)});return n&&t.getTextByValue(n)?t.getTextByValue(n):r||n||t.getTextByValue(t.getDefaultProperty())},queryCommandValue:function(){var e=this;return e.canvas.query(function(t){return t.queryCommandValue(e.getQueryCommandName())})},reliableQueriedValue:function(e){return _TRUE},queryElementCurrentStyle:function(e){for(var t=this.getCssPropertyName(),r=e,n=10,u=0;u<n&&$tom.kindOf(r,"%inline");u++){var a=r.style[t];if(a)return a;if($tom.kindOf(r,"font")&&$tom.getAttribute(this.getFontTagAttribute()))return $tom.getAttribute(this.getFontTagAttribute());r=r.parentNode}var i=this.canvas.getProcessor();return e?i.queryStyle(e,t):_NULL},computeNewStyle:function(e){var t={};return t[this.getCssPropertyName()]=e,t},cachedProperty:_FALSE,syncButton:function(e){var t=this;t.button.setText(e),t.cachedProperty!=e&&(t.button.setText(e),t.cachedProperty=e)}});