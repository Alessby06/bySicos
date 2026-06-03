<?php
class TaskController {
    private TaskModel $model;

    public function __construct() {
        $this->model = new TaskModel();
    }

    public function handle(): void {
        $action = $_POST['action'] ?? 'list';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            match($action) {
                'create' => $this->create(),
                'toggle' => $this->toggle(),
                'delete' => $this->delete(),
                default  => null,
            };
            // PRG: evita reenvío del formulario al refrescar
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }

        // GET → mostrar lista
        $tasks = $this->model->getAll();
        $stats = $this->model->stats();
        require __DIR__ . '/../views/tasks.php';
    }

    private function create(): void {
        $title = $_POST['title'] ?? '';
        if (strlen(trim($title)) > 0) {
            $this->model->create($title);
        }
    }

    private function toggle(): void {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) $this->model->toggle($id);
    }

    private function delete(): void {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) $this->model->delete($id);
    }
}
