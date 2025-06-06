<section id="register">
    <i class="map"></i>
    <article class="form">
        <div class="section__tabs tabs">

            <div class="tabs__head">
                <div class="tabs__caption" data-tab="register">Регистрация</div>
                <div class="tabs__caption tabs__caption_active" data-tab="login">Вход</div>
            </div>
            <div class="tabs__body">
                <div class="tabs__content tabs__content_active login" data-tab="register" id="register">
                    <form enctype="multipart/form-data" name="register" method="post" action="register-complete.php">
                        <div class="form" id="register">
                            <div class="field">
                                <label for="name">Ваше имя и фамилия</label>
                                <input type="text" name="firstName" id="name" placeholder="Укажите ваше имя" onkeyup="limitInput( 'ru', this );" required="">
                            </div>
                            <div class="field">
                                <label for="name">Фамилия</label>
                                <input type="text" name="lastName" id="name" placeholder="Укажите фамилию" onkeyup="limitInput( 'ru', this );" required="">
                            </div>
                            <div class="field full">
                                <label for="name">Электронная почта</label>
                                <input type="email" name="email" id="email" placeholder="Эл.почта" required="">
                            </div>
                            <div class="field company">
                                <label for="name">Компания</label>
                                <input type="text" name="company" id="company" placeholder="Укажите компанию" onkeyup="limitInput( 'ru', this );" required="">
                            </div>
                            <div class="field role">
                                <label for="name">Ваша роль</label>
                                <select name="role" required="">
                                    <option value="">Выберите из списка</option>
                                    <option value="Продавец">Продавец</option>
                                    <option value="Инвестор">Инвестор</option>
                                </select>
                            </div>
                            <div class="buttons">
                                <input id="submit" type="submit" value="Зарегистрироваться">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tabs__content login" data-tab="login" id="auth">
                    <form enctype="multipart/form-data" name="login" method="post" id="auth" action="authorization.php">
                        <p class="introText">Для&nbsp;авторизации в&nbsp;веб-сервисе воспользуйтесь адресом электронной почты, используемой при&nbsp;регистрации.</p>
                        <div class="field">
                            <label for="userEmail">Эл.почта</label>
                            <input type="email" name="userEmail" id="userEmail" placeholder="Укажите эл.почту" required>
                        </div>
                        <input id="auth" type="submit" value="Отправить">
                    </form>
                </div>
            </div>
        </div>
    </article>
</section>