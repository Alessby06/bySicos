<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TaskFlow</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="assets/css/style.css"/>
</head>
<body>

<div class="bg-grid"></div>

<header class="header">
  <div class="header-inner">
    <div class="logo">
      <span class="logo-icon">◈</span>
      <span class="logo-text">TaskFlow</span>
    </div>
    <div class="stats-bar">
      <span class="stat"><strong><?= $stats['total'] ?></strong> total</span>
      <span class="stat stat--done"><strong><?= $stats['done'] ?></strong> hechas</span>
      <span class="stat stat--pending"><strong><?= $stats['pending'] ?></strong> pendientes</span>
    </div>
  </div>
</header>

<main class="main">

  <!-- Nueva tarea -->
  <section class="add-section">
    <form method="POST" class="add-form" id="addForm">
      <input type="hidden" name="action" value="create"/>
      <div class="input-wrap">
        <input type="text" name="title" id="taskInput" class="task-input"
               placeholder="Escribe una tarea nueva…" maxlength="255" autocomplete="off" required/>
        <span class="char-count" id="charCount">0/255</span>
      </div>
      <button type="submit" class="btn btn--add">
        <span class="btn-icon">+</span> Agregar
      </button>
    </form>
  </section>

  <!-- Lista -->
  <section class="tasks-section">
    <?php if (empty($tasks)): ?>
      <div class="empty-state">
        <div class="empty-icon">○</div>
        <p>Sin tareas aún. ¡Agrega la primera!</p>
      </div>
    <?php else: ?>
      <ul class="task-list">
        <?php foreach ($tasks as $t): ?>
          <li class="task-item <?= $t['done'] ? 'task-item--done' : '' ?>">

            <!-- Marcar hecha -->
            <form method="POST" class="toggle-form">
              <input type="hidden" name="action" value="toggle"/>
              <input type="hidden" name="id" value="<?= $t['id'] ?>"/>
              <button type="submit" class="check-btn">
                <span class="check-icon"><?= $t['done'] ? '✓' : '○' ?></span>
              </button>
            </form>

            <span class="task-title"><?= htmlspecialchars($t['title']) ?></span>
            <span class="task-date"><?= date('d/m H:i', strtotime($t['created_at'])) ?></span>

            <!-- Eliminar -->
            <form method="POST" class="delete-form">
              <input type="hidden" name="action" value="delete"/>
              <input type="hidden" name="id" value="<?= $t['id'] ?>"/>
              <button type="submit" class="del-btn" onclick="return confirm('¿Eliminar?')">✕</button>
            </form>

          </li>
        <?php endforeach; ?>
      </ul>

      <?php if ($stats['total'] > 0):
            $pct = round(($stats['done'] / $stats['total']) * 100); ?>
        <div class="progress-wrap">
          <div class="progress-label">
            <span>Progreso</span>
            <span class="progress-pct"><?= $pct ?>%</span>
          </div>
          <div class="progress-bar">
            <div class="progress-fill" style="width:<?= $pct ?>%"></div>
          </div>
        </div>
      <?php endif; ?>
    <?php endif; ?>
  </section>

</main>

<footer class="footer">
  <p>TaskFlow · MVC PHP · <a href="https://infinityfree.net" target="_blank">InfinityFree</a></p>
</footer>

<script src="assets/js/app.js"></script>
</body>
</html>
