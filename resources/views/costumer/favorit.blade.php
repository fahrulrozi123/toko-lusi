<!-- Halaman Favorit -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Favorit</title>
</head>
<body>
    <h1>Produk Favorit</h1>
    <div id="favoriteList"></div>

    <script>
        // Menampilkan item favorit dari localStorage
        let favorites = JSON.parse(localStorage.getItem('favorites')) || [];
        let favoriteList = document.getElementById('favoriteList');

        // Menampilkan item favorit di halaman favorit
        favorites.forEach(function (productInfo) {
            // Buat elemen produk untuk ditampilkan di halaman favorit
            let productElement = createProductElement(productInfo);

            // Tambahkan elemen produk ke daftar favorit
            favoriteList.appendChild(productElement);
        });

        // Fungsi untuk membuat elemen produk
        function createProductElement(productInfo) {
            let productElement = document.createElement('div');
            productElement.classList.add('col-md-6', 'col-lg-4');

            productElement.innerHTML = `
                <div class="card text-center card-product">
                    <div class="card-product__img">
                        <img class="card-img" src="path/to/product/image.jpg" alt="${productInfo.name}">
                        <ul class="card-product__imgOverlay">
                            <li><button><i class="ti-search"></i></button></li>
                            <!-- Tambahkan button untuk membeli atau tautan produk sesuai kebutuhan -->
                        </ul>
                    </div>
                    <div class="card-body">
                        <p>${productInfo.category}</p>
                        <h4 class="card-product__title">${productInfo.name}</h4>
                        <p class="card-product__price">Rp. ${number_format(productInfo.price)}</p>
                    </div>
                </div>
            `;

            return productElement;
        }

        // Fungsi untuk memformat angka sebagai mata uang
        function number_format(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }
    </script>
</body>
</html>
