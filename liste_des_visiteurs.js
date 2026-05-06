document.addEventListener('DOMContentLoaded', () => {
  const searchForm = document.querySelector('.search-form');
  const inputSearch = searchForm.querySelector('input[name="search"]');

  // Recherche en temps réel
  let searchTimeout;
  inputSearch.addEventListener('input', (e) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
      // Créer un formulaire temporaire pour soumettre la recherche
      const tempForm = document.createElement('form');
      tempForm.method = 'get';
      tempForm.action = '';
      
      // Ajouter le paramètre de recherche
      const searchInput = document.createElement('input');
      searchInput.type = 'hidden';
      searchInput.name = 'search';
      searchInput.value = e.target.value;
      tempForm.appendChild(searchInput);
      
      // Soumettre le formulaire
      document.body.appendChild(tempForm);
      tempForm.submit();
    }, 500); // Délai de 500ms pour éviter trop de requêtes
  });

  // Permettre aussi la soumission normale du formulaire
  searchForm.addEventListener('submit', (e) => {
    // Pas de validation - permettre la recherche même avec un champ vide
  });

  const clearBtn = document.createElement('button');
  clearBtn.type = 'button';
  clearBtn.textContent = '✕';
  clearBtn.title = 'Effacer la recherche';
  clearBtn.className = 'clear-search-btn';
  clearBtn.style.marginLeft = '8px';
  clearBtn.style.cursor = 'pointer';
  clearBtn.style.background = 'transparent';
  clearBtn.style.border = 'none';
  clearBtn.style.fontSize = '1.2rem';
  clearBtn.style.color = '#b73a5b';

  inputSearch.after(clearBtn);

  clearBtn.addEventListener('click', () => {
    inputSearch.value = '';
    // Déclencher la recherche automatiquement après avoir effacé
    inputSearch.dispatchEvent(new Event('input'));
  });
});

