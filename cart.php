<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
    <link rel="stylesheet" href="./assets/css/cart.css">
</head>

<body>
    <div class="container">
        <div class="header">
            <button class="back-button">
                <img src="./assets/image/icons/back.svg" alt="Back Icon">
            </button>
        </div>
        <div class="cart-items" id="cart-items">
            <!-- Cart items will be inserted here dynamically -->
        </div>
        <div class="promo-code-container">
            <input type="text" placeholder="Promo Code" class="promo-code-input">
            <button class="apply-button">Apply</button>
        </div>
        <div class="total-container">
            <span>Total</span>
            <span id="total-amount">$0.00</span>
        </div>
        <button class="checkout-button">Checkout</button>
    </div>

    <script>
        function loadCartItems() {
            const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            const cartItemsContainer = document.getElementById('cart-items');
            let totalAmount = 0;

            cartItems.forEach(item => {
                totalAmount += item.price * item.quantity;

                const cartItem = document.createElement('div');
                cartItem.classList.add('cart-item');

                cartItem.innerHTML = `
                    <img src="${item.image}" alt="${item.name}" class="item-image">
                    <div class="item-details">
                        <h2>${item.name}</h2>
                        <a href="#" class="edit-link">Edit</a>
                        <div class="item-price">
                            <span>$${(item.price * item.quantity).toFixed(2)}</span>
                        </div>
                        <div class="quantity-controls">
                            <button class="quantity-button" onclick="updateQuantity(${item.id}, -1)">-</button>
                            <span class="quantity">${item.quantity}</span>
                            <button class="quantity-button" onclick="updateQuantity(${item.id}, 1)">+</button>
                        </div>
                    </div>
                    <button class="remove-button" onclick="removeItem(${item.id})">×</button>
                `;

                cartItemsContainer.appendChild(cartItem);
            });

            document.getElementById('total-amount').innerText = `$${totalAmount.toFixed(2)}`;
        }

        function updateQuantity(id, change) {
            const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            const item = cartItems.find(item => item.id === id);
            
            if (item) {
                item.quantity += change;
                if (item.quantity < 1) item.quantity = 1;

                localStorage.setItem('cart', JSON.stringify(cartItems));
                updateCart();
            }
        }

        function removeItem(id) {
            let cartItems = JSON.parse(localStorage.getItem('cart')) || [];
            cartItems = cartItems.filter(item => item.id !== id);

            localStorage.setItem('cart', JSON.stringify(cartItems));
            updateCart();
        }

        function updateCart() {
            const cartItemsContainer = document.getElementById('cart-items');
            cartItemsContainer.innerHTML = '';
            loadCartItems();
        }

        document.addEventListener('DOMContentLoaded', loadCartItems);
    </script>
</body>

</html>
