    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.html" method="post">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="?all" class="tasks-switch__item <?php if (isset($_GET["all"])): ?>
              tasks-switch__item--active
            <?php endif; ?>">Все задачи</a>
            <a href="?on_day" class="tasks-switch__item <?php if (isset($_GET["on_day"])): ?>
              tasks-switch__item--active
            <?php endif;?>">Повестка дня</a>
            <a href="?on_tomorrow" class="tasks-switch__item <?php if (isset($_GET["on_tomorrow"])): ?>
              tasks-switch__item--active
            <?php endif;?>">Завтра</a>
            <a href="?not_done" class="tasks-switch__item <?php if (isset($_GET["not_done"])): ?>
              tasks-switch__item--active
            <?php endif;?>">Просроченные</a>
        </nav>

        <label class="checkbox">
            <a href="?show_completed">
                    <input class="checkbox__input visually-hidden" type="checkbox" <?php if ($completed): ?> 
					    checked="checked"
				    <?php endif; ?>>
                <span class="checkbox__text">Показывать выполненные</span>
            </a>
        </label>
    </div>

    <table class="tasks">
        <?php foreach ($tasks as $id => $task): ?>
            <tr class="tasks__item task 
                <?php if ($task["completed"] === "1"): ?>
                    task--completed
                <?php endif; ?>
                <?php if (countDays($date, $task["date"]) <= 1 && $task["date"] !== ""): ?>
                    task--important
                <?php endif; ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden" type="checkbox" <?php if ($task["completed"] === "1"): ?>
                           checked
                        <?php endif; ?>>
                        <a href="?task_id=<?=$id; ?>"><span class="checkbox__text"><?=strip_tags($task["name"]); ?></span></a>
                    </label>
                </td>
                
                <td class="task__file">
                <?php if (isset($task["image"]) && $task["image"] !== ""): ?>
                    <a class="download-link" href="<?=$task["image"]; ?>"><?=$task["image"]; ?></a>
                <?php endif; ?>
                </td>
                
                <td class="task__date"><?=date("d.m.Y", strtotime($task["date"])); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
