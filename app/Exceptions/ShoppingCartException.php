<?php

namespace App\Exceptions;

use Exception;

class ShoppingCartException extends Exception
{
    /**
     * 报告异常。
     *
     * @return void
     */
    public function report()
    {
        //
    }

    /**
     * 渲染异常为HTTP响应。
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'error' => $this->getMessage(),
        ], 400);
    }
}
