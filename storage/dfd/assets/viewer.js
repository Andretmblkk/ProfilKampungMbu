const data=window.DFD_DATA||{levels:[],meta:{}};
const svgs=window.DFD_SVGS||{};
const nav=document.getElementById("levelNav");
const tree=document.getElementById("tree");
const title=document.getElementById("title");
const canvas=document.getElementById("canvas");
const stage=document.getElementById("stage");
const minimap=document.getElementById("minimap");
const breadcrumb=document.getElementById("breadcrumb");
const search=document.getElementById("search");
let current=(data.levels.find(level=>level.level===0)||data.levels[0]||{}).id||"level-0";
let scale=1,pan={x:0,y:0},drag=null;
document.getElementById("generatedAt").textContent=data.meta.generatedAt?`Generated ${new Date(data.meta.generatedAt).toLocaleString()}`:"Generated now";
document.getElementById("stats").innerHTML=["routes","processes","dataStores","externalEntities"].map(key=>`<div class=\"stat\"><b>${data.meta[key]||0}</b><span>${key}</span></div>`).join("");
function renderNav(){const query=(search.value||"").toLowerCase();nav.innerHTML="";data.levels.filter(level=>!query||level.title.toLowerCase().includes(query)||level.processes.some(process=>process.label.toLowerCase().includes(query))).forEach(level=>{const button=document.createElement("button");button.type="button";button.textContent=`Level ${level.level} - ${level.title.replace(/^Level \d+\s*/,"")}`;button.className=level.id===current?"active":"";button.onclick=()=>show(level.id);nav.appendChild(button);});}
function renderTree(){tree.innerHTML="<strong>Hierarchy</strong>";data.levels.filter(level=>level.level===1).forEach(level=>{level.processes.forEach(process=>{const details=document.createElement("details");details.open=true;const summary=document.createElement("summary");summary.textContent=process.label;summary.onclick=()=>show(level.id);details.appendChild(summary);data.levels.filter(child=>child.parentProcessId===process.id).forEach(child=>{const a=document.createElement("a");a.href="#";a.textContent=child.title;a.dataset.target=child.id;a.onclick=event=>{event.preventDefault();show(child.id);};details.appendChild(a);});tree.appendChild(details);});});}
function activateTree(id){tree.querySelectorAll("a").forEach(link=>link.classList.toggle("active",link.dataset.target===id));}
function show(id){current=id;const level=data.levels.find(item=>item.id===id)||data.levels[0];if(!level)return;title.textContent=level.title;breadcrumb.textContent=`${data.system} / Level ${level.level}`;stage.innerHTML=svgs[level.id]||"";minimap.innerHTML=svgs[level.id]||"";stage.querySelectorAll(".dfd-process").forEach(node=>{node.style.cursor="pointer";node.addEventListener("click",()=>openChild(node.dataset.id));});renderNav();activateTree(level.id);applyTransform();}
function openChild(processId){const child=data.levels.find(level=>level.parentProcessId===processId);if(child)show(child.id);}
function applyTransform(){stage.style.transform=`translate(${pan.x}px,${pan.y}px) scale(${scale})`;}
function zoomAt(delta,cx=canvas.clientWidth/2,cy=canvas.clientHeight/2){const old=scale;scale=Math.min(2.8,Math.max(.25,scale+delta));pan.x=cx-(cx-pan.x)*(scale/old);pan.y=cy-(cy-pan.y)*(scale/old);applyTransform();}
function fit(){const svg=stage.querySelector("svg");if(!svg)return;const box=svg.viewBox.baseVal;scale=Math.min((canvas.clientWidth-60)/box.width,(canvas.clientHeight-60)/box.height);pan={x:30,y:30};applyTransform();}
document.getElementById("zoomIn").onclick=()=>zoomAt(.15);
document.getElementById("zoomOut").onclick=()=>zoomAt(-.15);
document.getElementById("fit").onclick=fit;
document.getElementById("reset").onclick=()=>{scale=1;pan={x:0,y:0};applyTransform();};
document.getElementById("fullscreen").onclick=()=>document.documentElement.requestFullscreen&&document.documentElement.requestFullscreen();
document.getElementById("exportSvg").onclick=()=>download(`${current}.svg`,svgs[current]||"","image/svg+xml");
document.getElementById("exportJson").onclick=()=>download(`${current}.json`,JSON.stringify(data.levels.find(level=>level.id===current)||{},null,2),"application/json");
document.getElementById("exportPng").onclick=()=>{const svg=stage.querySelector("svg");if(!svg)return;const img=new Image();const blob=new Blob([svg.outerHTML],{type:"image/svg+xml"});const url=URL.createObjectURL(blob);img.onload=()=>{const c=document.createElement("canvas");c.width=img.width||1180;c.height=img.height||720;const ctx=c.getContext("2d");ctx.drawImage(img,0,0);URL.revokeObjectURL(url);c.toBlob(png=>downloadBlob(`${current}.png`,png));};img.src=url;};
function download(name,content,type){downloadBlob(name,new Blob([content],{type}));}
function downloadBlob(name,blob){if(!blob)return;const a=document.createElement("a");a.href=URL.createObjectURL(blob);a.download=name;a.click();setTimeout(()=>URL.revokeObjectURL(a.href),1200);}
canvas.addEventListener("wheel",event=>{event.preventDefault();zoomAt(event.deltaY<0?.12:-.12,event.clientX,event.clientY);},{passive:false});
canvas.addEventListener("mousedown",event=>{if(event.button!==0&&event.button!==1)return;drag={x:event.clientX-pan.x,y:event.clientY-pan.y};canvas.classList.add("dragging");});
canvas.addEventListener("touchstart",event=>{const t=event.touches[0];drag={x:t.clientX-pan.x,y:t.clientY-pan.y};canvas.classList.add("dragging");},{passive:true});
canvas.addEventListener("touchmove",event=>{if(!drag)return;const t=event.touches[0];pan={x:t.clientX-drag.x,y:t.clientY-drag.y};applyTransform();},{passive:true});
window.addEventListener("mousemove",event=>{if(!drag)return;pan={x:event.clientX-drag.x,y:event.clientY-drag.y};applyTransform();});
window.addEventListener("mouseup",()=>{drag=null;canvas.classList.remove("dragging");});
window.addEventListener("touchend",()=>{drag=null;canvas.classList.remove("dragging");});
document.getElementById("toggleSidebar").onclick=()=>document.querySelector(".side").classList.toggle("open");
search.addEventListener("input",renderNav);
renderTree();show(current);
