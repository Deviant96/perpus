<?php

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

return function (App $app) {

    $app->get('/',function(){
        echo "Selamat datang di Perpustakaan";
    });

    $app->get("/buku/", function (Request $request, Response $response){
        $sql = "SELECT * FROM buku";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/buku/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "SELECT * FROM buku WHERE id_buku=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([":id" => $id]);
        $result = $stmt->fetch();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->get("/buku/search/", function (Request $request, Response $response, $args){
        $keyword = $request->getQueryParam("keyword");
        $sql = "SELECT * FROM buku WHERE judul LIKE '%$keyword%' OR sinopsis LIKE '%$keyword%' OR penulis LIKE '%$keyword%'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $response->withJson(["status" => "success", "data" => $result], 200);
    });

    $app->post("/buku/", function (Request $request, Response $response){

        $new_book = $request->getParsedBody();
    
        $sql = "INSERT INTO buku (judul, penulis, sinopsis) VALUE (:judul, :penulis, :sinopsis)";
        $stmt = $this->db->prepare($sql);
    
        $data = [
            ":judul" => $new_book["judul"],
            ":penulis" => $new_book["penulis"],
            ":sinopsis" => $new_book["sinopsis"]
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->put("/buku/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $new_book = $request->getParsedBody();
        $sql = "UPDATE buku SET judul=:judul, penulis=:penulis, sinopsis=:sinopsis WHERE book_id=:id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":id" => $id,
            ":judul" => $new_book["judul"],
            ":penulis" => $new_book["penulis"],
            ":sinopsis" => $new_book["sinopsis"]
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });

    $app->delete("/buku/{id}", function (Request $request, Response $response, $args){
        $id = $args["id"];
        $sql = "DELETE FROM buku WHERE id_buku=:id";
        $stmt = $this->db->prepare($sql);
        
        $data = [
            ":id" => $id
        ];
    
        if($stmt->execute($data))
           return $response->withJson(["status" => "success", "data" => "1"], 200);
        
        return $response->withJson(["status" => "failed", "data" => "0"], 200);
    });
};
