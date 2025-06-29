<?php 
$title = 'Gestión de Pedidos';
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
                    <a href="<?php echo SITE_URL; ?>admin/orders" class="list-group-item list-group-item-action active">
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
                <h2 class="text-primary-custom">Gestión de Pedidos</h2>
                <div class="d-flex gap-2">
                    <select class="form-select" id="filterStatus">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmado">Confirmado</option>
                        <option value="preparando">Preparando</option>
                        <option value="enviado">Enviado</option>
                        <option value="entregado">Entregado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>8</h3>
                            <p class="mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>5</h3>
                            <p class="mb-0">Confirmados</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3>12</h3>
                            <p class="mb-0">En Preparación</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>25</h3>
                            <p class="mb-0">Entregados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de pedidos -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Datos de ejemplo para pedidos
                                $sampleOrders = [
                                    ['id' => 1, 'cliente' => 'María García', 'email' => 'maria@email.com', 'fecha' => '2024-06-10 14:30:00', 'total' => 45000, 'estado' => 'pendiente'],
                                    ['id' => 2, 'cliente' => 'Carlos López', 'email' => 'carlos@email.com', 'fecha' => '2024-06-10 12:15:00', 'total' => 32000, 'estado' => 'confirmado'],
                                    ['id' => 3, 'cliente' => 'Ana Rodríguez', 'email' => 'ana@email.com', 'fecha' => '2024-06-09 16:45:00', 'total' => 28500, 'estado' => 'preparando'],
                                    ['id' => 4, 'cliente' => 'Luis Martínez', 'email' => 'luis@email.com', 'fecha' => '2024-06-09 10:20:00', 'total' => 52000, 'estado' => 'enviado'],
                                    ['id' => 5, 'cliente' => 'Sofia Hernández', 'email' => 'sofia@email.com', 'fecha' => '2024-06-08 18:30:00', 'total' => 38000, 'estado' => 'entregado']
                                ];
                                
                                foreach ($sampleOrders as $order):
                                    $statusClass = '';
                                    switch($order['estado']) {
                                        case 'pendiente': $statusClass = 'bg-warning'; break;
                                        case 'confirmado': $statusClass = 'bg-info'; break;
                                        case 'preparando': $statusClass = 'bg-primary'; break;
                                        case 'enviado': $statusClass = 'bg-success'; break;
                                        case 'entregado': $statusClass = 'bg-success'; break;
                                        case 'cancelado': $statusClass = 'bg-danger'; break;
                                    }
                                ?>
                                <tr>
                                    <td>#<?php echo $order['id']; ?></td>
                                    <td>
                                        <div>
                                            <strong><?php echo $order['cliente']; ?></strong>
                                            <br><small class="text-muted"><?php echo $order['email']; ?></small>
                                        </div>
                                    </td>
                                    <td><?php echo formatDateTime($order['fecha']); ?></td>
                                    <td><?php echo formatPrice($order['total']); ?></td>
                                    <td>
                                        <select class="form-select form-select-sm" onchange="updateOrderStatus(<?php echo $order['id']; ?>, this.value)">
                                            <option value="pendiente" <?php echo $order['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                            <option value="confirmado" <?php echo $order['estado'] == 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                                            <option value="preparando" <?php echo $order['estado'] == 'preparando' ? 'selected' : ''; ?>>Preparando</option>
                                            <option value="enviado" <?php echo $order['estado'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                            <option value="entregado" <?php echo $order['estado'] == 'entregado' ? 'selected' : ''; ?>>Entregado</option>
                                            <option value="cancelado" <?php echo $order['estado'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                        </select>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewOrderDetails(<?php echo $order['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="printOrder(<?php echo $order['id']; ?>)">
                                            <i class="fas fa-print"></i>
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

<script>
function updateOrderStatus(orderId, newStatus) {
    if (confirm('¿Cambiar el estado del pedido #' + orderId + ' a ' + newStatus + '?')) {
        // Aquí iría la llamada AJAX para actualizar el estado
        showAlert('Estado del pedido actualizado', 'success');
    }
}

function viewOrderDetails(orderId) {
    alert('Ver detalles del pedido #' + orderId);
}

function printOrder(orderId) {
    alert('Imprimir pedido #' + orderId);
}
</script>

<?php include 'views/layout/footer.php'; ?>
