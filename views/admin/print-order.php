<?php
require_once 'config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>#<?php echo str_pad($order['id'], 3, '0', STR_PAD_LEFT); ?></title>
    <style>
        @media print {
            body { margin: 0; padding: 20px; }
            .no-print { display: none; }
            .page-break { page-break-after: always; }
            @page {
                margin-top: 2cm;
                margin-bottom: 2cm;
            }
        }
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
        }
        body::before {
            content: '';
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 600px;
            height: 600px;
            background-image: url('<?php echo SITE_URL; ?>assets/images/products/logo1.png');
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
        }
        .content-wrapper {
            position: relative;
            z-index: 1;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #E91E63;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #E91E63;
            margin: 0;
        }
        .header p {
            margin: 5px 0 0 0;
        }
        .order-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .info-box {
            flex: 1;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 5px;
            margin: 0 10px;
        }
        .info-box h3 {
            margin-top: 0;
            font-size: 14px;
            color: #666;
            text-transform: uppercase;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #E91E63;
            color: white;
            font-weight: bold;
        }
        .total-row {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 18px;
        }
        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-warning { background-color: #ffc107; color: #000; }
        .badge-info { background-color: #17a2b8; color: #fff; }
        .badge-primary { background-color: #007bff; color: #fff; }
        .badge-success { background-color: #28a745; color: #fff; }
        .badge-danger { background-color: #dc3545; color: #fff; }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
        }
        .personalizaciones {
            font-size: 12px;
            color: #666;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="content-wrapper">
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #E91E63; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            <i class="fas fa-print"></i> Imprimir
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-left: 10px;">
            Cerrar
        </button>
    </div>

    <div class="header">
        <h1>YOGURT ARTESANAL SAN FRANCISCO</h1>
    </div>

    <div class="order-info">
        <div class="info-box">
            <h3>Información del Pedido</h3>
            <p><strong>Pedido #<?php echo str_pad($order['id'], 3, '0', STR_PAD_LEFT); ?></strong></p>
            <p><strong>Fecha:</strong> <?php echo formatDateTime($order['fecha_pedido']); ?></p>
            <p>
                <strong>Estado:</strong> 
                <?php
                $estados = [
                    'pendiente' => ['class' => 'badge-warning', 'text' => 'Pendiente'],
                    'confirmado' => ['class' => 'badge-info', 'text' => 'Confirmado'],
                    'preparando' => ['class' => 'badge-primary', 'text' => 'Preparando'],
                    'enviado' => ['class' => 'badge-success', 'text' => 'Enviado'],
                    'entregado' => ['class' => 'badge-success', 'text' => 'Entregado'],
                    'cancelado' => ['class' => 'badge-danger', 'text' => 'Cancelado']
                ];
                $estado = $estados[$order['estado']] ?? ['class' => 'badge-secondary', 'text' => ucfirst($order['estado'])];
                ?>
                <span class="badge <?php echo $estado['class']; ?>"><?php echo $estado['text']; ?></span>
            </p>
            <p>
                <strong>Método de Pago:</strong> 
                <?php 
                $metodoPago = $order['metodo_pago'] ?? 'efectivo';
                $metodos = [
                    'efectivo' => 'Efectivo contra entrega',
                    'transferencia' => 'Transferencia bancaria',
                    'nequi' => 'Nequi',
                    'bancolombia' => 'Bancolombia Ahorro a la Mano'
                ];
                echo $metodos[$metodoPago] ?? ucfirst($metodoPago);
                ?>
            </p>
        </div>
        <div class="info-box">
            <h3>Cliente</h3>
            <p><strong><?php echo htmlspecialchars($order['cliente_nombre'] ?? 'N/A'); ?></strong></p>
            <p><?php echo htmlspecialchars($order['cliente_email'] ?? 'N/A'); ?></p>
            <?php if ($order['telefono_contacto']): ?>
                <p>Tel: <?php echo htmlspecialchars($order['telefono_contacto']); ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <h3 style="font-size: 16px; color: #666; margin-bottom: 10px;">Dirección de Entrega</h3>
        <p><?php echo htmlspecialchars($order['direccion_entrega'] ?? 'No especificada'); ?></p>
    </div>

    <?php if ($order['notas']): ?>
    <div style="margin-bottom: 20px;">
        <h3 style="font-size: 16px; color: #666; margin-bottom: 10px;">Notas del Pedido</h3>
        <p><?php echo nl2br(htmlspecialchars($order['notas'])); ?></p>
    </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th style="text-align: center;">Cantidad</th>
                <th style="text-align: right;">Precio Unit.</th>
                <th style="text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($order['items'])): ?>
                <?php foreach ($order['items'] as $item): ?>
                <tr>
                    <td>
                        <strong><?php echo htmlspecialchars($item['nombre']); ?></strong>
                        <?php if ($item['personalizaciones']): ?>
                            <?php
                            $personalizaciones = json_decode($item['personalizaciones'], true);
                            if ($personalizaciones):
                                $personalizacionesArray = [];
                                if (isset($personalizaciones['sabor'])) $personalizacionesArray[] = 'Sabor: ' . ucfirst($personalizaciones['sabor']);
                                if (isset($personalizaciones['endulzante'])) $personalizacionesArray[] = 'Endulzante: ' . ucfirst(str_replace('_', ' ', $personalizaciones['endulzante']));
                                if (isset($personalizaciones['nivel_dulzor'])) $personalizacionesArray[] = 'Dulzor: ' . ucfirst($personalizaciones['nivel_dulzor']);
                                if (isset($personalizaciones['tamaño'])) $personalizacionesArray[] = 'Tamaño: ' . ucfirst($personalizaciones['tamaño']);
                                if (!empty($personalizacionesArray)):
                            ?>
                                <div class="personalizaciones">
                                    <?php echo implode(', ', $personalizacionesArray); ?>
                                </div>
                            <?php endif; endif; ?>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;"><?php echo $item['cantidad']; ?></td>
                    <td style="text-align: right;"><?php echo formatPrice($item['precio_unitario']); ?></td>
                    <td style="text-align: right;"><strong><?php echo formatPrice($item['precio_unitario'] * $item['cantidad']); ?></strong></td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No hay items en este pedido</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <?php
            // Calcular subtotal de productos
            $subtotalProductos = 0;
            if (!empty($order['items'])) {
                foreach ($order['items'] as $item) {
                    $subtotalProductos += $item['precio_unitario'] * $item['cantidad'];
                }
            }
            
            // Obtener información de envases devueltos y promociones desde info_pago
            $infoPago = !empty($order['info_pago']) ? json_decode($order['info_pago'], true) : [];
            $envasesDevueltos = isset($infoPago['envases_devueltos']) ? (int)$infoPago['envases_devueltos'] : 0;
            $descuentoEnvases = isset($infoPago['descuento_envases']) ? (float)$infoPago['descuento_envases'] : 0;
            $subtotalSinDescuento = isset($infoPago['subtotal_sin_descuento']) ? (float)$infoPago['subtotal_sin_descuento'] : 0;
            $descuentoPromocion = isset($infoPago['descuento_promocion']) ? (float)$infoPago['descuento_promocion'] : 0;
            $promocionNombre = isset($infoPago['promocion_nombre']) ? $infoPago['promocion_nombre'] : '';
            
            // Si no hay subtotal_sin_descuento guardado, usar el subtotal de productos
            if ($subtotalSinDescuento == 0 || $subtotalSinDescuento == $order['total']) {
                $subtotalSinDescuento = $subtotalProductos;
            }
            
            // Calcular el subtotal después de aplicar descuento de envases
            $subtotalConEnvases = $subtotalSinDescuento - $descuentoEnvases;
            
            // Si hay descuento de promoción guardado, usarlo
            // Si no hay descuento guardado pero hay diferencia, verificar si es por envases o promoción
            if ($descuentoPromocion == 0 && $subtotalConEnvases > $order['total']) {
                $diferencia = $subtotalConEnvases - $order['total'];
                
                if ($diferencia > 0) {
                    // Verificar si es primera compra del usuario (más probable que sea promoción)
                    require_once 'models/Order.php';
                    $orderModel = new Order();
                    $userOrders = $orderModel->getUserOrders($order['usuario_id']);
                    $isFirstOrder = count($userOrders) == 1 || (count($userOrders) > 0 && $userOrders[0]['id'] == $order['id']);
                    
                    // Verificar si el descuento coincide con una promoción de primera compra
                    require_once 'models/Promotion.php';
                    $promotionModel = new Promotion();
                    $firstOrderPromo = $promotionModel->getFirstOrderPromotion();
                    
                    $esPromocion = false;
                    if ($isFirstOrder && $firstOrderPromo) {
                        // Calcular el descuento esperado de primera compra
                        if ($firstOrderPromo['tipo'] === 'porcentaje') {
                            $descuentoEsperado = ($subtotalConEnvases * $firstOrderPromo['valor_descuento']) / 100;
                            // Si el descuento coincide (con un margen de error pequeño), es promoción
                            if (abs($descuentoEsperado - $diferencia) < 100) {
                                $esPromocion = true;
                                $descuentoPromocion = $diferencia;
                                $promocionNombre = $firstOrderPromo['nombre'];
                            }
                        } else {
                            // Si es descuento fijo y coincide exactamente
                            if (abs($firstOrderPromo['valor_descuento'] - $diferencia) < 100) {
                                $esPromocion = true;
                                $descuentoPromocion = $diferencia;
                                $promocionNombre = $firstOrderPromo['nombre'];
                            }
                        }
                    }
                    
                    // Si no es promoción y es múltiplo de 2000, probablemente es envases
                    if (!$esPromocion && $diferencia % 2000 == 0 && $descuentoEnvases == 0) {
                        $descuentoEnvases = $diferencia;
                        $envasesDevueltos = $descuentoEnvases / 2000;
                    } else if (!$esPromocion && $diferencia % 2000 != 0) {
                        // Si no es múltiplo de 2000, probablemente es una promoción
                        $descuentoPromocion = $diferencia;
                    }
                }
            }
            ?>
            <tr>
                <td colspan="3" style="text-align: right;"><strong>Subtotal:</strong></td>
                <td style="text-align: right;">
                    <strong><?php echo formatPrice($subtotalSinDescuento); ?></strong>
                </td>
            </tr>
            <?php if ($descuentoEnvases > 0): ?>
            <tr style="color: #28a745;">
                <td colspan="3" style="text-align: right;">
                    <strong>Descuento por envases devueltos (<?php echo $envasesDevueltos; ?>):</strong>
                </td>
                <td style="text-align: right;">
                    <strong>-<?php echo formatPrice($descuentoEnvases); ?></strong>
                </td>
            </tr>
            <?php endif; ?>
            <?php if ($descuentoPromocion > 0): ?>
            <tr style="color: #ff6b6b;">
                <td colspan="3" style="text-align: right;">
                    <strong>Descuento por promoción<?php echo $promocionNombre ? ' (' . htmlspecialchars($promocionNombre) . ')' : ''; ?>:</strong>
                </td>
                <td style="text-align: right;">
                    <strong>-<?php echo formatPrice($descuentoPromocion); ?></strong>
                </td>
            </tr>
            <?php endif; ?>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;"><strong>TOTAL:</strong></td>
                <td style="text-align: right; color: #E91E63; font-size: 20px;">
                    <strong><?php echo formatPrice($order['total']); ?></strong>
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <p>Gracias por su compra</p>
        <p><small>Yogurt Artesanal San Francisco - Productos artesanales de calidad</small></p>
    </div>
    </div>
</body>
</html>

