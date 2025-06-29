<?php 
$title = 'Gestión de Contactos';
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
                    <a href="<?php echo SITE_URL; ?>admin/orders" class="list-group-item list-group-item-action">
                        <i class="fas fa-shopping-cart me-2"></i>Pedidos
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/users" class="list-group-item list-group-item-action">
                        <i class="fas fa-users me-2"></i>Usuarios
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/promotions" class="list-group-item list-group-item-action">
                        <i class="fas fa-gift me-2"></i>Promociones
                    </a>
                    <a href="<?php echo SITE_URL; ?>admin/contacts" class="list-group-item list-group-item-action active">
                        <i class="fas fa-envelope me-2"></i>Contactos
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary-custom">Gestión de Contactos</h2>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary" onclick="markAllAsRead()">
                        <i class="fas fa-check-double me-2"></i>Marcar Todos como Leídos
                    </button>
                </div>
            </div>

            <!-- Estadísticas de contactos -->
            <div class="row g-3 mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3>45</h3>
                            <p class="mb-0">Total Mensajes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3>12</h3>
                            <p class="mb-0">No Leídos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3>33</h3>
                            <p class="mb-0">Respondidos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3>8</h3>
                            <p class="mb-0">Esta Semana</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lista de contactos -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Estado</th>
                                    <th>Nombre</th>
                                    <th>Email</th>
                                    <th>Teléfono</th>
                                    <th>Fecha</th>
                                    <th>Mensaje</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Datos de ejemplo para contactos
                                $sampleContacts = [
                                    ['id' => 1, 'nombre' => 'María García', 'email' => 'maria@email.com', 'telefono' => '3001234567', 'mensaje' => 'Hola, me interesa conocer más sobre sus productos de yogur griego...', 'fecha_contacto' => '2024-06-10 14:30:00', 'leido' => 0],
                                    ['id' => 2, 'nombre' => 'Carlos López', 'email' => 'carlos@email.com', 'telefono' => '3007654321', 'mensaje' => '¿Hacen entregas a domicilio en Medellín?', 'fecha_contacto' => '2024-06-10 12:15:00', 'leido' => 1],
                                    ['id' => 3, 'nombre' => 'Ana Rodríguez', 'email' => 'ana@email.com', 'telefono' => '3009876543', 'mensaje' => 'Me gustaría saber los precios de las tortas personalizadas...', 'fecha_contacto' => '2024-06-09 16:45:00', 'leido' => 0],
                                    ['id' => 4, 'nombre' => 'Luis Martínez', 'email' => 'luis@email.com', 'telefono' => '3005432109', 'mensaje' => '¿Tienen productos sin lactosa?', 'fecha_contacto' => '2024-06-09 10:20:00', 'leido' => 1],
                                    ['id' => 5, 'nombre' => 'Sofia Hernández', 'email' => 'sofia@email.com', 'telefono' => '3002468135', 'mensaje' => 'Excelente servicio, muy recomendado!', 'fecha_contacto' => '2024-06-08 18:30:00', 'leido' => 1]
                                ];
                                
                                foreach ($sampleContacts as $contact):
                                ?>
                                <tr class="<?php echo !$contact['leido'] ? 'table-warning' : ''; ?>">
                                    <td>
                                        <i class="fas fa-circle <?php echo !$contact['leido'] ? 'text-warning' : 'text-success'; ?>" 
                                           title="<?php echo !$contact['leido'] ? 'No leído' : 'Leído'; ?>"></i>
                                    </td>
                                    <td><?php echo $contact['nombre']; ?></td>
                                    <td><?php echo $contact['email']; ?></td>
                                    <td><?php echo $contact['telefono']; ?></td>
                                    <td><?php echo formatDateTime($contact['fecha_contacto']); ?></td>
                                    <td>
                                        <div style="max-width: 200px;">
                                            <?php echo substr($contact['mensaje'], 0, 50) . '...'; ?>
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="viewContact(<?php echo $contact['id']; ?>)">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-success" onclick="replyContact('<?php echo $contact['email']; ?>')">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                        <?php if (!$contact['leido']): ?>
                                        <button class="btn btn-sm btn-outline-warning" onclick="markAsRead(<?php echo $contact['id']; ?>)">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <?php endif; ?>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deleteContact(<?php echo $contact['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ver Contacto -->
<div class="modal fade" id="viewContactModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Contacto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="contactDetails">
                <!-- Aquí se cargarán los detalles del contacto -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-success" onclick="replyFromModal()">
                    <i class="fas fa-reply me-2"></i>Responder
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function viewContact(id) {
    // Simular carga de detalles del contacto
    const contactDetails = `
        <div class="row">
            <div class="col-md-6">
                <h6>Información del Contacto</h6>
                <p><strong>Nombre:</strong> María García</p>
                <p><strong>Email:</strong> maria@email.com</p>
                <p><strong>Teléfono:</strong> 3001234567</p>
                <p><strong>Fecha:</strong> 10/06/2024 14:30</p>
            </div>
            <div class="col-md-6">
                <h6>Mensaje</h6>
                <p>Hola, me interesa conocer más sobre sus productos de yogur griego. ¿Podrían enviarme información sobre precios y disponibilidad? También me gustaría saber si hacen entregas a domicilio en Bogotá.</p>
            </div>
        </div>
    `;
    
    document.getElementById('contactDetails').innerHTML = contactDetails;
    new bootstrap.Modal(document.getElementById('viewContactModal')).show();
}

function replyContact(email) {
    window.location.href = 'mailto:' + email + '?subject=Respuesta a tu consulta - Yogurt Artesanal San Francisco';
}

function replyFromModal() {
    // Implementar respuesta desde el modal
    alert('Abrir formulario de respuesta');
}

function markAsRead(id) {
    if (confirm('¿Marcar este mensaje como leído?')) {
        alert('Mensaje marcado como leído');
        location.reload();
    }
}

function markAllAsRead() {
    if (confirm('¿Marcar todos los mensajes como leídos?')) {
        alert('Todos los mensajes marcados como leídos');
        location.reload();
    }
}

function deleteContact(id) {
    if (confirm('¿Estás seguro de eliminar este contacto?')) {
        alert('Contacto eliminado');
        location.reload();
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>
