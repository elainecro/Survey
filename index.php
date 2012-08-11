<?
session_start();
include('inc/util.php');
if($_POST){
  $erro = fazerLogin($login, $senha);
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="UTF-8">
	<title>Survey</title>
  <!-- Script Salve a Web -->
  <script type="text/javascript" src="http://sawpf.com/1.0.js"></script>
  <!-- Script Salve a Web -->

	<!-- Le styles -->
    <link href="Scripts/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="Scripts/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <script type="text/javascript" src="Scripts/jquery-1.7.2.min.js"></script>
    <script type="text/javascript" src="Scripts/bootstrap/js/bootstrap-dropdown.js"></script>
    <script type="text/javascript">$('.dropdown-toggle').dropdown()</script>
</head>
<body>
    <div class="container">  

      <div class="row">
        <div class="span4 offset4" >
          <p>
          <img src="img/question.jpg" height="300px" width="300px">
          </p>
        </div>
      </div>

      <div class="hero-unit">
        <h3 align="center">Faça seu login</h3>

        <form class="well" action="" method="post">
          <div class="row">
            <div class="span4 offset4">
              <label><strong>Login:</strong></label>
              <input type="text" name="login" class="span3" placeholder="Login..." autofocus required/>
              <label><strong>Senha:</strong></label>
              <input type="password" name="senha" class="span3" placeholder="Senha..." required/>
              <br>
              <? if ($erro){ ?>
              <div class="alert alert-error">
                Login/senha incorreto(s). Tente novamente.
              </div>
              <? } ?>
              <button type="submit" class="btn btn-primary btn-large">Entrar</button>
            </div>
          </div>
        </form>


      </div>

      <hr>

      <footer>
        <p>&copy; Oliveira's Consultoria e Gestão da Informação - 2012</p>
      </footer>

    </div> <!-- /container -->


</body>
</html>