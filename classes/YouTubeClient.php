<?php namespace Bluhex\YouTube\Classes;

use Google_Client;
use Bluhex\YouTube\Models\Settings;
use Carbon\Carbon;
use October\Rain\Exception\ApplicationException;

class YouTubeClient {

    use \October\Rain\Support\Traits\Singleton;

    /**
     * @var Google_Client Google API Client
     */
    public $client;
    public $service;

    protected function init()
    {
        $settings = Settings::instance();

        if (!strlen($settings->api_key))
            throw new ApplicationException('Google API access requires an API Key. Please add your key to Settings / Misc / YouTube');

        // Create the Google Client
        $client = new Google_Client();
        $client->setDeveloperKey($settings->api_key);
        $this->client = $client;
        $this->service = new \Google_Service_YouTube($client);
    }

    /**
     * Grabs the latest videos from a channel
     *
     * @param $channelId string YouTube channel ID
     * @param $maxItems int maximum number of items to display
     * @return array|null array of videos or null if failure
     */
    public function getLatest($channelId, $maxItems)
    {
        try {
            // Build the query and submit it
            $params = array('channelId' => $channelId,
                'order' => 'date',
                'maxResults' => $maxItems);
            $results = $this->service->search->listSearch('id,snippet', $params);

            // Parse the results
            $videos = [];
            foreach ($results['items'] as $item) {
                array_push($videos, array(
                    'id' => $item['id']['videoId'],
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
            return null;
        }
    }

    public function getLatestCacheKey($channelId, $maxItems)
    {
        // Components with the same channel and item count will use the same cached response
        return 'bluhex_ytvideos_' . $channelId . '_' . $maxItems;
    }

}