<?php 
$title = 'Mi Perfil';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar del perfil -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-4x text-primary-custom"></i>
                    </div>
                    <h5><?php echo $_SESSION['user_name']; ?></h5>
                    <p class="text-muted"><?php echo $_SESSION['user_email']; ?></p>
                </div>
            </div>
            
            <div class="list-group mt-3">
                <a href="<?php echo SITE_URL; ?>profile" class="list-group-item list-group-item-action active">
                    <i class="fas fa-user me-2"></i>Mi Perfil
                </a>
                <a href="<?php echo SITE_URL; ?>profile/orders" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-bag me-2"></i>Mis Pedidos
                </a>
                <a href="<?php echo SITE_URL; ?>profile/addresses" class="list-group-item list-group-item-action">
                    <i class="fas fa-map-marker-alt me-2"></i>Direcciones
                </a>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-user me-2"></i>Información Personal</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo SITE_URL; ?>profile/update">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $_SESSION['user_name']; ?>">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['user_email']; ?>" readonly>
                            </div>
                            
                            <div class="col-md-6">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="3001234567">
                            </div>
                            
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                            </div>
                            
                            <div class="col-12">
                                <label for="direccion" class="form-label">Dirección Principal</label>
                                <textarea class="form-control" id="direccion" name="direccion" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Guardar Cambios
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Cambiar contraseña -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i>Cambiar Contraseña</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo SITE_URL; ?>profile/change-password">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="current_password" class="form-label">Contraseña Actual</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="new_password" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6">
                            </div>
                            
                            <div class="col-md-4">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>Cambiar Contraseña
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Estadísticas del usuario -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card text-center bg-primary text-white">
                        <div class="card-body">
                            <i class="fas fa-shopping-bag fa-2x mb-2"></i>
                            <h4>12</h4>
                            <p class="mb-0">Pedidos Realizados</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card text-center bg-warning text-white">
                        <div class="card-body">
                            <i class="fas fa-recycle fa-2x mb-2"></i>
                            <h4>15</h4>
                            <p class="mb-0">Envases Devueltos</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>
