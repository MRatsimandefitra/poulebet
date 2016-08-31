$(document).ready(function(){

   $('#selection').click(function(){
       if($('#selection').is(':checked')){
           $("input[type=checkbox]").each(function(){
               $(this).prop("checked", true);
//               $(this).attr("checked", "checked");
           });
       }else{
           $("input[type=checkbox]").each(function(){
               $(this).prop("checked", false);
           });
       }
   });
   $('#check_all_role').click(function(){
       $("input.checking_role").prop("checked",true);
   });
   $('#uncheck_all_role').click(function(){
       $("input.checking_role").prop("checked",false);
   });
    $('label#lecture').click(function(){
        if($('table.table-edit-role td.lecture input[type=checkbox]').is(':checked')){
            $('table.table-edit-role td.lecture input[type=checkbox]').each(function(){
                $(this).prop("checked", false);
            });
        }else{
            $('table.table-edit-role td.lecture input[type=checkbox]').each(function(){
                $(this).prop("checked", true);
            });
        }
        /*
        $('table.table-edit-role td.lecture input[type=checkbox]').each(function(){
            $(this).prop("checked", true);
        });*/
    });

    $('label#ajout').click(function(){
        if($('table.table-edit-role td.ajout input[type=checkbox]').is(':checked')){
            $('table.table-edit-role td.ajout input[type=checkbox]').each(function(){
                $(this).prop("checked", false);
            });
        }else{
            $('table.table-edit-role td.ajout input[type=checkbox]').each(function(){
                $(this).prop("checked", true);
            });
        }
        /*
         $('table.table-edit-role td.lecture input[type=checkbox]').each(function(){
         $(this).prop("checked", true);
         });*/
    });

    $('label#modification').click(function(){
        if($('table.table-edit-role td.modification input[type=checkbox]').is(':checked')){
            $('table.table-edit-role td.modification input[type=checkbox]').each(function(){
                $(this).prop("checked", false);
            });
        }else{
            $('table.table-edit-role td.modification input[type=checkbox]').each(function(){
                $(this).prop("checked", true);
            });
        }
        /*
         $('table.table-edit-role td.lecture input[type=checkbox]').each(function(){
         $(this).prop("checked", true);
         });*/
    });

    $('label#suppression').click(function(){
        if($('table.table-edit-role td.suppression input[type=checkbox]').is(':checked')){
            $('table.table-edit-role td.suppression input[type=checkbox]').each(function(){
                $(this).prop("checked", false);
            });
        }else{
            $('table.table-edit-role td.suppression input[type=checkbox]').each(function(){
                $(this).prop("checked", true);
            });
        }
        /*
         $('table.table-edit-role td.lecture input[type=checkbox]').each(function(){
         $(this).prop("checked", true);
         });*/
    });


});