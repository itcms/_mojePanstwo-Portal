function tb_getCookie(t){for(var e=t+"=",o=document.cookie.split(";"),r=0;r<o.length;r++){for(var n=o[r];" "==n.charAt(0);)n=n.substring(1);if(0==n.indexOf(e))return n.substring(e.length,n.length)}return""}function tb_close(){return document.body.removeChild(document.getElementById("tb_blackout")),!0}if(!tb_getCookie("tbalert")){var htmlStr='<div id="tb_blackout" style="height:100%;width:100%;position:fixed;top:0;left:0;background-color:#000000;z-index:100000;"><div style="border-radius: 10px;width:600px;height:300px;position:fixed;left:50%;top:50%;margin-left:-300px;margin-top:-150px;background-color:#FFFFFF;text-align:center;margin-bottom:0;margin-right:0;padding:30px;"><h1 style="font-size:32px;margin:0;padding:0;">Ta strona została zablokowana na mocy ustawy antyterrorystycznej!</h1><div style="padding:30px 60px 10px; font-size: 14px; line-height: 21px;"><p>Tak naprawdę, to tylko akcja ostrzegająca o niebezpiecznych skutkach przyjętej właśnie przez Sejm ustawy.</p><p><a target="_blank" onclick="tb_close(); return true;" href="https://mojepanstwo.pl">Przeczytaj więcej o akcji &raquo;</a></p></div><p><a style="-moz-user-select: none; background-image: none; border: 1px solid transparent; border-radius: 3px; cursor: pointer; display: inline-block; font-size: 13px; font-weight: normal; line-height: 1.846; margin-bottom: 0; padding: 6px 16px; text-align: center; vertical-align: middle; white-space: nowrap; background-color: #2196f3; border-color: transparent; color: #fff;" href="#" onclick="tb_close(); return false;">Powrót do witryny</a></p></div></div>',frag=document.createDocumentFragment(),temp=document.createElement("div");for(temp.innerHTML=htmlStr;temp.firstChild;)frag.appendChild(temp.firstChild);document.body.insertBefore(frag,document.body.childNodes[0]);var d=new Date;d.setTime(d.getTime()+864e5),document.cookie="tbalert=1;expires="+d.toUTCString()+";path=/"}