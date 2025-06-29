<?php 
$title = $category['nombre'];
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>products">Productos</a></li>
            <li class="breadcrumb-item active"><?php echo $category['nombre']; ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Sidebar de categorías -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-list me-2"></i>Categorías</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="<?php echo SITE_URL; ?>products" class="list-group-item list-group-item-action">
                            <i class="fas fa-th-large me-2"></i>Todos los Productos
                        </a>
                        <?php foreach ($categories as $cat): ?>
                        <a href="<?php echo SITE_URL; ?>products/category/<?php echo $cat['id']; ?>" 
                           class="list-group-item list-group-item-action <?php echo $cat['id'] == $category['id'] ? 'active' : ''; ?>">
                            <i class="fas fa-tag me-2"></i><?php echo $cat['nombre']; ?>
                        </a>
                        <?php endforeach; ?>
                        <a href="<?php echo SITE_URL; ?>products/personalized" 
                           class="list-group-item list-group-item-action text-success">
                            <i class="fas fa-palette me-2"></i>Productos Personalizados
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos de la categoría -->
        <div class="col-lg-9">
            <div class="mb-4">
                <h2 class="text-primary-custom"><?php echo $category['nombre']; ?></h2>
                <p class="text-muted"><?php echo $category['descripcion']; ?></p>
                <span class="text-muted"><?php echo count($products); ?> productos en esta categoría</span>
            </div>

            <div class="row g-4">
                <?php foreach ($products as $product): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $product['imagen'] ?: 'default.jpg'; ?>" 
                                 class="card-img-top" alt="<?php echo $product['nombre']; ?>" style="height: 250px; object-fit: cover;">
                            <?php if ($product['es_personalizable']): ?>
                                <span class="badge bg-success position-absolute top-0 end-0 m-2">
                                    <i class="fas fa-palette me-1"></i>Personalizable
                                </span>
                            <?php endif; ?>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $product['nombre']; ?></h5>
                            <p class="card-text flex-grow-1"><?php echo substr($product['descripcion'], 0, 100) . '...'; ?></p>
                            <div class="d-flex justify-content-between align-items-center">
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
            </div>

            <?php if (empty($products)): ?>
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No hay productos en esta categoría</h4>
                <p class="text-muted">Explora otras categorías o vuelve pronto</p>
                <a href="<?php echo SITE_URL; ?>products" class="btn btn-primary">Ver Todos los Productos</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
