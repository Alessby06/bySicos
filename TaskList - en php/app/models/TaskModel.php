<?php
class TaskModel {
    private PDO $db;

    public function __construct() {
        $this->db = Database::get();
        $this->db->exec("
            CREATE TABLE IF NOT EXISTS tasks (
                id         INT AUTO_INCREMENT PRIMARY KEY,
                title      VARCHAR(255) NOT NULL,
                done       TINYINT(1) NOT NULL DEFAULT 0,
                created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ");
    }

    public function getAll(): array {
        return $this->db->query("SELECT * FROM tasks ORDER BY created_at DESC")->fetchAll();
    }

    public function create(string $title): void {
        $this->db->prepare("INSERT INTO tasks (title) VALUES (?)")->execute([trim($title)]);
    }

    public function toggle(int $id): void {
        $this->db->prepare("UPDATE tasks SET done = 1 - done WHERE id = ?")->execute([$id]);
    }

    public function delete(int $id): void {
        $this->db->prepare("DELETE FROM tasks WHERE id = ?")->execute([$id]);
    }

    public function stats(): array {
        $r = $this->db->query("SELECT COUNT(*) AS total, SUM(done) AS done FROM tasks")->fetch();
        $total = (int)$r['total'];
        $done  = (int)($r['done'] ?? 0);
        return ['total' => $total, 'done' => $done, 'pending' => $total - $done];
    }
}
