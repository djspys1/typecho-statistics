<?php

/**
 * 网站访问统计插件；
 * 支持matomo；
 * 支持百度统计；
 * 支持谷歌统计；
 *
 * @package Statistics
 * @author  djspy
 * @version 1.0.0
 * @link    https://github.com/djspys1/statistics
 */
class Statistics_Plugin implements Typecho_Plugin_Interface
{
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = ['Statistics_Plugin', 'render'];
    }

    public static function deactivate()
    {
        // TODO: Implement deactivate() method.
    }

    public static function config(Typecho_Widget_Helper_Form $form)
    {
        $matomo_url = new Typecho_Widget_Helper_Form_Element_Text('matomo_url', null, null, _t('matomo地址,格式:  //matomo.xxx.com/'));
        $form->addInput($matomo_url);
        $baidu_code = new Typecho_Widget_Helper_Form_Element_Text('baidu_code', null, null, _t('百度统计跟踪ID'));
        $form->addInput($baidu_code);
        $google_code = new Typecho_Widget_Helper_Form_Element_Text('google_code', null, null, _t('谷歌统计跟踪ID'));
        $form->addInput($google_code);
    }

    public static function personalConfig(Typecho_Widget_Helper_Form $form)
    {
        // TODO: Implement personalConfig() method.
    }

    public static function render()
    {
        $url = Helper::options()->plugin('Statistics')->matomo_url;
        if(strlen($url) > 0) {
            echo self::getMatomoScript($url);
        }
        $baidu_code = Helper::options()->plugin('Statistics')->baidu_code;
        if(strlen($baidu_code) > 0) {
            echo self::getBaiduScript($baidu_code);
        }
        $google_code = Helper::options()->plugin('Statistics')->google_code;
        if(strlen($google_code) > 0) {
            echo self::getGoogleScript($google_code);
        }
    }

    private function getBaiduScript($code)
    {
        return '<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?' . $code . '";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>';
    }

    private function getMatomoScript($url)
    {
        return '<!-- Matomo -->
<script type="text/javascript">
  var _paq = _paq || [];
  /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
  _paq.push([\'trackPageView\']);
  _paq.push([\'enableLinkTracking\']);
  (function() {
    var u="' . $url . '";
    _paq.push([\'setTrackerUrl\', u+\'piwik.php\']);
    _paq.push([\'setSiteId\', \'1\']);
    var d=document, g=d.createElement(\'script\'), s=d.getElementsByTagName(\'script\')[0];
    g.type=\'text/javascript\'; g.async=true; g.defer=true; g.src=u+\'piwik.js\'; s.parentNode.insertBefore(g,s);
  })();
</script>';
}

private function getGoogleScript($code) {
    return '<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=' . $code . '"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag(\'js\', new Date());

  gtag(\'config\', \'' . $code . '\');
</script>';
}
}

