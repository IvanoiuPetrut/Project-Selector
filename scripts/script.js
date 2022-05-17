// const openModalBtn = document.querySelector("#create-project-btn-open");
// const closeModalBtn = document.querySelector("#create-project-btn-close");
// const overlay = document.querySelector("#overlay");

// openModalBtn.addEventListener("click", function () {
//   overlay.classList.add("active");
//   // document.body.classList.add("modal-open");
//   console.log("open");
// });

console.log("Hello World");

const accordion = document.getElementsByClassName("icon-accordion");

// Create a function that will toggle the "active" class on the accordion item when the user clicks on it.
for (let i = 0; i < accordion.length; i++) {
  accordion[i].addEventListener("click", function () {
    this.classList.toggle("active");
    console.log("click");
  });
}
