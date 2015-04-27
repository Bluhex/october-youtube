<?php namespace Bluhex\YouTube;

use System\Classes\PluginBase;

/**
 * YouTube Videos plugin
 *
 * @author Brendon Park
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Youtube Videos',
            'description' => 'Provides a component to display YouTube videos',
            'author'      => 'Bluhex Studios',
            'icon'        => 'icon-youtube-play',
            'homepage'    => 'https://github.com/bluhex/october-youtube'
        ];
    }

    /**
     * Register the settings listing
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'config' => [
                'label'       => 'YouTube',
                'icon'        => 'icon-youtube-play',
                'description' => 'Configure YouTube API Key and Channel settings',
                'class'       => 'Bluhex\YouTube\Models\Settings',
                'order'       => 600
            ]
        ];
    }

    /**
     * Register the component/s
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            '\Bluhex\YouTube\Components\LatestVideos' => 'latestVideos'
        ];
    }

}
