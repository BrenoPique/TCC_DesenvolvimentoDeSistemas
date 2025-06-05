import { handleFormSubmit } from "../../popup/mostrarpopup.js";
import { setupPasswordToggles } from "../components/togglePassword.js";

document.addEventListener("DOMContentLoaded", () => {
  const loginForm = document.getElementById("login");
  setupPasswordToggles();

  if (loginForm) {
    loginForm.addEventListener(
      "submit",
      handleFormSubmit(loginForm, `${window.BASE_URL}/partner/login`, 1000)
    );
  }
});
