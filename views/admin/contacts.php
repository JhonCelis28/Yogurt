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
                <?php
                $totalContacts = count($contacts);
                $unreadContacts = count(array_filter($contacts, function($c) { return !$c['leido']; }));
                $readContacts = $totalContacts - $unreadContacts;
                $thisWeek = date('Y-m-d', strtotime('-7 days'));
                $thisWeekContacts = count(array_filter($contacts, function($c) use ($thisWeek) { 
                    return $c['fecha_contacto'] >= $thisWeek; 
                }));
                ?>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $totalContacts; ?></h3>
                            <p class="mb-0">Total Mensajes</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $unreadContacts; ?></h3>
                            <p class="mb-0">No Leídos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $readContacts; ?></h3>
                            <p class="mb-0">Leídos</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h3><?php echo $thisWeekContacts; ?></h3>
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
                                <?php if (!empty($contacts)): ?>
                                    <?php foreach ($contacts as $contact): ?>
                                    <tr class="<?php echo !$contact['leido'] ? 'table-warning' : ''; ?>">
                                        <td>
                                            <i class="fas fa-circle <?php echo !$contact['leido'] ? 'text-warning' : 'text-success'; ?>" 
                                               title="<?php echo !$contact['leido'] ? 'No leído' : 'Leído'; ?>"></i>
                                        </td>
                                        <td><?php echo $contact['nombre']; ?></td>
                                        <td><?php echo $contact['email']; ?></td>
                                        <td><?php echo $contact['telefono'] ?? 'N/A'; ?></td>
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
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay contactos registrados</td>
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
        window.location.href = '<?php echo SITE_URL; ?>admin/contacts/mark-read/' + id;
    }
}

function markAllAsRead() {
    if (confirm('¿Marcar todos los mensajes como leídos?')) {
        alert('Función en desarrollo');
    }
}

function deleteContact(id) {
    if (confirm('¿Estás seguro de eliminar este contacto?')) {
        window.location.href = '<?php echo SITE_URL; ?>admin/contacts/delete/' + id;
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>
