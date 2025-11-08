<?php 
$title = 'Gestión de Productos';
include 'views/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 col-md-3">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Panel Admin</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo SITE_URL; ?>admin" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/products" class="list-group-item list-group-item-action active">
                        <i class="fas fa-box me-2"></i>Productos
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/categories" class="list-group-item list-group-item-action">
                        <i class="fas fa-tags me-2"></i>Categorías
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/orders" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-cart me-2"></i>Pedidos
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/users" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Usuarios
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/promotions" class="list-group-item list-group-item-action">
                        <i class="fas fa-gift me-2"></i>Promociones
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/contacts" class="list-group-item list-group-item-action">
                        <i class="fas fa-envelope me-2"></i>Contactos
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary-custom">Gestión de Productos</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="fas fa-plus me-2"></i>Agregar Producto
                </button>
            </div>

            <!-- Tabla de productos -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Imagen</th>
                                    <th>Nombre</th>
                                    <th>Categoría</th>
                                    <th>Precio</th>
                                    <th>Stock</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($products)): ?>
                                    <?php foreach ($products as $product): ?>
                                    <tr>
                                        <td>
                                            <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $product['imagen'] ?: 'default.jpg'; ?>" 
                                                 alt="<?php echo $product['nombre']; ?>" style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                        </td>
                                        <td><?php echo $product['nombre']; ?></td>
                                        <td><?php echo $product['categoria_nombre']; ?></td>
                                        <td><?php echo formatPrice($product['precio']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $product['stock'] > 10 ? 'bg-success' : ($product['stock'] > 0 ? 'bg-warning' : 'bg-danger'); ?>">
                                                <?php echo $product['stock']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $product['activo'] ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo $product['activo'] ? 'Activo' : 'Inactivo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editProduct(<?php echo $product['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(<?php echo $product['id']; ?>, '<?php echo htmlspecialchars($product['nombre'], ENT_QUOTES); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay productos registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Producto -->
<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nuevo Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo SITE_URL; ?>admin/products/add" enctype="multipart/form-data" id="addProductForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="categoria_id" class="form-label">Categoría *</label>
                            <select class="form-select" id="categoria_id" name="categoria_id" required>
                                <option value="">Seleccionar categoría</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['nombre']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="precio" class="form-label">Precio *</label>
                            <input type="number" class="form-control" id="precio" name="precio" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="stock" class="form-label">Stock *</label>
                            <input type="number" class="form-control" id="stock" name="stock" required>
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="imagen" class="form-label">Imagen del Producto</label>
                            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="es_personalizable" name="es_personalizable">
                                <label class="form-check-label" for="es_personalizable">
                                    Producto Personalizable
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="destacado" name="destacado">
                                <label class="form-check-label" for="destacado">
                                    Producto Destacado
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Producto -->
<div class="modal fade" id="editProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Producto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" enctype="multipart/form-data" id="editProductForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_nombre" class="form-label">Nombre del Producto *</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_categoria_id" class="form-label">Categoría *</label>
                            <select class="form-select" id="edit_categoria_id" name="categoria_id" required>
                                <option value="">Seleccionar categoría</option>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category['id']; ?>"><?php echo $category['nombre']; ?></option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_precio" class="form-label">Precio *</label>
                            <input type="number" class="form-control" id="edit_precio" name="precio" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_stock" class="form-label">Stock *</label>
                            <input type="number" class="form-control" id="edit_stock" name="stock" required>
                        </div>
                        <div class="col-12">
                            <label for="edit_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_imagen" class="form-label">Imagen del Producto</label>
                            <input type="file" class="form-control" id="edit_imagen" name="imagen" accept="image/*">
                            <div id="current_image_preview" class="mt-2"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" id="edit_es_personalizable" name="es_personalizable">
                                <label class="form-check-label" for="edit_es_personalizable">
                                    Producto Personalizable
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="edit_destacado" name="destacado">
                                <label class="form-check-label" for="edit_destacado">
                                    Producto Destacado
                                </label>
                            </div>
                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" id="edit_activo" name="activo">
                                <label class="form-check-label" for="edit_activo">
                                    Producto Activo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editProduct(id) {
    // Limpiar el formulario
    document.getElementById('editProductForm').reset();
    document.getElementById('current_image_preview').innerHTML = '';
    
    // Cargar datos del producto
    fetch('<?php echo SITE_URL; ?>admin/products/edit/' + id + '?ajax=1')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }
            
            // Llenar el formulario con los datos del producto
            document.getElementById('edit_nombre').value = data.nombre || '';
            document.getElementById('edit_categoria_id').value = data.categoria_id || '';
            document.getElementById('edit_precio').value = data.precio || '';
            document.getElementById('edit_stock').value = data.stock || '';
            document.getElementById('edit_descripcion').value = data.descripcion || '';
            document.getElementById('edit_es_personalizable').checked = data.es_personalizable == 1;
            document.getElementById('edit_destacado').checked = data.destacado == 1;
            document.getElementById('edit_activo').checked = data.activo == 1;
            
            // Actualizar la acción del formulario
            document.getElementById('editProductForm').action = '<?php echo SITE_URL; ?>admin/products/edit/' + id;
            
            // Mostrar imagen actual si existe
            if (data.imagen) {
                const imgPreview = document.getElementById('current_image_preview');
                imgPreview.innerHTML = '<small class="text-muted">Imagen actual:</small><br>' +
                    '<img src="<?php echo SITE_URL; ?>assets/images/products/' + data.imagen + '" alt="Imagen actual" style="max-width: 150px; margin-top: 5px;" class="img-thumbnail">';
            }
            
            // Mostrar el modal
            const editModal = new bootstrap.Modal(document.getElementById('editProductModal'));
            editModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del producto');
        });
}

function deleteProduct(id, productName = 'este producto') {
    Swal.fire({
        title: '¿Estás seguro?',
        html: `<div style="text-align: center;">
                <i class="fas fa-exclamation-triangle" style="font-size: 60px; color: #ff6b6b; margin-bottom: 20px;"></i>
                <p style="font-size: 18px; margin-bottom: 10px;">Estás a punto de eliminar:</p>
                <p style="font-size: 20px; font-weight: bold; color: var(--primary-color); margin-bottom: 20px;">${productName}</p>
                <p style="font-size: 14px; color: #666;">Esta acción no se puede deshacer</p>
               </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#E91E63',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Sí, eliminar',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom',
            confirmButton: 'swal2-confirm-custom',
            cancelButton: 'swal2-cancel-custom'
        },
        buttonsStyling: false,
        showClass: {
            popup: 'animate__animated animate__fadeInDown'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Eliminando...',
                html: '<div style="text-align: center;"><i class="fas fa-spinner fa-spin" style="font-size: 40px; color: #E91E63;"></i><p style="margin-top: 20px;">Por favor espera</p></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Redirigir para eliminar
            window.location.href = '<?php echo SITE_URL; ?>admin/products/delete/' + id;
        }
    });
}
</script>

<?php include 'views/layout/footer.php'; ?>
