import { handleFormSubmit } from "../popup/mostrarpopup.js";

document.addEventListener("DOMContentLoaded", function () {
  // Verificar se há parâmetro de edição na URL e abrir modal
  const urlParams = new URLSearchParams(window.location.search);
  const editId = urlParams.get("edit");
  if (editId) {
    document.getElementById("editModal").classList.add("active");
  }

  // Função para abrir modal
  function openModal(modalId) {
    document.getElementById(modalId).classList.add("active");
  }

  // Handlers para botão de adicionar prato
  const addBtns = document.querySelectorAll(".btn-add-prato");
  addBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      openModal("addModal");
    });
  });

  // Fechar modais
  const closeBtns = document.querySelectorAll(".modal-close");
  closeBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      const modal = btn.closest(".modal");
      modal.classList.remove("active");
      // Se estiver no modo de edição, redirecionar para a página sem parâmetros
      if (editId) {
        window.location.href = `${BASE_URL}/partner/menu`;
      }
    });
  });

  // Form submit para adicionar prato - atualizado para usar handleFormSubmit
  const addForm = document.querySelector("#addModal form");
  if (addForm) {
    addForm.addEventListener(
      "submit",
      handleFormSubmit(addForm, `${BASE_URL}/partner/menu`, 1000)
    );
  }

  // Atualizar formulário de edição para usar handleFormSubmit
  const editForm = document.querySelector("#editModal form");
  if (editForm) {
    editForm.addEventListener(
      "submit",
      handleFormSubmit(editForm, `${BASE_URL}/partner/menu`, 1000)
    );
  }

  // Manipulador para botões de exclusão
  const deleteBtns = document.querySelectorAll(".btn-delete");
  let pratoIdToDelete = null;

  deleteBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      pratoIdToDelete = btn.dataset.id;
      openModal("deleteModal");
    });
  });

  // Botão de confirmação de exclusão
  const btnConfirmDelete = document.querySelector(".btn-confirm-delete");
  const btnCancelDelete = document.querySelector(".btn-cancel");

  btnConfirmDelete.addEventListener("click", async () => {
    if (!pratoIdToDelete) return;

    const formData = new FormData();
    formData.append("action", "delete");
    formData.append("id", pratoIdToDelete);

    try {
      const response = await fetch(`${BASE_URL}/partner/menu`, {
        method: "POST",
        body: formData,
      });

      if (!response.ok) {
        throw new Error("Erro na requisição");
      }

      const data = await response.json();
      if (data.type === "success") {
        window.location.reload();
      } else {
        alert(data.message);
      }
    } catch (error) {
      console.error("Erro:", error);
      alert("Erro ao excluir prato. Tente novamente.");
    }
  });

  // Cancelar exclusão
  btnCancelDelete.addEventListener("click", () => {
    pratoIdToDelete = null;
    document.getElementById("deleteModal").classList.remove("active");
  });
});
