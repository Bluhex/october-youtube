<?php namespace Bluhex\YouTube\Components;

use Cms\Classes\ComponentBase;
use Cache;
use Google_Client;
use Bluhex\YouTube\Models\Settings;
use Carbon\Carbon;

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
        $this->videos = Cache::remember($this->getCacheKey(), Settings::get('cache_time'), function() {
            return $this->getLatest();
        });
    }

    private function getLatest()
    {
        try {
            // Crreate the Google Client
            $client = new Google_Client();
            $client->setApplicationName("Bluhex Website");
            $client->setDeveloperKey(Settings::get('api_key'));
            $service = new \Google_Service_YouTube($client);

            // Build the query and submit it
            $params = array('channelId' => $this->property('channel_id'),
                'order' => 'date',
                'maxResults' => $this->property('max_items'));
            $results = $service->search->listSearch('id,snippet', $params);

            // Parse the results
            $videos = [];
            foreach ($results['items'] as $item) {
                array_push($videos, array(
                    'link' => 'http://youtube.com/watch?v=' . $item['id']['videoId'],
                    'title' => $item['snippet']['title'],
                    'thumbnail' => 'http://img.youtube.com/vi/' . $item['id']['videoId'] . '/maxresdefault.jpg',
                    'description' => $item['snippet']['description'],
                    'published_at' => Carbon::parse($item['snippet']['publishedAt'])
                ));
            }
            return $videos;
        }
        catch (\Exception $e)
        {
            // Since we're relying on an outside source, lets not crash the page if we can't reach YouTube
            traceLog($e);
            $this->videos = null;
        }
    }

    private function getCacheKey()
    {
        // Components with the same channel and item count will use the same cached response
        return 'bluhex_ytvideos_' . $this->property('channel_id') . '_' . $this->property('max_items');
    }
}