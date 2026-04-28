<aside>
    <ul class="category-list">
        <?php
        $currentCategory = $_GET['category'] ?? null;
        $categories = [
            ['key' => 'Milk', 'label' => CATEGORY_LABELS['Milk'], 'image' => 'assets/img/milk_1.jpg'],
            ['key' => 'Bread', 'label' => CATEGORY_LABELS['Bread'], 'image' => 'assets/img/milk_1.jpg'],
            ['key' => 'Meat', 'label' => CATEGORY_LABELS['Meat'], 'image' => 'assets/img/milk_1.jpg'],
            ['key' => 'Water', 'label' => CATEGORY_LABELS['Water'], 'image' => 'assets/img/milk_1.jpg'],
        ];
        
        foreach ($categories as $cat):
            $isActive = $currentCategory === $cat['key'];
        ?>
            <li class="category-item <?= $isActive ? 'active' : '' ?>" onclick="window.location.href='index.php?category=<?= $cat['key'] ?>'">
                <img src="<?= $cat['image'] ?>" class="category-icon" alt="<?= escape($cat['label']) ?>">
                <span><?= escape($cat['label']) ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
</aside>
