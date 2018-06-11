<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/products', function () use ($app) {
    // Use your configuration to connect to db
    // DO NOT CHANGE FIRST PARAMETER(HOST). Since app works in docker container, it's linking to other containers in own network that we described in docker-compose-yml
    $mysqli = new mysqli('db', 'root', 'root', 'lab_8', 3306);

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

$app->get('/products/{id}', function ($id) use ($app) {
    // Use your configuration to connect to db
    // DO NOT CHANGE FIRST PARAMETER(HOST). Since app works in docker container, it's linking to other containers in own network that we described in docker-compose-yml
    $mysqli = new mysqli('db', 'root', 'root', 'lab_8', 3306);

    if ($mysqli->connect_errno) {
        return new JsonResponse(['message' => 'Bad connection to mysql ' . $mysqli->connect_errno], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    // Perform your query
    $sql = "SELECT * FROM product where id = $id";
    if (!$sqlResult = $mysqli->query($sql)) {
        return new JsonResponse(['message' => 'Bad query to mysql: ' . $mysqli->error], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    return new JsonResponse((object)$sqlResult->fetch_assoc());
});

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    return new JsonResponse(['message' => $e->getMessage()], $code);
});
