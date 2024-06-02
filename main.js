const menuBtn = document.getElementById("menu-btn");
const navLinks = document.getElementById("nav-links");
const menuBtnIcon = menuBtn.querySelector("i");

menuBtn.addEventListener("click", (e) => {
  navLinks.classList.toggle("open");

  const isOpen = navLinks.classList.contains("open");
  menuBtnIcon.setAttribute("class", isOpen ? "ri-close-line" : "ri-menu-line");
});

navLinks.addEventListener("click", (e) => {
  navLinks.classList.remove("open");
  menuBtnIcon.setAttribute("class", "ri-menu-line");
});

const scrollRevealOption = {
  distance: "50px",
  origin: "bottom",
  duration: 900,
};

ScrollReveal().reveal(".header__image img", {
  ...scrollRevealOption,
  origin: "right",
});
ScrollReveal().reveal(".header__content h2", {
  ...scrollRevealOption,
  delay: 300,
});
ScrollReveal().reveal(".header__content h1", {
  ...scrollRevealOption,
  delay: 600,
});
ScrollReveal().reveal(".header__content p1", {
  ...scrollRevealOption,
  delay: 900,
});
ScrollReveal().reveal(".header__content .header__btn", {
  ...scrollRevealOption,
  delay: 1200,
});
ScrollReveal().reveal(".header__content .socials", {
  ...scrollRevealOption,
  delay: 1500,
});
ScrollReveal().reveal(".Clinical", {
  ...scrollRevealOption,
  delay: 1800,
});
ScrollReveal().reveal(".Laboratory", {
  ...scrollRevealOption,
  delay: 2100,
});
ScrollReveal().reveal(".Pharmaceutical", {
  ...scrollRevealOption,
  delay: 2400,
});
ScrollReveal().reveal(".Nursing", {
  ...scrollRevealOption,
  delay: 2700,
});