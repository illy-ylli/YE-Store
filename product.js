// Get product info from URL query parameters
const params = new URLSearchParams(window.location.search);
const image = params.get('image');
const name = params.get('name');
const price = params.get('price');

// Set product details in HTML
document.getElementById('productImage').src = image;
document.getElementById('productName').textContent = name;
document.getElementById('productPrice').textContent = price;

// Add to Cart functionality
document.getElementById('addToCart').addEventListener('click', () => {
    const cart = JSON.parse(localStorage.getItem('cart')) || [];

    cart.push({
        name: name,
        price: price,
        image: image
    });

    localStorage.setItem('cart', JSON.stringify(cart));

    alert(`${name} added to cart!`);
});
