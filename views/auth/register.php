<?php 
$title = 'Registrarse';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center py-4">
                    <h3 class="mb-0"><i class="fas fa-user-plus me-2"></i>Crear Cuenta</h3>
                    <p class="mb-0 mt-2">¡Únete a nuestra familia de productos naturales!</p>
                </div>
                <div class="card-body p-5">
                    <form method="POST" action="<?php echo SITE_URL; ?>register">
                        <div class="row g-3">
                            <div class="col-12">
                                <label for="nombre" class="form-label">Nombre Completo *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="email" class="form-label">Email *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Ej: 3001234567">
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="direccion" class="form-label">Dirección</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <textarea class="form-control" id="direccion" name="direccion" rows="2" placeholder="Dirección completa para entregas"></textarea>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <label for="password" class="form-label">Contraseña *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" required minlength="6">
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                        <i class="fas fa-eye" id="toggleIcon1"></i>
                                    </button>
                                </div>
                                <small class="text-muted">Mínimo 6 caracteres</small>
                            </div>
                            
                            <div class="col-12">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña *</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('confirm_password')">
                                        <i class="fas fa-eye" id="toggleIcon2"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        Acepto los <a href="#" class="text-primary-custom">términos y condiciones</a> y la <a href="#" class="text-primary-custom">política de privacidad</a>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="newsletter">
                                    <label class="form-check-label" for="newsletter">
                                        Quiero recibir promociones y novedades por email
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-user-plus me-2"></i>Crear Mi Cuenta
                            </button>
                        </div>
                        
                        <div class="text-center mt-4">
                            <p class="mb-0">¿Ya tienes cuenta? 
                                <a href="<?php echo SITE_URL; ?>login" class="text-primary-custom text-decoration-none">
                                    Inicia sesión aquí
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Promoción primera compra -->
            <div class="card mt-4 bg-warning bg-opacity-25 border-warning">
                <div class="card-body text-center">
                    <i class="fas fa-gift text-warning fa-3x mb-3"></i>
                    <h5 class="text-warning">¡Oferta Especial!</h5>
                    <p class="mb-0">Obtén <strong>15% de descuento</strong> en tu primera compra al registrarte</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(fieldId) {
    const passwordInput = document.getElementById(fieldId);
    const toggleIcon = document.getElementById(fieldId === 'password' ? 'toggleIcon1' : 'toggleIcon2');
    
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

// Validar que las contraseñas coincidan
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (password !== confirmPassword) {
        this.setCustomValidity('Las contraseñas no coinciden');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>
