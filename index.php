<?php

require 'vendor/autoload.php';
Flight::register('db', 'PDO', array('mysql:host=localhost;dbname=apr','root',''));

Flight::route('GET /users', function(){
    $db = Flight::db();
    $query = $db->prepare("SELECT * FROM  users");
    $query->execute();
    $data= $query->fetchAll();
//    print_r($data);
    $array = [];
    foreach ($data as $row){
        $array[] =[
            "id" => $row['id'],
            "Name" => $row['first_name'],
            "Apellido" => $row['last_name'],
            "email" => $row['email'],
            "password" => $row['password'],
        ];
    }

    Flight::json($array);


});

Flight::route('GET /users/@id', function($id){
    $db = Flight::db();
    $query = $db->prepare("SELECT * FROM  users WHERE id = :id"); //:id => identificador de PDO
    $query->execute([":id"=>$id]);
    $data= $query->fetch();

        $array =[
            "id" => $data['id'],
            "Name" => $data['first_name'],
            "Apellido" => $data['last_name'],
            "email" => $data['email'],
            "password" => $data['password'],
        ];


    Flight::json($array);


});

Flight::route('POST /users', function(){
    $db = Flight::db();
    $name = Flight::request()->data->Name;
    $apellido = Flight::request()->data->Apellido;
    $email = Flight::request()->data->email;
    $password = Flight::request()->data->password;

    $query = $db->prepare("INSERT INTO users (last_name,first_name,email,password) VALUES (:apellido,:name,:email,:password)"); //:id => identificador de PDO

    $array =[
        "error" => "Hubo un error intente nuevamente",
        "status" => "error"
    ];

    if($query->execute([":name" => $name, ":apellido" => $apellido,":email"=>$email,":password" => $password])){
        $array =[
            "id" => $db->lastInsertId(),
            "Name" => $name,
            "Apellido" => $apellido,
            "email" => $email,
            "password" => $password,
        ];


    }

    Flight::json($array);


});

Flight::start();
