document.addEventListener("DOMContentLoaded", function () {
  // Add click handlers to all favorite buttons
  document.querySelectorAll(".favorite-btn").forEach((button) => {
    button.addEventListener("click", async function (e) {
      e.preventDefault();
      e.stopPropagation(); // Impede que o clique propague para o link do restaurante

      const restaurantId = this.dataset.restaurantId;
      const isLoggedIn = this.dataset.loggedIn === "true";

      if (!isLoggedIn) {
        showLoginPopup();
        return;
      }

      try {
        const response = await fetch(
          `${BASE_URL}/favorite/toggle/${restaurantId}`,
          {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
          }
        );

        const data = await response.json();

        if (data.success) {
          // Toggle the class based on the server response
          this.classList.toggle("is-favorite", data.isFavorite);

          // Se estiver na página do painel e desfavoritou, remove o card do restaurante
          if (
            window.location.pathname.includes("/user/panel") &&
            !data.isFavorite
          ) {
            const favoriteItem = this.closest(".favorito-item");
            if (favoriteItem) {
              favoriteItem.remove();

              // Verifica se ainda existem favoritos
              const favoritosList = document.querySelector(".favoritos-list");
              if (
                favoritosList &&
                !favoritosList.querySelector(".favorito-item")
              ) {
                favoritosList.innerHTML =
                  '<p class="no-favorites">Você ainda não tem restaurantes favoritos.</p>';
              }
            }
          }
        } else {
          console.error("Erro ao atualizar favorito:", data.message);
        }
      } catch (error) {
        console.error("Erro na requisição:", error);
      }
    });
  });
});

function showLoginPopup() {
  const result = confirm(
    "Faça login para favoritar restaurantes. Deseja fazer login?"
  );
  if (result) {
    window.location.href = `${BASE_URL}/user/login`;
  }
}
