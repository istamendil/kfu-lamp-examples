<?php

// Массив внешних ссылок (начинаются с http)
$links = array();
// Получаем страницу
$pageUrl = 'http://study.istamendil.info/';
$pattern = '/<a[^>]+href="(http[^"]+)"[^>]*>/i';
$page = file_get_contents($pageUrl);

preg_match_all($pattern, $page, $links);

 // Для более красивого вывода - чтобы переноы строк не выводились. как пробелы
echo '<pre>';
// В нулевом элементе содержится массив соответствий регулярному выражению (тег a с внешними ссылками)
// В первом элементе содержится массив с искомыми подстроками - в регулярном выражении они выделены скобками
var_dump($links[1]);
