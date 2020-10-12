<?php

declare(strict_types=1);

use model\Model as Model;

ini_set('display_errors', '1');

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers:Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

include('/var/www/html/minesweeper/model/Model.php');

$model = new Model();

$model->createGame();

echo json_encode('new game');
