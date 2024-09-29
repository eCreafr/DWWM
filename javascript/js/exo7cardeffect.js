const card = document.querySelectorAll(".card");
card.forEach((el) => {
  el.addEventListener("mousemove", (e) => {
    let elRect = el.getBoundingClientRect();

    //The Element.getBoundingClientRect() method returns a DOMRect object providing information about the size of an element and its position relative to the viewport.

    let x = e.clientX - elRect.x;
    let y = e.clientY - elRect.y;

    let midCardWidth = elRect.x / 2;
    let midCardHeight = elRect.y / 2;

    let angleY = (x - midCardWidth) / 8;
    let angleX = (midCardHeight - y) / 8; // on inverse le calcul par rapport a la video de front end, pour un effet de poids de la souris plus naturel sur la carte !

    let glowX = (x / elRect.width) * 100;
    let glowY = (y / elRect.height) * 100;

    el.children[0].style.transform = `rotateX(${angleX}deg) rotateY(${angleY}deg) scale(1.1)`;
    el.children[1].style.transform = `rotateX(${angleX}deg) rotateY(${angleY}deg) scale(1.1)`;
    el.children[1].style.background = `radial-gradient(circle at ${glowX}% ${glowY}%, rgba(255, 255, 255, 0.5), transparent)`;
    el.children[0].style.boxShadow = `0px 4px 16px rgba(114, 21, 190, 0.5)`; // toujours plus, un effet d'ombre en plus de la video
  });

  el.addEventListener("mouseleave", () => {
    el.children[0].style.transform = `rotateX(0) rotateY(0) scale(1)`;
    el.children[0].style.boxShadow = `0px 0px 0px rgba(0, 0, 0, 0)`; // toujours plus, un effet d'ombre en plus de la video

    el.children[1].style.transform = `rotateX(0) rotateY(0) scale(1)`;
  });
});
