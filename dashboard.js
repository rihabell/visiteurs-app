document.addEventListener('DOMContentLoaded', () => {
  const dashboardWrapper = document.querySelector('.dashboard-wrapper');
  if (dashboardWrapper) {
    dashboardWrapper.style.opacity = 0;
    dashboardWrapper.style.transition = 'opacity 0.8s ease-in-out';
    requestAnimationFrame(() => {
      dashboardWrapper.style.opacity = 1;
    });
  }

  const menuToggle = document.querySelector('#menu-toggle');
  const dashboardMenu = document.querySelector('.dashboard-menu');

  if (menuToggle && dashboardMenu) {
    menuToggle.addEventListener('click', () => {
      dashboardMenu.classList.toggle('active');
    });
  }

  const menuLinks = document.querySelectorAll('.dashboard-menu nav ul li a');
  menuLinks.forEach(link => {
    link.addEventListener('mouseenter', () => {
      link.style.color = '#d65a92'; 
    });
    link.addEventListener('mouseleave', () => {
      link.style.color = ''; 
    });
  });
});
