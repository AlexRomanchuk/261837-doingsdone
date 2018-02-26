 <?php
 $name = $_POST["name"] ?? "";
 $email = $_POST["email"] ?? "";
 $password = $_POST["password"] ?? "";
 $contacts = $_POST["contacts"] ?? "";
 ?>
 <div class="content">
    <section class="content__side">
        <p class="content__side-info">Если у вас уже есть аккаунт, авторизуйтесь на сайте</p>

        <a class="button button--transparent content__side-button" href="#">Войти</a>
    </section>

    <main class="content__main">
        <h2 class="content__main-heading">Регистрация аккаунта</h2>

        <form class="form" action="index.php" method="post">
        <div class="form__row">
            <label class="form__label" for="email">E-mail <sup>*</sup></label>

            <input class="form__input <?php if (count($registrErrors["email"])): ?>
              form__input--error
            <?php endif; ?>" type="text" name="email" id="email" value="<?=$email; ?>" placeholder="Введите e-mail">
            <?php if (isset($registrErrors["email"]["missing_email"])): ?>
              <p class="form__message"><?=$registrErrors["email"]["missing_email"]; ?></p>
            <?php endif; ?>
            <?php if (isset($registrErrors["email"]["incorrect_email"])): ?>
              <p class="form__message"><?=$registrErrors["email"]["incorrect_email"]; ?></p>
            <?php endif; ?>
            <?php if (isset($registrErrors["email"]["already_exists_user"])): ?>
              <p class="form__message"><?=$registrErrors["email"]["already_exists_user"]; ?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="password">Пароль <sup>*</sup></label>

            <input class="form__input <?php if (count($registrErrors["password"])): ?>
              form__input--error
            <?php endif; ?>" type="password" name="password" id="password" value="<?=$password; ?>" placeholder="Введите пароль">
            <?php if (isset($registrErrors["password"]["missing_password"])): ?>
              <p class="form__message"><?=$registrErrors["password"]["missing_password"]; ?></p>
            <?php endif; ?>
            <?php if (isset($registrErrors["password"]["already_exists_pass"])): ?>
              <p class="form__message"><?=$registrErrors["password"]["already_exists_pass"]; ?></p>
            <?php endif; ?>
        </div>

        <div class="form__row">
            <label class="form__label" for="name">Имя <sup>*</sup></label>

            <input class="form__input <?php if (count($registrErrors["name"])): ?>
              form__input--error
            <?php endif; ?>" type="text" name="name" id="name" value="<?=$name; ?>" placeholder="Введите имя">
            <?php if (isset($registrErrors["name"]["missing_name"])): ?>
              <p class="form__message"><?=$registrErrors["name"]["missing_name"]; ?></p>
            <?php endif; ?>
         </div>
         
         <div class="form__row">
            <label class="form__label" for="contacts">Контакты</label>
            <textarea class="form__input" type="text" name="contacts" id="contacts" placeholder="Дополнительная информация"><?=$contacts; ?></textarea>
         </div>

        <div class="form__row form__row--controls">
        <?php if (isset($registrErrors) && (count($registrErrors["email"]) || count($registrErrors["name"]) || count($registrErrors["password"]))): ?>
          <p class="error-message">Пожалуйста, исправьте ошибки в форме</p>
        <?php endif; ?>

        <input class="button" type="submit" name="registration" value="Зарегистрироваться">
        </div>
        </form>
    </main>
</div>
