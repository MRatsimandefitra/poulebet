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
       $("#deselectAll").removeAttr("name");
   });
   $('#uncheck_all_role').click(function(){
       $("input.checking_role").each(function(){
           $(this).prop("checked",false);
       });
       $("#deselectAll").prop("name","deselectAll");
       $("#deselectAll").attr("value","true");
   });
   $('.checking_role').click(function(){
       var nb = $(".checking_role:checked").length;
       if (nb === 0){
            $("#deselectAll").prop("name","deselectAll");
           $("#deselectAll").attr("value","true");
       }
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