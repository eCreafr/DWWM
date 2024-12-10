document.addEventListener('DOMContentLoaded', function () {
    const shareButton = document.getElementById('shareButton');
    if (!shareButton) return;

    shareButton.addEventListener('click', async () => {
        const shareData = {
            title: shareButton.dataset.title,
            text: shareButton.dataset.text,
            url: shareButton.dataset.url || window.location.href,
        };

        try {
            if (navigator.share) {
                await navigator.share(shareData);
            } else {
                // Fallback pour desktop
                await navigator.clipboard.writeText(shareData.url);
                showAlert('Lien copié !');
            }
        } catch (err) {
            console.error('Erreur de partage:', err);
        }
    });

    function showAlert(message) {
        const alert = document.getElementById('shareAlert');
        alert.textContent = message;
        alert.style.display = 'block';
        setTimeout(() => (alert.style.display = 'none'), 3000);
    }
});
