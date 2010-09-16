=== Taobaoke Plugin For Wordpress(淘宝客) ===
Contributors: <a href="http://tao.da-fang.com">Wyatt Fang</a>
Donate link:  http://tao.da-fang.com/
Tags: taobaoke, 淘宝客, 广告, 推广, 阿里妈妈, 淘宝, Tao Bao Ke, Tao bao ke
Requires at least: 2.8.2
Tested up to: 3.0.1
Stable tag: 2.3.2

淘宝网官方推荐的淘宝客wordpress的插件，可以通过wordpress的后台添加淘宝客的商品到您的blog来赚钱。

== Description ==

<h1><a href="http://club.alimama.com/read-htm-tid-685825.html">祝贺该插件成为淘宝网官方推荐的插件。</a></h1>
<h1><a href="http://tao.da-fang.com/"更多详细的介绍，请参见作者插件的页面。</a></h1>
淘宝客的wordpress的插件，可以通过wordpress的后台添加淘宝客的商品到您的blog来赚钱，目前版本中可以在wordpress后台方便的浏览淘宝客的商品和分类列表，然后选择热门的商品推广到站点的sidebar或者博客页面中。更多功能还在陆续加入中。

安装详见安装步骤。


目前支持的功能有：
1. 可以在后台自定义你的淘宝客的pid，通过该pid，你可以方便的通过你的博客来赚钱；
2. 自定义淘宝开放平台的应用的验证码；
3. 可以在wordpress后台方便的浏览淘宝客商品的分类，并可方便查看该分类下的商品；
4. 可以选择自己喜欢的商品放入推广列表，编写博文的时候，可以通过该plugin定制在文本编辑框上的icon选择商品插入到文章中(支持插入图片广告，图片+文字广告)；
5. 通过该插件编写的widget工具，可以在“小工具”页面添加侧边栏淘宝客广告，轻轻一拖，你选择的淘宝客商品推广便出现在侧边栏了，而且，更为方便的，你可以定制化你的淘宝客侧边栏商品推广样式，让你不用到阿里妈妈淘宝客主页，也能拥有相同的便捷推广享受。
6. 可以选择商品的同时选择店铺推广。
7. 增加了多用户支持，每个用户都可以设置自己的淘宝客信息。

== Installation ==

1. 上传 `taobaoke` 文件夹到你的wordpress插件目录下： `/wp-content/plugins/`
2. 在插件管理页面激活该插件；
3. 到“淘宝客”设置界面，设置你的淘宝客pid，这是你获得淘宝客佣金的依据.

注意：如果你的主题不支持widget，你需要找到你的主题的sidebar.php，把下面的代码增加到你的侧边栏：
<?php taobaoke_widget_sidebar(); ?>

如果你希望改变默认的设置，可以更改以下的函数：
function taobaoke_widget_sidebar()  {
    $widget_title = var_get('widget_title');
    if (empty($widget_title)) {
        $widget_title = '淘宝客 - 侧边栏推荐';
    }

    $before_widget = ''; //自己根据自己主题的格式更改样式，这里是侧边栏块元素的风格
    $before_title = '<h1>';//自己根据自己主题的格式更改样式，这里是 侧边栏 标题风格
    $after_title = '</h1>';//自己根据自己主题的格式更改样式
    $after_widget = '';//自己根据自己主题的格式更改样式

    $vars['before_widget'] = $before_widget;
    $vars['before_title'] = $before_title;
    $vars['after_title'] = $after_title;
    $vars['after_widget'] = $after_widget;

    taobaoke_widget_sidebar_promote($vars);
}


== Screenshots ==

1. 在 淘宝客 设置界面，配置你的淘宝客PID信息；
2. 当你需要推广淘宝客商品的时候，可以先选在分类，然后选择该分类下的商品，商品可以放入推广列表(写文章时可以及时插入文章中，见screenshot-4)；
3. 浏览商品时也可以将商品做推广，推广的商品会出现在侧边栏(可以通过“小工具”widget把淘宝客的widget拖到侧边栏)，并配置淘宝客侧边栏推广的样式，也可以保持默认，默认值和阿里妈妈淘宝客推广的默认值一致；
4. 写文章时，可以点击 酷酷 的淘宝客图标，插入你加入推广列表的商品到文章中；
5. 可以选择店铺做推广
6. 店铺推广操作界面
7. 写文章时插入关键字链接
8. 写文章时插入关键字链接

== Changelog ==

= 1.0 =
* 第一个发布版本，更多好用的功能还在开发中。
= 1.2 =
* 做了对plugin folder 名字hard code的hot fix
= 1.4 =
* 增加了新的功能，可以删除自己收藏的淘宝客商品，可以删除推广的商品列表；增加了对侧边栏广告的随机播放；

= 1.5 =
* 增加了搜索淘宝客商品的功能

= 1.6 =
* 增加了推广店铺的功能；

= 1.7 =
* 增加了搜索推广的功能；

= 1.7.1 =
* 修复了几个使用者提的bug。

= 1.8 =
* 修复了IE下面把“小工具”页 安装淘宝客插件 后无法显示的问题；
* 鉴于大家申请APP KEY比较困难的问题，内置了申请好的APP KEY，方便大家使用；

= 1.9 =
* 增加了多用户的支持，使每个用户都可以设置自己的pid和淘宝客nickname；

= 2.1 =
* 增加了多用户权限的设置

= 2.2.2 =
* 升级API版本从1.0到2.0

= 2.3.1 =
* 增加了更多的广告位，更多智能化的设置

= 2.3.2 =
* 修复pid初始化时出问题的bug；修复关键词链接不正确的bug；

== Arbitrary section ==

N/A

== A brief Markdown Example ==

N/A