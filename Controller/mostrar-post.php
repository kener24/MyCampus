<?php
require_once '../models/postModel.php';

class PostController {
    private $model;

    public function __construct($db) {
        $this->model = new PostModel($db);
    }

    public function mostrarPublicaciones() {
        return $this->model->obtenerPublicaciones();
    }

    public function mostrarPublicacionesPorUsuario($userId) {
        return $this->model->obtenerPublicacionesPorUsuario($userId);
    }
}
?>
