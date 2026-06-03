document.addEventListener('DOMContentLoaded', () => {

  // Contador de caracteres
  const input   = document.getElementById('taskInput');
  const counter = document.getElementById('charCount');
  if (input && counter) {
    input.addEventListener('input', () => {
      const n = input.value.length;
      counter.textContent = n + '/255';
      counter.style.color = n > 230 ? (n >= 255 ? '#f04a4a' : '#f0a84a') : '';
    });
    input.focus();
  }

  // Deshabilita botón al enviar (evita doble submit)
  document.getElementById('addForm')?.addEventListener('submit', e => {
    const btn = e.currentTarget.querySelector('.btn--add');
    btn.disabled = true;
    btn.textContent = '…';
  });

  // Animación de salida al eliminar
  document.querySelectorAll('.delete-form').forEach(f => {
    f.addEventListener('submit', () => {
      const li = f.closest('.task-item');
      if (li) { li.style.opacity = '0'; li.style.transform = 'translateX(20px)'; li.style.transition = '.2s'; }
    });
  });

});
