#### Undefined index: 字段名，如何规避这样的错误


"Undefined index" 错误通常是由于尝试访问数组中不存在的索引引起的。为了避免这个错误，你可以在访问数组索引之前进行检查，判断该索引是否存在。在 PHP 中，可以使用 `isset()` 或者 `array_key_exists()` 函数来进行判断。

下面是一些处理 "Undefined index" 错误的方法：

1. **使用 isset() 函数：**

   ```php
   if (isset($yourArray['one_yuan'])) {
       // 执行针对 $yourArray['one_yuan'] 的操作
   } else {
       // $yourArray['one_yuan'] 不存在，进行其他操作
   }
   ```

2. **使用 array_key_exists() 函数：**

   ```php
   if (array_key_exists('one_yuan', $yourArray)) {
       // 执行针对 $yourArray['one_yuan'] 的操作
   } else {
       // $yourArray['one_yuan'] 不存在，进行其他操作
   }
   ```

3. **使用三元表达式简化代码：**

   ```php
   $value = isset($yourArray['one_yuan']) ? $yourArray['one_yuan'] : null;
   // 或者
   $value = array_key_exists('one_yuan', $yourArray) ? $yourArray['one_yuan'] : null;
   ```

4. **使用 null 合并运算符（PHP 7.0+）：**

   ```php
   $value = $yourArray['one_yuan'] ?? null;
   ```

确保在访问数组索引之前进行适当的检查，以避免 "Undefined index" 错误。根据实际情况选择适合的方法来判断数组索引是否存在。
