<?php 
$title = 'Gestión de Pedidos';
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
                    <a href="<?php echo SITE_URL; ?>admin" class="list-group-item list-group-item-action">
                        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/products" class="list-group-item list-group-item-action">
                        <i class="fas fa-box me-2"></i>Productos
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/categories" class="list-group-item list-group-item-action">
                        <i class="fas fa-tags me-2"></i>Categorías
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/orders" class="list-group-item list-group-item-action active">
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
                <h2 class="text-primary-custom">
                    Gestión de Pedidos
                    <?php if (isset($filteredUser) && $filteredUser): ?>
                        <small class="text-muted">- Cliente: <?php echo htmlspecialchars($filteredUser['nombre']); ?></small>
                        <a href="<?php echo SITE_URL; ?>admin/orders" class="btn btn-sm btn-outline-secondary ms-2">
                            <i class="fas fa-times"></i> Quitar filtro
                        </a>
                    <?php endif; ?>
                </h2>
                <div class="d-flex gap-2">
                    <select class="form-select" id="filterStatus">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="confirmado">Confirmado</option>
                        <option value="preparando">Preparando</option>
                        <option value="enviado">Enviado</option>
                        <option value="entregado">Entregado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
            </div>

            <!-- Estadísticas rápidas -->
            <div class="row g-3 mb-4">
                <?php
                $pendientes = count(array_filter($orders, function($o) { return $o['estado'] == 'pendiente'; }));
                $confirmados = count(array_filter($orders, function($o) { return $o['estado'] == 'confirmado'; }));
                $preparando = count(array_filter($orders, function($o) { return $o['estado'] == 'preparando'; }));
                $entregados = count(array_filter($orders, function($o) { return $o['estado'] == 'entregado'; }));
                ?>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $pendientes; ?></h3>
                            <p class="mb-0">Pendientes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $confirmados; ?></h3>
                            <p class="mb-0">Confirmados</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $preparando; ?></h3>
                            <p class="mb-0">En Preparación</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $entregados; ?></h3>
                            <p class="mb-0">Entregados</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabla de pedidos -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Fecha</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($orders)): ?>
                                    <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo str_pad($order['id'], 3, '0', STR_PAD_LEFT); ?></td>
                                        <td>
                                            <div>
                                                <strong><?php echo $order['cliente_nombre'] ?? 'N/A'; ?></strong>
                                                <br><small class="text-muted"><?php echo $order['cliente_email'] ?? 'N/A'; ?></small>
                                            </div>
                                        </td>
                                        <td><?php echo formatDateTime($order['fecha_pedido']); ?></td>
                                        <td><?php echo formatPrice($order['total']); ?></td>
                                        <td>
                                            <?php 
                                            $isFinalState = in_array($order['estado'], ['entregado', 'cancelado']);
                                            ?>
                                            <?php if ($isFinalState): ?>
                                                <span class="badge <?php echo $order['estado'] == 'entregado' ? 'bg-success' : 'bg-danger'; ?>">
                                                    <?php echo ucfirst($order['estado']); ?>
                                                </span>
                                                <small class="d-block text-muted mt-1">
                                                    <i class="fas fa-lock"></i> Estado final
                                                </small>
                                            <?php else: ?>
                                                <form method="POST" action="<?php echo SITE_URL; ?>admin/orders/update-status/<?php echo $order['id']; ?>" style="display:inline;">
                                                    <select class="form-select form-select-sm" name="estado" onchange="this.form.submit()">
                                                        <option value="pendiente" <?php echo $order['estado'] == 'pendiente' ? 'selected' : ''; ?>>Pendiente</option>
                                                        <option value="confirmado" <?php echo $order['estado'] == 'confirmado' ? 'selected' : ''; ?>>Confirmado</option>
                                                        <option value="preparando" <?php echo $order['estado'] == 'preparando' ? 'selected' : ''; ?>>Preparando</option>
                                                        <option value="enviado" <?php echo $order['estado'] == 'enviado' ? 'selected' : ''; ?>>Enviado</option>
                                                        <option value="entregado" <?php echo $order['estado'] == 'entregado' ? 'selected' : ''; ?>>Entregado</option>
                                                        <option value="cancelado" <?php echo $order['estado'] == 'cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                                                    </select>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="viewOrderDetails(<?php echo $order['id']; ?>)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-success" onclick="printOrder(<?php echo $order['id']; ?>)">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">No hay pedidos registrados</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detalles del Pedido -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Pedido</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <div class="text-center py-4">
                    <i class="fas fa-spinner fa-spin fa-2x text-primary-custom"></i>
                    <p class="mt-2">Cargando detalles del pedido...</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="printOrderBtn" onclick="printOrderFromModal()">
                    <i class="fas fa-print me-2"></i>Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentOrderId = null;

function viewOrderDetails(orderId) {
    currentOrderId = orderId;
    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
    const content = document.getElementById('orderDetailsContent');
    
    // Mostrar loading
    content.innerHTML = `
        <div class="text-center py-4">
            <i class="fas fa-spinner fa-spin fa-2x text-primary-custom"></i>
            <p class="mt-2">Cargando detalles del pedido...</p>
        </div>
    `;
    
    modal.show();
    
    // Cargar datos del pedido
    fetch('<?php echo SITE_URL; ?>admin/orders/view/' + orderId + '?ajax=1')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>${data.error}
                    </div>
                `;
                return;
            }
            
            // Formatear fecha
            const fecha = new Date(data.fecha_pedido);
            const fechaFormateada = fecha.toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            
            // Formatear estado
            const estados = {
                'pendiente': { class: 'bg-warning', text: 'Pendiente' },
                'confirmado': { class: 'bg-info', text: 'Confirmado' },
                'preparando': { class: 'bg-primary', text: 'Preparando' },
                'enviado': { class: 'bg-success', text: 'Enviado' },
                'entregado': { class: 'bg-success', text: 'Entregado' },
                'cancelado': { class: 'bg-danger', text: 'Cancelado' }
            };
            const estado = estados[data.estado] || { class: 'bg-secondary', text: data.estado };
            
            // Generar tabla de items
            let itemsHtml = '';
            if (data.items && data.items.length > 0) {
                data.items.forEach(item => {
                    let personalizacionesHtml = '';
                    if (item.personalizaciones) {
                        try {
                            const personalizaciones = JSON.parse(item.personalizaciones);
                            const personalizacionesArray = [];
                            if (personalizaciones.sabor) personalizacionesArray.push(`Sabor: ${personalizaciones.sabor}`);
                            if (personalizaciones.endulzante) personalizacionesArray.push(`Endulzante: ${personalizaciones.endulzante.replace(/_/g, ' ')}`);
                            if (personalizaciones.nivel_dulzor) personalizacionesArray.push(`Dulzor: ${personalizaciones.nivel_dulzor}`);
                            if (personalizaciones.tamaño) personalizacionesArray.push(`Tamaño: ${personalizaciones.tamaño}`);
                            if (personalizacionesArray.length > 0) {
                                personalizacionesHtml = `<small class="text-muted d-block">${personalizacionesArray.join(', ')}</small>`;
                            }
                        } catch (e) {
                            console.error('Error parsing personalizaciones:', e);
                        }
                    }
                    
                    const imagen = item.imagen ? `<?php echo SITE_URL; ?>assets/images/products/${item.imagen}` : `<?php echo SITE_URL; ?>assets/images/products/default.jpg`;
                    const subtotal = parseFloat(item.precio_unitario) * parseInt(item.cantidad);
                    
                    itemsHtml += `
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="${imagen}" alt="${item.nombre}" class="me-2" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                    <div>
                                        <strong>${item.nombre}</strong>
                                        ${personalizacionesHtml}
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">${item.cantidad}</td>
                            <td class="text-end">${formatPrice(item.precio_unitario)}</td>
                            <td class="text-end"><strong>${formatPrice(subtotal)}</strong></td>
                        </tr>
                    `;
                });
            } else {
                itemsHtml = '<tr><td colspan="4" class="text-center">No hay items en este pedido</td></tr>';
            }
            
            // Construir HTML completo
            content.innerHTML = `
                <div class="row mb-3">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Información del Pedido</h6>
                        <p class="mb-1"><strong>Pedido #${String(data.id).padStart(3, '0')}</strong></p>
                        <p class="mb-1"><small class="text-muted">Fecha: ${fechaFormateada}</small></p>
                        <p class="mb-0">
                            <span class="badge ${estado.class}">${estado.text}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">Cliente</h6>
                        <p class="mb-1"><strong>${data.cliente_nombre || 'N/A'}</strong></p>
                        <p class="mb-1"><small class="text-muted">${data.cliente_email || 'N/A'}</small></p>
                        ${data.telefono_contacto ? `<p class="mb-0"><small class="text-muted">Tel: ${data.telefono_contacto}</small></p>` : ''}
                    </div>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Dirección de Entrega</h6>
                    <p class="mb-0">${data.direccion_entrega || 'No especificada'}</p>
                </div>
                
                ${data.notas ? `
                <div class="mb-3">
                    <h6 class="text-muted mb-2">Notas del Pedido</h6>
                    <p class="mb-0">${data.notas}</p>
                </div>
                ` : ''}
                
                <hr>
                
                <h6 class="mb-3">Productos del Pedido</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-end">Precio Unit.</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${itemsHtml}
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end">Total:</th>
                                <th class="text-end text-primary-custom">${formatPrice(data.total)}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>Error al cargar los detalles del pedido
                </div>
            `;
        });
}

function formatPrice(price) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP',
        minimumFractionDigits: 0
    }).format(price);
}

function printOrder(orderId) {
    window.open('<?php echo SITE_URL; ?>admin/orders/print/' + orderId, '_blank');
}

function printOrderFromModal() {
    if (currentOrderId) {
        printOrder(currentOrderId);
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>
