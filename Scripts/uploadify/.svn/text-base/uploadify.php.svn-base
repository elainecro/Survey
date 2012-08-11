<?php
require_once('../../Connections/conn.php'); 
include('../../home/inc/util.php');
include('canvas.php');

$idevent = $_GET['idevent'];
if (!empty($_FILES)) {
    $nomeFoto   = $_FILES['Filedata']['name'];
	$ext        = substr($nomeFoto, -4);
	$nomeFoto   = str_replace($ext, '', $nomeFoto).date("dmYHis").$ext;
	$nomeFoto   = tiraEspacos(tiraAcentos($nomeFoto));
	$tempFile   = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$targetThumb = $targetPath.'thumbs/';
	$targetFile =  str_replace('//','/',$targetPath) . $nomeFoto;
	$thumb = str_replace('//','/',$targetThumb) . $nomeFoto;
		
	$adicionar = mysql_query("INSERT INTO tbl_fotos (idevent, img, texto) VALUES ('$idevent', '$nomeFoto', '$nomeFoto')");
	
	move_uploaded_file($tempFile,$targetFile);
	echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	
	$img = new canvas($targetFile);
	$img->carrega($targetFile)
		->redimensiona( 640, 480, 'proporcional')
		->marca('../../adm/watermark_new.png', 'baixo', 'direita')
		->grava($targetFile);
		
	$img = new canvas($targetFile);
	$img->carrega($targetFile)
		->redimensiona(100, 75, 'proporcional')
		->grava($thumb);
}

/*if (!empty($_FILES)) {
	//Comentado em 02/12/2011 por Elaine
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$targetThumb = $targetPath.'thumbs/';
	$nomeFoto = $_FILES['Filedata']['name'];
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];
	$thumb = str_replace('//','/',$targetThumb) . $_FILES['Filedata']['name'];
		
	move_uploaded_file($tempFile,$targetFile);		
		
	/*$img = new canvas($targetFile);
	$img->carrega($targetFile)
		->redimensiona( 640, 480, 'proporcional')
		->marca('../../adm/watermark.png', 'baixo', 'direita')
		->grava($targetFile);*/
		
	/*$img = new canvas($targetFile);
	$img->carrega($targetFile)
		->redimensiona(100, 75, 'proporcional')
		->grava($thumb);*/
	
	//gravando no banco
	/*$qry = "INSERT INTO tbl_fotos (idevent, img, texto) VALUES ('$idevent', '$nomeFoto', '$nomeFoto')";
	$rst = mysql_query($qry);
}*/
?>