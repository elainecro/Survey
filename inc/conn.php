<?php

function db_connect()
{
    $connect = mysql_connect("localhost","root","") or die ("Impossível estabelecer conexão com o servidor de banco de dados");
	mysql_select_db("sistemasurvey") or die ("Impossivel estabelecer conexao com o banco de dados");
	
	return $connect;
}

$conn = db_connect();

if(isset($_GET)){
	foreach($_GET as $chave => $valor){
		$$chave = $valor;
	}
}

if(isset($_POST)){
	foreach($_POST as $chave => $valor){
		$$chave = $valor;
	}
}
?>

