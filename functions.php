<?php
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

?>