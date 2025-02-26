<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel Crud API</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <h2 class="text-center mb-4">Laravel CRUD API</h2>

    <form id="productForm" enctype="multipart/form-data" class="mb-4 border p-3 rounded shadow">
        <h4 class="mb-3">Add Product</h4>
        <div class="mb-3">
            <input type="text" id="name" name="name" class="form-control" placeholder="Product Name" required>
        </div>
        <div class="mb-3">
            <input type="text" id="description" name="description" class="form-control" placeholder="Description">
        </div>
        <div class="mb-3">
            <input type="number" id="price" name="price" class="form-control" placeholder="Price" required>
        </div>
        <div class="mb-3">
            <input type="number" id="stock" name="stock" class="form-control" placeholder="Stock" required>
        </div>
        <div class="mb-3">
            <input type="file" id="image" name="image" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Add Product</button>
    </form>

    <table class="table table-bordered shadow">
        <thead class="table-dark">
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productList"></tbody>
    </table>

    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editProductForm" enctype="multipart/form-data" class="border p-3 rounded">
                        <input type="hidden" id="editProductId">
                        <div class="mb-3">
                            <input type="text" id="editName" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <input type="text" id="editDescription" name="description" class="form-control">
                        </div>
                        <div class="mb-3">
                            <input type="number" id="editPrice" name="price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <input type="number" id="editStock" name="stock" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <input type="file" id="editImage" name="image" class="form-control">
                        </div>
                        <button type="submit" class="btn btn-success">Update Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetchProducts();

            document.getElementById("productForm").addEventListener("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);
                fetch('/api/products', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(() => {
                    this.reset();
                    fetchProducts();
                });
            });

            document.getElementById("editProductForm").addEventListener("submit", function(e) {
                e.preventDefault();
                let productId = document.getElementById("editProductId").value;
                let formData = new FormData(this);
                formData.append('_method', 'PUT');

                fetch(`/api/products/${productId}`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(() => {
                    fetchProducts();
                    let editModal = bootstrap.Modal.getInstance(document.getElementById("editProductModal"));
                    editModal.hide();
                });
            });
        });

        function fetchProducts() {
            fetch('/api/products')
            .then(response => response.json())
            .then(products => {
                let rows = "";
                products.forEach(product => {
                    rows += `<tr>
                        <td>${product.name}</td>
                        <td>${product.description}</td>
                        <td>${product.price}</td>
                        <td>${product.stock}</td>
                        <td><img src="/storage/${product.image}" width="50"></td>
                        <td>
                            <button class="btn btn-warning" onclick="editProduct(${product.id})">Edit</button>
                            <button class="btn btn-danger" onclick="deleteProduct(${product.id})">Delete</button>
                        </td>
                    </tr>`;
                });
                document.getElementById("productList").innerHTML = rows;
            });
        }

        function editProduct(id) {
            fetch(`/api/products/${id}`)
            .then(response => response.json())
            .then(product => {
                document.getElementById("editProductId").value = product.id;
                document.getElementById("editName").value = product.name;
                document.getElementById("editDescription").value = product.description;
                document.getElementById("editPrice").value = product.price;
                document.getElementById("editStock").value = product.stock;

                let editModal = new bootstrap.Modal(document.getElementById("editProductModal"));
                editModal.show();
            });
        }

        function deleteProduct(id) {
            fetch(`/api/products/${id}`, {
                method: 'DELETE'
            })
            .then(() => fetchProducts());
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
