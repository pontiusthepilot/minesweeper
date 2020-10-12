<?php

declare(strict_types=1);

use model\Model as Model;

ini_set('display_errors', '1');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include('/var/www/html/minesweeper/model/Model.php');

$model = new Model();

$row = intval($_GET['row']);
$column = intval($_GET['column']);

$result = $model->checkForMine($row, $column);

echo json_encode($result);
