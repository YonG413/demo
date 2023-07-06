<?php
/**
 * foreach里获取到的$valueId，用"_"组合起来
 * 
 */
class CombinedValueController{

    /**
     *如果你想将 foreach 循环中获取到的 $valueId 值使用下划线 "_" 连接起来，
	 *可以在循环过程中使用一个字符串变量来进行拼接。以下是示例代码
     */
    public function index()
    {
        $combinedValue = ''; // 初始化拼接的字符串变量

		foreach ($yourArray as $valueId) {
			$combinedValue .= $valueId . '_'; // 使用下划线连接当前的 $valueId
		}

		$combinedValue = rtrim($combinedValue, '_'); // 去除最后一个多余的下划线

		echo $combinedValue; // 输出拼接后的结果

    }

    /**
     * 在上述代码中，我们初始化了一个空的字符串变量 $combinedValue，然后在 foreach 循环中，
	 * 将每个 $valueId 使用下划线连接到 $combinedValue 中。最后，
	 * 使用 rtrim() 函数去除最后一个多余的下划线。最终，我们可以输出拼接后的结果。
	 */
     
}

