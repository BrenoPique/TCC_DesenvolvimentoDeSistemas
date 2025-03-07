// Importa as funções auxiliares de outros módulos
import { mostrarPopup } from "../popup/mostrarpopup.js"; // Função para mostrar popups de sucesso ou erro
import { changeLoginToRegister } from "./user/changeLoginToRegister.js"; // Função que altera a página de login para registro
import { loginErrorShake } from "./user/loginErrorShake.js"; // Função que aplica um efeito de shake no formulário de login em caso de erro
import { showPassword } from "./global/showPassword.js"; // Função que permite alternar a visibilidade da senha

// Chama as funções importadas para preparar a página
changeLoginToRegister(); // Modifica a interface para permitir a troca entre login e registro
loginErrorShake(); // Aplica o efeito de shake no formulário de login
showPassword(); // Permite mostrar ou ocultar a senha nos campos de senha

// Evento que aguarda o carregamento completo do DOM para iniciar a manipulação
document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("login");
  const registerForm = document.getElementById("register");

  // Adiciona o evento de submit para o formulário de login, caso exista
  if (loginForm) {
    loginForm.addEventListener("submit", (e) => handleFormSubmit(e, "login"));
  }

  // Adiciona o evento de submit para o formulário de registro, caso exista
  if (registerForm) {
    registerForm.addEventListener("submit", (e) =>
      handleFormSubmit(e, "register")
    );
  }

  // Função que lida com o envio do formulário, dependendo do tipo (login ou registro)
  async function handleFormSubmit(event, formType) {
    event.preventDefault(); // Impede que a página seja recarregada automáticamente ao submeter o formulário

    const form = event.target; // Obtém o formulário que foi enviado
    const formData = new FormData(form); // Cria um objeto com os dados do formulário

    try {
      const response = await fetch("/login", {
        method: "POST",
        body: formData, // Envia os dados do formulário
      });

      // Converte a resposta da requisição em JSON
      const result = await response.json();

      // Verifica o tipo de resposta (sucesso ou erro) e exibe o popup correspondente
      if (result.type === "success") {
        const mainContainer = document.getElementById("main-box");
        mainContainer.classList.remove("active"); // Remove a classe "active" para aplicar animação de troca de telas

        // Limpa os campos do formulário de registro após um cadastro bem-sucedido
        const formCadastro = document.getElementById("register");
        const inputsCadastro = formCadastro.querySelectorAll(
          "input:not([type='submit'])" // Seleciona todos os campos de input, exceto o botão de submit
        );
        inputsCadastro.forEach((input) => {
          input.value = ""; // Limpa o valor de cada campo de input
        });

        // Exibe um popup de sucesso com a mensagem retornada do servidor
        mostrarPopup("success", result.message);

        // Redirecionar após login bem-sucedido
        if (formType === "login") {
          setTimeout(() => {
            window.location.href = "/"; // Redireciona para a página inicial após login
          }, 1500); // Aguarda 1.5 segundos para redirecionar
        }
      } else {
        // Exibe um popup de erro se o tipo da resposta for "error"
        mostrarPopup("error", result.message);
      }
    } catch (error) {
      // Se ocorrer um erro durante a requisição, exibe um popup de erro e loga o erro no console
      mostrarPopup("error", "Ocorreu um erro inesperado. Tente novamente.");
      console.error(error); // Exibe o erro no console para facilitar o diagnóstico
    }
  }
});
