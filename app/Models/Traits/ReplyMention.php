<?php
namespace App\Models\Traits;


use App\Models\User;

trait ReplyMention
{
    public $users = [];

    /**
     * 回去reply内容中的用户
     * @param $content
     * @return array
     */
    public function getMentionUser($content)
    {
        preg_match_all("/<a.*?>@(.*?)<\/a>/i", $content, $atlist_tmp);

        $mention_users = $atlist_tmp[1];

        count($mention_users) > 0 && $this->users = User::whereIn('name',$mention_users)->get();

        return $this->users;
    }
}