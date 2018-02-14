<?php
$name = $_POST["name"] ?? "";
$date = $_POST["date"] ?? "";
$project = $_POST["project"] ?? "";
$preview = $_POST["preview"] ?? "";
?>
<div class="modal">
  <button class="modal__close" type="button" name="button">Закрыть</button>

  <h2 class="modal__heading">Добавление задачи</h2>

  <form class="form"  action="index.php" method="post" enctype="multipart/form-data">
    <div class="form__row">
      <label class="form__label" for="name">Название <sup>*</sup></label>
        <?php if (isset($errors["name"])): ?>
            <p class="form__message"><?=$errors["name"]; ?></p>
        <?php endif; ?>
      <input class="form__input
        <?=isset($errors["name"]) ? "form__input--error" : ""; ?>" type="text" name="name" id="name" value="<?=$name; ?>" placeholder="Введите название">
    </div>

    <div class="form__row">
      <label class="form__label" for="project">Проект <sup>*</sup></label>
        <?php if (isset($errors["project"])): ?>
            <p class="form__message"><?=$errors["project"]; ?></p>
        <?php endif; ?>
      <select class="form__input form__input--select
        <?=isset($errors["project"]) ? "form__input--error" : ""; ?>" name="project" id="project">
        <?php foreach($categories as $category): ?>
          <?php if ($category !== "Все"): ?>
            <option value="<?=$category; ?>" <?php if ($project === $category): ?>
                selected
            <?php endif; ?>>
            <?=$category; ?></option>
          <?php endif; ?>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form__row">
      <label class="form__label" for="date">Дата выполнения</label>
        <?php if (isset($errors["date"])): ?>
            <p class="form__message"><?=$errors["date"]; ?></p>
        <?php endif; ?>
      <input class="form__input form__input--date
        <?=isset($errors["date"]) ? "form__input--error" : ""; ?>" type="date" name="date" id="date" value="<?=$date; ?>" placeholder="Введите дату в формате ДД.ММ.ГГГГ">
    </div>

    <div class="form__row">
      <label class="form__label" for="preview">Файл</label>

      <div class="form__input-file">
        <?php if (isset($errors["preview"])): ?>
            <p class="form__message"><?=$errors["preview"]; ?></p>
        <?php endif; ?>
        <input class="visually-hidden" type="file" name="preview" id="preview" value="<?=$preview; ?>">

        <label class="button button--transparent" for="preview">
            <span>Выберите файл</span>
        </label>
      </div>
    </div>

    <div class="form__row form__row--controls">
      <input class="button" type="submit" name="" value="Добавить">
    </div>
  </form>
</div>
