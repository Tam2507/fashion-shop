<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Tin Nhắn'); ?> - Fashion Shop</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background: #f0f2f5;
            overflow: hidden;
        }
        
        .messenger-app {
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .messenger-topbar {
            height: 60px;
            background: white;
            border-bottom: 1px solid #e4e6eb;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        
        .messenger-topbar h4 {
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            color: #050505;
        }
        
        .messenger-topbar .topbar-actions {
            margin-left: auto;
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .messenger-topbar .btn-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f0f2f5;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s;
            color: #050505;
        }
        
        .messenger-topbar .btn-icon:hover {
            background: #e4e6eb;
        }
        
        .messenger-topbar .user-info {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .messenger-topbar .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .messenger-topbar .user-avatar-placeholder {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
        }
        
        .messenger-content {
            flex: 1;
            overflow: hidden;
        }
    </style>
    
    <?php echo $__env->yieldContent('styles'); ?>
</head>
<body>
    <div class="messenger-app">
        <div class="messenger-topbar">
            <h4><i class="fas fa-comments text-primary"></i> Messenger</h4>
            
            <div class="topbar-actions">
                <?php if(auth()->guard()->check()): ?>
                    <div class="user-info">
                        <span class="text-muted small"><?php echo e(auth()->user()->name); ?></span>
                        <?php if(auth()->user()->avatar): ?>
                            <img src="/storage/<?php echo e(auth()->user()->avatar); ?>" 
                                 alt="<?php echo e(auth()->user()->name); ?>" 
                                 class="user-avatar">
                        <?php else: ?>
                            <div class="user-avatar-placeholder">
                                <i class="fas fa-user"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if(auth()->user() && auth()->user()->is_admin): ?>
                    <a href="<?php echo e(route('admin.home')); ?>" class="btn-icon" title="Quay về Admin">
                        <i class="fas fa-home"></i>
                    </a>
                <?php else: ?>
                    <a href="<?php echo e(route('home')); ?>" class="btn-icon" title="Quay về Trang chủ">
                        <i class="fas fa-home"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="messenger-content">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php echo $__env->yieldContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\Boutique\fashion-shop\resources\views/layouts/messenger.blade.php ENDPATH**/ ?>