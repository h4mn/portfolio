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
    public $mysql_conn_port = 3306;
    public $mysql_conn_username = 'user';
    public $mysql_conn_password = '***';
    public $mysql_conn_dbname = 'portfolio';
    public $msg_erro;
    public $mysql_table = 'jobs';
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
                        URL do Site:
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
        $this->mysql_conn_link = mysql_connect(
                $this->mysql_conn_server.':'.$this->mysql_conn_port, 
                $this->mysql_conn_username, 
                $this->mysql_conn_password
        ) or die('Erro de conexão MySQL');
        //echo $this->mysql_conn_server.':'.$this->mysql_conn_port;
        //exit;
        mysql_selectdb($this->mysql_conn_dbname, $this->mysql_conn_link);
        return $this->mysql_conn_link;
    }
    public function dbConnectionClose () {
        if ($this->mysql_conn_link) {
            mysql_close($this->mysql_conn_link);
        }
        $this->mysql_conn_link = false;
    }
    public function Jobs ($jobId = 'All') {
        $this->dbConnection();
        if ($jobId == 'All') {
            $mysql_result = mysql_query("SELECT * FROM jobs;");
        } else {
            $mysql_result = mysql_query("SELECT * FROM jobs WHERE idjobs = `$jobId`;");
        }
        if (!$mysql_result) {
            echo 'Erro SQL: '. mysql_error();
            exit;
        }
        if (mysql_num_rows($mysql_result) == 0) {
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
    public function visao_teste () {
        $retorno = '<table class="table table-stripped">';
        $mysql_result = $this->Jobs();
        while ($linha = mysql_fetch_assoc($mysql_result)) {
            $retorno = $retorno . "<tr>";
            $retorno = $retorno . "<td>{$linha['idjobs']}</td>";
            $retorno = $retorno . "<td>{$linha['jobsname']}</td>";
            $retorno = $retorno . "<td>{$linha['jobsclient']}</td>";
            $retorno = $retorno . "<td>{$linha['jobsfilepreviewport']}</td>";
            $retorno = $retorno . "<td>{$linha['jobsfilepreviewportmini']}</td>";
            $retorno = $retorno . "<td>{$linha['jobsdesc']}</td>";
            $retorno = $retorno . "<td>{$linha['jobsdateinit']}</td>";
            $retorno = $retorno . "<td>{$linha['jobsusedtechs']}</td>";
            $retorno = $retorno . "</tr>";
        }
        $retorno = $retorno . '</table>';
        return $retorno;
    }
    public function visao () {
        $visao = $this->table_open;
        $jobs = $this->Jobs();
        while ($linha = mysql_fetch_assoc($jobs)) {
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