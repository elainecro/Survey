<?php
include('conn.php');



/*****************************************************************
		 Converte data do padrão nacional para padrão db
*****************************************************************/
function data2date($data)
{
	if (strlen($data) > 10)
	{
		$dia = substr($data,0,2);
		$mes = substr($data,3,2);
		$ano = substr($data,6,4);
		$hora = substr($data,11,5);
		$date = "$ano-$mes-$dia $hora";
	}
	else
	{
		$dia = substr($data,0,2);
		$mes = substr($data,3,2);
		$ano = substr($data,6,4);
		$date = "$ano-$mes-$dia";
	}
	if ($date=='0000-00-00'){
		return '';
	} else {
		return $date;
	}
}
//------------------------------||--------------------------------



/*****************************************************************
		  Converte data do padrão db para padrão nacional
*****************************************************************/
function date2data($date)
{
	if (strlen($date) > 10)
	{
		$dia = substr($date,8,2);
		$mes = substr($date,5,2);
		$ano = substr($date,0,4);
		$hora = substr($date,11,5);
		$data = "$dia/$mes/$ano $hora";
	}
	else
	{
		$dia = substr($date,8,2);
		$mes = substr($date,5,2);
		$ano = substr($date,0,4);
		$data = "$dia/$mes/$ano";
	}
	if ($data=='00/00/0000'){
		return '';
	} else {
		return $data;
	}
}

//------------------------------||--------------------------------

/**
* Criado em 18/03/2011 por Elaine Cristina
* Idéia de organizar algumas funções úteis e utilizáveis em qualquer lugar do sistema
**/

function tiraAcentos($texto)
{
	$texto = strtolower($texto);
	$texto = trim($texto);
	$texto = str_replace(" ", "", $texto);
	$texto = str_replace("/", "", $texto);
	$texto = str_replace(",", "", $texto);	
	$texto = str_replace("!", "", $texto);
	$texto = str_replace("@", "", $texto);
	$texto = str_replace("#", "", $texto);
	$texto = str_replace("$", "", $texto);
	$texto = str_replace("%", "", $texto);
	$texto = str_replace("¨", "", $texto);
	$texto = str_replace("&", "", $texto);
	$texto = str_replace("*", "", $texto);
	$texto = str_replace("(", "", $texto);
	$texto = str_replace(")", "", $texto);
	$texto = str_replace("-", "", $texto);
	$texto = str_replace("=", "", $texto);
	$texto = str_replace("+", "", $texto);
	$texto = str_replace("{", "", $texto);
	$texto = str_replace("[", "", $texto);
	$texto = str_replace("]", "", $texto);
	$texto = str_replace("}", "", $texto);
	$texto = str_replace("?", "", $texto);
	$texto = str_replace("<", "", $texto);
	$texto = str_replace(">", "", $texto);
	$texto = str_replace("|", "", $texto);
			
	$acentos = array(
		'A' => '/Á|À|Â|Ã|Ä/',
		'a' => '/á|à|â|ã|ä/',
		'C' => '/Ç/',
		'c' => '/ç/',
		'E' => '/É|È|Ê|Ë/',
		'e' => '/é|è|ê|ë/',
		'I' => '/Í|Ì|Î|Ï/',
		'i' => '/í|ì|î|ï/',
		'N' => '/Ñ/',
		'n' => '/ñ/',
		'O' => '/Ó|Ò|Ô|Õ|Ö/',
		'o' => '/ó|ò|ô|õ|ö/',
		'U' => '/Ú|Ù|Û|Ü/',
		'u' => '/ú|ù|û|ü/',
		'Y' => '/Ý/',
		'y' => '/ý|ÿ/',
		'a.' => '/ª/',
		'o.' => '/º/');

   return preg_replace($acentos,
                       array_keys($acentos),
                       $texto);
}

function tiraEspacos($texto)
{
	$texto = str_replace(' ', '', $texto);
	return $texto;
}

function primeiraMaiuscula($palavra)
{
	return ucfirst($palavra);
}

function criaTabela($novaTabela){
	$sql = "CREATE TABLE $novaTabela (
				cod int(4) not null auto_increment,
				primary key(cod)
			)ENGINE=INNODB";
	mysql_query($sql);
}
/**
 * Função fazerLogin - realiza login corretamente e registra variaveis e sessoes que serão utilizadas por todo o sistema
 * @param date $login
 * @param date $senha
 */
function fazerLogin($login, $senha, $refaz='nao'){
	$erro = false;
	
	//limpando sessao
	unset($_SESSION["login"]);
	unset($_SESSION["nome"]);
	unset($_SESSION["acesso"]);
	unset($_SESSION["idempresa"]);
	unset($_SESSION['codusuario']);
	
	if ((settype($login, 'string')) and (settype($senha, 'string'))){
		$login = mysql_real_escape_string($login);
		$login = addslashes($login);
		$login = htmlspecialchars($login);
		$senha = mysql_real_escape_string($senha);
		$senha = addslashes($senha);
		$senha = htmlspecialchars($senha);
		$senha = md5($senha);
		
		$query = "SELECT *
				  FROM usuarios
				  WHERE strLogin = '$login' 
				    AND strSenha = '$senha' 
					AND bAtivo = 1";
		$result = mysql_query($query);
		if(mysql_num_rows($result)==1){
			$aux = mysql_fetch_array($result);
			
			$user      = $aux['strLogin'];
			$nome      = $aux['strNome'];
			$acesso    = $aux['chrAcesso'];
			$idempresa = $aux['codEmpresa'];
			$codusuario = $aux['codUsuario'];
			
			$_SESSION["login"]     = $user;
			$_SESSION["nome"]      = $nome;
			$_SESSION["acesso"]    = $acesso;
			$_SESSION["idempresa"] = $idempresa;
			$_SESSION['codusuario'] = $codusuario;
			
			$nome_usuario = $nome;
			die('<script>location.href = "principal.php";</script>');
		}
	}
	$erro = true;
	return $erro;
}



function diffDate($d1, $d2, $type='', $sep='-'){ 
	$d1 = explode($sep, $d1);
	$d2 = explode($sep, $d2);
	switch ($type) {
		case 'A':
			$X = 31536000; //365 dias
			break;
		case 'S';
			$X = 15552000; //180 dias
			break;
		case 'T';
			$X = 7776000; //90 dias
			break;
		case 'B';
			$X = 5184000; //60 dias
			break;
		case 'M':
			$X = 2592000; //30dias
			break;
		case 'W':
			$X = 604800; //7 dias 
			break;
		case 'D':
			$X = 86400;
			break;
		case 'H':
			$X = 3600;
			break;
		case 'MI':
			$X = 60;
			break;
		default:
			$X = 1;
			break;
	}

	return floor( ( ( mktime(0, 0, 0, $d2[1], $d2[2], $d2[0] ) - 
		              mktime(0, 0, 0, $d1[1], $d1[2], $d1[0] ) ) / $X ) );
}

?>