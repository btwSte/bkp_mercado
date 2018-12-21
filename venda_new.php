?<?php
  session_start();
  require_once('Connections/conexao.php');

  //pegar id da loja
  $id_loja = $_SESSION['id_loja'];

  // se houver GET do ID compra criar a sessão
  if ((isset($_GET['id_venda'])) && ($_GET['id_venda'] != "")) {
    $_SESSION['venda']		=	$_GET['id_venda'];
  }

  // resgatar dados operador, data , hora , loja, cod
  if ((isset($_GET['cod'])) && ($_GET['cod'] != "")) {
    $_SESSION['cod']		=	$_GET['cod'];
    echo "<script>location.href='venda.php';</script>";
  }

  // se houver POST do ID compra criar a sessão
  if ((isset($_POST['id_venda'])) && ($_POST['id_venda'] != "")) {
    $_SESSION['venda']		=	$_POST['id_venda'];
    echo "<script>location.href='venda.php';</script>";
  }

  if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
      if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
      }

      $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

      switch ($theType) {
        case "text":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "long":
        case "int":
          $theValue = ($theValue != "") ? intval($theValue) : "NULL";
          break;
        case "double":
          $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
          break;
        case "date":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "defined":
          $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
          break;
      }

      return $theValue;
    }
  }

  if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = ""){
      if (PHP_VERSION < 6) {
        $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
      }

      $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

      switch ($theType) {
        case "text":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "long":
        case "int":
          $theValue = ($theValue != "") ? intval($theValue) : "NULL";
          break;
        case "double":
          $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
          break;
        case "date":
          $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
          break;
        case "defined":
          $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
          break;
      }

      return $theValue;
    }
  }

  $maxRows_chama_produto = 10;
  $pageNum_chama_produto = 0;
  if (isset($_GET['pageNum_chama_produto'])) {
    $pageNum_chama_produto = $_GET['pageNum_chama_produto'];
  }

  $startRow_chama_produto = $pageNum_chama_produto * $maxRows_chama_produto;

  $colname_chama_produto = "-1";
  if (isset($_POST['cod_ean'])) {
    $colname_chama_produto = $_POST['cod_ean'];
  }

  mysql_select_db($database_conexao, $conexao);
  $query_chama_produto = sprintf("SELECT * FROM produtos WHERE cod_ean = %s and id_loja = '$id_loja'", GetSQLValueString($colname_chama_produto, "text"));
  $query_limit_chama_produto = sprintf("%s LIMIT %d, %d", $query_chama_produto, $startRow_chama_produto, $maxRows_chama_produto);
  $chama_produto = mysql_query($query_limit_chama_produto, $conexao) or die(mysql_error());
  $row_chama_produto = mysql_fetch_assoc($chama_produto);

  if (isset($_GET['totalRows_chama_produto'])) {
    $totalRows_chama_produto = $_GET['totalRows_chama_produto'];
  } else {
    $all_chama_produto = mysql_query($query_chama_produto);
    $totalRows_chama_produto = mysql_num_rows($all_chama_produto);
  }
  $totalPages_chama_produto = ceil($totalRows_chama_produto/$maxRows_chama_produto)-1;

  $colname_chama_venda = "-1";
  if (isset($_SESSION['cod'])) {
    $colname_chama_venda = $_SESSION['cod'];
  }
  mysql_select_db($database_conexao, $conexao);
  $query_chama_venda = sprintf("SELECT * FROM vendas WHERE cod_venda = %s ORDER BY id_venda ASC", GetSQLValueString($colname_chama_venda, "text"));
  $chama_venda = mysql_query($query_chama_venda, $conexao) or die(mysql_error());
  $row_chama_venda = mysql_fetch_assoc($chama_venda);
  $totalRows_chama_venda = mysql_num_rows($chama_venda);

  $colname_listar_vendidos = "-1";
  if (isset($_SESSION['venda'])) {
    $colname_listar_vendidos = $_SESSION['venda'];
  }
  mysql_select_db($database_conexao, $conexao);
  $query_listar_vendidos = sprintf("SELECT * FROM produtos_vendidos WHERE id_venda = %s ORDER BY id_vendido DESC", GetSQLValueString($colname_listar_vendidos, "int"));
  $listar_vendidos = mysql_query($query_listar_vendidos, $conexao) or die(mysql_error());
  $row_listar_vendidos = mysql_fetch_assoc($listar_vendidos);
  $totalRows_listar_vendidos = mysql_num_rows($listar_vendidos);

  $colname_chama_loja = "-1";
  if (isset($_SESSION['id_loja'])) {
    $colname_chama_loja = $_SESSION['id_loja'];
  }
  mysql_select_db($database_conexao, $conexao);
  $query_chama_loja = sprintf("SELECT * FROM loja WHERE id = %s", GetSQLValueString($colname_chama_loja, "int"));
  $chama_loja = mysql_query($query_chama_loja, $conexao) or die(mysql_error());
  $row_chama_loja = mysql_fetch_assoc($chama_loja);
  $totalRows_chama_loja = mysql_num_rows($chama_loja);

  $colname_chama_operador = "-1";
  if (isset($_SESSION['operador'])) {
    $colname_chama_operador = $_SESSION['operador'];
  }

  mysql_select_db($database_conexao, $conexao);
  $query_chama_operador = sprintf("SELECT * FROM operadores WHERE operador = %s", GetSQLValueString($colname_chama_operador, "text"));
  $chama_operador = mysql_query($query_chama_operador, $conexao) or die(mysql_error());
  $row_chama_operador = mysql_fetch_assoc($chama_operador);
  $totalRows_chama_operador = mysql_num_rows($chama_operador);

  mysql_select_db($database_conexao, $conexao);
  $query_chama_prods = "SELECT * FROM produtos WHERE id_loja = '$id_loja' ORDER BY saida DESC";
  $chama_prods = mysql_query($query_chama_prods, $conexao) or die(mysql_error());
  $row_chama_prods = mysql_fetch_assoc($chama_prods);
  $totalRows_chama_prods = mysql_num_rows($chama_prods);

  //resgatar o código EAN

  if ((isset($_POST['cod_ean'])) && ($_POST['cod_ean'] != "")) {
   $_SESSION['cod_ean']		=	$_POST['cod_ean'];
  }

  // RESGATAR PRODUTOS ADICIONADOS
  if ((isset($_POST['qtd_prod'])) && ($_POST['qtd_prod'] != "")) {
  	$desc_prod	= 	$_POST['desc_prod'];
    // somar o item com o valor total até o momento
    $valor_itens= $_POST["total_prod"];
    $valor_parcial= $_POST["total"];
    $id_venda = $_POST["id_venda"];
    $calculo_total =  $valor_itens+$valor_parcial;

    // atualizar no banco de dados o valor da compra
    $sql=mysql_query("UPDATE vendas SET valor='$calculo_total' WHERE id_venda='$id_venda'; ");

    // ACRECENTAR ITEM AO TICKET
    // chamar produto
    $id_prod= $_POST["id_prod"];
    $descricao= $_POST["desc_prod"];
    $quantidade= $_POST["qtd_prod"];
    $valortotal= $_POST["valor_prod"];
    $id_venda= $_POST["id_venda"];
    $total_und= $_POST["total_prod"];
    $ean= $_POST["ean"];
    $id_loja = $_SESSION["id_loja"];

    // inserir no Banco de dados
    $sql=mysql_query("INSERT INTO produtos_vendidos (id_prod,id_venda,quantidade,descricao_vendido,valor_vendido,total_und,ean_vendido,id_loja) VALUES ('$id_prod','$id_venda','$quantidade','$descricao','$valortotal','$total_und','$ean','$id_loja');");

    //inserir a saida no banco de dados
    $sql=mysql_query("UPDATE produtos SET saida= saida + '$quantidade' WHERE cod_ean='$ean' and id_loja='$id_loja'; ");
    echo'<script>location.href=\'venda.php\';</script>';
  }

  // SE HOUVER EXCLUSAO DE ITENS
  if ((isset($_GET['excluir'])) && ($_GET['excluir'] != "")) {
    $id_vendido	= 	$_GET['excluir'];
    $id_venda = $_GET['venda'];
    $valor_liquido = $_GET['liquido'];
    $desconto = $_GET['descontar'];
    $ean = $_GET['ean'];
    $quantidade = $_GET['qtd'];

    //descontar o valor
    $descontado=$valor_liquido-$desconto;

    // atualizar no BD
    $sql=mysql_query("UPDATE vendas SET valor='$descontado' WHERE id_venda='$id_venda'; ");
    $sql=mysql_query("UPDATE produtos SET saida= saida - '$quantidade' WHERE cod_ean='$ean' and id_loja = '$id_loja'; ");

    // deletar o produto do BD e do Ticket
    $sql=mysql_query("DELETE FROM produtos_vendidos WHERE id_vendido='$id_vendido';");
    echo'<script>location.href=\'venda.php\';</script>';
  }
?>

<!DOCTYPE html>
<html lang="pt-BR" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>TBS - Automação Comercial</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">

    <!-- chama css -->
    <link rel="stylesheet" href="css/style.css">
    <script src="javascript/shortcut.js"></script>

    <script>
      // FUNÇÃO MOEDA - QTDE
      function MascaraMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
        var sep = 0;
        var key = '';
        var i = j = 0;
        var len = len2 = 0;
        var strCheck = '0123456789';
        var aux = aux2 = '';
        var whichCode = (window.Event) ? e.which : e.keyCode;

        if (whichCode == 13)
          return true;
        key = String.fromCharCode(whichCode); // Valor para o código da Chave

        if (strCheck.indexOf(key) == -1)
          return false; // Chave inválida

        len = objTextBox.value.length;
        for(i = 0; i < len; i++)
          if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal))
          break;
        aux = '';
        for(; i < len; i++)
          if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
          aux += key;
          len = aux.length;
          if (len == 0)
            objTextBox.value = '';

          if (len == 1)
            objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;

          if (len == 2)
            objTextBox.value = '0'+ SeparadorDecimal + aux;

          if (len > 2) {
            aux2 = '';
            for (j = 0, i = len - 3; i >= 0; i--) {
              if (j == 3) {
                aux2 += SeparadorMilesimo;
                j = 0;
              }
              aux2 += aux.charAt(i);
              j++;
            }
            objTextBox.value = '';
            len2 = aux2.length;
            for (i = len2 - 1; i >= 0; i--)
              objTextBox.value += aux2.charAt(i);
              objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);
          }
        return false;
      }
      // FIM DA FUNÇÃO MOEDA- QTDE

      // FUNÇÃO FOCO
      function foco_add(){
        document.add.qtd_prod.focus();
      }

      function foco_ean(){
        document.ean.cod_ean.focus();
      }
      // FIM DA FUNÇÃO FOCO

      // Função ENTER
      function handleEnter (field, event) {
        var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
        if (keyCode == 13) {
          var i;
          for (i = 0; i < field.form.elements.length; i++)
            if (field == field.form.elements[i])
          break;
          i = (i + 1) % field.form.elements.length;
          field.form.elements[i].focus();
          return false;
        } else {
          return true;
        }
      }
      //fim da função enter

      function formata() {
        document.getElementById("qtd_prod").value += ",00";
      }

      function SubstituiVirgulaPorPonto(campo){
        campo.value = campo.value.replace(/,/gi, ".");
      }

      function MM_findObj(n, d) { //v4.01
        var p,i,x;  if(!d) d=document;
        if((p=n.indexOf("?"))>0&&parent.frames.length) {
          d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
        }

        if(!(x=d[n])&&d.all) x=d.all[n];
          for (i=0;!x&&i<d.forms.length;i++)
            x=d.forms[i][n];
          for(i=0;!x&&d.layers&&i<d.layers.length;i++)
            x=MM_findObj(n,d.layers[i].document);

        if(!x && d.getElementById) x=d.getElementById(n);
          return x;
      }

      function KW_getVal(o) { //v1.2
        var retVal="0";
        if (o.type=="select-one") {
          retVal=(o.selectedIndex==-1)?0:o.options[o.selectedIndex].value;
        } else if (o.length>1) {
          for (var i=0;i<o.length;i++)
            if (o[i].checked) retVal=o[i].value;
        } else if (o.type=="checkbox") {
          retVal=(o.checked)?o.value:0;
        } else {
          retVal=Number(o.value)}return parseFloat(retVal);
      }

      function KW_calcForm() { //v1.2
        var str="",a=KW_calcForm.arguments;
        for (var i=3;i<a.length;i++)
          str+=(a[i].indexOf("#")==-1)?a[i]:KW_getVal(MM_findObj(a[i].substring(1)));
          t=Math.round(a[1]*eval(str))/a[1];
          tS=t.toString();
          if(a[2]>0) {
            tSp=tS.indexOf(".");
            if(tSp==-1)	tS+=".";
              tSp=tS.indexOf(".");

            while(tSp!=(tS.length-1-a[2])) {
              tS+="0";
              tSp=tS.indexOf(".");
            }
          }

        MM_findObj(a[0]).value=tS;
      }

      function moveRelogio(){
        momentoAtual = new Date()
        hora = momentoAtual.getHours()
        minuto = momentoAtual.getMinutes()
        segundo = momentoAtual.getSeconds()

        str_segundo = new String (segundo)
        if (str_segundo.length == 1)
          segundo = "0" + segundo

        str_minuto = new String (minuto)
        if (str_minuto.length == 1)
          minuto = "0" + minuto

        str_hora = new String (hora)
        if (str_hora.length == 1)
          hora = "0" + hora

        horaImprimivel = hora + ":" + minuto + ":" + segundo

        document.form_relogio.relogio.value = horaImprimivel

        setTimeout("moveRelogio()",1000)
      }

      function disabletext(e){
        return false
      }

      function reEnable(){
        return true
      }

      //if the browser is IE4+
      document.onselectstart=new Function ("return false")

      //if the browser is NS6
      if (window.sidebar){
        document.onmousedown=disabletext
        document.onclick=reEnable
      }

      var teste = '<?php echo $row_chama_venda['id_venda']; ?>';
      shortcut.add("F9",function() {
        location.href="pagamento.php?id_venda=" + teste
      });

      shortcut.add("F10",function() {
        location.href="excluir_venda.php?id=" + teste
      });

      shortcut.add("F8",function() {
	      location.href="vendas.php"
      });
    </script>
  </head>
  <body>
    <?php
      // se não existir operador sair da tela de vendas
      if ($row_chama_venda['operador']=="") {
        echo "<script>alert('ATENÇÃO: O sistema não encontrou nenhum Operador!'); location.href='registrar_nova_venda.php'</script>";
      }

      // Se o operador logado não for o mesmo da compra tirar ele da venda
      // chamar a senha da Session
      if ($_SESSION['operador']!= $row_chama_venda['operador'] ){
        echo"<script>alert('ATENÇÃO: Esta venda não está no seu nome!'); location.href='vendas.php'</script>";
      }

      // verificar se está inativo
      if($row_chama_loja['estado']=='LIBERADO' or $row_chama_loja['estado'] == 'PEDIDO') {

    ?>
    <div class="licenca_expirada">
      <div class="titulo_expirada">
        <h1>
          <img src="img/icon_alert.png" />
          Sua licença expirou!
        </h1>
      </div>

      <div id="texto_expirado">
        <p>
          Seu prazo de utilização do programa expirou no dia <strong><?php echo $row_chama_loja['data_final']; ?></strong>.
        </p>
        <p>
          Se você gostou, e achou que esse software é importante para o seu negócio entre em contato e renove sua licença por mais um mês.
        </p>
      </div>

      <div id="texto_desconsiderar">
        <p>
          Caso a mensagem acima esteja incorreta, favor desconciderar e entrar em contato com o suporte para relatar o fato!
        </p>
      </div>
    </div>
  <?php
    } else {
    // verificar se está inativo
    if($row_chama_loja['estado']=='LIBERADO') {
  ?>
  <div class="licenca_expirada">
    <div class="titulo_expirada">
      <h1>
        <img src="img/icon_alert.png" />
        Licença provisória!
      </h1>
    </div>

    <div id="texto_temporario">
      <p>
        Você tem até o dia<strong> <?php echo $row_chama_loja['data_final']; ?></strong> para realizar o pagamento da sua licença e continuar ultilizando este sistema.
      </p>
    </div>
  </div>
  <?php
    }
  ?>
    <header id="venda">
      <!-- div para alinhar à direita -->
      <div id="container_opc_header">
        <!-- Exibe nome do operador -->
        <div id="operador">
          <p>
            Operador: <?php echo $row_chama_venda['operador']; ?>
          </p>
        </div>

        <!-- Botão de cancelar venda -->
        <div class="cancelar_venda">
            <a href="excluir_venda.php?id=<?php echo $row_chama_venda['id_venda']; ?>" title="Sair e excluir está venda">
            <p>
              Cancelar Venda
            </p>
          </a>
        </div>
      </div>
      <!-- fim da div para alinhar à direita -->
    </header>
    <main>
      <!-- container de dados do produto e botao de enviar -->
      <div id="segura_dados_botao">
        <!-- segura dados do produto -->
        <div id="segura_dados">
          <!-- codigo -->
          <form id="ean" name="ean" action="venda.php" method="post">
          <div class="dados_item">
            <div class="txt_dados">
              Cod
            </div>
            <div class="txt_dados produto">
              <input type="text" name="cod_ean" id="cod_ean" value="<?php echo $row_chama_produto['cod_ean']; ?>">
            </div>
          </div>
        </form>

        <form action="" method="post" id="add" name="add" >
          <!-- quantidade -->
          <div class="dados_item">
            <div class="txt_dados">
              Qtd
            </div>
            <div class="txt_dados produto">
              <input name="qtd_prod" type="text" id="qtd_prod" onblur="KW_calcForm('total do item',100,2,'(','#valor_prod','*','#qtd_prod',')')" onkeydown="return handleEnter(this, event)" onkeyup="SubstituiVirgulaPorPonto(this)" value="1" />
            </div>
          </div>

          <!-- produto -->
          <div class="dados_item">
            <div class="txt_dados">
              Produto
            </div>
            <div class="txt_dados produto">
              <input type="text" id="desc_prod" name="desc_prod" onkeypress="return handleEnter(this, event)" value="<?php echo $row_chama_produto['descricao_prod']; ?>">
            </div>
          </div>

          <!-- valor Unitario -->
          <div class="dados_item">
            <div class="txt_dados">
              Valor Unitario
            </div>
            <div class="txt_dados produto">
              <input name="valor_prod" type="text" id="valor_prod" onkeydown="return handleEnter(this, event)" onkeyup="SubstituiVirgulaPorPonto(this)"
              value="<?php echo $row_chama_produto['valor']; ?>"/>
            </div>
          </div>

          <!-- valor total -->
          <div class="dados_item">
            <div class="txt_dados">
              Sub Total
            </div>
            <div class="txt_dados produto">
              <input name="total_prod" type="text" id="total_prod" onfocus="KW_calcForm('total_prod',100,2,'(','#valor_prod','*','#qtd_prod',')')"  value="<?php echo $row_chama_produto['valor']; ?>" />

              <input type="hidden" name="add_prod" id="add_prod" src="img/icon_add.png" width="1px" height="1px" />
              <span style=" font-size:10px;">
                <input name="id_prod" type="hidden" id="id_prod" value="<?php echo $row_chama_produto['id_prod']; ?>" />
                <input name="id_venda" type="hidden" id="id_venda" value="<?php echo $row_chama_venda['id_venda']; ?>" />
                <input name="ean" type="hidden" id="ean" value="<?php echo $row_chama_produto['cod_ean']; ?>" />
              </span>
            </div>
          </div>
        </form>
        </div>
        <!-- fim segura dados do produto -->

        <!-- segura botao enviar -->
        <div class="segura_botao">
          <!-- btn -->
          <div id="botao_venda">
            <a href="#" onclick="document.getElementById('apDiv1').style.display='block';" >
              <div class="img_botao">
                <img src="img/icon_lupa.png" width="15" height="15"/>
              </div>
              <div class="txt_botao">
                Buscar Produto
              </div>
            </a>
          </div>
        </div>
      </div>
      <!-- fim container de dados do produto e botao de enviar -->

      <!-- container de todos os produtos adicionados e demonstrativo -->
      <div id="segura_produtos">
        <!-- segura todos os produtos adicionados, subtotal, pagamento -->
        <div id="produto_dados">
          <!-- todos produtos adicionados -->
          <div id="produtos_adicionados">
            <!-- titulos -->
            <div id="adicionados_titulo">
              <div class="prod_tit"> Cod </div>
              <div class="prod_tit"> Qtd </div>
              <div class="prod_tit"> Produto </div>
              <div class="prod_tit"> Valor Unitario </div>
              <div class="prod_tit"> Valor Total </div>
              <div class="prod_tit"> Remover </div>
            </div>

            <!--  -->
            <div id="adicionados_conteudo">
              <div class="prod_linha"> 1 </div>
              <div class="prod_linha"> 2 </div>
              <div class="prod_linha"> Fone </div>
              <div class="prod_linha"> 1.00 </div>
              <div class="prod_linha"> 2.00 </div>
              <div class="prod_linha"> X </div>
            </div>
          </div>

          <!-- segura subtotal e pagamento -->
          <div class="segura_total">
            <!-- subtotal -->
            <div class="subtotal">
              Subtotal: 2.00
            </div>

            <!-- pagamento -->
            <div class="segura_pagamento">
              <!-- botao pagar -->
              <div class="pagamento">
                Pagamento
              </div>
            </div>
          </div>
          <!-- fim segura subtotal e pagamento -->

        </div>
        <!-- fim segura todos os produtos adicionados, subtotal, pagamento -->

        <!-- segura demonstrativo, salvar e sair -->
        <div class="segura_demonstrativo_salvar">
          <!-- demonstrativo -->
          <div class="demonstrativo">
            ola demonstrativo
          </div>

          <!-- salvar e sair -->
          <div class="segura_salvar">
            <div class="salvar">
              Salvar e Sair
            </div>
          </div>
        </div>
      </div>
      <!-- fim container de todos os produtos adicionados e demonstrativo -->
    </main>
    <!-- chama footer para pagina -->
    <?php include('footer.php') ?>
  <?php } ?>
  </body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <DIV class="invisivel">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Venda</title>
    <script src="javascript/shortcut.js"></script>

      <style>
        #scroll {
          width:340px;
          height:550px;
          overflow:auto;
          padding-left:5px;
          font-family: Verdana, Geneva, sans-serif;
          font-size:11px;
          padding-right:5px;
          padding-bottom:5px;
          text-align:center;
          color:#FFF;
          margin:0 auto;
        }

        a:link{ color:#458fc0; text-decoration:none;}
        a:visited{ color:#458fc0; text-decoration:none;}
        a:hover{ color:#555555; text-decoration:none;}

        #apDiv1 {
        	display:none;
        	background-color:#FFF;
        	font-family:Arial, Helvetica, sans-serif;
        	box-shadow: 0 4px 20px 3px rgba(0, 0, 0, 0.2);
          border-radius: 2px;
        	position: absolute;
        	width:574px;
        	height:259px;
        	z-index:1;
        	left: 33%;
        	top: 20%;
        }

        .apDiv1 a:link {
          color: #458fc0;
          text-decoration:none;
        }

        .apDiv1 a:visited {
          color: #458fc0;
          text-decoration:none;
        }

        .apDiv1 a:hover {
          color: #458fc0;
          text-decoration: underline;
          cursor: pointer;
        }

      </style>
    </head>

    <?php
      //resgatar o código EAN
      if ((isset($_POST['cod_ean'])) && ($_POST['cod_ean'] != "")) {
        echo "<body  onload=\"foco_add(), moveRelogio();\" bgcolor=\"#ffffff\">";
      } else {
    ?>

    <body onload="foco_ean(), moveRelogio();" bgcolor="#fff" bgcolor="#ffffff">

      <?php }
        // se não existir operador sair da tela de vendas
        if ($row_chama_venda['operador']=="") {
          echo "<script>alert('ATENÇÃO: O sistema não encontrou nenhum Operador!'); location.href='registrar_nova_venda.php'</script>";
        }

        // Se o operador logado não for o mesmo da compra tirar ele da venda
        // chamar a senha da Session
        if ($_SESSION['operador']!= $row_chama_venda['operador'] ){
          echo"<script>alert('ATENÇÃO: Esta venda não está no seu nome!'); location.href='vendas.php'</script>";
        }

        // verificar se está inativo
        if($row_chama_loja['estado']=='LIBERADO' or $row_chama_loja['estado'] == 'PEDIDO') {

      ?>
      <table width="100%" border="0">
        <tr>
          <td width="100%" style=" color:#F00; font-family:Arial, Helvetica, sans-serif; border:#F00 1px solid; background-color:#FFDDE8; -webkit-border-radius: 8px;">
            <strong>
              <img src="telas e graficos/alert.png" width="32" height="32" />
              Sua liçença expirou!
            </strong>
            <br />
            <br />
            Seu prazo de utilização do programa expirou no dia <strong><?php echo $row_chama_loja['data_final']; ?></strong>,
            <br />
            Se você gostou, e achou que esse software é importante para o seu negócio <a href="#" style=" color:#06F; text-decoration:underline;">clique aqui</a>, e renove sua licença por mais um mês.<br />
            <br />
            Caso a mensagem acima esteja incorreta, favor desconciderar e entrar em contato com o suporte para relatar o fato!
          </td>
        </tr>
      </table>
      <?php } else {
        // verificar se está inativo
        if($row_chama_loja['estado']=='LIBERADO') {
      ?>
      <table width="100%" border="0">
        <tr>
          <td width="100%" style=" color:#F90; font-family:Arial, Helvetica, sans-serif; border: #F90 1px solid; background-color: #FF9; -webkit-border-radius: 8px;">
            <strong>
              <strong>
                <img src="telas e graficos/alert.png" alt="" width="22" height="22" />
              </strong>
              Licença provisória!
              <br />
            </strong>
            Você tem até o dia<strong> <?php echo $row_chama_loja['data_final']; ?></strong> para realizar o pagamento da sua licença e continuar ultilizando este sistema.
            <br />
          </td>
        </tr>
      </table>
      <?php }?>
      <table width="1136" height="735" border="0" align="center" cellspacing="0" style="box-shadow: 0 4px 20px 3px rgba(0, 0, 0, 0.2); margin-top: 1%; border-radius: 3px; background-color: #fff;">
        <tr>
          <td height="40">
            <img src="telas e graficos/Cash-register-icon (1).png" width="30" height="30" style="margin-left: 8px;"/>
          </td>
          <td width="1018" style="font-family: Arial, Helvetica, sans-serif; color: #555; text-align:center;">
            <strong>Sistema Caixa  PDV v 1.02</strong>
          </td>
        </tr>
        <tr>
          <td height="681" colspan="3" style=" background-color: #FFF; border-radius: 3px">
          <table width="1087" border="0" align="center">
            <tr>
              <td height="14" colspan="9" style=" font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:bold; color:#458fc0; text-align: center;">
                <form name="form_relogio" method="POST" action="pagamento.php">
                  Hora:
                  <input name="relogio" type="text" style="font-family: Arial, Helvetica, sans-serif; border:none;" size="10" readonly="readonly">
                  Operador:
                  <input type="text" style="font-family: Arial, Helvetica, sans-serif; border:none;" value="<?php echo $row_chama_venda['operador']; ?>"  size="20" readonly="readonly">
                  Venda nº:
                  <input name="id_venda" type="text" id="id_venda" style="font-family: Arial, Helvetica, sans-serif;  border:none;" value="<?php echo $row_chama_venda['id_venda']; ?>"  size="10" readonly="readonly" />
                  Cód:
                  <input name="cod" type="text" style="font-family: Arial, Helvetica, sans-serif;  border:none;" value="<?php echo $row_chama_venda['cod_venda']; ?>"  size="10" readonly="readonly" />
                  Estado:
                  <input type="text" style="font-family: Arial, Helvetica, sans-serif; border:none;" value="<?php echo $row_chama_venda['estado']; ?>"  size="10" readonly="readonly" />
                </form>

                <?php
                  if($row_chama_venda['estado']=="fechado"){
                    echo"<script>alert('Venda finalizada com sucesso!'); location.href='registrar_nova_venda.php';</script>";
                  }
                ?>
              </td>
            </tr>
            <tr>
              <td height="20" colspan="9" style="padding-bottom:5px ;font-family:Arial, Helvetica, sans-serif; font-size:13px; font-weight:bold; color:#458fc0; background-color: #f2f2f2; border-radius: 3px; text-align: center;">
                <a href="excluir_venda.php?id=<?php echo $row_chama_venda['id_venda']; ?>" title="Sair e excluir está venda">
                  <img src="telas e graficos/bt_menu_principal.png" alt="Cancelar" style="margin-top: 5px; margin-left: 5px;" width="17" height="17" border="0" />
                  Cancelar Venda - (F10)
                </a>
                <span style="color: #555;">
                  |
                </span>
                <a href="vendas.php" title="Continuar a venda outra hora" >
                  <img src="telas e graficos/ico_save.png" width="17" height="17" border="0" style="margin-top: 5px; margin-left: 5px;"/>
                   Salvar e Sair - (F8)
                 </a>
                 <span style="color: #555;">
                   |
                 </span>
                 <a href="pagamento.php?id_venda=<?php echo $row_chama_venda['id_venda']; ?>" title="Registrar Pagamento">
                   <img src="telas e graficos/US-dollar-icon.png" width="17" height="17" border="0" style="margin-top: 5px; margin-left: 5px;"/>
                   Pagamento - (F9)
                 </a>
                 <span style="color: #555;">
                   |
                 </span>
                 <img src="telas e graficos/full_screen.png" width="17" height="17" style="margin-top: 5px; margin-left: 5px;"/>
                 Tela Cheia - (F11)
               </td>
             </tr>
           </DIV>
           <tr>
              <td width="350" rowspan="3" style="background-image:url(telas%20e%20graficos/cupom_bg.png); background-repeat:no-repeat;">
                <div id="scroll" style="color: #000;">
                  <table width="308" border="0">
                    <tr>
                      <td width="302" align="center">
                        * * * * DEMONTRATIVO DE VENDA Nº
                        <strong><?php echo strtoupper($row_chama_venda['id_venda']); ?> * * * *
                          <br />
                          ******************************************
                        </strong>
                      </td>
                    </tr>
                    <tr>
                      <td align="left"><strong><?php echo strtoupper($row_chama_loja['descricao']); ?></strong></td>
                    </tr>
                    <tr>
                      <td align="left">
                        <strong>
                          <?php echo strtoupper($row_chama_loja['rua']); ?>, <?php echo $row_chama_loja['numero']; ?>  <?php echo strtoupper($row_chama_loja['bairro']); ?>
                        </strong>
                      </td>
                    </tr>
                    <tr>
                      <td align="left" >
                        <strong>
                          CEP <?php echo $row_chama_loja['cep']; ?> - <?php echo $row_chama_loja['uf']; ?>
                        </strong>
                      </td>
                    </tr>
                    <tr>
                      <td align="left">
                        <strong>
                          CNPJ - CPF: <?php echo $row_chama_loja['cnpj']; ?><br />
                          IE - RG: <?php echo $row_chama_loja['ie']; ?>
                        </strong>
                      </td>
                    </tr>
                    <tr>
                      <td align="left">
                        <?php echo $row_chama_venda['data_venda']; ?> <?php echo $row_chama_venda['hora_venda']; ?> LOJA 00<?php echo $row_chama_loja['id']; ?>
                      </td>
                    </tr>
                  </table>
                  <br />
                  ***********************************************
                  <br />
                  <table width="324" border="0">
                    <tr>
                      <td width="321" align="left">
                        <?php if ($totalRows_listar_vendidos == 0) { // Show if recordset empty ?>
                          <strong><em> ** DEMONSTRATIVO SEM VALOR **</em></strong>
                        <?php } // Show if recordset empty ?>
                        <br />
                        ITEM  - CóDIGO - DESCRIÇÂO -  <br />
                        * * QTD. UND. x VL. UNTI (R$) =  VL. TOTAL(R$)<br />
                        <br />
                        <?php if ($totalRows_listar_vendidos > 0) { // Show if recordset not empty ?>
                          <?php $cont = 01; do { ?>
                            <a href="?excluir=<?php echo $row_listar_vendidos['id_vendido']; ?>&descontar=<?php echo $row_listar_vendidos['total_und']; ?>&liquido=<?php echo $row_chama_venda['valor']; ?>&venda=<?php echo $row_chama_venda['id_venda']; ?>&ean=<?php echo $row_listar_vendidos['ean_vendido']; ?>&qtd=<?php echo $row_listar_vendidos['quantidade']; ?>" title=" excluir ' <?php echo $row_listar_vendidos['descricao_vendido']; ?>'">
                              <img src="telas e graficos/manipulacao_botao_excluir.png" width="15" height="15" border="0" />
                            </a>
                            :.<strong><?php echo "$cont"; ?></strong> - <?php echo $row_listar_vendidos['ean_vendido']; ?> - <strong><?php echo $row_listar_vendidos['descricao_vendido']; ?></strong><br />
                            * * <?php echo $row_listar_vendidos['quantidade']; ?> X
                            <?php
                              $valor1 =$row_listar_vendidos['valor_vendido'];
                              echo number_format($valor1, 2, ',', '.');
                            ?>
                            =<strong>
                              <?php
                                $valor1 =$row_listar_vendidos['total_und'];
                                echo number_format($valor1, 2, ',', '.');
                              ?>
                            </strong>
                            <br />
                            ***********************************************
                            <br />
                            <?php $cont++;} while ($row_listar_vendidos = mysql_fetch_assoc($listar_vendidos)); ?><?php } // Show if recordset not empty ?>
                      </td>
                    </tr>
                    <tr>
                      <td align="left">
                        <p>
                          OPERADOR: <strong><?php echo $row_chama_venda['operador']; ?></strong><br />
                        </p>
                        <br />
                        VALOR TOTAL:
                        <strong style="font-size:15px;"> R$
                          <?php
                            $valor = $row_chama_venda['valor'];
                            echo number_format($valor, 2, ',', '.');
                          ?>
                        </strong>
                        <br />
                      </td>
                    </tr>
                    <tr>
                      <td align="center">
                        <br/>
                        *********** FIM DO TICKET Nº <strong><?php echo strtoupper($row_chama_venda['id_venda']); ?></strong> ***********<br />
                        <br/>
                        SOFTWARE VERSAO 1.02 PHP ONLINE <br/>
                        CLIENTE LICENCIADO DESDE: <?php echo $row_chama_loja['data_inicio']; ?> <br/>
                        <br/>
                        Sistema de automação para pequenos e médios comércios
                      </td>
                      <DIV class="invisivel">
                    </tr>
                  </table>
                </div>
              </td>
              <td colspan="8" style=" font-size:17px; font-family:Arial, Helvetica, sans-serif;">
                <form id="ean" name="ean" method="post" action="venda.php">
                  <table width="703" border="0">
                    <tr>
                      <td width="134">
                        <p style="padding: 5px; color: #000; text-align: center; font-size: 17px;">
                          <span>
                            Código:
                          </span>
                        </p>
                      </td>
                      <td width="364" style="">
                        &nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <input name="cod_ean" type="text" id="cod_ean" style=" font-size:17px; color:#555; border:solid 1px #000; border-radius: 2px; padding-left: 3px; width: 250px; text-align:left; outline:none;"/>
                      </td>
                      <td width="200">
                        <span style="font-family:Arial, Helvetica, sans-serif; font-size:17px; font-weight:bold; border-radius: 2px;  border: solid 1px #555; color:#555; padding: 5px;">
                          <a href="#" onclick="document.getElementById('apDiv1').style.display='block';" >
                            <img src="telas e graficos/search-b-icon.png" width="17" height="17"/>
                            Buscar Produto
                          </a>
                        </span>
                      </td>
                    </tr>
                  </table>
                </form>
              </td>
            </tr>
            <tr>
              <td height="297" colspan="8" style="font-family:Arial, Helvetica, sans-serif;">
                <form action="" method="post" id="add" name="add" >
                  <table width="761" border="0">
                    <tr>
                      <td style="width: 150px">
                        <p style="padding: 5px; font-size: 17px;color: #5f5f5f; text-align: center;">
                          <span>
                            Produto:
                          </span>
                        </p>
                      </td>
                      <td>
                        &nbsp; &nbsp;
                        <input name="desc_prod" type="text" id="desc_prod" style="font-size: 17px; color: #666; border:solid #000 1px; outline:none; border-radius: 2px; padding-left: 2px;" value="<?php echo $row_chama_produto['descricao_prod']; ?>" size="44" onkeypress="return handleEnter(this, event)"  />
                      </td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="width: 150px;">
                        <p style="padding: 5px; font-size: 17px; color: #5f5f5f; text-align: center;">
                          <span>
                            Valor Unitário:
                          </span>
                        </p>
                      </td>
                      <td >
                        &nbsp; &nbsp;
                        <input name="valor_prod" type="text" id="valor_prod" style=" font-size: 17px; color: #666; border:solid #000 1px; border-radius: 2px; outline:none; padding-left: 2px;" onkeydown="return handleEnter(this, event)" onkeyup="SubstituiVirgulaPorPonto(this)" value="<?php echo $row_chama_produto['valor']; ?>" size="8"  />
                      </td>
                      <td style=" font-size:10px;">&nbsp;</td>
                    </tr>
                    <tr>
                      <td style="width: 150px;">
                        <p style="padding: 5px; font-size: 17px; color: #5f5f5f; text-align: center;">
                          <span>
                            Quantidade:
                          </span>
                        </p>
                      </td>
                      <td>
                        &nbsp; &nbsp;

                      </td>
                      <td rowspan="4">
                        <p>&nbsp;</p>
                      </td>
                    </tr>
                    <tr>
                      <td style="width: 150px;">
                        <p style="padding: 5px; font-size: 17px; color: #5f5f5f; text-align: center;">
                          <span>
                            Sub Total:
                          </span>
                        </p>
                      </td>
                      <td style="">
                        &nbsp; &nbsp;

                      </td>
                    </tr>
                    <tr>
                      <td  style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td  style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">&nbsp;</td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="60" colspan="2">
                        <table width="686" border="0">
                          <tr>
                            <td style="width: 150px;">
                              <p style="padding: 5px; font-size: 20px; color: #458fc0; text-align: center;">
                                <span>
                                  Total:
                                </span>
                              </p>
                            </td>
                            <td align="left" style="">
                              &nbsp;&nbsp;
                              <input name="totaldemo" type="text" id="totaldemo" style=" font-size: 20px; color: #458fc0; border-radius: 2px; text-align:right; border: solid 1px #458fc0;" onkeyup="SubstituiVirgulaPorPonto(this)" value="<?php $valor = $row_chama_venda['valor'];
                              echo number_format($valor, 2, ',', '.');
                              ?> " size="8" readonly="readonly"/>
                              <input name="total" type="hidden" id="total" style=" font-size:22px; color:#999; text-align:right;" onkeyup="SubstituiVirgulaPorPonto(this)" value="<?php echo $row_chama_venda['valor']; ?>" size="6"/>
                            </td>
                            <td align="right"></td>
                          </tr>
                        </table>
                      </td>
                      <td>&nbsp;</td>
                    </tr>
                    <tr>
                      <td height="72" colspan="2">
                        <p>
                          <input type="image" name="add_prod" id="add_prod" src="telas e graficos/adicionar_prod_btn.png" width="1px" height="1px" />
                          <span style=" font-size:10px;">
                            <input name="id_prod" type="hidden" id="id_prod" value="<?php echo $row_chama_produto['id_prod']; ?>" />
                            <input name="id_venda" type="hidden" id="id_venda" value="<?php echo $row_chama_venda['id_venda']; ?>" />
                            <input name="ean" type="hidden" id="ean" value="<?php echo $row_chama_produto['cod_ean']; ?>" />
                          </span>
                        </p>
                      </td>
                      <td>&nbsp;</td>
                    </tr>
                  </table>
                </form>
              </td>
            </tr>
            <tr>
              <td><br /></td>
              <td width="3">&nbsp;</td>
              <td width="71">&nbsp;</td>
              <td width="44">&nbsp;</td>
              <td width="57">&nbsp;</td>
              <td width="248">&nbsp;</td>
              <td width="140">&nbsp;</td>
              <td width="140">&nbsp;</td>
              <td width="30" align="right">&nbsp;</td>
            </tr>
          </table>
          <div id="apDiv1" class="apDiv1">
            <table width="518" align="center" style="border:1px solid #CCC; border-radius: 2px; margin-top: 5px; margin-bottom: 5px;">
              <tr>
                <td height="40" colspan="2">
                  <strong>Busca por produtos:</strong><br />
                  Clique no produto para obter informações.
                </td>
                <td width="30">
                  <a href="#" onclick="document.getElementById('apDiv1').style.display='none';" >
                    <img src="telas e graficos/bt_menu_principal.png" width="25" height="25" />
                  </a>
                </td>
              </tr>
              <tr>
                <td width="219" style="font-size: 15px">Cód:</td>
                <td colspan="2" style="font-size: 15px">Descrição:</td>
              </tr>
            </table>
            <div id="produts" style="height:130px; overflow:auto;">
              <table width="518" border="solid 1px #cccccc" style="border-radius: 2px;" align="center">
                <?php do { ?>
                  <tr>
                    <td width="60" style=" height: 16px; padding: 3px; border: solid 1px #999; border-radius: 1px; font-size:12px; text-align: left;">
                      <strong>
                        <a href="javascript:;" onclick="cod_ean.value = &quot;<?php echo $row_chama_prods['cod_ean']; ?>&quot;; document.ean.submit()">
                          <?php echo $row_chama_prods['cod_ean']; ?>
                        </a>
                      </strong>
                    </td>
                    <td style="font-size:12px; border: solid 1px #999; border-radius: 1px; padding-left: 2px;">
                      <strong>
                        <a href="javascript:;" onclick="cod_ean.value = &quot;<?php echo $row_chama_prods['cod_ean']; ?>&quot;; document.ean.submit()">
                          <?php echo $row_chama_prods['descricao_prod']; ?>
                        </a>
                      </strong>
                    </td>
                  </tr>
                  <?php } while ($row_chama_prods = mysql_fetch_assoc($chama_prods)); ?>
                </table>
              </div>
              <table width="484" border="0" align="center">
                <tr>
                  <td width="436" style=" font-size:12px;">
                    ** Para busca avançada do item precione as teclas <strong>CTRL + F</strong>, e digite os dados, os itens semelhantes serão destacados **
                  </td>
                </tr>
              </table>
            </div>
          </td>
        </tr>
      </table>
      <?php  }?>
    </body>
  </DIV>
</html>
<?php
  mysql_free_result($chama_produto);

  mysql_free_result($chama_venda);

  mysql_free_result($listar_vendidos);

  mysql_free_result($chama_loja);

  mysql_free_result($chama_operador);

  mysql_free_result($chama_prods);
?>
