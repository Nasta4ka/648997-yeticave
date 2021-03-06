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

/** Функция извлекает из бд лот по id
 * @param string $con - подключение
 * @param string/int $lot_id - id лота
 * @return array - массив с данными
 */
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
              lots.id = ?
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


/** Функция проверяет наличие категории в бд по id
 * @param string $con - подключение
 * @param string/int $category_id - id категории
 * @return array - массив с данными
 */
function check_category($con, $category_id) {
    $sql = "
        SELECT 
               categories.*
               
        FROM 
               categories
        WHERE 
               categories.id = ?
    ";
    $stmt = db_get_prepare_stmt($con, $sql, [$category_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        return mysqli_num_rows($res) > 0;
    }

    return false;
}

/** Функция проверяет наличие email в бд
 * @param string $con - подключение
 * @param string $email - исходный email введённый пользователем
 * @return array - массив с данными
 */
function check_email($con, $email)
{
    $sql = "
        SELECT 
               users.email
               
        FROM 
               users
        WHERE 
               users.email = ?
    ";
    $stmt = db_get_prepare_stmt($con, $sql, [$email]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $email = mysqli_fetch_assoc($res);
    }
    return $email;
}

/** Функция извлекает ставки из бд
 * @param string $con - подключение
 * @param string/int $lot_id - id лота, к которому привязаны ставки
 * @return array - массив с данными
 */
function get_bids($con, $lot_id) {
    $bids = [];
    $sql = "
        SELECT 
               bids.*,
               users.name
        FROM 
             bids
        JOIN
               users
                 ON bids.user_id = users.id
        WHERE 
              bids.lot_id = ?
    ";
    $stmt = db_get_prepare_stmt($con, $sql, [$lot_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $bids = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
    return $bids;
}


/** Функция извлекает ставки из бд
 * @param string $con - подключение
 * @param string/int $lot_id - id лота, к которому привязаны ставки
 * @return array - массив с данными
 */
function get_bid_users($con, $lot_id) {
    $bid_users =[];
    $sql = "
        SELECT 
               bids.user_id
        FROM 
             bids
        WHERE 
              bids.lot_id = ?
    ";
    $stmt = db_get_prepare_stmt($con, $sql, [$lot_id]);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    if ($res) {
        $bid_users = mysqli_fetch_all($res);
    }
    return $bid_users;
}


/** Функция посчитывает минуты в значениях меньше часа, часы - в значениях меньше суток
 * @param string/int $seconds - количество секунд
 * @return str
 */
function time_count($seconds) {
if ($seconds < 3600) {
    $time = round($seconds/60) . ' м. назад';
}

if ($seconds >= 3600) {
    $time = round(($seconds / 60) / 60) . ' ч. назад';
}

    return $time;
}