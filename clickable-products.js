document.addEventListener("DOMContentLoaded", () => {
    const productCards = document.querySelectorAll('.product-card');

    productCards.forEach(card => {
        card.style.cursor = 'pointer'; // Show pointer on hover

        card.addEventListener('click', () => {
            const imgElement = card.querySelector('img');
            const nameElement = card.querySelector('h3');
            const priceElement = card.querySelector('p');

            const image = imgElement ? imgElement.src : '';
            const name = nameElement ? nameElement.textContent : '';
            const price = priceElement ? priceElement.textContent : '';

            // Navigate to product detail page with query parameters
            const url = `product.html?image=${encodeURIComponent(image)}&name=${encodeURIComponent(name)}&price=${encodeURIComponent(price)}`;
            window.location.href = url;
        });
    });
});