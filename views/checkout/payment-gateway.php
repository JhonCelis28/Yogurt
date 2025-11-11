<?php 
$title = 'Pasarela de Pago';
include 'views/layout/header.php'; 

// Obtener método de pago desde la URL
$method = $_GET['method'] ?? 'transferencia';
$methodNames = [
    'transferencia' => 'Transferencia Bancaria',
    'nequi' => 'Nequi',
    'bancolombia' => 'Bancolombia Ahorro a la Mano'
];
$methodName = $methodNames[$method] ?? 'Transferencia Bancaria';

// Las variables $subtotal y $total vienen del controlador
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0"><i class="fas fa-lock me-2"></i>Pasarela de Pago Segura</h3>
                    <p class="mb-0 mt-2"><small>Método: <?php echo htmlspecialchars($methodName); ?></small></p>
                </div>
                <div class="card-body p-4">
                    <!-- Resumen del pedido -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading"><i class="fas fa-shopping-cart me-2"></i>Resumen del Pedido</h5>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <strong><?php echo formatPrice($subtotal); ?></strong>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Envío:</span>
                            <span class="text-success">Gratis</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total a Pagar:</strong>
                            <strong class="text-primary"><?php echo formatPrice($total); ?></strong>
                        </div>
                    </div>

                    <?php 
                    // Obtener datos del checkout desde sessionStorage (se pasarán por JavaScript)
                    // Por ahora, obtener desde POST si vienen, o usar valores por defecto
                    $checkoutData = [];
                    if (isset($_POST['checkout_data'])) {
                        $checkoutData = json_decode($_POST['checkout_data'], true) ?? [];
                    }
                    ?>
                    
                    <?php if ($method === 'transferencia'): ?>
                        <!-- Formulario de Transferencia Bancaria -->
                        <form id="paymentForm" method="POST" action="<?php echo SITE_URL; ?>checkout/process-payment">
                            <input type="hidden" name="metodo_pago" value="transferencia">
                            <input type="hidden" name="checkout_data" id="checkout_data_input" value="">
                            
                            <div class="mb-4">
                                <h5 class="mb-3"><i class="fas fa-university me-2"></i>Datos para Transferencia Bancaria</h5>
                                
                                <!-- Información de la cuenta destino -->
                                <div class="card bg-light mb-4">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Cuenta Destino</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="mb-1"><strong>Banco:</strong> Bancolombia</p>
                                        <p class="mb-1"><strong>Número de Cuenta:</strong> 1234567890</p>
                                        <p class="mb-1"><strong>Tipo de Cuenta:</strong> Ahorros</p>
                                        <p class="mb-0"><strong>Titular:</strong> Yogurt Artesanal San Francisco</p>
                                    </div>
                                </div>
                                
                                <!-- Datos de la cuenta origen del cliente -->
                                <h6 class="mb-3"><i class="fas fa-credit-card me-2"></i>Datos de tu Cuenta Bancaria</h6>
                                
                                <div class="mb-3">
                                    <label for="banco_origen" class="form-label">Banco de Origen *</label>
                                    <select class="form-select" id="banco_origen" name="banco_origen" required>
                                        <option value="">Selecciona tu banco</option>
                                        <option value="bancolombia">Bancolombia</option>
                                        <option value="davivienda">Davivienda</option>
                                        <option value="bancodebogota">Banco de Bogotá</option>
                                        <option value="bancoavvillas">Banco AV Villas</option>
                                        <option value="bancooccidente">Banco de Occidente</option>
                                        <option value="bancoagrario">Banco Agrario</option>
                                        <option value="bancoomeva">Bancoomeva</option>
                                        <option value="bbva">BBVA</option>
                                        <option value="scotiabank">Scotiabank Colpatria</option>
                                        <option value="otro">Otro</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="tipo_cuenta" class="form-label">Tipo de Cuenta *</label>
                                    <select class="form-select" id="tipo_cuenta" name="tipo_cuenta" required>
                                        <option value="">Selecciona el tipo de cuenta</option>
                                        <option value="ahorros">Ahorros</option>
                                        <option value="corriente">Corriente</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="numero_cuenta" class="form-label">Número de Cuenta *</label>
                                    <input type="text" class="form-control" id="numero_cuenta" name="numero_cuenta" 
                                           placeholder="Ingresa el número de tu cuenta" pattern="[0-9]{6,20}" maxlength="20" required>
                                    <small class="form-text text-muted">Ingresa el número de cuenta desde donde realizarás la transferencia</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="titular_cuenta" class="form-label">Nombre del Titular *</label>
                                    <input type="text" class="form-control" id="titular_cuenta" name="titular_cuenta" 
                                           placeholder="Nombre completo del titular de la cuenta" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="numero_identificacion" class="form-label">Número de Identificación *</label>
                                    <input type="text" class="form-control" id="numero_identificacion" name="numero_identificacion" 
                                           placeholder="C.C. o NIT" pattern="[0-9]{6,15}" maxlength="15" required>
                                    <small class="form-text text-muted">Cédula de ciudadanía o NIT del titular</small>
                                </div>
                                
                                <hr class="my-4">
                                
                                <h6 class="mb-3"><i class="fas fa-receipt me-2"></i>Confirmación de Transferencia</h6>
                                
                                <div class="mb-3">
                                    <label for="numero_referencia" class="form-label">Número de Referencia de Transferencia *</label>
                                    <input type="text" class="form-control" id="numero_referencia" name="numero_referencia" 
                                           placeholder="Ingresa el número de referencia" required>
                                    <small class="form-text text-muted">Este número lo recibirás después de realizar la transferencia desde tu banco</small>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Instrucciones:</strong>
                                    <ol class="mb-0 mt-2">
                                        <li>Realiza la transferencia desde tu banco a la cuenta destino mostrada arriba</li>
                                        <li>Ingresa el monto: <strong><?php echo formatPrice($total); ?></strong></li>
                                        <li>Guarda el número de referencia que te proporciona tu banco</li>
                                        <li>Ingresa el número de referencia en el campo correspondiente</li>
                                        <li>Confirma el pago para completar tu pedido</li>
                                    </ol>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Confirmar Pago
                                </button>
                                <a href="<?php echo SITE_URL; ?>checkout" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Volver al Checkout
                                </a>
                            </div>
                        </form>
                    <?php elseif ($method === 'nequi'): ?>
                        <!-- Formulario de Nequi -->
                        <form id="paymentForm" method="POST" action="<?php echo SITE_URL; ?>checkout/process-payment">
                            <input type="hidden" name="metodo_pago" value="nequi">
                            <input type="hidden" name="checkout_data" id="checkout_data_input" value="">
                            
                            <div class="mb-4">
                                <h5 class="mb-3"><i class="fas fa-mobile-alt me-2"></i>Pago con Nequi</h5>
                                
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <i class="fas fa-mobile-alt fa-3x text-primary mb-3"></i>
                                        <h6 class="card-title">Número Nequi</h6>
                                        <p class="mb-0"><strong>3001234567</strong></p>
                                        <small class="text-muted">Yogurt Artesanal San Francisco</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="numero_celular_nequi" class="form-label">Tu Número de Celular (Nequi) *</label>
                                    <input type="tel" class="form-control" id="numero_celular_nequi" name="numero_celular" 
                                           placeholder="3001234567" pattern="[0-9]{10}" maxlength="10" required>
                                    <small class="form-text text-muted">Confirma el número de celular registrado en Nequi</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="codigo_verificacion" class="form-label">Código de Verificación *</label>
                                    <input type="text" class="form-control" id="codigo_verificacion" name="codigo_verificacion" 
                                           placeholder="Ingresa el código de 6 dígitos" pattern="[0-9]{6}" maxlength="6" required>
                                    <small class="form-text text-muted">Ingresa el código que recibiste por SMS</small>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Instrucciones:</strong>
                                    <ol class="mb-0 mt-2">
                                        <li>Abre la app de Nequi</li>
                                        <li>Envía el dinero al número: <strong>3001234567</strong></li>
                                        <li>Ingresa el monto: <strong><?php echo formatPrice($total); ?></strong></li>
                                        <li>Ingresa el código de verificación que recibiste</li>
                                    </ol>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Confirmar Pago
                                </button>
                                <a href="<?php echo SITE_URL; ?>checkout" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Volver al Checkout
                                </a>
                            </div>
                        </form>
                    <?php elseif ($method === 'bancolombia'): ?>
                        <!-- Formulario de Bancolombia Ahorro a la Mano -->
                        <form id="paymentForm" method="POST" action="<?php echo SITE_URL; ?>checkout/process-payment">
                            <input type="hidden" name="metodo_pago" value="bancolombia">
                            <input type="hidden" name="checkout_data" id="checkout_data_input" value="">
                            
                            <div class="mb-4">
                                <h5 class="mb-3"><i class="fas fa-wallet me-2"></i>Pago con Bancolombia Ahorro a la Mano</h5>
                                
                                <div class="card bg-light mb-3">
                                    <div class="card-body text-center">
                                        <i class="fas fa-wallet fa-3x text-primary mb-3"></i>
                                        <h6 class="card-title">Número Ahorro a la Mano</h6>
                                        <p class="mb-0"><strong>3001234567</strong></p>
                                        <small class="text-muted">Yogurt Artesanal San Francisco</small>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="numero_celular_bancolombia" class="form-label">Tu Número de Celular (Ahorro a la Mano) *</label>
                                    <input type="tel" class="form-control" id="numero_celular_bancolombia" name="numero_celular" 
                                           placeholder="3001234567" pattern="[0-9]{10}" maxlength="10" required>
                                    <small class="form-text text-muted">Confirma el número de celular registrado en Ahorro a la Mano</small>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="codigo_verificacion_bancolombia" class="form-label">Código de Verificación *</label>
                                    <input type="text" class="form-control" id="codigo_verificacion_bancolombia" name="codigo_verificacion" 
                                           placeholder="Ingresa el código de 6 dígitos" pattern="[0-9]{6}" maxlength="6" required>
                                    <small class="form-text text-muted">Ingresa el código que recibiste por SMS</small>
                                </div>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Instrucciones:</strong>
                                    <ol class="mb-0 mt-2">
                                        <li>Abre la app de Bancolombia</li>
                                        <li>Selecciona "Ahorro a la Mano"</li>
                                        <li>Envía el dinero al número: <strong>3001234567</strong></li>
                                        <li>Ingresa el monto: <strong><?php echo formatPrice($total); ?></strong></li>
                                        <li>Ingresa el código de verificación que recibiste</li>
                                    </ol>
                                </div>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Confirmar Pago
                                </button>
                                <a href="<?php echo SITE_URL; ?>checkout" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Volver al Checkout
                                </a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Información de seguridad -->
            <div class="card mt-4">
                <div class="card-body text-center">
                    <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                    <h6 class="card-title">Pago 100% Seguro</h6>
                    <p class="card-text small text-muted mb-0">
                        Tus datos están protegidos con encriptación SSL. Esta es una pasarela de pago simulada para fines de demostración.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Simular envío de código de verificación (para Nequi y Bancolombia)
document.addEventListener('DOMContentLoaded', function() {
    const method = '<?php echo $method; ?>';
    
    // Obtener datos del checkout desde sessionStorage
    const checkoutDataStr = sessionStorage.getItem('checkout_data');
    const checkoutDataInput = document.getElementById('checkout_data_input');
    if (checkoutDataInput && checkoutDataStr) {
        checkoutDataInput.value = checkoutDataStr;
    }
    
    if (method === 'nequi' || method === 'bancolombia') {
        const celularInput = document.querySelector('input[name="numero_celular"]');
        const codigoInput = document.querySelector('input[name="codigo_verificacion"]');
        
        if (celularInput && codigoInput) {
            celularInput.addEventListener('blur', function() {
                const celular = this.value;
                if (celular && celular.length === 10 && /^[0-9]{10}$/.test(celular)) {
                    // Simular envío de código
                    const codigoSimulado = Math.floor(100000 + Math.random() * 900000);
                    
                    // Mostrar alerta simulada
                    if (confirm('Código de verificación enviado al ' + celular + '\n\nCódigo simulado: ' + codigoSimulado + '\n\n(Para pruebas, puedes usar este código)')) {
                        // Opcional: auto-completar el código (solo para desarrollo)
                        // codigoInput.value = codigoSimulado;
                    }
                }
            });
        }
    }
    
    // Validación del formulario
    const form = document.getElementById('paymentForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Validaciones adicionales si es necesario
            const method = '<?php echo $method; ?>';
            
            if (method === 'nequi' || method === 'bancolombia') {
                const codigo = document.querySelector('input[name="codigo_verificacion"]').value;
                if (!codigo || codigo.length !== 6 || !/^[0-9]{6}$/.test(codigo)) {
                    e.preventDefault();
                    alert('Por favor ingresa un código de verificación válido de 6 dígitos');
                    return false;
                }
            }
        });
    }
});
</script>

<?php include 'views/layout/footer.php'; ?>

