export function setupPasswordToggles() {
  const toggleButtons = document.querySelectorAll(".togglePassword");

  toggleButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const targetId = button.getAttribute("data-target");
      const passwordInput = document.getElementById(targetId);

      const type =
        passwordInput.getAttribute("type") === "password" ? "text" : "password";
      passwordInput.setAttribute("type", type);

      const img = button.querySelector("img");
      img.src =
        type === "password"
          ? "/assets/svg/eyeClosed.svg"
          : "/assets/svg/eyeOpen.svg";
    });
  });
}
