<?php
// --- Cargar config y clases ---
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Database.php';
require_once __DIR__ . '/app/models/TaskModel.php';
require_once __DIR__ . '/app/controllers/TaskController.php';

// --- Arrancar controlador ---
$controller = new TaskController();
$controller->handle();
