import { handleFormSubmit } from "../popup/mostrarpopup.js";

document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".profile-form");

  if (form) {
    form.addEventListener(
      "submit",
      handleFormSubmit(form, `${window.BASE_URL}/partner/profile`, 1500, {
        formType: "profile",
      })
    );
  }

  // Adiciona callback para atualizar imagens após sucesso
  const updateImages = () => {
    const logos = document.querySelectorAll(
      "img.restaurant-logo, #preview-logo"
    );
    logos.forEach((logo) => {
      const currentSrc = logo.src.split("?")[0];
      logo.src = `${currentSrc}?v=${new Date().getTime()}`;
    });
  };

  document.getElementById("logo").addEventListener("change", function (e) {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById("preview-logo").src = e.target.result;
      };
      reader.readAsDataURL(file);
    }
  });

  document.getElementById("cep").addEventListener("blur", function (e) {
    const cep = e.target.value.replace(/\D/g, "");
    if (cep.length === 8) {
      fetch(`https://viacep.com.br/ws/${cep}/json/`)
        .then((response) => response.json())
        .then((data) => {
          if (!data.erro) {
            document.getElementById("rua").value = data.logradouro;
            document.getElementById("bairro").value = data.bairro;
            document.getElementById("cidade").value = data.localidade;
            document.getElementById("estado").value = data.uf;
          }
        });
    }
  });
});
