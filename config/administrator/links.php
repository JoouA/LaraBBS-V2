<?php
use App\Models\Link;

return  [
    'title' => '资源推荐',
    'single' => '资源推荐',

    'model' => Link::class,

    //访问权限
    'permission' => function(){
        return Auth::user()->hasRole('Founder');
    },

    'columns' => [
        'id' => [
            'title' => 'ID',
        ],
        'avatar' => [
            'title' => '资源照片',
            'sortable' => false,
            'output' => function($value){
                return '<img src="'.$value.'" style="width:28px;height:28px"/>';
            },
        ],
        'title' => [
            'title' => '名称',
            'sortable' => false,
        ],
        'link' => [
            'title' => '链接',
            'sortable' => false,
        ],
        'operation' => [
            'title' => '管理',
            'sortable' => false,
        ],
    ],

    'edit_fields' => [
        'avatar' => [
            'title' => '资源图片',
            'type' => 'image',
            // 图片上传必须设置图片存放路径
            'location' => public_path() .  '/uploads/images/links/',
        ],
        'title' => [
            'title' => '名称'
        ],
        'link' => [
            'title' => '链接'
        ],
    ],

    'filters' => [
        'id' => [
            'title' => 'ID',
        ],
        'title' => [
            'title' => '名称',
        ]
    ]
];
