function atualizaComboGruposQuest(){
    var codQuest = $("#codQuestionario").get(0).value;

    $.ajax({
        type: "POST",
        url: "ajax/buscaGrupoQuest.php",
        data: {codQuest: codQuest}, //"name=John&location=Boston",
        success: function(msg) {
            $("#div_GrupoQuest").html(msg);
        }
    });
}