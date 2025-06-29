<?php 
$title = 'Mis Direcciones';
include 'views/layout/header.php'; 
?>

<div class="container py-5">
    <div class="row">
        <!-- Sidebar del perfil -->
        <div class="col-lg-3 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-4x text-primary-custom"></i>
                    </div>
                    <h5><?php echo $_SESSION['user_name']; ?></h5>
                    <p class="text-muted"><?php echo $_SESSION['user_email']; ?></p>
                </div>
            </div>
            
            <div class="list-group mt-3">
                <a href="<?php echo SITE_URL; ?>profile" class="list-group-item list-group-item-action">
                    <i class="fas fa-user me-2"></i>Mi Perfil
                </a>
                <a href="<?php echo SITE_URL; ?>profile/orders" class="list-group-item list-group-item-action">
                    <i class="fas fa-shopping-bag me-2"></i>Mis Pedidos
                </a>
                <a href="<?php echo SITE_URL; ?>profile/addresses" class="list-group-item list-group-item-action active">
                    <i class="fas fa-map-marker-alt me-2"></i>Direcciones
                </a>
            </div>
        </div>

        <!-- Contenido principal -->
        <div class="col-lg-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="fas fa-map-marker-alt me-2"></i>Mis Direcciones</h4>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                        <i class="fas fa-plus me-2"></i>Agregar Dirección
                    </button>
                </div>
                <div class="card-body">
                    <?php if (!empty($addresses)): ?>
                        <div class="row g-3">
                            <?php foreach ($addresses as $address): ?>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="card-title"><?php echo $address['nombre']; ?></h6>
                                            <?php if ($address['es_principal']): ?>
                                                <span class="badge bg-primary">Principal</span>
                                            <?php endif; ?>
                                        </div>
                                        <p class="card-text">
                                            <?php echo $address['direccion']; ?><br>
                                            <?php echo $address['ciudad']; ?>, <?php echo $address['departamento']; ?><br>
                                            <strong>Tel:</strong> <?php echo $address['telefono']; ?>
                                        </p>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline-primary" onclick="editAddress(<?php echo $address['id']; ?>)">
                                                <i class="fas fa-edit"></i> Editar
                                            </button>
                                            <?php if (!$address['es_principal']): ?>
                                                <button class="btn btn-sm btn-outline-success" onclick="setPrincipal(<?php echo $address['id']; ?>)">
                                                    <i class="fas fa-star"></i> Principal
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="deleteAddress(<?php echo $address['id']; ?>)">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="fas fa-map-marker-alt fa-4x text-muted mb-3"></i>
                            <h4 class="text-muted">No tienes direcciones guardadas</h4>
                            <p class="text-muted">Agrega una dirección para facilitar tus pedidos</p>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                                <i class="fas fa-plus me-2"></i>Agregar Primera Dirección
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Agregar Dirección -->
<div class="modal fade" id="addAddressModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nueva Dirección</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo SITE_URL; ?>profile/addresses/add">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre de la Dirección *</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Ej: Casa, Oficina, etc." required>
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label">Dirección Completa *</label>
                        <textarea class="form-control" id="direccion" name="direccion" rows="2" placeholder="Calle, carrera, número, apartamento, etc." required></textarea>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="ciudad" class="form-label">Ciudad *</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" required>
                        </div>
                        <div class="col-md-6">
                            <label for="departamento" class="form-label">Departamento *</label>
                            <input type="text" class="form-control" id="departamento" name="departamento" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono de Contacto</label>
                        <input type="tel" class="form-control" id="telefono" name="telefono" placeholder="Teléfono para la entrega">
                    </div>
                    <div class="mb-3">
                        <label for="instrucciones" class="form-label">Instrucciones de Entrega</label>
                        <textarea class="form-control" id="instrucciones" name="instrucciones" rows="2" placeholder="Ej: Portería, timbre, referencias, etc."></textarea>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="es_principal" name="es_principal">
                        <label class="form-check-label" for="es_principal">
                            Establecer como dirección principal
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Dirección</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editAddress(id) {
    alert('Editar dirección ID: ' + id);
}

function setPrincipal(id) {
    if (confirm('¿Establecer esta dirección como principal?')) {
        alert('Dirección establecida como principal');
    }
}

function deleteAddress(id) {
    if (confirm('¿Estás seguro de eliminar esta dirección?')) {
        alert('Dirección eliminada');
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>
