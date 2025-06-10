<?php

if(!defined('IN_ADMIN')) {
    exit('Access Denied');
}

require_once GAME_ROOT.'./include/cloudreve.func.php';
require_once GAME_ROOT.'./gamedata/cloudreve.config.php';

$action = !empty($_POST['action']) ? $_POST['action'] : (!empty($_GET['action']) ? $_GET['action'] : '');
$filename = !empty($_POST['filename']) ? $_POST['filename'] : (!empty($_GET['filename']) ? $_GET['filename'] : '');

$message = '';
$error = '';

// 处理各种操作
switch($action) {
    case 'backup':
        if ($filename) {
            $result = backup_replay_to_cloudreve($filename);
            if ($result['success']) {
                $message = "文件 {$filename} 备份成功";
                adminlog('backup_replay', $filename);
            } else {
                $error = "备份失败: " . $result['message'];
            }
        }
        break;
        
    case 'download':
        if ($filename) {
            $result = download_replay_from_cloudreve($filename);
            if ($result['success']) {
                $message = "文件 {$filename} 下载成功";
                adminlog('download_replay', $filename);
            } else {
                $error = "下载失败: " . $result['message'];
            }
        }
        break;
        
    case 'delete_local':
        if ($filename) {
            $local_path = GAME_ROOT . './gamedata/replays/' . $filename;
            if (file_exists($local_path)) {
                if (unlink($local_path)) {
                    $message = "本地文件 {$filename} 删除成功";
                    adminlog('delete_local_replay', $filename);
                } else {
                    $error = "删除本地文件失败";
                }
            } else {
                $error = "本地文件不存在";
            }
        }
        break;
        
    case 'delete_remote':
        if ($filename) {
            global $cloudreve_config;
            if ($cloudreve_config['enabled']) {
                $client = new CloudreveClient();
                if ($client->login()) {
                    $remote_path = $cloudreve_config['replay_storage_path'] . '/' . $filename;
                    if ($client->deleteFile($remote_path)) {
                        $message = "远程文件 {$filename} 删除成功";
                        adminlog('delete_remote_replay', $filename);
                    } else {
                        $error = "删除远程文件失败";
                    }
                } else {
                    $error = "登录Cloudreve失败";
                }
            } else {
                $error = "Cloudreve备份未启用";
            }
        }
        break;
        
    case 'batch_backup':
        $selected_files = !empty($_POST['selected_files']) ? $_POST['selected_files'] : array();
        $success_count = 0;
        $fail_count = 0;
        
        foreach ($selected_files as $file) {
            $result = backup_replay_to_cloudreve($file);
            if ($result['success']) {
                $success_count++;
                adminlog('batch_backup_replay', $file);
            } else {
                $fail_count++;
            }
        }
        
        if ($success_count > 0) {
            $message = "批量备份完成: 成功 {$success_count} 个文件";
            if ($fail_count > 0) {
                $message .= ", 失败 {$fail_count} 个文件";
            }
        } else {
            $error = "批量备份失败";
        }
        break;
        
    case 'batch_delete_local':
        $selected_files = !empty($_POST['selected_files']) ? $_POST['selected_files'] : array();
        $success_count = 0;
        $fail_count = 0;
        
        foreach ($selected_files as $file) {
            $local_path = GAME_ROOT . './gamedata/replays/' . $file;
            if (file_exists($local_path) && unlink($local_path)) {
                $success_count++;
                adminlog('batch_delete_local_replay', $file);
            } else {
                $fail_count++;
            }
        }
        
        if ($success_count > 0) {
            $message = "批量删除完成: 成功 {$success_count} 个文件";
            if ($fail_count > 0) {
                $message .= ", 失败 {$fail_count} 个文件";
            }
        } else {
            $error = "批量删除失败";
        }
        break;
        
    case 'update_config':
        $config_data = array(
            'host' => !empty($_POST['host']) ? $_POST['host'] : '',
            'username' => !empty($_POST['username']) ? $_POST['username'] : '',
            'password' => !empty($_POST['password']) ? $_POST['password'] : '',
            'enabled' => !empty($_POST['enabled']) ? true : false,
            'replay_storage_path' => !empty($_POST['replay_storage_path']) ? $_POST['replay_storage_path'] : '/replays',
            'auto_delete_local_days' => (int)(!empty($_POST['auto_delete_local_days']) ? $_POST['auto_delete_local_days'] : 0)
        );
        
        if (update_cloudreve_config($config_data)) {
            $message = "配置更新成功";
            adminlog('update_cloudreve_config');
        } else {
            $error = "配置更新失败";
        }
        break;
        
    case 'test_connection':
        $client = new CloudreveClient();
        if ($client->login()) {
            $message = "连接测试成功";
        } else {
            $error = "连接测试失败，请检查配置";
        }
        break;
}

// 获取本地和远程文件列表
$local_files = get_local_replay_files();
$remote_files = get_cloudreve_replay_files();

// 计算统计信息
$local_total_size = 0;
$local_file_count = count($local_files);
foreach ($local_files as $file) {
    $local_total_size += $file['size'];
}

$remote_file_count = count($remote_files);
$remote_total_size = 0;
foreach ($remote_files as $file) {
    if (isset($file['size'])) {
        $remote_total_size += $file['size'];
    }
}

// 格式化文件大小
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

// 更新Cloudreve配置
function update_cloudreve_config($new_config) {
    $config_file = GAME_ROOT . './gamedata/cloudreve.config.php';
    
    // 读取现有配置
    global $cloudreve_config;
    require $config_file;
    
    // 更新配置
    foreach ($new_config as $key => $value) {
        if (array_key_exists($key, $cloudreve_config)) {
            $cloudreve_config[$key] = $value;
        }
    }
    
    // 写入配置文件
    $config_content = "<?php\n\nif(!defined('IN_GAME')) {\n    exit('Access Denied');\n}\n\n";
    $config_content .= "// Cloudreve 网盘配置\n";
    $config_content .= "\$cloudreve_config = " . var_export($cloudreve_config, true) . ";\n\n?>";
    
    return file_put_contents($config_file, $config_content) !== false;
}

// 检查文件是否在远程存在
function is_file_in_remote($filename, $remote_files) {
    foreach ($remote_files as $remote_file) {
        if (isset($remote_file['name']) && $remote_file['name'] == $filename) {
            return true;
        }
    }
    return false;
}

// 检查文件是否在本地存在
function is_file_in_local($filename, $local_files) {
    foreach ($local_files as $local_file) {
        if ($local_file['name'] == $filename) {
            return true;
        }
    }
    return false;
}

// 传递变量给模板
global $cloudreve_config;

include template('admin_replaymng');

?>
