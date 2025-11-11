<?php 
$title = 'Gestión de Usuarios';
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
                    <a href="<?php echo SITE_URL; ?>admin/users" class="list-group-item list-group-item-action active">
                        <i class="fas fa-users me-2"></i>Usuarios
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/promotions" class="list-group-item list-group-item-action">
                        <i class="fas fa-gift me-2"></i>Promociones
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary-custom">Gestión de Usuarios</h2>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" placeholder="Buscar usuario..." id="searchUser">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                        <i class="fas fa-plus me-2"></i>Agregar Usuario
                    </button>
                </div>
            </div>

            <!-- Estadísticas de usuarios -->
            <div class="row g-3 mb-4">
                <?php
                $totalUsers = count($users);
                $activeUsers = count(array_filter($users, function($u) { return $u['activo'] == 1; }));
                $inactiveUsers = $totalUsers - $activeUsers;
                $thisMonth = date('Y-m');
                $newThisMonth = count(array_filter($users, function($u) use ($thisMonth) { 
                    return strpos($u['fecha_registro'], $thisMonth) === 0; 
                }));
                ?>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $totalUsers; ?></h3>
                            <p class="mb-0">Total Usuarios</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $activeUsers; ?></h3>
                            <p class="mb-0">Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $newThisMonth; ?></h3>
                            <p class="mb-0">Nuevos (Este mes)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $inactiveUsers; ?></h3>
                            <p class="mb-0">Inactivos</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de usuarios -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Fecha Registro</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($users)): ?>
                                    <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['nombre']; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td><?php echo $user['telefono'] ?? 'N/A'; ?></td>
                                        <td><?php echo formatDate($user['fecha_registro']); ?></td>
                                        <td>
                                            <span class="badge <?php echo $user['activo'] ? 'bg-success' : 'bg-secondary'; ?>">
                                                <?php echo $user['activo'] ? 'Activo' : 'Inactivo'; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="editUser(<?php echo $user['id']; ?>)">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-warning" onclick="toggleUserStatus(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['nombre'], ENT_QUOTES); ?>', <?php echo $user['activo'] ? 1 : 0; ?>)">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-info" onclick="viewUserOrders(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars($user['nombre'], ENT_QUOTES); ?>')">
                                                <i class="fas fa-shopping-bag"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay usuarios registrados</td>
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

<!-- Modal Agregar Usuario -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nuevo Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo SITE_URL; ?>admin/users/add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña *</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="tipo" class="form-label">Tipo de Usuario</label>
                        <select class="form-select" id="tipo" name="tipo">
                            <option value="cliente">Cliente</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="editUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="" id="editUserForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_nombre" class="form-label">Nombre Completo *</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="edit_email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_telefono" class="form-label">Teléfono</label>
                        <input type="tel" class="form-control" id="edit_telefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="edit_direccion" class="form-label">Dirección</label>
                        <textarea class="form-control" id="edit_direccion" name="direccion" rows="2"></textarea>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="edit_activo" name="activo">
                            <label class="form-check-label" for="edit_activo">
                                Usuario Activo
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Usuario</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editUser(id) {
    // Limpiar el formulario
    document.getElementById('editUserForm').reset();
    
    // Cargar datos del usuario
    fetch('<?php echo SITE_URL; ?>admin/users/edit/' + id + '?ajax=1')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
                return;
            }
            
            // Llenar el formulario con los datos del usuario
            document.getElementById('edit_nombre').value = data.nombre || '';
            document.getElementById('edit_email').value = data.email || '';
            document.getElementById('edit_telefono').value = data.telefono || '';
            document.getElementById('edit_direccion').value = data.direccion || '';
            document.getElementById('edit_activo').checked = data.activo == 1;
            
            // Actualizar la acción del formulario
            document.getElementById('editUserForm').action = '<?php echo SITE_URL; ?>admin/users/edit/' + id;
            
            // Mostrar el modal
            const editModal = new bootstrap.Modal(document.getElementById('editUserModal'));
            editModal.show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cargar los datos del usuario');
        });
}

function toggleUserStatus(id, userName, currentStatus) {
    const newStatus = currentStatus == 1 ? 'inactivo' : 'activo';
    const statusIcon = currentStatus == 1 ? 'fa-toggle-off' : 'fa-toggle-on';
    const statusColor = currentStatus == 1 ? '#ff6b6b' : '#4CAF50';
    
    Swal.fire({
        title: '¿Cambiar estado del usuario?',
        html: `<div style="text-align: center;">
                <i class="fas ${statusIcon}" style="font-size: 60px; color: ${statusColor}; margin-bottom: 20px;"></i>
                <p style="font-size: 18px; margin-bottom: 10px;">Usuario:</p>
                <p style="font-size: 20px; font-weight: bold; color: var(--primary-color); margin-bottom: 20px;">${userName}</p>
                <p style="font-size: 16px; margin-bottom: 10px;">
                    Estado actual: <strong>${currentStatus == 1 ? 'Activo' : 'Inactivo'}</strong>
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
            window.location.href = '<?php echo SITE_URL; ?>admin/users/toggle-status/' + id;
        }
    });
}

function viewUserOrders(id, userName) {
    // Redirigir a pedidos con filtro de usuario
    window.location.href = '<?php echo SITE_URL; ?>admin/orders?user_id=' + id;
}
</script>

<?php include 'views/layout/footer.php'; ?>
