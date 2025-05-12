<?php
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/controllers/AlunniController.php';

$app = AppFactory::create();

// Endpoint per la gestione degli alunni

// Recuperare tutti gli alunni
// curl http://localhost:8080/alunni
$app->get('/alunni', "AlunniController:index");

// Recuperare un singolo alunno tramite ID
// curl http://localhost:8080/alunni/2
$app->get('/alunni/{id}', "AlunniController:show");

// Creare un nuovo alunno
// curl -X POST http://localhost:8080/alunni -H "Content-Type: application/json" -d '{"nome": "Mario", "cognome": "Rossi"}'
$app->post('/alunni', "AlunniController:create");

// Aggiornare un alunno tramite ID
// curl -X PUT http://localhost:8080/alunni/4 -H "Content-Type: application/json" -d '{"nome": "Giuseppe", "cognome": "Verdi"}'
$app->put('/alunni/{id}', "AlunniController:update");

// Eliminare un alunno tramite ID
// curl -X DELETE http://localhost:8080/alunni/4
$app->delete('/alunni/{id}', "AlunniController:delete");

$app->run();
?>