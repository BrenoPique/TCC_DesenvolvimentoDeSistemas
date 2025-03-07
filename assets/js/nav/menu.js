document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.querySelector(".menu-toggle");
  const menu = document.getElementById("menu");

  menuToggle.addEventListener("click", function () {
    menu.classList.toggle("active");
  });

  // Fechar o menu ao clicar em um link
  const menuLinks = document.querySelectorAll("#menu a");
  menuLinks.forEach((link) => {
    link.addEventListener("click", function () {
      menu.classList.remove("active");
    });
  });

  // Fechar o menu ao redimensionar a janela para desktop
  window.addEventListener("resize", function () {
    if (window.innerWidth > 768) {
      menu.classList.remove("active");
    }
  });
});
