/* =================================================================
   Quiz DWWM — améliorations UI/UX (GSAP)
   Le PHP reste maître : le JS ne fait qu'embellir.
   ================================================================= */

document.addEventListener('DOMContentLoaded', () => {

    const hasGsap = typeof gsap !== 'undefined';

    /* ---- Accueil : apparition de la carte ---- */
    const carteAccueil = document.querySelector('.carte-accueil');
    if (carteAccueil && hasGsap) {
        gsap.from(carteAccueil, { y: 30, opacity: 0, duration: .6, ease: 'power3.out' });
        gsap.from('.infos-quiz li', { y: 12, opacity: 0, duration: .4, stagger: .1, delay: .3 });
    }

    /* ---- Quiz : énoncé + tuiles en cascade ---- */
    const blocQuestion = document.querySelector('.bloc-question');
    if (blocQuestion && hasGsap) {
        gsap.from(blocQuestion, { y: -20, opacity: 0, duration: .45, ease: 'power2.out' });
        gsap.from('.tuile', { scale: .85, opacity: 0, duration: .35, stagger: .08, delay: .15, ease: 'back.out(1.6)' });
    }

    /* ---- Feedback : verdict + explication ---- */
    const verdict = document.querySelector('.verdict');
    if (verdict && hasGsap) {
        gsap.from(verdict, { scale: .6, opacity: 0, duration: .4, ease: 'back.out(2)' });
    }
    const explication = document.querySelector('.explication');
    if (explication && hasGsap) {
        gsap.from(explication, { y: 20, opacity: 0, duration: .5, delay: .2, ease: 'power2.out' });
    }

    /* ---- Verrou anti double-clic sur les tuiles réponses ---- */
    const grille = document.getElementById('grille-reponses');
    if (grille) {
        // On attend le prochain tick pour désactiver les tuiles afin
        // que la valeur du bouton submit cliqué soit bien envoyée.
        grille.addEventListener('submit', () => {
            setTimeout(() => {
                grille.querySelectorAll('.tuile').forEach(t => {
                    t.disabled = true;
                    t.style.pointerEvents = 'none';
                });
            }, 0);
        });
    }

    /* ---- Résultat : jauge circulaire + compteur animé ---- */
    const cercle = document.querySelector('.score-cercle');
    if (cercle) {
        const score = parseInt(cercle.dataset.score, 10);
        const total = parseInt(cercle.dataset.total, 10);
        const angle = (score / total) * 360;
        const valeur = cercle.querySelector('.score-valeur');

        // Couleur de l'anneau selon le niveau
        const body = document.body;
        let couleur = getComputedStyle(document.documentElement).getPropertyValue('--vert');
        if (body.classList.contains('niveau-faible')) couleur = getComputedStyle(document.documentElement).getPropertyValue('--rouge');
        else if (body.classList.contains('niveau-moyen')) couleur = getComputedStyle(document.documentElement).getPropertyValue('--jaune');
        else if (body.classList.contains('niveau-bien')) couleur = getComputedStyle(document.documentElement).getPropertyValue('--bleu');

        if (hasGsap) {
            const obj = { a: 0, n: 0 };
            gsap.to(obj, {
                a: angle, n: score, duration: 1.4, ease: 'power2.out',
                onUpdate: () => {
                    cercle.style.background =
                        `conic-gradient(${couleur.trim()} ${obj.a}deg, #eee ${obj.a}deg)`;
                    valeur.textContent = Math.round(obj.n);
                }
            });
            gsap.from('.carte-resultat', { y: 30, opacity: 0, duration: .6, ease: 'power3.out' });
        } else {
            cercle.style.background = `conic-gradient(${couleur.trim()} ${angle}deg, #eee ${angle}deg)`;
        }
    }
});
