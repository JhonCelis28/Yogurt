<?php 
$title = 'Gestión de Promociones';
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
                    <a href="<?php echo SITE_URL; ?>admin/categories" class="list-group-item list-group-item-action">
                        <i class="fas fa-tags me-2"></i>Categorías
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/orders" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-cart me-2"></i>Pedidos
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/users" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Usuarios
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/promotions" class="list-group-item list-group-item-action active">
                        <i class="fas fa-gift me-2"></i>Promociones
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary-custom">Gestión de Promociones</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                    <i class="fas fa-plus me-2"></i>Crear Promoción
                </button>
            </div>

            <!-- Tabla de promociones -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Descuento</th>
                                    <th>Código</th>
                                    <th>Vigencia</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($promotions)): ?>
                                    <?php foreach ($promotions as $promotion): ?>
                                    <tr>
                                        <td><?php echo $promotion['nombre']; ?></td>
                                        <td>
                                            <span class="badge <?php echo $promotion['tipo'] == 'porcentaje' ? 'bg-info' : 'bg-success'; ?>">
                                                <?php echo $promotion['tipo'] == 'porcentaje' ? 'Porcentaje' : 'Monto Fijo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php echo $promotion['tipo'] == 'porcentaje' ? $promotion['valor_descuento'] . '%' : formatPrice($promotion['valor_descuento']); ?>
                                        </td>
                                        <td><code><?php echo $promotion['codigo'] ?? 'N/A'; ?></code></td>
                                        <td>
                                            <small>
                                                <?php echo $promotion['fecha_inicio'] ? formatDate($promotion['fecha_inicio']) : 'Sin inicio'; ?> - 
                                                <?php echo $promotion['fecha_fin'] ? formatDate($promotion['fecha_fin']) : 'Sin fin'; ?>
                                            </small>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo $promotion['activo'] ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo $promotion['activo'] ? 'Activa' : 'Inactiva'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editPromotion(<?php echo $promotion['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="togglePromotion(<?php echo $promotion['id']; ?>, '<?php echo htmlspecialchars($promotion['nombre'], ENT_QUOTES); ?>', <?php echo $promotion['activo'] ? 1 : 0; ?>)">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deletePromotion(<?php echo $promotion['id']; ?>, '<?php echo htmlspecialchars($promotion['nombre'], ENT_QUOTES); ?>')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay promociones registradas</td>
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

<!-- Modal Crear Promoción -->
<div class="modal fade" id="addPromotionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nueva Promoción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo SITE_URL; ?>admin/promotions/add">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre de la Promoción *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="codigo" class="form-label">Código de Promoción *</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required>
                            <small class="text-muted">Para primera compra automática, use: PRIMERA_COMPRA o PRIMERA</small>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo" class="form-label">Tipo de Descuento *</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="porcentaje">Porcentaje</option>
                                <option value="monto_fijo">Monto Fijo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="valor_descuento" class="form-label">Valor del Descuento *</label>
                            <input type="number" class="form-control" id="valor_descuento" name="valor_descuento" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="condicion_minima" class="form-label">Condición Mínima (Monto)</label>
                            <input type="number" class="form-control" id="condicion_minima" name="condicion_minima" step="0.01" placeholder="Ej: 80000 para aplicar automáticamente">
                            <small class="text-muted">Si se establece, la promoción se aplicará automáticamente cuando el carrito supere este monto</small>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Promoción</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Promoción -->
<div class="modal fade" id="editPromotionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Promoción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="editPromotionForm">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_nombre" class="form-label">Nombre de la Promoción *</label>
                            <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_codigo" class="form-label">Código de Promoción *</label>
                            <input type="text" class="form-control" id="edit_codigo" name="codigo" required>
                            <small class="text-muted">Para primera compra automática, use: PRIMERA_COMPRA o PRIMERA</small>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_tipo" class="form-label">Tipo de Descuento *</label>
                            <select class="form-select" id="edit_tipo" name="tipo" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="porcentaje">Porcentaje</option>
                                <option value="monto_fijo">Monto Fijo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_valor_descuento" class="form-label">Valor del Descuento *</label>
                            <input type="number" class="form-control" id="edit_valor_descuento" name="valor_descuento" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_condicion_minima" class="form-label">Condición Mínima (Monto)</label>
                            <input type="number" class="form-control" id="edit_condicion_minima" name="condicion_minima" step="0.01" placeholder="Ej: 80000 para aplicar automáticamente">
                            <small class="text-muted">Si se establece, la promoción se aplicará automáticamente cuando el carrito supere este monto</small>
                        </div>
                        <div class="col-md-6">
                            <label for="edit_fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="edit_fecha_inicio" name="fecha_inicio">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="edit_fecha_fin" name="fecha_fin">
                        </div>
                        <div class="col-12">
                            <label for="edit_descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="edit_descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Promoción</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editPromotion(id) {
    // Limpiar el formulario
    document.getElementById('editPromotionForm').reset();
    
    // Cargar datos de la promoción
    fetch('<?php echo SITE_URL; ?>admin/promotions/edit/' + id + '?ajax=1')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }
            
            // Llenar el formulario con los datos de la promoción
            document.getElementById('edit_nombre').value = data.nombre || '';
            document.getElementById('edit_codigo').value = data.codigo || '';
            document.getElementById('edit_tipo').value = data.tipo || '';
            document.getElementById('edit_valor_descuento').value = data.valor_descuento || '';
            document.getElementById('edit_condicion_minima').value = data.condicion_minima || '';
            document.getElementById('edit_descripcion').value = data.descripcion || '';
            document.getElementById('edit_fecha_inicio').value = data.fecha_inicio || '';
            document.getElementById('edit_fecha_fin').value = data.fecha_fin || '';
            
            // Actualizar la acción del formulario
            document.getElementById('editPromotionForm').action = '<?php echo SITE_URL; ?>admin/promotions/edit/' + id;
            
            // Mostrar el modal
            const editModal = new bootstrap.Modal(document.getElementById('editPromotionModal'));
            editModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos de la promoción');
        });
}

function togglePromotion(id, promotionName, currentStatus) {
    const newStatus = currentStatus == 1 ? 'inactiva' : 'activa';
    const statusIcon = currentStatus == 1 ? 'fa-toggle-off' : 'fa-toggle-on';
    const statusColor = currentStatus == 1 ? '#ff6b6b' : '#4CAF50';
    
    Swal.fire({
        title: '¿Cambiar estado de la promoción?',
        html: `<div style="text-align: center;">
                <i class="fas ${statusIcon}" style="font-size: 60px; color: ${statusColor}; margin-bottom: 20px;"></i>
                <p style="font-size: 18px; margin-bottom: 10px;">Promoción:</p>
                <p style="font-size: 20px; font-weight: bold; color: var(--primary-color); margin-bottom: 20px;">${promotionName}</p>
                <p style="font-size: 16px; margin-bottom: 10px;">
                    Estado actual: <strong>${currentStatus == 1 ? 'Activa' : 'Inactiva'}</strong>
                </p>
                <p style="font-size: 16px; color: ${statusColor};">
                    Nuevo estado: <strong>${newStatus.charAt(0).toUpperCase() + newStatus.slice(1)}</strong>
                </p>
               </div>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#E91E63',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-check me-2"></i>Confirmar',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom',
            confirmButton: 'swal2-confirm-custom',
            cancelButton: 'swal2-cancel-custom'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Actualizando...',
                html: '<div style="text-align: center;"><i class="fas fa-spinner fa-spin" style="font-size: 40px; color: #E91E63;"></i><p style="margin-top: 20px;">Por favor espera</p></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Redirigir para cambiar estado
            window.location.href = '<?php echo SITE_URL; ?>admin/promotions/toggle/' + id;
        }
    });
}

function deletePromotion(id, promotionName) {
    Swal.fire({
        title: '¿Desactivar promoción?',
        html: `<div style="text-align: center;">
                <i class="fas fa-exclamation-triangle" style="font-size: 60px; color: #ff6b6b; margin-bottom: 20px;"></i>
                <p style="font-size: 18px; margin-bottom: 10px;">Promoción:</p>
                <p style="font-size: 20px; font-weight: bold; color: var(--primary-color); margin-bottom: 20px;">${promotionName}</p>
                <p style="font-size: 16px; color: #666;">
                    Esta acción desactivará la promoción. Podrás reactivarla más tarde.
                </p>
               </div>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-2"></i>Sí, desactivar',
        cancelButtonText: '<i class="fas fa-times me-2"></i>Cancelar',
        reverseButtons: true,
        customClass: {
            popup: 'swal2-popup-custom',
            confirmButton: 'swal2-confirm-custom',
            cancelButton: 'swal2-cancel-custom'
        },
        buttonsStyling: false
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostrar loading
            Swal.fire({
                title: 'Desactivando...',
                html: '<div style="text-align: center;"><i class="fas fa-spinner fa-spin" style="font-size: 40px; color: #dc3545;"></i><p style="margin-top: 20px;">Por favor espera</p></div>',
                allowOutsideClick: false,
                allowEscapeKey: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Redirigir para eliminar
            window.location.href = '<?php echo SITE_URL; ?>admin/promotions/delete/' + id;
        }
    });
}
</script>

<?php include 'views/layout/footer.php'; ?>
