<?php
$title = 'Productos Personalizados';
include 'views/layout/header.php';
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-4 text-primary-custom">Productos Personalizados</h1>
        <p class="lead">Crea tu yogurt o torta perfecta eligiendo sabor, endulzante y nivel de dulzor</p>
    </div>

    <div class="row">
        <!-- Yogurt Personalizado -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-primary">
                <div class="card-header bg-primary text-white text-center">
                    <h4><i class="fas fa-glass-whiskey me-2"></i>Yogurt Personalizado</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="../yogurt1.jpg"
                            alt="Yogurt Personalizado" class="img-fluid rounded" style="height: 200px; object-fit: cover; box-shadow: 0 4px 8px rgba(0, 0, 0, 8);">
                    </div>

                    <form id="yogurtForm">
                        <input type="hidden" name="tipo_producto" value="yogurt">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Sabor *</label>
                            <div class="row g-2">
                                <?php
                                $sabores = ['Natural', 'Arequipe', 'Café', 'Coco', 'Fresa', 'Guanábana', 'Kiwi', 'Limón', 'Lulo', 'Maracuyá', 'Melocotón', 'Mora', 'Piña'];
                                foreach ($sabores as $sabor):
                                ?>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sabor" value="<?php echo $sabor; ?>" id="yogurt_<?php echo $sabor; ?>">
                                            <label class="form-check-label" for="yogurt_<?php echo $sabor; ?>">
                                                <?php echo ucfirst($sabor); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Endulzante *</label>
                            <div class="row g-2">
                                <?php
                                $endulzantes = ['stevia', 'Azúcar', 'sin_endulzante'];
                                foreach ($endulzantes as $endulzante):
                                ?>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="endulzante" value="<?php echo $endulzante; ?>" id="yogurt_<?php echo $endulzante; ?>">
                                            <label class="form-check-label" for="yogurt_<?php echo $endulzante; ?>">
                                                <?php echo str_replace('_', ' ', ucfirst($endulzante)); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nivel de Dulzor *</label>
                            <div class="row g-2">
                                <?php
                                $niveles = ['bajo', 'medio', 'alto'];
                                foreach ($niveles as $nivel):
                                ?>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="nivel_dulzor" value="<?php echo $nivel; ?>" id="yogurt_<?php echo $nivel; ?>">
                                            <label class="form-check-label" for="yogurt_<?php echo $nivel; ?>">
                                                <?php echo ucfirst($nivel); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <br>
                        <br>
                        <br>
                        <br>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Cantidad</label>
                            <div class="input-group" style="max-width: 150px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity('yogurt', -1)">-</button>
                                <input type="number" class="form-control text-center" id="yogurt_quantity" value="1" min="1" max="10" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity('yogurt', 1)">+</button>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="h4 text-primary-custom mb-3">$15.000</div>
                            <?php if (isLoggedIn()): ?>
                                <button type="button" onclick="addPersonalizedProduct('yogurt')" class="btn btn-primary btn-lg">
                                    <i class="fas fa-cart-plus me-2"></i>Agregar al Carrito
                                </button>
                            <?php else: ?>
                                <a href="<?php echo SITE_URL; ?>login" class="btn btn-primary btn-lg">
                                    <i class="fas fa-user me-2"></i>Iniciar Sesión
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Torta Personalizada -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100 border-success">
                <div class="card-header bg-success text-white text-center">
                    <h4><i class="fas fa-birthday-cake me-2"></i>Torta Personalizada</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="../tortas2.jpg"
                            alt="Torta Personalizada" class="img-fluid rounded" style="height: 200px; object-fit: cover; box-shadow: 0 1px 8px rgba(0, 0, 0, 8);">
                    </div>

                    <form id="tortaForm">
                        <input type="hidden" name="tipo_producto" value="torta">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Sabor *</label>
                            <div class="row g-2">
                                <?php
                                $sabores_torta = ['Ahuyama', 'Amapola', 'Chocolate', 'Espinaca', 'Mango', 'Milkiway', 'Tomate', 'Vainilla', 'Yogurt', 'Zanahoria', 'Remolacha', 'Cebra', 'Frutos Secos'];
                                foreach ($sabores_torta as $sabor):
                                ?>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sabor" value="<?php echo $sabor; ?>" id="torta_<?php echo $sabor; ?>">
                                            <label class="form-check-label" for="torta_<?php echo $sabor; ?>">
                                                <?php echo str_replace('_', ' ', ucfirst($sabor)); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Endulzante *</label>
                            <div class="row g-2">
                                <?php
                                $endulzantes = ['stevia', 'Azúzar', 'azucar_organica'];
                                foreach ($endulzantes as $endulzante):
                                ?>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="endulzante" value="<?php echo $endulzante; ?>" id="torta_<?php echo $endulzante; ?>">
                                            <label class="form-check-label" for="torta_<?php echo $endulzante; ?>">
                                                <?php echo str_replace('_', ' ', ucfirst($endulzante)); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Nivel de Dulzor *</label>
                            <div class="row g-2">
                                <?php
                                $niveles = ['bajo', 'medio', 'alto'];
                                foreach ($niveles as $nivel):
                                ?>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="nivel_dulzor" value="<?php echo $nivel; ?>" id="torta_<?php echo $nivel; ?>">
                                            <label class="form-check-label" for="torta_<?php echo $nivel; ?>">
                                                <?php echo ucfirst($nivel); ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Tamaño</label>
                            <select class="form-select" name="tamaño">
                                <option value="mediana" selected>1/2 Libra - $35.000</option>
                                <option value="grande">1 Libra - $50.000</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Cantidad</label>
                            <div class="input-group" style="max-width: 150px;">
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity('torta', -1)">-</button>
                                <input type="number" class="form-control text-center" id="torta_quantity" value="1" min="1" max="5" readonly>
                                <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity('torta', 1)">+</button>
                            </div>
                        </div>

                        <div class="text-center">
                            <div class="h4 text-success mb-3" id="torta_price">$35.000</div>
                            <?php if (isLoggedIn()): ?>
                                <button type="button" onclick="addPersonalizedProduct('torta')" class="btn btn-success btn-lg">
                                    <i class="fas fa-cart-plus me-2"></i>Agregar al Carrito
                                </button>
                            <?php else: ?>
                                <a href="<?php echo SITE_URL; ?>login" class="btn btn-success btn-lg">
                                    <i class="fas fa-user me-2"></i>Iniciar Sesión
                                </a>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Información adicional -->
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light-pink">
                <div class="card-body text-center">
                    <h5 class="text-primary-custom mb-3">¿Necesitas algo más específico?</h5>
                    <p class="mb-3">Si tienes alguna solicitud especial o necesitas un producto completamente personalizado, contáctanos directamente.</p>
                    <button onclick="contactWhatsApp('Hola, necesito un producto personalizado especial')" class="btn btn-success">
                        <i class="fab fa-whatsapp me-2"></i>Contactar por WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function changeQuantity(tipo, change) {
        const quantityInput = document.getElementById(tipo + '_quantity');
        let currentValue = parseInt(quantityInput.value);
        let newValue = currentValue + change;
        let maxValue = tipo === 'torta' ? 5 : 10;

        if (newValue >= 1 && newValue <= maxValue) {
            quantityInput.value = newValue;
        }
    }

    // Actualizar precio de torta según tamaño
    document.querySelector('select[name="tamaño"]').addEventListener('change', function() {
        const prices = {
            'pequeña': '$25.000',
            'mediana': '$35.000',
            'grande': '$50.000'
        };
        document.getElementById('torta_price').textContent = prices[this.value];
    });

    function addPersonalizedProduct(tipo) {
        const form = document.getElementById(tipo + 'Form');
        const formData = new FormData(form);

        // Validar campos requeridos
        if (!formData.get('sabor') || !formData.get('endulzante') || !formData.get('nivel_dulzor')) {
            alert('Por favor completa todas las opciones requeridas');
            return;
        }

        const quantity = document.getElementById(tipo + '_quantity').value;

        const personalizaciones = {
            tipo_producto: tipo,
            sabor: formData.get('sabor'),
            endulzante: formData.get('endulzante'),
            nivel_dulzor: formData.get('nivel_dulzor')
        };

        if (tipo === 'torta') {
            personalizaciones.tamaño = formData.get('tamaño');
        }

        // Usar ID especial para productos personalizados (999 para yogurt, 998 para torta)
        const productId = tipo === 'yogurt' ? 999 : 998;

        addToCart(productId, quantity, personalizaciones);
    }
</script>

<?php include 'views/layout/footer.php'; ?>