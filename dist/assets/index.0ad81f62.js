import{$ as e}from"./index.10772f47.js";function n(t){return e({url:"/api/v1/dept",method:"get",params:t})}function o(){return e({url:"/api/v1/dept/options",method:"get"})}function u(t){return e({url:"/api/v1/dept/"+t+"/form",method:"get"})}function a(t){return e({url:"/api/v1/dept",method:"post",data:t})}function d(t,p){return e({url:"/api/v1/dept/"+t,method:"put",data:p})}function i(t){return e({url:"/api/v1/dept/"+t,method:"delete"})}export{a,o as b,i as d,u as g,n as l,d as u};