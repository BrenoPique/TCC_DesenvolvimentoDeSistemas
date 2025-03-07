export function loginErrorShake() {
  document.getElementById("login").addEventListener("submit", function (event) {
    event.preventDefault(); // Impede o envio do formulário para simular o erro
    const mainBox = document.getElementById("main-box");
    mainBox.classList.add("shake");

    setTimeout(() => {
      mainBox.classList.remove("shake");
    }, 350);
  });
}
