// Функции для работы с корзиной через AJAX

function addToCart(productId, button) {
    // Отправка AJAX запроса
    fetch('cart_add.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=1`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Изменение текста кнопки
                const originalText = button.textContent;
                button.textContent = 'Добавлено ✓';
                button.classList.add('added');

                // Возврат к исходному тексту через 1.2 секунды
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove('added');
                }, 1200);

                // Обновление корзины
                updateCartDisplay();
            } else {
                alert(data.error || 'Ошибка при добавлении товара');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при добавлении товара');
        });
}

function removeFromCart(productId) {
    if (!confirm('Удалить товар из корзины?')) {
        return;
    }

    // Отправка AJAX запроса
    fetch('cart_remove.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Перезагрузка страницы для обновления корзины
                location.reload();
            } else {
                alert(data.error || 'Ошибка при удалении товара');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при удалении товара');
        });
}

function updateQuantity(productId, quantity) {
    // Отправка AJAX запроса
    fetch('cart_update.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `product_id=${productId}&quantity=${quantity}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Обновление корзины
                updateCartDisplay();
            } else {
                alert(data.error || 'Ошибка при обновлении корзины');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при обновлении корзины');
        });
}

function updateCartDisplay() {
    // Перезагрузка страницы для обновления корзины
    // В будущем можно заменить на динамическое обновление через AJAX
    location.reload();
}

// Функция для показа деталей товара
function showProductDetails(productId) {
    // Отправка AJAX запроса
    fetch(`product_details.php?product_id=${productId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const product = data.product;

                // Создание модального окна
                const overlay = document.createElement('div');
                overlay.className = 'overlay';
                overlay.style.opacity = '0';
                overlay.style.transition = 'opacity 0.3s ease';

                const popup = document.createElement('div');
                popup.className = 'product-popup';
                popup.style.transform = 'translateY(-50%) translateX(100%)';
                popup.style.transition = 'transform 0.3s ease';

                // Кнопка закрытия
                const closeDiv = document.createElement('div');
                closeDiv.className = 'close-div';
                const closeButton = document.createElement('button');
                closeButton.className = 'close-button';
                const closeImg = document.createElement('img');
                closeImg.src = 'assets/img/close.svg';
                closeImg.className = 'close-img';
                closeButton.appendChild(closeImg);
                closeDiv.appendChild(closeButton);
                popup.appendChild(closeDiv);

                // Тело модального окна
                const body = document.createElement('div');
                body.className = 'product-popup-body';

                // Изображение
                const img = document.createElement('img');
                img.src = product.image_path;
                img.className = 'product-popup-img';
                img.alt = product.name;
                body.appendChild(img);

                // Название
                const name = document.createElement('p');
                name.className = 'product-popup-name';
                name.textContent = product.name;
                body.appendChild(name);

                // Описание
                const desc = document.createElement('p');
                desc.className = 'product-popup-desc';
                desc.textContent = product.description;
                body.appendChild(desc);

                // Цена
                const priceDiv = document.createElement('div');
                priceDiv.style.fontSize = '24px';
                priceDiv.style.fontWeight = '700';
                priceDiv.style.color = '#222';
                priceDiv.style.marginTop = '8px';
                priceDiv.textContent = `${product.price} ₽`;
                body.appendChild(priceDiv);

                // Пищевая ценность
                if (product.kcal || product.protein || product.fat || product.carbo) {
                    const nutritionLabel = document.createElement('p');
                    nutritionLabel.className = 'product-popup-section-label';
                    nutritionLabel.textContent = 'Пищевая ценность на 100г';
                    body.appendChild(nutritionLabel);

                    const nutrition = document.createElement('div');
                    nutrition.className = 'product-popup-nutrition';

                    const nutritionData = [{
                            value: product.kcal || 0,
                            label: 'ккал'
                        },
                        {
                            value: product.protein || 0,
                            label: 'Белки'
                        },
                        {
                            value: product.fat || 0,
                            label: 'Жиры'
                        },
                        {
                            value: product.carbo || 0,
                            label: 'Углеводы'
                        }
                    ];

                    nutritionData.forEach(item => {
                        const cell = document.createElement('div');
                        cell.className = 'nutrition-cell';

                        const value = document.createElement('div');
                        value.className = 'nutrition-value';
                        value.textContent = item.value;

                        const label = document.createElement('div');
                        label.className = 'nutrition-label';
                        label.textContent = item.label;

                        cell.appendChild(value);
                        cell.appendChild(label);
                        nutrition.appendChild(cell);
                    });

                    body.appendChild(nutrition);
                }

                // Дополнительная информация
                if (product.compound) {
                    const compoundRow = document.createElement('div');
                    compoundRow.className = 'product-popup-info-row';

                    const compoundLabel = document.createElement('div');
                    compoundLabel.className = 'product-popup-info-label';
                    compoundLabel.textContent = 'Состав';

                    const compoundValue = document.createElement('div');
                    compoundValue.className = 'product-popup-info-value';
                    compoundValue.textContent = product.compound;

                    compoundRow.appendChild(compoundLabel);
                    compoundRow.appendChild(compoundValue);
                    body.appendChild(compoundRow);
                }

                if (product.expiry) {
                    const expiryRow = document.createElement('div');
                    expiryRow.className = 'product-popup-info-row';

                    const expiryLabel = document.createElement('div');
                    expiryLabel.className = 'product-popup-info-label';
                    expiryLabel.textContent = 'Срок годности';

                    const expiryValue = document.createElement('div');
                    expiryValue.className = 'product-popup-info-value';
                    expiryValue.textContent = product.expiry;

                    expiryRow.appendChild(expiryLabel);
                    expiryRow.appendChild(expiryValue);
                    body.appendChild(expiryRow);
                }

                if (product.producer) {
                    const producerRow = document.createElement('div');
                    producerRow.className = 'product-popup-info-row';

                    const producerLabel = document.createElement('div');
                    producerLabel.className = 'product-popup-info-label';
                    producerLabel.textContent = 'Производитель';

                    const producerValue = document.createElement('div');
                    producerValue.className = 'product-popup-info-value';
                    producerValue.textContent = product.producer;

                    producerRow.appendChild(producerLabel);
                    producerRow.appendChild(producerValue);
                    body.appendChild(producerRow);
                }

                popup.appendChild(body);

                // Кнопка добавления в корзину
                const addBtn = document.createElement('button');
                addBtn.className = 'product-popup-buy-btn';
                addBtn.textContent = 'Добавить в корзину';
                addBtn.onclick = () => {
                    addToCart(product.id, addBtn);
                };
                popup.appendChild(addBtn);

                // Добавление в DOM
                document.body.appendChild(overlay);
                document.body.appendChild(popup);

                // Анимация появления
                requestAnimationFrame(() => {
                    overlay.style.opacity = '1';
                    popup.style.transform = 'translateY(-50%) translateX(-10%)';
                });

                // Закрытие модального окна
                const closePopup = () => {
                    overlay.style.opacity = '0';
                    popup.style.transform = 'translateY(-50%) translateX(100%)';
                    setTimeout(() => {
                        overlay.remove();
                        popup.remove();
                    }, 300);
                };

                overlay.onclick = closePopup;
                closeButton.onclick = closePopup;
            } else {
                alert(data.error || 'Ошибка при загрузке данных товара');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ошибка при загрузке данных товара');
        });
}

// Добавление обработчиков клика на карточки товаров
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up product card listeners');

    const productCards = document.querySelectorAll('.product-card');
    console.log('Found product cards:', productCards.length);

    productCards.forEach(card => {
        // Добавить стиль курсора
        card.style.cursor = 'pointer';

        card.addEventListener('click', function(e) {
            console.log('Card clicked');

            // Игнорировать клик на кнопку добавления в корзину
            if (e.target.closest('.add-to-cart-btn')) {
                console.log('Button clicked, ignoring');
                return;
            }

            const productId = this.dataset.productId;
            console.log('Opening product details for ID:', productId);

            if (productId) {
                showProductDetails(productId);
            }
        });
    });
});