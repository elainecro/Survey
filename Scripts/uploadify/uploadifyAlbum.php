<?php
require_once('../../conexao.php'); 
include_once('../../util.php');
include_once('canvas.php');

$idAlbum = $_GET['idAlbum'];
if (!empty($_FILES)) {
    $nomeFoto   = $_FILES['Filedata']['name'];                            //pega o nome da foto
	$ext        = substr($nomeFoto, -4);                                  //pega a extensão da foto
	$nomeFoto   = str_replace($ext, '', $nomeFoto).date("dmYHis").$ext;   //inclui no nome da foto a data e hora atual e extensao
	$nomeFoto   = tiraEspacos(tiraAcentos($nomeFoto));                    //retira todos os acentos e espaços do nome da foto para evitar erros de salvamento
	$tempFile   = $_FILES['Filedata']['tmp_name'];                        //pega a foto
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';  //define o caminho da pasta para salvar a foto redimensionada
	$targetThumb = $targetPath.'Thumbs/';                                 //define o caminho da pasta para salvar o thumb
	$targetFile =  str_replace('//','/',$targetPath) . $nomeFoto;         
	$thumb = str_replace('//','/',$targetThumb) . 'thumb_'.$nomeFoto; 
		
	$adicionar = mysql_query("INSERT INTO fotos (idAlbum, strCaminhoFoto, bCapa) VALUES ('$idAlbum', '$nomeFoto', '0')");//gravo na tabela do bd
	
	set_time_limit(0);
	move_uploaded_file($tempFile,$targetFile); //move a imagem para a pasta de Fotos
	echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	
	$img = new canvas($targetFile);
	$img->carrega($targetFile) //abre a foto para ser redimensionada
		->redimensiona( 740, 580, 'proporcional') //define que a imagem salva terá o tamanho de 740 x 580
		->grava($targetFile); //redimensiona a imagem e grava no destino
	
	$img->carrega($targetFile) //abre a foto para incluir a marca d'agua
		->marca('../../Imagens/Logo_Pequeno.png', 'baixo', 'direita') //informa o caminho da imagem que será a marca d'agua
		->grava($targetFile); //Insere marca d'agua na foto que foi salva
		
	$img = new canvas($targetFile);
	$img->carrega($targetFile) //abre a foto para ser redimenionada
		->redimensiona(220, 220, 'proporcional') //define que o thum terá o tamanho de 220 x 220
		->grava($thumb); //redimensiosa a imagem e grava no destino
}

?>