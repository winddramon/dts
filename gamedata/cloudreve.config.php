<?php

if(!defined('IN_GAME')) {
    exit('Access Denied');
}

// Cloudreve 网盘配置
$cloudreve_config = array(
    // Cloudreve 服务器地址 (不要以/结尾)
    'host' => 'https://your-cloudreve-server.com',
    
    // 用户名
    'username' => 'admin',
    
    // 密码
    'password' => 'your_password',
    
    // API 版本
    'api_version' => 'v4',
    
    // 连接超时时间 (秒)
    'timeout' => 30,
    
    // 回放文件在Cloudreve中的存储路径
    'replay_storage_path' => '/replays',
    
    // 是否启用Cloudreve备份
    'enabled' => false,
    
    // 自动删除本地文件的天数 (0表示不自动删除)
    'auto_delete_local_days' => 30,
    
    // 最大重试次数
    'max_retry' => 3,
    
    // 是否压缩上传
    'compress_upload' => true,
    
    // 允许的文件扩展名
    'allowed_extensions' => array('dat', 'rep', 'js'),
    
    // 单次上传最大文件大小 (MB)
    'max_file_size' => 100,
    
    // 批量操作时的文件数量限制
    'batch_limit' => 50
);

?>
