import{$ as d,d as y,G as C,H as w,v as x,I as D,c as R,b as u,h as l,a as U,q as p,e as n,y as m,j as a}from"./index.5191b12d.js";function q(e=[]){return d({url:"/audit",method:"get",params:e})}function k(e){return d({url:"/audit",method:"post",data:e})}function A(e){return d({url:"/audit/"+e.id,method:"put",data:e})}function O(e){return d({url:"/audit/"+e,method:"delete"})}const L={class:"app-container"},j={class:"dialog-footer"},$=y({__name:"dialogForm",props:{options:{type:Object,default(){return{}}},data:{type:Object,default:()=>{}}},emits:["close","success"],setup(e,{emit:i}){const f=e,_=C({rules:{url:[{required:!0,message:"\u8BF7\u8F93\u5165URL",trigger:"blur"}]}}),{rules:b}=w(_),t=x({get:()=>f.data,set:v=>{}}),g=()=>{t.value.id?A(t.value).then(()=>{i("success")}):k(t.value).then(()=>{i("success")})};return D(()=>{}),(v,o)=>{var c;const F=l("el-input"),h=l("el-form-item"),V=l("el-form"),r=l("el-button"),B=l("el-dialog");return U(),R(B,{title:e.options.title,width:e.options.width,top:e.options.top,"append-to-body":(c=e.options.inBody)!=null?c:!0,modelValue:e.options.visible,"onUpdate:modelValue":o[2]||(o[2]=s=>e.options.visible=s),draggable:""},{footer:u(()=>[p("span",j,[n(r,{onClick:o[1]||(o[1]=s=>{i("close")})},{default:u(()=>[m("\u53D6\u6D88")]),_:1}),n(r,{type:"success",onClick:g},{default:u(()=>[m("\u4FDD\u5B58")]),_:1})])]),default:u(()=>[p("div",L,[n(V,{ref:"dataFormRef",rules:a(b),model:a(t),"label-width":"90px"},{default:u(()=>[n(h,{label:"URL",prop:"url"},{default:u(()=>[n(F,{modelValue:a(t).url,"onUpdate:modelValue":o[0]||(o[0]=s=>a(t).url=s),placeholder:"\u8BF7\u8F93URL\uFF0C\u652F\u6301*\u53F7\u6CDB\u89E3\u6790"},null,8,["modelValue"])]),_:1})]),_:1},8,["rules","model"])])]),_:1},8,["title","width","top","append-to-body","modelValue"])}}});export{$ as _,O as d,q as g};
