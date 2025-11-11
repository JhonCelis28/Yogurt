<?php 
$title = 'Editar Producto';
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
                    <a href="<?php echo SITE_URL; ?>admin/products" class="list-group-item list-group-item-action active">
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
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-primary-custom">Editar Producto</h2>
                <a href="<?php echo SITE_URL; ?>admin/products" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Volver
                </a>
            </div>

            <?php if ($product): ?>
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo SITE_URL; ?>admin/products/edit/<?php echo $product['id']; ?>" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label">Nombre del Producto *</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($product['nombre']); ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="categoria_id" class="form-label">Categoría *</label>
                                <select class="form-select" id="categoria_id" name="categoria_id" required>
                                    <option value="">Seleccionar categoría</option>
                                    <?php if (!empty($categories)): ?>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category['id']; ?>" <?php echo $category['id'] == $product['categoria_id'] ? 'selected' : ''; ?>>
                                                <?php echo $category['nombre']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="precio" class="form-label">Precio *</label>
                                <input type="number" class="form-control" id="precio" name="precio" step="0.01" value="<?php echo $product['precio']; ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="stock" class="form-label">Stock *</label>
                                <input type="number" class="form-control" id="stock" name="stock" value="<?php echo $product['stock']; ?>" required>
                            </div>
                            <div class="col-12">
                                <label for="descripcion" class="form-label">Descripción</label>
                                <textarea class="form-control" id="descripcion" name="descripcion" rows="3"><?php echo htmlspecialchars($product['descripcion'] ?? ''); ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="imagen" class="form-label">Imagen del Producto</label>
                                <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*">
                                <?php if ($product['imagen']): ?>
                                    <small class="text-muted">Imagen actual: <?php echo $product['imagen']; ?></small>
                                    <br>
                                    <img src="<?php echo SITE_URL; ?>assets/images/products/<?php echo $product['imagen']; ?>" alt="Imagen actual" style="max-width: 200px; margin-top: 10px;" class="img-thumbnail">
                                <?php endif; ?>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="es_personalizable" name="es_personalizable" <?php echo $product['es_personalizable'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="es_personalizable">
                                        Producto Personalizable
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="destacado" name="destacado" <?php echo $product['destacado'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="destacado">
                                        Producto Destacado
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Producto
                            </button>
                            <a href="<?php echo SITE_URL; ?>admin/products" class="btn btn-secondary">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-danger">
                Producto no encontrado.
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layout/footer.php'; ?>

