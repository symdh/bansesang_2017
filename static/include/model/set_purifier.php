<?php defined('BASEPATH') OR exit('No direct script access allowed');
// 웹사이트에서 다운받아 적당한 곳에 압축 푸세요.
require_once('./static/lib/htmlpurifier/library/HTMLPurifier.auto.php');

// 기본 설정을 불러온 후 적당히 커스터마이징을 해줍니다.
$config_Purifier = HTMLPurifier_Config::createDefault();
$config_Purifier->set('Attr.EnableID', false);
$config_Purifier->set('Attr.DefaultImageAlt', '');

// 인터넷 주소를 자동으로 링크로 바꿔주는 기능
$config_Purifier->set('AutoFormat.Linkify', true);

// 이미지 크기 제한 해제 (한국에서 많이 쓰는 웹툰이나 짤방과 호환성 유지를 위해)
$config_Purifier->set('HTML.MaxImgLength', null);
$config_Purifier->set('CSS.MaxImgLength', null);

// 다른 인코딩 지원 여부는 확인하지 않았습니다. EUC-KR인 경우 iconv로 UTF-8 변환후 사용하시는 게 좋습니다.
$config_Purifier->set('Core.Encoding', 'UTF-8');

// 필요에 따라 DOCTYPE 바꿔쓰세요.
//$config_Purifier->set('HTML.Doctype', 'XHTML 1.0 Transitional');

// 플래시 삽입 허용
$config_Purifier->set('HTML.FlashAllowFullScreen', true);
$config_Purifier->set('HTML.SafeEmbed', true);
$config_Purifier->set('HTML.SafeIframe', true);
$config_Purifier->set('HTML.SafeObject', true);
$config_Purifier->set('Output.FlashCompat', true);

// 최근 많이 사용하는 iframe 동영상 삽입 허용
$config_Purifier->set('URI.SafeIframeRegexp', '#^(?:https?:)?//(?:'.implode('|', array(
    'www\\.youtube(?:-nocookie)?\\.com/',
    'maps\\.google\\.com/',
    'player\\.vimeo\\.com/video/',
    'www\\.microsoft\\.com/showcase/video\\.aspx',
    '(?:serviceapi\\.nmv|player\\.music)\\.naver\\.com/',
    '(?:api\\.v|flvs|tvpot|videofarm)\\.daum\\.net/',
    'v\\.nate\\.com/',
    'play\\.mgoon\\.com/',
    'channel\\.pandora\\.tv/',
    'www\\.tagstory\\.com/',
    'play\\.pullbbang\\.com/',
    'tv\\.seoul\\.go\\.kr/',
    'ucc\\.tlatlago\\.com/',
    'vodmall\\.imbc\\.com/',
    'www\\.musicshake\\.com/',
    'www\\.afreeca\\.com/player/Player\\.swf',
    'static\\.plaync\\.co\\.kr/',
    'video\\.interest\\.me/',
    'player\\.mnet\\.com/',
    'sbsplayer\\.sbs\\.co\\.kr/',
    'img\\.lifestyler\\.co\\.kr/',
    'c\\.brightcove\\.com/',
    'www\\.slideshare\\.net/',
)).')#');

// 설정을 저장하고 필터링 라이브러리 초기화
$purifier = new HTMLPurifier($config_Purifier);

// HTML 필터링 실행
//$html = $purifier->purify($html);




