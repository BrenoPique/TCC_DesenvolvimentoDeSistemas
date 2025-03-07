// Script para controlar a visibilidade do botão e funcionalidade de rolar
document.addEventListener('DOMContentLoaded', function() {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');
    
    // Função que detecta a rolagem da página e mostra/oculta o botão
    function toggleScrollBtn() {
      if (window.pageYOffset > 300) {
        scrollToTopBtn.classList.add('visible');
      } else {
        scrollToTopBtn.classList.remove('visible');
      }
    }
    
    // Função para rolar suavemente até o topo da página
    function scrollToTop() {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    }
    
    // Adiciona o evento de clique ao botão
    scrollToTopBtn.addEventListener('click', scrollToTop);
    
    // Adiciona o evento de rolagem à janela
    window.addEventListener('scroll', toggleScrollBtn);
    
    // Verifica a posição inicial da página
    toggleScrollBtn();
  });