// Jsut use for menu button in mobile view.

const menuBtn = document.querySelector(".menu-icon span");
const items = document.querySelector(".nav-items");
menuBtn.onclick = ()=>{
  items.classList.add("active");
  menuBtn.classList.add("hide");
}