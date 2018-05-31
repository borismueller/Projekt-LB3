<?php

require_once '../repository/UserRepository.php';
require_once '../repository/FileRepository.php';
require_once '../repository/UserFileRepository.php';

/**
* Siehe Dokumentation im DefaultController.
*/
class UserController
{
  public function index()
  {
    $userRepository = new UserRepository();

    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    if (!empty($_GET['name'])) {
      $folderName = $_GET['name'];
    } else {
        $folderName = $_SESSION['username'];
    }

    $folderFragments = explode("/", $folderName);
    if (sizeof($folderFragments) > 1) {
      $displayFolderName = $folderFragments[sizeof($folderFragments) - 1]; //this name is only used for displaying
    } else {
      $displayFolderName = $_SESSION['username'];
    }

    $view = new View('user_index');
    $view->title = 'RUN';

    $userRepository = new UserRepository();
    $uid = $userRepository->getId($_SESSION['username']);
    $uid = $uid->id;

    $userFileRepository = new UserFileRepository();
    if (!empty($userFileRepository->getFileIds($uid))) {
      $fids = $userFileRepository->getFileIds($uid);

      $fileRepository = new FileRepository();
      $files = array();

      foreach ($fids as $fid) {
        $files[] = $fileRepository->readById($fid->file_id);
      }

      foreach ($files as $key => $file) {
        //get correct subfolder
        $pathFragments = explode("/", $file->path);
        $pathSize = sizeof($pathFragments);
        $fileSubFolder = $pathFragments[$pathSize - 2];
        if ($displayFolderName !== $fileSubFolder) {
            unset($files[$key]);
        }
      }

      $view->folderName = $folderName; //set subfolder for view
      $view->files = $files;
    }
    $view->display();
  }

  public function folder() {
    $userRepository = new UserRepository();

    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    if (!empty($_GET['name'])) {
      $folderName = $_GET['name'];
    } else {
        $this->error('user_index', 'RUN', "Something went wrong");
    }

    $folderFragments = explode("/", $folderName);
    if (sizeof($folderFragments) > 1) {
      $displayFolderName = $folderFragments[sizeof($folderFragments) - 1]; //this name is only used for displaying
    } else {
      $displayFolderName = $folderName;
    }

    $view = new View('user_index');
    $view->title = 'RUN';

    $userRepository = new UserRepository();
    $uid = $userRepository->getId($_SESSION['username']);
    $uid = $uid->id;

    $userFileRepository = new UserFileRepository();
    if (!empty($userFileRepository->getFileIds($uid))) {
      $fids = $userFileRepository->getFileIds($uid);

      $fileRepository = new FileRepository();
      $files = array();

      foreach ($fids as $fid) {
        $files[] = $fileRepository->readById($fid->file_id);
      }

      foreach ($files as $key => $file) {
        //get correct subfolder
        $pathFragments = explode("/", $file->path);
        $pathSize = sizeof($pathFragments);
        $fileSubFolder = $pathFragments[$pathSize - 2];
        if ($displayFolderName !== $fileSubFolder) {
            unset($files[$key]);
        }
      }

      $view->folderName = $folderName; //set subfolder for view
      $view->files = $files;
    }
    $view->display();
  }

  public function create()
  {
    $view = new View('user_create');
    $view->title = 'Register';
    $view->display();
  }

  public function doCreate()
  {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }
    if ($_POST['Submit']) {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $passwordRepeat = $_POST['passwordRepeat'];

      if ($username == "" || empty($username) || $password == "" || empty($password)){
        $this->error('user_create', 'Register', "Name and password can't be empty.");
      }

      if ($password !== $passwordRepeat) {
        $this->error('user_create', 'Register', 'Passwords have to match.');
      }

      $userRepository = new UserRepository();

      if (!empty($userRepository->getId($username))) {
        //user with that name already exists
        $this->error('user_create', 'Register', 'A User with that name already exists.');
      }
      $userRepository->create($username, $password);
      $_SESSION['username'] = $username;
      header('Location: /user');
    }
  }

  public function user_settings() {
    $username = $_SESSION['username'];

    $userRepository = new UserRepository();
    $user_id = $userRepository->getId($username);
    $user = $userRepository->readById($user_id->id);

    $view = new View('user_setting');
    $view->user = $user;
    $view->displayOnly();
  }

  public function doEdit()
  {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    $newUsername = htmlspecialchars($_POST['username']);
    $newPassword = $_POST['password'];
    $repPassword = $_POST['repeat'];

    $userRepository = new UserRepository();
    $id = $userRepository->getId($_SESSION['username']);
    $id = $id->id;

    if($newPassword !== $repPassword) {
      $this->error('user_settings', 'Edit', 'Passwords have to match.');
    }

    $userRepository = new UserRepository();
    $userRepository->changeUser($id, $newUsername, $newPassword);

    header('Location: /user');
  }

  public function doUpdate() {
      if (!isset($_SESSION['username'])){
        $this->error('user_login', 'Login', 'Acces denied.');
      }

      if (isset($_POST['submit'])) {
        $newName = htmlspecialchars($_POST['filename']);

        if (empty($newName) || $newName == "") {
          $this->error('user_index', 'RUN', 'File Name cant be empty');
          return;
        }

        if (empty($_GET['id'])) {
          $this->error('user_index', 'RUN', 'Id not set');
          return;
        }

        $fileId = $_GET['id'];
        $fileRepository = new FileRepository();

        $oldFile = $fileRepository->readById($fileId);

        if(!empty($fileRepository->getId($newName))) {
          $this->error('user_index', 'RUN', 'A file with that name already exists');
          return;
        }
        $fileRepository->changeFile($newName, $fileId);

        header('Location: /user');
      }
  }

  public function delete()
  {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    $userRepository = new UserRepository();
    $userRepository->deleteById($_GET['id']);

    // Anfrage an die URI /user weiterleiten (HTTP 302)
    header('Location: /user');
  }

  public function login() {
    $view = new View('user_login');
    $view->title = 'Login';
    $view->display();
  }

  public function doLogin() {
    if (isset($_POST['Submit'])) {
      $username = htmlspecialchars($_POST['username']);
      $password = htmlspecialchars($_POST['password']);

      $userRepository = new UserRepository();

      if ($userRepository->login($username, $password)){
        //login korrekt
        $_SESSION['username'] = $username;
        header('Location: /user');
      } else {
        //Fehler
        $this->error('user_login', 'Login', 'Login not correct');
      }
    }
  }

  public function upload() {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    $view = new View('user_upload');
    $view->title = 'Upload';

    if (!empty($_GET['folderName'])) {
      //check if we are in folder and add to view if we are
      $folderName = $_GET['folderName'];
      $view->folderName = $folderName;
    }


    $view->display();
  }

  public function doUpload() {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    if (isset($_POST['Submit'])) {
      if (!empty($_GET['folderName'])) {
        //check if we are in folder and save it if we are
        $folderName = $_GET['folderName'];
      }

      $name = htmlspecialchars($_POST['name']);
      if ($name == "" || empty($name)){
        $this->error('user_upload', 'Upload', 'Name cant be empty.');
      }
      $tags = htmlspecialchars($_POST['tags']);
      $acces = htmlspecialchars($_POST['acces']);
      $file = $_FILES['file'];

      $username = $_SESSION['username'];
      if (!empty($folderName) && $folderName != $username) {
          //if folderName is username we are probably in the default dir
          $path = "data/files/".$username."/".$folderName."/".$file['name'];
      } else {
          $path = "data/files/".$username."/".$file['name'];
      }


      if (!is_dir("data/files/".$username)) {
        //create dir if it doesnt exist
        mkdir("data/files/".$username, 0777, true);
      }

      if (move_uploaded_file($file["tmp_name"], $path)){
        $fileRepository = new FileRepository();

        if (!empty($fileRepository->getId($name))) {
          //file with that name already exists
          $this->error('user_upload', 'Upload', 'A file with that name already exists.');
          return;
        }

        $fileRepository->create($name, $tags, $path);

        $file_id = $fileRepository->getId($name);
        $file_id = $file_id->id;

        $userRepository = new userRepository();
        $user_id = $userRepository->getId($username);
        $user_id = $user_id->id;

        $userFileRepository = new UserFileRepository();
        $userFileRepository->create($user_id, $file_id);

        header('Location: /user');
      }
      else {
        $this->error('user_upload', 'Upload', 'Something went wrong.');
      }
    }
  }

  public function makeDir() {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    $view = new View('user_makeDir');
    $view->title = 'Upload';
    if (!empty($_GET['folderName'])) {
      //check if we are in folder and add to view if we are
      $folderName = $_GET['folderName'];
      $view->folderName = $folderName;
    }

    $view->display();
  }

  public function doMakeDir() {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    if (isset($_POST['Submit'])) {
      if (!empty($_GET['folderName'])) {
        //check if we are in folder and save it if we are
        $folderName = $_GET['folderName'];
      }

      $name = htmlspecialchars($_POST['name']);
      if ($name == "" || empty($name)){
        $this->error('user_makeDir', 'Create Folder', 'Name cant be empty.');
      }
      if (!isset($_SESSION['username'])){
        $this>error('user_login', 'Login', 'Access denied');
      }
      $username = $_SESSION['username'];
      if (!empty($folderName) && $folderName !== $username) {
          //we are in a sub folder
          $path = "data/files/".$username."/".$folderName."/".$name;

          $name = $folderName."/".$name; //change name
      } else {
          $path = "data/files/".$username."/".$name;
      }

      if (!is_dir("data/files/".$username)) {
        //create user-dir if it doesnt exist
        mkdir("data/files/".$username, 0777, true);
      }

      if (!is_dir($path)) {
        //create dir if it doesnt exist
        mkdir($path, 0777, true);
      }

      $fileRepository = new FileRepository();
      if (!empty($fileRepository->getId($name))) {
        //file with that name already exists
        $this->error('user_makeDir', 'Create Folder', 'A File with that name alredy exists.');
        return;
      }

      $fileRepository->create($name, "", $path);//no tag

      $file_id = $fileRepository->getId($name);
      $file_id = $file_id->id;

      $userRepository = new userRepository();
      $user_id = $userRepository->getId($username);
      $user_id = $user_id->id;

      $userFileRepository = new UserFileRepository();
      $userFileRepository->create($user_id, $file_id);
      header('Location: /user');
    }

    header('Location: /user/');
  }

  public function search()
  {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    $content = htmlspecialchars($_POST['searchbar']);

    $view = new view('user_index');
    $view->title = "RUN";
    $fileRepository = new FileRepository();
    if (!empty($fileRepository->getFilesByNameAndTag($content))) {
      $files = $fileRepository->getFilesByNameAndTag($content);
      $view->files = $files;
    } else {
      $view->error = "No Results";
    }
    $view->display();
  }

  public function delFile()
  {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    $file_id = $_GET['id'];
    $fileRepository = new FileRepository();
    $fileRepository->delFileById($file_id);

    header('Location: /user');
  }

  public function delTag() {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    $file_id = $_GET['id'];
    $fileRepository = new FileRepository();
    $fileRepository->delTagById($file_id);

    header('Location: /user');
  }

  public function logout() {
    session_destroy();
    header('Location: /');
  }



  public function fileprops() {
    if (!isset($_SESSION['username'])){
      $this->error('user_login', 'Login', 'Acces denied.');
    }

    $fileid = $_GET['id'];

    $fileRepository = new FileRepository();
    $file = $fileRepository->readById($fileid);

    $view = new View('file_properties');
    $view->file = $file;
    $view->displayOnly();
  }

  public function error($viewFile, $title,  $error) {
    $view = new View($viewFile);
    $view->title = $title;
    $view->error = $error;
    $view->display();
    return;
  }

}
