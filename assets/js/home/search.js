import { mostrarPopup } from "../popup/mostrarpopup.js";

let map;
let markers = [];

function initMap() {
  const centerLat = window.centerLat;
  const centerLng = window.centerLng;

  if (centerLat && centerLng) {
    const center = {
      lat: parseFloat(centerLat),
      lng: parseFloat(centerLng),
    };
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 16,
      center: center,
    });

    // Add a marker for the searched location
    new google.maps.Marker({
      position: center,
      map: map,
      icon: {
        url: BASE_URL + "/assets/img/user-location.png",
        scaledSize: new google.maps.Size(30, 60),
      },
    });

    // Add markers for restaurants
    document.querySelectorAll(".restaurant-card").forEach((card) => {
      const lat = parseFloat(card.dataset.lat);
      const lng = parseFloat(card.dataset.lng);

      if (lat && lng) {
        const marker = new google.maps.Marker({
          position: { lat, lng },
          map: map,
          title: card.querySelector("h3").textContent,
        });

        markers.push(marker);

        // Highlight restaurant card when marker is clicked
        marker.addListener("click", () => {
          card.scrollIntoView({ behavior: "smooth" });
          card.classList.add("highlighted");
          setTimeout(() => card.classList.remove("highlighted"), 2000);
        });
      }
    });
  }
}

function handleFavoriteClick(restaurantId, isLoggedIn) {
  if (!isLoggedIn) {
    showLoginPopup("Faça login para favoritar restaurantes");
    return;
  }
  // existing favorite logic
}

function handleReviewClick(event, restaurantId, restaurantName, isLoggedIn) {
  event.preventDefault();
  if (!isLoggedIn) {
    showLoginPopup("Faça login para avaliar restaurantes");
    return;
  }
  openReviewModal(restaurantId, restaurantName);
}

function showLoginPopup(message) {
  const result = confirm(message + ". Deseja fazer login?");
  if (result) {
    window.location.href = BASE_URL + "/user/login";
  }
}

// Initialize favorite buttons
document.querySelectorAll(".favorite-btn").forEach((btn) => {
  btn.addEventListener("click", function (e) {
    e.preventDefault();
    const restaurantId = this.dataset.id;
    this.classList.toggle("is-favorite");
  });
});

function openReviewModal(restaurantId, restaurantName) {
  const form = document.getElementById("reviewForm");
  form.reset();

  document.getElementById("restaurantId").value = restaurantId.toString();
  document.getElementById("restaurantName").textContent = restaurantName;
  document.getElementById("reviewModal").style.display = "flex";
}

function closeReviewModal() {
  document.getElementById("reviewModal").style.display = "none";
  document.getElementById("reviewForm").reset();
  document.getElementById("restaurantId").value = "";
  document.getElementById("restaurantName").textContent = "";
  document.getElementById("reviewText").value = "";
  document
    .querySelectorAll('.stars input[type="radio"]')
    .forEach((radio) => (radio.checked = false));
}

window.onclick = function (event) {
  const modal = document.getElementById("reviewModal");
  if (event.target === modal) {
    closeReviewModal();
  }
};

function submitReview(event) {
  event.preventDefault();
  const form = document.getElementById("reviewForm");
  const formData = new FormData(form);

  const restaurantId = formData.get("restaurantId");
  const rating = formData.get("rating");
  const reviewText = formData.get("reviewText");

  if (!restaurantId || !rating || !reviewText) {
    mostrarPopup("error", "Por favor, preencha todos os campos");
    return;
  }

  fetch(`${BASE_URL}/review/submit`, {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((text) => {
      let data;
      try {
        data = JSON.parse(text);
      } catch (e) {
        console.error("Error parsing JSON:", text);
        throw new Error("Resposta inválida do servidor");
      }

      if (data.type === "success") {
        mostrarPopup("success", data.message);
        setTimeout(() => {
          closeReviewModal();
          window.location.reload();
        }, 1500);
      } else {
        mostrarPopup("error", data.message);
      }
    })
    .catch((error) => {
      mostrarPopup("error", error.message || "Erro ao enviar avaliação");
      console.error("Erro:", error);
    });
}

// Expor funções necessárias globalmente
window.initMap = initMap;
window.handleReviewClick = handleReviewClick;
window.submitReview = submitReview;
window.closeReviewModal = closeReviewModal;
window.map = map;
window.markers = markers;
