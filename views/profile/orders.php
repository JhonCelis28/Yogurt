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
                <?php if (!isAdmin()): ?>
                    <a href="<?php echo SITE_URL; ?>profile/orders" class="list-group-item list-group-item-action active">
                        <i class="fas fa-shopping-bag me-2"></i>Mis Pedidos
                    </a>
                    <a href="<?php echo SITE_URL; ?>profile/addresses" class="list-group-item list-group-item-action">
                        <i class="fas fa-map-marker-alt me-2"></i>Direcciones
                    </a>
                <?php endif; ?>
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
                                        <div class="d-flex gap-2 justify-content-end">
                                            <button class="btn btn-outline-primary btn-sm" onclick="toggleOrderDetails(<?php echo $order['id']; ?>)">
                                                <i class="fas fa-eye me-1"></i>Ver Detalles
                                            </button>
                                            <button class="btn btn-outline-success btn-sm" onclick="printOrder(<?php echo $order['id']; ?>)">
                                                <i class="fas fa-print me-1"></i>Imprimir
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Detalles del pedido (ocultos por defecto) -->
                                <div id="order-details-<?php echo $order['id']; ?>" class="mt-3" style="display: none;">
                                    <hr>
                                    <h6>Productos del pedido:</h6>
                                    <?php 
                                    $orderItems = isset($order['items']) ? $order['items'] : [];
                                    if (!empty($orderItems)): 
                                    ?>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Unitario</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($orderItems as $item): 
                                                    $subtotal = $item['cantidad'] * $item['precio_unitario'];
                                                    $personalizaciones = !empty($item['personalizaciones']) ? json_decode($item['personalizaciones'], true) : null;
                                                ?>
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <?php if (!empty($item['imagen'])): ?>
                                                            <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $item['imagen']; ?>" 
                                                                 alt="<?php echo htmlspecialchars($item['nombre']); ?>" 
                                                                 class="me-2" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                            <?php endif; ?>
                                                            <div>
                                                                <strong><?php echo htmlspecialchars($item['nombre']); ?></strong>
                                                                <?php if ($personalizaciones): ?>
                                                                    <br><small class="text-muted">
                                                                        <?php if (isset($personalizaciones['sabor'])): ?>
                                                                            Sabor: <?php echo ucfirst($personalizaciones['sabor']); ?>
                                                                        <?php endif; ?>
                                                                        <?php if (isset($personalizaciones['endulzante'])): ?>
                                                                            <?php if (isset($personalizaciones['sabor'])): ?>, <?php endif; ?>
                                                                            Endulzante: <?php echo ucfirst(str_replace('_', ' ', $personalizaciones['endulzante'])); ?>
                                                                        <?php endif; ?>
                                                                    </small>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><?php echo $item['cantidad']; ?></td>
                                                    <td><?php echo formatPrice($item['precio_unitario']); ?></td>
                                                    <td><strong><?php echo formatPrice($subtotal); ?></strong></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                            <tfoot>
                                                <?php
                                                // Calcular subtotal de productos
                                                $subtotalProductos = 0;
                                                if (!empty($orderItems)) {
                                                    foreach ($orderItems as $item) {
                                                        $subtotalProductos += $item['precio_unitario'] * $item['cantidad'];
                                                    }
                                                }
                                                
                                                // Obtener información de envases devueltos desde info_pago
                                                $infoPago = !empty($order['info_pago']) ? json_decode($order['info_pago'], true) : [];
                                                $envasesDevueltos = isset($infoPago['envases_devueltos']) ? (int)$infoPago['envases_devueltos'] : 0;
                                                $descuentoEnvases = isset($infoPago['descuento_envases']) ? (float)$infoPago['descuento_envases'] : 0;
                                                $subtotalSinDescuento = isset($infoPago['subtotal_sin_descuento']) ? (float)$infoPago['subtotal_sin_descuento'] : 0;
                                                
                                                // Si no hay subtotal_sin_descuento guardado, usar el subtotal de productos
                                                if ($subtotalSinDescuento == 0 || $subtotalSinDescuento == $order['total']) {
                                                    $subtotalSinDescuento = $subtotalProductos;
                                                }
                                                
                                                // Si hay diferencia entre subtotal y total, y no hay descuento guardado, calcularlo
                                                if ($descuentoEnvases == 0 && $subtotalSinDescuento > $order['total']) {
                                                    $descuentoEnvases = $subtotalSinDescuento - $order['total'];
                                                    // Si el descuento es múltiplo de 2000, calcular cantidad de envases
                                                    if ($descuentoEnvases > 0 && $descuentoEnvases % 2000 == 0) {
                                                        $envasesDevueltos = $descuentoEnvases / 2000;
                                                    }
                                                }
                                                ?>
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                                    <td><strong><?php echo formatPrice($subtotalSinDescuento); ?></strong></td>
                                                </tr>
                                                <?php if ($descuentoEnvases > 0): ?>
                                                <tr style="color: #28a745;">
                                                    <td colspan="3" class="text-end">
                                                        <strong>Descuento por envases devueltos (<?php echo $envasesDevueltos; ?>):</strong>
                                                    </td>
                                                    <td><strong>-<?php echo formatPrice($descuentoEnvases); ?></strong></td>
                                                </tr>
                                                <?php endif; ?>
                                                <tr>
                                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                                    <td><strong class="text-primary"><?php echo formatPrice($order['total']); ?></strong></td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                    <?php else: ?>
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>No se encontraron productos para este pedido.
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if (isset($order['metodo_pago']) && !empty($order['metodo_pago'])): ?>
                                    <div class="mt-3">
                                        <p class="mb-1"><strong>Método de pago:</strong></p>
                                        <span class="badge bg-secondary">
                                            <?php 
                                            $metodos = [
                                                'efectivo' => 'Efectivo contra entrega',
                                                'transferencia' => 'Transferencia bancaria',
                                                'nequi' => 'Nequi',
                                                'bancolombia' => 'Bancolombia Ahorro a la Mano'
                                            ];
                                            echo $metodos[$order['metodo_pago']] ?? ucfirst($order['metodo_pago']);
                                            ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
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

function printOrder(orderId) {
    window.open('<?php echo SITE_URL; ?>profile/orders/print/' + orderId, '_blank');
}
</script>

<?php include 'views/layout/footer.php'; ?>
