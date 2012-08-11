<?
  session_start();
  if (!$_SESSION["login"]){
    echo "<script>location.href='index.php';</script>";  
  }

  $p = $_GET['p'];
?>
<!DOCTYPE html>
<html lang="pt">
<head>
	<meta charset="UTF-8">
	<title>Survey</title>
  <!-- Script Salve a Web -->
  <script type="text/javascript" src="http://sawpf.com/1.0.js"></script>
  <!-- Script Salve a Web -->
  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="">
  <meta name="author" content="Oliveira's CGI">

	<!-- Le styles -->
  <link href="inc/util.css" rel="stylesheet">
  <link href="Scripts/bootstrap/css/bootstrap.css" rel="stylesheet">
  <style type="text/css">
    body {
      padding-top: 60px;
      padding-bottom: 40px;
    }
  </style>
  <link href="Scripts/bootstrap/css/bootstrap-responsive.css" rel="stylesheet">

  <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
  <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <script type="text/javascript" src="Scripts/jquery-1.7.2.min.js"></script>
  <script type="text/javascript" src="Scripts/bootstrap/js/bootstrap-dropdown.js"></script>
  <script type="text/javascript" src="Scripts/bootstrap/js/bootstrap-tab.js"></script>
  <script type="text/javascript" src="Scripts/jquery.meio.mask.js"></script>
  <script type="text/javascript" src="Scripts/usa.meio.mask.js"></script>
  <script type="text/javascript" src="Scripts/jquery.util.js"></script>
  <script type="text/javascript">$('.dropdown-toggle').dropdown()</script>

  <link href="Scripts/jquery-ui/css/redmond/jquery-ui-1.8.9.custom.css" rel="stylesheet">
  <script type="text/javascript" src="Scripts/jquery-ui/js/jquery-ui-1.8.20.custom.min.js"></script>
  <script type="text/javascript" src="Scripts/jquery-ui/js/jquery.ui.datepicker-pt-BR.js"></script>

  <!--CHARTS-->
  <script type="text/javascript" src="https://www.google.com/jsapi"></script>
  <!--<script type="text/javascript" src="Scripts/jquery-1.7.2.min.js"></script>-->
</head>
<body>

  <div class="navbar navbar-fixed-top">
      <div class="navbar-inner">
        <div class="container-fluid">
          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
          <a class="brand" href="principal.php">Survey</a>
          <div class="btn-group pull-right">
            <p class="btn btn-info"><?=date("d/m/Y")?></p>
            <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
              <i class="icon-user"></i> <?=$_SESSION["nome"]?>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
              <li class="divider"></li>
              <li><a href="logout.php"><i class="icon-off"></i> Sair</a></li>
            </ul>
          </div>


          <div class="nav-collapse">
            <ul class="nav">
              <li class="<? if (!$p) echo 'active'; ?>"><a href="principal.php">Home</a></li>

              <? $arrayMenu = array('questionarios', 'questgrupos', 'questcampos');?>

              <li class="<? if (in_array($p, $arrayMenu)) echo 'active'; ?> dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Cadastros<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li class="<? if ($p=='gestao-politicas') echo 'active'; ?>">
                    <a href="?p=questionarios">Questionários</a>
                  </li>
                  <li class="<? if ($p=='gestao-objetivos_metas') echo 'active'; ?>">
                    <a href="?p=questgrupos">Grupos</a>
                  </li>
                  <li class="<? if ($p=='gestao-documentos') echo 'active'; ?>">
                    <a href="?p=questcampos">Campos</a>
                  </li>
                  <li class="divider"></li>
                </ul>
              </li>

            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>


    <div class="container">
      <? 
      if (in_array($p, $arrayMenu)) { 
        include('/'.$p.'.php');
      } else { ?>


      <div class="hero-unit">
        <h2>Olá <?=$_SESSION["nome"]?>,</h2>
      </div>

      <div class="row">
        <div class="span12">
          <h2>Estatísticas</h2>
          <div id="chart_div_usuarios" class="grafico span4"  ></div>
          <div id="chart_div_notas" class="grafico span4"></div>          
        </div>
        <div class="row">
    		  <div class="span4">...</div>
    		  <div class="span8">...</div>
  		  </div>
      </div>
      <? } ?>

      <hr>

      <footer>
        <p>
          <div><h6>
            &copy;2012 - Todos os direitos reservados
            <div class="pull-right">
              <img src="img/Logo_Pequeno.jpg" alt="OliveirasCGI" title="Oliveira's CGI">
              Sistema desenvolvido por 
                <a href="http://www.oliveirascgi.com.br" target="_blank">
                  Oliveira's Consultoria e Gestão da Informação
                </a>             
            </div>
          </h6></div>
        </p>
      </footer>

    </div> <!-- /container -->
    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="Scripts/bootstrap/js/bootstrap-transition.js"></script>
    <script src="Scripts/bootstrap/js/bootstrap-collapse.js"></script>


</body>
</html>