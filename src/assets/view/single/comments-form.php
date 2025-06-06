<style>
    .fc-comment-form-container {
        background: #E8F9FF;
        padding: 50px 0px 70px;
    }
    .fc-post-container h3.fc-comment-title {
        color: #131049;
        font-size: 2em;
        margin-top: 0px;
        text-align: left;
        padding-left: 0px;
    }
    .fc-comment-respond-container {
        width: 730px;
        max-width: 100%;
        margin: 0px auto;
        padding: 0px 15px;
    }
    .fc-comment-respond {
        background: #fff;
        border: 1px solid rgb(230, 230, 230);
        padding: 30px;
    }
    .fc-comment-inp  {
        display: block;
        width: 100%;
        font-weight: 400;
        color: #131049;
        background-color: rgb(255, 255, 255);
        background-clip: padding-box;
        border: 1px solid rgb(230, 230, 230);
        transition: background-color 0.2s ease-in-out 0s, border-color 0.2s ease-in-out 0s, box-shadow 0.2s ease-in-out 0s;
        resize: none;
        padding: 1.35rem 1.25rem;
        line-height: 1.4rem;
        font-size: 1.125rem;
        border-radius: 5px;
        box-shadow: none !important;
    }
    .fc-comment-inp-active {
        border-color: #00B66E;
    }
    .fc-comment-l {
        position: absolute;
        pointer-events: none;
        margin-bottom: 0px;
        font-size: 1.125rem;
        color: rgb(165, 165, 165);
        line-height: 1.4rem;
        top: 1.4125rem;
        padding: 0px 1.25rem;
        transition: all 0.2s ease-in-out 0s;
    }
    .fc-comment-l-a {
        position: absolute;
        pointer-events: none;
        margin-bottom: 0px;
        line-height: 1.4rem;
        padding: 0px 1.25rem;
        transition: all 0.2s ease-in-out 0s;
        color: rgb(0, 0, 0);
        font-size: 0.75rem;
        top: 0.20625rem;
        outline: none;
    }
    .comment-form-row {
        width: 100%;
        min-height: 1px;
        position: relative;
        margin-bottom: 1.8rem;
    }
    .fc-comment-notes {
        margin-bottom: 35px;
        font-size: 16px;
    }
    .fc-comment-btn {
        text-align: center;
        vertical-align: middle;
        user-select: none;
        border: 1px solid #131049;
        padding: 1.05rem 1.2rem;
        font-size: 1.125rem;
        line-height: 1.4;
        transition: all 0.2s ease-in-out 0s;
        -webkit-box-align: center;
        align-items: center;
        -webkit-box-pack: center;
        justify-content: center;
        font-style: normal;
        white-space: normal;
        letter-spacing: 0px;
        cursor: pointer;
        text-transform: none;
        font-weight: 600;
        min-height: 3.7875rem;
        outline: none;
        border-radius: 6px;
        display: block;
        width: 100%;
        color: #ffffff;
        background-color: #131049;
        width: 100%;
        opacity: 0.9;
    }
    .fc-comment-btn:hover {
        opacity: 1;
    }
    .fc-comment-form-flex {
         display: -webkit-box;
         display: -webkit-flex;
         display: -ms-flexbox;
         display: flex;
         -webkit-box-flex-wrap: wrap;
         -webkit-flex-wrap: wrap;
         -ms-flex-wrap: wrap;
         flex-wrap: wrap;
     }
    @media screen and (min-width: 769px) {
        .comment-form-author {
            width: calc(50% - 0.9rem);
            margin-right: 0.9rem;
        }

        .comment-form-email {
            width: calc(50% - 0.9rem);
            margin-left: 0.9rem;
        }
    }
    .fc-form-submit {
        width: 100%;
    }
    .fc-cancel-comment-reply-link {
        margin-top: 0px;
        margin-bottom: 20px;
        letter-spacing: 0;
        display: block;
        font-size: 1.125rem;
        color: #bb4a03;
        padding: 0;
        border: none;
        background: none;
        cursor: pointer;
        outline: none;
        line-height: 1.4;
    }
</style>
<div class="fc-comment-form-container">
    <div class="fc-comment-respond-container">

        <div id="respond" class="fc-comment-respond">
            <h3 class="fc-comment-title" id="reply-title">Оставить комментарий</h3>
            <div id="cancel-comment-reply-link"  class="fc-cancel-comment-reply-link" style="display:none;">Отменить ответ</div>
            <form method="post" id="commentform" class="fc-comment-form js-fc-from-ajax" action="fc-comment-add" trigger="clean-form-comment">
                <div class="fc-comment-form-flex">
                    <div class="fc-comment-notes  ">
                        <span id="email-notes">Ваш электронный адрес не будет опубликован.</span> Обязательные поля помечены <span class="required">*</span>
                    </div>
                    <div class="comment-form-comment comment-form-row">
                        <label for="comment" class="fc-comment-l">Комментарий <span class="required">*</span></label>
                        <textarea id="comment" name="comment" class="form-control form-control-lg fc-comment-inp" cols="45" rows="8" maxlength="65525" required=""></textarea>
                    </div>
                    <?php if ( !Utils::is_auth_user()) { ?>
                    <div class="comment-form-author comment-form-row">
                        <label for="author" class="fc-comment-l">Имя <span class="required">*</span></label>
                        <input id="author" name="author" type="text" size="30" maxlength="245" required="" class="fc-comment-inp" />
                    </div>
                    <div class="comment-form-email comment-form-row">
                        <label for="email" class="fc-comment-l">Email <span class="required">*</span></label>
                        <input id="email" name="email" type="email" size="30" maxlength="100" aria-describedby="email-notes" required="" class="fc-comment-inp" />
                    </div>
                    <div class="comment-form-url comment-form-row">
                        <label for="url" class="fc-comment-l">Сайт</label>
                        <input id="url" name="url" type="text" size="30" maxlength="200" class="fc-comment-inp" />
                    </div>
                    <?php } ?>
                    <div class="fc-form-submit focus ">
                        <button type="submit" id="submit" class="submit btn btn-block btn-primary fc-comment-btn"><span>Добавить комментарий</span></button>
                        <input type="hidden" name="comment_post_ID" id="comment_post_ID" value="<?= get_the_ID();?>" />
                        <input type="hidden" name="comment_parent" id="comment_parent" value="0" />
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script src="https://furnituric.ru/wp-includes/js/comment-reply.js"></script>
    <script>
        let inputs = document.getElementsByClassName('fc-comment-inp');

        for(let input of inputs) {

            input.onfocus = function() {
                this.classList.add('fc-comment-inp-active');
                this.previousElementSibling.classList.add('fc-comment-l-a');
            };

            input.onblur = function() {
                if ( this.value == "" ) {
                    this.classList.remove('fc-comment-inp-active');
                    this.previousElementSibling.classList.remove('fc-comment-l-a');
                }

            };

        }

        jQuery(document).on("clean-form-comment", function(){
            jQuery("#commentform input, #commentform textarea").val("");
            jQuery("#commentform input, #commentform textarea").blur();
        });

    </script>
</div>