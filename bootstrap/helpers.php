<?php

function route_class()
{
    // topic.index
    return str_replace('.','-',Route::currentRouteName());
}

function make_excerpt($value,$length = 200)
{
    //  剥去字符串中的 HTML 标签：
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/','',strip_tags($value)));

    return str_limit($excerpt,$length);
}