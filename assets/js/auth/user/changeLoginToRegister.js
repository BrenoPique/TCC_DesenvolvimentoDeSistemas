export function changeLoginToRegister() {
  const mainContainer = document.getElementById("main-box");
  const registerButton = document.getElementById("btn-register");
  const gotologinButton = document.getElementById("btn-login");

  registerButton.addEventListener("click", () => {
    mainContainer.classList.add("active");
  });

  gotologinButton.addEventListener("click", () => {
    mainContainer.classList.remove("active");
  });
}
