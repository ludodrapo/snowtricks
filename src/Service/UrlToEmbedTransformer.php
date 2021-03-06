<?php

declare(strict_types=1);

namespace App\Service;

use Exception;

/**
 * class UrlToEmbedTransformer
 * @package App\Service
 */
class UrlToEmbedTransformer
{
    /**
     * Allows you to extract the ID of any video url
     * (youtube, dailymotion or vimeo)
     * in order to add it in an html embed video tag
     * 
     * @param string $url
     * @return string
     */
    public function urlToEmbed($url): string
    {
        $host = parse_url($url)["host"];

        if ($host === 'youtu.be' || strpos($host, 'youtube') == true) {

            $functionnal_link = 'https://www.youtube.com/embed/' . $this->getYouTubeVideoId($url);
        } else if (is_int(strpos($host, 'vimeo', 0))) {

            $functionnal_link = "https://player.vimeo.com/video/" . $this->getVimeoVideoId($url);
        } else if ($host === 'dai.ly' || strpos($host, 'daily') == true) {

            $functionnal_link = 'https://www.dailymotion.com/embed/video/' . $this->getDailyMotionVideoId($url, $host);
        } else {
            throw new Exception("L'url de cette vidéo n'est pas prise en charge par notre système.");
        }
        return $functionnal_link;
    }

    /**
     * To extract video id from youtube urls
     *
     * @param string $url
     * @return string
     */
    public function getYouTubeVideoId($url): string
    {

        if (!empty(parse_url($url)['fragment'])) {
            $partial_url = parse_url($url)['fragment'];
        } elseif (!empty(parse_url($url)['query'])) {
            $partial_url = parse_url($url)['query'];
        } else {
            $partial_url = parse_url($url)['path'];
        }

        $partial_url = explode('/', $partial_url);
        $partial_url = explode('=', $partial_url[count($partial_url) - 1]);
        return $partial_url[count($partial_url) - 1];
    }

    /**
     * To extract video id from vimeo urls
     *
     * @param string $url
     * @return string
     */
    public function getVimeoVideoId($url): string
    {
        $partial_url = parse_url($url)['path'];
        $partial_url = explode('/', $partial_url);
        return $partial_url[count($partial_url) - 1];
    }

    /**
     * To extract video id from dailymotion urls
     *
     * @param string $url
     * @param string $host
     * @return string
     */
    public function getDailyMotionVideoId($url, $host): string
    {
        $partial_url = parse_url($url)['path'];
        $partial_url = explode('/', $partial_url);

        if (strpos($host, 'daily') == true) {
            if (count($partial_url) == 4) {
                $videoId = $partial_url[3];
            } else {
                $videoId = explode('_', $partial_url[2])[0];
            }
        }
        if ($host === 'dai.ly') {
            $videoId = $partial_url[1];
        }

        return $videoId;
    }
}
