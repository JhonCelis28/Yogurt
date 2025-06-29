<?php 
$title = 'Finalizar Compra';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Resumen del Pedido</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cartItems as $item): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $item['imagen'] ?: 'default.jpg'; ?>" 
                                                 alt="<?php echo $item['nombre']; ?>" class="me-2" style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0"><?php echo $item['nombre']; ?></h6>
                                                <?php if ($item['personalizaciones']): ?>
                                                    <?php $personalizaciones = json_decode($item['personalizaciones'], true); ?>
                                                    <small class="text-muted">
                                                        <?php if (isset($personalizaciones['sabor'])): ?>
                                                            Sabor: <?php echo ucfirst($personalizaciones['sabor']); ?>,
                                                        <?php endif; ?>
                                                        <?php if (isset($personalizaciones['endulzante'])): ?>
                                                            Endulzante: <?php echo ucfirst(str_replace('_', ' ', $personalizaciones['endulzante'])); ?>
                                                        <?php endif; ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td><?php echo formatPrice($item['precio']); ?></td>
                                    <td><?php echo $item['cantidad']; ?></td>
                                    <td class="text-end"><?php echo formatPrice($item['precio'] * $item['cantidad']); ?></td>
                                </tr>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                    <td class="text-end"><strong><?php echo formatPrice($subtotal); ?></strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <form method="POST" action="<?php echo SITE_URL; ?>checkout/process">
                <div class="card mb-4">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Información de Entrega</h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="direccion_entrega" class="form-label">Dirección de Entrega *</label>
                            <textarea class="form-control" id="direccion_entrega" name="direccion_entrega" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="telefono_contacto" class="form-label">Teléfono de Contacto *</label>
                            <input type="tel" class="form-control" id="telefono_contacto" name="telefono_contacto" required>
                        </div>
                        <div class="mb-3">
                            <label for="notas" class="form-label">Notas Adicionales</label>
                            <textarea class="form-control" id="notas" name="notas" rows="2" placeholder="Instrucciones especiales para la entrega, etc."></textarea>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0"><i class="fas fa-credit-card me-2"></i>Método de Pago</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="efectivo" value="efectivo" checked>
                            <label class="form-check-label" for="efectivo">
                                <i class="fas fa-money-bill-wave me-2"></i>Efectivo contra entrega
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="metodo_pago" id="transferencia" value="transferencia">
                            <label class="form-check-label" for="transferencia">
                                <i class="fas fa-university me-2"></i>Transferencia bancaria
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fas fa-check-circle me-2"></i>Confirmar Pedido
                    </button>
                    <a href="<?php echo SITE_URL; ?>cart" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Volver al Carrito
                    </a>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Resumen</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span><?php echo formatPrice($subtotal); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Envío:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-primary-custom"><?php echo formatPrice($subtotal); ?></strong>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class="fas fa-gift me-2"></i>Código Promocional</h5>
                </div>
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Código promocional" id="promo_code">
                        <button class="btn btn-outline-warning" type="button" id="apply_promo">Aplicar</button>
                    </div>
                    <div id="promo_message"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-shield-alt me-2 text-success"></i>Compra Segura</h5>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Productos 100% naturales</li>
                        <li><i class="fas fa-check text-success me-2"></i>Entrega a domicilio</li>
                        <li><i class="fas fa-check text-success me-2"></i>Pago contra entrega</li>
                        <li><i class="fas fa-check text-success me-2"></i>Satisfacción garantizada</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('apply_promo').addEventListener('click', function() {
    const promoCode = document.getElementById('promo_code').value;
    const promoMessage = document.getElementById('promo_message');
    
    if (promoCode.trim() === '') {
        promoMessage.innerHTML = '<div class="alert alert-warning">Por favor ingresa un código promocional</div>';
        return;
    }
    
    // Simulación de aplicación de código promocional
    if (promoCode.toLowerCase() === 'primera15') {
        promoMessage.innerHTML = '<div class="alert alert-success">¡Código aplicado! 15% de descuento</div>';
    } else if (promoCode.toLowerCase() === 'familia20') {
        promoMessage.innerHTML = '<div class="alert alert-success">¡Código aplicado! 20% de descuento</div>';
    } else {
        promoMessage.innerHTML = '<div class="alert alert-danger">Código promocional inválido</div>';
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>
