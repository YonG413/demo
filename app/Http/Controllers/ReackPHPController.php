<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

/**
 * ReactPHP框架使用的一些场景
 */
class Controller extends BaseController
{
    

	 /**
	  * 高性能的 Web 服务器
	  */
	 public function reactSocket()
	 {
		require 'vendor/autoload.php';

		use React\EventLoop\Factory;
		use React\Http\Server;
		use Psr\Http\Message\ServerRequestInterface;
		use React\Http\Response;

		$loop = Factory::create();
		$server = new Server(function (ServerRequestInterface $request) {
			return new Response(
				200,
				array('Content-Type' => 'text/plain'),
				"Hello, World!\n"
			);
		});

		$socket = new \React\Socket\Server('0.0.0.0:8000', $loop);
		$server->listen($socket);

		$loop->run();
	 }


	 /**
	  * 实时应用程序（如聊天应用）
	  * 这是一个简单的聊天应用的示例，使用 ReactPHP 和 Ratchet 来构建 WebSocket 服务器。
	  */
	 public function webSocket()
	 {
		require 'vendor/autoload.php';

		use React\EventLoop\Factory;
		use Ratchet\Server\IoServer;
		use Ratchet\Http\HttpServer;
		use Ratchet\WebSocket\WsServer;
		use MyApp\Chat;

		$loop = Factory::create();
		$chat = new Chat();

		$webServer = new \React\Http\Server(function ($request) {
			// Serve static files or handle REST API requests
		});

		$webSocket = new \React\Socket\Server($loop);
		$webServer->listen($webSocket);

		$webSocketServer = new WsServer($chat);
		$webSocketServer->disableVersion(0); // disable old, insecure WebSocket versions

		$webSocket->listen(8080, '0.0.0.0');
		$webSocketServer->listen($webSocket);

		$loop->run();
	 }

	 /**
	  * 异步 API 客户端
	  * 这是一个简单的异步 API 客户端示例，使用 ReactPHP 的异步 HTTP 浏览器组件来发送 GET 请求并处理响应。
	  */
	 public function syncClient()
	 {
		require 'vendor/autoload.php';

		use React\EventLoop\Factory;
		use React\Http\Browser;

		$loop = Factory::create();
		$browser = new Browser($loop);

		$browser->get('http://example.com/')
			->then(function (Psr\Http\Message\ResponseInterface $response) {
				echo $response->getBody();
			});

		$loop->run();
	 }



}
