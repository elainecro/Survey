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
		$qryQuestCampo = "SELECT * FROM questionario_campos qc
						  INNER JOIN questionario_grupos qg ON qg.codQuestGrupo = qc.codQuestGrupo
						  WHERE codQuestCampo = $c";
	} else {
		$qryQuestCampo = "SELECT * FROM questionario_campos qc
						  INNER JOIN questionario_grupos qg ON qg.codQuestGrupo = qc.codQuestGrupo
						  ORDER BY qg.strTitulo, qg.intOrdem, qc.strNome";

		$qryTotal = $qryQuestCampo;
		$rstTotal = mysql_query($qryTotal);
		$totItens = mysql_num_rows($rstTotal);

		$qryQuestCampo .= " LIMIT $i_ini, $i_max";
	}
	$rstQuestCampo = mysql_query($qryQuestCampo);

	if ($_POST) {
		if ($bAtivo) $bAtivo = 1; else $bAtivo = '';
		$dtCriacao = date('Y-m-d h:i:s');
		$strNome = htmlentities($strNome, ENT_QUOTES, 'UTF-8');
		
		$strNomeInterno = htmlentities($strNomeInterno, ENT_QUOTES, 'UTF-8');
		$strNomeInterno = tiraAcentos(tiraEspacos($strNomeInterno));

		$strDescricao = htmlentities($strDescricao, ENT_QUOTES, 'UTF-8');
		
		$strValores = tiraAcentos(tiraEspacos($strOpcoes));
		$strValores = html_entity_decode($strValores, ENT_QUOTES, 'UTF-8');
		$strOpcoes = htmlentities($strOpcoes, ENT_QUOTES, 'UTF-8');

		if (!$intOrdem) $intOrdem = 1;


		if ($c){
			//update
			$qry = "UPDATE questionario_campos
					   SET codQuestGrupo = $codQuestGrupo,
					   	   strNome = '$strNome',					   	   
					   	   strDescricao = '$strDescricao',
					   	   intOrdem = '$intOrdem',
					   	   bAtivo = '$bAtivo'
					 WHERE codQuestCampo = $c";
			$rst = mysql_query($qry);
		} else {
			//antes de inserir novo campo, devo verificar se já existe um campo na tabela do questionario
			$sql = "SELECT * FROM questionario_campos WHERE codQuestionario = $codQuestionario AND strNomeInterno = '$strNomeInterno' ";
			$verify = mysql_query($sql);
			if (mysql_num_rows($verify)<=0){

				$arrayValores = explode(',', $strValores);
				$strValores = '';
				foreach ($arrayValores as $key => $value) {
					if ($strValores) $strValores .= ', ';
					$strValores .= tiraAcentos(tiraEspacos($value));
				}
				echo $strValores;
			
				//insert			
				$qry = "INSERT INTO questionario_campos (codQuestionario, codQuestGrupo, strNome, strNomeInterno, strTipo, strDescricao, 
									strOpcoes, strValores, intOrdem, dtCriacao, bAtivo)
							 VALUES ('$codQuestionario', '$codQuestGrupo', '$strNome', '$strNomeInterno', '$strTipo', '$strDescricao', 
							 		'$strOpcoes', '$strValores', '$intOrdem', '$dtCriacao', '$bAtivo')";
				$rst = mysql_query($qry);

				//apos inserir eu crio o campo do questionario na tabela em questao
				criaCampoQuestionario($codQuestionario, $strNomeInterno);
			} else {
				echo "<script>alert('Já existe um campo com este nome interno para o questionário selecionado.');</script>";
			}
		}
		echo "<script>location.href='?p=questcampos';</script>";
	}
?>

<?
	if ($a){ 
		if($a=='d'){	
			//deletar
			if($c){
				//deletando campo
				$qryDel = "DELETE FROM questionario_campos WHERE codQuestCampo = $c";
				$rstDel = mysql_query($qryDel);
			}
			echo "<script>location.href='?p=questcampos';</script>";
		}
		if ($c){
			$rowQuestCampo = mysql_fetch_array($rstQuestCampo);
			$strNome = html_entity_decode($rowQuestCampo['strNome'], ENT_QUOTES, 'UTF-8');
			$strNomeInterno = html_entity_decode($rowQuestCampo['strNomeInterno'], ENT_QUOTES, 'UTF-8');
			$strTipo = $rowQuestCampo['strTipo'];
			$strDescricao = html_entity_decode($rowQuestCampo['strDescricao'], ENT_QUOTES, 'UTF-8');
			$strOpcoes = html_entity_decode($rowQuestCampo['strOpcoes'], ENT_QUOTES, 'UTF-8');
			$strValores = html_entity_decode($rowQuestCampo['strValores'], ENT_QUOTES, 'UTF-8');
			$dtCriacao = date2data($rowQuestCampo['dtCriacao']);
			$intOrdem = $rowQuestCampo['intOrdem'];
			$bAtivo = $rowQuestCampo['bAtivo'];
			$codQuestGrupo = $rowQuestCampo['codQuestGrupo'];

			$buscaQuest = mysql_query("SELECT codQuestionario FROM questionario_grupos WHERE codQuestGrupo = $codQuestGrupo");
			$codQuestionario = mysql_result($buscaQuest, 0, 0);
		}
?>
<div class="row">
	<div class="span12">
      <h2>Cadastro de Campos de Questionários</h2>
    </div>
</div>

<form class="well form-horizontal" method="post" action="" name="formQuestGrupo">

	<div class="control-group">
		<label class="control-label" for="codQuestionario">Questionário:</label>
		<div class="controls">
			<select name="codQuestionario" id="codQuestionario" autofocus <?if($c) echo "disabled";?> onChange="atualizaComboGruposQuest();">
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
		<label class="control-label" for="codQuestGrupo">Grupo:</label>
		<div class="controls" id="div_GrupoQuest">
			<select name="codQuestGrupo" id="codQuestGrupo" <?if (!$c) echo "disabled"; ?>>
				<option value="">:: Selecionar</option>
				<? $qryGrupos = "SELECT * FROM questionario_grupos ORDER BY strTitulo";
				   $rstGrupos = mysql_query($qryGrupos);
				   while ($rowGrupo = mysql_fetch_array($rstGrupos)){
				   	$codGrupo = $rowGrupo['codQuestGrupo'];
				?>
				<option value="<?=$rowGrupo['codQuestGrupo']?>" <?if($codGrupo==$codQuestGrupo) echo "selected";?>><?=$rowGrupo['strTitulo']?></option>
				<? } ?>
			</select>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strNome">Nome:</label>
		<div class="controls">
			<input type="text" class="input-xlarge span6" id="strNome" name="strNome" value="<?=$strNome?>" placeholder="Informe o nome do Campo."/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strNomeInterno">Nome Interno:</label>
		<div class="controls">
			<input maxlength="50" type="text" class="input-xlarge" id="strNomeInterno" name="strNomeInterno" value="<?=$strNomeInterno?>" placeholder="Informe o nome interno do Campo." <?if($c) echo "disabled";?>/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strTipo">Tipo:</label>
		<div class="controls">
			<select name="strTipo" id="strTipo" <?if($c) echo "disabled";?>>
				<option value="">:: Selecionar</option>
				<option value="checkbox" <?if($strTipo=='checkbox') echo 'selected';?>>Checkbox Group</option>
				<option value="select" <?if($strTipo=='select') echo 'selected';?>>Combobox</option>
				<option value="radio" <?if($strTipo=='radio') echo 'selected';?>>Radio Group</option>				
			</select>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strDescricao">Descrição:</label>
		<div class="controls">
			<input type="text" class="input-xlarge span7" id="strDescricao" name="strDescricao" value="<?=$strDescricao?>" placeholder="Informe uma descrição para o campo." <?if($c) echo "disabled";?>/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strOpcoes">Opções:</label>
		<div class="controls">
			<input <? if($c) echo "disabled"; ?> type="text" class="input-xlarge span7" id="strOpcoes" name="strOpcoes" value="<?=$strOpcoes?>" placeholder="Informe uma lista de Opções. Ex: opcao1, opcao2, opcao3 ..." <?if($c) echo "disabled";?>/>
			<br>
			<span><strong>Atenção: </strong>a lista de opções não poderá ser modificada. Para modificar será necessário apagar o campo e criá-lo novamente, mas isso poderá interferir no resultado da pesquisa.</span>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strValores">Valores:</label>
		<div class="controls">
			<input disabled type="text" class="input-xlarge span7" id="strValores" name="strValores" value="<?=$strValores?>" placeholder="Lista de Valores, correspondentes às Opções." <?if($c) echo "disabled";?>/>
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
  		<a href="?p=questcampos" class="btn">Cancelar</a>
  	</div>
</form>

<? } else { ?>

<div class="row">
	<div class="span12">
      <h2>Listagem de Campos de Questionários</h2>
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
			<? while ($rowQuestCampo = mysql_fetch_array($rstQuestCampo)){ ?>
			<tr>
			  <td><?=$rowQuestCampo['strNome']?></td>
			  <td><?=date2data($rowQuestCampo['dtCriacao'])?></td>
			  <td>
			  	<a class="btn" href="?p=questcampos&a=e&c=<?=$rowQuestCampo['codQuestCampo']?>">
					<i class="icon-pencil"></i>
				</a>
			  </td>
			  <td>
			  	<? $codQuestCampo = $rowQuestCampo['codQuestCampo']; ?>
			  	<a class="btn" onClick="confirma_exclusao('?p=questcampos&a=d&c=<?=$codQuestCampo?>');">
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
							<a href="?p=questcampos&i_ini=<?=$ant?>">Anterior</a>
						</li>
						<? } if ($totItens - ($i_ini + $i_max) > 0) { ?>
						<li>
							<a href="?p=questcampos&i_ini=<?=$prox?>">Próximo</a>
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
    	<a href="?p=questcampos&a=i" class="btn btn-primary">Incluir Campo</a>
    </div>
</div>

<? } ?>