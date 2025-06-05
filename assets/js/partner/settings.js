import { handleFormSubmit } from "../popup/mostrarpopup.js";
import { setupPasswordToggles } from "../auth/components/togglePassword.js";

document.addEventListener("DOMContentLoaded", function () {
  // Setup password toggles
  setupPasswordToggles();

  // Handle password form submit
  const passwordForm = document.querySelector("#passwordForm");
  if (passwordForm) {
    passwordForm.addEventListener(
      "submit",
      handleFormSubmit(passwordForm, `${BASE_URL}/partner/settings`)
    );
  }
});
