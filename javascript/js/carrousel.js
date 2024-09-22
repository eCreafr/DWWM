const carrouselSlide = document.querySelector(".carrousel-slide");
const images = document.querySelectorAll(".carrousel-slide img");

// Compteur
let counter = 0;
const size = images[0].clientWidth;

// Position de départ
carrouselSlide.style.transform = "translateX(" + -size * counter + "px)";

// Boutons
const prevBtn = document.getElementById("prevBtn");
const nextBtn = document.getElementById("nextBtn");

// Bouton suivant
nextBtn.addEventListener("click", () => {
  if (counter >= images.length - 1) return; // Empêche de sortir des limites
  carrouselSlide.style.transition = "transform 0.4s ease-in-out";
  counter++;
  carrouselSlide.style.transform = "translateX(" + -size * counter + "px)";
});

// Bouton précédent
prevBtn.addEventListener("click", () => {
  if (counter <= 0) return; // Empêche de sortir des limites
  carrouselSlide.style.transition = "transform 0.4s ease-in-out";
  counter--;
  carrouselSlide.style.transform = "translateX(" + -size * counter + "px)";
});

// Fonction pour la lecture automatique
function autoPlay() {
  nextBtn.click();
}

// Démarrer la lecture automatique toutes les 3 secondes
let autoPlayInterval = setInterval(autoPlay, 3000);

// Pause automatique lors d'un hover (survol de la souris)
carrouselSlide.addEventListener("mouseover", () => {
  clearInterval(autoPlayInterval);
});

// Reprendre la lecture automatique lorsque la souris quitte
carrouselSlide.addEventListener("mouseout", () => {
  autoPlayInterval = setInterval(autoPlay, 3000);
});
