<?php include 'views/layout/header.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 text-center">
            <div class="mb-4">
                <i class="fas fa-server fa-5x text-danger"></i>
            </div>
            <h1 class="display-4 text-primary-custom">500</h1>
            <h2 class="mb-4">Error del servidor</h2>
            <p class="lead mb-4">
                Ha ocurrido un error interno. Nuestro equipo ha sido notificado y está trabajando para solucionarlo.
            </p>
            <div class="d-flex gap-3 justify-content-center">
                <a href="<?php echo SITE_URL; ?>" class="btn btn-primary">
                    <i class="fas fa-home me-2"></i>Ir al Inicio
                </a>
                <button onclick="contactWhatsApp('Hola, estoy experimentando problemas técnicos en el sitio web')" 
                        class="btn btn-success">
                    <i class="fab fa-whatsapp me-2"></i>Reportar Problema
                </button>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
