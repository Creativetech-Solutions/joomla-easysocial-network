FD40.component("EasySocial", {"environment":"static","source":"local","mode":"compressed","baseUrl":"http:\/\/www.dowalo.com\/index.php\/en\/?option=com_easysocial&Itemid=1474","version":"1.4.12","momentLang":"en-gb","lockdown":false,"guest":true,"ajaxUrl":"http:\/\/www.dowalo.com\/index.php\/en\/?option=com_easysocial&Itemid=1474"});
!function(a){var x,b=" ",c="width",d="height",e="replace",f="classList",g="className",h="parentNode",i="fit-width",j="fit-height",k="fit-both",l="fit-small",m=i+b+j+b+k+b+l,n=function(a,b){return a.getAttribute("data-"+b)},o=function(a,b){return a["natural"+b[0].toUpperCase()+b.slice(1)]},p=function(a,b){return parseInt(n(a,b)||o(a,b)||a[b])},q=function(a,c){a[f]?a[f].add(c):a[g]+=b+c},r=function(a,c){a[g]=a[g][e](new RegExp("\\b("+c[e](/\s+/g,"|")+")\\b","g"),b)[e](/\s+/g,b)[e](/^\s+|\s+$/g,"")},s=function(a,b,c){a.style[b]=c+"px"},u=function(a,b,e,f,g,t,v,x,y,z){return!n(a,c)&&0===o(a,c)&&(a._retry||(a._retry=0))<=25?setTimeout(function(){a._retry++,u(a)},200):(b=a[h],e=b[h],f=e[h],g=n(b,"mode"),t=n(b,"threshold"),v=p(a,c),x=p(a,d),y=b.offsetWidth,z=b.offsetHeight,r(f,m),q(f,t>v&&t>x?function(){return s(a,c,v),s(a,d,x),l}():"cover"==g?function(b,c,d){return 1>y||1>z?(w.push(a),k):(b=y/z,c=y/v,d=z/x,1>b?z>x*c?j:i:b>1?y>v*d?i:j:1==b?1>=v/x?i:j:void 0)}():function(){return w.push(a),a.style.maxHeight="none",s(a,"maxHeight",b.offsetHeight),k}()),a.removeAttribute("onload"),void 0)},v=function(a,b){for(b=w,w=[];a=b.shift();)a[h]&&u(a)},w=[],y=function(){clearTimeout(x),x=setTimeout(v,500)},z=a.ESImageList||[];for(a.ESImage=u,a.ESImageRefresh=v,a.addEventListener?a.addEventListener("resize",y,!1):a.attachEvent("resize",y);z.length;)u(z.shift())}(window);
