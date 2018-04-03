if(wx.miniProgram){
    wx.miniProgram.getEnv(function(res) {
      if(res.miniprogram){
          $.get("plugin.php?id=tom_tongcheng:ajax&act=miniprogram&ok=1");
      }
    })
}else{
    $.get("plugin.php?id=tom_tongcheng:ajax&act=miniprogram&ok=0");
}

function miniprogramReady(){
  if(window.__wxjs_environment === 'miniprogram'){
      console.log('check miniProgram ok 1');
      $.get("plugin.php?id=tom_tongcheng:ajax&act=miniprogram&ok=1");
  }else{
      console.log('check miniProgram ok 0');
      $.get("plugin.php?id=tom_tongcheng:ajax&act=miniprogram&ok=0");
  }
}
if (!window.WeixinJSBridge || !WeixinJSBridge.invoke) {
  document.addEventListener('WeixinJSBridgeReady', miniprogramReady, false)
}else{
  miniprogramReady()
}

function jumpMiniprogram(link){
    var newviewurl = encodeURIComponent(link);
    wx.miniProgram.navigateTo({
      url: 'view?viewurl=' + newviewurl
    })
}