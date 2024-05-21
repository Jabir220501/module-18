<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pizza Customization</title>
    <link rel="stylesheet" href="./assets/css/edit.css">
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
        <form action="#" method="post">
            <div class="size-selection">
                <?php foreach ($product['sizes'] as $size): ?>
                <label class="size-button">
                    <input type="radio" name="size" value="<?= htmlspecialchars($size['name']) ?>"> <?= htmlspecialchars($size['name']) ?>
                </label>
                <?php endforeach; ?>
            </div>
            <div class="options-section">
                <h3>Crust</h3>
                <div class="options">
                    <?php foreach ($product['crusts'] as $crust): ?>
                    <label class="option-button">
                        <input type="radio" name="crust" value="<?= htmlspecialchars($crust['name']) ?>"> <?= htmlspecialchars($crust['name']) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
                <h3>Sauce</h3>
                <div class="options">
                    <?php foreach ($product['sauces'] as $sauce): ?>
                    <label class="option-button">
                        <input type="radio" name="sauce" value="<?= htmlspecialchars($sauce['name']) ?>"> <?= htmlspecialchars($sauce['name']) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
                <h3>Edit Toppings / Extra</h3>
                <div class="options">
                    <?php foreach ($product['toppings'] as $topping): ?>
                    <label class="option-button">
                        <input type="checkbox" name="toppings[]" value="<?= htmlspecialchars($topping['name']) ?>"> <?= htmlspecialchars($topping['name']) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="add-to-cart-section">
                <button type="submit" class="add-to-cart-button">Add to Cart</button>
                <span class="price">$<?= number_format($product['price'], 2) ?></span>
            </div>
        </form>
    </div>
</body>

</html>
