<script>
	function habilitaSenha(){
		document.formUsuario.strSenha.disabled = false;
	}

	function confirma_exclusao(url){
		if (confirm("Tem certeza que deseja excluir?")) {
			location.href = url;
		}
	}
</script>
<?
	session_start();
	if ((!$_SESSION["login"]) || (!$_SESSION['idempresa'])){
		echo "<script>location.href='index.php';</script>";  
	}

	include_once('inc/util.php');

	$idempresa = $_SESSION['idempresa'];

	//definições de paginação
	if (!$i_ini) $i_ini = 0;
	$i_max = 10;
	$max = $i_ini + $i_max;
	$prox = $i_ini + $i_max;
	$ant = $i_ini - $i_max;

	if ($c){
		$qryUsuarios = "SELECT * FROM usuarios WHERE codUsuario = $c";
	} else {
		$qryUsuarios = "SELECT u.*, e.strNome as strEmpresa FROM usuarios u 
		INNER JOIN empresas e ON e.codEmpresa = u.codEmpresa ";
		if ($idempresa!=1) { $qryUsuarios .= " AND e.codEmpresa = $idempresa ";}
		$qryUsuarios .= " ORDER BY u.strNome";

		$qryTotal = $qryUsuarios;
		$rstTotal = mysql_query($qryTotal);
		$totItens = mysql_num_rows($rstTotal);

		$qryUsuarios .= " LIMIT $i_ini, $i_max";
	}
	$rstUsuarios = mysql_query($qryUsuarios);

	if ($_POST) {
		if ($c){
			//update
			if ($bAtivo) $bAtivo = 1; else $bAtivo = 0;			
			if (!$cbEmpresa) $cbEmpresa = $idempresa;
			$strNome = htmlentities($strNome, ENT_QUOTES, 'UTF-8');
			$strLogin = htmlentities($strLogin, ENT_QUOTES, 'UTF-8');

			$qry = "UPDATE usuarios
					   SET strNome = '$strNome',
					   	   strLogin = '$strLogin',
					   	   codEmpresa = '$cbEmpresa',
					   	   chrAcesso = '$cbAcesso',
					   	   bAtivo = '$bAtivo'";
			if ($strSenha){
				$strSenha = md5($strSenha);
				$qry .= " ,strSenha = '$strSenha' ";
			}
			$qry .= " WHERE codUsuario = $c";
			$rst = mysql_query($qry);
		} else {
			if ($bAtivo) $bAtivo = 1; else $bAtivo = 0;
			if (!$cbEmpresa) $cbEmpresa = $idempresa;
			$strSenha = md5($strSenha);
			$strNome = htmlentities($strNome, ENT_QUOTES, 'UTF-8');
			$strLogin = htmlentities($strLogin, ENT_QUOTES, 'UTF-8');
			
			$qry = "INSERT INTO usuarios (codEmpresa, strNome, strLogin, strSenha, chrAcesso, bAtivo)
								  VALUES ('$cbEmpresa', '$strNome', '$strLogin', '$strSenha', '$cbAcesso', '$bAtivo')";
			$rst = mysql_query($qry);
		}
		echo "<script>location.href='?p=geral-usuarios';</script>";
	}
?>

<?
	if ($a){ 
		if($a=='d'){
			//deletar
			if($c){
				$qryDel = "DELETE FROM usuarios WHERE codUsuario = $c";
				$rstDel = mysql_query($qryDel);
			}
			echo "<script>location.href='?p=geral-usuarios';</script>";
		}
		if ($c){
			$rowUsuario = mysql_fetch_array($rstUsuarios);
			$strNome = html_entity_decode($rowUsuario['strNome'], ENT_QUOTES, 'UTF-8');
			$strLogin = html_entity_decode($rowUsuario['strLogin'], ENT_QUOTES, 'UTF-8');
			$strSenha = $rowUsuario['strSenha'];
			$cbAcesso = $rowUsuario['chrAcesso'];
			$cbEmpresa = $rowUsuario['codEmpresa'];
			$bAtivo = $rowUsuario['bAtivo'];
		}
?>
<div class="row">
	<div class="span12">
      <h2>Cadastro de Usuários</h2>
    </div>
</div>

<form class="well form-horizontal" method="post" action="" name="formUsuario">

	<? if ($_SESSION['idempresa']==1){ ?>
	<div class="control-group">
		<label class="control-label" for="cbEmpresa">Empresa:</label>
		<div class="controls">
			<select name="cbEmpresa" id="cbEmpresa" required>
				<option value="">::Selecionar</option>
				<? 
					$qryEmpresa = "SELECT * FROM empresas WHERE bAtivo = 1 ORDER BY strNome";
					$rstEmpresa = mysql_query($qryEmpresa);
					while ($rowEmpresa = mysql_fetch_array($rstEmpresa)){
						$codEmpresa = $rowEmpresa['codEmpresa'];
						if (!$cbEmpresa) $cbEmpresa = $_SESSION["idempresa"];
				?>
				<option value="<?=$codEmpresa?>" <? if($cbEmpresa==$codEmpresa) echo "selected"; ?> >
					<?=$rowEmpresa['strNome']?></option>
				<?  } ?>
			</select>
		</div>
	</div>
	<? } ?>

	<div class="control-group">
		<label class="control-label" for="strNome">Nome do usuário:</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="strNome" name="strNome" value="<?=$strNome?>" autofocus placeholder="Informe o nome do usuário."/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="cbAcesso">Tipo de Acesso:</label>
		<div class="controls">
			<select name="cbAcesso" id="cbAcesso" required>
				<option value="">::Selecionar</option>
				<option value="A" <? if($cbAcesso=='A') echo "selected"; ?>>Administrador</option>
				<option value="V" <? if($cbAcesso=='V') echo "selected"; ?>>Vendedor</option>
			</select>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strLogin">Login:</label>
		<div class="controls">
			<input type="text" class="input-xlarge" id="strLogin" name="strLogin" value="<?=$strLogin?>" placeholder="Informe o login."/>
		</div>
	</div>

	<div class="control-group">
		<label class="control-label" for="strSenha">Senha:</label>
		<div class="controls">
			<input type="password" class="input-xlarge" id="strSenha" name="strSenha" value="<?=$strSenha?>" placeholder="Informe a senha."
			<? if ($c) echo 'disabled';?>/>
			<button class="btn" type="button" onclick="habilitaSenha();">Editar Senha</button>
		</div>
	</div>

	<div class="control-group">
		<label class="checkbox offset2">
			<input type="checkbox" id="bAtivo" name="bAtivo"
				<? if ($bAtivo==1) echo "checked"; else if ($bAtivo==0) echo ""; else echo "checked"; ?> 
			/> Ativo
		</label>
	</div>

  	<div class="form-actions">
  		<button type="submit" class="btn btn-primary">Salvar</button>
  		<a href="?p=geral-usuarios" class="btn">Cancelar</a>
  	</div>
</form>

<? } else { ?>

<div class="row">
	<div class="span12">
      <h2>Listagem de Usuários</h2>
    </div>

    <div class="span12">
    	<table class="table">
			<thead>
			<tr>
			  <th>Nome</th>
			  <th>Login</th>
			  <th>Permissão</th>
			  <? if ($_SESSION['idempresa']==1) { ?>
			  <th>Empresa</th>
			  <? } ?>
			  <th>Editar</th>
			  <th>Excluir</th>
			</tr>
			</thead>
			<tbody>
			<? while ($rowUsuario = mysql_fetch_array($rstUsuarios)){ ?>
			<tr>
			  <td><?=$rowUsuario['strNome']?></td>
			  <td><?=$rowUsuario['strLogin']?></td>
			  <td><?
			  switch($rowUsuario['chrAcesso']){
			  	case 'A': echo 'Administrador'; break;
			  	case 'V': echo 'Vendedor'; break;
			  	default: echo 'Não definido'; break;
			  }
			  ?></td>
			  <? if ($_SESSION['idempresa']==1) { ?>
			  <td><?=$rowUsuario['strEmpresa']?></td>
			  <? } ?>
			  <td>
			  	<a class="btn" href="?p=geral-usuarios&a=e&c=<?=$rowUsuario['codUsuario']?>">
					<i class="icon-pencil"></i>
				</a>
			  </td>
			  <td>
			  	<? $codUsuario = $rowUsuario['codUsuario']; ?>
			  	<a class="btn" onClick="confirma_exclusao('?p=geral-usuarios&a=d&c=<?=$codUsuario?>');">
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
							<a href="?p=geral-usuarios&i_ini=<?=$ant?>">Anterior</a>
						</li>
						<? } if ($totItens - ($i_ini + $i_max) > 0) { ?>
						<li>
							<a href="?p=geral-usuarios&i_ini=<?=$prox?>">Próximo</a>
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
    	<a href="?p=geral-usuarios&a=i" class="btn btn-primary">Incluir Usuário</a>
    </div>
</div>

<? } ?>