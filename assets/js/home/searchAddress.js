document.addEventListener("DOMContentLoaded", function () {
  const input = document.getElementById("searchAddress");
  if (!input) return;

  let timeoutId;
  const searchForm = input.closest("form");
  const suggestionsContainer = document.createElement("div");
  suggestionsContainer.className = "address-suggestions";
  searchForm.appendChild(suggestionsContainer);

  input.addEventListener("input", function () {
    clearTimeout(timeoutId);

    if (this.value.length < 3) {
      suggestionsContainer.innerHTML = "";
      return;
    }

    timeoutId = setTimeout(() => {
      fetch(
        `${BASE_URL}/places/autocomplete?query=${encodeURIComponent(
          this.value
        )}`
      )
        .then((response) => response.json())
        .then((data) => {
          suggestionsContainer.innerHTML = "";
          if (data.predictions) {
            data.predictions.forEach((place) => {
              const div = document.createElement("div");
              div.className = "suggestion-item";
              div.textContent = place.description;
              div.addEventListener("click", () => {
                input.value = place.description;
                // Usar as coordenadas do mock
                document.getElementById("lat").value =
                  place.geometry.location.lat;
                document.getElementById("lng").value =
                  place.geometry.location.lng;
                suggestionsContainer.innerHTML = "";
              });
              suggestionsContainer.appendChild(div);
            });
          }
        })
        .catch((error) => console.error("Erro:", error));
    }, 300);
  });

  // Fechar sugestões ao clicar fora
  document.addEventListener("click", function (e) {
    if (!input.contains(e.target) && !suggestionsContainer.contains(e.target)) {
      suggestionsContainer.innerHTML = "";
    }
  });

  // Prevenir submissão do formulário ao pressionar enter
  input.addEventListener("keypress", function (e) {
    if (e.key === "Enter" && suggestionsContainer.hasChildNodes()) {
      e.preventDefault();
    }
  });

  const searchInput = document.getElementById("searchAddress");

  function initAutocomplete() {
    searchInput.addEventListener(
      "input",
      debounce(async function (e) {
        const query = e.target.value;
        if (query.length < 3) return;

        try {
          const response = await fetch(
            `${BASE_URL}/places/autocomplete?query=${encodeURIComponent(query)}`
          );
          const data = await response.json();

          // Limpar resultados anteriores
          removeAutocompleteDropdown();

          // Criar e mostrar dropdown
          if (data.predictions && data.predictions.length > 0) {
            createAutocompleteDropdown(data.predictions);
          }
        } catch (error) {
          console.error("Erro ao buscar endereços:", error);
        }
      }, 300)
    );

    // Fechar dropdown quando clicar fora
    document.addEventListener("click", function (e) {
      if (!e.target.closest(".search-inputs")) {
        removeAutocompleteDropdown();
      }
    });
  }

  function createAutocompleteDropdown(predictions) {
    const dropdown = document.createElement("div");
    dropdown.className = "autocomplete-dropdown";

    predictions.forEach((place) => {
      const item = document.createElement("div");
      item.className = "autocomplete-item";
      item.textContent = place.description;

      item.addEventListener("click", () => {
        searchInput.value = place.description;
        document.getElementById("lat").value = place.geometry.location.lat;
        document.getElementById("lng").value = place.geometry.location.lng;
        removeAutocompleteDropdown();
      });

      dropdown.appendChild(item);
    });

    // Inserir dropdown após o input
    searchInput.parentNode.appendChild(dropdown);
  }

  function removeAutocompleteDropdown() {
    const existing = document.querySelector(".autocomplete-dropdown");
    if (existing) existing.remove();
  }

  function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
      const later = () => {
        clearTimeout(timeout);
        func(...args);
      };
      clearTimeout(timeout);
      timeout = setTimeout(later, wait);
    };
  }

  // Inicializar quando o documento estiver pronto
  document.addEventListener("DOMContentLoaded", initAutocomplete);
});
