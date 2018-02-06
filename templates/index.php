    <h2 class="content__main-heading">Список задач</h2>

    <form class="search-form" action="index.html" method="post">
        <input class="search-form__input" type="text" name="" value="" placeholder="Поиск по задачам">

        <input class="search-form__submit" type="submit" name="" value="Искать">
    </form>

    <div class="tasks-controls">
        <nav class="tasks-switch">
            <a href="/" class="tasks-switch__item tasks-switch__item--active">Все задачи</a>
            <a href="/" class="tasks-switch__item">Повестка дня</a>
            <a href="/" class="tasks-switch__item">Завтра</a>
            <a href="/" class="tasks-switch__item">Просроченные</a>
        </nav>

        <label class="checkbox">
            <a href="/">
                    <!--добавить сюда аттрибут "checked", если переменная $show_complete_tasks равна единице-->
                    <input class="checkbox__input visually-hidden" type="checkbox" <?php if ($show_complete_tasks === 1): ?> 
					    checked="checked"
				    <?php endif; ?>>
                <span class="checkbox__text">Показывать выполненные</span>
            </a>
        </label>
    </div>

    <table class="tasks">
    <!--показывать следующий тег <tr/>, если переменная $show_complete_tasks равна единице-->
        <?php foreach ($tasks as $task): ?>
            <tr class="tasks__item task 
                <?php if ($task["completed"] === true): ?>
                    task--completed
                <?php endif; ?>">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden" type="checkbox" checked>
                        <a href="/"><span class="checkbox__text"><?=strip_tags($task["task"]); ?></span></a>
                    </label>
                </td>

                <td class="task__file">
                    <a class="download-link" href="#">Home.psd</a>
                </td>

                <td class="task__date"><?=$task["date"]; ?></td>
            </tr>
        <?php endforeach; ?>
        <!-- Нужно ли удалить этот скрипт? -->
		<?php if ($show_complete_tasks === 1): ?>
		    <tr class="tasks__item task task--completed">
                <td class="task__select">
                    <label class="checkbox task__checkbox">
                        <input class="checkbox__input visually-hidden" type="checkbox" checked>
                        <span class="checkbox__text">Записаться на интенсив "Базовый PHP"</span>
                    </label>
                </td>
                <td class="task__date">10.04.2017</td>
				<td class="task__controls"></td>
            </tr>
		<?php endif; ?>
    </table>
