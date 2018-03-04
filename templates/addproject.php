<?php 
$name = $_POST["name"] ?? "";
?>
<div class="modal">
  <a href="index.php" class="modal__close">Закрыть</a>

  <h2 class="modal__heading">Добавление проекта</h2>

  <form class="form"  action="index.php" method="post">
    <div class="form__row">
      <label class="form__label" for="project_name">Название <sup>*</sup></label>

      <input class="form__input <?php if (count($projectErrors)): ?>
        form__input--error
      <?php endif; ?>" type="text" name="name" id="project_name" value="<?=$name; ?>" placeholder="Введите название проекта">
      <?php if (isset($projectErrors["name"])): ?>
        <p class="form__message"><?=$projectErrors["name"]; ?></p>
      <?php endif; ?>
      <?php if (isset($projectErrors["already_exists_name"])): ?>
        <p class="form__message"><?=$projectErrors["already_exists_name"]; ?></p>
      <?php endif; ?>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="add_project" value="Добавить">
    </div>
  </form>
</div>
