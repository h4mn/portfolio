$(document).ready(function(){
    $("#analisar").click(function(){
        var sequencia = ($("#sequencia").val()+'').replace(/ /g, '+');
        $("#saida").load("/processa-velha/analisar/" + sequencia);
    });
});
