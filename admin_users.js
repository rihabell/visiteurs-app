document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.form-create');

  const messageBox = document.createElement('div');
  messageBox.style.marginTop = '15px';
  messageBox.style.fontWeight = '600';
  messageBox.style.textAlign = 'center';
  form.appendChild(messageBox);

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const nom = form.nom.value.trim();
    const prenom = form.prenom.value.trim();
    const email = form.email.value.trim();
    const mdp = form.mot_de_passe.value.trim();
    const role = form.role.value;

    if (!nom || !prenom || !email || !mdp || !role) {
      showMessage('Merci de remplir tous les champs.', 'error');
      return;
    }

    if (!validateEmail(email)) {
      showMessage('Veuillez entrer un email valide.', 'error');
      return;
    }

    showMessage('Utilisateur créé avec succès !', 'success');

    form.reset();
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
