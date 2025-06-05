export function handleFormSubmit(
  form,
  url,
  redirectDelay = 1500,
  extraFormData = null
) {
  return async (e) => {
    e.preventDefault();

    // Disable submit button
    const submitButton = form.querySelector('button[type="submit"]');
    if (submitButton) {
      submitButton.disabled = true;
      submitButton.style.opacity = "0.7";
      submitButton.style.cursor = "not-allowed";
    }

    const formData = new FormData(form);

    // Adiciona dados extras ao formData se fornecidos
    if (extraFormData) {
      Object.entries(extraFormData).forEach(([key, value]) => {
        formData.append(key, value);
      });
    }

    try {
      const response = await fetch(url, {
        method: "POST",
        body: formData,
      });

      const responseText = await response.text();

      let result;
      try {
        result = JSON.parse(responseText);
      } catch (e) {
        console.error("Error parsing JSON:", e);
        throw new Error("Resposta inválida do servidor");
      }

      if (result.type === "success") {
        mostrarPopup("success", result.message);
        if (result.redirect) {
          setTimeout(() => {
            window.location.href = result.redirect;
          }, redirectDelay);
        }
      } else {
        mostrarPopup("error", result.message || "Erro desconhecido");
        enableSubmitButton();
        addShakeEffect();
      }
    } catch (error) {
      console.error("Erro:", error);
      mostrarPopup(
        "error",
        "Erro ao processar a requisição. Por favor, tente novamente."
      );
      enableSubmitButton();
      addShakeEffect();
    }

    function enableSubmitButton() {
      if (submitButton) {
        submitButton.disabled = false;
        submitButton.style.opacity = "1";
        submitButton.style.cursor = "pointer";
      }
    }

    function addShakeEffect() {
      const container = form.closest(".login-container, .auth-container");
      if (container) {
        container.classList.add("shake");
        setTimeout(() => {
          container.classList.remove("shake");
        }, 350);
      }
    }
  };
}

export function mostrarPopup(type, message) {
  const popupContainer = document.getElementById("popup-container");
  const popup = document.createElement("div");
  popup.className = `popup ${type}`;

  const iconPath =
    type === "success"
      ? `${window.BASE_URL}/assets/svg/success.svg`
      : `${window.BASE_URL}/assets/svg/error.svg`;

  popup.innerHTML = `
        <b>
            <img src="${iconPath}" alt="${type}" />
            ${type === "success" ? "Sucesso" : "Erro"}
        </b>
        <p>${message}</p>
    `;

  popupContainer.appendChild(popup);

  setTimeout(() => {
    popup.style.animation = "fadeOut 0.3s ease";
    setTimeout(() => {
      popup.remove();
    }, 300);
  }, 3000);
}
