<?php

namespace App\Service;

class VideoLinkValidator
{
    const YOUTUBEURL = ['youtube.com', 'youtu.be'];
    const EMBEDYOUTUBEURLBASE = 'https://www.youtube.com/embed/';
    const EMBEDDAILYMOTIONBASE = 'https://www.dailymotion.com/embed/video/';
    const DAILYMOTIONURL = ['dailymotion.com', 'dai.ly'];
    const AVAILABLEURL = ['youtube' => self::YOUTUBEURL, 'dailymotion' => self::DAILYMOTIONURL];

    public function checkUrl($listUrl)
    {
        $finalList = [];
        foreach ($listUrl as $key => $value) {
            $result = $this->checkValidUrl($value);
            if (false !== $result) {
                array_push($finalList, $result);
            }
        }

        return $finalList;
    }

    private function checkEmbed($url)
    {
        $embed = false;
        if (true === strpos($url, 'embed')) {
            $embed = true;
        }

        return $embed;
    }

    private function convertYoutubeUrl($url)
    {
        $query = parse_url($url, PHP_URL_QUERY);
        $explodeQuery = explode('&', $query);
        $videoLink = false;
        if ('' !== $explodeQuery[0]) {
            $video = substr($explodeQuery[0], 2);
            $videoLink = self::EMBEDYOUTUBEURLBASE.$video;
        } else {
            $linkExplode = explode('/', $url);
            $video = $linkExplode[count($linkExplode) - 1];
            $videoLink = self::EMBEDYOUTUBEURLBASE.$video;
            /*invalid url*/
            if (1 == count($linkExplode)) {
                $videoLink = false;
                $url = false;
            }
        }

        if (false === $videoLink) {
            return $url;
        } else {
            return $videoLink;
        }
    }

    private function convertDailymotionUrl($url)
    {
        $linkExplode = explode('/', $url);
        $video = $linkExplode[count($linkExplode) - 1];

        //if contain query argument (playlist)
        if (null !== parse_url($url, PHP_URL_QUERY)) {
            $video = explode('?', $video)[0];
        }
        $videoLink = self::EMBEDDAILYMOTIONBASE.$video;
        /*invalid url*/
        if (1 == count($linkExplode)) {
            $videoLink = false;
        }
        if (false === $videoLink) {
            return false;
        }
        return $videoLink;
    }

    private function checkValidUrl($url)
    {
        $result = false;
        $i = 0;
        foreach (self::AVAILABLEURL as $key => $value) {
            $e = 0;
            while (count(self::AVAILABLEURL[$key]) > $e) {
                if (strpos($url, self::AVAILABLEURL[$key][$e])) {
                    $result = $url;
                    $embed = $this->checkEmbed($url);
                    if ('youtube' == $key) {
                        if (false === $embed) {
                            $result = $this->convertYoutubeUrl($url);
                        }
                    } elseif ('dailymotion' == $key) {
                        if (false === $embed) {
                            $result = $this->convertDailymotionUrl($url);
                        }
                    }
                    break;
                }
                ++$e;
            }
        }

        return $result;
    }
}
