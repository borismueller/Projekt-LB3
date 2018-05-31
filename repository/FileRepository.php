<?php

 require_once '../lib/Repository.php';

 /**
  * Das FileRepository ist zuständig für alle Zugriffe auf die Tabelle "file".
  *
  * Die Ausführliche Dokumentation zu Repositories findest du in der Repository Klasse.
  */
 class FileRepository extends Repository
 {
     protected $tableName = 'file';

     public function create($name, $tags, $path) {
           $query = "INSERT INTO $this->tableName (name, tags, path) VALUES (?, ?, ?)";

           $statement = ConnectionHandler::getConnection()->prepare($query);
           if (!$statement){
             //TODO: bessere meldung
             echo "Ein Fehler ist aufgetreten";
           } else {
             $statement->bind_param('sss', $name, $tags, $path);
             $statement->execute();
           }

           return $statement->insert_id;
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

     public function getFilesByNameAndTag($name)
     {
       $query = "SELECT * FROM file WHERE name=? OR tags=?";

       $statement = ConnectionHandler::getConnection()->prepare($query);
       $statement->bind_param('ss', $name, $name);

       $statement->execute();

       $result = $statement->get_result();
       if (!$result) {
           throw new Exception($statement->error);
       }

       // Datensätze aus dem Resultat holen und in das Array $rows speichern
       $rows = array();
       while ($row = $result->fetch_object()) {
           $rows[] = $row;
       }

       return $rows;
     }

     public function delTagById($id) {
       $query = "UPDATE {$this->tableName} SET tags = '' WHERE id=?";

       $statement = ConnectionHandler::getConnection()->prepare($query);
       $statement->bind_param('i', $id);

       $statement->execute();
     }

     public function delFileById($id) {
       $query = "DELETE FROM {$this->tableName} WHERE id=?";

       $statement = ConnectionHandler::getConnection()->prepare($query);
       $statement->bind_param('i', $id);

       $statement->execute();
     }

     public function changeFile($name, $id) {
       $query = "UPDATE {$this->tableName} SET name=? WHERE id=?";

       $statement = ConnectionHandler::getConnection()->prepare($query);
       $statement->bind_param('si', $name, $id);

       $result = $statement->execute();

       // Resultat der Abfrage holen
       if (!$result) {
           throw new Exception($statement->error);
       }
     }
}
