<?
	session_start();
	
	include ("../inc/util.php");

	$codQuest = mysql_real_escape_string( $_REQUEST['codQuest'] );
?>

<select name="codQuestGrupo" id="codQuestGrupo">
	<option value="">:: Selecionar</option>
	<? $qryGrupos = "SELECT * FROM questionario_grupos WHERE codQuestionario = $codQuest ORDER BY strTitulo";
	   $rstGrupos = mysql_query($qryGrupos);
	   while ($rowGrupo = mysql_fetch_array($rstGrupos)){
	   	$codGrupo = $rowGrupo['codQuestGrupo'];
	?>
	<option value="<?=$rowGrupo['codQuestGrupo']?>" <?if($codGrupo==$codQuestGrupo) echo "selected";?>><?=$rowGrupo['strTitulo']?></option>
	<? } ?>
</select>