<script>
	function confirma_exclusao(url){
		if (confirm("Tem certeza que deseja excluir?")) {
			location.href = url;
		}
	}
</script>

<?
	session_start();
	if ((!$_SESSION["login"])){
		echo "<script>location.href='index.php';</script>";  
	}

	include_once('inc/util.php');

	//definições de paginação
	if (!$i_ini) $i_ini = 0;
	$i_max = 10;
	$max = $i_ini + $i_max;
	$prox = $i_ini + $i_max;
	$ant = $i_ini - $i_max;

	if ($c){
		$qryQuestGrupo = "SELECT * FROM questionario_grupos qg
						  INNER JOIN questionarios q ON q.codQuestionario = qg.codQuestionario
						  WHERE codQuestGrupo = $c";
	} else {
		$qryQuestGrupo = "SELECT *  FROM questionario_grupos qg
						  INNER JOIN questionarios q ON q.codQuestionario = qg.codQuestionario
						  ORDER BY strNome, intOrdem, strTitulo";

		$qryTotal = $qryQuestGrupo;
		$rstTotal = mysql_query($qryTotal);
		$totItens = mysql_num_rows($rstTotal);

		$qryQuestGrupo .= " LIMIT $i_ini, $i_max";
	}
	$rstQuestGrupo = mysql_query($qryQuestGrupo);

	if ($_POST) {
		if (!$codQuestionario){
			if ($c){
				//update
				echo $bAtivo;
				if ($bAtivo) $bAtivo = 1; else $bAtivo = '0';	
				$strTitulo = htmlentities($strTitulo, ENT_QUOTES, 'UTF-8');
				$strSubTitulo = htmlentities($strSubTitulo, ENT_QUOTES, 'UTF-8');

				$qry = "UPDATE questionario_grupos
						   SET strTitulo = '$strTitulo',
						   	   strSubTitulo = '$strSubTitulo',
						   	   intOrdem = '$intOrdem',
						   	   bAtivo = '$bAtivo'
						 WHERE codQuestGrupo = $c";
				$rst = mysql_query($qry);
			} else {
				//insert
				if ($bAtivo) $bAtivo = 1; else $bAtivo = '';
				$dtCriacao = date('Y-m-d h:i:s');
				$strTitulo = htmlentities($strTitulo, ENT_QUOTES, 'UTF-8');
				$strSubTitulo = htmlentities($strSubTitulo, ENT_QUOTES, 'UTF-8');
				
				$qry = "INSERT INTO questionario_grupos (codQuestionario, strTitulo, strSubTitulo, intOrdem, dtCriacao, bAtivo)
									  VALUES ('$codQuestionario', '$strTitulo', '$strSubTitulo', '$intOrdem', '$dtCriacao', '$bAtivo')";
				$rst = mysql_query($qry);
			}
			//echo $qry;
			echo "<script>location.href='?p=questgrupos';</script>";
		} else {
			echo "<script>alert('Um grupo deve pertencer a um questionário. Por favor, corrija o seu cadastro.');</script>";
		}
	}
?>

<?
	if ($a){ 
		if($a=='d'){	
			//deletar
			if($c){
				//antes de deletar tenho que apagar os campos relacionados ao grupo
				$sqlDelCampos = mysql_query("DELETE FROM questionario_campos WHERE codQuestGrupo = $c");

				//agora sim, deleta
				$qryDel = "DELETE FROM questionario_grupos WHERE codQuestGrupo = $c";
				$rstDel = mysql_query($qryDel);
			}
			echo "<script>location.href='?p=questgrupos';</script>";
		}
		if ($c){
			$rowQuestGrupo = mysql_fetch_array($rstQuestGrupo);
			$strTitulo = html_entity_decode($rowQuestGrupo['strTitulo'], ENT_QUOTES, 'UTF-8');
			$strSubTitulo = html_entity_decode($rowQuestGrupo['strSubTitulo'], ENT_QUOTES, 'UTF-8');
			$dtCriacao = date2data($rowQuestGrupo['dtCriacao']);
			$intOrdem = $rowQuestGrupo['intOrdem'];
			$bAtivo = $rowQuestGrupo['bAtivo'];
			$codQuestionario = $rowQuestGrupo['codQuestionario'];
		}
?>
<div class="row">
	<div class="span12">
      <h2>Cadastro de Grupos de Questionários</h2>
    </div>
</div>

<form class="well form-horizontal" method="post" action="" name="formQuestGrupo">

	<div class="control-group">
		<label class="control-label" for="codQuestionario">Questionário:</label>
		<div class="controls">
			<select name="codQuestionario" id="codQuestionario" autofocus <?if($c) echo "disabled";?>>
				<option value="">:: Selecionar</option>
				<? $qryQuests = "SELECT * FROM questionarios ORDER BY strNome";
				   $rstQuests = mysql_query($qryQuests);
				   while ($rowQuest = mysql_fetch_array($rstQuests)){
				   	$codQuest = $rowQuest['codQuestionario'];
				?>
				<option value="<?=$rowQuest['codQuestionario']?>" <?if($codQuestionario==$codQuest) echo "selected";?>><?=$rowQuest['strNome']?></option>
				<? } ?>
			</select>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strTitulo">Título:</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="strTitulo" name="strTitulo" value="<?=$strTitulo?>" placeholder="Informe o título do Grupo."/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strSubTitulo">Sub Título:</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="strSubTitulo" name="strSubTitulo" value="<?=$strSubTitulo?>" placeholder="Informe o sub título do Grupo."/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="intOrdem">Ordem:</label>
		<div class="controls">
			<input type="text" class="input-small" id="intOrdem" name="intOrdem" value="<?=$intOrdem?>" alt="integer" placeholder="Informe a ordem do grupo."/>
		</div>
	</div>

	<div class="control-group">
		<label class="checkbox offset1">
			<input type="checkbox" id="bAtivo" name="bAtivo"
				<? if ($bAtivo==1) echo "checked"; else if ($bAtivo==0) echo ""; else echo "checked"; 
				if (!$c) echo "checked";?> 
			/> Ativo
		</label>
	</div>

  	<div class="form-actions">
  		<button type="submit" class="btn btn-primary">Salvar</button>
  		<a href="?p=questionario_grupos" class="btn">Cancelar</a>
  	</div>
</form>

<? } else { ?>

<div class="row">
	<div class="span12">
      <h2>Listagem de Grupos de Questionários</h2>
    </div>

    <div class="span12">
    	<table class="table">
			<thead>
			<tr>
			  <th>Nome</th>
			  <th>Criado em</th>
			  <th>Editar</th>
			  <th>Excluir</th>
			</tr>
			</thead>
			<tbody>
			<? while ($rowQuestGrupo = mysql_fetch_array($rstQuestGrupo)){ ?>
			<tr>
			  <td><?=$rowQuestGrupo['strTitulo']?></td>
			  <td><?=date2data($rowQuestGrupo['dtCriacao'])?></td>
			  <td>
			  	<a class="btn" href="?p=questgrupos&a=e&c=<?=$rowQuestGrupo['codQuestGrupo']?>">
					<i class="icon-pencil"></i>
				</a>
			  </td>
			  <td>
			  	<? $codQuestGrupo = $rowQuestGrupo['codQuestGrupo']; ?>
			  	<a class="btn" onClick="confirma_exclusao('?p=questgrupos&a=d&c=<?=$codQuestGrupo?>');">
					<i class="icon-trash"></i>
				</a>
			  </td>
			</tr>
			<? } ?>
			<tr>
				<td colspan="6">
				<div align="center">
					<ul class="pager">
						<? if ($prox > $i_max) { ?>
						<li>
							<a href="?p=questgrupos&i_ini=<?=$ant?>">Anterior</a>
						</li>
						<? } if ($totItens - ($i_ini + $i_max) > 0) { ?>
						<li>
							<a href="?p=questgrupos&i_ini=<?=$prox?>">Próximo</a>
						</li>
						<? } ?>
					</ul>      
				</div>
				</td>
			</tr>
			</tbody>
		</table>

    </div>

    <div class="span4">
    	<a href="?p=questgrupos&a=i" class="btn btn-primary">Incluir Grupo</a>
    </div>
</div>

<? } ?>