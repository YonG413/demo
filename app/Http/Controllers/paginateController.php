<?php

use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
/**
 * laravel如何将array里的数据，每页20条，转为分页
 */
class paginateController {

    /**
     * 在 Laravel 中，你可以使用 paginate 方法将一个数组分割为分页。以下是将一个数组转换为分页的示例：
     */
    public function index() {
        $perPage = 20; // 每页显示的数量
        $array = [...] // 你的数组数据

        $collection = collect($array); // 将数组转换为集合
        $currentPage = Paginator::resolveCurrentPage(); // 获取当前页码

        // 使用分页器将集合分割为分页
        $paginatedData = new LengthAwarePaginator(
            $collection->forPage($currentPage, $perPage), // 当前页显示的数据
            $collection->count(), // 总数据数量
            $perPage, // 每页显示的数量
            $currentPage, // 当前页码
            ['path' => Paginator::resolveCurrentPath()] // URL 配置项，可根据需要修改
        );

        // 可以通过 $paginatedData->items() 获取当前页的数据，通过 $paginatedData->links() 获取分页链接

    }
}