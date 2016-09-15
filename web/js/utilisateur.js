$(document).ready(function () {
    $('select.nbaffiche').change(function () {

    });

    $('a#supprimer-utilisateur').click(function () {
        var response = confirm("Etes vous sur de vouloir supprimer cet utilisateur ?");
        if (!response) {
            return false;
        }
        return true;
    });
    $('a#supprimer-masterprono').click(function () {
        var response = confirm("Etes vous sur de vouloir supprimer le master prono ?");
        if (!response) {
            return false;
        }
        return true;
    });
    $('#check_all').click(function () {
        $('input:checkbox').not(this).prop('checked', "checked");
        $('#all_user').val("true");
    });
    $('#uncheck_all').click(function () {
        $("input.checking_users").removeAttr("checked");
        $("#all_user").removeAttr("value");
    });


});
function supprimer(msg) {
    var response = confirm(msg);
    if (response == false) {
        return false;
    } else {
        return true;
    }

}
function onSelectChange(value) {
    document.getElementById('nbpage').submit();
    xhttp.open("GET", null, false);
    xhttp.send();

}
function getParams() {
    var dateDebut = document.getElementById('dateDebut').value;
    var dateFinale = document.getElementById('dateFinale').value;
    var championnat = document.getElementById('championat_match').value;

    var dateDebutGoalApi = document.getElementById('dateDebutGoalApi').value = dateDebut;
    var dateFinaleGoalApi = document.getElementById('dateFinaleGoalApi').value = dateFinale;
    var championnatGoalApi = document.getElementById('championat_goal_api').value = championnat;

}
function cancelGoalapi() {
    var dateDebutGoalApi = document.getElementById('dateDebutGoalApi').value = "";
    var dateFinaleGoalApi = document.getElementById('dateFinaleGoalApi').value = "";
    var championnatGoalApi = document.getElementById('championat_goal_api').value = "";

}
/*
 function deleteProno(msg){
 var msgbox = confirm(msg);
 if(msgbox == false){
 return false;
 }else{
 return true;
 }
 }*/
function annulerProno() {
    // document.getElementById('annuler-filtre-prono');
    document.getElementById('dateDebut').value = "";
    document.getElementById('dateFinale').value = "";
    document.getElementById('championat_match').value = "";
    document.getElementById('pays_match').value = "";
    document.getElementById('match_status').value = "";
}