Set html = CreateObject("Msxml2.ServerXMLHTTP.3.0")
html.open "GET", "http://www.esugo.cn/index.php/Api/AutoPush/push.html",false
html.send