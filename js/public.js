/**
 * Author: Gerits Aurelien <aurelien[at]magix-cms[point]com>
 * Copyright: MAGIX CMS
 * Date: 16/06/14
 * Time: 21:39
 * License: Dual licensed under the MIT or GPL Version
 */
var MC_plugins_maillingchimp = (function ($, undefined) {
    //Fonction Private
    function add(iso){
        // *** Set required fields for validation
        $("#maillingchimp-form").validate({
            onsubmit: true,
            event: 'submit',
            rules: {
                lastname_chimp: {
                    required: true,
                    minlength: 2
                },
                firstname_chimp: {
                    required: true,
                    minlength: 2
                },
                email_chimp: {
                    required: true,
                    email: true
                }
            },
            submitHandler: function(form) {
                $.nicenotify({
                    ntype: "submit",
                    uri: '/'+iso+'/maillingchimp/',
                    typesend: 'post',
                    idforms: $(form),
                    resetform:true,
                    successParams:function(data){
                        $.nicenotify.notifier = {
                            box:"",
                            elemclass : '.mc-message-chimp'
                        };
                        $.nicenotify.initbox(data,{
                            display:true
                        });
                        setTimeout(function() {
                            $('#modal-mailling').modal('hide');
                        }, 3000);
                    }
                });
                return false;
            }
        });
    }
    return {
        //Fonction Public
        run:function (iso) {
            add(iso);
        }
    };
})(jQuery);