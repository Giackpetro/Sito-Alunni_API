<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlunniController{
  //mostra tutte le colonne della tabella alunni
  public function index(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    if ($mysqli_connection->connect_error) {
      $response->getBody()->write(json_encode(["error" => "Errore di connessione al database"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(500);
    }else{
      $result = $mysqli_connection->query("SELECT * FROM alunni");
      $results = $result->fetch_all(MYSQLI_ASSOC);
      $response->getBody()->write(json_encode($results));
      return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }
  }

    //mostra tutte le colenne della tabella alunni di un alunnocon il relativo id
    public function show(Request $request, Response $response, $args){
      $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
      if ($mysqli_connection->connect_error) {
        $response->getBody()->write(json_encode(["error" => "Errore di connessione al database"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(500);
      }else{
        $result = $mysqli_connection->query("SELECT * FROM alunni WHERE id = {$args['id']}");
        $results = $result->fetch_all(MYSQLI_ASSOC);
        $response->getBody()->write(json_encode($results));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
      }
    }

    //crea un nuovo alunno
    public function create(Request $request, Response $response, $args){
      $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
      $data = json_decode($request->getBody()->getContents(), true);
      if ($mysqli_connection->connect_error) {
          $response->getBody()->write(json_encode(["error" => "Errore di connessione al database"]));
          return $response->withHeader("Content-type", "application/json")->withStatus(500);
      }
      if (!$data || !isset($data['nome']) || !isset($data['cognome'])) {
          $response->getBody()->write(json_encode(["error" => "Dati non validi"]));
          return $response->withHeader("Content-type", "application/json")->withStatus(400);
      }
      $fields = implode(", ", array_keys($data));
      $values = implode("', '", array_values($data));

      $result = $mysqli_connection->query("INSERT INTO alunni ($fields) VALUES ('$values')");
  
      $status = 500; //di default per dare errore
      if ($result) {
          $res = ["message" => "Alunno creato con successo"];
          $status = 200; //azione eseguita con successo
      } else {
          $res = ["error" => "Errore nella creazione dell'alunno"];
      }
      $response->getBody()->write(json_encode($res));
      return $response->withHeader("Content-type", "application/json")->withStatus($status);
  }  

  //aggiorna le informazioni di un alunno  
  public function update(Request $request, Response $response, $args){
      $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
      $data = json_decode($request->getBody()->getContents(), true);
      if ($mysqli_connection->connect_error) {
        $response->getBody()->write(json_encode(["error" => "Errore di connessione al database"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(500);
      }else{
        $fields = implode(", ", array_keys($data));
        $values = implode("', '", array_values($data));
        $updateFields = [];
        foreach ($data as $field => $value) {
          $updateFields[] = "$field = '$value'";
        }
        $updateQuery = implode(", ", $updateFields);
        $result = $mysqli_connection->query("UPDATE alunni SET $updateQuery WHERE id = {$args['id']}");

        $res = ["message" => "Alunno aggiornato con successo"];
        $response->getBody()->write(json_encode($res));
        return $response->withHeader("Content-type", "application/json")->withStatus(200);
      } 
    }

    //elimina un alunno
    public function delete(Request $request, Response $response, $args){
      $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
      if ($mysqli_connection->connect_error) {
        $response->getBody()->write(json_encode(["error" => "Errore di connessione al database"]));
        return $response->withHeader("Content-type", "application/json")->withStatus(500);
      }else{
        $result = $mysqli_connection->query("DELETE FROM alunni WHERE id = {$args['id']}");
        
        if ($result) {
            $res = ["message" => "Alunno eliminato con successo"];
        } else {
            $res = ["message" => "Errore durante l'eliminazione dell'alunno"];
        }
    
        $response->getBody()->write(json_encode($res));
        return $response->withHeader("Content-type", "application/json")->withStatus($result ? 200 : 500);
      }
  }
}