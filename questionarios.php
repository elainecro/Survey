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
		$qryQuest = "SELECT * FROM questionarios WHERE codQuestionario = $c";
	} else {
		$qryQuest = "SELECT *  FROM questionarios ORDER BY strNome";

		$qryTotal = $qryQuest;
		$rstTotal = mysql_query($qryTotal);
		$totItens = mysql_num_rows($rstTotal);

		$qryQuest .= " LIMIT $i_ini, $i_max";
	}
	$rstQuest = mysql_query($qryQuest);

	if ($_POST) {
		if ($c){
			//update
			echo $bAtivo;
			if ($bAtivo) $bAtivo = 1; else $bAtivo = '0';			
			$strNome = htmlentities($strNome, ENT_QUOTES, 'UTF-8');

			$qry = "UPDATE questionarios
					   SET strNome = '$strNome',
					   	   bAtivo = '$bAtivo'
					 WHERE codQuestionario = $c";
			$rst = mysql_query($qry);
		} else {
			//insert
			if ($bAtivo) $bAtivo = 1; else $bAtivo = '';
			$dtCriacao = date('Y-m-d h:i:s');
			$strNome = htmlentities($strNome, ENT_QUOTES, 'UTF-8');
			$strNomeInterno = htmlentities($strNomeInterno, ENT_QUOTES, 'UTF-8');
			$strNomeInterno = tiraAcentos(tiraEspacos($strNomeInterno));

			//antes de inserir verifica se já existe algum questionário com esse nome interno
			$sqlVerify = mysql_query("SELECT * FROM questionarios WHERE strNomeInterno = '$strNomeInterno' ");
			if (mysql_num_rows($sqlVerify) <= 0){				
				$qry = "INSERT INTO questionarios (strNome, strNomeInterno, dtCriacao, bAtivo)
									  VALUES ('$strNome', '$strNomeInterno', '$dtCriacao', '$bAtivo')";
				$rst = mysql_query($qry);

				if ($rst){
					criaTabela($strNomeInterno);
				}
			} else {
				echo "<script>alert('Já existe um questionário com este nome interno. Por favor, escolha outro nome.');</script>";
			}
		}
		//echo $qry;
		echo "<script>location.href='?p=questionarios';</script>";
	}
?>

<?
	if ($a){ 
		if($a=='d'){	
			//deletar
			if($c){
				//deletando todos os campos relacionados ao questionario
				$sqlDel = mysql_query("DELETE FROM questionario_campos qc WHERE qc.codQuestGrupo IN (SELECT codQuestGrupo FROM questionario_grupos WHERE codQuestionario = $c)");
				//deletando todos os grupos relacionados ao questionario
				$sqlDel = mysql_query("DELETE FROM questionario_grupos WHERE codQuestionario = $c");

				//antes de deletar tenho que apagar a tabela relacionada ao questionario
				$sqltabRel = mysql_query("SELECT strNomeInterno FROM questionarios WHERE codQuestionario = $c");
				$tabRel = mysql_result($sqltabRel, 0, 0);

				$delTab = mysql_query("DROP TABLE $tabRel");

				//agora sim, deleta
				$qryDel = "DELETE FROM questionarios WHERE codQuestionario = $c";
				$rstDel = mysql_query($qryDel);
			}
			echo "<script>location.href='?p=questionarios';</script>";
		}
		if ($c){
			$rowQuest = mysql_fetch_array($rstQuest);
			$strNome = html_entity_decode($rowQuest['strNome'], ENT_QUOTES, 'UTF-8');
			$strNomeInterno = html_entity_decode($rowQuest['strNomeInterno'], ENT_QUOTES, 'UTF-8');
			$dtCriacao = date2data($rowQuest['dtCriacao']);
			$bAtivo = $rowQuest['bAtivo'];
		}
?>
<div class="row">
	<div class="span12">
      <h2>Cadastro de Questionários</h2>
    </div>
</div>

<form class="well form-horizontal" method="post" action="" name="formQuest">

	<div class="control-group">
		<label class="control-label" for="strNome">Nome do questionário:</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="strNome" name="strNome" value="<?=$strNome?>" autofocus placeholder="Informe o nome do questionário."/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strNomeInterno">Nome interno:</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="strNomeInterno" name="strNomeInterno" value="<?=$strNomeInterno?>" placeholder="Nome identificador interno do questionario." <?if($c) echo 'disabled';?>/>
			<span><strong>Atenção:</strong> O nome definido na inserção não poderá mais ser alterado. Evite acentos e espaços.</span>
		</div>
	</div>

	<div class="control-group">
		<label class="checkbox offset2">
			<input type="checkbox" id="bAtivo" name="bAtivo"
				<? if ($bAtivo==1) echo "checked"; else if ($bAtivo==0) echo ""; else echo "checked"; 
				if (!$c) echo "checked";?> 
			/> Ativo
		</label>
	</div>

  	<div class="form-actions">
  		<button type="submit" class="btn btn-primary">Salvar</button>
  		<a href="?p=questionarios" class="btn">Cancelar</a>
  	</div>
</form>

<? } else { ?>

<div class="row">
	<div class="span12">
      <h2>Listagem de Questionários</h2>
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
			<? while ($rowQuest = mysql_fetch_array($rstQuest)){ ?>
			<tr>
			  <td><?=$rowQuest['strNome']?></td>
			  <td><?=date2data($rowQuest['dtCriacao'])?></td>
			  <td>
			  	<a class="btn" href="?p=questionarios&a=e&c=<?=$rowQuest['codQuestionario']?>">
					<i class="icon-pencil"></i>
				</a>
			  </td>
			  <td>
			  	<? $codQuestionario = $rowQuest['codQuestionario']; ?>
			  	<a class="btn" onClick="confirma_exclusao('?p=questionarios&a=d&c=<?=$codQuestionario?>');">
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
							<a href="?p=questionarios&i_ini=<?=$ant?>">Anterior</a>
						</li>
						<? } if ($totItens - ($i_ini + $i_max) > 0) { ?>
						<li>
							<a href="?p=questionarios&i_ini=<?=$prox?>">Próximo</a>
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
    	<a href="?p=questionarios&a=i" class="btn btn-primary">Incluir Questionário</a>
    </div>
</div>

<? } ?>