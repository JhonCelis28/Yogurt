<!-- Footer -->
    <footer class="bg-dark text-light py-5 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="text-primary-custom mb-3">
                        <img src="<?php echo SITE_URL; ?>logo1.png" alt="Logo" height="40" class="me-2">
                        <?php echo SITE_NAME; ?>
                    </h5>
                    <p class="text-light">
                        Productos artesanales 100% naturales, elaborados con amor y compromiso 
                        con tu salud y el medio ambiente.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="https://www.facebook.com/YogurtArtesanalSanFrancisco/" class="text-light" target="_blank">
                            <i class="fab fa-facebook fa-2x"></i>
                        </a>
                        <a href="https://www.instagram.com/yogurtartesanalsanfrancisco?igsh=MXNnbXpmZGpmN2xkbg==" class="text-light" target="_blank">
                            <i class="fab fa-instagram fa-2x"></i>
                        </a>                    
                        <a href="https://wa.me/573112668752?" class="text-success" target="_blank">
                            <i class="fab fa-whatsapp fa-2x"></i>
                        </a>
                    </div>
                </div>
                
                <div class="col-lg-2">
                    <h6 class="text-primary-custom mb-3">Productos</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>products/category/1" class="text-light text-decoration-none">Yogures Griegos</a></li>
                        <li><a href="<?php echo SITE_URL; ?>products/category/2" class="text-light text-decoration-none">Yogures con Frutas</a></li>
                        <li><a href="<?php echo SITE_URL; ?>products/category/3" class="text-light text-decoration-none">Postres</a></li>
                        <li><a href="<?php echo SITE_URL; ?>products/category/4" class="text-light text-decoration-none">Tortas</a></li>
                        <li><a href="<?php echo SITE_URL; ?>products/personalized" class="text-success text-decoration-none">Personalizados</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2">
                    <h6 class="text-primary-custom mb-3">Empresa</h6>
                    <ul class="list-unstyled">
                        <li><a href="<?php echo SITE_URL; ?>about" class="text-light text-decoration-none">Nosotros</a></li>
                        <li><a href="<?php echo SITE_URL; ?>promotions" class="text-light text-decoration-none">Promociones</a></li>                        
                    </ul>
                </div>
                
                <div class="col-lg-4">
                    <h6 class="text-primary-custom mb-3">Contacto</h6>
                    <div class="mb-2">
                        <i class="fas fa-map-marker-alt text-primary-custom me-2"></i>
                        <span class="text-light">  Aipe, Huila, Colombia</span>
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-phone text-primary-custom me-2"></i>
                        <span class="text-light">+57 311 266 8752</span>
                    </div>
                    <div class="mb-3">
                        <i class="fas fa-envelope text-primary-custom me-2"></i>
                        <span class="text-light">info@yogurtsanfrancisco.com</span>
                    </div>
                    
                    <h6 class="text-primary-custom mb-3">Newsletter</h6>
                    <form class="d-flex">
                        <input type="email" class="form-control me-2" placeholder="Tu email">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light mb-0">
                        &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. Todos los derechos reservados. 
                        <br><img src="developer.png" alt="" style="width: 30px; height: 30px;">Dev (Jhon/Celis)
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end gap-3">
                        <i class="fas fa-leaf text-success" title="100% Natural"></i>
                        <i class="fas fa-recycle text-success" title="Eco-Friendly"></i>
                        <i class="fas fa-heart text-danger" title="Hecho con Amor"></i>
                        <i class="fas fa-shield-alt text-primary" title="Calidad Garantizada"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Función para contactar por WhatsApp
        function contactWhatsApp(message = '') {
            const defaultMessage = 'Hola, me interesa conocer más sobre sus productos artesanales.';
            const finalMessage = message || defaultMessage;
            const whatsappUrl = `https://wa.me/573112668752?<?php echo WHATSAPP_NUMBER; ?>?text=${encodeURIComponent(finalMessage)}`;
            window.open(whatsappUrl, '_blank');
        }

        // Función para agregar al carrito
        function addToCart(productId, quantity = 1, personalizaciones = null) {
            showLoading();
            
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('quantity', quantity);
            if (personalizaciones) {
                formData.append('personalizaciones', JSON.stringify(personalizaciones));
            }

            fetch('<?php echo SITE_URL; ?>cart/add', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                hideLoading();
                if (data.success) {
                    showAlert('Producto agregado al carrito', 'success');
                    updateCartBadge();
                } else {
                    showAlert(data.message || 'Error al agregar producto', 'error');
                }
            })
            .catch(error => {
                hideLoading();
                showAlert('Error de conexión', 'error');
            });
        }

        // Función para actualizar cantidad en el carrito
        function updateQuantity(cartId, change) {
            const quantityInput = document.querySelector(`input[data-cart-id="${cartId}"]`);
            if (!quantityInput) return;
            
            let currentValue = parseInt(quantityInput.value);
            let newValue = currentValue + change;
            
            if (newValue >= 1) {
                showLoading();
                
                const formData = new FormData();
                formData.append('cart_id', cartId);
                formData.append('quantity', newValue);

                fetch('<?php echo SITE_URL; ?>cart/update', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    hideLoading();
                    if (data.success) {
                        quantityInput.value = newValue;
                        // Actualizar subtotal
                        const priceElement = document.querySelector(`[data-price-id="${cartId}"]`);
                        const subtotalElement = document.querySelector(`[data-subtotal-id="${cartId}"]`);
                        const totalElement = document.querySelector('#cart-total');
                        
                        if (priceElement && subtotalElement) {
                            const price = parseFloat(priceElement.getAttribute('data-price'));
                            const newSubtotal = price * newValue;
                            subtotalElement.textContent = formatPrice(newSubtotal);
                            
                            // Recalcular total
                            if (totalElement) {
                                let newTotal = 0;
                                document.querySelectorAll('[data-subtotal-id]').forEach(el => {
                                    const subtotal = parseFloat(el.textContent.replace(/[^0-9]/g, ''));
                                    newTotal += subtotal;
                                });
                                totalElement.textContent = formatPrice(newTotal);
                            }
                        }
                        
                        showAlert('Cantidad actualizada', 'success');
                    } else {
                        showAlert('Error al actualizar cantidad', 'error');
                    }
                })
                .catch(error => {
                    hideLoading();
                    showAlert('Error de conexión', 'error');
                });
            }
        }

        // Función para formatear precio
        function formatPrice(price) {
            return '$' + price.toLocaleString('es-CO');
        }

        // Función para mostrar alertas
        function showAlert(message, type = 'info') {
            const alertClass = type === 'success' ? 'alert-success' : 
                              type === 'error' ? 'alert-danger' : 'alert-info';
            
            const alertHtml = `
                <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
                     style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            document.body.insertAdjacentHTML('beforeend', alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert:last-of-type');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 5000);
        }

        // Función para mostrar/ocultar loading
        function showLoading() {
            document.getElementById('loading').style.display = 'block';
        }

        function hideLoading() {
            document.getElementById('loading').style.display = 'none';
        }

        // Función para actualizar badge del carrito
        function updateCartBadge() {
            fetch('<?php echo SITE_URL; ?>cart/count')
            .then(response => response.json())
            .then(data => {
                const badge = document.querySelector('.cart-badge');
                if (data.count > 0) {
                    if (badge) {
                        badge.textContent = data.count;
                    } else {
                        const cartLink = document.querySelector('a[href*="cart"]');
                        if (cartLink) {
                            cartLink.innerHTML += `<span class="cart-badge">${data.count}</span>`;
                        }
                    }
                } else if (badge) {
                    badge.remove();
                }
            });
        }

        // Validación de formularios
        document.addEventListener('DOMContentLoaded', function() {
            // Validar formularios con Bootstrap
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                });
            });

            // Smooth scroll para enlaces internos
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
        });

        // Función para buscar productos
        function searchProducts() {
            const searchTerm = document.getElementById('searchInput').value;
            if (searchTerm.trim()) {
                window.location.href = `<?php echo SITE_URL; ?>products/search?q=${encodeURIComponent(searchTerm)}`;
            }
        }

        // Enter key para búsqueda
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && e.target.id === 'searchInput') {
                searchProducts();
            }
        });
    </script>
</body>
</html>
