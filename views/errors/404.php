<?php include 'views/layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="mb-4">
                <i class="fas fa-exclamation-triangle fa-5x text-warning"></i>
            </div>
            <h1 class="display-4 text-primary-custom">404</h1>
            <h2 class="mb-4">Página no encontrada</h2>
            <p class="lead mb-4">
                Lo sentimos, la página que buscas no existe o ha sido movida.
            </p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Ir al Inicio
                </a>
                <a href="<?php echo SITE_URL; ?>products" class="btn btn-outline-primary">
                    <i class="fas fa-shopping-bag me-2"></i>Ver Productos
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
