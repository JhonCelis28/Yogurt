<?php 
$title = $product['nombre'];
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>">Inicio</a></li>
            <li class="breadcrumb-item"><a href="<?php echo SITE_URL; ?>products">Productos</a></li>
            <li class="breadcrumb-item active"><?php echo $product['nombre']; ?></li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card">
                <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $product['imagen'] ?: 'default.jpg'; ?>" 
                     class="card-img-top" alt="<?php echo $product['nombre']; ?>" style="height: 700px; object-fit: cover;">
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title text-primary-custom"><?php echo $product['nombre']; ?></h1>
                    <p class="text-muted mb-3">
                        <i class="fas fa-tag me-2"></i><?php echo $product['categoria_nombre']; ?>
                    </p>
                    
                    <?php if ($product['es_personalizable']): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-palette me-2"></i>
                            <strong>¡Producto Personalizable!</strong> Puedes elegir sabor, endulzante y nivel de dulzor.
                        </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <h3 class="text-primary-custom" id="product-price"><?php echo formatPrice($product['precio']); ?></h3>
                        <small class="text-muted">Precio por unidad</small>
                    </div>

                    <div class="mb-4">
                        <h5>Descripción</h5>
                        <p>
                            <?php 
                            // Para el producto ID 6, usar una descripción profesional y llamativa
                            if ($product['id'] == 6) {
                                $descripcion = 'Yogures artesanales de textura cremosa, elaborados con ingredientes frescos y naturales. Cada taza ofrece un sabor auténtico que preserva todos los nutrientes. Productos sin conservantes ni saborizantes artificiales.';
                            } else {
                                $descripcion = $product['descripcion'];
                            }
                            echo nl2br($descripcion);
                            ?>
                        </p>
                    </div>

                    <?php if ($product['es_personalizable']): ?>
                    <!-- Formulario de personalización -->
                    <?php 
                    // Detectar si es una torta (por nombre o categoría)
                    $esTorta = stripos($product['nombre'], 'torta') !== false || 
                               stripos($product['categoria_nombre'] ?? '', 'torta') !== false;
                    
                    // Detectar si es un yogurt (por nombre o categoría)
                    $esYogurt = stripos($product['nombre'], 'yogurt') !== false || 
                                stripos($product['nombre'], 'yogur') !== false ||
                                stripos($product['categoria_nombre'] ?? '', 'yogurt') !== false ||
                                stripos($product['categoria_nombre'] ?? '', 'yogur') !== false;
                    
                    // Sabores para tortas (chocolate y milkiway se manejan como productos separados)
                    $sabores_torta = ['ahuyama', 'remolacha', 'espinaca', 'tomate', 'amapola', 'zanahoria', 'mango', 'naranja', 'yogurt', 'vainilla'];
                    
                    // Sabores para yogures
                    $sabores_yogurt = ['coco', 'melocotón', 'fresa', 'arequipe', 'piña', 'guanábana', 'naranja', 'café', 'kiwi', 'mora', 'maracuyá', 'limón', 'lulo', 'mango', 'natural'];
                    ?>
                    <form id="personalizationForm">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Sabor</label>
                                <select class="form-select" name="sabor" id="sabor-select" required>
                                    <option value="">Seleccionar sabor</option>
                                    <?php if ($esTorta): ?>
                                        <?php foreach ($sabores_torta as $sabor): ?>
                                            <option value="<?php echo $sabor; ?>">
                                                <?php echo ucfirst($sabor); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php elseif ($esYogurt): ?>
                                        <?php foreach ($sabores_yogurt as $sabor): ?>
                                            <option value="<?php echo strtolower($sabor); ?>">
                                                <?php 
                                                $saborTexto = ucfirst($sabor);
                                                if (strtolower($sabor) === 'natural') {
                                                    $saborTexto = 'Natural (sin fruta)';
                                                }
                                                echo $saborTexto;
                                                ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <option value="fresa">Fresa</option>
                                        <option value="mora">Mora</option>
                                        <option value="mango">Mango</option>
                                        <option value="maracuya">Maracuyá</option>
                                        <option value="natural">Natural</option>
                                        <option value="limon">Limón</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Endulzante</label>
                                <select class="form-select" name="endulzante" id="endulzante-select" required>
                                    <option value="">Seleccionar endulzante</option>
                                    <?php if ($esTorta): ?>
                                        <option value="azucar">Azúcar</option>
                                        <option value="stevia">Stevia</option>
                                        <option value="sin_endulzante">Sin endulzante</option>
                                    <?php elseif ($esYogurt): ?>
                                        <option value="azucar">Azúcar</option>
                                        <option value="stevia">Stevia</option>
                                        <option value="sin_endulzante">Sin endulzante</option>
                                    <?php else: ?>
                                        <option value="miel">Miel</option>
                                        <option value="stevia">Stevia</option>
                                        <option value="panela">Panela</option>
                                        <option value="sin_endulzante">Sin endulzante</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nivel de Dulzor</label>
                                <select class="form-select" name="nivel_dulzor" id="nivel_dulzor-select" required>
                                    <option value="">Seleccionar nivel</option>
                                    <option value="bajo">Bajo</option>
                                    <option value="medio">Medio</option>
                                    <option value="alto">Alto</option>
                                </select>
                            </div>
                        </div>
                        
                        <?php if ($esTorta): ?>
                        <!-- Opciones adicionales para tortas -->
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Tipo de Harina</label>
                                <select class="form-select" name="harina" id="harina-select">
                                    <option value="normal">Normal</option>
                                    <option value="almendras">Almendras (Harina Especial)</option>
                                    <option value="coco">Coco (Harina Especial)</option>
                                </select>
                                <div id="harina-message" class="alert alert-warning mt-2" style="display: none;">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Nota:</strong> Al seleccionar harina especial, el precio aumenta a $50.000
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Semillas y Frutos Secos</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="semillas_frutos" value="1" id="semillas-check" checked>
                                    <label class="form-check-label" for="semillas-check">
                                        Incluir semillas (chia, quinua, amapola) y frutos secos
                                    </label>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </form>
                    <?php endif; ?>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Cantidad</label>
                            <div class="input-group">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">-</button>
                                <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="10">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">+</button>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2 d-md-flex">
                        <?php if (isLoggedIn()): ?>
                            <button onclick="addProductToCart()" class="btn btn-primary btn-lg me-md-2">
                                <i class="fas fa-cart-plus me-2"></i>Agregar al Carrito
                            </button>
                            <button onclick="buyNow()" class="btn btn-success btn-lg">
                                <i class="fas fa-shopping-bag me-2"></i>Comprar Ahora
                            </button>
                        <?php else: ?>
                            <a href="<?php echo SITE_URL; ?>login" class="btn btn-primary btn-lg me-md-2">
                                <i class="fas fa-user me-2"></i>Iniciar Sesión para Comprar
                            </a>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4">
                        <button onclick="contactWhatsApp('Hola, me interesa el producto: <?php echo $product['nombre']; ?>')" 
                                class="btn btn-outline-success">
                            <i class="fab fa-whatsapp me-2"></i>Consultar por WhatsApp
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Información del Producto</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-leaf text-success me-2"></i>100% Natural</li>
                                <li><i class="fas fa-recycle text-success me-2"></i>Envase Ecológico</li>
                                <li><i class="fas fa-heart text-danger me-2"></i>Hecho con Amor</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li><i class="fas fa-truck text-primary me-2"></i>Entrega a Domicilio</li>
                                <li><i class="fas fa-shield-alt text-primary me-2"></i>Producto Fresco</li>
                                <li><i class="fas fa-award text-warning me-2"></i>Calidad Garantizada</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    let currentValue = parseInt(quantityInput.value);
    let newValue = currentValue + change;
    
    if (newValue >= 1 && newValue <= 10) {
        quantityInput.value = newValue;
    }
}

<?php if ($product['es_personalizable']): ?>
// Bloquear nivel de dulzor cuando se selecciona "Sin endulzante"
const endulzanteSelect = document.getElementById('endulzante-select');
const nivelDulzorSelect = document.getElementById('nivel_dulzor-select');

if (endulzanteSelect && nivelDulzorSelect) {
    endulzanteSelect.addEventListener('change', function() {
        if (this.value === 'sin_endulzante') {
            nivelDulzorSelect.disabled = true;
            nivelDulzorSelect.value = '';
            nivelDulzorSelect.removeAttribute('required');
        } else {
            nivelDulzorSelect.disabled = false;
            nivelDulzorSelect.setAttribute('required', 'required');
        }
    });
}

<?php if ($esTorta): ?>
// Mostrar mensaje y actualizar precio cuando se selecciona harina especial
const harinaSelect = document.getElementById('harina-select');
const harinaMessage = document.getElementById('harina-message');
const productPriceElement = document.getElementById('product-price');
const precioBase = <?php echo $product['precio']; ?>;

if (harinaSelect && harinaMessage && productPriceElement) {
    harinaSelect.addEventListener('change', function() {
        if (this.value === 'almendras' || this.value === 'coco') {
            harinaMessage.style.display = 'block';
            // Actualizar precio a $50.000
            productPriceElement.textContent = '$50.000';
        } else {
            harinaMessage.style.display = 'none';
            // Restaurar precio original
            productPriceElement.textContent = '<?php echo formatPrice($product['precio']); ?>';
        }
    });
}
<?php endif; ?>
<?php endif; ?>

function addProductToCart() {
    const quantity = document.getElementById('quantity').value;
    let personalizaciones = null;
    
    <?php if ($product['es_personalizable']): ?>
    const form = document.getElementById('personalizationForm');
    const formData = new FormData(form);
    
    // Validar campos básicos
    if (!formData.get('sabor') || !formData.get('endulzante')) {
        showAlert('Por favor completa todas las opciones requeridas (Sabor y Endulzante)', 'warning');
        return;
    }
    
    personalizaciones = {
        sabor: formData.get('sabor'),
        endulzante: formData.get('endulzante')
    };
    
    // Agregar nivel de dulzor (solo si no es "Sin endulzante")
    const endulzante = formData.get('endulzante');
    if (endulzante !== 'sin_endulzante') {
        if (!formData.get('nivel_dulzor')) {
            showAlert('Por favor selecciona el nivel de dulzor', 'warning');
            return;
        }
        personalizaciones.nivel_dulzor = formData.get('nivel_dulzor');
    }
    
    <?php if ($esTorta): ?>
    // Para tortas: agregar harina y semillas
    const harina = formData.get('harina');
    if (harina && harina !== 'normal') {
        personalizaciones.harina = harina;
    }
    const semillas = formData.get('semillas_frutos');
    if (semillas) {
        personalizaciones.semillas_frutos = true;
    }
    <?php endif; ?>
    <?php endif; ?>
    
    addToCart(<?php echo $product['id']; ?>, quantity, personalizaciones);
}

function buyNow() {
    addProductToCart();
    setTimeout(() => {
        window.location.href = '<?php echo SITE_URL; ?>cart';
    }, 1000);
}
</script>

<?php include 'views/layout/footer.php'; ?>
