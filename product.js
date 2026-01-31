// merri detajet e produktit prej parametrav t'URL query
const params = new URLSearchParams(window.location.search);
const image = params.get('image');
const name = params.get('name');
const price = params.get('price');

// futi detajet e produktit nhtml
document.getElementById('productImage').src = image;
document.getElementById('productName').textContent = name;
document.getElementById('productPrice').textContent = price;

// me qit nshport
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
