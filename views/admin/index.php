<?php 
$title = 'Panel de Administración';
include 'views/layout/header.php'; 
?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 col-md-3">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h6 class="mb-0"><i class="fas fa-cog me-2"></i>Panel Admin</h6>
                </div>
                <div class="list-group list-group-flush">
                    <a href="<?php echo SITE_URL; ?>admin" class="list-group-item list-group-item-action active">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/products" class="list-group-item list-group-item-action">
                        <i class="fas fa-box me-2"></i>Productos
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/categories" class="list-group-item list-group-item-action">
                        <i class="fas fa-tags me-2"></i>Categorías
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/orders" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-cart me-2"></i>Pedidos
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/users" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Usuarios
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/promotions" class="list-group-item list-group-item-action">
                        <i class="fas fa-gift me-2"></i>Promociones
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/contacts" class="list-group-item list-group-item-action">
                        <i class="fas fa-envelope me-2"></i>Contactos
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary-custom">Dashboard</h2>
                <div class="text-muted">
                    <i class="fas fa-calendar me-2"></i><?php echo date('d/m/Y H:i'); ?>
                </div>
            </div>

            <!-- Estadísticas -->
            <div class="row g-4 mb-5">
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Productos</h6>
                                    <h3 class="mb-0">45</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-box fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Pedidos Hoy</h6>
                                    <h3 class="mb-0">12</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Usuarios</h6>
                                    <h3 class="mb-0">156</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6 class="card-title">Ventas Mes</h6>
                                    <h3 class="mb-0">$2.5M</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-2x opacity-75"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pedidos Recientes -->
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Pedidos Recientes</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th>Fecha</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>#001</td>
                                            <td>María García</td>
                                            <td>$45.000</td>
                                            <td><span class="badge bg-warning">Pendiente</span></td>
                                            <td>10/06/2024</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">Ver</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#002</td>
                                            <td>Carlos López</td>
                                            <td>$32.000</td>
                                            <td><span class="badge bg-success">Confirmado</span></td>
                                            <td>10/06/2024</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">Ver</button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>#003</td>
                                            <td>Ana Rodríguez</td>
                                            <td>$28.500</td>
                                            <td><span class="badge bg-info">Preparando</span></td>
                                            <td>09/06/2024</td>
                                            <td>
                                                <button class="btn btn-sm btn-outline-primary">Ver</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Productos Más Vendidos</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">Yogurt Griego Natural</h6>
                                    <small class="text-muted">25 unidades</small>
                                </div>
                                <span class="badge bg-primary">1°</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">Yogurt con Fresa</h6>
                                    <small class="text-muted">18 unidades</small>
                                </div>
                                <span class="badge bg-secondary">2°</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="mb-0">Torta de Zanahoria</h6>
                                    <small class="text-muted">12 unidades</small>
                                </div>
                                <span class="badge bg-warning">3°</span>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">Acciones Rápidas</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="<?php echo SITE_URL; ?>admin/products/add" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Agregar Producto
                                </a>
                                <a href="<?php echo SITE_URL; ?>admin/promotions/add" class="btn btn-success">
                                    <i class="fas fa-gift me-2"></i>Nueva Promoción
                                </a>
                                <a href="<?php echo SITE_URL; ?>admin/orders" class="btn btn-warning">
                                    <i class="fas fa-eye me-2"></i>Ver Pedidos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
