<?php
include_once('canvas.php');
require_once('../../inc/util.php');

$string = $_GET['id'];
$strings = explode(',', $string);

$codusuario = $strings[0];
$strTela = $strings[1];
$codPropriedade = $strings[2];
$codCadastro = $strings[3];

$qryEmp = mysql_query("SELECT codEmpresa FROM usuarios WHERE codUsuario = $codusuario");
$codEmpresa = mysql_result($qryEmp, 0, 0);

if (!empty($_FILES)) {
    $nomeArq   = $_FILES['Filedata']['name']; //pega o nome do arquivo
    $fim   = substr(strrev($nomeArq),0,1);
    if ($fim=='x'){
		$ext   = substr($nomeArq, -5);
	} else {
		$ext   = substr($nomeArq, -4); //pega a extensão do arquivo
	}
	$nomeArq   = $codusuario.'_'.str_replace($ext, '', $nomeArq).'_'.date("dmYHis").$ext;   //inclui no nome da foto a data e hora atual e extensao
	$nomeArq   = tiraEspacos(tiraAcentos($nomeArq));                    //retira todos os acentos e espaços do nome da foto para evitar erros de salvamento
	$tempFile   = $_FILES['Filedata']['tmp_name'];                        //pega a foto
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';  //define o caminho da pasta para salvar a foto redimensionada
	$targetPath .= $codEmpresa.'/';
	if (!is_dir($targetPath)){
		mkdir($targetPath);
	}
	//$targetThumb = $targetPath.'Thumbs/';                                 //define o caminho da pasta para salvar o thumb
	$targetFile =  str_replace('//','/',$targetPath) . $nomeArq;         
	//$thumb = str_replace('//','/',$targetThumb) . 'thumb_'.$nomeArq; 
		

	if ($strTela == 'Pessoas'){		
		$qry = "INSERT INTO propriedades_pessoas (codPessoa, codPropriedade, strValor, codUsuarioCad)
					VALUES ('$codCadastro', '$codPropriedade', '$nomeArq', '$codusuario')";
		$rst = mysql_query($qry);

	} else if ($strTela == 'Documentos'){

	}
	
	set_time_limit(0);
	move_uploaded_file($tempFile,$targetFile); //move o arquivo para a pasta de arquivos
	echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	
	/*$img = new canvas($targetFile);
	$img->carrega($targetFile) //abre a foto para ser redimensionada
		->redimensiona( 740, 580, 'proporcional') //define que a imagem salva terá o tamanho de 740 x 580
		->grava($targetFile); //redimensiona a imagem e grava no destino
	
	$img->carrega($targetFile) //abre a foto para incluir a marca d'agua
		->marca('../../Imagens/Logo_Pequeno.png', 'baixo', 'direita') //informa o caminho da imagem que será a marca d'agua
		->grava($targetFile); //Insere marca d'agua na foto que foi salva
		
	$img = new canvas($targetFile);
	$img->carrega($targetFile) //abre a foto para ser redimenionada
		->redimensiona(220, 220, 'proporcional') //define que o thum terá o tamanho de 220 x 220
		->grava($thumb); //redimensiosa a imagem e grava no destino*/
}

?>