<?php require_once __DIR__.'/includes/functions.php';
$errors=[];$sent=false;$old=['name'=>'','email'=>'','subject'=>'','message'=>''];
if($_SERVER['REQUEST_METHOD']==='POST'){
  foreach($old as $k=>$_) $old[$k]=trim($_POST[$k]??'');
  if(!csrf_ok()) $errors[]='Session expired. Reload the page and try again.';
  if(mb_strlen($old['name'])<2) $errors[]='Enter your name.';
  if(!filter_var($old['email'],FILTER_VALIDATE_EMAIL)) $errors[]='Enter a valid email address.';
  if(mb_strlen($old['subject'])<3) $errors[]='Enter a subject.';
  if(mb_strlen($old['message'])<10) $errors[]='Write a message of at least 10 characters.';
  if(!$errors){ db()->prepare('INSERT INTO contact_messages(name,email,subject,message) VALUES(?,?,?,?)')->execute(array_values($old)); $sent=true; $old=array_map(fn()=>'',$old); }
}
$pdo=db();
$projects=$pdo->query('SELECT * FROM projects WHERE status=1 ORDER BY featured DESC,created_at DESC')->fetchAll();
$skills=$pdo->query('SELECT * FROM skills WHERE status=1 ORDER BY level DESC')->fetchAll();
$tests=$pdo->query('SELECT * FROM testimonials WHERE status=1 ORDER BY id DESC')->fetchAll();
$posts=$pdo->query('SELECT * FROM blog_posts WHERE status=1 ORDER BY created_at DESC LIMIT 3')->fetchAll();
?><!doctype html><html lang="en" data-theme="dark"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Nexa Digital — Web developer for hire</title>
<meta name="description" content="Nexa Digital builds fast, secure PHP and MySQL websites with 3D interfaces for startups and agencies.">
<meta property="og:title" content="Nexa Digital — Web developer for hire"><meta property="og:type" content="website">
<link rel="preconnect" href="https://fonts.googleapis.com"><link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Syne:wght@700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<script>try{document.documentElement.dataset.theme=localStorage.getItem('theme')||'dark'}catch(e){}</script></head>
<body><canvas id="bg" aria-hidden="true"></canvas><div class="progress" id="progress"></div>
<div class="cursor" id="cur" aria-hidden="true"></div>
<header class="nav" id="nav"><a class="logo" href="#home" aria-label="Nexa Digital home"><b>NEXA</b><i>DIGITAL</i></a>
<nav id="menu" aria-label="Main"><a href="#home">Home</a><a href="#skills">Skills</a><a href="#projects">Projects</a><a href="#testimonials">Reviews</a><a href="#blog">Blog</a><a href="#contact">Contact</a></nav>
<div class="nav-r"><span class="avail">Available</span><button id="theme" class="icon" aria-label="Toggle light and dark theme">◐</button><a class="btn mag" href="#contact">Hire me</a>
<button id="burger" class="icon burger" aria-label="Open menu" aria-expanded="false">☰</button></div></header>

<main>
<section id="home" class="hero"><div class="hero-t"><p class="badge"><span></span> Available for freelance</p>
<h1>Building<br>digital<br><em>experiences.</em></h1>
<p class="sub">I design and ship fast PHP and MySQL products with interfaces that feel three-dimensional, from booking systems to store backends.</p>
<div class="cta"><a class="btn mag" href="#projects">View my work</a><a class="btn ghost mag" href="#contact">Get a quote</a></div>
<dl class="facts"><div><dt data-n="12">0</dt><dd>Projects shipped</dd></div><div><dt data-n="3">0</dt><dd>Years working</dd></div><div><dt data-n="15">0</dt><dd>Technologies</dd></div></dl></div>
<div class="scene" id="scene" aria-hidden="true"><div class="stage"><div class="rig">
<div class="screen"><pre><span class="c">// nexa.php</span>
$db = new PDO($dsn);
$stmt = $db-&gt;prepare(
  'SELECT * FROM projects');
<span class="c">&gt; build complete ✔</span></pre></div><div class="base"></div>
<div class="chip c1">PHP</div><div class="chip c2">JS</div><div class="chip c3">SQL</div><div class="chip c4">CSS</div>
<div class="ring"></div></div><div class="floor"></div></div></div></section>

<section id="skills" class="sec"><h2 class="rv">Skills I work with</h2><div class="grid skills">
<?php foreach($skills as $s):?><article class="card tilt rv"><h3><?=e($s['name'])?></h3><small><?=e($s['category'])?></small>
<div class="bar" role="progressbar" aria-valuenow="<?=$s['level']?>" aria-valuemin="0" aria-valuemax="100"><span data-w="<?=$s['level']?>"></span></div><b><?=$s['level']?>%</b></article><?php endforeach;?></div></section>

<section id="projects" class="sec"><h2 class="rv">Selected projects</h2>
<div class="filters" role="group" aria-label="Filter projects"><?php foreach(['all'=>'All','web'=>'Web','php'=>'PHP','uiux'=>'UI/UX','3d'=>'3D','database'=>'Database'] as $k=>$l):?><button class="chip-b<?=$k==='all'?' on':''?>" data-f="<?=$k?>"><?=$l?></button><?php endforeach;?></div>
<div class="grid proj"><?php foreach($projects as $p):?>
<article class="card tilt proj-c<?=$p['featured']?' feat':''?>" data-cat="<?=e($p['category'])?>" tabindex="0" role="button" aria-label="Open <?=e($p['title'])?>"
 data-t="<?=e($p['title'])?>" data-d="<?=e($p['description'])?>" data-tech="<?=e($p['technologies'])?>" data-live="<?=e($p['live_url'])?>" data-gh="<?=e($p['github_url'])?>">
<div class="thumb"><?php if($p['image']):?><img loading="lazy" src="assets/uploads/<?=e($p['image'])?>" alt="<?=e($p['title'])?> screenshot"><?php else:?><span><?=e(strtoupper(substr($p['title'],0,2)))?></span><?php endif;?></div>
<h3><?=e($p['title'])?></h3><p><?=e($p['description'])?></p>
<ul class="tags"><?php foreach(array_filter(array_map('trim',explode(',',$p['technologies']))) as $t):?><li><?=e($t)?></li><?php endforeach;?></ul></article><?php endforeach;?></div></section>

<section id="testimonials" class="sec"><h2 class="rv">What clients say</h2><div class="slider" id="slider">
<?php foreach($tests as $i=>$t):?><figure class="slide<?=$i?'':' on'?>"><blockquote><?=e($t['review'])?></blockquote><figcaption><b><?=e($t['name'])?></b> <?=e($t['position'])?> <span aria-label="<?=$t['rating']?> out of 5"><?=str_repeat('★',$t['rating'])?></span></figcaption></figure><?php endforeach;?>
<div class="sl-c"><button class="icon" id="prev" aria-label="Previous review">‹</button><button class="icon" id="next" aria-label="Next review">›</button></div></div></section>

<section id="blog" class="sec"><h2 class="rv">From the blog</h2><div class="grid">
<?php foreach($posts as $b):?><article class="card tilt rv"><small><?=e($b['category'])?> · <?=date('M j, Y',strtotime($b['created_at']))?></small><h3><?=e($b['title'])?></h3><p><?=e(mb_substr($b['content'],0,120))?></p></article><?php endforeach;?></div></section>

<section id="contact" class="sec"><div class="contact"><div><h2>Let's build something great.</h2><p class="sub">Tell me what you need. I reply within one working day.</p></div>
<form method="post" action="#contact" novalidate><?php if($sent):?><p class="ok" role="status">Message sent. Thank you, I will reply soon.</p><?php endif;?>
<?php if($errors):?><ul class="error" role="alert"><?php foreach($errors as $er):?><li><?=e($er)?></li><?php endforeach;?></ul><?php endif;?>
<?=csrf_field()?><label>Name<input name="name" required value="<?=e($old['name'])?>"></label><label>Email<input type="email" name="email" required value="<?=e($old['email'])?>"></label>
<label>Subject<input name="subject" required value="<?=e($old['subject'])?>"></label><label>Message<textarea name="message" rows="5" required><?=e($old['message'])?></textarea></label>
<button class="btn mag" type="submit">Send message</button></form></div></section></main>

<div class="modal" id="modal" role="dialog" aria-modal="true" aria-labelledby="m-t" hidden><div class="m-box"><button class="icon" id="m-x" aria-label="Close">✕</button>
<h3 id="m-t"></h3><p id="m-d"></p><ul class="tags" id="m-tech"></ul><div class="cta"><a class="btn" id="m-live" target="_blank" rel="noopener">Live site</a><a class="btn ghost" id="m-gh" target="_blank" rel="noopener">GitHub</a></div></div></div>
<footer class="foot">© <?=date('Y')?> Nexa Digital · <a href="admin/login.php">Admin</a></footer>
<script src="assets/js/script.js"></script></body></html>
