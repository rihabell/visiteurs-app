document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  const input = form.querySelector('input[name="badge_code"]');

  const messageBox = document.createElement('p');
  messageBox.style.color = '#D32F2F'; 
  messageBox.style.fontWeight = '600';
  messageBox.style.marginTop = '12px';
  form.appendChild(messageBox);

  form.addEventListener('submit', (e) => {
    const code = input.value.trim();
    if (!code) {
      e.preventDefault();
      messageBox.textContent = 'Veuillez entrer un code badge valide.';
    } else {
      messageBox.textContent = '';
    }
  });
});
