const urlParams = new URLSearchParams(window.location.search);
const mode = urlParams.get("mode");

const sign_up_link = document.querySelector("#sign-up-link");
const sign_in_link = document.querySelector("#sign-in-link");
const container = document.querySelector(".container");
const sign_in_form = document.querySelector(".sign-in-form");
const sign_up_form = document.querySelector(".sign-up-form");

sign_up_link.addEventListener("click", () => {
  container.classList.add("sign-up-mode");
});

sign_in_link.addEventListener("click", () => {
  container.classList.remove("sign-up-mode");
});

if (mode === "inscription") {
  container.classList.add("sign-up-mode");
} else {
  container.classList.remove("sign-up-mode");
}
