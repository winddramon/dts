<?php

if(!defined('IN_GAME')) {
    exit('Access Denied');
}

/**
 * Cloudreve API 客户端类
 */
class CloudreveClient {
    private $config;
    private $access_token;
    private $refresh_token;
    private $token_expires;
    
    public function __construct() {
        require_once GAME_ROOT.'./gamedata/cloudreve.config.php';
        global $cloudreve_config;
        $this->config = $cloudreve_config;
    }
    
    /**
     * 用户登录获取访问令牌
     */
    public function login() {
        $url = $this->config['host'] . '/api/' . $this->config['api_version'] . '/user/session';
        $data = array(
            'userName' => $this->config['username'],
            'Password' => $this->config['password']
        );
        
        $response = $this->makeRequest('POST', $url, $data);
        
        if ($response && isset($response['code']) && $response['code'] == 0) {
            $token_data = $response['data']['token'];
            $this->access_token = $token_data['access_token'];
            $this->refresh_token = $token_data['refresh_token'];
            $this->token_expires = strtotime($token_data['access_expires']);
            return true;
        }
        
        return false;
    }
    
    /**
     * 刷新访问令牌
     */
    public function refreshToken() {
        if (!$this->refresh_token) {
            return $this->login();
        }
        
        $url = $this->config['host'] . '/api/' . $this->config['api_version'] . '/user/session';
        $headers = array('Authorization: Bearer ' . $this->refresh_token);
        
        $response = $this->makeRequest('PUT', $url, array(), $headers);
        
        if ($response && isset($response['code']) && $response['code'] == 0) {
            $token_data = $response['data']['token'];
            $this->access_token = $token_data['access_token'];
            $this->refresh_token = $token_data['refresh_token'];
            $this->token_expires = strtotime($token_data['access_expires']);
            return true;
        }
        
        return $this->login();
    }
    
    /**
     * 确保访问令牌有效
     */
    private function ensureValidToken() {
        if (!$this->access_token || time() >= $this->token_expires - 300) {
            return $this->refreshToken();
        }
        return true;
    }
    
    /**
     * 获取文件列表
     */
    public function listFiles($path = '/') {
        if (!$this->ensureValidToken()) {
            return false;
        }
        
        $uri = 'cloudreve://my' . $path;
        $url = $this->config['host'] . '/api/' . $this->config['api_version'] . '/file/list';
        $data = array('uri' => $uri);
        $headers = array('Authorization: Bearer ' . $this->access_token);
        
        $response = $this->makeRequest('POST', $url, $data, $headers);
        
        if ($response && isset($response['code']) && $response['code'] == 0) {
            return $response['data'];
        }
        
        return false;
    }
    
    /**
     * 创建目录
     */
    public function createDirectory($path) {
        if (!$this->ensureValidToken()) {
            return false;
        }
        
        $uri = 'cloudreve://my' . $path;
        $url = $this->config['host'] . '/api/' . $this->config['api_version'] . '/directory';
        $data = array('uri' => $uri);
        $headers = array('Authorization: Bearer ' . $this->access_token);
        
        $response = $this->makeRequest('POST', $url, $data, $headers);
        
        return $response && isset($response['code']) && $response['code'] == 0;
    }
    
    /**
     * 上传文件
     */
    public function uploadFile($local_path, $remote_path) {
        if (!$this->ensureValidToken()) {
            return false;
        }
        
        if (!file_exists($local_path)) {
            return false;
        }
        
        // 确保远程目录存在
        $remote_dir = dirname($remote_path);
        if ($remote_dir != '/' && $remote_dir != '.') {
            $this->createDirectory($remote_dir);
        }
        
        // 获取上传策略
        $policy_url = $this->config['host'] . '/api/' . $this->config['api_version'] . '/file/upload';
        $policy_data = array(
            'path' => dirname($remote_path),
            'size' => filesize($local_path),
            'name' => basename($remote_path)
        );
        $headers = array('Authorization: Bearer ' . $this->access_token);
        
        $policy_response = $this->makeRequest('PUT', $policy_url, $policy_data, $headers);
        
        if (!$policy_response || $policy_response['code'] != 0) {
            return false;
        }
        
        // 执行文件上传
        $upload_data = $policy_response['data'];
        $upload_url = $upload_data['uploadURL'];
        
        return $this->uploadFileToUrl($local_path, $upload_url, $upload_data);
    }
    
    /**
     * 执行实际的文件上传
     */
    private function uploadFileToUrl($local_path, $upload_url, $upload_data) {
        $ch = curl_init();
        
        $post_data = array();
        if (isset($upload_data['credential'])) {
            foreach ($upload_data['credential'] as $key => $value) {
                $post_data[$key] = $value;
            }
        }
        
        $post_data['file'] = new CURLFile($local_path);
        
        curl_setopt_array($ch, array(
            CURLOPT_URL => $upload_url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->config['timeout'],
            CURLOPT_FOLLOWLOCATION => true
        ));
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        return $http_code >= 200 && $http_code < 300;
    }
    
    /**
     * 下载文件
     */
    public function downloadFile($remote_path, $local_path) {
        if (!$this->ensureValidToken()) {
            return false;
        }
        
        $uri = 'cloudreve://my' . $remote_path;
        $url = $this->config['host'] . '/api/' . $this->config['api_version'] . '/file/download';
        $data = array('uri' => $uri);
        $headers = array('Authorization: Bearer ' . $this->access_token);
        
        $response = $this->makeRequest('POST', $url, $data, $headers);
        
        if ($response && isset($response['code']) && $response['code'] == 0) {
            $download_url = $response['data']['downloadURL'];
            return $this->downloadFromUrl($download_url, $local_path);
        }
        
        return false;
    }
    
    /**
     * 从URL下载文件到本地
     */
    private function downloadFromUrl($download_url, $local_path) {
        $ch = curl_init();
        $fp = fopen($local_path, 'w+');
        
        curl_setopt_array($ch, array(
            CURLOPT_URL => $download_url,
            CURLOPT_FILE => $fp,
            CURLOPT_TIMEOUT => $this->config['timeout'],
            CURLOPT_FOLLOWLOCATION => true
        ));
        
        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        fclose($fp);
        
        if (!$result || $http_code < 200 || $http_code >= 300) {
            unlink($local_path);
            return false;
        }
        
        return true;
    }
    
    /**
     * 删除文件
     */
    public function deleteFile($remote_path) {
        if (!$this->ensureValidToken()) {
            return false;
        }
        
        $uri = 'cloudreve://my' . $remote_path;
        $url = $this->config['host'] . '/api/' . $this->config['api_version'] . '/file';
        $data = array('uri' => array($uri));
        $headers = array('Authorization: Bearer ' . $this->access_token);
        
        $response = $this->makeRequest('DELETE', $url, $data, $headers);
        
        return $response && isset($response['code']) && $response['code'] == 0;
    }
    
    /**
     * 发送HTTP请求
     */
    private function makeRequest($method, $url, $data = array(), $headers = array()) {
        $ch = curl_init();
        
        $default_headers = array(
            'Content-Type: application/json',
            'Accept: application/json'
        );
        
        $headers = array_merge($default_headers, $headers);
        
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $this->config['timeout'],
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ));
        
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method == 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method == 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if (!empty($data)) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($error) {
            return false;
        }
        
        return json_decode($response, true);
    }
}

/**
 * 回放文件管理函数
 */

/**
 * 获取本地回放文件列表
 */
function get_local_replay_files() {
    $replay_dir = GAME_ROOT . './gamedata/replays/';
    $files = array();
    
    if (!is_dir($replay_dir)) {
        return $files;
    }
    
    $handle = opendir($replay_dir);
    while (($file = readdir($handle)) !== false) {
        if ($file != '.' && $file != '..' && !is_dir($replay_dir . $file)) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            if (in_array($ext, array('dat', 'rep', 'js'))) {
                $files[] = array(
                    'name' => $file,
                    'size' => filesize($replay_dir . $file),
                    'mtime' => filemtime($replay_dir . $file),
                    'path' => $replay_dir . $file
                );
            }
        }
    }
    closedir($handle);
    
    // 按修改时间排序
    usort($files, function($a, $b) {
        return $b['mtime'] - $a['mtime'];
    });
    
    return $files;
}

/**
 * 备份回放文件到Cloudreve
 */
function backup_replay_to_cloudreve($filename) {
    global $cloudreve_config;
    
    if (!$cloudreve_config['enabled']) {
        return array('success' => false, 'message' => 'Cloudreve备份未启用');
    }
    
    $client = new CloudreveClient();
    if (!$client->login()) {
        return array('success' => false, 'message' => '登录Cloudreve失败');
    }
    
    $local_path = GAME_ROOT . './gamedata/replays/' . $filename;
    $remote_path = $cloudreve_config['replay_storage_path'] . '/' . $filename;
    
    if ($client->uploadFile($local_path, $remote_path)) {
        return array('success' => true, 'message' => '备份成功');
    } else {
        return array('success' => false, 'message' => '上传失败');
    }
}

/**
 * 从Cloudreve下载回放文件
 */
function download_replay_from_cloudreve($filename) {
    global $cloudreve_config;
    
    if (!$cloudreve_config['enabled']) {
        return array('success' => false, 'message' => 'Cloudreve备份未启用');
    }
    
    $client = new CloudreveClient();
    if (!$client->login()) {
        return array('success' => false, 'message' => '登录Cloudreve失败');
    }
    
    $local_path = GAME_ROOT . './gamedata/replays/' . $filename;
    $remote_path = $cloudreve_config['replay_storage_path'] . '/' . $filename;
    
    if ($client->downloadFile($remote_path, $local_path)) {
        return array('success' => true, 'message' => '下载成功');
    } else {
        return array('success' => false, 'message' => '下载失败');
    }
}

/**
 * 获取Cloudreve中的回放文件列表
 */
function get_cloudreve_replay_files() {
    global $cloudreve_config;
    
    if (!$cloudreve_config['enabled']) {
        return array();
    }
    
    $client = new CloudreveClient();
    if (!$client->login()) {
        return array();
    }
    
    $files = $client->listFiles($cloudreve_config['replay_storage_path']);
    if ($files && isset($files['objects'])) {
        return $files['objects'];
    }
    
    return array();
}

?>
