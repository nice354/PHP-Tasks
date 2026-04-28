"use strict";
(function () {
    // ===== DATA =====
    const DEFAULT_PRODUCTS = [
        { id: 1, name: 'Молоко Домик в деревне, 2,5%, ультрапастеризованное', description: '950 мл', price: 89, category: 'Milk', image: 'img/milk_1.jpg', kcal: 53, protein: 2.9, fat: 2.5, carbo: 4.7, compound: 'Молоко нормализованное', expiry: '12 месяцев', producer: 'АО Вимм-Билль-Данн, Россия', brand: 'Домик в деревне', productType: 'Молоко' },
        { id: 2, name: 'Молоко Домик в деревне, 3,2%', description: '1 л', price: 95, category: 'Milk', image: 'img/milk_1.jpg', kcal: 60, protein: 3.0, fat: 3.2, carbo: 4.7, compound: 'Молоко нормализованное', expiry: '12 месяцев', producer: 'АО Вимм-Билль-Данн, Россия', brand: 'Домик в деревне', productType: 'Молоко' },
        { id: 3, name: 'Яйца куриные С1, 10 шт', description: '10 шт', price: 120, category: 'Milk', image: 'img/milk_1.jpg', kcal: 157, protein: 12.7, fat: 11.5, carbo: 0.7, compound: 'Яйца куриные', expiry: '25 суток', producer: 'Птицефабрика Синявинская', brand: 'Синявинские', productType: 'Яйца' },
        { id: 4, name: 'Сыр Российский, 45%', description: '200 г', price: 210, category: 'Milk', image: 'img/milk_1.jpg', kcal: 364, protein: 23.0, fat: 30.0, carbo: 0.0, compound: 'Молоко, соль, закваска', expiry: '60 суток', producer: 'Россия', brand: 'Российский', productType: 'Сыр' },
        { id: 5, name: 'Хлеб Бородинский', description: '400 г', price: 55, category: 'Bread', image: 'img/milk_1.jpg', kcal: 208, protein: 6.8, fat: 1.3, carbo: 40.7, compound: 'Мука ржаная, солод, кориандр', expiry: '72 часа', producer: 'Хлебозавод №1', brand: 'Бородинский', productType: 'Хлеб' },
        { id: 6, name: 'Батон нарезной', description: '400 г', price: 45, category: 'Bread', image: 'img/milk_1.jpg', kcal: 242, protein: 7.6, fat: 2.9, carbo: 48.3, compound: 'Мука пшеничная, дрожжи, соль', expiry: '48 часов', producer: 'Хлебозавод №1', brand: 'Нарезной', productType: 'Хлеб' },
        { id: 7, name: 'Круассан с шоколадом', description: '80 г', price: 75, category: 'Bread', image: 'img/milk_1.jpg', kcal: 390, protein: 7.0, fat: 20.0, carbo: 46.0, compound: 'Мука, масло, шоколад', expiry: '5 суток', producer: 'Пекарня', brand: 'Французский', productType: 'Выпечка' },
        { id: 8, name: 'Курица охлаждённая', description: '1.5 кг', price: 350, category: 'Meat', image: 'img/milk_1.jpg', kcal: 165, protein: 31.0, fat: 3.6, carbo: 0.0, compound: 'Мясо куриное', expiry: '5 суток', producer: 'Россия', brand: 'Петелинка', productType: 'Птица' },
        { id: 9, name: 'Фарш говяжий', description: '500 г', price: 290, category: 'Meat', image: 'img/milk_1.jpg', kcal: 254, protein: 17.2, fat: 20.0, carbo: 0.0, compound: 'Говядина', expiry: '3 суток', producer: 'Россия', brand: 'Мираторг', productType: 'Говядина' },
        { id: 10, name: 'Вода Святой Источник', description: '1.5 л', price: 65, category: 'Water', image: 'img/milk_1.jpg', kcal: 0, protein: 0, fat: 0, carbo: 0, compound: 'Вода питьевая', expiry: '18 месяцев', producer: 'Россия', brand: 'Святой Источник', productType: 'Вода' },
        { id: 11, name: 'Сок апельсиновый', description: '1 л', price: 130, category: 'Water', image: 'img/milk_1.jpg', kcal: 45, protein: 0.7, fat: 0.2, carbo: 10.4, compound: 'Сок апельсиновый восстановленный', expiry: '12 месяцев', producer: 'Россия', brand: 'Добрый', productType: 'Сок' },
    ];
    const PRODUCTS_VERSION = '2';
    function getProducts() {
        try {
            const version = localStorage.getItem('products_version');
            if (version !== PRODUCTS_VERSION) {
                localStorage.setItem('products', JSON.stringify(DEFAULT_PRODUCTS));
                localStorage.setItem('products_version', PRODUCTS_VERSION);
                return DEFAULT_PRODUCTS;
            }
            const stored = localStorage.getItem('products');
            if (stored)
                return JSON.parse(stored);
        }
        catch (_a) { }
        localStorage.setItem('products', JSON.stringify(DEFAULT_PRODUCTS));
        localStorage.setItem('products_version', PRODUCTS_VERSION);
        return DEFAULT_PRODUCTS;
    }
    function saveProducts(products) {
        localStorage.setItem('products', JSON.stringify(products));
    }
    function getNextProductId() {
        const products = getProducts();
        return products.length > 0 ? Math.max(...products.map(p => p.id)) + 1 : 1;
    }
    const CITIES = ['Москва', 'Санкт-Петербург', 'Казань', 'Новосибирск', 'Екатеринбург', 'Нижний Новгород', 'Самара', 'Омск', 'Челябинск', 'Ростов-на-Дону'];
    const ADMIN_PHONES = ['+7 999 999 99 99', '+7 123 456 78 90', '79999999999', '71234567890'];
    // ===== STATE =====
    let selectedCity = localStorage.getItem('selectedCity') || '';
    let activeCategory = null;
    // ===== CART =====
    function getCart() {
        try {
            return JSON.parse(localStorage.getItem('cart') || '[]');
        }
        catch (_a) {
            return [];
        }
    }
    function saveCart(cart) {
        localStorage.setItem('cart', JSON.stringify(cart));
    }
    function addToCart(product) {
        const cart = getCart();
        const existing = cart.find(i => i.product.id === product.id);
        if (existing) {
            existing.quantity++;
        }
        else {
            cart.push({ product, quantity: 1 });
        }
        saveCart(cart);
        renderSecondAside();
    }
    function removeFromCart(productId) {
        const cart = getCart().filter(i => i.product.id !== productId);
        saveCart(cart);
        renderSecondAside();
    }
    function getCartTotal() {
        return getCart().reduce((sum, i) => sum + i.product.price * i.quantity, 0);
    }
    function getCartCount() {
        return getCart().reduce((sum, i) => sum + i.quantity, 0);
    }
    // ===== HELPERS =====
    function isAdminPhone(phone) {
        const clean = phone.replace(/\D/g, '');
        return ADMIN_PHONES.some(p => p.replace(/\D/g, '') === clean);
    }
    function openPopup(el, overlay) {
        document.body.appendChild(overlay);
        document.body.appendChild(el);
        requestAnimationFrame(() => {
            overlay.style.opacity = '1';
            el.style.transform = 'translateY(-50%) translateX(-10%)';
        });
    }
    function closePopup(el, overlay, cb) {
        overlay.style.opacity = '0';
        el.style.transform = 'translateY(-50%) translateX(100%)';
        setTimeout(() => {
            overlay.remove();
            el.remove();
            cb === null || cb === void 0 ? void 0 : cb();
        }, 300);
    }
    function makeOverlay() {
        const o = document.createElement('div');
        o.className = 'overlay';
        o.style.transition = 'opacity 0.3s ease';
        o.style.opacity = '0';
        return o;
    }
    function makePopupBase(className) {
        const el = document.createElement('div');
        el.className = className;
        el.style.transition = 'transform 0.3s ease';
        el.style.transform = 'translateY(-50%) translateX(100%)';
        return el;
    }
    function makeCloseDiv(title) {
        const closeDiv = document.createElement('div');
        closeDiv.className = 'close-div';
        const closeButton = document.createElement('button');
        closeButton.className = 'close-button';
        const closeImg = document.createElement('img');
        closeImg.src = 'img/close.svg';
        closeImg.className = 'close-img';
        closeButton.appendChild(closeImg);
        if (title) {
            const p = document.createElement('p');
            p.innerText = title;
            closeDiv.appendChild(p);
        }
        closeDiv.appendChild(closeButton);
        return [closeDiv, closeButton];
    }
    // ===== HEADER =====
    const header = document.createElement('header');
    const logoImg = document.createElement('img');
    const searchInput = document.createElement('input');
    const logContainer = document.createElement('div');
    const helpButton = document.createElement('button');
    const logInPhoto = document.createElement('img');
    const helpPhoto = document.createElement('img');
    let logButton = null;
    logoImg.src = 'img/Logo.svg';
    logoImg.className = 'logo-svg';
    const logoWrap = document.createElement('div');
    logoWrap.className = 'logo-wrap';
    logoWrap.appendChild(logoImg);
    searchInput.type = 'text';
    searchInput.className = 'search';
    searchInput.placeholder = 'Искать в Везет';
    logContainer.className = 'log-container';
    helpButton.className = 'help-button';
    logInPhoto.src = 'img/login-user-photo.svg';
    logInPhoto.className = 'log-in-photo';
    helpPhoto.src = 'img/help.svg';
    helpPhoto.className = 'help-photo';
    document.body.append(header);
    header.appendChild(logoWrap);
    header.appendChild(searchInput);
    header.appendChild(logContainer);
    function RenderLogIn(userName) {
        if (logButton) {
            logButton.innerHTML = '';
            logButton.appendChild(logInPhoto.cloneNode(true));
            logButton.appendChild(document.createTextNode(userName ? ` ${userName}` : ' Войти'));
        }
        else {
            logButton = document.createElement('button');
            logButton.className = 'log-button';
            logButton.appendChild(logInPhoto.cloneNode(true));
            logButton.appendChild(document.createTextNode(userName ? ` ${userName}` : ' Войти'));
            logContainer.appendChild(logButton);
            logButton.onclick = () => LogInForm();
        }
    }
    RenderLogIn();
    logContainer.appendChild(helpButton);
    helpButton.appendChild(helpPhoto);
    helpButton.onclick = () => HelpWindow();
    // ===== ASIDE (categories) =====
    const aside = document.createElement('aside');
    document.body.append(aside);
    const categories = [
        { key: 'Milk', label: 'Молоко, яйца и сыр', image: 'img/milk_1.jpg' },
        { key: 'Bread', label: 'Хлеб и выпечка', image: 'img/milk_1.jpg' },
        { key: 'Meat', label: 'Мясо и рыба', image: 'img/milk_1.jpg' },
        { key: 'Water', label: 'Вода и напитки', image: 'img/milk_1.jpg' },
    ];
    function renderAside() {
        aside.innerHTML = '';
        const catList = document.createElement('ul');
        catList.className = 'category-list';
        categories.forEach(cat => {
            const li = document.createElement('li');
            li.className = 'category-item' + (activeCategory === cat.key ? ' active' : '');
            const icon = document.createElement('img');
            icon.className = 'category-icon';
            icon.src = cat.image;
            icon.alt = cat.label;
            const label = document.createElement('span');
            label.innerText = cat.label;
            li.appendChild(icon);
            li.appendChild(label);
            li.onclick = () => {
                activeCategory = activeCategory === cat.key ? null : cat.key;
                renderAside();
                renderMain();
            };
            catList.appendChild(li);
        });
        aside.appendChild(catList);
    }
    renderAside();
    // ===== MAIN =====
    const main = document.createElement('main');
    document.body.append(main);
    function renderMain() {
        var _a;
        main.innerHTML = '';
        const allProducts = getProducts();
        const filtered = activeCategory
            ? allProducts.filter(p => p.category === activeCategory)
            : allProducts;
        if (activeCategory) {
            const breadcrumb = document.createElement('p');
            breadcrumb.className = 'breadcrumb';
            breadcrumb.innerText = 'Главная';
            main.appendChild(breadcrumb);
            const catLabel = ((_a = categories.find(c => c.key === activeCategory)) === null || _a === void 0 ? void 0 : _a.label) || '';
            const catTitle = document.createElement('h1');
            catTitle.className = 'main-title';
            catTitle.innerText = catLabel;
            main.appendChild(catTitle);
        }
        else {
            const banner = document.createElement('h1');
            banner.className = 'main-banner';
            banner.innerText = 'Доставка до 30 минут!';
            main.appendChild(banner);
            const popularTitle = document.createElement('h2');
            popularTitle.className = 'main-subtitle';
            popularTitle.innerText = 'Популярное';
            main.appendChild(popularTitle);
        }
        const grid = document.createElement('div');
        grid.className = 'products-grid';
        filtered.forEach(product => {
            const card = document.createElement('div');
            card.className = 'product-card';
            const imgWrap = document.createElement('div');
            imgWrap.className = 'product-img-wrap';
            const img = document.createElement('img');
            img.src = product.image;
            img.className = 'product-img';
            img.alt = product.name;
            imgWrap.appendChild(img);
            const name = document.createElement('p');
            name.className = 'product-name';
            name.innerText = product.name;
            const price = document.createElement('p');
            price.className = 'product-price';
            price.innerText = `${product.price} ₽`;
            const addBtn = document.createElement('button');
            addBtn.className = 'add-to-cart-btn';
            addBtn.innerText = 'Добавить в корзину';
            addBtn.onclick = () => {
                addToCart(product);
                addBtn.innerText = 'Добавлено ✓';
                addBtn.classList.add('added');
                setTimeout(() => {
                    addBtn.innerText = 'Добавить в корзину';
                    addBtn.classList.remove('added');
                }, 1200);
            };
            card.appendChild(imgWrap);
            card.appendChild(name);
            card.appendChild(price);
            card.appendChild(addBtn);
            card.style.cursor = 'pointer';
            card.onclick = (e) => {
                if (e.target.closest('.add-to-cart-btn'))
                    return;
                ProductPopup(product);
            };
            grid.appendChild(card);
        });
        main.appendChild(grid);
        renderFooter();
    }
    renderMain();
    // Redirect wheel scroll from anywhere on page into main
    document.addEventListener('wheel', (e) => {
        const target = e.target;
        if (target.closest('.log-form, .help-window, .city-popup, .second-aside'))
            return;
        e.preventDefault();
        main.scrollTop += e.deltaY;
    }, { passive: false });
    // ===== SECOND ASIDE (cart + city) =====
    const secondAside = document.createElement('div');
    secondAside.className = 'second-aside';
    document.body.append(secondAside);
    function renderSecondAside() {
        secondAside.innerHTML = '';
        // City block
        const cityBlock = document.createElement('div');
        cityBlock.className = 'city-block';
        const cityBtn = document.createElement('button');
        cityBtn.className = 'city-button';
        cityBtn.innerHTML = `${selectedCity || 'Выбрать город'} <span class="city-arrow">▾</span>`;
        cityBtn.onclick = () => CityPopup();
        if (!selectedCity) {
            const cityHint = document.createElement('p');
            cityHint.className = 'city-hint';
            cityHint.innerText = 'Выберите адрес, и покажем товары и акции, которые точно доступны';
            cityBlock.appendChild(cityBtn);
            cityBlock.appendChild(cityHint);
            const addressBtn = document.createElement('button');
            addressBtn.className = 'address-btn';
            addressBtn.innerText = 'Выбрать адрес';
            addressBtn.onclick = () => CityPopup();
            cityBlock.appendChild(addressBtn);
        }
        else {
            cityBlock.appendChild(cityBtn);
            const deliveryNote = document.createElement('p');
            deliveryNote.className = 'delivery-note';
            deliveryNote.innerText = 'Доставка 15 минут';
            cityBlock.appendChild(deliveryNote);
        }
        secondAside.appendChild(cityBlock);
        // Cart block
        const cart = getCart();
        const cartBlock = document.createElement('div');
        cartBlock.className = 'cart-block';
        if (cart.length === 0) {
            const emptyMsg = document.createElement('p');
            emptyMsg.className = 'cart-empty';
            emptyMsg.innerText = 'Добавьте товары в корзину и мы доставим их!';
            cartBlock.appendChild(emptyMsg);
        }
        else {
            const cartTitle = document.createElement('p');
            cartTitle.className = 'cart-title';
            cartTitle.innerText = 'Корзина';
            cartBlock.appendChild(cartTitle);
            cart.forEach(item => {
                const row = document.createElement('div');
                row.className = 'cart-row';
                const rowName = document.createElement('span');
                rowName.className = 'cart-row-name';
                rowName.innerText = item.product.name;
                const rowQty = document.createElement('span');
                rowQty.className = 'cart-row-qty';
                rowQty.innerText = `× ${item.quantity}`;
                const rowPrice = document.createElement('span');
                rowPrice.className = 'cart-row-price';
                rowPrice.innerText = `${item.product.price * item.quantity} ₽`;
                const removeBtn = document.createElement('button');
                removeBtn.className = 'cart-remove-btn';
                removeBtn.innerText = '✕';
                removeBtn.onclick = () => removeFromCart(item.product.id);
                row.appendChild(rowName);
                row.appendChild(rowQty);
                row.appendChild(rowPrice);
                row.appendChild(removeBtn);
                cartBlock.appendChild(row);
            });
            const orderBtn = document.createElement('button');
            orderBtn.className = 'order-btn';
            orderBtn.innerText = `Заказ от ${getCartTotal()} ₽`;
            cartBlock.appendChild(orderBtn);
        }
        secondAside.appendChild(cartBlock);
    }
    renderSecondAside();
    // ===== FOOTER =====
    function renderFooter() {
        const existing = main.querySelector('footer');
        if (existing)
            existing.remove();
        const footer = document.createElement('footer');
        const footerStores = document.createElement('div');
        footerStores.className = 'footer-stores';
        const storeButtons = [
            { src: 'img/Apple.svg', alt: 'App Store' },
            { src: 'img/Play.svg', alt: 'Google Play' },
            { src: 'img/Ru.svg', alt: 'RuStore' },
            { src: 'img/Huaw.svg', alt: 'AppGallery' },
        ];
        storeButtons.forEach(({ src, alt }) => {
            const btn = document.createElement('button');
            btn.className = 'footer-store-btn';
            const img = document.createElement('img');
            img.src = src;
            img.alt = alt;
            btn.appendChild(img);
            footerStores.appendChild(btn);
        });
        footer.appendChild(footerStores);
        const footerLinks = document.createElement('div');
        footerLinks.className = 'footer-links';
        const links = [
            'Ответы на вопросы', 'Для поставщиков', 'Деловая этика и противодействие коррупции',
            'Контакты', 'Умный импорт', 'Правила и соглашения',
            'Работа в Везет', 'Стать курьером-партнёром', 'Политика конфиденциальности',
        ];
        links.forEach(text => {
            const a = document.createElement('button');
            a.className = 'footer-link';
            a.innerText = text;
            footerLinks.appendChild(a);
        });
        footer.appendChild(footerLinks);
        const footerLegal = document.createElement('p');
        footerLegal.className = 'footer-legal';
        footerLegal.innerText = 'Зона, время, товары и предложения доставки ограничены. Организатор, продавец: ООО «Умный ритейл» ОГРН 1177847261802, 121087 Москва, вн.г.тер. муниципальный округ Филёвский парк, ул. Барклая, д. 8, стр. 3, помещ. 8/28.';
        footer.appendChild(footerLegal);
        main.appendChild(footer);
    }
    // ===== CITY POPUP =====
    function CityPopup() {
        const existing = document.querySelector('.city-popup');
        const existingOverlay = document.querySelector('.overlay');
        if (existing && existingOverlay) {
            closePopup(existing, existingOverlay);
            return;
        }
        const popup = makePopupBase('city-popup');
        const overlay = makeOverlay();
        const [closeDiv, closeButton] = makeCloseDiv('Выберите город');
        popup.appendChild(closeDiv);
        const cityList = document.createElement('ul');
        cityList.className = 'city-list';
        CITIES.forEach(city => {
            const li = document.createElement('li');
            li.className = 'city-list-item';
            li.innerText = city;
            li.onclick = () => {
                selectedCity = city;
                localStorage.setItem('selectedCity', city);
                closePopup(popup, overlay, () => renderSecondAside());
            };
            cityList.appendChild(li);
        });
        popup.appendChild(cityList);
        const close = () => closePopup(popup, overlay);
        overlay.onclick = close;
        closeButton.onclick = close;
        openPopup(popup, overlay);
    }
    // ===== LOGIN FORM =====
    function LogInForm() {
        const existing = document.querySelector('.log-form');
        const existingOverlay = document.querySelector('.overlay');
        if (existing && existingOverlay) {
            closePopup(existing, existingOverlay, () => createLogInForm());
            return;
        }
        createLogInForm();
    }
    function createLogInForm() {
        const form = makePopupBase('log-form');
        const overlay = makeOverlay();
        const [closeDiv, closeButton] = makeCloseDiv();
        form.appendChild(closeDiv);
        const btnContainer = document.createElement('div');
        btnContainer.className = 'button-contanier';
        const maxImg = document.createElement('img');
        maxImg.src = 'img/max-reg.svg';
        const maxBtn = document.createElement('button');
        maxBtn.className = 'max-button';
        maxBtn.innerText = 'Войти с MAX';
        const phoneBtn = document.createElement('button');
        phoneBtn.className = 'phone-button';
        phoneBtn.innerText = 'Войти по номеру телефона';
        phoneBtn.onclick = () => PhoneLogIn();
        btnContainer.appendChild(maxImg);
        btnContainer.appendChild(maxBtn);
        btnContainer.appendChild(phoneBtn);
        form.appendChild(btnContainer);
        const divContainer = document.createElement('div');
        divContainer.className = 'div-container';
        const salesDiv = document.createElement('div');
        salesDiv.className = 'sales-div';
        const salesCheck = document.createElement('input');
        salesCheck.type = 'checkbox';
        salesCheck.id = 'salescheck';
        const salesLabel = document.createElement('label');
        salesLabel.htmlFor = 'salescheck';
        salesLabel.innerText = 'Получать предложения об акциях и скидках';
        salesDiv.appendChild(salesCheck);
        salesDiv.appendChild(salesLabel);
        const dataDiv = document.createElement('div');
        dataDiv.className = 'data-div';
        const dataCheck = document.createElement('input');
        dataCheck.type = 'checkbox';
        dataCheck.id = 'datashare';
        const dataLabel = document.createElement('label');
        dataLabel.htmlFor = 'datashare';
        dataLabel.innerText = 'Делиться данными с партнёрами Везет';
        dataDiv.appendChild(dataCheck);
        dataDiv.appendChild(dataLabel);
        const privacy = document.createElement('p');
        privacy.innerText = 'Продолжая авторизацию, вы соглашаетесь с политикой конфиденциальности, условиями сервиса и условиями продажи товаров';
        divContainer.appendChild(salesDiv);
        divContainer.appendChild(dataDiv);
        divContainer.appendChild(privacy);
        form.appendChild(divContainer);
        const close = () => closePopup(form, overlay);
        overlay.onclick = close;
        closeButton.onclick = close;
        openPopup(form, overlay);
    }
    // ===== PHONE LOGIN =====
    function PhoneLogIn() {
        const current = document.querySelector('.log-form');
        const currentOverlay = document.querySelector('.overlay');
        if (current && currentOverlay) {
            closePopup(current, currentOverlay, () => createPhoneLogInForm());
        }
        else {
            createPhoneLogInForm();
        }
    }
    function createPhoneLogInForm() {
        const form = makePopupBase('log-form');
        const overlay = makeOverlay();
        const [closeDiv, closeButton] = makeCloseDiv();
        form.appendChild(closeDiv);
        const phoneContainer = document.createElement('div');
        phoneContainer.className = 'phone-input-container';
        const phoneInput = document.createElement('input');
        phoneInput.type = 'tel';
        phoneInput.className = 'phone-input';
        phoneInput.placeholder = '+7 123 456 78 90';
        const phoneBtn = document.createElement('button');
        phoneBtn.className = 'phone-login-button';
        phoneBtn.innerText = 'Получить код';
        phoneContainer.appendChild(phoneInput);
        phoneContainer.appendChild(phoneBtn);
        form.appendChild(phoneContainer);
        const divContainer = document.createElement('div');
        divContainer.className = 'div-container-phone';
        const privacy = document.createElement('p');
        privacy.className = 'privacy-policy';
        privacy.innerText = 'Продолжая авторизацию, вы соглашаетесь с политикой конфиденциальности, условиями сервиса и условиями продажи товаров';
        divContainer.appendChild(privacy);
        form.appendChild(divContainer);
        function formatPhone(input) {
            let n = input.replace(/\D/g, '');
            if (n.startsWith('8'))
                n = '7' + n.slice(1);
            n = n.slice(0, 11);
            if (!n.length)
                return '';
            let f = '+7';
            if (n.length > 1) {
                const r = n.slice(1);
                if (r.length > 0)
                    f += ' ' + r.slice(0, 3);
                if (r.length > 3)
                    f += ' ' + r.slice(3, 6);
                if (r.length > 6)
                    f += ' ' + r.slice(6, 8);
                if (r.length > 8)
                    f += ' ' + r.slice(8, 10);
            }
            return f.trim();
        }
        phoneInput.addEventListener('input', (e) => {
            const t = e.target;
            const pos = t.selectionStart || 0;
            const old = t.value;
            const newVal = formatPhone(t.value.replace(/\D/g, ''));
            if (newVal === old)
                return;
            t.value = newVal;
            const diff = newVal.length - old.length;
            t.setSelectionRange(pos + diff, pos + diff);
        });
        phoneBtn.onclick = () => {
            closePopup(form, overlay, () => {
                if (isAdminPhone(phoneInput.value)) {
                    RenderAdminPage();
                }
                else {
                    const name = prompt('Введите ваше имя:', 'Пользователь');
                    RenderLogIn((name === null || name === void 0 ? void 0 : name.trim()) || undefined);
                }
            });
        };
        const close = () => closePopup(form, overlay);
        overlay.onclick = close;
        closeButton.onclick = close;
        openPopup(form, overlay);
    }
    // ===== HELP WINDOW =====
    function HelpWindow() {
        const existing = document.querySelector('.help-window');
        const existingOverlay = document.querySelector('.overlay');
        if (existing && existingOverlay) {
            closePopup(existing, existingOverlay, () => createHelpWindow());
            return;
        }
        createHelpWindow();
    }
    function createHelpWindow() {
        const win = makePopupBase('help-window');
        const overlay = makeOverlay();
        const [closeDiv, closeButton] = makeCloseDiv('Чат Везёт');
        win.appendChild(closeDiv);
        const mainHelp = document.createElement('div');
        mainHelp.className = 'main-help';
        const helpImg = document.createElement('img');
        helpImg.src = 'img/error.svg';
        helpImg.className = 'help-img';
        mainHelp.appendChild(helpImg);
        win.appendChild(mainHelp);
        const close = () => closePopup(win, overlay);
        overlay.onclick = close;
        closeButton.onclick = close;
        openPopup(win, overlay);
    }
    // ===== PRODUCT POPUP =====
    function ProductPopup(product) {
        const existing = document.querySelector('.product-popup');
        const existingOverlay = document.querySelector('.overlay');
        if (existing && existingOverlay) {
            closePopup(existing, existingOverlay, () => createProductPopup(product));
            return;
        }
        createProductPopup(product);
    }
    function createProductPopup(product) {
        const popup = makePopupBase('product-popup');
        const overlay = makeOverlay();
        const [closeDiv, closeButton] = makeCloseDiv();
        popup.appendChild(closeDiv);
        const body = document.createElement('div');
        body.className = 'product-popup-body';
        // Image
        const img = document.createElement('img');
        img.src = product.image;
        img.className = 'product-popup-img';
        img.alt = product.name;
        body.appendChild(img);
        // Name + description
        const nameEl = document.createElement('h2');
        nameEl.className = 'product-popup-name';
        nameEl.innerText = product.name;
        body.appendChild(nameEl);
        const descEl = document.createElement('p');
        descEl.className = 'product-popup-desc';
        descEl.innerText = product.description;
        body.appendChild(descEl);
        // Nutrition (per 100g)
        if (product.kcal !== undefined) {
            const nutTitle = document.createElement('p');
            nutTitle.className = 'product-popup-section-label';
            nutTitle.innerText = 'В 100 граммах';
            body.appendChild(nutTitle);
            const nutGrid = document.createElement('div');
            nutGrid.className = 'product-popup-nutrition';
            const nutrients = [
                { value: product.kcal, label: 'Ккал' },
                { value: product.protein, label: 'Белки' },
                { value: product.fat, label: 'Жиры' },
                { value: product.carbo, label: 'Углеводы' },
            ];
            nutrients.forEach(n => {
                var _a;
                const cell = document.createElement('div');
                cell.className = 'nutrition-cell';
                const val = document.createElement('span');
                val.className = 'nutrition-value';
                val.innerText = `${(_a = n.value) !== null && _a !== void 0 ? _a : '—'}`;
                const lbl = document.createElement('span');
                lbl.className = 'nutrition-label';
                lbl.innerText = n.label;
                cell.appendChild(val);
                cell.appendChild(lbl);
                nutGrid.appendChild(cell);
            });
            body.appendChild(nutGrid);
        }
        // Info rows
        const infoRows = [
            ['Состав', product.compound],
            ['Срок хранения', product.expiry],
            ['Производитель', product.producer],
            ['Бренд', product.brand],
            ['Тип товара', product.productType],
        ];
        infoRows.forEach(([label, value]) => {
            if (!value)
                return;
            const row = document.createElement('div');
            row.className = 'product-popup-info-row';
            const lbl = document.createElement('p');
            lbl.className = 'product-popup-info-label';
            lbl.innerText = label;
            const val = document.createElement('p');
            val.className = 'product-popup-info-value';
            val.innerText = value;
            row.appendChild(lbl);
            row.appendChild(val);
            body.appendChild(row);
        });
        popup.appendChild(body);
        // Bottom buy button
        const buyBtn = document.createElement('button');
        buyBtn.className = 'product-popup-buy-btn';
        buyBtn.innerHTML = `${product.price} ₽ &nbsp;+`;
        buyBtn.onclick = () => {
            addToCart(product);
            buyBtn.innerText = 'Добавлено ✓';
            setTimeout(() => { buyBtn.innerHTML = `${product.price} ₽ &nbsp;+`; }, 1200);
        };
        popup.appendChild(buyBtn);
        const close = () => closePopup(popup, overlay);
        overlay.onclick = close;
        closeButton.onclick = close;
        openPopup(popup, overlay);
    }
    // ===== ADMIN PAGE =====
    function RenderAdminPage() {
        document.body.innerHTML = '';
        const logoutBtn = document.createElement('button');
        logoutBtn.className = 'log-out-button';
        logoutBtn.innerText = 'Выйти';
        logoutBtn.onclick = () => location.reload();
        document.body.appendChild(logoutBtn);
        const allContent = document.createElement('div');
        allContent.className = 'all-admin-content';
        document.body.appendChild(allContent);
        // --- ADD CONTAINER ---
        const h1Add = document.createElement('h1');
        h1Add.innerText = 'Добавить товар';
        const addContainer = document.createElement('div');
        addContainer.className = 'add-admin-container';
        const inputName = document.createElement('input');
        inputName.type = 'text';
        inputName.placeholder = 'Название';
        inputName.className = 'input-name';
        const inputVolume = document.createElement('input');
        inputVolume.type = 'text';
        inputVolume.placeholder = 'Объём';
        inputVolume.className = 'input-value';
        const inputPrice = document.createElement('input');
        inputPrice.type = 'number';
        inputPrice.placeholder = 'Цена (₽)';
        inputPrice.className = 'input-price';
        const divKcal = document.createElement('div');
        divKcal.className = 'div-kcal-carbo';
        const inputKcal = document.createElement('input');
        inputKcal.type = 'number';
        inputKcal.placeholder = 'Ккал';
        inputKcal.className = 'input-kcal';
        const inputCarbo = document.createElement('input');
        inputCarbo.type = 'number';
        inputCarbo.placeholder = 'Углеводы';
        inputCarbo.className = 'input-carbo';
        divKcal.appendChild(inputKcal);
        divKcal.appendChild(inputCarbo);
        const textarea = document.createElement('textarea');
        textarea.placeholder = 'Описание';
        textarea.className = 'input-desc';
        const inputComp = document.createElement('input');
        inputComp.type = 'text';
        inputComp.placeholder = 'Состав';
        inputComp.className = 'input-compound';
        const divDataType = document.createElement('div');
        divDataType.className = 'div-data-type';
        const inputDate = document.createElement('input');
        inputDate.type = 'date';
        inputDate.className = 'input-data';
        const select = document.createElement('select');
        select.className = 'select-type';
        ['Молоко, яйца, сыр|Milk', 'Хлеб и выпечка|Bread', 'Мясо и рыба|Meat', 'Вода и напитки|Water'].forEach(s => {
            const [lbl, val] = s.split('|');
            select.add(new Option(lbl, val));
        });
        divDataType.appendChild(inputDate);
        divDataType.appendChild(select);
        const inputProducer = document.createElement('input');
        inputProducer.type = 'text';
        inputProducer.placeholder = 'Производитель';
        const inputBrand = document.createElement('input');
        inputBrand.type = 'text';
        inputBrand.placeholder = 'Бренд';
        const inputImg = document.createElement('input');
        inputImg.type = 'file';
        inputImg.accept = 'image/*';
        inputImg.className = 'input-img';
        const addFeedback = document.createElement('p');
        addFeedback.className = 'admin-feedback';
        const submitBtn = document.createElement('button');
        submitBtn.className = 'submit-button';
        submitBtn.innerText = 'Добавить';
        submitBtn.onclick = () => {
            var _a;
            const name = inputName.value.trim();
            const volume = inputVolume.value.trim();
            const price = parseFloat(inputPrice.value);
            const category = select.value;
            if (!name) {
                showFeedback(addFeedback, 'Введите название', true);
                return;
            }
            if (!volume) {
                showFeedback(addFeedback, 'Введите объём', true);
                return;
            }
            if (!price || price <= 0) {
                showFeedback(addFeedback, 'Введите корректную цену', true);
                return;
            }
            let imageData = 'img/error.svg';
            const file = (_a = inputImg.files) === null || _a === void 0 ? void 0 : _a[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    var _a;
                    imageData = (_a = e.target) === null || _a === void 0 ? void 0 : _a.result;
                    doAdd(name, volume, price, category, imageData);
                };
                reader.readAsDataURL(file);
            }
            else {
                doAdd(name, volume, price, category, imageData);
            }
            function doAdd(n, v, p, c, img) {
                const newProduct = {
                    id: getNextProductId(),
                    name: n,
                    description: v,
                    price: p,
                    category: c,
                    image: img,
                    kcal: parseFloat(inputKcal.value) || undefined,
                    carbo: parseFloat(inputCarbo.value) || undefined,
                    compound: inputComp.value.trim() || undefined,
                    expiry: inputDate.value || undefined,
                    producer: inputProducer.value.trim() || undefined,
                    brand: inputBrand.value.trim() || undefined,
                    productType: select.value || undefined,
                };
                const products = getProducts();
                products.push(newProduct);
                saveProducts(products);
                showFeedback(addFeedback, `Товар "${n}" добавлен`, false);
                inputName.value = '';
                inputVolume.value = '';
                inputPrice.value = '';
                inputKcal.value = '';
                inputCarbo.value = '';
                textarea.value = '';
                inputComp.value = '';
                inputProducer.value = '';
                inputBrand.value = '';
                inputImg.value = '';
            }
        };
        addContainer.appendChild(inputName);
        addContainer.appendChild(inputVolume);
        addContainer.appendChild(inputPrice);
        addContainer.appendChild(divKcal);
        addContainer.appendChild(textarea);
        addContainer.appendChild(inputComp);
        addContainer.appendChild(divDataType);
        addContainer.appendChild(inputProducer);
        addContainer.appendChild(inputBrand);
        addContainer.appendChild(inputImg);
        addContainer.appendChild(addFeedback);
        addContainer.appendChild(submitBtn);
        // --- DELETE CONTAINER ---
        const h1Del = document.createElement('h1');
        h1Del.innerText = 'Удалить товар';
        const delContainer = document.createElement('div');
        delContainer.className = 'del-admin-container';
        const delInputName = document.createElement('input');
        delInputName.type = 'text';
        delInputName.placeholder = 'Название';
        const delInputProducer = document.createElement('input');
        delInputProducer.type = 'text';
        delInputProducer.placeholder = 'Производитель';
        const delInputBrand = document.createElement('input');
        delInputBrand.type = 'text';
        delInputBrand.placeholder = 'Бренд';
        const delInputVolume = document.createElement('input');
        delInputVolume.type = 'text';
        delInputVolume.placeholder = 'Объём';
        const delFeedback = document.createElement('p');
        delFeedback.className = 'admin-feedback';
        const delBtn = document.createElement('button');
        delBtn.className = 'delete-button';
        delBtn.innerText = 'Удалить';
        delBtn.onclick = () => {
            const name = delInputName.value.trim().toLowerCase();
            const volume = delInputVolume.value.trim().toLowerCase();
            if (!name) {
                showFeedback(delFeedback, 'Введите название товара', true);
                return;
            }
            const products = getProducts();
            const idx = products.findIndex(p => {
                const nameMatch = p.name.toLowerCase().includes(name);
                const volMatch = !volume || p.description.toLowerCase().includes(volume);
                return nameMatch && volMatch;
            });
            if (idx === -1) {
                showFeedback(delFeedback, 'Товар не найден', true);
            }
            else {
                const removed = products[idx];
                products.splice(idx, 1);
                saveProducts(products);
                showFeedback(delFeedback, `Товар "${removed.name}" удалён`, false);
                delInputName.value = '';
                delInputProducer.value = '';
                delInputBrand.value = '';
                delInputVolume.value = '';
            }
        };
        delContainer.appendChild(delInputName);
        delContainer.appendChild(delInputProducer);
        delContainer.appendChild(delInputBrand);
        delContainer.appendChild(delInputVolume);
        delContainer.appendChild(delFeedback);
        delContainer.appendChild(delBtn);
        allContent.appendChild(h1Add);
        allContent.appendChild(h1Del);
        allContent.appendChild(addContainer);
        allContent.appendChild(delContainer);
    }
    function showFeedback(el, msg, isError) {
        el.innerText = msg;
        el.className = 'admin-feedback' + (isError ? ' error' : '');
        setTimeout(() => { el.innerText = ''; }, 3000);
    }
})();
