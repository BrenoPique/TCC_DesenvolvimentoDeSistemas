export function showPassword() {
  // Seleciona todos os botões de exibir/ocultar senha
  const toggleButtons = document.querySelectorAll(".togglePassword");

  toggleButtons.forEach((button) => {
    button.addEventListener("click", () => {
      // Identifica o campo alvo através do atributo data-target
      const targetId = button.getAttribute("data-target");
      const campoSenha = document.getElementById(targetId);

      // Alterna entre 'password' e 'text'
      const type =
        campoSenha.getAttribute("type") === "password" ? "text" : "password";
      campoSenha.setAttribute("type", type);

      // Atualiza o ícone do botão
      button.innerHTML =
        type === "password"
          ? '<img src="../assets/svg/eyeClosed.svg">'
          : '<img src="../assets/svg/eyeOpen.svg">';
    });
  });
}
