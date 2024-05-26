<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Customization</title>
    <link rel="stylesheet" href="./assets/css/edit.css">
    <style>
        .option-button input[type="radio"],
        .option-button input[type="checkbox"] {
            display: none;
        }

        .option-button {
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            margin: 5px;
        }

        .option-button.selected {
            background-color: blue;
            color: white;
            border-color: blue;
        }
    </style>
</head>

<body>
    <?php
    $json = file_get_contents('menu.json');
    $menu = json_decode($json, true);

    $product_id = isset($_GET['id']) ? $_GET['id'] : null;
    $product = null;

    // Search for the product in the menu
    foreach ($menu as $category => $items) {
        foreach ($items as $item) {
            if ($item['id'] == $product_id) {
                $product = $item;
                break 2;
            }
        }
    }

    if (!$product) {
        echo "<p>Product not found.</p>";
        exit;
    }
    ?>

    <div class="customization-container">
        <div class="header">
            <a href="javascript:history.back()" class="back-button">&lt;</a>
            <img src="<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="pizza-image">
            <h2><?= htmlspecialchars($product['name']) ?></h2>
        </div>
        <form id="customization-form">
            <div class="size-selection">
                <?php foreach ($product['sizes'] as $size): ?>
                <label class="option-button">
                    <input type="radio" name="size" value="<?= htmlspecialchars($size['name']) ?>" data-additional-price="<?= htmlspecialchars($size['additional_price']) ?>"> <?= htmlspecialchars($size['name']) ?>
                </label>
                <?php endforeach; ?>
            </div>
            <div class="options-section">
                <h3>Crust</h3>
                <div class="options">
                    <?php foreach ($product['crusts'] as $crust): ?>
                    <label class="option-button">
                        <input type="radio" name="crust" value="<?= htmlspecialchars($crust['name']) ?>" data-additional-price="<?= htmlspecialchars($crust['additional_price']) ?>"> <?= htmlspecialchars($crust['name']) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
                <h3>Sauce</h3>
                <div class="options">
                    <?php foreach ($product['sauces'] as $sauce): ?>
                    <label class="option-button">
                        <input type="radio" name="sauce" value="<?= htmlspecialchars($sauce['name']) ?>" data-additional-price="<?= htmlspecialchars($sauce['additional_price']) ?>"> <?= htmlspecialchars($sauce['name']) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
                <h3>Edit Toppings / Extra</h3>
                <div class="options">
                    <?php foreach ($product['toppings'] as $topping): ?>
                    <label class="option-button">
                        <input type="checkbox" name="toppings[]" value="<?= htmlspecialchars($topping['name']) ?>" data-additional-price="<?= htmlspecialchars($topping['additional_price']) ?>"> <?= htmlspecialchars($topping['name']) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="add-to-cart-section">
                <button type="button" class="add-to-cart-button" onclick="addToCart()">Add to Cart</button>
                <span class="price" id="total-price">$<?= number_format($product['price'], 2) ?></span>
            </div>
        </form>
    </div>

    <script>
        const basePrice = <?= $product['price'] ?>;
        let currentPrice = basePrice;

        function updatePrice() {
            currentPrice = basePrice;

            // Update based on size
            const selectedSize = document.querySelector('input[name="size"]:checked');
            if (selectedSize) {
                currentPrice += parseFloat(selectedSize.dataset.additionalPrice);
            }

            // Update based on crust
            const selectedCrust = document.querySelector('input[name="crust"]:checked');
            if (selectedCrust) {
                currentPrice += parseFloat(selectedCrust.dataset.additionalPrice);
            }

            // Update based on sauce
            const selectedSauce = document.querySelector('input[name="sauce"]:checked');
            if (selectedSauce) {
                currentPrice += parseFloat(selectedSauce.dataset.additionalPrice);
            }

            // Update based on toppings
            const selectedToppings = document.querySelectorAll('input[name="toppings[]"]:checked');
            selectedToppings.forEach(topping => {
                currentPrice += parseFloat(topping.dataset.additionalPrice);
            });

            document.getElementById('total-price').innerText = `$${currentPrice.toFixed(2)}`;
        }

        function addToCart() {
            const selectedSize = document.querySelector('input[name="size"]:checked');
            const selectedCrust = document.querySelector('input[name="crust"]:checked');
            const selectedSauce = document.querySelector('input[name="sauce"]:checked');
            const selectedToppings = Array.from(document.querySelectorAll('input[name="toppings[]"]:checked')).map(topping => topping.value);

            const cartItem = {
                id: <?= $product['id'] ?>,
                name: '<?= htmlspecialchars($product['name']) ?>',
                price: currentPrice,
                quantity: 1,
                image: '<?= $product['image'] ?>',
                size: selectedSize ? selectedSize.value : null,
                crust: selectedCrust ? selectedCrust.value : null,
                sauce: selectedSauce ? selectedSauce.value : null,
                toppings: selectedToppings
            };

            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.push(cartItem);
            localStorage.setItem('cart', JSON.stringify(cart));

            alert('Item added to cart!');
        }

        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('change', function() {
                updatePrice();
                updateSelectedOptions();
            });
        });

        function updateSelectedOptions() {
            document.querySelectorAll('.option-button').forEach(label => {
                label.classList.remove('selected');
                const input = label.querySelector('input');
                if (input.checked) {
                    label.classList.add('selected');
                }
            });
        }

        updatePrice(); // Initialize price on page load
        updateSelectedOptions(); // Initialize selected options on page load
    </script>
</body>

</html>
