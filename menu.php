<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Kiosk Menu</title>
    <link rel="stylesheet" href="./assets/css/menu.css">
</head>

<body>
    <?php
    $json = file_get_contents('menu.json');
    $menu = json_decode($json, true);

    $category = isset($_GET['category']) ? $_GET['category'] : 'pizzas';

    function filterMenuItems($menu, $search) {
        $filteredItems = [];
        foreach ($menu as $category => $items) {
            foreach ($items as $item) {
                if (strpos(strtolower($item['name']), $search) !== false ||
                    strpos(strtolower($item['description']), $search) !== false) {
                    $filteredItems[] = $item;
                }
            }
        }
        return $filteredItems;
    }

    $filteredItems = $menu[$category]; // Default display items
    ?>

    <header class="header-container">
        <div class="icon-button">
            <a href="./"> <img src="./assets/image/icons/back.svg" alt="Back Icon"></a>
        </div>
        <a href="./cart.php"><div class="icon-button cart-icon">
            <img src="./assets/image/icons/shopping-cart.png" alt="Cart Icon" style="width:25px;">
            <span class="cart-count" id="cart-count">0</span>
        </div></a>
        
    </header>

    <section class="hero-section">
        <h1 class="hero-title">What would you like to order today</h1>
        <input type="text" id="search-input" class="search-input" placeholder="Search..." oninput="liveSearch()">
    </section>

    <section class="categories-section">
        <div class="category-container">
            <a href="?category=pizzas" class="category-item <?= $category == 'pizzas' ? 'active' : '' ?>">
                <img src="assets/image/icons/pizza.svg" alt="Pizza" class="category-image">
                <h5>Pizza</h5>
            </a>
            <a href="?category=pastas" class="category-item <?= $category == 'pastas' ? 'active' : '' ?>">
                <img src="assets/image/icons/pasta.svg" alt="Pasta" class="category-image">
                <h5>Pasta</h5>
            </a>
            <a href="?category=chickens" class="category-item <?= $category == 'chickens' ? 'active' : '' ?>">
                <img src="assets/image/icons/chicken.svg" alt="Chicken" class="category-image">
                <h5>Chicken</h5>
            </a>
            <a href="?category=breads" class="category-item <?= $category == 'breads' ? 'active' : '' ?>">
                <img src="assets/image/icons/breads.svg" alt="Breads" class="category-image">
                <h5>Breads</h5>
            </a>
            <a href="?category=drinks" class="category-item <?= $category == 'drinks' ? 'active' : '' ?>">
                <img src="assets/image/icons/drinks.svg" alt="Drinks" class="category-image">
                <h5>Drinks</h5>
            </a>
        </div>
        <div class="special-deal-container">
            <div class="special-deal-info">
                <h2>Special Offer for March</h2>
                <p>We are here with the best pizzas in town</p>
                <button class="special-deal-button">Order Now</button>
            </div>
            <img src="./assets/image/pizza.svg" alt="Special Offer Pizza" class="special-deal-image">
        </div>
    </section>

    <section class="menu-container" id="menu-container">
        <?php foreach ($filteredItems as $item) : ?>
            <div class="menu-item">
                <div class="item-header">
                    <img src="<?= $item['image'] ?>" alt="<?= $item['name'] ?>">
                    <button class="favorite-button">❤️</button>
                </div>
                <div class="item-details">
                    <h3><?= $item['name'] ?></h3>
                    <p><?= $item['description'] ?></p>
                    <div class="item-footer">
                        <span class="price">$<?= number_format($item['price'], 2) ?></span>
                        <button class="add-button" onclick="addToCart('<?= $item['name'] ?>', <?= $item['price'] ?>)">+</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </section>

    <script>
        const menuData = <?php echo json_encode($menu); ?>;

        document.addEventListener('DOMContentLoaded', (event) => {
            updateCartCount();
        });

        function liveSearch() {
            const searchInput = document.getElementById('search-input').value.toLowerCase();
            const menuContainer = document.getElementById('menu-container');
            menuContainer.innerHTML = '';

            if (searchInput) {
                const filteredItems = filterMenuItems(menuData, searchInput);
                displayItems(filteredItems);
            } else {
                const category = '<?= $category ?>';
                displayItems(menuData[category]);
            }
        }

        function filterMenuItems(menu, search) {
            const filteredItems = [];
            for (const category in menu) {
                for (const item of menu[category]) {
                    if (item.name.toLowerCase().includes(search) || item.description.toLowerCase().includes(search)) {
                        filteredItems.push(item);
                    }
                }
            }
            return filteredItems;
        }

        function displayItems(items) {
            const menuContainer = document.getElementById('menu-container');
            items.forEach(item => {
                const menuItem = document.createElement('div');
                menuItem.className = 'menu-item';
                menuItem.innerHTML = `
                    <div class="item-header">
                        <img src="${item.image}" alt="${item.name}">
                        <button class="favorite-button">❤️</button>
                    </div>
                    <div class="item-details">
                        <h3>${item.name}</h3>
                        <p>${item.description}</p>
                        <div class="item-footer">
                            <span class="price">$${item.price.toFixed(2)}</span>
                            <button class="add-button" onclick="addToCart('${item.name}', ${item.price})">+</button>
                        </div>
                    </div>
                `;
                menuContainer.appendChild(menuItem);
            });
        }

        function addToCart(name, price) {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            cart.push({ name, price });
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCartCount();
        }

        function updateCartCount() {
            let cart = JSON.parse(localStorage.getItem('cart')) || [];
            document.getElementById('cart-count').innerText = cart.length;
        }
    </script>
</body>

</html>
