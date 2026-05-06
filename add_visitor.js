document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('form.form-visitor');
  const messageBox = document.createElement('p');
  messageBox.style.fontWeight = '600';
  messageBox.style.marginTop = '12px';
  form.appendChild(messageBox);

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    // Récupérer les valeurs et nettoyer
    const nom = form.nom.value.trim();
    const prenom = form.prenom.value.trim();
    const cin = form.cin.value.trim();
    const societe = form.societe.value.trim();
    const personne = form.personne.value.trim();
    const objet = form.objet.value.trim();

    // Validation simple
    if (!nom || !prenom || !cin || !personne || !objet) {
      showMessage('Merci de remplir tous les champs obligatoires.', 'error');
      return;
    }

    if (!validateCin(cin)) {
      showMessage('Le CIN est invalide. Il doit être alphanumérique.', 'error');
      return;
    }

    // Option: ici tu pourrais faire un envoi AJAX, mais pour l'instant on soumet le formulaire normalement
    // form.submit();

    showMessage('Formulaire validé. Envoi en cours...', 'success');

    // Pour soumettre après validation (décommenter la ligne suivante)
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

  function validateCin(cin) {
    // Validation simple: alphanumérique, longueur entre 5 et 15 (à adapter selon norme)
    const re = /^[a-zA-Z0-9]{5,15}$/;
    return re.test(cin);
  }
});
