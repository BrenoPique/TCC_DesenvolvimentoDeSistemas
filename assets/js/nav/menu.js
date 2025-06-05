document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.querySelector(".menu-toggle");
  const menu = document.getElementById("menu");

  menuToggle.addEventListener("click", function () {
    menu.classList.toggle("active");
    menuToggle.classList.toggle("active");
    document.body.style.overflow = menu.classList.contains("active")
      ? "hidden"
      : "";
  });

  // Fechar o menu ao clicar em um link
  const menuLinks = document.querySelectorAll("#menu a");
  menuLinks.forEach((link) => {
    link.addEventListener("click", function () {
      menu.classList.remove("active");
      menuToggle.classList.remove("active");
      document.body.style.overflow = "";
    });
  });

  // Fechar o menu ao redimensionar a janela para desktop
  window.addEventListener("resize", function () {
    if (window.innerWidth > 800) {
      menu.classList.remove("active");
      menuToggle.classList.remove("active");
      document.body.style.overflow = "";
    }
  });
});
