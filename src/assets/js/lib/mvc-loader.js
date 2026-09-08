(function ($) {

    $(".js-mobile-menu").click(function(){
        $(this).parents(".js-header-menu").toggleClass("_active");
    });

    window.mvc_reload_event = function($parent) {
        $(document).trigger("mvc_reload_event", $parent);
    }


    window.ajax_form_send = function(object) {
        const formAjax = new FormAjax($(object).data("action"));


        $(object)
            .data("params")
            .split("&")
            .forEach(item => {
                const [param, value] = item.split("=");
                formAjax.add_param(param, value);
            });

        formAjax.send();
    }

    window.ajax_html = function(object) {

        let url = mvc_setting.mvc_ajaxurl;
        let action = $(object).data("action");
        let params = "action="+action+"&"+$(object).data("params") ;
        let idHtmlBlock = $(object).data("html");
        let objectLoaded = object;

        $( "#" + idHtmlBlock ).addClass("loading");


        $.ajax({
            type: "POST",
            data: params,
            url: url,
            async: true,
            data_params: {
                param: params,
                html_block: $( "#" + idHtmlBlock ),
                object_loaded: objectLoaded
            },
            error: function (jqXHR,   textStatus,   errorThrown){
                console.log(jqXHR);
                let msg = "Error: " +errorThrown;
                if ( this.data_params.html_block.hasClass("popup")  ) {
                    this.data_params.html_block.find(".popup-content").html(msg);
                } else {
                    this.data_params.html_block.html(msg);
                }
            },
            complete: function () {
                this.data_params.html_block.removeClass("loading");
            },
            success: function (data) {
                let htmlBlock =  this.data_params.html_block;

                htmlBlock.html(data);

                if ( htmlBlock.attr("trigger") !== undefined) {
                    $params = {
                        "html" : data,
                        "dom" :  htmlBlock
                    };
                    $(document).trigger( htmlBlock.attr("trigger"),  $params );
                }

                mvc_reload_event( htmlBlock );

                if ( htmlBlock.hasClass("popup")  ) {

                    $.fancybox.open({src  : "#"+htmlBlock.attr("id"),
                        padding: 0,
                        margin: 0,
                        modal: false,
                        touch: false,
                        baseClass: "fancybox-custom-bg",
                        animationEffect: 'slide-in-out',
                        transitionEffect: "zoom-in-out",
                        showCloseButton : true,
                        toolbar: false,
                        backFocus : false,
                        afterClose : function (instance, current){
                            if ( $(current.src).hasClass("b-popup-dynamic") ) {
                                $(current.src).remove();
                            }
                        }
                    });

                }

            }
        });

        return false;
    }

    //Событие submit для форм с аякс сохранением
    $(document).on("mvc_reload_event", function(e, $parent) {

        $(".js-ajax-form", $parent).submit(function(e) {

            if ($(this).attr("id") == "" || $(this).attr("id") == undefined) {
                $(this).attr("id", "mvc-form-ajax-" + ( $("js-mvc-from-ajax").length + 1 ) );
            }

            let formajax = new FormAjax( $(this).attr("action"), $(this).attr("id") );

            formajax.add_trigger($(this).attr("id"));
            if ($(this).attr("trigger") ) {
                formajax.add_trigger( $(this).attr("trigger") );
            }

            const submitButton = $(':submit', this).filter(':focus');

            if (submitButton.length) {
                const name = submitButton.attr('name');
                const value = submitButton.val();
                if (name && value) {
                    formajax.add_param(name, value);
                }
            }
            formajax.send();
            return false;

        });

    });

    $(document).ready(function() {
        mvc_reload_event( $("body") );
    });


    generate_uniq_id = function(){
        min = Math.ceil(100);
        max = Math.floor(999);

        let newid = Math.floor(Math.random() * (max - min + 1)) + min;
        if ( $("[id='" + newid + "']",parent).length == 0) {
            return newid;
        }

        return generate_id();
    }

     mvc_add_popup = function( _html, _form_size ) {
        let id_form = "mvc-popup-"+generate_uniq_id();
        let form_size = _form_size ? _form_size : "midi";
        let html = '<div class="mvc-popup mvc-popup-'+form_size+' mvc-popup-dynamic"  id="'+id_form+'">\n' +
            '\t<div class="mvc-popup-content"  >\n' +
            '\t\t\n' + _html +
            '\t</div>\n' +
            '</div>';
        $("body").append(html);

        mvc_reload_event($("#"+id_form));

        $.fancybox.open({src  : '#'+id_form,
            padding: 0,
            margin: 20,
            modal: false,
            touch: false,
            baseClass: "fancybox-custom-bg",
            animationEffect: 'zoom',
            transitionEffect: "zoom-in-out",
            speed: 350,
            transitionDuration: 300,
            smallBtn : true,
            backFocus : false,
            afterClose : function (instance, current){
                if ( $(current.src).hasClass("cf-popup-dynamic") ) {
                    $(current.src).remove();
                }
            }
        });

    }


    $(document).on("mvc_reload_event", function(e, parent) {

        $(".js-load-image", parent).change(function() {
            let preloadImage = $(this).parent().parent().find(".js-file-preview"),
                preloadText = $(this).parent().parent().find(".js-file-text");

            let maxSizes = 8 * 1048576;
            let sumSizes = 0;
            for(let i = 0; i < this.files.length; i++) {
                sumSizes += this.files[i].size;
            }

            if (sumSizes > maxSizes) {
                $(this).after('<span class="invalid-feedback" >Файлы имеют размер > 8 Мб</span>');
                $(this).val("");
                return false;
            }

            if (preloadText) {
                preloadText.html( this.files[0].name );
            }

            for(let i = 0; i < this.files.length; i++) {

                if (this.files[i].type.indexOf("image/") !== -1) {
                    let reader = new FileReader();

                    reader.onload = function (e) {
                        if (preloadImage) {
                            preloadImage.attr('src', e.target.result);

                            if (preloadImage.parent().hasClass("js-fancybox")) {
                                preloadImage.parent().attr("href", e.target.result);
                            }
                        }
                    }

                    reader.readAsDataURL( this.files[i] );

                }

            }


        });

    });

    $(document).bind("mvc_reload_event", function($event, parent) {

        $(".js-click-ajax", parent).click(function(e) {
            ajax_form_send(this);
        });

        $(".js-click-ajax-html", parent).click(function(e) {
            ajax_html(this);
        });

        $(".js-load-ajax-html", parent).each(function(e) {
            ajax_html(this);
        });



    });


})(jQuery);

