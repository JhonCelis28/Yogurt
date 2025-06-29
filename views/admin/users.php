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
                    <a href="<?php echo SITE_URL; ?>admin/contacts" class="list-group-item list-group-item-action">
                        <i class="fas fa-envelope me-2"></i>Contactos
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
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3>156</h3>
                            <p class="mb-0">Total Usuarios</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>142</h3>
                            <p class="mb-0">Activos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>23</h3>
                            <p class="mb-0">Nuevos (Este mes)</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>14</h3>
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
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Fecha Registro</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Datos de ejemplo para usuarios
                                $sampleUsers = [
                                    ['id' => 1, 'nombre' => 'María García', 'email' => 'maria@email.com', 'telefono' => '3001234567', 'fecha_registro' => '2024-05-15', 'activo' => 1],
                                    ['id' => 2, 'nombre' => 'Carlos López', 'email' => 'carlos@email.com', 'telefono' => '3007654321', 'fecha_registro' => '2024-05-20', 'activo' => 1],
                                    ['id' => 3, 'nombre' => 'Ana Rodríguez', 'email' => 'ana@email.com', 'telefono' => '3009876543', 'fecha_registro' => '2024-06-01', 'activo' => 1],
                                    ['id' => 4, 'nombre' => 'Luis Martínez', 'email' => 'luis@email.com', 'telefono' => '3005432109', 'fecha_registro' => '2024-06-05', 'activo' => 0],
                                    ['id' => 5, 'nombre' => 'Sofia Hernández', 'email' => 'sofia@email.com', 'telefono' => '3002468135', 'fecha_registro' => '2024-06-08', 'activo' => 1]
                                ];
                                
                                foreach ($sampleUsers as $user):
                                ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['nombre']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['telefono']; ?></td>
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
                                        <button class="btn btn-sm btn-outline-warning" onclick="toggleUserStatus(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-info" onclick="viewUserOrders(<?php echo $user['id']; ?>)">
                                            <i class="fas fa-shopping-bag"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
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

<script>
function editUser(id) {
    alert('Editar usuario ID: ' + id);
}

function toggleUserStatus(id) {
    if (confirm('¿Cambiar el estado de este usuario?')) {
        alert('Cambiar estado del usuario ID: ' + id);
    }
}

function viewUserOrders(id) {
    alert('Ver pedidos del usuario ID: ' + id);
}
</script>

<?php include 'views/layout/footer.php'; ?>
