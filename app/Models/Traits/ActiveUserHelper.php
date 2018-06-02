<?php
/**
 * Created by PhpStorm.
 * User: xing
 * Date: 2018/6/2
 * Time: 17:16
 */

namespace App\Models\Traits;


use App\Models\Reply;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

trait ActiveUserHelper
{
    protected $users = [];

    protected $topic_weight = 4;
    protected $reply_weight = 1;
    protected $pass_days = 7;
    protected $user_number = 6;

    protected $cache_key = 'larabbs_active_users';
    protected $cache_expire_in_minutes = 65;

    public function getActiveUsers()
    {
        return Cache::remember($this->cache_key, $this->cache_expire_in_minutes, function () {
            return $this->calculateActiveUsers();
        });
    }

    public function calculateActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();
        $users = array_sort($this->users, function ($user) {
            return $user['score'];
        });
        $users = array_reverse($users, true);
        $users = array_slice($users, 0, $this->user_number, true);
        $active_users = collect();
        foreach ($users as $user_id => $user) {
            $user = $this->find($user_id);
            if ($user) {
                $active_users->push($user);
            }
        }
        return $active_users;
    }

    private function calculateTopicScore()
    {
        // 从话题数据表里取出限定时间范围（$pass_days）内，有发表过话题的用户
        // 并且同时取出用户此段时间内发布话题的数量
        $topic_users = Topic::query()->select(\DB::raw('user_id, count(*) as topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();
        // 根据话题数量计算得分
        foreach ($topic_users as $value) {
            $this->users[$value->user_id]['score'] = $value->topic_count * $this->topic_weight;
        }
    }

    protected function calculateReplyScore()
    {
        $reply_users = Reply::query()
            ->select(\DB::raw('user_id,count(*) as reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->pass_days))
            ->groupBy('user_id')
            ->get();
        foreach ($reply_users as $value) {
            $reply_score = $value->reply_count * $this->reply_weight;
            if (isset($this->users[$value->user_id])) {
                $this->users[$value->user_id]['score'] += $reply_score;
            } else {
                $this->users[$value->user_id]['score'] = $reply_score;
            }
        }
    }

    public function calculateAndCacheActiveUsers()
    {
        $active_users = $this->calculateActiveUsers();
        $this->cacheActiveUsers($active_users);
    }

    private function cacheActiveUsers($active_users)
    {
        Cache::put($this->cache_key, $active_users, $this->cache_expire_in_minutes);
    }
}