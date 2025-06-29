<?php 
$title = 'Iniciar Sesión';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0"><i class="fas fa-user me-2"></i>Iniciar Sesión</h3>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="<?php echo SITE_URL; ?>login">
                        <div class="mb-4">
                            <label for="email" class="form-label">Email *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label">Contraseña *</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <button class="btn btn-outline-secondary" type="button" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mb-4">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                            </button>
                        </div>
                        
                        <div class="text-center">
                            <p class="mb-0">¿No tienes cuenta? 
                                <a href="<?php echo SITE_URL; ?>register" class="text-primary-custom text-decoration-none">
                                    Regístrate aquí
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Beneficios de registrarse -->
            <div class="card mt-4 bg-light-pink border-0">
                <div class="card-body text-center">
                    <h6 class="text-primary-custom mb-3">Beneficios de tener cuenta</h6>
                    <div class="row g-3">
                        <div class="col-4">
                            <i class="fas fa-gift text-primary-custom fa-2x mb-2"></i>
                            <small class="d-block">Promociones Exclusivas</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-history text-primary-custom fa-2x mb-2"></i>
                            <small class="d-block">Historial de Pedidos</small>
                        </div>
                        <div class="col-4">
                            <i class="fas fa-shipping-fast text-primary-custom fa-2x mb-2"></i>
                            <small class="d-block">Compra Rápida</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>
