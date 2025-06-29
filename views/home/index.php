<?php
$title = 'Inicio';
include 'views/layout/header.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold text-primary-custom mb-4">
                        Productos Artesanales
                        <span class="text-green-custom">100% Naturales</span>
                    </h1>
                    <p class="lead mb-4 text-muted">
                        Descubre el sabor auténtico de nuestros yogures y tortas artesanales,
                        elaborados con amor y los mejores ingredientes naturales de la región.
                    </p>
                    <div class="d-flex gap-3 flex-wrap">
                        <a href="<?php echo SITE_URL; ?>products" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Ver Productos
                        </a>
                        <a href="<?php echo SITE_URL; ?>products/personalized" class="btn btn-success btn-lg">
                            <i class="fas fa-palette me-2"></i>Personalizar
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 text-center">
                <div class="hero-image">
                    <img src="foto3.png"
                        alt="Yogures Artesanales"
                        class="img-fluid rounded-5"
                        style="max-height: 800px; object-fit: cover; margin-left: 120px;">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Características -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-card p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-leaf fa-3x text-green-custom"></i>
                    </div>
                    <h5 class="text-primary-custom">100% Natural</h5>
                    <p class="text-muted">Sin conservantes ni aditivos artificiales</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-card p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-heart fa-3x text-primary-custom"></i>
                    </div>
                    <h5 class="text-primary-custom">Hecho con Amor</h5>
                    <p class="text-muted">Cada producto elaborado artesanalmente</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-card p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-recycle fa-3x text-green-custom"></i>
                    </div>
                    <h5 class="text-primary-custom">Eco-Friendly</h5>
                    <p class="text-muted">Envases reutilizables y sostenibles</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 text-center">
                <div class="feature-card p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-truck fa-3x text-primary-custom"></i>
                    </div>
                    <h5 class="text-primary-custom">Entrega Fresca</h5>
                    <p class="text-muted">Productos frescos directo a tu hogar</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Productos Destacados -->
<section class="py-5 bg-light-pink">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-primary-custom">Productos Destacados</h2>
            <p class="lead text-muted">Descubre nuestros productos más populares</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($featuredProducts)): ?>
                <?php foreach ($featuredProducts as $product): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="product-card card h-100">
                            <div class="position-relative">
                                <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $product['imagen'] ?: 'default.jpg'; ?>"
                                    class="card-img-top" alt="<?php echo $product['nombre']; ?>"
                                    style="height: 250px; object-fit: cover;">
                                <?php if ($product['es_personalizable']): ?>
                                    <span class="badge bg-success position-absolute top-0 end-0 m-3">
                                        <i class="fas fa-palette me-1"></i>Personalizable
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title text-primary-custom"><?php echo $product['nombre']; ?></h5>
                                <p class="card-text text-muted small"><?php echo $product['categoria_nombre']; ?></p>
                                <p class="card-text flex-grow-1"><?php echo substr($product['descripcion'], 0, 100) . '...'; ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                    <span class="h5 text-primary-custom mb-0"><?php echo formatPrice($product['precio']); ?></span>
                                    <div>
                                        <a href="<?php echo SITE_URL; ?>products/show/<?php echo $product['id']; ?>"
                                            class="btn btn-outline-primary btn-sm me-2">Ver</a>
                                        <?php if (isLoggedIn()): ?>
                                            <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-primary btn-sm">
                                                <i class="fas fa-cart-plus"></i>
                                            </button>
                                        <?php else: ?>
                                            <a href="<?php echo SITE_URL; ?>login" class="btn btn-primary btn-sm">
                                                <i class="fas fa-cart-plus"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p class="text-muted">No hay productos destacados disponibles.</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5">
            <a href="<?php echo SITE_URL; ?>products" class="btn btn-primary btn-lg">
                <i class="fas fa-eye me-2"></i>Ver Todos los Productos
            </a>
        </div>
    </div>
</section>

<!-- Categorías -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-5 fw-bold text-primary-custom">Nuestras Categorías</h2>
            <p class="lead text-muted">Explora nuestra variedad de productos artesanales</p>
        </div>

        <div class="row g-4">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $category): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card category-card h-100 border-0 shadow-sm">
                            <div class="card-body text-center p-4">
                                <div class="category-icon mb-3">
                                    <i class="fas fa-tag fa-3x text-primary-custom"></i>
                                </div>
                                <h5 class="card-title text-primary-custom"><?php echo $category['nombre']; ?></h5>
                                <p class="card-text text-muted"><?php echo $category['descripcion']; ?></p>
                                <a href="<?php echo SITE_URL; ?>products/category/<?php echo $category['id']; ?>"
                                    class="btn btn-outline-primary">
                                    <i class="fas fa-arrow-right me-2"></i>Explorar
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- Promoción Especial -->
<section class="py-5 bg-soft-purple">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="display-5 fw-bold text-primary-custom mb-4">¡Oferta Especial!</h2>
                <p class="lead mb-4">
                    <strong>15% de descuento</strong> en tu primera compra.
                    Únete a nuestra familia de productos naturales y saludables.
                </p>
                <div class="d-flex gap-3">
                    <?php if (!isLoggedIn()): ?>
                        <a href="<?php echo SITE_URL; ?>register" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-plus me-2"></i>Registrarse Ahora
                        </a>
                    <?php else: ?>
                        <a href="<?php echo SITE_URL; ?>products" class="btn btn-primary btn-lg">
                            <i class="fas fa-shopping-bag me-2"></i>Comprar Ahora
                        </a>
                    <?php endif; ?>
                    <button onclick="contactWhatsApp('Hola, me interesa la oferta del 15% de descuento')"
                        class="btn btn-success btn-lg">
                        <i class="fab fa-whatsapp me-2"></i>Consultar
                    </button>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="promo-badge">
                    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
                    <dotlottie-player src="https://lottie.host/f0c81153-ab5e-4acd-b766-3c8263782e36/DB3bixVDAD.lottie" background="transparent" speed="1" style="width: 200px; height: 200px; margin-left: 210px; padding: 5px;" loop autoplay></dotlottie-player>
                    <h3 class="text-primary-custom">15% OFF</h3>
                    <p class="text-muted">Primera Compra</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'views/layout/footer.php'; ?>