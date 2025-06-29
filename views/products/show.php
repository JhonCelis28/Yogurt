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
                     class="card-img-top" alt="<?php echo $product['nombre']; ?>" style="height: 400px; object-fit: cover;">
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
                        <h3 class="text-primary-custom"><?php echo formatPrice($product['precio']); ?></h3>
                        <small class="text-muted">Precio por unidad</small>
                    </div>

                    <div class="mb-4">
                        <h5>Descripción</h5>
                        <p><?php echo nl2br($product['descripcion']); ?></p>
                    </div>

                    <?php if ($product['es_personalizable']): ?>
                    <!-- Formulario de personalización -->
                    <form id="personalizationForm">
                        <div class="row g-3 mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Sabor</label>
                                <select class="form-select" name="sabor" required>
                                    <option value="">Seleccionar sabor</option>
                                    <option value="fresa">Fresa</option>
                                    <option value="mora">Mora</option>
                                    <option value="mango">Mango</option>
                                    <option value="maracuya">Maracuyá</option>
                                    <option value="natural">Natural</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Endulzante</label>
                                <select class="form-select" name="endulzante" required>
                                    <option value="">Seleccionar endulzante</option>
                                    <option value="miel">Miel</option>
                                    <option value="stevia">Stevia</option>
                                    <option value="panela">Panela</option>
                                    <option value="sin_endulzante">Sin endulzante</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Nivel de Dulzor</label>
                                <select class="form-select" name="nivel_dulzor" required>
                                    <option value="">Seleccionar nivel</option>
                                    <option value="bajo">Bajo</option>
                                    <option value="medio">Medio</option>
                                    <option value="alto">Alto</option>
                                </select>
                            </div>
                        </div>
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

function addProductToCart() {
    const quantity = document.getElementById('quantity').value;
    let personalizaciones = null;
    
    <?php if ($product['es_personalizable']): ?>
    const form = document.getElementById('personalizationForm');
    const formData = new FormData(form);
    
    // Validar que todos los campos estén llenos
    if (!formData.get('sabor') || !formData.get('endulzante') || !formData.get('nivel_dulzor')) {
        alert('Por favor completa todas las opciones de personalización');
        return;
    }
    
    personalizaciones = {
        sabor: formData.get('sabor'),
        endulzante: formData.get('endulzante'),
        nivel_dulzor: formData.get('nivel_dulzor')
    };
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
