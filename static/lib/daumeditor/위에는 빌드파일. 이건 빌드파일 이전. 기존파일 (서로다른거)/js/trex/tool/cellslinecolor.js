TrexConfig.addTool("cellslinecolor",{defaultcolor:"#7c84ef",wysiwygonly:_TRUE,sync:_FALSE,status:_TRUE,useFavorite:_TRUE,thumbs:Trex.__CONFIG_COMMON.thumbs,needRevert:_TRUE}),Trex.Tool.Cellslinecolor=Trex.Class.create({$const:{__Identity:"cellslinecolor"},$extend:Trex.Tool,oninitialized:function(){var t=this.canvas,e=this;this.button=new Trex.Button(this.buttonCfg);var o=function(e){n(e),t.query(function(t){t.table&&t.table.setBorderColor(e)})},n=function(t){if(t)try{$tx.setStyle(e.button.elButton,{backgroundColor:t})}catch(t){console.log(t)}};n(this.config.defaultcolor),this.weave.bind(this)(e.button,new Trex.Menu.ColorPallete(this.menuCfg),o)}});