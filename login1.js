document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form');
  const emailInput = form.querySelector('input[name="email"]');
  const passwordInput = form.querySelector('input[name="mot_de_passe"]');

  const messageBox = document.createElement('p');
  messageBox.style.color = '#D32F2F';
  messageBox.style.fontWeight = '600';
  messageBox.style.marginTop = '12px';
  messageBox.style.textAlign = 'center';
  form.insertBefore(messageBox, form.firstChild);

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const email = emailInput.value.trim();
    const password = passwordInput.value.trim();

    if (!email || !password) {
      showMessage('Veuillez remplir tous les champs.', 'error');
      return;
    }

    if (!validateEmail(email)) {
      showMessage('Adresse email invalide.', 'error');
      return;
    }

    showMessage('Connexion en cours...', 'success');
    form.submit();
  });

  function showMessage(msg, type) {
    messageBox.textContent = msg;
    messageBox.style.color = type === 'success' ? '#4CAF50' : '#D32F2F';
    messageBox.style.opacity = '1';

    setTimeout(() => {
      messageBox.style.opacity = '0';
    }, 4000);
  }

  function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }
});
