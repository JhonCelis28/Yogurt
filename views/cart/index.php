<?php 
$title = 'Carrito de Compras';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Mi Carrito de Compras</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($cartItems)): ?>
                        <?php foreach ($cartItems as $item): ?>
                        <div class="row align-items-center border-bottom py-3">
                            <div class="col-md-2">
                                <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $item['imagen'] ?: 'default.jpg'; ?>" 
                                     alt="<?php echo $item['nombre']; ?>" class="img-fluid rounded">
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1"><?php echo $item['nombre']; ?></h6>
                                <?php if ($item['personalizaciones']): ?>
                                    <?php $personalizaciones = json_decode($item['personalizaciones'], true); ?>
                                    <small class="text-muted">
                                        <?php if (isset($personalizaciones['sabor'])): ?>
                                            <span class="badge bg-light text-dark me-1">Sabor: <?php echo ucfirst($personalizaciones['sabor']); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($personalizaciones['endulzante'])): ?>
                                            <span class="badge bg-light text-dark me-1">Endulzante: <?php echo ucfirst(str_replace('_', ' ', $personalizaciones['endulzante'])); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($personalizaciones['nivel_dulzor'])): ?>
                                            <span class="badge bg-light text-dark me-1">Dulzor: <?php echo ucfirst($personalizaciones['nivel_dulzor']); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($personalizaciones['tamaño'])): ?>
                                            <span class="badge bg-light text-dark me-1">Tamaño: <?php echo ucfirst($personalizaciones['tamaño']); ?></span>
                                        <?php endif; ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="fw-bold" data-price-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['precio']; ?>"><?php echo formatPrice($item['precio']); ?></span>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                                    <input type="text" class="form-control text-center" value="<?php echo $item['cantidad']; ?>" data-cart-id="<?php echo $item['id']; ?>" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                                </div>
                            </div>
                            <div class="col-md-1 text-center">
                                <span class="fw-bold" data-subtotal-id="<?php echo $item['id']; ?>"><?php echo formatPrice($item['precio'] * $item['cantidad']); ?></span>
                            </div>
                            <div class="col-md-1 text-center">
                                <a href="<?php echo SITE_URL; ?>cart/remove/<?php echo $item['id']; ?>" 
                                   class="btn btn-outline-danger btn-sm" 
                                   onclick="return confirm('¿Estás seguro de eliminar este producto?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="text-end mt-3">
                            <a href="<?php echo SITE_URL; ?>cart/clear" 
                               class="btn btn-outline-secondary me-2"
                               onclick="return confirm('¿Estás seguro de vaciar el carrito?')">
                                <i class="fas fa-trash me-2"></i>Vaciar Carrito
                            </a>
                            <a href="<?php echo SITE_URL; ?>products" class="btn btn-outline-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Seguir Comprando
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Tu carrito está vacío</h4>
                            <p class="text-muted">Agrega algunos productos para comenzar</p>
                            <a href="<?php echo SITE_URL; ?>products" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Ir a Productos
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($cartItems)): ?>
        <div class="col-lg-4">
            <!-- Resumen del pedido -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="cart-total"><?php echo formatPrice($total); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Envío:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-primary-custom"><?php echo formatPrice($total); ?></strong>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="<?php echo SITE_URL; ?>checkout" class="btn btn-primary btn-lg">
                            <i class="fas fa-credit-card me-2"></i>Proceder al Pago
                        </a>
                        <button onclick="contactWhatsApp('Hola, quiero hacer un pedido. Mi carrito tiene un total de <?php echo formatPrice($total); ?>')" 
                                class="btn btn-success">
                            <i class="fab fa-whatsapp me-2"></i>Pedir por WhatsApp
                        </button>
                    </div>
                </div>
            </div>

            <!-- Promociones disponibles -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-gift me-2"></i>Promociones Disponibles</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-star text-warning me-2"></i>
                            <strong>Primera Compra</strong>
                        </div>
                        <small class="text-muted">15% de descuento en tu primera compra</small>
                    </div>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-recycle text-success me-2"></i>
                            <strong>Envase Devuelto</strong>
                        </div>
                        <small class="text-muted">$2.000 de descuento por cada envase de vidrio devuelto</small>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="envaseDevuelto">
                        <label class="form-check-label" for="envaseDevuelto">
                            Tengo envases para devolver
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
