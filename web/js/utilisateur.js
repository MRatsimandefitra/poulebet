$(document).ready(function(){
    $('select.nbaffiche').change(function(){
        
    });

    $('a#supprimer-utilisateur').click(function(){

        var response = confirm("Etes vous sur de vouloir supprimer cet utilisateur ?");
        if(!response){
            return false;
        }
        return true;
    });


});
function onSelectChange(value){
    document.getElementById('nbpage').submit();
    xhttp.open("GET", null, false);
    xhttp.send();

}