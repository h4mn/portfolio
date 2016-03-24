<?php
/*
 * Camada de negócio para acesso ao db em busca dos jobs cadastrados e
 * faz o retorno formatado dos dados para o template
 */

//classe portfolio
class portfolio {
//propriedades
/*
 * string-conection
 * db-name
 * tb-name
 * campos{}
 * path-preview
 * layout
 * mensagem de erro
 */
    private $mysql_conn_link = false;
    public $mysql_conn_server = '127.0.0.1';
    public $mysql_conn_username = 'user';
    public $mysql_conn_password = '***';
    public $mysql_conn_dbname = 'portfolio';
    public $msg_erro;
    public $mysql_table = 'jobs';
    private $mysql_initerror;
    
    private $variavel_do_vinny;
    private $table_open = <<<END

    <table class="table table-striped">
    	<tbody>
END;
    private $table_close = <<<END

        </tbody>
    </table>
END;
    private $line_open = <<<END

        	<tr>
END;
    private $line_close = <<<END

        	</tr>
END;
    private $thumb = <<<END

            	<td class="col-sm-6 [text-align]">
                    <img class="img-thumbnail" src="[img]">
                    <p>
                        Site:
                        <a href="[site-url]" class="label label-default">[site-url]</a>
                    </p>
                </td>
END;
    private $desc = <<<END

            	<td class="col-sm-6 [text-align]">
                    <div class="col-desc-container">
                        <div class="btn btn-default btn-block [text-align]" data-toggle="collapse" data-target="#detalhes-[jobid]">
                            <h1 class="col-desc-title [text-align]">
                                [titulo]
                                <small class="col-desc-subtitle">[subtitulo]</small>
                            </h1>
                            <p class="col-desc-body [text-align]">[desc]</p>
                            <div id="detalhes-[jobid]" class="collapse panel panel-default">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr class="row">
                                            <td class="col-sm-4 text-right">Cliente:</td>
                                            <td class="col-sm-8 text-left">[cliente]</td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-sm-4 text-right">Data do projeto:</td>
                                            <td class="col-sm-8 text-left">[data]</td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-sm-4 text-right">Tecnologias utilizadas:</td>
                                            <td class="col-sm-8 text-left">
                                                <strong>[tecnologias]</strong>
                                            </td>
                                        </tr>
                                        <tr class="row">
                                            <td class="col-sm-4 text-right">Código fonte:</td>
                                            <td class="col-sm-8 text-left">
                                                <a href="[fonte]">[fonte]</a>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
END;

//funções de dados
/*
 * acessar db
 * adicionar registro
 * editar especifico
 * excluir especifico
 * recuperar especifico
 * recuperar todos
 */
    public function dbConnection () {
        if ($this->mysql_conn_link) {
            return $this->mysql_conn_link;
        }
        
        $this->mysql_conn_link = mysqli_connect(
                $this->mysql_conn_server, 
                $this->mysql_conn_username, 
                $this->mysql_conn_password
        );
        
        mysqli_select_db($this->mysql_conn_link, $this->mysql_conn_dbname);

        return $this->mysql_conn_link;
    }
    public function dbConnectionClose () {
        if ($this->mysql_conn_link) {
            mysqli_close($this->mysql_conn_link);
        }
        $this->mysql_conn_link = false;
    }
    public function Jobs_old ($jobId = 'All') {
        $link = $this->dbConnection();
        if ($jobId == 'All') {
            $mysql_result = mysqli_query($link, "SELECT * FROM jobs;");
        } else {
            $mysql_result = mysqli_query($link, "SELECT * FROM jobs WHERE idjobs = `$jobId`;");
        }
        if (!$mysql_result) {
            echo 'Erro SQL: '. mysqli_error($link);
            exit;
        }

        if (mysqli_num_rows($mysql_result) == 0) {
            echo 'Tabela de Jobs vazia';
            exit;
        }
        return $mysql_result;
    }
//funções de arquivo
/*
 * determinar caminho
 * verificar existencia
 */
// funções de exibição
/*
 * envia elemento
 * envia visão
 */
    
    /**
     * Função que envia a visão para a saida
     * 
     * @return visao
     */
    public function visao () {
        $visao = $this->admin();
        $paridade = 1;
        $visao .= $this->table_open;
        
        $jobs = $this->Jobs();
        
        while ($linha = mysqli_fetch_assoc($jobs)) {
            $paridade += 1;
            $visao .= $this->line_open;
            $campos = array("[titulo]", "[subtitulo]", "[desc]", "[cliente]", "[data]", "[tecnologias]", "[fonte]");
            $dados = array(
                utf8_encode($linha['jobsname']),
                utf8_encode($linha['jobsnameaka']),
                utf8_encode($linha['jobsdesc']),
                utf8_encode($linha['jobsclient']),
                date('d/m/Y', strtotime($linha['jobsdateinit'])),
                $linha['jobsusedtechs'],
                $linha['jobsurlsource']
            );
            $thumb_campos = array("[img]", "[site-url]");
            $thumb_dados = array($linha['jobsfilepreviewportmini'], $linha['jobsurlsite']);
            $desc_id = str_replace('[jobid]', $linha['idjobs'], $this->desc);
            if ($paridade % 2 == 0) {
                $desc_align = str_replace('[text-align]', 'text-right', $desc_id);
                $visao .= str_replace($campos, $dados, $desc_align);
                $visao .= str_replace($thumb_campos, $thumb_dados, $this->thumb);
            } else {
                $thumb = str_replace('[text-align]', 'text-right', $this->thumb);
                $visao .= str_replace($thumb_campos, $thumb_dados, $thumb);
                $desc_align = str_replace('[text-align]', 'text-left', $desc_id);
                $visao .= str_replace($campos, $dados, $desc_align);
            }
            $visao .= $this->line_close;
        }
        
        $visao .= $this->table_close;
        return $visao;
    }
    
    /**
     * Função que retorna o elemento para a chamada side-client
     * 
     * @return elemento
     */
    public function elemento () {
        $elemento = "";
        
        // bloco aqui
        
        return $elemento;
    }
    
    /**
     * Função que verifica acesso e retorna pagina administrativa para a saida
     * 
     * @return admin
     */
    public function admin () {
        $admin = "";
        /**
         * Uso da API GitHub via GitHubClient-PHP
         *
        require_once __DIR__ . '/clientes/github-php-client/client/GitHubClient.php';
        $github = new GitHubClient();
        $github->setCredentials('h4mn-bot', 'foxBOT2016');
        //$github->setPage();
        //$github->setPageSize(2);
        $commits = $github->repos->commits->listCommitsOnRepository('h4mn', 'portfolio');
        //$commits = $github->repos->commits->listCommitsOnRepository($owner, $repo);

        echo "Count: " . count($commits) . "\n";
        foreach($commits as $commit)
        {
            $admin .= get_class($commit) . " - Sha: " . $commit->getSha() . "\n";
        }
        /*
         * 
         */
        /*
         * Uso da API GitHub via cURL e JSON
         */
        $this->curl_request("https://api.github.com -u h4mn-bot:foxBOT2016");
        //$this->curl_request("https://api.github.com -u h4mn:hamnGH2015");
        $status = $this->curl_request("https://api.github.com/repos/h4mn/portfolio/commits", "user-agent");
        /**
         * 
         */

        $admin .= '<br>'.$status['response'].'<br>';
        //$admin .= pre_array($status);
        //$admin .= $this->pre_array(json_decode($resultado, true));

        $this->action();
        if ($this->isAdmin()) {
            $admin .= <<<END

    <div class="panel panel-default">
        <div class="container-fluid panel-heading">
            <div class="navbar-header">
                <a href="/jobs" class="navbar-brand">Área Administrativa</a>
            </div>
            <ul class="nav navbar-nav">
                <li><a href="/jobs/add">Novo Job</a></li>
                <li><a href="/jobs/edit">Editar Job</a></li>
            </ul>
        </div>
    </div>
    $this->variavel_do_vinny
END;
        }
        return $admin;
    }
    
    private function action () {
        $path = trim(parse_url(filter_input(INPUT_SERVER, "REQUEST_URI"), PHP_URL_PATH), "/");
        list($controller, $action, $parameter) = explode("/", $path, 3);
        if ($parameter) {}

        if ($controller == "jobs" && $action == "add") {
            
        }

        if ($controller == "jobs" && $action == "githubbing") {
            /**
             * Início - Easter Egg do Vinny
             */
            $this->variavel_do_vinny = <<<END

    <div class="panel panel-danger">
        <div class="panel-heading"><h4>Lição Programação nº2</h4></div>
        <div class="panel-body">
            <h4>Vinny esteve aqui e aprendeu a lição.</h4>
            <p>Teste de alteração do repositório portfolio no GitHub</p>
        </div>
    </div>

END;
            /**
             * Fim - Easter Egg do Vinny
             */
        }
        
        return $controller;
    }
    
    private function Jobs ($request = 'show', $id = 'all') {
        $link = $this->dbConnection();

        switch ($request) {
            case 'show':
                if ($id == 'all') {
                    $result = mysqli_query($link, "SELECT * FROM jobs;");
                } else {
                    $result = mysqli_query($link, "SELECT * FROM jobs WHERE idjobs = `$id`;");
                }
                if (!$result) {
                    return array('Mensagem' => 'Erro SQL', 'MySQL Error' => mysqli_error($link));
                }
                if (mysqli_num_rows($result) == 0) {
                    return array('Mensagem' => 'Tabela de Jobs vazia', 'MySQL Error' => mysqli_error($link));
                }

                break;
            case 'add':
                
                break;
            case 'edit':
                break;
            case 'delete':
                break;
        }
        return $result;
    }
    
    /**
     * Função retorna verdadeiro se a assinatura estiver correta
     * @return bolean
     */
    private function isAdmin() {
        $isAdmin = false;
        global $UNIKE_PHRASE;
        $signature_file = filter_input(INPUT_SERVER, "DOCUMENT_ROOT").'/chaves/assinatura.rsa';
        if (file_exists($signature_file)) {
            $file = fopen($signature_file, "r");
            $key = md5($UNIKE_PHRASE.':'.filter_input(INPUT_SERVER, "REMOTE_ADDR"));
            if (($key == md5($UNIKE_PHRASE.':'.constant("ADMINLAN_HOST"))) || ($key == fgets($file))) {
                $isAdmin = true;
            }
            fclose($file);
        } else {
            // Pedir o upload do arquivo
        }
        return $isAdmin;
    }
    public function curl_request($url,$headers = "") {
        $cURL = curl_init(); // Instancia CURL criada
        curl_setopt($cURL, CURLOPT_URL, $url); //URL definida
        curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1); //Retorna a transferencia em string
        if ($headers != "") {
            curl_setopt($cURL, CURLOPT_HEADER, "User-Agent: h4mn-portfolio");
        }
        $response = curl_exec($cURL); //Imprimindo a saida
        $status = curl_getinfo($cURL, CURLINFO_HTTP_CODE); //Verificar status
        curl_close($cURL); //Fecha a instancia
        return array(
            'status' => $status,
            'response' => $response
        );
    }
    
    public function pre_array ($msg) {
        $return = '<pre>';
        $return .= $msg;
        $return .= '</pre>';
        return $return;
    }
}
    
/**
 * Testes

$portfolio = new portfolio();
$portfolio->mysql_conn_username = "portfolio";
$portfolio->mysql_conn_password = "workbench";
echo $portfolio->visao();
$portfolio->dbConnectionClose();
die();

 * 
 */