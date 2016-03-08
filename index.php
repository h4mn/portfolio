<?php 
error_reporting(0);

require_once 'mcv.class.php';
$mcv = new mcv();
$mcv->siteName = '<strong class="brand-me-1">h<strong class="brand-me-2">4</strong>mn</strong><em class="brand-me-3">soft</em>';

$mcv->stylize("http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css");
$mcv->stylize("portifolio.css");

//navegação

$mcv->addNavItem("xp", "Currículo", "curr.html", ("xp" == $mcv->controller())?true:false);
$mcv->addNavItem("blog", "Blog", "blog.html", ("blog" == $mcv->controller())?true:false);
$mcv->addNavItem("callme", "Contato", "contato.html", ("callme" == $mcv->controller())?true:false);
if (filter_input(INPUT_SERVER, "HTTP_HOST") == "localhost" || filter_input(INPUT_SERVER, "HTTP_HOST") == "192.168.1.11"){
    $mcv->addNavItem("jobs", "Portfólio", "portifolio.html", ("jobs" == $mcv->controller())?true:false);
    $mcv->addNavItem("gta", "GTA Cheats", "gta.html", ("gta" == $mcv->controller())?true:false);    
    $mcv->addNavItem("stream", "DualStream", "duo-stream.html", ("stream" == $mcv->controller())?true:false);
    $mcv->addNavItem("processa-velha", "Desafio Processa", "desafioProcessa.php", ("processa-velha" == $mcv->controller())?true:false);
}
$mcv->renderize();
?>