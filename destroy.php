<?php

declare(strict_types=1);

use model\Model as Model;

ini_set('display_errors', '1');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include('/var/www/html/minesweeper/model/Model.php');

$model = new Model();

$model->destroyGame();

echo json_encode('game ended');
