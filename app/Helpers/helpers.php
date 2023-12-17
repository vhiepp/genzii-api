<?php

use App\Helpers\NumberHelper;
use App\Helpers\ResponseHelper;

function numberhelper(): NumberHelper
{
    return new NumberHelper();
}

function reshelper(): ResponseHelper
{
    return new ResponseHelper();
}
