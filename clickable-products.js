document.addEventListener("DOMContentLoaded", () => {
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        card.style.cursor = 'pointer';

        card.addEventListener('click', () => {
            const imgElement = card.querySelector('img');
            const nameElement = card.querySelector('h3');
            const priceElement = card.querySelector('p');

            const image = imgElement ? imgElement.src : '';
            const name = nameElement ? nameElement.textContent : '';
            const price = priceElement ? priceElement.textContent : '';

            // drrejtohu te faqja e produktit me parametra query
            const url = `product.html?image=${encodeURIComponent(image)}&name=${encodeURIComponent(name)}&price=${encodeURIComponent(price)}`;
            window.location.href = url;
        });
    });
});