import { handleFormSubmit } from "../../popup/mostrarpopup.js";
import { setupPasswordToggles } from "../components/togglePassword.js";

document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("register");
  setupPasswordToggles();

  if (registerForm) {
    registerForm.addEventListener(
      "submit",
      handleFormSubmit(
        registerForm,
        `${window.BASE_URL}/partner/register`,
        1500
      )
    );
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const cepInput = document.getElementById("cep");
  const buscarBtn = document.getElementById("buscarCep");

  buscarBtn.addEventListener("click", async () => {
    const rawCep = cepInput.value.replace(/\D/g, "");
    if (!/^[0-9]{8}$/.test(rawCep)) {
      alert("CEP inválido. Digite os 8 números corretamente.");
      return;
    }

    try {
      const response = await fetch(`https://viacep.com.br/ws/${rawCep}/json/`);
      const data = await response.json();

      if (data.erro) {
        alert("CEP não encontrado.");
        return;
      }

      document.getElementById("rua").value = data.logradouro || "";
      document.getElementById("bairro").value = data.bairro || "";
      document.getElementById("cidade").value = data.localidade || "";
      document.getElementById("estado").value = data.uf || "";
    } catch (error) {
      alert("Erro ao buscar o CEP. Tente novamente.");
      console.error(error);
    }
  });
});
