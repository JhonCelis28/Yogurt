<?php 
$title = 'Nosotros';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <!-- Hero Section -->
    <div class="row align-items-center mb-5">
        <div class="col-lg-6">
            <h1 class="display-4 text-primary-custom mb-4">Nuestra Historia</h1>
            <p class="lead">
                Yogurt Artesanal San Francisco nació del amor por los productos naturales y el compromiso 
                con la salud de nuestras familias y el cuidado del medio ambiente.
            </p>
            <p>
                Desde 2018, hemos estado elaborando productos 100% naturales, sin conservantes ni aditivos 
                artificiales, utilizando únicamente ingredientes frescos y de la más alta calidad.
            </p>
        </div>
        <div class="col-lg-6 text-center">
            <img src="yogurt.jpg" alt="Nuestra Historia" class="img-fluid rounded-5" style="width: 500px; height: auto; box-shadow: 0 2px 8px rgba(0, 0, 0, 8); margin-top: 0;">
        </div>
    </div>

    <!-- Misión, Visión, Valores -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4">
            <div class="card h-100 text-center border-primary">
                <div class="card-body">
                    <i class="fas fa-bullseye fa-3x text-primary-custom mb-3"></i>
                    <h4 class="text-primary-custom">Misión</h4>
                    <p>
                        Ofrecer productos lácteos artesanales de la más alta calidad, elaborados con 
                        ingredientes naturales, promoviendo la salud y el bienestar de nuestros clientes 
                        mientras cuidamos el medio ambiente.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card h-100 text-center border-success">
                <div class="card-body">
                    <i class="fas fa-eye fa-3x text-green-custom mb-3"></i>
                    <h4 class="text-green-custom">Visión</h4>
                    <p>
                        Ser la marca líder en productos lácteos artesanales en Colombia, reconocida por 
                        nuestra calidad, compromiso ambiental y contribución al bienestar de las familias 
                        colombianas.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card h-100 text-center border-warning">
                <div class="card-body">
                    <i class="fas fa-heart fa-3x text-warning mb-3"></i>
                    <h4 class="text-warning">Valores</h4>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-check text-success me-2"></i>Calidad</li>
                        <li><i class="fas fa-check text-success me-2"></i>Naturalidad</li>
                        <li><i class="fas fa-check text-success me-2"></i>Sostenibilidad</li>
                        <li><i class="fas fa-check text-success me-2"></i>Compromiso</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Nuestro Proceso -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-5">
                <h2 class="display-5 text-primary-custom">Nuestro Proceso Artesanal</h2>
                <p class="lead">Cada producto es elaborado con dedicación y amor</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mb-3">
                        <i class="fas fa-seedling fa-3x text-success"></i>
                    </div>
                    <h5>1. Ingredientes Frescos</h5>
                    <p class="text-muted">Seleccionamos cuidadosamente los mejores ingredientes naturales y frescos.</p>
                </div>
                
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mb-3">
                        <i class="fas fa-hands fa-3x text-primary-custom"></i>
                    </div>
                    <h5>2. Elaboración Artesanal</h5>
                    <p class="text-muted">Cada producto es elaborado a mano siguiendo recetas tradicionales.</p>
                </div>
                
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mb-3">
                        <i class="fas fa-shield-alt fa-3x text-warning"></i>
                    </div>
                    <h5>3. Control de Calidad</h5>
                    <p class="text-muted">Rigurosos controles de calidad en cada etapa del proceso.</p>
                </div>
                
                <div class="col-lg-3 col-md-6 text-center">
                    <div class="mb-3">
                        <i class="fas fa-truck fa-3x text-info"></i>
                    </div>
                    <h5>4. Entrega Fresca</h5>
                    <p class="text-muted">Productos frescos entregados directamente a tu hogar.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Compromiso Ambiental -->
    <div class="row align-items-center mb-5 bg-light-pink rounded p-5">
        <div class="col-lg-6">
            <img src="foto.jpg" alt="Compromiso Ambiental" class="img-fluid rounded" style="width: 300px; margin-left: 80px;box-shadow: 0 2px 8px rgba(0, 0, 0, 8);">
        </div>
        <div class="col-lg-6">
            <h3 class="text-green-custom mb-4">Compromiso Ambiental</h3>
            <p>
                En Yogurt Artesanal San Francisco, creemos que cuidar el planeta es responsabilidad de todos. 
                Por eso, hemos implementado prácticas sostenibles en todo nuestro proceso:
            </p>
            <ul class="list-unstyled">
                <li class="mb-2"><i class="fas fa-leaf text-success me-2"></i>Envases de vidrio reutilizables</li>
                <li class="mb-2"><i class="fas fa-recycle text-success me-2"></i>Empaques de cartón reciclable</li>
                <li class="mb-2"><i class="fas fa-water text-success me-2"></i>Uso responsable del agua</li>
                <li class="mb-2"><i class="fas fa-solar-panel text-success me-2"></i>Energía renovable en producción</li>
                <li class="mb-2"><i class="fas fa-tree text-success me-2"></i>Programa de reforestación</li>
            </ul>
        </div>
    </div>

    <!-- Equipo -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center mb-5">
                <h2 class="display-5 text-primary-custom">Nuestro Equipo</h2>
                <p class="lead">Las personas que hacen posible la magia</p>
            </div>
            
            <div class="row g-4">
                <div class="col-lg-4 col-md-6 text-center">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <img src="<?php echo SITE_URL; ?>assets/images/team/founder.jpg" alt="Fundadora" class="rounded-circle mb-3" width="120" height="120">
                            <h5>Bellanira Quintero</h5>
                            <p class="text-muted">Fundadora y Maestra Artesana</p>
                            <p class="small">Con más de 15 años de experiencia en productos lácteos artesanales.</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 text-center">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <img src="jhoncelis.jpg" alt="Chef" class="rounded-circle mb-3" width="120" height="120">
                            <h5>Jhon Celis</h5>
                            <p class="text-muted">Desarrollador y diseñador</p>
                            <p class="small">Diseñador software Web, gestion en redes sociales y ventas</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4 col-md-6 text-center">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <img src="<?php echo SITE_URL; ?>assets/images/team/quality.jpg" alt="Control de Calidad" class="rounded-circle mb-3" width="120" height="120">
                            <h5>Daniel Celis</h5>
                            <p class="text-muted">Control de Calidad</p>
                            <p class="small">Garantiza que cada producto cumpla con nuestros altos estándares.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Certificaciones -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body text-center py-5">
                    <h3 class="text-primary-custom mb-4">Certificaciones y Reconocimientos</h3>
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <i class="fas fa-certificate fa-3x text-warning mb-2"></i>
                            <h6>INVIMA</h6>
                            <small class="text-muted">Registro Sanitario</small>
                        </div>
                        <div class="col-md-3">
                            <i class="fas fa-leaf fa-3x text-success mb-2"></i>
                            <h6>Orgánico</h6>
                            <small class="text-muted">Certificación Orgánica</small>
                        </div>
                        <div class="col-md-3">
                            <i class="fas fa-award fa-3x text-primary mb-2"></i>
                            <h6>Calidad</h6>
                            <small class="text-muted">ISO 9001</small>
                        </div>
                        <div class="col-md-3">
                            <i class="fas fa-handshake fa-3x text-info mb-2"></i>
                            <h6>Comercio Justo</h6>
                            <small class="text-muted">Fair Trade</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
