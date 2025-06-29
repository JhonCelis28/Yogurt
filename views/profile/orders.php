<?php 
$title = 'Mis Pedidos';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar del perfil -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-4x text-primary-custom"></i>
                    </div>
                    <h5><?php echo $_SESSION['user_name']; ?></h5>
                    <p class="text-muted"><?php echo $_SESSION['user_email']; ?></p>
                </div>
            </div>
            
            <div class="list-group mt-3">
                <a href="<?php echo SITE_URL; ?>profile" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i>Mi Perfil
                </a>
                <a href="<?php echo SITE_URL; ?>profile/orders" class="list-group-item list-group-item-action active">
                    <i class="fas fa-shopping-bag me-2"></i>Mis Pedidos
                </a>
                <a href="<?php echo SITE_URL; ?>profile/addresses" class="list-group-item list-group-item-action">
                    <i class="fas fa-map-marker-alt me-2"></i>Direcciones
                </a>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-shopping-bag me-2"></i>Mis Pedidos</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                        <div class="card mb-3">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">Pedido #<?php echo $order['id']; ?></h6>
                                    <small class="text-muted"><?php echo formatDateTime($order['fecha_pedido']); ?></small>
                                </div>
                                <div>
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch($order['estado']) {
                                        case 'pendiente':
                                            $statusClass = 'bg-warning';
                                            $statusText = 'Pendiente';
                                            break;
                                        case 'confirmado':
                                            $statusClass = 'bg-info';
                                            $statusText = 'Confirmado';
                                            break;
                                        case 'preparando':
                                            $statusClass = 'bg-primary';
                                            $statusText = 'Preparando';
                                            break;
                                        case 'enviado':
                                            $statusClass = 'bg-success';
                                            $statusText = 'Enviado';
                                            break;
                                        case 'entregado':
                                            $statusClass = 'bg-success';
                                            $statusText = 'Entregado';
                                            break;
                                        case 'cancelado':
                                            $statusClass = 'bg-danger';
                                            $statusText = 'Cancelado';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="mb-1"><strong>Dirección de entrega:</strong></p>
                                        <p class="text-muted"><?php echo $order['direccion_entrega']; ?></p>
                                        <?php if ($order['notas']): ?>
                                            <p class="mb-1"><strong>Notas:</strong></p>
                                            <p class="text-muted"><?php echo $order['notas']; ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <h5 class="text-primary-custom"><?php echo formatPrice($order['total']); ?></h5>
                                        <button class="btn btn-outline-primary btn-sm" onclick="toggleOrderDetails(<?php echo $order['id']; ?>)">
                                            <i class="fas fa-eye me-1"></i>Ver Detalles
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Detalles del pedido (ocultos por defecto) -->
                                <div id="order-details-<?php echo $order['id']; ?>" class="mt-3" style="display: none;">
                                    <hr>
                                    <h6>Productos del pedido:</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Aquí irían los detalles del pedido -->
                                                <tr>
                                                    <td>Yogur Griego Natural</td>
                                                    <td>2</td>
                                                    <td>$12.000</td>
                                                    <td>$24.000</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No tienes pedidos aún</h4>
                            <p class="text-muted">¡Haz tu primer pedido y disfruta de nuestros productos artesanales!</p>
                            <a href="<?php echo SITE_URL; ?>products" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Ver Productos
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleOrderDetails(orderId) {
    const details = document.getElementById('order-details-' + orderId);
    if (details.style.display === 'none') {
        details.style.display = 'block';
    } else {
        details.style.display = 'none';
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>
