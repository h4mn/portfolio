<?php
/* 
 * Script desenvolvido como parte da solução ao desafio da empresa 
 * Processa Sistemas http://processasistemas.com.br/noticia/21
 * 
 * O desafio consiste em implementar um código que verifique o ganhador
 * entre 'x' e 'o' numa determinada sequência de jogadas do jogo da velha.
 * 
 * Hadston Nunes 201602171103
 */
class vencedorVelha {
    private $controller, $action, $params;
    private $vitoria = array(
        "000000111", //posições da linha 1
        "000111000", //posições da linha 2
        "111000000", //posições da linha 3
        "001001001", //posições da coluna 1
        "010010010", //posições da coluna 1
        "100100100", //posições da coluna 1
        "100010100", //posições da diagonal topo/esquerdo -> baixo/direito
        "001010001", //posições da diagonal topo/direito -> baixo/esquerdo
    );
    public function vencedorVelha() {
        $path = trim(parse_url(filter_input(INPUT_SERVER, "REQUEST_URI"), PHP_URL_PATH), "/");
        list($controller, $action, $params) = \explode("/", $path, 3);
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }
    public function header(){
        return file_get_contents("desafioProcessa.html");
    }
    public function controllerOk(){
        if ($this->controller == "processa-velha" && $this->action == "") {
            return true;
        }
    }
    public function actionOk(){
        if ($this->controller == "processa-velha" && $this->action == "analisar") {
            return true;
        }
    }
    public function analisa(){
        $iGanhador = (string) "0";
        $partida = urldecode($this->params);
        $mapa = $this->toBitmap($partida);
        
        //echo $this->controller, '/', $this->action, '/', $this->params;
        
        foreach ($mapa as $ganhador => $sequencia) {
            foreach ($this->vitoria as $possibilidade) {
                /*
                 * Verificar a comparação bit a bit
                 */
                $match = bindec($sequencia) & bindec($possibilidade);
                /*
                 * Não está funcionando
                 */
                if ($match == bindec($possibilidade)) {
                    if ($ganhador == "x") {
                        $iGanhador = "-1";
                        break;
                    } else {
                        $iGanhador = "1";
                        break;
                    }
                }
            }
        }
        echo $iGanhador;
        exit();
    }
    private function toBitmap($sequencia){
        $mapa = array();
        foreach (explode(" ", $sequencia) as $jogada) {
            list($jogador, $coordenada) = explode(":", $jogada);
            list($coluna, $linha) = explode(",", $coordenada, 2);
            switch (true) {
                case ($linha == 1 && $coluna == 1): $mapa[$jogador] = $mapa[$jogador] + "000000001"; break;
                case ($linha == 1 && $coluna == 2): $mapa[$jogador] = $mapa[$jogador] + "000000010"; break;
                case ($linha == 1 && $coluna == 3): $mapa[$jogador] = $mapa[$jogador] + "000000100"; break;
                case ($linha == 2 && $coluna == 1): $mapa[$jogador] = $mapa[$jogador] + "000001000"; break;
                case ($linha == 2 && $coluna == 2): $mapa[$jogador] = $mapa[$jogador] + "000010000"; break;
                case ($linha == 2 && $coluna == 3): $mapa[$jogador] = $mapa[$jogador] + "000100000"; break;
                case ($linha == 3 && $coluna == 1): $mapa[$jogador] = $mapa[$jogador] + "001000000"; break;
                case ($linha == 3 && $coluna == 2): $mapa[$jogador] = $mapa[$jogador] + "010000000"; break;
                case ($linha == 3 && $coluna == 3): $mapa[$jogador] = $mapa[$jogador] + "100000000"; break;
                default:
                    break;
            }
        }
        return $mapa;
    }
}