<?php 
error_reporting(0);

const DEVELOPER_HOST = "192.168.0.6";
const ADMINLAN_HOST = "192.168.0.10";
if (filter_input(INPUT_SERVER, "HTTP_HOST") == "localhost" || filter_input(INPUT_SERVER, "HTTP_HOST") == constant("DEVELOPER_HOST")) {
    $CURRENT_WAN = file_get_contents("http://bot.whatismyipaddress.com");
} else {
    $CURRENT_WAN = "nowan";
}
$UNIKE_PHRASE = "Hadston_E_Kelly";

require_once 'mcv.class.php';
$mcv = new mcv();
$mcv->siteName = '<strong class="brand-me-1">h<strong class="brand-me-2">4</strong>mn</strong><em class="brand-me-3">soft</em>';

$mcv->stylize("http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css");
$mcv->stylize("portifolio.css");

//navegação

$mcv->addNavItem("xp", "Currículo", "curr.html", ("xp" == $mcv->controller())?true:false);
$mcv->addNavItem("blog", "Blog", "blog.html", ("blog" == $mcv->controller())?true:false);
$mcv->addNavItem("callme", "Contato", "contato.html", ("callme" == $mcv->controller())?true:false);
if (filter_input(INPUT_SERVER, "HTTP_HOST") == "localhost" || filter_input(INPUT_SERVER, "HTTP_HOST") == constant("DEVELOPER_HOST")) {
    $mcv->addNavItem("jobs", "Portfólio", "portifolio.php", ("jobs" == $mcv->controller())?true:false);
    $mcv->addNavItem("gta", "GTA Cheats", "gta.html", ("gta" == $mcv->controller())?true:false);    
    $mcv->addNavItem("stream", "DualStream", "duo-stream.html", ("stream" == $mcv->controller())?true:false);
    $mcv->addNavItem("processa-velha", "Desafio Processa", "desafioProcessa.php", ("processa-velha" == $mcv->controller())?true:false);
}
$mcv->renderize();

/**
echo "<pre>";
print_r(filter_input_array(INPUT_SERVER));
echo "</pre>";
/**
**/