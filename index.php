<?php
require_once "config/User.php";
global $user_obj;
header("Access-Control-Allow-Origin : *"); // public API
header("Content-Type: application/json; charset=UTF-8");
$request_url = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

// Get All Users
if ($request_url==="/users" && $request_method==="GET"){
    $allUsers=$user_obj->getAllUsers();
    echo $allUsers;
}
// Add User
elseif ($request_url==="/users" && $request_method==="POST"){
    $data=json_decode(file_get_contents("php://input"),true);
    $name=$data['name'];
    $email=$data['email'];
    $password=$data['password'];
    $createUser=$user_obj->createUser($name,$email,$password);
    echo $createUser;
}
// Get User By Id
elseif (preg_match("/\/users\/[0-9]+/",$request_url) && $request_method==="GET"){
    $id=explode("/",$request_url)[2];
    $getUserById=$user_obj->getUserById($id);
    echo $getUserById;
}
// Edit User
elseif (preg_match("/\/users\/[0-9]+/",$request_url) && $request_method==="PUT"){
    $data=json_decode(file_get_contents("php://input"),true);
    $name=$data['name'];
    $email=$data['email'];
    $password=$data['password'];
    $id=explode("/",$request_url)[2];
    $editUser=$user_obj->editUser($id,$name,$email,$password);
    echo $editUser;
}
// Delete User
elseif (preg_match("/\/users\/[0-9]+/",$request_url) && $request_method==="DELETE"){
    $id=explode("/",$request_url)[2];
    $deleteUser=$user_obj->deleteUser($id);
    echo $deleteUser;
}
// Search User
elseif (preg_match("/\/users\/search/",$request_url) && $request_method==="GET"){
    $search=explode("=",$request_url)[1];
    $searchResult=$user_obj->searchUser($search);
    echo $searchResult;
}