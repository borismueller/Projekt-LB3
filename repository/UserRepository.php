<?php

require_once '../lib/Repository.php';

/**
 * Das UserRepository ist zuständig für alle Zugriffe auf die Tabelle "user".
 *
 * Die Ausführliche Dokumentation zu Repositories findest du in der Repository Klasse.
 */
class UserRepository extends Repository
{
    /**
     * Diese Variable wird von der Klasse Repository verwendet, um generische
     * Funktionen zur Verfügung zu stellen.
     */
    protected $tableName = 'user';

    /**
     * Erstellt einen neuen benutzer mit den gegebenen Werten.
     *
     * Das Passwort wird vor dem ausführen des Queries noch mit dem PASSWORD_DEFAULT
     *  Algorythmus gehashed.
     *
     * @param $firstName Wert für die Spalte firstName
     * @param $lastName Wert für die Spalte lastName
     * @param $email Wert für die Spalte email
     * @param $password Wert für die Spalte password
     *
     * @throws Exception falls das Ausführen des Statements fehlschlägt
     */
    public function create($username, $password)
    {
        $password = password_hash ($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO $this->tableName (name, password) VALUES (?, ?)";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        if (!$statement){
          throw new Exception($statement->error);
        } else {
          $statement->bind_param('ss', $username, $password);
          $statement->execute();
        }

        return $statement->insert_id;
    }

    public function login($username, $password) {
        $query = "SELECT password FROM $this->tableName WHERE name = ?";

        $statement = ConnectionHandler::getConnection()->prepare($query);
        if (!$statement){
          //TODO: bessere meldung
          echo "Ein Fehler ist aufgetreten";
        } else {
          $statement->bind_param('s', $username);
          $statement->execute();
          //get the result and turn it into a string
          //ofc you need 3 lines for that shit
          $result = $statement->get_result();
          $result = $result->fetch_object();
          if (!$result){
              return false;
          }
          $db_password = $result->password;
        }

        if (password_verify($password, $db_password)){
          echo "yo";
          return true;
        }
        else {
          echo "no";
          return false;
        }

        if (!$statement->execute()) {
            throw new Exception($statement->error);
            return false;
        }
    }

    public function getId($name)
    {
        // Query erstellen
        $query = "SELECT id FROM {$this->tableName} WHERE name=?";

        // Datenbankverbindung anfordern und, das Query "preparen" (vorbereiten)
        // und die Parameter "binden"
        $statement = ConnectionHandler::getConnection()->prepare($query);
        $statement->bind_param('s', $name);

        // Das Statement absetzen
        $statement->execute();

        // Resultat der Abfrage holen
        $result = $statement->get_result();
        if (!$result) {
            throw new Exception($statement->error);
        }

        // Ersten Datensatz aus dem Reultat holen
        $row = $result->fetch_object();

        // Datenbankressourcen wieder freigeben
        $result->close();

        // Den gefundenen Datensatz zurückgeben
        return $row;
    }

    public function changeUser($id, $username, $password)
    {
      $password = password_hash ($password, PASSWORD_DEFAULT);
      $query = "UPDATE {$this->tableName} SET name=?, password=? WHERE id=?";

      $statement = ConnectionHandler::getConnection()->prepare($query);
      $statement->bind_param('ssi', $username, $password, $id);

      $result = $statement->execute();

      // Resultat der Abfrage holen
      if (!$result) {
          throw new Exception($statement->error);
      }
      $_SESSION['username'] = $username;
    }
}
