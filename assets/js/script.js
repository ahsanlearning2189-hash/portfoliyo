(()=>{'use strict';
const $=(s,r=document)=>r.querySelector(s),$$=(s,r=document)=>[...r.querySelectorAll(s)];
const reduce=matchMedia('(prefers-reduced-motion:reduce)').matches,touch=matchMedia('(hover:none)').matches;
// theme
$('#theme').onclick=()=>{const t=document.documentElement.dataset.theme==='dark'?'light':'dark';document.documentElement.dataset.theme=t;try{localStorage.setItem('theme',t)}catch(e){}};
// mobile menu
const menu=$('#menu'),bg=$('#burger');
bg.onclick=()=>{const o=menu.classList.toggle('open');bg.setAttribute('aria-expanded',o);bg.textContent=o?'✕':'☰'};
$$('#menu a').forEach(a=>a.onclick=()=>{menu.classList.remove('open');bg.textContent='☰'});
// scroll: navbar, progress, active link
const links=$$('#menu a'),secs=links.map(a=>$(a.getAttribute('href')));
addEventListener('scroll',()=>{const h=document.documentElement;$('#nav').classList.toggle('sc',scrollY>30);
 $('#progress').style.width=(scrollY/(h.scrollHeight-innerHeight)*100)+'%';
 let i=0;secs.forEach((s,n)=>{if(s&&s.getBoundingClientRect().top<innerHeight*.4)i=n});links.forEach((a,n)=>a.classList.toggle('act',n===i))},{passive:true});
// reveal + counters + skill bars
const io=new IntersectionObserver(es=>es.forEach(e=>{if(!e.isIntersecting)return;const t=e.target;t.classList.add('in');
 $$('[data-w]',t).forEach(b=>b.style.width=b.dataset.w+'%');io.unobserve(t)}),{threshold:.2});
$$('.rv').forEach(e=>io.observe(e));
$$('.facts dt').forEach(d=>{const n=+d.dataset.n;let c=0;const step=()=>{c+=Math.max(1,n/40);d.textContent=Math.min(n,Math.round(c))+'+';if(c<n)requestAnimationFrame(step)};step()});
// tilt + magnetic + hero parallax (rAF-throttled)
if(!reduce&&!touch){
 $$('.tilt').forEach(c=>{c.addEventListener('mousemove',e=>{const r=c.getBoundingClientRect(),x=(e.clientX-r.left)/r.width-.5,y=(e.clientY-r.top)/r.height-.5;
  c.style.transform=`rotateY(${x*10}deg) rotateX(${-y*10}deg) translateZ(10px)`});c.addEventListener('mouseleave',()=>c.style.transform='')});
 $$('.mag').forEach(b=>{b.addEventListener('mousemove',e=>{const r=b.getBoundingClientRect();b.style.transform=`translate(${(e.clientX-r.left-r.width/2)*.2}px,${(e.clientY-r.top-r.height/2)*.3}px)`});b.addEventListener('mouseleave',()=>b.style.transform='')});
 const st=$('.stage');let px=0,py=0,q=false;addEventListener('mousemove',e=>{px=e.clientX/innerWidth-.5;py=e.clientY/innerHeight-.5;if(!q){q=true;requestAnimationFrame(()=>{st.style.transform=`rotateY(${px*22}deg) rotateX(${-py*14}deg)`;q=false})}});
 const cur=$('#cur');addEventListener('mousemove',e=>{cur.style.transform=`translate(${e.clientX}px,${e.clientY}px)`});
 document.addEventListener('mouseover',e=>cur.classList.toggle('big',!!e.target.closest('a,button,.proj-c')));
}
// project filter
$$('.chip-b').forEach(b=>b.onclick=()=>{$$('.chip-b').forEach(x=>x.classList.toggle('on',x===b));
 $$('.proj-c').forEach(c=>c.hidden=b.dataset.f!=='all'&&c.dataset.cat!==b.dataset.f)});
// project modal
const m=$('#modal');let last;
const open=c=>{last=c;$('#m-t').textContent=c.dataset.t;$('#m-d').textContent=c.dataset.d;
 const ul=$('#m-tech');ul.innerHTML='';c.dataset.tech.split(',').filter(Boolean).forEach(t=>{const li=document.createElement('li');li.textContent=t.trim();ul.append(li)});
 [['live','#m-live'],['gh','#m-gh']].forEach(([k,s])=>{const a=$(s),u=c.dataset[k];a.hidden=!u||u==='#';a.href=u||'#'});m.hidden=false;$('#m-x').focus()};
const close=()=>{m.hidden=true;last&&last.focus()};
$$('.proj-c').forEach(c=>{c.onclick=()=>open(c);c.onkeydown=e=>{if(e.key==='Enter'||e.key===' '){e.preventDefault();open(c)}}});
$('#m-x').onclick=close;m.onclick=e=>{if(e.target===m)close()};addEventListener('keydown',e=>{if(e.key==='Escape'&&!m.hidden)close()});
// testimonial slider
const sl=$$('.slide');let i=0,timer;const go=n=>{if(!sl.length)return;sl[i].classList.remove('on');i=(n+sl.length)%sl.length;sl[i].classList.add('on')};
$('#prev').onclick=()=>{go(i-1);rs()};$('#next').onclick=()=>{go(i+1);rs()};const rs=()=>{clearInterval(timer);if(!reduce)timer=setInterval(()=>go(i+1),6000)};rs();
let sx=0;const sd=$('#slider');sd.addEventListener('touchstart',e=>sx=e.touches[0].clientX,{passive:true});sd.addEventListener('touchend',e=>{const d=e.changedTouches[0].clientX-sx;if(Math.abs(d)>40){go(i+(d<0?1:-1));rs()}});
// particles
const cv=$('#bg'),x=cv.getContext('2d');let W,H,P=[],mx=-999,my=-999,run=true;
const size=()=>{W=cv.width=innerWidth;H=cv.height=innerHeight;P=Array.from({length:innerWidth<700?28:70},()=>({x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.3,vy:(Math.random()-.5)*.3}))};
size();addEventListener('resize',size);addEventListener('mousemove',e=>{mx=e.clientX;my=e.clientY});
document.addEventListener('visibilitychange',()=>{run=!document.hidden;if(run)tick()});
function tick(){if(!run)return;x.clearRect(0,0,W,H);const col=document.documentElement.dataset.theme==='light'?'8,145,178':'34,227,255';
 for(const p of P){p.x+=p.vx;p.y+=p.vy;if(p.x<0||p.x>W)p.vx*=-1;if(p.y<0||p.y>H)p.vy*=-1;
  const dx=p.x-mx,dy=p.y-my,d=dx*dx+dy*dy;if(d<14000){p.x+=dx*.002;p.y+=dy*.002}
  x.fillStyle=`rgba(${col},.7)`;x.fillRect(p.x,p.y,2,2)}
 for(let a=0;a<P.length;a++)for(let b=a+1;b<P.length;b++){const dx=P[a].x-P[b].x,dy=P[a].y-P[b].y,d=dx*dx+dy*dy;
  if(d<14400){x.strokeStyle=`rgba(${col},${.18*(1-d/14400)})`;x.beginPath();x.moveTo(P[a].x,P[a].y);x.lineTo(P[b].x,P[b].y);x.stroke()}}
 if(!reduce)requestAnimationFrame(tick)}
tick();
})();
