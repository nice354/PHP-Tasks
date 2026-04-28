// ===== CITY SELECTION =====
(function() {
    const CITIES = [
        'Москва',
        'Санкт-Петербург',
        'Казань',
        'Новосибирск',
        'Екатеринбург',
        'Нижний Новгород',
        'Самара',
        'Омск',
        'Челябинск',
        'Ростов-на-Дону'
    ];

    let selectedCity = localStorage.getItem('selectedCity') || '';

    // Helper functions
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
            if (cb) cb();
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
        closeImg.src = 'assets/img/close.svg';
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

    function updateCityDisplay() {
        const cityButton = document.querySelector('.city-button');
        const cityBlock = document.querySelector('.city-block');

        if (!cityButton || !cityBlock) return;

        selectedCity = localStorage.getItem('selectedCity') || '';

        if (selectedCity) {
            cityButton.innerHTML = `${selectedCity} <span class="city-arrow">▾</span>`;

            // Remove hint and address button if they exist
            const cityHint = cityBlock.querySelector('.city-hint');
            const addressBtn = cityBlock.querySelector('.address-btn');
            if (cityHint) cityHint.remove();
            if (addressBtn) addressBtn.remove();

            // Add or update delivery note
            let deliveryNote = cityBlock.querySelector('.delivery-note');
            if (!deliveryNote) {
                deliveryNote = document.createElement('p');
                deliveryNote.className = 'delivery-note';
                deliveryNote.innerText = 'Доставка 15 минут';
                cityBlock.appendChild(deliveryNote);
            }
        } else {
            cityButton.innerHTML = `Выбрать город <span class="city-arrow">▾</span>`;

            // Remove delivery note if it exists
            const deliveryNote = cityBlock.querySelector('.delivery-note');
            if (deliveryNote) deliveryNote.remove();

            // Add hint and address button if they don't exist
            if (!cityBlock.querySelector('.city-hint')) {
                const cityHint = document.createElement('p');
                cityHint.className = 'city-hint';
                cityHint.innerText = 'Выберите адрес, и покажем товары и акции, которые точно доступны';
                cityBlock.insertBefore(cityHint, cityButton.nextSibling);

                const addressBtn = document.createElement('button');
                addressBtn.className = 'address-btn';
                addressBtn.innerText = 'Выбрать адрес';
                addressBtn.onclick = () => openCityPopup();
                cityBlock.appendChild(addressBtn);
            }
        }
    }

    function openCityPopup() {
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
                closePopup(popup, overlay, () => {
                    updateCityDisplay();
                });
            };
            cityList.appendChild(li);
        });

        popup.appendChild(cityList);

        const close = () => closePopup(popup, overlay);
        overlay.onclick = close;
        closeButton.onclick = close;

        openPopup(popup, overlay);
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', () => {
        updateCityDisplay();

        // Attach click handler to city button
        const cityButton = document.querySelector('.city-button');
        if (cityButton) {
            cityButton.onclick = () => openCityPopup();
        }

        // Attach click handler to address button if it exists
        const addressBtn = document.querySelector('.address-btn');
        if (addressBtn) {
            addressBtn.onclick = () => openCityPopup();
        }
    });

    // Make function globally available
    window.openCityPopup = openCityPopup;
})();