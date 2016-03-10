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
}
