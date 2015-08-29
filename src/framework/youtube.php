<?php
namespace framework;
use framework\string;
use framework\html;

class youtube{
    public function subscribeButton($channelId){
        $html = new html();
        $attr = '{"class":"g-ytsubscribe", "data-channelid":"'.$channelId.'", "data-layout":"full", "data-count":"default"}';
        $html->b('div', 0, 1, '', $attr);
        $html->b('div', 1, 1);
    }

    public function getPart($id, $part){
        $params = array(
            'part' => $part,
            'id' => $id,
            'key' => YOUTUBE_API_KEY,
        );

        $api_url = YOUTUBE_API_BASE . '?' . http_build_query($params);
        $result = json_decode(@file_get_contents($api_url), true);

        return $result;
    }

    public function player($id){
        $string = new string();

        $player = $this->getPart($id, 'player');

        $this->videoEmbedHtml = $player['items'][0]['player']['embedHtml'];

        $explode = $string->explode($this->videoEmbedHtml, '<');
        foreach ($explode as $key => $value){
            $iframe = $string->explode($value, '=');
            foreach ($iframe as $iframeKey => $iframeValue){
                if($iframeKey == 1){
                    $this->videoWidth = $string->between('"', '"', $iframe[$iframeKey]);
                }
                if($iframeKey == 2){
                    $this->videoHeight = $string->between('"', '"', $iframe[$iframeKey]);
                }
            }
        }

    }

    public function contentDetails($id){
        $contentDetails = $this->getPart($id, 'contentDetails');
        $this->videoDuration = $contentDetails['items'][0]['contentDetails']['duration'];
        $this->videoDimension = $contentDetails['items'][0]['contentDetails']['dimension'];
        $this->videoDefinition = $contentDetails['items'][0]['contentDetails']['definition'];
        $this->videoCaption = $contentDetails['items'][0]['contentDetails']['caption'];
        $this->videoLicensedContent = $contentDetails['items'][0]['contentDetails']['licensedContent'];
    }

    public function snippet($id){
        $snippet = $this->getPart($id, 'snippet');

        $this->channelTitle = $snippet['items'][0]['snippet']['channelTitle'];
        $this->channelId = $snippet['items'][0]['snippet']['channelId'];
        
        $this->videoId = $id;
        $this->videoTitle = $snippet['items'][0]['snippet']['title'];
        $this->videoPublishedAt = $snippet['items'][0]['snippet']['publishedAt'];
        $this->videoCategoryId = $snippet['items'][0]['snippet']['categoryId'];
        $this->videoDescription = $snippet['items'][0]['snippet']['description'];
        $this->videoTags = $snippet['items'][0]['snippet']['tags'];
        $this->videoThumbnailAlt = preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->videoTitle);

        $this->videoThumbnailDefaultUrl = $snippet['items'][0]['snippet']['thumbnails']['default']['url'];
        $this->videoThumbnailDefaultWidth = $snippet['items'][0]['snippet']['thumbnails']['default']['width'];
        $this->videoThumbnailDefaultHeight = $snippet['items'][0]['snippet']['thumbnails']['default']['height'];

        $this->videoThumbnailMediumUrl = $snippet['items'][0]['snippet']['thumbnails']['medium']['url'];
        $this->videoThumbnailMediumWidth = $snippet['items'][0]['snippet']['thumbnails']['medium']['width'];
        $this->videoThumbnailMediumHeight = $snippet['items'][0]['snippet']['thumbnails']['medium']['height'];

        $this->videoThumbnailHighUrl = $snippet['items'][0]['snippet']['thumbnails']['high']['url'];
        $this->videoThumbnailHighWidth = $snippet['items'][0]['snippet']['thumbnails']['high']['width'];
        $this->videoThumbnailHighHeight = $snippet['items'][0]['snippet']['thumbnails']['high']['height'];
    }
}
?>