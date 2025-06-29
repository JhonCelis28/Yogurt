<?php 
$title = 'Pedido Exitoso';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-success">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success fa-5x"></i>
                    </div>
                    <h1 class="display-4 text-success mb-4">¡Pedido Realizado!</h1>
                    <p class="lead">
                        Gracias por tu compra. Tu pedido ha sido recibido y está siendo procesado.
                    </p>
                    <div class="alert alert-light my-4">
                        <div class="row">
                            <div class="col-md-6 text-md-start">
                                <p class="mb-1"><strong>Número de Pedido:</strong></p>
                                <p class="h5">#<?php echo $order['id']; ?></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="mb-1"><strong>Fecha:</strong></p>
                                <p><?php echo formatDateTime($order['fecha_pedido']); ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-start mb-4">
                        <h5 class="mb-3">Resumen del Pedido</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th class="text-end">Cantidad</th>
                                        <th class="text-end">Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($orderItems)): ?>
                                        <?php foreach ($orderItems as $item): ?>
                                        <tr>
                                            <td><?php echo $item['nombre']; ?></td>
                                            <td class="text-end"><?php echo $item['cantidad']; ?></td>
                                            <td class="text-end"><?php echo formatPrice($item['precio_unitario'] * $item['cantidad']); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">Detalles del pedido no disponibles</td>
                                        </tr>
                                    <?php endif; ?>
                                    <tr>
                                        <td colspan="2" class="text-end"><strong>Total:</strong></td>
                                        <td class="text-end"><strong><?php echo formatPrice($order['total']); ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        Te hemos enviado un correo electrónico con los detalles de tu pedido.
                    </div>
                    
                    <div class="d-flex gap-3 justify-content-center">
                        <a href="<?php echo SITE_URL; ?>" class="btn btn-primary btn-lg">
                            <i class="fas fa-home me-2"></i>Volver al Inicio
                        </a>
                        <a href="<?php echo SITE_URL; ?>profile/orders" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-list me-2"></i>Ver Mis Pedidos
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
