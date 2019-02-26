<?php
require_once 'mysql_helper.php';


/** Функция подключает шаблоны к сценарию
 * @param string $name - ссылка на шаблон
 * @param array $data - массив с данными для подключаемого шаблона
 * @return string - HTML разметка
*/

function include_template($name, $data) {
$name = 'templates/' . $name;
$result = '';

if (!is_readable($name)) {
return $result;
}

ob_start();
extract($data);
require $name;

$result = ob_get_clean();

return $result;
}

/** Функция округляет число до целого, отделяет пробелом каждые три цифры с конца у чисел более 1000, добавляет знак российского рубля.
 * @param float $price - число с плавающей точкой либо целое
 * @return string - выражение из числа и символа
 */
function format_price($price)
{
$price = ceil($price);
if ($price >= 1000) {
$price = number_format($price, 0, '', ' ');
}
return $price . " ₽";
};

/** Функция фильтрует полученные от пользователя данные, замещая опасные спецсимволы
 * @param string $str - исходные данные
 * @return string - текст, визуально идентичный исходному
 */
function esc($str) {
$text = htmlspecialchars($str);
return $text;
};

/** Функция считает время до полуночи
 * @return string - время в формате ЧЧ:MM
 */

function lot_expire($value){
    $ts_midnight = strtotime($value);
    $secs_to_midnight = $ts_midnight - time();
    $hours = floor($secs_to_midnight / 3600);
    if ($hours <= 9)  {
        $hours = 0 . "$hours";
    }
    $minutes = floor(($secs_to_midnight % 3600) / 60);
    if ($minutes <= 9)  {
        $minutes = 0 . "$minutes";
    }
    $remaining_time = $hours . ":" . $minutes;
    return $remaining_time;
}


/** Функция извлекает из бд массив с категориями
 * @param string $con - подключение
 * @return array - массив с данными
 */
function get_categories($con) {
    $categories = [];
    $sql = 'SELECT * FROM categories';
    $result = mysqli_query($con, $sql);
    if ($result) {
        $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return $categories;
}

/** Функция извлекает из бд массив с информацией о лотах
 * @param string $con - подключение
 * @return array - массив с данными
 */
function get_adverts($con) {
    $advert = [];
    $sql = "
        SELECT 
               lots.*,
               MAX(bids.sum) AS max_price,
               categories.category
        FROM 
             lots
        JOIN
               categories
                 ON lots.category_id = categories.id
        LEFT JOIN 
               bids 
                 ON lots.id = bids.lot_id
        WHERE 
              lots.end_time > NOW()
        GROUP BY 
              lots.id
    ";
    if ($res = mysqli_query($con, $sql)) {
        $advert = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return $advert;
}
//

function get_lot_id($con, $lot_id) {
    $advert = [];
    $sql = "
        SELECT 
               lots.*,
               MAX(bids.sum) AS max_price,
               categories.category
        FROM 
             lots
        JOIN
               categories
                 ON lots.category_id = categories.id
        LEFT JOIN 
               bids 
                 ON lots.id = bids.lot_id
        WHERE 
              lots.end_time > NOW() AND lots.id = ?
        GROUP BY 
              lots.id
    ";
    $stmt = db_get_prepare_stmt($con, $sql, [$lot_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $advert = mysqli_fetch_assoc($res);
    }
    return $advert;
}



/** Функция выводит данные из бд
 * @param $sql
 * @param $con
 * @return array/null
 */
function get_assoc($sql, $con) {
    $result = mysqli_query($con, $sql);

    if ($result) {
        return mysqli_fetch_assoc($result);
    }
}

// функции для нового лота

/** Функция осуществляет проверку стоимости и шага ставки
 * @param $value
 * @return bool
 */
function check_price($value) {
    $number = floatval($value);
    $is_number = is_numeric($number);
    $is_positive = $number >= 0;

    return ($is_number && $is_positive);
}


/** Функция для поиска ошибок в отправленной форме
 * @param $lot
 * @param $required
 * @param $rules
 * @return array
 */

function find_errors($lot, $required) {
    $errors = [];
    $errors_list = [
        'lot-name' => 'Введите наименование лота',
        'category' => 'Выберите категорию',
        'message' => 'Напишите описание лота',
        'lot-rate' => 'Введите начальную цену',
        'lot-step' => 'Введите шаг ставки',
        'lot-date' => 'Введите дату завершения торгов',
        'bottom' => 'Пожалуйста, исправьте ошибки в форме.'
    ];
    foreach ($lot as $key => $value) {
        if (in_array($key, $required) && $value = '') {
            $errors[$key] = $errors_list[$key];
        }}
    return $errors;}