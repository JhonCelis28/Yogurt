<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title . ' - ' : ''; ?><?php echo SITE_NAME; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #E91E63;
            --secondary-color: #F8BBD9;
            --accent-color: #4CAF50;
            --light-pink: #FCE4EC;
            --dark-pink: #C2185B;
            --cream: #FFF8E1;
            --soft-purple: #E1BEE7;
            --mint-green: #A5D6A7;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #FAFAFA;
        }
        
        .text-primary-custom { color: var(--primary-color) !important; }
        .text-green-custom { color: var(--accent-color) !important; }
        .text-pink-custom { color: var(--secondary-color) !important; }
        .bg-primary-custom { background-color: var(--primary-color) !important; }
        .bg-light-pink { background-color: var(--light-pink) !important; }
        .bg-soft-purple { background-color: var(--soft-purple) !important; }
        .bg-mint-green { background-color: var(--mint-green) !important; }
        
        .btn-primary { 
            background: linear-gradient(45deg, var(--primary-color), var(--dark-pink));
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover { 
            background: linear-gradient(45deg, var(--dark-pink), var(--primary-color));
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(233, 30, 99, 0.3);
        }
        
        .btn-success {
            background: linear-gradient(45deg, var(--accent-color), #388E3C);
            border: none;
            border-radius: 25px;
            padding: 10px 25px;
            font-weight: 500;
        }
        
        .navbar {
            background: linear-gradient(135deg, #FFFFFF 0%, var(--light-pink) 100%);
            box-shadow: 0 2px 20px rgba(233, 30, 99, 0.1);
            border-bottom: 3px solid var(--secondary-color);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-brand img {
            height: 50px;
            width: auto;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1));
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--primary-color) !important;
            transition: all 0.3s ease;
            border-radius: 20px;
            margin: 0 5px;
            padding: 8px 15px !important;
        }
        
        .nav-link:hover {
            background-color: var(--light-pink);
            color: var(--dark-pink) !important;
            transform: translateY(-1px);
        }
        
        .hero-section {
            background: linear-gradient(135deg, var(--light-pink) 0%, var(--cream) 50%, var(--soft-purple) 100%);
            padding: 50px 0;
            position: relative;
            overflow: hidden;
        }
        
        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="%23E91E63" opacity="0.1"/><circle cx="80" cy="80" r="2" fill="%234CAF50" opacity="0.1"/><circle cx="40" cy="60" r="1.5" fill="%23F8BBD9" opacity="0.2"/></svg>');
        }
        
        .product-card {
            transition: all 0.3s ease;
            border: none;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(233, 30, 99, 0.1);
            background: linear-gradient(145deg, #FFFFFF, var(--light-pink));
            overflow: hidden;
        }
        
        .product-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 15px 40px rgba(233, 30, 99, 0.2);
        }
        
        .product-card img {
            border-radius: 20px 20px 0 0;
            transition: transform 0.3s ease;
        }
        
        .product-card:hover img {
            transform: scale(1.1);
        }
        
        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: linear-gradient(45deg, var(--accent-color), #2E7D32);
            color: white;
            border-radius: 50%;
            width: 22px;
            height: 22px;
            font-size: 12px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px rgba(76, 175, 80, 0.3);
        }
        
        .whatsapp-float {
            position: fixed;
            width: 65px;
            height: 65px;
            bottom: 30px;
            right: 30px;
            background: linear-gradient(45deg, #25d366, #128c7e);
            color: #FFF;
            border-radius: 50%;
            text-align: center;
            font-size: 28px;
            box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: pulse 2s infinite;
        }
        
        .whatsapp-float:hover {
            background: linear-gradient(45deg, #128c7e, #25d366);
            color: #FFF;
            transform: scale(1.1);
            box-shadow: 0 6px 25px rgba(37, 211, 102, 0.6);
        }
        
        @keyframes pulse {
            0% { box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4); }
            50% { box-shadow: 0 4px 20px rgba(37, 211, 102, 0.8); }
            100% { box-shadow: 0 4px 20px rgba(37, 211, 102, 0.4); }
        }
        
        .loading {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(233, 30, 99, 0.1);
            backdrop-filter: blur(5px);
            z-index: 9999;
        }
        
        .loading-spinner {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: var(--primary-color);
            font-size: 3rem;
        }
        
        .card {
            border-radius: 20px;
            border: none;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
        }
        
        .alert {
            border-radius: 15px;
            border: none;
        }
        
        .form-control {
            border-radius: 15px;
            border: 2px solid var(--secondary-color);
            padding: 12px 20px;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(233, 30, 99, 0.25);
        }
        
        @media (max-width: 768px) {
            .hero-section {
                padding: 40px 0;
            }
            
            .whatsapp-float {
                width: 55px;
                height: 55px;
                bottom: 20px;
                right: 20px;
                font-size: 24px;
            }
            
            .navbar-brand {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Spinner -->
    <div class="loading" id="loading">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="<?php echo SITE_URL; ?>">
                <img src="logo1.png" class="me-4">
                <span class="text-primary-custom"></span>
            </a>
            
            <button class="navbar-toggler border-4" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="fas fa-bars text-primary-custom"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>">
                            <i class="fas fa-home me-1"></i>Inicio
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>products">
                            <i class="fas fa-shopping-bag me-1"></i>Productos
                        </a>
                        <ul class="dropdown-menu border-0 shadow-lg" style="border-radius: 15px;">
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>products">Todos los Productos</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>products/category/1">Yogures Griegos</a></li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>products/category/2">Yogures con Frutas</a></li>
                            <li>
                                <a class="dropdown-item" href="<?php echo SITE_URL; ?>products/category/3">Postres de Yogur</a>
                            </li>
                            <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>products/category/4">Tortas Artesanales</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-success" href="<?php echo SITE_URL; ?>products/personalized">
                                <i class="fas fa-palette me-1"></i>Productos Personalizados
                            </a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>promotions">
                            <i class="fas fa-gift me-1"></i>Promociones
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo SITE_URL; ?>about">
                            <i class="fas fa-info-circle me-1"></i>Nosotros
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav">
                    <?php if (isLoggedIn()): ?>
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="<?php echo SITE_URL; ?>cart">
                                <i class="fas fa-shopping-cart"></i>
                                <?php 
                                $cartCount = getCartItemCount();
                                if ($cartCount > 0): 
                                ?>
                                    <span class="cart-badge"><?php echo $cartCount; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i><?php echo $_SESSION['user_name']; ?>
                            </a>
                            <ul class="dropdown-menu border-0 shadow-lg" style="border-radius: 15px;">
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>profile">
                                    <i class="fas fa-user me-2"></i>Mi Perfil
                                </a></li>
                                <li><a class="dropdown-item" href="<?php echo SITE_URL; ?>profile/orders">
                                    <i class="fas fa-shopping-bag me-2"></i>Mis Pedidos
                                </a></li>
                                <?php if (isAdmin()): ?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-primary" href="<?php echo SITE_URL; ?>admin">
                                        <i class="fas fa-cog me-2"></i>Panel Admin
                                    </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?php echo SITE_URL; ?>logout">
                                    <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                                </a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>login">
                                <i class="fas fa-sign-in-alt me-1"></i>Iniciar Sesión
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo SITE_URL; ?>register">
                                <i class="fas fa-user-plus me-1"></i>Registrarse
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Messages -->
    <div class="container mt-3">
        <?php showMessage(); ?>
    </div>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/573112668752?" class="whatsapp-float" target="_blank" title="Contactar por WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
