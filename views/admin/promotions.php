<?php 
$title = 'Gestión de Promociones';
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
                    <a href="<?php echo SITE_URL; ?>admin/promotions" class="list-group-item list-group-item-action active">
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
                <h2 class="text-primary-custom">Gestión de Promociones</h2>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPromotionModal">
                    <i class="fas fa-plus me-2"></i>Crear Promoción
                </button>
            </div>

            <!-- Tabla de promociones -->
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Descuento</th>
                                    <th>Código</th>
                                    <th>Vigencia</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Datos de ejemplo para promociones
                                $samplePromotions = [
                                    ['id' => 1, 'nombre' => 'Primera Compra', 'tipo' => 'porcentaje', 'valor' => 15, 'codigo' => 'PRIMERA15', 'fecha_inicio' => '2024-01-01', 'fecha_fin' => '2024-12-31', 'activo' => 1],
                                    ['id' => 2, 'nombre' => 'Combo Familiar', 'tipo' => 'porcentaje', 'valor' => 20, 'codigo' => 'FAMILIA20', 'fecha_inicio' => '2024-01-01', 'fecha_fin' => '2024-12-31', 'activo' => 1],
                                    ['id' => 3, 'nombre' => 'Envase Devuelto', 'tipo' => 'monto_fijo', 'valor' => 2000, 'codigo' => 'ENVASE2000', 'fecha_inicio' => '2024-01-01', 'fecha_fin' => '2024-12-31', 'activo' => 1],
                                    ['id' => 4, 'nombre' => 'Fin de Semana', 'tipo' => 'porcentaje', 'valor' => 10, 'codigo' => 'WEEKEND10', 'fecha_inicio' => '2024-06-01', 'fecha_fin' => '2024-06-30', 'activo' => 0]
                                ];
                                
                                foreach ($samplePromotions as $promotion):
                                ?>
                                <tr>
                                    <td><?php echo $promotion['id']; ?></td>
                                    <td><?php echo $promotion['nombre']; ?></td>
                                    <td>
                                        <span class="badge <?php echo $promotion['tipo'] == 'porcentaje' ? 'bg-info' : 'bg-success'; ?>">
                                            <?php echo $promotion['tipo'] == 'porcentaje' ? 'Porcentaje' : 'Monto Fijo'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php echo $promotion['tipo'] == 'porcentaje' ? $promotion['valor'] . '%' : formatPrice($promotion['valor']); ?>
                                    </td>
                                    <td><code><?php echo $promotion['codigo']; ?></code></td>
                                    <td>
                                        <small>
                                            <?php echo formatDate($promotion['fecha_inicio']); ?> - 
                                            <?php echo formatDate($promotion['fecha_fin']); ?>
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge <?php echo $promotion['activo'] ? 'bg-success' : 'bg-secondary'; ?>">
                                            <?php echo $promotion['activo'] ? 'Activa' : 'Inactiva'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary" onclick="editPromotion(<?php echo $promotion['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" onclick="togglePromotion(<?php echo $promotion['id']; ?>)">
                                            <i class="fas fa-toggle-on"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" onclick="deletePromotion(<?php echo $promotion['id']; ?>)">
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

<!-- Modal Crear Promoción -->
<div class="modal fade" id="addPromotionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crear Nueva Promoción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo SITE_URL; ?>admin/promotions/add">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="nombre" class="form-label">Nombre de la Promoción *</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="col-md-6">
                            <label for="codigo" class="form-label">Código de Promoción *</label>
                            <input type="text" class="form-control" id="codigo" name="codigo" required>
                        </div>
                        <div class="col-md-6">
                            <label for="tipo" class="form-label">Tipo de Descuento *</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccionar tipo</option>
                                <option value="porcentaje">Porcentaje</option>
                                <option value="monto_fijo">Monto Fijo</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="valor_descuento" class="form-label">Valor del Descuento *</label>
                            <input type="number" class="form-control" id="valor_descuento" name="valor_descuento" step="0.01" required>
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio">
                        </div>
                        <div class="col-md-6">
                            <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="fecha_fin" name="fecha_fin">
                        </div>
                        <div class="col-12">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Promoción</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editPromotion(id) {
    alert('Editar promoción ID: ' + id);
}

function togglePromotion(id) {
    if (confirm('¿Cambiar el estado de esta promoción?')) {
        alert('Cambiar estado de promoción ID: ' + id);
    }
}

function deletePromotion(id) {
    if (confirm('¿Estás seguro de eliminar esta promoción?')) {
        alert('Eliminar promoción ID: ' + id);
    }
}
</script>

<?php include 'views/layout/footer.php'; ?>
