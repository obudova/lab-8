<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/products', function () use ($app) {
    // Use your configuration to connect to db
    // DO NOT CHANGE FIRST PARAMETER(HOST). Since app works in docker container, it's linking to other containers in own network that we described in docker-compose-yml
    $mysqli = new mysqli('db', 'root', 'root', 'manufactory', 3306);

    if ($mysqli->connect_errno) {
        return new JsonResponse(['message' => 'Bad connection to mysql ' . $mysqli->connect_errno], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Perform your query
    $sql = "SELECT * FROM product";
    if (!$sqlResult = $mysqli->query($sql)) {
        return new JsonResponse(['message' => 'Bad query to mysql: ' . $mysqli->error], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    $result = [];

    foreach ($sqlResult->fetch_all(MYSQLI_ASSOC) as $item){
        $result[] = (object)$item;
    }

    return new JsonResponse($result);
});

$app->get('/products/search/{string}', function ($string) use ($app) {
    // Use your configuration to connect to db
    // DO NOT CHANGE FIRST PARAMETER(HOST). Since app works in docker container, it's linking to other containers in own network that we described in docker-compose-yml
    $mysqli = new mysqli('db', 'root', 'root', 'manufactory', 3306);

    if ($mysqli->connect_errno) {
        return new JsonResponse(['message' => 'Bad connection to mysql ' . $mysqli->connect_errno], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    $stringQuery = '%'. $string .'%';


    // Perform your query
    $sql = "select * from product where concat(name, definition)  like('%".$string."%')";
    if (!$sqlResult = $mysqli->query($sql)) {
        return new JsonResponse(['message' => 'Bad query to mysql: ' . $mysqli->error], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    $result = [];

    foreach ($sqlResult->fetch_all(MYSQLI_ASSOC) as $item){
        $result[] = (object)$item;
    }

    return new JsonResponse($result);
});

$app->get('/products/{id}', function ($id) use ($app) {
    // Use your configuration to connect to db
    // DO NOT CHANGE FIRST PARAMETER(HOST). Since app works in docker container, it's linking to other containers in own network that we described in docker-compose-yml
    $mysqli = new mysqli('db', 'root', 'root', 'manufactory', 3306);

    if ($mysqli->connect_errno) {
        return new JsonResponse(['message' => 'Bad connection to mysql ' . $mysqli->connect_errno], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Perform your query
    $sql = "select sum(amount*price) as sum
              from (select contract_id from contract_prod where product_id = $id) as contracts
              join contract_prod
              on contracts.contract_id =contract_prod.contract_id
            group by contracts.contract_id;";
    if (!$sqlResult = $mysqli->query($sql)) {
        return new JsonResponse(['message' => 'Bad query to mysql: ' . $mysqli->error], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    $result = [];

    foreach ($sqlResult->fetch_all(MYSQLI_ASSOC) as $item){
        $result[] = (object)$item;
    }

    return new JsonResponse($result);
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    return new JsonResponse(['message' => $e->getMessage()], $code);
});
