<?php

function route_class()
{
    // topic.index
    return str_replace('.','-',Route::currentRouteName());
}