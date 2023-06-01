<?php

use Illuminate\Support\Facades\Validator;

 function validateInput($req)
{
    $checkInput = Validator::make($req->all(), [
        'ProductName' => 'required|unique|max:255',
        'SellPrice' => 'required',
        'sizePro' => 'require'
    ]);

    if ($checkInput->fails()) {
        return false;
    }
    return true;
}
