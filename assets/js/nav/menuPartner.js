document.addEventListener("DOMContentLoaded", function () {
  const navItems = document.querySelectorAll(".nav-item");
  const currentPath = window.location.pathname;
  const mobileToggle = document.querySelector(".mobile-nav-toggle");
  const nav = document.querySelector(".dashboard-nav");
  const overlay = document.querySelector(".nav-overlay");

  // Função para remover classe active de todos os itens
  function removeAllActive() {
    navItems.forEach((item) => {
      item.classList.remove("active");
    });
  }

  // Adiciona classe active ao item correspondente à página atual
  navItems.forEach((item) => {
    const link = item.querySelector("a");
    const href = link.getAttribute("href");

    // Verifica se o href corresponde ao caminho atual
    if (currentPath === href) {
      removeAllActive();
      item.classList.add("active");
    }

    // Adiciona animação ao hover
    item.addEventListener("mouseenter", function () {
      if (!this.classList.contains("active")) {
        this.style.transition = "all 0.3s ease";
        this.style.transform = "translateX(10px)";
      }
    });

    item.addEventListener("mouseleave", function () {
      if (!this.classList.contains("active")) {
        this.style.transform = "translateX(0)";
      }
    });
  });

  // Toggle menu mobile
  function toggleMenu() {
    nav.classList.toggle("active");
    mobileToggle.classList.toggle("active");
    overlay.classList.toggle("active");
    document.body.style.overflow = nav.classList.contains("active")
      ? "hidden"
      : "";
  }

  if (mobileToggle) {
    mobileToggle.addEventListener("click", toggleMenu);
  }

  // Fecha menu ao clicar no overlay
  if (overlay) {
    overlay.addEventListener("click", toggleMenu);
  }

  // Fecha menu ao clicar em um link
  const navLinks = document.querySelectorAll(".nav-item a");
  navLinks.forEach((link) => {
    link.addEventListener("click", toggleMenu);
  });

  // Fecha menu ao redimensionar para desktop
  window.addEventListener("resize", function () {
    if (window.innerWidth > 768 && nav.classList.contains("active")) {
      toggleMenu();
    }
  });
});
