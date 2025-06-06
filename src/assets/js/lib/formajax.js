(function($) {
	
FormAjax = function ($action, $id) {
    this.action = $action;
    this.dom = $("#" + $id);
    this.params = [];
    this.files_input = []; 
    this.files = [];
    this.func = false;
    this.async = true; 
    this.trigger = false;
    this.blobs = [];
    this.add_param = function ($param, $value) {
      if ($param !== undefined) {
        this.params.push([$param, $value]);        
      }
    }
    this.add_file = function ($param, $id) {
        this.files_input.push([$param, $id]);
    }
    this.add_trigger = function($trigger) {
        this.trigger = $trigger;
    }
    this.add_func = function(func) {
        this.func = func;
    }
    this.add_blob = function(paramname, blob, filename) {
        if (paramname && blob && filename) {
            this.blobs.push({
                paramname: paramname,
                blob: blob,
                filename: filename
            })
        }
    }
    this.create_params = function( ) {
        let scope = this;
      $("input[type!='file'][type!='checkbox'][type!='radio'][type!='submit'], textarea, select", this.dom).each(function(){
          let value = $(this).val();
          if (value) {
              scope.add_param( $(this).attr("name") , value);
          } else {
              scope.add_param( $(this).attr("name") , "");
          }

      });

      $("input[type='radio']:checked", this.dom).each( function() {
        scope.add_param( $(this).attr("name") , $(this).val()); 
      });

      $("input[type='checkbox']:checked", this.dom).each( function() {
        scope.add_param( $(this).attr("name") + ( $(this).hasClass("checkbox-one") ? "" : "[]"), $(this).val()); 
      });

      $("input[type='file']", this.dom).each(function() {

        if ($(this).attr("id") == "" || $(this).attr("id") == undefined) {
          $(this).attr("id", "input-file-" + ( $("input[type='file']").length + 1 ) );
        }

        scope.add_file( $(this).attr("name") , $(this).attr("id"));
      });
    }
    this.send = function () {

        if ( this.dom  && this.dom.hasClass("sender") ) {
            return false;
        }

        this.dom.find("#errordiv").html('');
        this.dom.find(".invalid-feedback").remove();
        this.dom.find(".error-wrap").hide();
        this.dom.find(".is-invalid").removeClass("is-invalid");

        $params = '';

        var formData = new FormData();
        //formData.append('action', this.action);

        this.params.forEach(function ($item, $i) {
            formData.append($item[0], $item[1]);
        });

        this.files_input.forEach(function ($item, $i) {
            jQuery.each(jQuery('#' + $item[1])[0].files, function (i, file) {
                formData.append($item[0], file);
            });
        });

        this.files.forEach(function ($item, $i) {
            formData.append($item.name, $item.file);
        });

        if ( this.blobs.length > 0 ) {
            for(let i = 0; i < this.blobs.length; i++) {
                if (this.blobs[i].blob instanceof Blob ) {
                    formData.append(this.blobs[i].paramname, this.blobs[i].blob, this.blobs[i].filename);
                }
            }
        }

        //this.dom.addClass("sender"); 
        formsubmitajax = this.dom; 
        formsubmitajax.addClass("form__loading");
 
        let url = mvc_setting.mvc_ajaxurl;

        formData.append("action", this.action);
			
        $.ajax({
           type: "POST",
            data: formData,
            url: url, 
            dataType: "json",
            async: this.async,
            cache: false,
            func: this.func,
            contentType: false,
            processData: false, 
            formajax: this, 
            complete: function () {
              this.formajax.dom.removeClass("form__loading");
            },
            error : function (res ) {
                if ( res.responseJSON &&  res.responseJSON.msg) {
                    data = res.responseJSON;
                    if (formsubmitajax.find(".error-wrap").length == 0) {
                        formsubmitajax.append("<div class='error-wrap'></div>");
                    }
                    formsubmitajax.find(".error-wrap").html(data.msg);
                    formsubmitajax.find(".error-wrap").show();
                }
            },
            success: function (data) {
                if (data.result === "error") {
                    if (typeof data.msg === 'string' || data.msg instanceof String) {
                        if ($(".response_error", formsubmitajax).length > 0) {
                          $(formsubmitajax).find(".response_error").html(data.msg); 
                        }  
                    }
 
                    if (data.fielderror && data.fielderror.length) {
                        $.each(data.fielderror, function ($ind, $elem) {
                            if ($elem.field == "username") {
                              $elem.field = "email";
                            }
                            $fielderror = $(formsubmitajax).find("[name='" + $elem.field+"']");

                            if ($fielderror.length === 0) {
                                $(formsubmitajax).find(".response_error").append($elem.msg + "<br>");
                            } else {
                                if ($fielderror.attr("type") == "checkbox"  ) {
                                  $choice = $fielderror.parent( );
                                  $choice.after('<span class="invalid-feedback" >'+ $elem.msg +'</span>');

                                } else if (  $fielderror.attr("type") == "radio") {
                                  $choice = $fielderror.parent( ).parent( );
																	if ( $choice.find(".invalid-feedback").length == 0) {
	                                  $choice.after('<span class="invalid-feedback" >'+ $elem.msg +'</span>'); 
																	}
                                } else {
                                  $fielderror.addClass("is-invalid");
                                    $fielderror.after('<span class="invalid-feedback" >' + $elem.msg + '</span>');
                                }

                            }
                        });
                    }

                  if (data.msg !== undefined && data.msg != "") {
                    if (formsubmitajax.find(".error-wrap").length == 0) {
                      formsubmitajax.append("<div class='error-wrap'></div>");
                    }
                    formsubmitajax.find(".error-wrap").html(data.msg);
                    formsubmitajax.find(".error-wrap").show();
                  }
                } else if (data.result == "success") {

                    if ( formsubmitajax.attr("trigger") !== undefined) {
                        triggers = formsubmitajax.attr("trigger").split(" ");
                        triggers.forEach( function(item,i) {
                            jQuery(document).trigger( item, data, formsubmitajax );
                        });
                    } else if (this.formajax.trigger ) {
                        let formajax = this.formajax;
                        jQuery(document).trigger( this.formajax.trigger, [data, formajax] );
                    }

                  if (data.type == "popup" ) {
                      $popup_content = $("#"+this.formajax.dom.parents(".popup").first().attr("id")).find(".popup-content");
                      if ($popup_content.length > 0    ) {
                          if ( data.msg.indexOf("popup-content-padding-big") == -1 ) {
                              $popup_content.html("<div class='popup-content-padding-big'>"+data.msg+"</div>");
                          } else {
                              $popup_content.html(data.msg);
                          }

                          mvc_reload_event( $popup_content );
                      } else {
                          mvc_add_popup(data.msg);
                      }

                      if (data.redirect) {
                          timeout = data.timeout ? data.timeout : 1500;
                          setTimeout(() => {
                              location.href = data.redirect;

                          }, timeout );
                      }
                      if (data.reload) {
                          timeout = data.timeout ? data.timeout : 1500;
                          setTimeout(() => {
                              window.location.reload();
                          }, timeout );
                      }

                  } else if (data.type == "redirect" ) { 
                    location.href = data.redirect;
                  }  else if (data.type == "reload") {
                    window.location.reload();
                  } else if ( data.type == "closepopup" ) {
                      $.fancybox.close({src  : "#"+this.formajax.dom.parents(".popup").first().attr("id")});
                  } else if (data.type == "replace" && data.replaceSelector ) {
                        $(data.replaceSelector).html(data.content);
                        mvc_reload_event( $(data.replaceSelector) );
                    }

                  if (this.func) {
                    this.func(data);
                  }



                }
              
              //console.log(formsubmitajax);
            }
        });  
    } 
		
		this.create_params();
	} 
 
}(jQuery));