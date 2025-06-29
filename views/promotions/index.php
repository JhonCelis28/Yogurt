<?php 
$title = 'Promociones';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 text-primary-custom">Promociones Especiales</h1>
        <p class="lead">¡Aprovecha nuestras ofertas exclusivas!</p>
    </div>

    <div class="row g-4">
        <!-- Promoción Primera Compra -->
        <div class="col-lg-6">
            <div class="card h-100 border-warning shadow-lg">
                <div class="card-header bg-warning text-dark text-center">
                    <h4><i class="fas fa-star me-2"></i>Primera Compra</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-gift fa-4x text-warning mb-3"></i>
                        <h2 class="text-warning">15% OFF</h2>
                        <p class="lead">En tu primera compra</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Condiciones:</h6>
                        <ul class="list-unstyled text-muted">
                            <li><i class="fas fa-check text-success me-2"></i>Solo para nuevos usuarios</li>
                            <li><i class="fas fa-check text-success me-2"></i>Compra mínima $30.000</li>
                            <li><i class="fas fa-check text-success me-2"></i>Válido hasta fin de mes</li>
                        </ul>
                    </div>
                    
                    <?php if (!isLoggedIn()): ?>
                        <a href="<?php echo SITE_URL; ?>register" class="btn btn-warning btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Registrarse Ahora
                        </a>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>products" class="btn btn-warning btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Comprar Ahora
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Promoción Envase Devuelto -->
        <div class="col-lg-6">
            <div class="card h-100 border-success shadow-lg">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-recycle me-2"></i>Envase Devuelto</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-leaf fa-4x text-success mb-3"></i>
                        <h2 class="text-success">$2.000</h2>
                        <p class="lead">Por cada envase de vidrio devuelto</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Beneficios:</h6>
                        <ul class="list-unstyled text-muted">
                            <li><i class="fas fa-check text-success me-2"></i>Cuidas el medio ambiente</li>
                            <li><i class="fas fa-check text-success me-2"></i>Ahorras en tu próxima compra</li>
                            <li><i class="fas fa-check text-success me-2"></i>Sin límite de envases</li>
                        </ul>
                    </div>
                    
                    <button onclick="contactWhatsApp('Hola, quiero información sobre el descuento por envase devuelto')" 
                            class="btn btn-success btn-lg">
                        <i class="fab fa-whatsapp me-2"></i>Más Información
                    </button>
                </div>
            </div>
        </div>

        <!-- Combo Familiar -->
        <div class="col-lg-6">
            <div class="card h-100 border-primary shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="fas fa-users me-2"></i>Combo Familiar</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-home fa-4x text-primary mb-3"></i>
                        <h2 class="text-primary">20% OFF</h2>
                        <p class="lead">En compras superiores a $80.000</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Incluye:</h6>
                        <ul class="list-unstyled text-muted">
                            <li><i class="fas fa-check text-success me-2"></i>3 Yogures de 1L</li>
                            <li><i class="fas fa-check text-success me-2"></i>1 Torta mediana</li>
                            <li><i class="fas fa-check text-success me-2"></i>Envío gratis</li>
                        </ul>
                    </div>
                    
                    <a href="<?php echo SITE_URL; ?>products" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-cart me-2"></i>Ver Productos
                    </a>
                </div>
            </div>
        </div>

        <!-- Promoción Fin de Semana -->
        <div class="col-lg-6">
            <div class="card h-100 border-info shadow-lg">
                <div class="card-header bg-info text-white text-center">
                    <h4><i class="fas fa-calendar-weekend me-2"></i>Fin de Semana</h4>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="fas fa-clock fa-4x text-info mb-3"></i>
                        <h2 class="text-info">2x1</h2>
                        <p class="lead">En postres de yogurt griego</p>
                    </div>
                    
                    <div class="mb-4">
                        <h6>Válido:</h6>
                        <ul class="list-unstyled text-muted">
                            <li><i class="fas fa-check text-success me-2"></i>Sábados y domingos</li>
                            <li><i class="fas fa-check text-success me-2"></i>Solo postres de yogurt griego</li>
                            <li><i class="fas fa-check text-success me-2"></i>Máximo 4 unidades</li>
                        </ul>
                    </div>
                    
                    <a href="<?php echo SITE_URL; ?>products/category/3" class="btn btn-info btn-lg">
                        <i class="fas fa-ice-cream me-2"></i>Ver Postres
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección de suscripción -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light-pink border-0">
                <div class="card-body text-center py-5">
                    <h3 class="text-primary-custom mb-3">¡No te pierdas nuestras promociones!</h3>
                    <p class="lead mb-4">Suscríbete para recibir ofertas exclusivas y novedades</p>
                    
                    <div class="row justify-content-center">
                        <div class="col-lg-6">
                            <form class="d-flex gap-2">
                                <input type="email" class="form-control" placeholder="Tu email">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
