import { mostrarPopup } from "../popup/mostrarpopup.js";
import { setupPasswordToggles } from "../auth/components/togglePassword.js";

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("editProfile");
  setupPasswordToggles();

  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const formData = new FormData(form);

      try {
        const response = await fetch(`${window.BASE_URL}/user/edit`, {
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
