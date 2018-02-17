<?php 
$email = $_POST["email"] ?? "";
$password = $_POST["password"] ?? "";
?>
<div class="modal">
  <button class="modal__close" type="button" name="button">Закрыть</button>

  <h2 class="modal__heading">Вход на сайт</h2>

  <form class="form" action="index.php" method="post">
    <div class="form__row">
      <label class="form__label" for="email">E-mail <sup>*</sup></label>
      <?php if (isset($loginErrors["email"]["missing_email"])): ?>
      <p class="form__message"><?=$loginErrors["email"]["missing_email"]; ?></p>
      <?php elseif (isset($loginErrors["email"]["incorrect_email"])): ?>
      <p class="form__message"><?=$loginErrors["email"]["incorrect_email"]; ?></p>
      <?php elseif (isset($loginErrors["email"]["unknown_user"])): ?>
      <p class="form__message"><?=$loginErrors["email"]["unknown_user"]; ?></p>
      <?php endif; ?>
      <input class="form__input <?php if (count($loginErrors["email"])): ?>
          form__input--error
      <?php endif; ?>" type="text" name="email" id="email" value="<?=$email; ?>" placeholder="Введите e-mail">
    </div>

    <div class="form__row">
      <label class="form__label" for="password">Пароль <sup>*</sup></label>
      <?php if (isset($loginErrors["password"]["missing_password"])): ?>
          <p class="form__message"><?=$loginErrors["password"]["missing_password"]; ?></p>
      <?php elseif (isset($loginErrors["password"]["invalid_password"])): ?>
          <p class="form__message"><?=$loginErrors["password"]["invalid_password"]; ?></p>
      <?php endif; ?>
      <input class="form__input <?php if (count($loginErrors["password"])): ?>
          form__input--error
      <?php endif; ?>" type="password" name="password" id="password" value="<?=$password; ?>" placeholder="Введите пароль">
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="login" value="Войти">
    </div>
  </form>
</div>
