document.addEventListener("DOMContentLoaded", function () {
  const filterCheckboxes = document.querySelectorAll(
    'input[name="filter_restricao"]'
  );
  const menuItems = document.querySelectorAll(".menu-item");

  function filterDishes() {
    const selectedRestrictions = Array.from(filterCheckboxes)
      .filter((cb) => cb.checked)
      .map((cb) => cb.value);

    menuItems.forEach((item) => {
      // Se nenhuma restrição estiver selecionada, mostra todos os pratos
      if (selectedRestrictions.length === 0) {
        item.style.display = "flex";
        return;
      }

      // Pega todas as restrições do prato atual
      const dishRestrictions = Array.from(
        item.querySelectorAll(".restriction-tag")
      )
        .map((tag) => {
          // Encontra o checkbox correspondente a esta tag
          const checkbox = Array.from(filterCheckboxes).find(
            (cb) =>
              cb.nextElementSibling.textContent.trim() ===
              tag.textContent.trim()
          );
          return checkbox ? checkbox.value : null;
        })
        .filter(Boolean);

      // Verifica se o prato tem TODAS as restrições selecionadas
      const hasAllRestrictions = selectedRestrictions.every((selectedId) =>
        dishRestrictions.includes(selectedId)
      );

      // Mostra apenas os pratos que têm todas as restrições selecionadas
      item.style.display = hasAllRestrictions ? "flex" : "none";
    });
  }

  // Adiciona o evento de change em cada checkbox
  filterCheckboxes.forEach((checkbox) => {
    checkbox.addEventListener("change", filterDishes);
  });
});
