ValidateSignature=function(a){var b=true;if(a==null||'undefined'==a||""==a){for(var i=0;i<signObjects.length;i++){var c=signObjects[i],isitOk=eval("obj"+c).IsValid();if(!isitOk){b=false;if(!isMobileIE){document.getElementById(c).style.borderColor="red"}}}}else{b=eval("obj"+a).IsValid();if(!isMobileIE&&b==false){document.getElementById(a).style.borderColor="red"}}return b};ClearSignature=function(a){if(a==null||'undefined'==a||""==a){for(var i=0;i<signObjects.length;i++){var a=signObjects[i];eval("obj"+a).ResetClick()}}else{eval("obj"+a).ResetClick()}};ResizeSignature=function(a,w,h){eval("obj"+a).ResizeSignature(w,h)};SignatureColor=function(a,b){eval("obj"+a).SignatureColor(b)};SignatureBackColor=function(a,b){eval("obj"+a).SignatureBackColor(b)};SignaturePen=function(a,b){eval("obj"+a).SignaturePen(b)};SignatureEnabled=function(a,b){eval("obj"+a).SignatureEnabled(b)};SignatureStatusBar=function(a,b){eval("obj"+a).SignatureStatusBar(b)};SignatureTotalPoints=function(a){return eval("obj"+a).CurrentTotalpoints()};UndoSignature=function(a){eval("obj"+a).UndoSignature()};LoadSignature=function(a,b){eval("obj"+a).LoadSignature(b)};TextSignature=function(a,b,c,x,y){eval("obj"+a).TextSignature(b,c,x,y)};var msie=window.navigator.userAgent.toUpperCase().indexOf("MSIE ");var isIE=false;var isIENine=false;var isIETen=false;var isMobileIE=false;var isOperaMini=false;var isIETablet=false;var winTabletPointerEvt=false;var iever=getInternetExplorerVersion();var canvasSupport=false;if(window.navigator.userAgent.toUpperCase().indexOf("OPERA MINI")>0){isOperaMini=true}if(window.navigator.userAgent.toUpperCase().indexOf("OPERA MOBI")>0){isOperaMini=true}function supports_canvas(){try{document.createElement("canvas").getContext("2d");return true}catch(e){return false}};function getInternetExplorerVersion(){var a=-1;if(window.navigator.appName=='Microsoft Internet Explorer'){var b=window.navigator.userAgent;var c=new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");if(c.exec(b)!=null)a=parseFloat(RegExp.$1)}return a};if(msie>0){isIE=true;if(supports_canvas()){isIE=false;if(iever>=9.0){isIENine=true}}if(window.navigator.userAgent.toUpperCase().indexOf("IEMOBILE ")>0){isMobileIE=true}}isIETablet=(new RegExp("Tablet PC","i")).test(window.navigator.userAgent);winTabletPointerEvt=window.navigator.msPointerEnabled;if(!isIETablet){if(window.navigator.userAgent.toUpperCase().indexOf("WINDOWS PHONE ")>0){isIETablet=true}if(window.navigator.msMaxTouchPoints){isIETablet=true}}function SuperSignature(){this.SignObject="";this.PenSize=3;this.PenColor="#D24747";this.PenCursor='';this.ClearImage='';this.BorderWidth="2px";this.BorderStyle="dashed";this.BorderColor="#DCDCDC";this.BackColor="#ffffff";this.BackImageUrl='';this.SignzIndex="99";this.SignWidth=450;this.SignHeight=250;this.CssClass="";this.ApplyStyle=true;this.SignToolbarBgColor="transparent";this.RequiredPoints=50;this.ErrorMessage="Please continue your signature.";this.StartMessage="Please sign";this.SuccessMessage="Signature OK.";this.ImageScaleFactor=0.50;this.TransparentSign=true;this.Enabled=true;this.Visible=true;this.Edition="Trial";this.IsCompatible=false;this.InternalError="";this.LicenseKey="";this.IeModalFix=false;this.SuccessFunction="";this.ErrorFunction="";this.ResetFunction="";this.SmoothSign=true;for(var n in arguments[0]){this[n]=arguments[0][n]}var r=0;var s=false;var u=null;var z=null;var A=null;var B=null;var C=null;var D=null;var E=null;var F=null;var G=this.Enabled;var H=false;var I=false;var J=[],fData=[],kData=[],bData=[],pData=[];var K="1",dcMode=false,currPenSize=this.PenSize,currPenColor=this.PenColor,currBackColor=this.BackColor,currBorderColor=this.BorderColor;var L=this.SignObject;var M=this.SignWidth;var N=this.SignHeight;var O=this.StartMessage;var P=this.ErrorMessage;var Q=this.SuccessMessage;var R=this.SuccessFunction;var S=this.ErrorFunction;var T=this.ResetFunction;var U=this.BackImageUrl;var V=false;var W=this.ImageScaleFactor;var X=this.TransparentSign;var a="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";var Y=this.RequiredPoints;var Z=0;var ba="";var bb=0;var bc=0;var bd;var be=0;var bf=0;var bg=this.IeModalFix;var bh=null;var bi=0;var bj=0;var bk=this.SmoothSign;if(isMobileIE){bd=new jsGraphics(L+"_Container");if(bd!=null&&bd!='undefined'){try{bd.clear();bd.paint()}catch(ee){alert("Graphics object error "+ee.description)}}else{alert("Graphics object error")}};this.IsValid=function(){return V};this.CurrentTotalpoints=function(){return Z};this.ReturnFalse=function(e){if(e.preventManipulation){e.preventManipulation()}if(e.preventDefault){e.preventDefault()}else{e.returnValue=false}};function MyAttachEvent(a,b,c){if(!a.myEvents)a.myEvents={};if(!a.myEvents[b])a.myEvents[b]=[];var d=a.myEvents[b];d[d.length]=c};function MyFireEvent(a,b){if(!a||!a.myEvents||!a.myEvents[b])return;var c=a.myEvents[b];for(var i=0,len=c.length;i<len;i++)c[i]()};this.XBrowserAddHandler=function(a,b,c){if(a.addEventListener)a.addEventListener(b,c,false);else if(a.attachEvent)a.attachEvent("on"+b,c);else{try{MyAttachEvent(a,b,c);a['on'+b]=function(){MyFireEvent(a,b)}}catch(ex){alert("Event attaching exception, "+ex.description)}}};this.DisableSelection=function(){if(!isIE){if(typeof z.style.MozUserSelect!="undefined"){z.style.MozUserSelect="none"}else{z.style.cursor="default"}}};this.ResizeSignature=function(w,h){z.style.width=w+"px";z.style.height=h+"px";B.style.width=w+"px";if(!isIE){var a=document.getElementById(this.SignObject);a.width=parseInt(w,0);a.height=parseInt(h,0);a.style.width=w+"px";a.style.height=h+"px"}else{u.style.width=w+"px";u.style.height=h+"px"}this.ResetClick();this.SignWidth=w;this.SignHeight=h;M=w;N=h};this.SignatureColor=function(a){this.PenColor=a;currPenColor=a};this.SignatureBackColor=function(a){this.BackColor=a;currBackColor=a;if(isIE){u.style.backgroundColor=a}else{u.fillStyle=a;u.fillRect(0,0,M,N)}};this.SignaturePen=function(a){this.PenSize=a;currPenSize=a};this.SignatureEnabled=function(a){this.Enabled=a;G=a};this.SignatureStatusBar=function(a){if(a){jQuery("#"+B.id).show("slow")}else{jQuery("#"+B.id).hide("slow")}};this.UndoSignature=function(){if(J.length<=2){this.ResetClick();return}J.pop();bData.pop();kData.pop();pData.pop();SetSignData();var a=base64Decode("'"+C.value+"'");this.LoadSignature(a,1)};this.LoadSignature=function(a,b){this.ResetClick();if(b==null||b=='undefined'){b='1.0'}b=parseFloat(b);var c=findPos(z);var d=c[0];var e=c[1];var f=RTrim(LTrim(a)).split(";");J[0]="";for(var i=0;i<f.length-1;i++){J[i]=f[i]+";"}for(var i=0;i<f.length-1;i++){var h=RTrim(LTrim(f[i])).split(' ');pData[i]=h[0];bData[i]=new BezierCurves(GetPointArray(h),30)}var k=f[0].split(",");currBackColor=k[1];M=k[3];N=k[4];X=k[5];this.SignatureBackColor(currBackColor);kData[0]=0;for(var i=1,len=f.length;i<len-1;i++){var l=RTrim(LTrim(f[i])).split(" ");kData[i]=parseInt(l.length,0)-1;kData[0]=parseInt(kData[0],0)+parseInt(l.length,0);for(var j=0,lent=l.length;j<lent;j++){var m=l[j].split(",");var n=m[0];var o=m[1];if(j==0){this.SignaturePen(n);this.SignatureColor(o);u.strokeStyle=o;u.lineWidth=n}else if(j==1){n=Math.abs(parseInt(n,0)*b);o=Math.abs(parseInt(o,0)*b);if(isIE){if(isMobileIE){bb=n;bc=o}else{var w='<SuperSignature:stroke weight="'+currPenSize+'" color="'+currPenColor+'" />';var t='"m'+n+","+o+" l"+n+","+o;var v='<SuperSignature:shape id="'+L+"_l_"+(i-1)+'" style="position: absolute; left:0px; top:0px; width:'+M+"px; height: "+N+'px;" coordsize="'+M+","+N+'"><SuperSignature:path v='+t+' e" /><SuperSignature:fill on="false" />'+w+"</SuperSignature:shape>";u.pathCoordString=t;u.insertAdjacentHTML("beforeEnd",v)}}else{u.beginPath();u.lineJoin="round";u.lineCap="round";u.moveTo(n,o)}if(l.length==2){eval("obj"+L).DrawDot(n,o)}}else{n=Math.abs(parseInt(n,0)*b);o=Math.abs(parseInt(o,0)*b);if(!isIE&&!bk){u.strokeStyle=currPenColor;u.lineWidth=currPenSize;u.lineTo(n,o);u.stroke();u.moveTo(n,o)}else{u.pathCoordString+=" "+n+","+o;var g=document.getElementById(L+"_l_"+(i-1));if(g){var p=g.firstChild;if(p){try{p.setAttribute("v",u.pathCoordString+" e")}catch(je){var q=je.Description}}}}}if(!isIE){u.closePath();u.restore()}else{u.innerHTML=u.innerHTML}}r++}if(!isIE&&bk){ProcessCanvasArea(b)}SetSignData()};this.TextSignature=function(a,b,x,y){if(canvasSupport){u.font=a;u.fillText(b,x,y)}};this.CheckCompatibility=function(){if(isIE){this.IsCompatible=true;if(!isMobileIE){if(!document.namespaces["SuperSignature"]){document.namespaces.add("SuperSignature","urn:schemas-microsoft-com:vml","#default#VML")}}}else{var a=false;try{a=!!document.createElement("canvas").getContext("2d")}catch(e){a=!!document.createElement("canvas").getContext}if(a){this.IsCompatible=true}else{document.write("Your browser does not support our signature control.")}}};function ShowMessage(a){ShowDebug(a)};function LTrim(a){var b=/\s*((\S+\s*)*)/;return a.replace(b,"$1")};function RTrim(a){var b=/((\s*\S+)*)\s*/;return a.replace(b,"$1")};function ShowDebug(a){if(F!=null&&F!='undefined'){try{F.innerHTML=a+"...<br/>"+F.innerHTML}catch(exp1){alert(exp1.description)}}};function base64Encode(b){var h="",i,d,e,k,l,j,f,g=0;b=c(b);while(g<b.length){i=b.charCodeAt(g++);d=b.charCodeAt(g++);e=b.charCodeAt(g++);k=i>>2;l=(i&3)<<4|d>>4;j=(d&15)<<2|e>>6;f=e&63;if(isNaN(d)){j=f=64}else if(isNaN(e)){f=64}h=h+a.charAt(k)+a.charAt(l)+a.charAt(j)+a.charAt(f)}return h};function b(c){var d="",a=0,b=c1=c2=0;while(a<c.length){b=c.charCodeAt(a);if(b<128){d+=String.fromCharCode(b);a++}else if(b>191&&b<224){c2=c.charCodeAt(a+1);d+=String.fromCharCode((b&31)<<6|c2&63);a+=2}else{c2=c.charCodeAt(a+1);c3=c.charCodeAt(a+2);d+=String.fromCharCode((b&15)<<12|(c2&63)<<6|c3&63);a+=3}}return d};function c(c){c=c.replace(/\x0d\x0a/g,"\n");for(var b="",d=0;d<c.length;d++){var a=c.charCodeAt(d);if(a<128){b+=String.fromCharCode(a)}else if(a>127&&a<2048){b+=String.fromCharCode(a>>6|192);b+=String.fromCharCode(a&63|128)}else{b+=String.fromCharCode(a>>12|224);b+=String.fromCharCode(a>>6&63|128);b+=String.fromCharCode(a&63|128)}}return b};function base64Decode(b){var c="";var d,chr2,chr3="";var e,enc2,enc3,enc4="";var i=0;var f=a;var g=/[^A-Za-z0-9\+\/\=]/g;if(g.exec(b)){}b=b.replace(/[^A-Za-z0-9\+\/\=]/g,"");do{e=f.indexOf(b.charAt(i++));enc2=f.indexOf(b.charAt(i++));enc3=f.indexOf(b.charAt(i++));enc4=f.indexOf(b.charAt(i++));d=(e<<2)|(enc2>>4);chr2=((enc2&15)<<4)|(enc3>>2);chr3=((enc3&3)<<6)|enc4;c=c+String.fromCharCode(d);if(enc3!=64){c=c+String.fromCharCode(chr2)}if(enc4!=64){c=c+String.fromCharCode(chr3)}d=chr2=chr3="";e=enc2=enc3=enc4=""}while(i<b.length);return unescape(c)};function SetSignData(){kData[0]=0;for(var h=1;h<kData.length;h++){kData[0]+=kData[h]}V=kData[0]>=Y?true:false;Z=kData[0];var j="";J[0]=K+","+currBackColor+","+W+","+M+","+N+","+X+","+kData[0]+","+L+";";for(var p=0;p<J.length;p++){j+=J[p]}if(J.length>1){C.value=base64Encode(j)}else{C.value=""}if(!isIE&&bk){var a="";if(bData.length>0){for(var i=0;i<bData.length;i++){var b=bData[i];if(b.length){for(var j=0;j<b.length;j++){a=a+(b[j].x+' '+b[j].y+",")}}else{a=a+b.x+' '+b.y+","}a=a+";"}D.value=base64Encode(a);var c=document.getElementById(L).toDataURL();if(c=="data:,"){E.value=""}else{E.value=c}}else{D.value="";E.value=""}}};function GetPointArray(a){var b=a;b.splice(0,1);var c=[];for(var d=0;d<b.length;d++){var e=b[d].split(',');c.push({x:parseInt(e[0]),y:parseInt(e[1])})}return c};function ProcessCanvasArea(a){u.clearRect(0,0,M,N);if(U.length>0){var b=new Image();jQuery(b).bind('load',function(){});b.src=U}else{SignatureBackColor(L,currBackColor)}for(var c=0;c<bData.length;c++){var d=bData[c];var e=pData[c].split(',');var f=e[0];var g=e[1];if(d.length){u.beginPath();u.lineWidth=f;u.moveTo(d[0].x*a,d[0].y*a);for(var h=1;h<=d.length-3;h=h+3){u.bezierCurveTo(d[h].x*a,d[h].y*a,d[h+1].x*a,d[h+1].y*a,d[h+2].x*a,d[h+2].y*a);u.strokeStyle=g;u.stroke();u.beginPath();u.moveTo(d[h+2].x*a,d[h+2].y*a)}u.closePath()}else{u.beginPath();u.moveTo(d.x*a,d.y*a);u.arc(d.x*a,d.y*a,f/2,0,2*Math.PI,false);u.strokeStyle=g;u.fill();u.stroke();u.closePath()}}};function findPos(a){var b=curtop=0;if(a.offsetParent){do{b+=a.offsetLeft;curtop+=a.offsetTop}while(a=a.offsetParent)}return[b,curtop]}this.MouseMove=function(e){if(!G){return}if(!H){return}ReturnEvent(e);var a=0,ptY=0;var b=jQuery('#'+z.id).offset();if(s){var c=e.targetTouches[0];a=c.pageX-be;ptY=c.pageY-bf}else{if(e.originalEvent){if(e.pageX){a=parseInt(e.pageX-b.left);ptY=parseInt(e.pageY-b.top)}else{a=parseInt(e.originalEvent.pageX-b.left);ptY=parseInt(e.originalEvent.pageY-b.top)}}else{if(isIE||isIENine){a=parseInt(e.x);ptY=parseInt(e.y);if(iever>=9.0){a=parseInt(e.pageX-b.left);ptY=parseInt(e.pageY-b.top)}}else{a=parseInt(e.pageX-b.left);ptY=parseInt(e.pageY-b.top)}}}if(isMobileIE){fData.push(Math.abs(parseInt(a)-parseInt(z.offsetLeft))+","+Math.abs(parseInt(ptY)-parseInt(z.offsetTop)))}else{fData.push(Math.abs(parseInt(a))+","+Math.abs(parseInt(ptY)))}kData[r]++;if(!isIE){u.lineTo(a,ptY);u.stroke()}else{if(isMobileIE){var d=(a-bb);var f=(ptY-bc);var h=(d*d+f*f);var k=(8*8);if(h>k){if(bd!=null&&bd!='undefined'){try{bd.setStroke(currPenSize);bd.setColor(currPenColor);bd.drawLine(bb,bc,a,ptY);bd.paint()}catch(mme){ShowDebug("Drawing error: "+mme.description)}}else{ShowDebug("Graphics object NULL")}bb=a;bc=ptY}}else{u.pathCoordString+=" "+a+","+ptY;var g=document.getElementById(L+"_l_"+r);if(g){var i=g.firstChild;if(i){try{i.setAttribute("v",u.pathCoordString+" e")}catch(j){var l=j.Description}if(dcMode&&kData[r]%8==0){u.innerHTML=u.innerHTML}}}}}};this.DrawDot=function(a,b){kData[r]++;if(!isIE){u.arc(a,b,currPenSize/2,0,2*Math.PI,false);u.fill();u.stroke()}else{var c='<SuperSignature:stroke weight="'+currPenSize+'" color="'+currPenColor+'" />';var d='<SuperSignature:oval id="'+L+"_l_"+r+'" style="position: absolute; left:'+a+'px; top:'+b+'px; width:'+currPenSize+"px; height: "+currPenSize+'px;"'+'">'+c+"</SuperSignature:oval>";u.insertAdjacentHTML("beforeEnd",d)}};this.MouseOut=function(e){if(!G){return}ShowDebug("Mouse out");if(!I){MarkStrokeEnd()}};function MarkStrokeEnd(){I=true;H=false;if(fData.length>0){J[r]=" "+fData.join(" ")+";";if(!isIE&&bk){pData.push(fData[0]);var a=GetPointArray(fData);if(a.length>0){var b=new BezierCurves(a,30);bData.push(b);ProcessCanvasArea(1)}}}SetSignData();if(kData[0]<Y){A.innerHTML=P}else{A.innerHTML=Q}if(!isIE){u.closePath();u.restore()}else{u.innerHTML=u.innerHTML}if(s){be=0;bf=0}}function ReturnEvent(e){if(e.preventManipulation){e.preventManipulation()}if(e.preventDefault){e.preventDefault()}else if(e.returnValue){e.returnValue=false}if(e.originalEvent){if(e.originalEvent.preventManipulation){e.originalEvent.preventManipulation()}if(e.originalEvent.returnValue){e.originalEvent.returnValue=false}}}this.MouseUp=function(e){if(!G){return}ShowDebug("Mouse up");if(null!=bh){var a=parseInt(new Date()-bh);if(a<125){var b=0,ptY=0;ShowDebug("Time diff "+a);if(s){b=bi;ptY=bj}else{var c=jQuery('#'+z.id).offset();if(e.originalEvent){if(e.pageX){b=parseInt(e.pageX-c.left);ptY=parseInt(e.pageY-c.top)}else{b=parseInt(e.originalEvent.pageX-c.left);ptY=parseInt(e.originalEvent.pageY-c.top)}}else{if(isIE||isIENine){b=parseInt(e.x);ptY=parseInt(e.y)}else{b=parseInt(e.pageX-c.left);ptY=parseInt(e.pageY-c.top)}}}if(isIE){ShowDebug("Drawing Dot At ("+b+","+ptY+")");eval("obj"+L).DrawDot(b,ptY)}}bh=null}if(!I){MarkStrokeEnd()}};this.MouseDown=function(e){if(!G){return}ReturnEvent(e);ShowDebug("Mouse down");bh=new Date();H=true;I=false;var a,ptY;if(s){var b=e.targetTouches[0];if(be==0){var c=findPos(z);be=c[0];bf=c[1]}a=b.pageX-be;ptY=b.pageY-bf;bi=a;bj=ptY}else{var d=jQuery('#'+z.id).offset();if(e.originalEvent){if(e.pageX){a=parseInt(e.pageX-d.left);ptY=parseInt(e.pageY-d.top)}else{a=parseInt(e.originalEvent.pageX-d.left);ptY=parseInt(e.originalEvent.pageY-d.top)}}else{if(isIE||isIENine){if(iever<9.0){a=parseInt(e.x);ptY=parseInt(e.y)}else if(isIENine){a=parseInt(e.pageX)-parseInt(d.left)+parseInt(jQuery('html').css('margin-left'));ptY=parseInt(e.pageY)-parseInt(d.top)+parseInt(jQuery('html').css('margin-top'));if(iever>=10.0){a=parseInt(e.pageX-d.left);ptY=parseInt(e.pageY-d.top)}}}else{a=parseInt(e.pageX-d.left);ptY=parseInt(e.pageY-d.top)}}}ShowDebug("Down ("+a+","+ptY+")");r++;kData[r]=0;fData.length=0;fData[0]=currPenSize+","+currPenColor;if(isMobileIE){fData.push(Math.abs(parseInt(a)-parseInt(z.offsetLeft))+","+Math.abs(parseInt(ptY)-parseInt(z.offsetTop)))}else{fData.push(Math.abs(parseInt(a))+","+Math.abs(parseInt(ptY)))}if(isIE){if(isMobileIE){bb=a;bc=ptY}else{var f='<SuperSignature:stroke weight="'+currPenSize+'" color="'+currPenColor+'" />';var g='"m'+a+","+ptY+" l"+a+","+ptY;var h='<SuperSignature:shape id="'+L+"_l_"+r+'" style="position: absolute; left:0px; top:0px; width:'+M+"px; height: "+N+'px;" coordsize="'+M+","+N+'"><SuperSignature:path v='+g+' e" /><SuperSignature:fill on="false" />'+f+"</SuperSignature:shape>";u.pathCoordString=g;u.insertAdjacentHTML("beforeEnd",h)}}else{u.save();u.beginPath();u.lineJoin="round";u.lineCap="round";u.strokeStyle=currPenColor;u.lineWidth=currPenSize;u.moveTo(a,ptY)}return false};this.ResetClick=function(e){if(!G){return}if(!isMobileIE){document.getElementById(L).style.borderColor=currBorderColor}if(isIE){u.innerHTML="";if(isMobileIE){bb=0;bc=0;if(bd!=null&&bd!='undefined'){bd.clear();bd.paint()}}}else{u.clearRect(0,0,M,N);if(U.length>0){if(isIE){u.style.backgroundImage='url("'+U+'")'}else{var a=new Image();jQuery(a).bind('load',function(){u.drawImage(this,0,0)});a.src=U}}else{SignatureBackColor(L,currBackColor)}}J=[],fData=[],kData=[],bData=[],pData=[];SetSignData();r=0;ba="";A.innerHTML=O};this.Init=function(){if(!this.Visible){ShowDebug("Control hidden");return}this.CheckCompatibility();if(this.IsCompatible){F=document.getElementById(this.SignObject+"_Debug");u=document.getElementById(this.SignObject);z=document.getElementById(this.SignObject+"_Container");if(u.addEventListener){ShowDebug("addEventListener supported")}else if(u.attachEvent){ShowDebug("attachEvent supported")}else{ShowDebug("Mouse events are not supported");return}this.mouseMoved=false;if(u!=null&&u!='undefined'){ShowDebug("Objects OK");if(isIE&&!isMobileIE){dcMode=document.documentMode?document.documentMode>=8:false}if(isMobileIE){ShowDebug("Mobile IE")}if(isIENine){ShowDebug("IE 9 Or Above")}if(isOperaMini){ShowDebug("Opera Mini, not supported.")}kData[0]=0;J[0]=K+","+currBackColor+","+W+","+M+","+N+","+X+","+kData[0]+","+L+";";if(this.ApplyStyle){ShowDebug("Setting up style");try{if(isMobileIE){z.style.borderWidth=this.BorderWidth;z.style.borderStyle=this.BorderStyle;z.style.borderColor=this.BorderColor;z.style.backgroundColor=this.BackColor;z.style.zIndex=this.SignzIndex;if(this.PenCursor.length>0){z.style.cursor="url('"+this.PenCursor+"'), pointer"}if(this.BackImageUrl.length>0){z.style.backgroundImage='url("'+this.BackImageUrl+'")'}}else{u.style.borderWidth=this.BorderWidth;u.style.borderStyle=this.BorderStyle;u.style.borderColor=this.BorderColor;u.style.backgroundColor=this.BackColor;u.style.zIndex=this.SignzIndex;if(this.PenCursor.length>0){u.style.cursor="url('"+this.PenCursor+"'), pointer"}if(this.BackImageUrl.length>0){u.style.backgroundImage='url("'+this.BackImageUrl+'")'}if(this.CssClass!=""){u.className=this.CssClass}u.style.width=this.SignWidth+"px";u.style.height=this.SignHeight+"px";if(u.style.cursor=="auto"){u.style.cursor="url('"+this.PenCursor+"'), hand"}}ShowDebug("Style OK")}catch(exs){ShowDebug("Style Error : "+exs.description)}}var a='<div id="'+this.SignObject+'_toolbar" style="margin:5px;position:relative;height:20px;width:'+this.SignWidth+'px;background-color:'+this.SignToolbarBgColor+';"><img  id="'+this.SignObject+'_resetbutton" src="'+this.ClearImage+'" style="cursor:pointer;float:right;height:24px;width:24px;border:0px solid transparent" alt="Clear Signature" />';a+='<div id="'+this.SignObject+'_status" style="color:blue;height:20px;width:auto;padding:2px;font-family:verdana;font-size:12px;float:left;margin-right:30px;">'+this.StartMessage+"</div>";a+=document.getElementById(this.SignObject+"_data")==null?'<input type="hidden" id="'+this.SignObject+'_data" name="'+this.SignObject+'_data" value="">':"";a+=document.getElementById(this.SignObject+"_data_smooth")==null?'<input type="hidden" id="'+this.SignObject+'_data_smooth" name="'+this.SignObject+'_data_smooth" value="">':"";a+=document.getElementById(this.SignObject+"_data_canvas")==null?'<input type="hidden" id="'+this.SignObject+'_data_canvas" name="'+this.SignObject+'_data_canvas" value="">':"";a+="</div>";ShowDebug("Setting up tools");jQuery("#"+z.id).after(a);r=0;var b="mousedown",mouseUpEvent="mouseup",mouseMoveEvent="mousemove",mouseOutEvent="mouseout";if(isIE){mouseOutEvent="mouseleave"}s=(new RegExp("iPhone","i")).test(navigator.userAgent)||(new RegExp("iPad","i")).test(navigator.userAgent)||(new RegExp("Android","i")).test(navigator.userAgent)||(new RegExp("playbook","i")).test(navigator.userAgent)||(new RegExp("symbian","i")).test(navigator.userAgent);if(!s&&!isIE){s=("ontouchstart"in window);ShowDebug("Touch supported!")}if(s){ShowDebug("Found iPhone or iPad or Android");b="touchstart";mouseUpEvent="touchend";mouseMoveEvent="touchmove"}if(isIETablet){ShowDebug("Found Tablet or Windows Phone or Touch Screen")}if(winTabletPointerEvt){ShowDebug("MSPointer supported");b="MSPointerDown";mouseUpEvent="MSPointerUp";mouseMoveEvent="MSPointerMove";mouseOutEvent="MSPointerOut"}else{ShowDebug("MSPointer NOT supported")}if(typeof u.style.msTouchAction!="undefined"){u.style.msTouchAction="none";jQuery('#'+u.id).css("-ms-touch-action","none");ShowDebug("MS Touch CSS added")}if(typeof z.style.msTouchAction!="undefined"){z.style.msTouchAction="none";jQuery('#'+z.id).css("-ms-touch-action","none")}if(!isIE){u=document.getElementById(this.SignObject).getContext("2d");u.width=this.SignWidth;u.height=this.SignHeight}A=document.getElementById(this.SignObject+"_status");A.innerHTML=O;B=document.getElementById(this.SignObject+"_toolbar");C=document.getElementById(this.SignObject+"_data");D=document.getElementById(this.SignObject+"_data_smooth");E=document.getElementById(this.SignObject+"_data_canvas");var c=document.getElementById(this.SignObject+"_resetbutton");if(null!=c){this.XBrowserAddHandler(c,"click",this.ResetClick)}ShowDebug("Attaching events");this.XBrowserAddHandler(z,"contextmenu",this.ReturnFalse);this.XBrowserAddHandler(z,"selectstart",this.ReturnFalse);this.XBrowserAddHandler(z,"dragstart",this.ReturnFalse);this.XBrowserAddHandler(u,"contextmenu",this.ReturnFalse);this.XBrowserAddHandler(u,"selectstart",this.ReturnFalse);this.XBrowserAddHandler(u,"dragstart",this.ReturnFalse);this.DisableSelection();if(this.IeModalFix){ShowDebug("IeModalFix true")}if(this.IeModalFix&&!s&&!isIETablet){jQuery(z).bind(b,this.MouseDown);jQuery(z).bind(mouseUpEvent,this.MouseUp);jQuery(z).bind(mouseMoveEvent,this.MouseMove);jQuery(z).bind(mouseOutEvent,this.MouseOut);ShowDebug("ModalFix event attached")}else{if(isIE&&!isMobileIE){this.XBrowserAddHandler(u,b,this.MouseDown);this.XBrowserAddHandler(u,mouseUpEvent,this.MouseUp);this.XBrowserAddHandler(u,mouseMoveEvent,this.MouseMove);this.XBrowserAddHandler(u,mouseOutEvent,this.MouseOut)}else{this.XBrowserAddHandler(z,b,this.MouseDown);this.XBrowserAddHandler(z,mouseUpEvent,this.MouseUp);this.XBrowserAddHandler(z,mouseMoveEvent,this.MouseMove);this.XBrowserAddHandler(z,mouseOutEvent,this.MouseOut);this.XBrowserAddHandler(u,mouseOutEvent,this.MouseOut)}}ShowDebug("isIE "+isIE);ShowDebug("isIE 9+ "+isIENine);if(isIE||isIENine||isIETablet){ShowDebug("IE Ver - "+iever)}if(supports_canvas()){ShowDebug("Canvas - Yes")}if(!G){ShowDebug("Control is disabled")}ShowDebug("Ready")}else{ShowDebug("Error initializing signature control")}}}};BezierCurves=function(a,t){if(a.length<2){return a[0]}var b,tmpSmooth;this.points=[];for(var i=0;i<a.length;i++){b=new Smooth(a[i].x,a[i].y);b.CheckValid(tmpSmooth)||this.points.push(b),tmpSmooth=b;this.offSet=0;this.error=t;this.result=[];this.result.push({x:a[0].x,y:a[0].y+this.offSet})}return this.fit()};BezierCurves.prototype={fit:function(){this.processFitPoints(0,this.points.length-1,this.points[1].MathFunc2(this.points[0]).nOR(),this.points[this.points.length-2].MathFunc2(this.points[this.points.length-1]).nOR());return this.result},processFitPoints:function(n,t,i,r){var e,c,u,l,o,f;if(t-n==1){var s=this.points[n],h=this.points[t],a=s.MathFunc6(h)/3;this.savePoints([s,s.MathFunc1(i.nOR(a)),h.MathFunc1(r.nOR(a)),h]);return}for(e=this.cLP(n,t),c=Math.max(this.error,this.error*this.error),l=0;l<=4;l++){if(o=this.gB(n,t,e,i,r),f=this.fM(n,t,o,e),f.error<this.error){this.savePoints(o);return}if(u=f.index,f.error>=c)break;this.rP(n,t,e,o),c=f.error}var y=this.points[u-1].MathFunc2(this.points[u]),p=this.points[u].MathFunc2(this.points[u+1]),v=y.MathFunc1(p).MathFunc4(2).nOR();this.processFitPoints(n,u,i,v),this.processFitPoints(u,t,v.MathFunc5(),r)},savePoints:function(n){this.result.push({x:this.r2(n[1].x),y:this.r2(n[1].y+this.offSet)}),this.result.push({x:this.r2(n[2].x),y:this.r2(n[2].y+this.offSet)}),this.result.push({x:this.r2(n[3].x),y:this.r2(n[3].y+this.offSet)})},r2:function(n){return Math.round(n*100)/100},gB:function(n,t,i,r,u){for(var s=1e-11,v=this.points[n],y=this.points[t],f=[[0,0],[0,0]],e=[0,0],b,a,o,ut,ft,k,et,d,c=0,g=t-n+1;c<g;c++){var h=i[c],l=1-h,nt=3*h*l,ot=l*l*l,tt=nt*l,it=nt*h,st=h*h*h,p=r.nOR(tt),w=u.nOR(it),rt=this.points[n+c].MathFunc2(v.MathFunc3(ot+tt)).MathFunc2(y.MathFunc3(it+st));f[0][0]+=p.dot(p),f[0][1]+=p.dot(w),f[1][0]=f[0][1],f[1][1]+=w.dot(w),e[0]+=p.dot(rt),e[1]+=w.dot(rt)}return b=f[0][0]*f[1][1]-f[1][0]*f[0][1],Math.abs(b)>s?(ut=f[0][0]*e[1]-f[1][0]*e[0],ft=e[0]*f[1][1]-e[1]*f[0][1],a=ft/b,o=ut/b):(k=f[0][0]+f[0][1],et=f[1][0]+f[1][1],a=Math.abs(k)>s?o=e[0]/k:Math.abs(k)>s?o=e[1]/et:o=0),d=y.MathFunc6(v),s*=d,(a<s||o<s)&&(a=o=d/3),[v,v.MathFunc1(r.nOR(a)),y.MathFunc1(u.nOR(o)),y]},rP:function(n,t,i,r){for(var u=n;u<=t;u++)i[u-n]=this.fR(r,this.points[u],i[u-n])},fR:function(n,t,i){for(var u=[],e=[],r=0;r<=2;r++)u[r]=n[r+1].MathFunc2(n[r]).MathFunc3(3);for(r=0;r<=1;r++)e[r]=u[r+1].MathFunc2(u[r]).MathFunc3(2);var h=this.eV(3,n,i),f=this.eV(2,u,i),c=this.eV(1,e,i),o=h.MathFunc2(t),s=f.dot(f)+o.dot(c);return Math.abs(s)<1e-5?i:i-o.dot(f)/s},eV:function(n,t,i){for(var u=t.slice(),r,f=1;f<=n;f++)for(r=0;r<=n-f;r++)u[r]=u[r].MathFunc3(1-i).MathFunc1(u[r+1].MathFunc3(i));return u[0]},cLP:function(n,t){for(var r=[0],u,i=n+1;i<=t;i++)r[i-n]=r[i-n-1]+this.points[i].MathFunc6(this.points[i-1]);for(i=1,u=t-n;i<=u;i++)r[i]/=r[u];return r},fM:function(n,t,i,r){for(var o=Math.floor((t-n+1)/2),e=0,u=n+1;u<t;u++){var h=this.eV(3,i,r[u-n]),f=h.MathFunc2(this.points[u]),s=f.x*f.x+f.y*f.y;s>=e&&(e=s,o=u)}return{error:e,index:o}}};Smooth=function(a,b){this.x=a;this.y=b};Smooth.prototype={CheckPtArray:function(a){return typeof a=="number"?{x:a,y:a}:a},MathFunc1:function(a){a=this.CheckPtArray(a);return SmoothRet(this.x+a.x,this.y+a.y)},MathFunc2:function(a){a=this.CheckPtArray(a);return SmoothRet(this.x-a.x,this.y-a.y)},MathFunc3:function(a){a=this.CheckPtArray(a);return SmoothRet(this.x*a.x,this.y*a.y)},MathFunc4:function(a){a=this.CheckPtArray(a);return SmoothRet(this.x/a.x,this.y/a.y)},MathFunc5:function(){return SmoothRet(-this.x,-this.y)},MathFunc6:function(a,t){var i=a.x-this.x,r=a.y-this.y,u=i*i+r*r;return t?u:Math.sqrt(u)},getLength:function(){var n=this.x*this.x+this.y*this.y;return arguments[0]?n:Math.sqrt(n)},nOR:function(n){if(n===undefined){n=1}var t=this.getLength(),i=t!=0?n/t:0;return SmoothRet(this.x*i,this.y*i)},CheckValid:function(a){return a==null?false:this.x==a.x&&this.y==a.y},dot:function(n){return this.x*n.x+this.y*n.y}};var SmoothRet=function(a,b){return new Smooth(a,b)};Compress=function(n,t){var o=0,s=0,h=n.length,r="",f,e,u,i;for(t=Math.pow(10,t),i=0;i<h;i++)f=Math.round(n[i].y*t),e=Math.round(n[i].x*t),r+=EncodeStr(f-o),r+=EncodeStr(e-s),o=f,s=e;for(u=[["?","0"],["@","1"],["[","2"],["\\","3"],["]","4"],["^","5"],["`","6"],["{","7"],["|","8"],["}","9"]],i=0;i<u.length;i++)r=r.replaceAll(u[i][0],u[i][1]);return r};EncodeStr=function(n){var n=n<<1,t;for(n<0&&(n=~n),t="";n>=32;)t+=String.fromCharCode((32|n&31)+63),n>>=5;return t+String.fromCharCode(n+63)};String.prototype.replaceAll=function(n,t,i){return this.replace(new RegExp(n.replace(/([\/\,\!\\\^\$\{\}\[\]\(\)\.\*\+\?\|\<\>\-\&])/g,"\\$&"),i?"gi":"g"),typeof t=="string"?t.replace(/\$/g,"$$$$"):t)};