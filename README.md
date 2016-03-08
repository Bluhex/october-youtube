# YouTube Plugin

A simple plugin interface with YouTube for October CMS.

## Setup

1. Clone or download the repo into ```plugins/bluhex/youtube```.
2. Run ``composer update`` to get the latest google client.

## Usage


### Settings

- ```API Key```: YouTube API key is needed to access the API.
- ```Minutes cached```: Time in minutes that the results should be cached for
##### Getting an API Key

1. Head on over to https://console.developers.google.com/project and either select your existing project or create a new one.
2. Open the side bar and select 'API Manager'
3. Select or search for YouTube Data API
4. Click on 'Enable'
5. Select 'Credentials' from the sidebar
6. Select 'Create credentials' -> 'API key' -> 'Server key'
7. Name the key whatever you like and enter your IP address into the 'Accept requests from these server IP addresses' and create
8. Copy the new API Key into the settings and save.

### Latest videos

Use the ```Latest Videos``` component to display a list of the latest videos for a channel. The component has the following properties available for config:

- ```channel_id```: The YouTube channel id can be found over at https://www.youtube.com/account_advanced.

- ```max_items```: Maximum number of videos to display, at this time there is no pagination support.

- ```thumb_resolution```: The resolution of the thumbnail. YouTube isn't great about their thumbnails so there are 4 options:
 - Default: Thumbnail is resized into 120 x 90 with black bars added if needed to fill the aspect ratio.
 - Medium (default option): Thumbnail is resized into 320 x 180 with black bars added if needed to fill the aspect ratio.
 - High: Thumbnail is resized into 480 x 360 with black bars added if needed to fill the aspect ratio.
 - Full Resolution is the absolute highest resolution provided by YouTube. This method isn't part of the API, but is the closest to the original thumbnail uploaded by the user.


### Creating a custom partial

Under the ```CMS > Partials``` menu, create a new partial called ``` latestVideos/default.htm ```. 

Inside the videos array each video object contains the following:

- ``link``: URL of the video
- ``title``: Video title
- ``thumbnail``: URL of the maximum resolution version of the thumbnail
- ``description``: Excerpt of the video description
- ``published_at``: Carbon date of the publish date

#### Simple partial 

``` twig
{% set videos = __SELF__.videos %}
{% for video in videos %}
  <a href="{{ video.link }}">
    <img src="{{ video.thumbnail }}" alt="{{ video.title }}">
    <span>{{ video.title }}</span>
  </a>
{% endfor %}
```
