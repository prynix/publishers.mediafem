<?php

function getImonomyTag($siteName, $imonomyTag) {
    return "<!-- BEGIN Adtomatik In Image TAG - "
    . $siteName
    . " - DO NOT MODIFY -->  <script type='text/javascript' src='//tag.imonomy.com/script/"
    . $imonomyTag
    . "/preload.js' ></script>"
    ."<!-- END Adtomatik TAG -->";
}

function getTags($adserver, array $replace, $formatName) {
    
    $tags = array(
        'YaxApi' => ':placementName - DO NOT MODIFY --><SCRIPT TYPE="text/javascript" SRC="http://ads.yahoo.com/st?ad_type=ad&ad_size=:size&section=:adserverKey&CACHEBUSTER&pub_url=${PUB_URL}&pub_redirect_unencoded=1&pub_redirect=INSERT_CLICK_MACRO"></SCRIPT>',
        'AppnexusApi' => ':placementName - DO NOT MODIFY -->  <script SRC="http://ads.mediafem.com/ttj?id=:adserverKey&gender=f&cb=[CACHEBUSTER]&pubclick=[INSERT_CLICK_TAG]&referrer=[REFERRER_URL]" TYPE="text/javascript"></script>',
        'DfpApi' => ":placementName - DO NOT MODIFY --><script src='//www.googletagservices.com/tag/js/gpt.js'> googletag.pubads().definePassback('/:adNetwork/:aditionalKey', [:width, :height]).display();</script>",
        'Adk2Api' => ":placementName - DO NOT MODIFY --><!--Copy and paste the code below into the location on your website where the ad will appear.--><script type='text/javascript'>var adParams = {p: ':aditionalKey', size: ':size', serverdomain: 'adtomatik'   };</script><script type='text/javascript' src='http://cdn.adtomatik.com/adtomatik/tags/xbanner/xbanner.js?ap=1300'></script>"
    );

    $tagsRichMedia = array(
        'YaxApi' => ':placementName - DO NOT MODIFY --><SCRIPT TYPE="text/javascript" SRC="http://ads.yahoo.com/st?ad_type=ad&ad_size=:size&section=:adserverKey&CACHEBUSTER&pub_url=${PUB_URL}&pub_redirect_unencoded=1&pub_redirect=INSERT_CLICK_MACRO"></SCRIPT>',
        'AppnexusApi' => ':placementName - DO NOT MODIFY --><script SRC="http://ads.mediafem.com/ttj?id=:adserverKey&gender=f&cb=[CACHEBUSTER]&pubclick=[INSERT_CLICK_TAG]&referrer=[REFERRER_URL]" TYPE="text/javascript"></script>',
        //'DfpApi' => ":placementName - DO NOT MODIFY --><script src='http://www.googletagservices.com/tag/js/gpt.js'></script><script type='text/javascript'>googletag.defineOutOfPageSlot('/:adNetwork/:aditionalKey', 'div-gpt-ad-%%CACHEBUSTER%%-0').setClickUrl('%%CLICK_URL_UNESC%%').addService(googletag.pubads()); googletag.pubads().enableSyncRendering(); googletag.enableServices();</script><div id='div-gpt-ad-%%CACHEBUSTER%%-0'><script type='text/javascript'>googletag.cmd.push(function() { googletag.display('div-gpt-ad-%%CACHEBUSTER%%-0'); });</script></div>",
        'DfpApi' => ":placementName - DO NOT MODIFY --><script src='//www.googletagservices.com/tag/js/gpt.js'> googletag.pubads().definePassback('/:adNetwork/:aditionalKey', [1, 1]).display();</script>",
        'Adk2Api' => " --> ---"
    );

    
    
    $tagsVideo = array(
        'YaxApi' => '---',
        'AppnexusApi' => '---',
        'DfpApi' => "http://pubads.g.doubleclick.net/gampad/ads?sz=:size&iu=/:adNetwork/:aditionalKey&ciu_szs&impl=s&gdfp_req=1&env=vp&output=xml_vast2&unviewed_position_start=1&url=:siteName&description_url=:siteName&correlator=[timestamp]",
        'Adk2Api' => "---"
    );
    
    if ($formatName == 'richmedia') {
        $line = "<!-- BEGIN " . Session::get('platform.brand') . " TAG - ";
        $line = $line . $tagsRichMedia[$adserver];
    } elseif ($formatName == 'video') {
        $line = $tagsVideo[$adserver];
    }elseif ($formatName == 'videoplayer'){
        $placement = Placement::getByKey($replace['adserverKey']);
        $line = $placement->bridVideo->getEmbedCode();
        //echo $line;
    } else {
        $line = "<!-- BEGIN " . Session::get('platform.brand') . " TAG - ";
        $line = $line . $tags[$adserver];
    }
    foreach ($replace as $key => $value) {
        $line = str_replace(':' . $key, str_replace('%s', ' ', $value), $line);
    }
    //Codigos de Procter y los de Dataexpand
    if ($formatName == 'video' || $formatName == 'videoplayer')
        return $line;
    //$line = $line . '<script src="http://js.revsci.net/gateway/gw.js?csid=F09828&auto=t&bpid=mediafem"></script>' . '<!-- END ' . Session::get('platform.brand') . ' TAG -->';
    return $line;
}
