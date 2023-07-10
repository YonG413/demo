<?php

/**
*laravel把参数并入request里
*要将参数并入 Laravel 的 Request 对象中，可以使用 merge() 方法将参数数组合并到 Request 对象中
**/
use Illuminate\Http\Request;
class mergeRequestController {
	

	public function index(Request $request)
	{
		$parameters = ['param1' => 'value1', 'param2' => 'value2'];

		$request->merge($parameters);

		// 现在 $request 对象中包含了合并后的参数

		// 可以通过 $request->input('param1') 或 $request->param1 来获取参数值
	}
	
}

/**
*在上述代码中，我们将参数数组 $parameters 使用 merge() 方法合并到 $request *对象中。现在 $request 对象中包含了合并后的参数，你可以通过 $request->input('param1') 或 $request->param1 来获取参数值。

*请注意，使用 merge() *方法后，合并的参数将作为请求参数在整个请求周期中可用。你可以在控制器方法、中间**件或其他地方访问和使用这些参数。
*/