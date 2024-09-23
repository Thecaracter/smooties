let selectedVariant = {
    jenis: '',
    harga: 0
};

function selectVariant(button, menuId, menuNama, varianJenis, varianHarga) {
    selectedVariant = {
        jenis: varianJenis,
        harga: varianHarga
    };

    // Hapus kelas 'active' dari semua tombol dalam modal yang sama
    button.closest('.varian-options').querySelectorAll('.varian-btn').forEach(btn => {
        btn.classList.remove('active');
    });

    // Tambahkan kelas 'active' ke tombol yang dipilih
    button.classList.add('active');
}

function addToCart(menuId, menuNama) {
    if (selectedVariant.jenis === '') {
        alert('Silakan pilih varian terlebih dahulu!');
        return;
    }

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let item = cart.find(i => i.id === menuId && i.varian === selectedVariant.jenis);

    if (item) {
        item.quantity += 1;
    } else {
        cart.push({
            id: menuId,
            nama: menuNama,
            varian: selectedVariant.jenis,
            harga: selectedVariant.harga,
            quantity: 1
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    alert('Item berhasil ditambahkan ke keranjang!');
    updateCartBadge();

    selectedVariant = {
        jenis: '',
        harga: 0
    };
}

function updateCartBadge() {
    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    let cartBadge = document.getElementById('cartBadge');
    if (cartBadge) {
        cartBadge.textContent = totalItems;
    }
}

function updateMenu(updatedMenu) {
    const menuItem = document.querySelector(`.menu-item[data-id="${updatedMenu.id}"]`);
    if (menuItem) {
        // Update nama menu
        menuItem.querySelector('.card-title').textContent = updatedMenu.nama;

        // Update kategori
        menuItem.querySelector('.card-text').textContent = updatedMenu.kategori.nama;

        // Update gambar
        menuItem.querySelector('.card-img-top').src = `/fotoMenu/${updatedMenu.foto}`;

        // Update data atribut
        menuItem.dataset.name = updatedMenu.nama.toLowerCase();
        menuItem.dataset.category = updatedMenu.kategori.nama.toLowerCase();

        // Update modal
        updateMenuModal(updatedMenu);

        // Re-run filter untuk memastikan item tetap terlihat jika sesuai dengan filter saat ini
        filterMenuItems();
    }
}

function updateMenuModal(updatedMenu) {
    const modal = document.querySelector(`#modal${updatedMenu.id}`);
    if (modal) {
        modal.querySelector('.modal-title').textContent = updatedMenu.nama;
        modal.querySelector('.img-fluid').src = `/fotoMenu/${updatedMenu.foto}`;

        // Update varian
        updateJenisMenuOptions(modal, updatedMenu.jenis_menu);

        // Update komentar
        updateComments(modal, updatedMenu.recent_comments);
    }
}

function updateJenisMenu(updatedJenisMenu, updatedMenu) {
    const modal = document.querySelector(`#modal${updatedMenu.id}`);
    if (modal) {
        updateJenisMenuOptions(modal, updatedMenu.jenis_menu);
    }
}

function updateJenisMenuOptions(modal, jenisMenus) {
    const varianOptions = modal.querySelector('.varian-options');
    varianOptions.innerHTML = '';
    jenisMenus.forEach((jenis, index) => {
        const button = document.createElement('button');
        button.className = `varian-btn ${index === 0 ? 'active' : ''}`;
        button.onclick = () => selectVariant(button, jenis.menu_id, jenis.menu.nama, jenis.jenis, jenis
            .harga);
        button.textContent = `${jenis.jenis} - Rp ${jenis.harga.toLocaleString('id-ID')}`;
        varianOptions.appendChild(button);
    });
}

function updateComments(modal, comments) {
    const commentsContainer = modal.querySelector('.comments-container');
    commentsContainer.innerHTML = '';
    if (comments && comments.length > 0) {
        comments.forEach(comment => {
            const commentElement = document.createElement('div');
            commentElement.className = 'comment-card mb-2 p-2 border rounded';
            commentElement.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <strong>${comment.user.username}</strong>
                    <div class="star-rating">
                        ${Array(5).fill().map((_, i) =>
                `<i class="${i < comment.rating ? 'fas' : 'far'} fa-star text-warning"></i>`
            ).join('')}
                    </div>
                </div>
                <p class="mb-0 mt-1">${comment.isi_komentar}</p>
            `;
            commentsContainer.appendChild(commentElement);
        });
    } else {
        commentsContainer.innerHTML = '<p>Belum ada komentar.</p>';
    }
}