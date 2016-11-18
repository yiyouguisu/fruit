Dim html
Set html = CreateObject("Msxml2.ServerXMLHTTP.3.0")
html.open "GET", "http://www.esugo.cn/index.php/Api/AutoBookorderNotice/bookordernotice.html",false
html.send
