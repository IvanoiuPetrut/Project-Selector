const openModalBtn = document.querySelector("#create-project-btn-open");
const closeModalBtn = document.querySelector("#create-project-btn-close");
const overlay = document.querySelector("#overlay");

openModalBtn.addEventListener("click", function () {
  overlay.classList.add("active");
  // document.body.classList.add("modal-open");
  console.log("open");
});
