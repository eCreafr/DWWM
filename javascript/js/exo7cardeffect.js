const card = document.querySelectorAll(".card");
card.forEach((el) => {
  el.addEventListener("mousemove", (e) => {
    let elRect = el.getBoundingClientRect();

    let x = e.clientX - elRect.x;
    let y = e.clientY - elRect.y;

    let midCardWidth = elRect.x / 2;
    let midCardHeight = elRect.height / 2;

    let angleY = (x - midCardWidth) / 8;
    let angleX = (y - midCardHeight) / 8;

    let glowX = (x / elRect.width) * 100;
    let glowY = (y / elRect.height) * 100;

    el.children[0].style.transform = `rotateX(${angleX}deg) rotateY(${angleY}deg) scale(1.1)`;
    el.children[1].style.transform = `rotateX(${angleX}deg) rotateY(${angleY}deg) scale(1.1)`;
    el.children[1].style.background = `radial-gradient(circle at ${glowX}% ${glowY}%, rgba(255, 255, 255, 0.5), transparent)`;
  });

  el.addEventListener("mouseleave", () => {
    el.children[0].style.transform = `rotateX(0) rotateY(0) scale(1)`;
    el.children[1].style.transform = `rotateX(0) rotateY(0) scale(1)`;
  });
});
