<?php

function customPath($path = '')
{
    return $path
        ? base_path("custom/$path")
        : base_path('custom');
}
