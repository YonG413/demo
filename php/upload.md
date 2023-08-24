#### PHP上传文件，如何判断$files = $_FILES; 有错误


在PHP中，当有文件上传时，` $_FILES`会返回一个关联队列，其中包含上传的文件信息。如果要判断` $files = $_FILES`是否有错误，可以检查` $_FILES['error' ] `备份中的值。

` $_FILES['error'] `是一个与上传文件相关的错误代码。常见的错误代码有以下几种：

-  ` UPLOAD_ERR_OK `：没有错误，文件上传成功。
-  ` UPLOAD_ERR_INI_SIZE `：上传的文件超过了 php.ini 中` upload_max_filesize `指令设置的大小。
-  ` UPLOAD_ERR_FORM_SIZE `：上传的文件超过了HTML表单中` MAX_FILE_SIZE `设置的大小。
-  ` UPLOAD_ERR_PARTIAL `：文件仅部分已上传。
-  ` UPLOAD_ERR_NO_FILE `：没有文件被上传。
-  ` UPLOAD_ERR_NO_TMP_DIR `：找不到临时文件夹。
-  ` UPLOAD_ERR_CANT_WRITE `：文件写入失败。
-  ` UPLOAD_ERR_EXTENSION `：PHP扩展阻止了文件上传。

你可以通过检查` $_FILES['error'] `的值来判断上传是否有错误。例如：

``` php
if ($files['error'] === UPLOAD_ERR_OK) {
    // 没有错误，文件上传成功
    // 进行其他处理
} 别的 {
    // 文件上传时发生了错误
    开关 ($files['错误']) {
        案例 UPLOAD_ERR_INI_SIZE：
            echo "上传的文件大小超过了 php.ini 中的限制";
            休息;
        案例 UPLOAD_ERR_FORM_SIZE：
            echo "上传的文件大小超过了表单中的限制";
            休息;
        // ... 其他错误处理
        默认：
            echo "文件上传时发生了错误";
            休息;
    }
}
````

根据实际情况，您可以根据不同的错误代码来执行不同的错误处理操作。
