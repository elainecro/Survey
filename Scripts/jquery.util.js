function buscaCep(codCliente){
	var strCEP = $("#strCEP").get(0).value;
    var intNum = $("#intNum").get(0).value;
	var strComplemento = $("#strComplemento").get(0).value;
	//alert("Entrou: "+ strCEP);

    $.ajax({
        type: "POST",
        url: "ajax/buscaCEP.php",
        data: { strCEP: strCEP, codCliente: codCliente, strComplemento: strComplemento, intNum: intNum}, //"name=John&location=Boston",
        success: function(msg) {
            $("#div_Endereco").html(msg);
        }
    });
}

function defineCampoValorProp(strTela,codCadastro){
    var codPropriedade = $("#strNovaPropriedade").get(0).value;
    //alert("Nova Propriedade: "+ strPropriedade);

    $.ajax({
        type: "POST",
        url: "ajax/defineCampoValorProp.php",
        data: { codPropriedade: codPropriedade, strTela: strTela, codCadastro: codCadastro}, //"name=John&location=Boston",
        success: function(msg) {
            $("#div_cadProp").html(msg);
        }
    });
}

function addPropriedade(strTela, codItem){
    var codPropriedade = $("#strNovaPropriedade").get(0).value;
    var strValorProp = $("#strValorProp").get(0).value;
    //alert("Nova Propriedade: "+ strPropriedade);

    $.ajax({
        type: "POST",
        url: "ajax/addPropriedade.php",
        data: { codPropriedade: codPropriedade, strValorProp: strValorProp, strTela: strTela, codItem: codItem}, //"name=John&location=Boston",
        success: function(msg) {
            $("#div_tablePropriedades").html(msg);
        }
    });
}

function atualizaTablePropriedades(strTela, codItem){
    $.ajax({
        type: "POST",
        url: "ajax/atualizaTablePropriedades.php",
        data: {strTela: strTela, codItem: codItem}, //"name=John&location=Boston",
        success: function(msg) {
            $("#div_tablePropriedades").html(msg);
            $("#strValorProp").attr('value', '');
        }
    });
}

function atualizaComboPropriedades(strTela, codItem){
    $.ajax({
        type: "POST",
        url: "ajax/atualizaComboPropriedades.php",
        data: {strTela: strTela, codItem: codItem}, //"name=John&location=Boston",
        success: function(msg) {
            $("#div_ComboPropriedades").html(msg);
        }
    });
}