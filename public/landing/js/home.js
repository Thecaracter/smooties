// Subscribe ke channel 'menu' (tetap sama)
const menuChannel = pusher.subscribe('menu');

// Listen untuk event 'menu-updated' (tetap sama)
menuChannel.bind('menu-updated', function (data) {
    updateMenuDisplay(data.menu);
});

// Subscribe ke channel 'jenis-menu' (tetap sama)
const jenisMenuChannel = pusher.subscribe('jenis-menu');

// Listen untuk event 'jenis-menu-updated' (tetap sama)
jenisMenuChannel.bind('jenis-menu-updated', function (data) {
    updateMenuDisplay(data.menu);
});

function updateMenuDisplay(menu) {
    console.log('Updating menu:', menu); // Tambahkan log ini untuk debugging

    // Update card menu (tetap sama)
    const menuCard = document.querySelector(`.card[data-menu-id="${menu.id}"]`);
    if (menuCard) {
        menuCard.querySelector('.card-title').textContent = menu.nama;
        menuCard.querySelector('.card-text').textContent = menu.kategori.nama;
        menuCard.querySelector('.card-img-top').src = `/fotoMenu/${menu.foto}`;
    }

    // Update modal
    const modal = document.getElementById(`modal${menu.id}`);
    if (modal) {
        modal.querySelector('.modal-title').textContent = menu.nama;
        modal.querySelector('.img-fluid').src = `/fotoMenu/${menu.foto}`;
        modal.querySelector('p').textContent = menu.kategori.nama;

        // Update varian options
        const varianContainer = modal.querySelector('.varian-options');
        varianContainer.innerHTML = '';
        menu.jenis_menu.forEach((jenis, index) => {
            const button = document.createElement('button');
            button.className = `varian-btn ${index === 0 ? 'active' : ''}`;
            button.setAttribute('data-harga', jenis.harga);
            button.onclick = function () {
                selectVariant(this, menu.id, menu.nama, jenis.jenis, jenis.harga);
            };
            button.textContent = `${jenis.jenis} - Rp ${jenis.harga.toLocaleString('id-ID')}`;
            varianContainer.appendChild(button);
        });

        // Update comments (tetap sama)
        const commentsContainer = modal.querySelector('.comments-container');
        commentsContainer.innerHTML = '';
        if (menu.komentar && menu.komentar.length > 0) {
            menu.komentar.forEach(comment => {
                commentsContainer.innerHTML += `
                <div class="comment-card mb-2 p-2 border rounded">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong>${comment.user.username}</strong>
                        <div class="star-rating">
                            ${getStarRating(comment.rating)}
                        </div>
                    </div>
                    <p class="mb-0 mt-1">${comment.isi_komentar}</p>
                </div>
            `;
            });
        } else {
            commentsContainer.innerHTML = '<p>Belum ada komentar.</p>';
        }
    }
}

function getStarRating(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += '<i class="fas fa-star text-warning"></i>';
        } else {
            stars += '<i class="far fa-star text-warning"></i>';
        }
    }
    return stars;
}