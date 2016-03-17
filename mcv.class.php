<?php
/**
 * Description of mcv
 *
 * @author Hadston Nunes
 */
class mcv {
    public $siteName;
    private $saida;
    private $stylesheets;
    private $navItems;
    private $views = array();
    private $activeView;
    private $globalNotice;
    function renderize () {
        if ($this->controller() == "jobs") {
            require('portfolio.php');
            $portfolio = new portfolio();
            $portfolio->mysql_conn_username = "portfolio";
            $portfolio->mysql_conn_password = "workbench";
            $this->activeView = $portfolio->visao();
            $portfolio->dbConnectionClose();
        } else if ($this->controller() == "face") {
            header('Location: http://'.$this->views[$this->controller()]);
        } else if ($this->controller() == "processa-velha") {
            require('desafioProcessa.php');            
            $velha = new vencedorVelha();
            if ($velha->controllerOk()){
                $this->activeView = $velha->header();
            } else if ($velha->actionOk()){
                $this->activeView = $velha->analisa('$params');
            }
        } else if ($this->controller() == "/") {
            $this->activeView = file_get_contents("home.html");
        } else {
            $this->activeView = file_get_contents($this->views[$this->controller()]);
        }
        $this->setGlobalNotice();
        $this->append('<!DOCTYPE html><html lang="pt-br">');
        $this->append('<head>');
        $this->append($this->meta_tags());
        $this->append($this->scripts());
        $this->append('<link rel="shortcut icon" href="/img/favicon.ico" />');
        $this->append($this->stylesheets);
        $this->append('</head>');
        $this->append("\r\n\r\n");
        $this->append('<div class="container">');
        if (filter_input(INPUT_SERVER, "HTTP_HOST") == "localhost"){
            $this->append($this->globalNotice);
        }
        $this->append('<nav class="navbar navbar-inverse"><ul>');
        $this->append('<div class="container-fluid">');
        $this->append('<div class="navbar-header">');
        $this->append('<a class="navbar-brand" href="/">' . $this->siteName . '</a>');
        $this->append('</div>');
        $this->append('<ul class="nav navbar-nav navbar-right">');
        $this->append($this->navItems);
        $this->append('</ul>');
        $this->append('</div>');
        $this->append('</nav>');
        $this->append("\r\n\r\n");
        $this->append($this->activeView);
        $this->append('<footer class="container-fluid bg-4 text-center">');
        $this->append('<p>&copy; 2016 - <strong>Hadston Nunes</strong>.');
        $this->append('Site sob licensa <a href="http://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR">Creative Commons</a>. ');
        $this->append('<em>Font Awesome </em> by Dave Gandy - <a href="http://fontawesome.io">http://fontawesome.io</a>. ');
        $this->append('<em>Simple Me</em> Theme by <a href="http://www.w3schools.com/bootstrap/default.asp"><img class="" src="http://www.w3schools.com/images/w3schools80x15.gif"></a></p>');
        $this->append('</footer>');
        $this->append('</div>');
        $this->append("\r\n\r\n".'</html>'."\r\n");

        echo $this->saida;
    }
    function append($item) {
        $this->saida = $this->saida . $item . "\r\n";
    }
    function meta_tags() {
        $saida = "\t".'<meta name="author" content="Hadston Nunes" />'."\r\n";
        $saida = $saida . "\t".'<meta http-equiv="content-type" content="text/html; charset=UTF-8" />'."\r\n";
        $saida = $saida . "\t".'<meta http-equiv="content-language" content="pt-br, en-US, fr" />'."\r\n";
        $saida = $saida . "\t".'<meta http-equiv="cache-control"   content="no-cache" />'."\r\n";
        $saida = $saida . "\t".'<meta http-equiv="pragma" content="no-cache" />'."\r\n";
        $saida = $saida . "\t".'<meta http-equiv="expires" content = "Sun, 7 feb 2016 12:00:00 GMT" />'."\r\n";
        $saida = $saida . "\t".'<meta name="description" content="Site pessoal de portifólio" />'."\r\n";
        $saida = $saida . "\t".'<meta name="keywords" content="portifólio" />'."\r\n";
        $saida = $saida . "\t".'<meta name="copyright" content="© 2004 tex   texin" />'."\r\n";
        return $saida;
    }
    function stylize($stylesheet) {
        $this->stylesheets = $this->stylesheets . "\t". '<link rel="stylesheet" type="text/css" href="'. $stylesheet . '">' . "\r\n";
    }
    function scripts() {
        //<!-- Latest compiled and minified CSS -->
        $saida = "\t".'<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">'."\r\n";
        //<!-- jQuery library -->
        $saida = $saida . "\t".'<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>'."\r\n";
        //<!-- Latest compiled JavaScript -->
        $saida = $saida . "\t".'<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>'."\r\n";
        $saida = $saida . "\t".'<script src="my_jquery.js"></script>'."\r\n";
        return $saida;
    }
    function addNavItem($tag, $caption, $view, $active = false){
        $class = "";
        if ($active == true) $class = "active";
        if ($tag == "face"){
            $this->navItems = $this->navItems ."\t".'<li class="'. $class .'"><a href="/'.$tag.'" target="_blank">'.$caption.'</a></li>';
            $this->views[$tag] = $view;
        } else {
            $this->navItems = $this->navItems ."\t".'<li class="'. $class .'"><a class="'. $class .'" href="/'.$tag.'">'.$caption.'</a></li>';
            $this->views[$tag] = $view;
        }
    }
    function controller(){
        $path = trim(parse_url(filter_input(INPUT_SERVER, "REQUEST_URI"), PHP_URL_PATH), "/");
        list($controller, $action, $params) = \explode("/", $path, 3);

        if ($controller == "") $controller = "/";
        return $controller;
    }
    function setGlobalNotice($msg=""){
        if ($msg == "") {
            $last_error = error_get_last();
            $errortype = $this->friendlyErrorType();
            if ($last_error['type'] >= 1) {
                $msg = '<div class="global-notice">';
                if ($last_error['type'] == 1) {
                    $class = "bg-danger";
                } else {
                    $class = "bg-warning";
                }
                $msg = $msg . '<div class="alert alert-warning"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'.
                    "<strong>".ucfirst($errortype[$last_error['type']]).":</strong> ".
                    $last_error['message'].
                    ' in: ' .
                    "<strong>...".substr($last_error['file'],-20)."</strong>".
                    ' on line: ' .
                    "<strong>".$last_error['line'].'</strong></div>';
                $msg = $msg .'</div>';
            }
        }
        $this->globalNotice = $msg;
    }
    function friendlyErrorType(){
        $errortype = array(
            E_ERROR           => 'error',
            E_WARNING         => 'warning',
            E_PARSE           => 'parsing error',
            E_NOTICE          => 'notice',
            E_CORE_ERROR      => 'core error',
            E_CORE_WARNING    => 'core warning',
            E_COMPILE_ERROR   => 'compile error',
            E_COMPILE_WARNING => 'compile warning',
            E_USER_ERROR      => 'user error',
            E_USER_WARNING    => 'user warning',
            E_USER_NOTICE     => 'user notice'
        );
        return $errortype;
    }
}