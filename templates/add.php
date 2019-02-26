<main>
    <nav class="nav">
        <ul class="nav__list container">
            <li class="nav__item">
                <a href="all-lots.html">Доски и лыжи</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Крепления</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Ботинки</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Одежда</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Инструменты</a>
            </li>
            <li class="nav__item">
                <a href="all-lots.html">Разное</a>
            </li>
        </ul>
    </nav>

    <form class="form form--add-lot container   <?php $form_validation_class = isset($errors) ? 'form--invalid' : ''; ?>" action="add.php" method="post" enctype="multipart/form-data"> <!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <?php $item_not_valid_class = 'form__item--invalid'; ?>
            <div class="form__item <?php $lot_name_validation_class = isset($errors['lot-name']) ? $item_not_valid_class : ''; ?>"> <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" required value="<?= $lot_name; ?>">
                <span class="form__error"><?php  $lot_name_error_message = isset($errors['lot-name']) ? $errors['lot-name'] : ''; ?></span>
            </div>

            <div class="form__item <?php $category_validation_class = isset($errors['category']) ? $item_not_valid_class : '';?>">
                <label for="category">Категория</label>
                <select id="category" name="category" required>
                    <option>Выберите категорию</option>
                    <?php foreach ($categories as $category): ?>
                        <option>
                            <?= esc($category['category']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="form__error"><?php $category_error_message = isset($errors['category']) ? $errors['category'] : ''; ?> /span>
            </div>
        </div>

        <div class="form__item form__item--wide <?php $message_validation_class = isset($errors['message']) ? $item_not_valid_class : ''; ?>">
            <label for="message">Описание</label>
            <textarea id="message" name="message" placeholder="Напишите описание лота" required><?= $message; ?></textarea>
            <span class="form__error"><?php  $message_error_message = isset($errors['message']) ? $errors['message'] : ''; ?></span>
        </div>

        <div class="form__item form__item--file <?php $image_validation_class = isset($errors['image']) ? $form__item_uploaded_class : ''; ?>"> <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="<?= $image; ?>" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>


        <div class="form__container-three">
            <div class="form__item form__item--small <?php $lot_rate_validation_class = isset($errors['lot-rate']) ? $item_not_valid_class : ''; ?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="number" name="lot-rate" placeholder="0" required value="<?= $lot_rate; ?>>
                <span class="form__error"><?php $lot_rate_error_message = isset($errors['lot-rate']) ? $errors['lot-rate'] : ''; ?> </span>
            </div>

            <div class="form__item form__item--small <?php $lot_step_validation_class = isset($errors['lot-step']) ? $item_not_valid_class : ''; ?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="number" name="lot-step" placeholder="0" required value="<?= $lot_step; ?>>
                <span class="form__error"><?php  $lot_step_error_message = isset($errors['lot-step']) ? $errors['lot-step'] : ''; ?> </span>
            </div>

            <div class="form__item <?php $lot_date_validation_class = isset($errors['lot-step']) ? $item_not_valid_class : ''; ?>">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="lot-date" type="date" name="lot-date" required value="<?= $lot_date; ?>>
                <span class="form__error"><?php  $lot_date_error_message = isset($errors['lot-date']) ? $errors['lot-date'] : ''; ?> </span>
            </div>

        </div>
        <span class="form__error form__error--bottom"><?php $form_error_bottom = isset($errors) ? $errors['bottom'] : ''; ?></php></span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>