<?php 
$title = 'Gestión de Categorías';
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
                    <a href="<?php echo SITE_URL; ?>admin/products" class="list-group-item list-group-item-action">
                        <i class="fas fa-box me-2"></i>Productos
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/categories" class="list-group-item list-group-item-action active">
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
                <h2 class="text-primary-custom">Gestión de Categorías</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus me-2"></i>Agregar Categoría
                </button>
            </div>

            <!-- Tabla de categorías -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Productos</th>
                                    <th>Estado</th>
                                    <th>Fecha Creación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($categories)): ?>
                                    <?php foreach ($categories as $category): ?>
                                    <tr>
                                        <td><?php echo $category['nombre']; ?></td>
                                        <td><?php echo substr($category['descripcion'], 0, 50) . '...'; ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo isset($category['product_count']) ? $category['product_count'] : 0; ?> productos
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $category['activo'] ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo $category['activo'] ? 'Activa' : 'Inactiva'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo formatDate($category['fecha_creacion']); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editCategory(<?php echo $category['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['nombre'], ENT_QUOTES); ?>', <?php echo isset($category['product_count']) ? $category['product_count'] : 0; ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay categorías registradas</td>
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

<!-- Modal Agregar Categoría -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nueva Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo SITE_URL; ?>admin/categories/add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Categoría *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Categoría -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Categoría</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="editCategoryForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre de la Categoría *</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCategory(id) {
    // Limpiar el formulario
    document.getElementById('editCategoryForm').reset();
    
    // Cargar datos de la categoría
    fetch('<?php echo SITE_URL; ?>admin/categories/edit/' + id + '?ajax=1')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }
            
            // Llenar el formulario con los datos de la categoría
            document.getElementById('edit_nombre').value = data.nombre || '';
            document.getElementById('edit_descripcion').value = data.descripcion || '';
            
            // Actualizar la acción del formulario
            document.getElementById('editCategoryForm').action = '<?php echo SITE_URL; ?>admin/categories/edit/' + id;
            
            // Mostrar el modal
            const editModal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
            editModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos de la categoría');
        });
}

function deleteCategory(id, categoryName, productCount) {
    let message = '';
    if (productCount > 0) {
        message = `<div style="text-align: center;">
                    <i class="fas fa-exclamation-triangle" style="font-size: 60px; color: #ff6b6b; margin-bottom: 20px;"></i>
                    <p style="font-size: 18px; margin-bottom: 10px;">No se puede eliminar la categoría:</p>
                    <p style="font-size: 20px; font-weight: bold; color: var(--primary-color); margin-bottom: 20px;">${categoryName}</p>
                    <p style="font-size: 16px; color: #ff6b6b; margin-bottom: 10px;">
                        <i class="fas fa-box me-2"></i>Tiene <strong>${productCount}</strong> producto(s) asociado(s)
                    </p>
                    <p style="font-size: 14px; color: #666;">Primero debes eliminar o mover los productos de esta categoría</p>
                   </div>`;
        
        Swal.fire({
            title: 'No se puede eliminar',
            html: message,
            icon: 'error',
            confirmButtonColor: '#E91E63',
            confirmButtonText: '<i class="fas fa-check me-2"></i>Entendido',
            customClass: {
                popup: 'swal2-popup-custom',
                confirmButton: 'swal2-confirm-custom'
            },
            buttonsStyling: false
        });
        return;
    }
    
    message = `<div style="text-align: center;">
                <i class="fas fa-exclamation-triangle" style="font-size: 60px; color: #ff6b6b; margin-bottom: 20px;"></i>
                <p style="font-size: 18px; margin-bottom: 10px;">Estás a punto de eliminar:</p>
                <p style="font-size: 20px; font-weight: bold; color: var(--primary-color); margin-bottom: 20px;">${categoryName}</p>
                <p style="font-size: 14px; color: #666;">Esta acción no se puede deshacer</p>
               </div>`;
    
    Swal.fire({
        title: '¿Estás seguro?',
        html: message,
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
            window.location.href = '<?php echo SITE_URL; ?>admin/categories/delete/' + id;
        }
    });
}
</script>

<?php include 'views/layout/footer.php'; ?>
