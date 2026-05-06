document.addEventListener('DOMContentLoaded', () => {
  const inputUser = document.getElementById('user');
  const inputAction = document.getElementById('action');
  const table = document.querySelector('table tbody');
  const rows = Array.from(table.querySelectorAll('tr'));

  function filterLogs() {
    const filterUser = inputUser.value.toLowerCase();
    const filterAction = inputAction.value.toLowerCase();

    rows.forEach(row => {
      const userCell = row.cells[1].textContent.toLowerCase();
      const actionCell = row.cells[2].textContent.toLowerCase();

      const matchesUser = userCell.includes(filterUser);
      const matchesAction = actionCell.includes(filterAction);

      if (matchesUser && matchesAction) {
        row.style.display = '';
      } else {
        row.style.display = 'none';
      }
    });

    const visibleRows = rows.filter(r => r.style.display !== 'none');
    const noLogsMsg = document.querySelector('.no-logs');
    if (visibleRows.length === 0) {
      if (!noLogsMsg) {
        const p = document.createElement('p');
        p.className = 'no-logs';
        p.textContent = 'Aucun résultat trouvé.';
        table.parentNode.insertBefore(p, table.nextSibling);
      }
    } else {
      if (noLogsMsg) {
        noLogsMsg.remove();
      }
    }
  }

  inputUser.addEventListener('input', filterLogs);
  inputAction.addEventListener('input', filterLogs);
});
