import { mostrarPopup } from "../../popup/mostrarpopup.js";
import { setupPasswordToggles } from "../components/togglePassword.js";

document.addEventListener("DOMContentLoaded", () => {
  const registerForm = document.getElementById("register");
  setupPasswordToggles();

  if (registerForm) {
    registerForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(registerForm);

      try {
        const response = await fetch(`${window.BASE_URL}/user/register`, {
          method: "POST",
          body: formData,
        });

        const result = await response.json();

        if (response.ok) {
          if (result.type === "success") {
            mostrarPopup("success", result.message);
            if (result.redirect) {
              setTimeout(() => {
                window.location.href = result.redirect;
              }, 1000);
            }
          } else {
            mostrarPopup("error", result.message);
          }
        } else {
          mostrarPopup(
            "error",
            result.message || "Erro ao processar a requisição"
          );
        }
      } catch (error) {
        console.error("Erro:", error);
        mostrarPopup(
          "error",
          "Erro ao processar a requisição. Por favor, tente novamente."
        );
      }
    });
  }
});


