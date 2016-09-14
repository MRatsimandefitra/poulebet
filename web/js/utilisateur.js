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
    $('#check_all').click(function(){
         $('input:checkbox').not(this).prop('checked', "checked");
    });
    $('#uncheck_all').click(function(){
        $("input.checking_users").removeAttr("checked");
    });



});
function supprimer(msg){
    var response = confirm(msg);

    if(response == false){
        return false;
    }else{
        return true;
    }

}
function onSelectChange(value){
    document.getElementById('nbpage').submit();
    xhttp.open("GET", null, false);
    xhttp.send();

}
function getParams(){
    var isChecked = document.getElementById('withParams').checked;
    if(isChecked){
      //  console.log('is cheked an ');
        var dateDebut = document.getElementById('dateDebut').value;
        console.log("Date debut " + dateDebut);
        var dateFinale = document.getElementById('dateFinale').value;
        var championnat = document.getElementById('championat_match').value;

        var dateDebutGoalApi = document.getElementById('dateDebutGoalApi').value = dateDebut;
        var dateFinaleGoalApi = document.getElementById('dateFinaleGoalApi').value = dateFinale;
        var championnatGoalApi = document.getElementById('championat_goal_api').value = championnat;
    }else{
        console.log("vider ihany");
        var dateDebutGoalApi = document.getElementById('dateDebutGoalApi').value = "";
        var dateFinaleGoalApi = document.getElementById('dateFinaleGoalApi').value = "";
        var championnatGoalApi = document.getElementById('championat_goal_api').value = "";
    }


}