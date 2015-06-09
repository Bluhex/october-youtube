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
            ],
            'thumb_resolution' => [
                'title' => 'Thumbnail Size',
                'type' => 'dropdown',
                'description' => "Thumbnails may return cropped images as per the YouTube API.
                                    However, 'Full Resolution' may fail to find an image, but won't be cropped.",
                'default' => 'medium',
                'options' => [  'full-resolution' => 'Full Resolution',
                                'high' => 'High',
                                'medium' => 'Medium',
                                'default' => 'Default']
            ]
        ];
    }

    public function onRun()
    {
        $channelId = $this->property('channel_id');
        $maxItems = $this->property('max_items');
        $thumbResolution = $this->property('thumb_resolution');
        $cacheKey = YouTubeClient::instance()->getLatestCacheKey($channelId, $maxItems, $thumbResolution);

        $this->videos = Cache::remember($cacheKey,
                                        Settings::get('cache_time'),
                                        function() use ($channelId, $maxItems, $thumbResolution)
        {
            return  YouTubeClient::instance()->getLatest($channelId, $maxItems, $thumbResolution);
        });
    }


}