<?php namespace Bluhex\YouTube\Components;

use Cache;
use Bluhex\YouTube\Models\Settings;
use Cms\Classes\ComponentBase;
use Bluhex\YouTube\Classes\YouTubeClient;


/**
 * Latest Videos component
 *
 * @author Brendon Park
 *
 */
class LatestVideos extends ComponentBase
{

    public $videos;

    public function componentDetails()
    {
        return [
            'name'        => 'Latest Videos',
            'description' => 'Display a list of latest YouTube videos for a channel'
        ];
    }

    public function defineProperties()
    {
        return [
            'channel_id' => [
                'title' => 'Channel Id',
                'description' => 'The YouTube Channel Id to query against. youtube.com/account_advanced',
                'type' => 'string',
            ],
            'max_items' => [
                'title' => 'Max Items',
                'description' => 'Maximum number of results',
                'default' => '12'
            ]
        ];
    }

    public function onRun()
    {
        $channelId = $this->property('channel_id');
        $maxItems = $this->property('max_items');
        $cacheKey = YouTubeClient::instance()->getLatestCacheKey($channelId, $maxItems);

        $this->videos = Cache::remember($cacheKey, Settings::get('cache_time'), function() use ($channelId, $maxItems) {

            return YouTubeClient::instance()->getLatest($channelId, $maxItems);

        });
    }


}