在Go语言的HTTP Web应用中，可以通过设置HTTP响应头来解决跨域问题。以下是一种常见的解决方案，具体步骤如下：

1 在处理HTTP请求的处理器函数中，设置响应头以允许跨域访问

```
func handleRequest(w http.ResponseWriter, r *http.Request) {
    // 设置允许跨域访问的来源（Origin）
    w.Header().Set("Access-Control-Allow-Origin", "*")

    // 设置允许跨域访问的方法（GET、POST等）
    w.Header().Set("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE")

    // 设置允许跨域访问的请求头
    w.Header().Set("Access-Control-Allow-Headers", "Content-Type, Authorization")

    // 处理请求逻辑...
}

```

2 在路由设置或HTTP处理器注册时，将请求路由到上述处理器函数。
```
func main() {
    http.HandleFunc("/api", handleRequest)
    http.ListenAndServe(":8080", nil)
}

```