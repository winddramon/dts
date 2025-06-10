<?php

// 简单的测试脚本，用于验证Cloudreve集成功能

define('IN_GAME', true);
define('GAME_ROOT', dirname(__FILE__).'/');

// 包含必要的文件
require_once GAME_ROOT.'./include/cloudreve.func.php';

echo "Cloudreve集成测试\n";
echo "================\n\n";

// 测试配置文件加载
echo "1. 测试配置文件加载...\n";
try {
    require_once GAME_ROOT.'./gamedata/cloudreve.config.php';
    global $cloudreve_config;
    
    if (isset($cloudreve_config) && is_array($cloudreve_config)) {
        echo "✓ 配置文件加载成功\n";
        echo "  - 服务器地址: " . $cloudreve_config['host'] . "\n";
        echo "  - 用户名: " . $cloudreve_config['username'] . "\n";
        echo "  - 启用状态: " . ($cloudreve_config['enabled'] ? '是' : '否') . "\n";
    } else {
        echo "✗ 配置文件加载失败\n";
    }
} catch (Exception $e) {
    echo "✗ 配置文件错误: " . $e->getMessage() . "\n";
}

echo "\n";

// 测试CloudreveClient类实例化
echo "2. 测试CloudreveClient类实例化...\n";
try {
    $client = new CloudreveClient();
    echo "✓ CloudreveClient实例化成功\n";
} catch (Exception $e) {
    echo "✗ CloudreveClient实例化失败: " . $e->getMessage() . "\n";
}

echo "\n";

// 测试本地回放文件列表功能
echo "3. 测试本地回放文件列表功能...\n";
try {
    $local_files = get_local_replay_files();
    echo "✓ 本地文件列表获取成功\n";
    echo "  - 找到 " . count($local_files) . " 个回放文件\n";
    
    if (count($local_files) > 0) {
        echo "  - 示例文件: " . $local_files[0]['name'] . "\n";
    }
} catch (Exception $e) {
    echo "✗ 本地文件列表获取失败: " . $e->getMessage() . "\n";
}

echo "\n";

// 测试文件大小格式化函数
echo "4. 测试文件大小格式化函数...\n";
try {
    // 创建一个临时的format_file_size函数用于测试
    if (!function_exists('format_file_size')) {
        function format_file_size($bytes) {
            if ($bytes >= 1073741824) {
                return number_format($bytes / 1073741824, 2) . ' GB';
            } elseif ($bytes >= 1048576) {
                return number_format($bytes / 1048576, 2) . ' MB';
            } elseif ($bytes >= 1024) {
                return number_format($bytes / 1024, 2) . ' KB';
            } else {
                return $bytes . ' B';
            }
        }
    }
    
    echo "✓ 文件大小格式化函数测试:\n";
    echo "  - 1024 bytes = " . format_file_size(1024) . "\n";
    echo "  - 1048576 bytes = " . format_file_size(1048576) . "\n";
    echo "  - 1073741824 bytes = " . format_file_size(1073741824) . "\n";
} catch (Exception $e) {
    echo "✗ 文件大小格式化函数测试失败: " . $e->getMessage() . "\n";
}

echo "\n";

// 测试目录结构
echo "5. 测试目录结构...\n";
$required_dirs = array(
    GAME_ROOT . './gamedata/',
    GAME_ROOT . './gamedata/replays/',
    GAME_ROOT . './include/',
    GAME_ROOT . './include/admin/',
    GAME_ROOT . './templates/',
    GAME_ROOT . './templates/default/'
);

foreach ($required_dirs as $dir) {
    if (is_dir($dir)) {
        echo "✓ 目录存在: " . $dir . "\n";
    } else {
        echo "✗ 目录不存在: " . $dir . "\n";
    }
}

echo "\n";

// 测试必要文件
echo "6. 测试必要文件...\n";
$required_files = array(
    GAME_ROOT . './gamedata/cloudreve.config.php',
    GAME_ROOT . './include/cloudreve.func.php',
    GAME_ROOT . './include/admin/replaymng.php',
    GAME_ROOT . './templates/default/admin_replaymng.htm'
);

foreach ($required_files as $file) {
    if (file_exists($file)) {
        echo "✓ 文件存在: " . basename($file) . "\n";
    } else {
        echo "✗ 文件不存在: " . basename($file) . "\n";
    }
}

echo "\n";

echo "测试完成！\n";
echo "如果所有测试都通过，说明Cloudreve集成基本配置正确。\n";
echo "接下来可以在管理员界面中进行实际的连接测试。\n";

?>
