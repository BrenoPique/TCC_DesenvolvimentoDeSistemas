export function mostrarPopup(type, message) {
  const container = document.getElementById("popup-container");

  // Criar um novo popup
  const popup = document.createElement("div");
  popup.classList.add("popup");

  // Personalizar popup conforme o tipo
  if (type === "success") {
    popup.innerHTML = `
            <b><img src="../assets/img/success.png" alt="Sucesso!" /> Sucesso!</b>
            <p>${message}</p>
        `;
    popup.classList.add("success");
  } else if (type === "error") {
    popup.innerHTML = `
            <b><img src="../assets/img/error.png" alt="Erro!" /> Erro!</b>
            <p>${message}</p>
        `;
    popup.classList.add("error");
  } else {
    console.error("Tipo de popup inválido:", type);
    return;
  }

  // Adicionar o popup ao container
  container.appendChild(popup);

  // Remover popup automaticamente após 5 segundos
  setTimeout(() => {
    if (popup.parentElement) {
      popup.remove();
    }
  }, 5000);
}
