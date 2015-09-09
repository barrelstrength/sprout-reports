$(function(){
    $("#exportCsvButton").click(function(e){
        e.preventDefault();
        e.stopPropagation();
        $("#exportCsvField").attr("value",1);
        $(this).parent().submit();
    });
});