<?php 
$title = 'Carrito de Compras';

// Capturar mensajes del carrito antes de que el header los muestre
$cartSuccessMessage = null;
$cartErrorMessage = null;

if (isset($_SESSION['success_message']) && ($_SESSION['success_message'] === 'Carrito vaciado' || $_SESSION['success_message'] === 'Producto eliminado del carrito')) {
    $cartSuccessMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['error_message']) && (strpos($_SESSION['error_message'], 'carrito') !== false || strpos($_SESSION['error_message'], 'Carrito') !== false)) {
    $cartErrorMessage = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

include 'views/layout/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Mi Carrito de Compras</h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($cartItems)): ?>
                        <?php foreach ($cartItems as $item): ?>
                        <div class="row align-items-center border-bottom py-3">
                            <div class="col-md-2">
                                <?php 
                                // Para productos personalizados, usar imágenes de la raíz
                                if ($item['producto_id'] == 999 || $item['producto_id'] == 998) {
                                    $imagenPath = SITE_URL . $item['imagen'];
                                } else {
                                    $imagenPath = SITE_URL . 'assets/images/products/' . ($item['imagen'] ?: 'default.jpg');
                                }
                                ?>
                                <img src="<?php echo $imagenPath; ?>" 
                                     alt="<?php echo $item['nombre']; ?>" class="img-fluid rounded">
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1"><?php echo $item['nombre']; ?></h6>
                                <?php if ($item['personalizaciones']): ?>
                                    <?php $personalizaciones = json_decode($item['personalizaciones'], true); ?>
                                    <small class="text-muted">
                                        <?php if (isset($personalizaciones['sabor'])): ?>
                                            <span class="badge bg-light text-dark me-1">Sabor: <?php echo ucfirst($personalizaciones['sabor']); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($personalizaciones['endulzante'])): ?>
                                            <span class="badge bg-light text-dark me-1">Endulzante: <?php echo ucfirst(str_replace('_', ' ', $personalizaciones['endulzante'])); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($personalizaciones['nivel_dulzor'])): ?>
                                            <span class="badge bg-light text-dark me-1">Dulzor: <?php echo ucfirst($personalizaciones['nivel_dulzor']); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($personalizaciones['tamaño'])): ?>
                                            <span class="badge bg-light text-dark me-1">Tamaño: <?php echo ucfirst($personalizaciones['tamaño']); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($personalizaciones['harina']) && $personalizaciones['harina'] !== 'normal'): ?>
                                            <span class="badge bg-warning text-dark me-1">Harina: <?php echo ucfirst($personalizaciones['harina']); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($personalizaciones['semillas_frutos']) && $personalizaciones['semillas_frutos']): ?>
                                            <span class="badge bg-info text-dark me-1">Con semillas y frutos secos</span>
                                        <?php endif; ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2 text-center">
                                <span class="fw-bold" data-price-id="<?php echo $item['id']; ?>" data-price="<?php echo $item['precio']; ?>"><?php echo formatPrice($item['precio']); ?></span>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="input-group input-group-sm">
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(<?php echo $item['id']; ?>, -1)">-</button>
                                    <input type="text" class="form-control text-center" value="<?php echo $item['cantidad']; ?>" data-cart-id="<?php echo $item['id']; ?>" readonly>
                                    <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity(<?php echo $item['id']; ?>, 1)">+</button>
                                </div>
                            </div>
                            <div class="col-md-1 text-center">
                                <span class="fw-bold" data-subtotal-id="<?php echo $item['id']; ?>"><?php echo formatPrice($item['subtotal']); ?></span>
                            </div>
                            <div class="col-md-1 text-center">
                                <button type="button" 
                                        class="btn btn-outline-danger btn-sm" 
                                        onclick="confirmDelete(<?php echo $item['id']; ?>)">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="text-end mt-3">
                            <button type="button" 
                                    class="btn btn-outline-secondary me-2"
                                    onclick="confirmClearCart()">
                                <i class="fas fa-trash me-2"></i>Vaciar Carrito
                            </button>
                            <a href="<?php echo SITE_URL; ?>products" class="btn btn-outline-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Seguir Comprando
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">Tu carrito está vacío</h4>
                            <p class="text-muted">Agrega algunos productos para comenzar</p>
                            <a href="<?php echo SITE_URL; ?>products" class="btn btn-primary">
                                <i class="fas fa-shopping-bag me-2"></i>Ir a Productos
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($cartItems)): ?>
        <div class="col-lg-4">
            <!-- Resumen del pedido -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Resumen del Pedido</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span id="cart-subtotal"><?php echo formatPrice($total); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2" id="descuento-envases-container" style="display: none;">
                        <span class="text-success">Descuento por envases:</span>
                        <span class="text-success" id="descuento-envases">$0</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Envío:</span>
                        <span class="text-success">Gratis</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-primary-custom" id="cart-total"><?php echo formatPrice($total); ?></strong>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="<?php echo SITE_URL; ?>checkout" class="btn btn-primary btn-lg" id="checkout-btn" onclick="saveEnvasesBeforeCheckout(event)">
                            <i class="fas fa-credit-card me-2"></i>Proceder al Pago
                        </a>
                        <button onclick="contactWhatsAppWithTotal()" 
                                class="btn btn-success">
                            <i class="fab fa-whatsapp me-2"></i>Pedir por WhatsApp
                        </button>
                    </div>
                </div>
            </div>

            <!-- Promociones disponibles -->
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h6 class="mb-0"><i class="fas fa-gift me-2"></i>Promociones Disponibles</h6>
                </div>
                <div class="card-body">
                    <?php if (isset($isFirstOrder) && $isFirstOrder): ?>
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-star text-warning me-2"></i>
                            <strong>Primera Compra</strong>
                        </div>
                        <small class="text-muted">15% de descuento en tu primera compra</small>
                    </div>
                    <?php endif; ?>
                    
                    <div class="mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-recycle text-success me-2"></i>
                            <strong>Envase Devuelto</strong>
                        </div>
                        <small class="text-muted">$2.000 de descuento por cada envase de vidrio devuelto</small>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="envaseDevuelto" onchange="toggleEnvasesInput()">
                        <label class="form-check-label" for="envaseDevuelto">
                            Tengo envases para devolver
                        </label>
                    </div>
                    
                    <div id="envases-cantidad-container" style="display: none;">
                        <label for="envases-cantidad" class="form-label small">Cantidad de envases:</label>
                        <div class="input-group">
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateEnvasesQuantity(-1)">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="form-control form-control-sm text-center" 
                                   id="envases-cantidad" 
                                   value="0" 
                                   min="0" 
                                   onchange="updateTotalWithEnvases()"
                                   oninput="updateTotalWithEnvases()">
                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateEnvasesQuantity(1)">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <small class="text-muted d-block mt-1">
                            Descuento: <span id="descuento-preview">$0</span>
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Variables globales
const DESCUENTO_POR_ENVASE = 2000;
let subtotal = <?php echo $total; ?>;

// Función para mostrar/ocultar el input de cantidad de envases
function toggleEnvasesInput() {
    const checkbox = document.getElementById('envaseDevuelto');
    const container = document.getElementById('envases-cantidad-container');
    const cantidadInput = document.getElementById('envases-cantidad');
    
    if (checkbox.checked) {
        container.style.display = 'block';
        cantidadInput.value = 1;
        updateTotalWithEnvases();
    } else {
        container.style.display = 'none';
        cantidadInput.value = 0;
        updateTotalWithEnvases();
    }
}

// Función para actualizar la cantidad de envases con los botones +/-
function updateEnvasesQuantity(change) {
    const cantidadInput = document.getElementById('envases-cantidad');
    let currentValue = parseInt(cantidadInput.value) || 0;
    let newValue = currentValue + change;
    
    if (newValue < 0) {
        newValue = 0;
    }
    
    cantidadInput.value = newValue;
    updateTotalWithEnvases();
}

// Función para actualizar el total con el descuento de envases
function updateTotalWithEnvases() {
    const cantidadInput = document.getElementById('envases-cantidad');
    const cantidad = parseInt(cantidadInput.value) || 0;
    const descuento = cantidad * DESCUENTO_POR_ENVASE;
    const totalConDescuento = subtotal - descuento;
    
    // Actualizar el preview del descuento
    document.getElementById('descuento-preview').textContent = formatPrice(descuento);
    
    // Actualizar el descuento en el resumen
    const descuentoContainer = document.getElementById('descuento-envases-container');
    const descuentoSpan = document.getElementById('descuento-envases');
    
    if (descuento > 0) {
        descuentoContainer.style.display = 'flex';
        descuentoSpan.textContent = '-' + formatPrice(descuento);
    } else {
        descuentoContainer.style.display = 'none';
        descuentoSpan.textContent = '$0';
    }
    
    // Actualizar el total
    document.getElementById('cart-total').textContent = formatPrice(Math.max(0, totalConDescuento));
    
    // Guardar en sesión (localStorage temporalmente, o podrías hacer una petición AJAX)
    saveEnvasesToSession(cantidad);
}

// Función para formatear precio
function formatPrice(price) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(price);
}

// Función para guardar la cantidad de envases en la sesión
function saveEnvasesToSession(cantidad) {
    // Guardar en localStorage temporalmente
    localStorage.setItem('envases_devueltos', cantidad);
    
    // También hacer una petición AJAX para guardar en la sesión del servidor
    fetch('<?php echo SITE_URL; ?>cart/save-envases', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'cantidad=' + cantidad
    }).catch(error => {
        console.error('Error al guardar envases:', error);
    });
}

// Función para guardar envases antes de ir al checkout
function saveEnvasesBeforeCheckout(event) {
    const cantidad = parseInt(document.getElementById('envases-cantidad').value) || 0;
    saveEnvasesToSession(cantidad);
    // No prevenir el comportamiento por defecto, dejar que continúe al checkout
}

// Función para actualizar el mensaje de WhatsApp con el total correcto
function contactWhatsAppWithTotal() {
    const cantidad = parseInt(document.getElementById('envases-cantidad').value) || 0;
    const descuento = cantidad * DESCUENTO_POR_ENVASE;
    const totalFinal = Math.max(0, subtotal - descuento);
    const totalFormateado = formatPrice(totalFinal);
    contactWhatsApp('Hola, quiero hacer un pedido. Mi carrito tiene un total de ' + totalFormateado);
}

// Función para confirmar eliminación con SweetAlert2
function confirmDelete(cartId) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Deseas eliminar este producto del carrito?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?php echo SITE_URL; ?>cart/remove/' + cartId;
        }
    });
}

// Función para confirmar vaciar el carrito con SweetAlert2
function confirmClearCart() {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¿Deseas vaciar todo el carrito de compras?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, vaciar carrito',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '<?php echo SITE_URL; ?>cart/clear';
        }
    });
}

// Cargar cantidad de envases guardada al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Intentar cargar desde la sesión del servidor primero
    const savedCantidad = <?php echo isset($_SESSION['envases_devueltos']) ? (int)$_SESSION['envases_devueltos'] : 0; ?>;
    
    if (savedCantidad > 0) {
        document.getElementById('envaseDevuelto').checked = true;
        document.getElementById('envases-cantidad-container').style.display = 'block';
        document.getElementById('envases-cantidad').value = savedCantidad;
        updateTotalWithEnvases();
    } else {
        // Si no hay en sesión, intentar desde localStorage
        const localCantidad = localStorage.getItem('envases_devueltos');
        if (localCantidad && localCantidad > 0) {
            document.getElementById('envaseDevuelto').checked = true;
            document.getElementById('envases-cantidad-container').style.display = 'block';
            document.getElementById('envases-cantidad').value = localCantidad;
            updateTotalWithEnvases();
        }
    }
    
    // Mostrar alerta si hay mensaje de sesión después de vaciar el carrito
    <?php if ($cartSuccessMessage === 'Carrito vaciado'): ?>
        Swal.fire({
            title: '¡Carrito vaciado!',
            text: 'Tu carrito de compras ha sido vaciado exitosamente',
            icon: 'success',
            confirmButtonColor: '#E91E63',
            confirmButtonText: 'Aceptar'
        });
    <?php elseif ($cartSuccessMessage === 'Producto eliminado del carrito'): ?>
        Swal.fire({
            title: 'Producto eliminado',
            text: 'El producto ha sido eliminado del carrito',
            icon: 'success',
            confirmButtonColor: '#E91E63',
            confirmButtonText: 'Aceptar'
        });
    <?php elseif ($cartErrorMessage): ?>
        Swal.fire({
            title: 'Error',
            text: '<?php echo addslashes($cartErrorMessage); ?>',
            icon: 'error',
            confirmButtonColor: '#E91E63',
            confirmButtonText: 'Aceptar'
        });
    <?php endif; ?>
});
</script>

<?php include 'views/layout/footer.php'; ?>
