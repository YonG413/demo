<?php

namespace App\Http\Controllers;

class SimulateLoginController extends Controller
{

    /**
     * 模拟登录如何跳过https
     * 在模拟登录时跳过 HTTPS 的过程中，
     * 您可以使用 cURL 库的 CURLOPT_SSL_VERIFYPEER 和 CURLOPT_SSL_VERIFYHOST 选项来禁用 SSL/TLS 验证。
     * 这样可以允许在不验证证书的情况下与 HTTPS 网站建立连接。
     * 
     * 
     * 我们使用 curl_setopt() 函数将 CURLOPT_SSL_VERIFYPEER 设置为 false，这将禁用对服务器证书的验证。
     * 我们还将 CURLOPT_SSL_VERIFYHOST 设置为 false，这将禁用对证书主机的验证。这样就允许在与 HTTPS 网站建立连接时跳过 SSL/TLS 验证。
     * 
     * 以下是一个示例代码：
     */
    public function index()
    {
        // 登录目标网站的 URL
        $loginUrl = 'https://example.com/login';

        // 登录表单的用户名和密码字段名
        $usernameField = 'username';
        $passwordField = 'password';

        // 登录表单的用户名和密码
        $username = 'your_username';
        $password = 'your_password';

        // 创建 cURL 句柄
        $ch = curl_init();

        // 设置 cURL 选项
        curl_setopt($ch, CURLOPT_URL, $loginUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            $usernameField => $username,
            $passwordField => $password
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // 禁用 SSL/TLS 验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        // 执行登录请求
        $response = curl_exec($ch);

        // 检查是否有错误发生
        if (curl_errno($ch)) {
            echo 'cURL error: ' . curl_error($ch);
            exit;
        }

        // 关闭 cURL 句柄
        curl_close($ch);

        // 在登录后的页面上执行进一步的操作，如抓取数据
        // 可以使用 $response 变量中的页面内容进行处理
        // 例如，使用 DOM 操作或正则表达式提取所需的数据

        // 示例：打印登录后的页面内容
        echo $response;
    }
}
