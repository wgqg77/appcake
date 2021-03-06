<?php

//读取渠道参数
$bool = @include_once  SITE_PATH .'config/paramsAdSource.php';
//更新时间小于1天 更新广告渠道配置参数
$updateTime = isset($updateTime) ? $updateTime : strtotime('-2 day');
if(!$bool || $updateTime < strtotime('-1 day')){
    include 'updateAdSource.php';
}
if(isset($source)){
    $adSource = json_decode($source,true);
    $adSource = array_flip($adSource);
}else{
    $adSource = array();
}
ksort($adSource);
$params =  array(
    //导航菜单位置
    'menuPostion' => array(
        0=>'请选择',
        1=>'顶部导航',
        2=>'默认左侧导航',
        3=>'appcake左侧导航',
        4=>'bt左侧导航',
        5=>'壁纸左侧导航',
        6=>'appStore排行',
        7=>'appstore左侧导航',
    ),

    //需要自动写入权限规则的控制器路径
    'autoRulesPath' => array(
        '/modules/appcake/controllers',
    ),
    //无需权限验证方法
    'notCheck'  => array(
        '/admin/index/index',
        //'/appcake/index/index',
        //'/bt/index/index',
        '/admin/index/head',
        '/admin/index/error',
        '/admin/index/welcome',
        '/admin/index/autherror',
        '/admin/is-login/error',
    ),
    //是否强制更新
    'mustUpdate' => array(
        0 => '不强制更新',
        1 => '强制更新'
    ),
    //bt应用文件类型
    'appFileType' => array(
        0 => 'deb',
        1 => 'ipa'
    ),
    //bt首页链接是否显示
    'isShow' => array(
        0 => '显示',
        1 => '不显示'
    ),
    //用户组启用/封禁
    'groupStatus' => array(
        0=>'启用',
        1=>'禁用'
    ),
    //权限规则是否作为菜单显示
    'isShowAsMenu' => array(
        '0' => '显示',
        '1' => '隐藏'
    ),
    //广告渠道
    'ad_source' => $adSource,
    //应用id
    'aid' => array(
        '' => '请选择',
        1 => 'appcake',
        2 => 'appstore',
        3 => 'torrent box',
        4 => 'wallpaper'
    ),
    //是否广告
    'is_ad' => array(
        0 => '非广告',
        1 => '广告'
    ),
    //排序修改方式
    'ad_sort_method' => array(
        1 => '自动排序',
        2 => '对换位置',
        3 => '添加app->自动排序'
        //4 => '添加广告->位置兑换',
    ),
    //修改排序更新方式
    'ad_sort_update_method' => array(
        0 => '下时段生效',
        //1 => '即时生效',
        2 => '自定义生效时间'
    ),
    //广告位置定义 3个列表
    'ad_sort_position' => array(
        'index'     => 0,   //'首页',
        'app'       => 1,   //'应用',
        'game'      => 2,   //'游戏',
        'banner'    => 3,   //'banner'
    ),
    'ad_sort_position_name' => array(
        0 =>'首页',   //'首页',
        1 =>'应用',   //'应用',
        2 =>'游戏',   //'游戏',
        3 =>'banner',   //'banner'
    ),
    'banner_category' => array(
          'Games' => '游戏',
          'other' => '应用',
    ),
    'banner_appsotre' => array(
        0=>'应用app',1=>'跳转AppStore',2=>'广告'
    ),
    'banner_compatible' => array(
        'universal' => '通用',
        'ipad' => 'ipad',
        'iphone' => 'iphone'
    ),
    //自定义分页默认显示页数
    'page_defaut_num' => 500,
    //展示条目默认数 即默认count(*)值
    'page_max_num' => 10000,

    'appstore_popid' =>array(
        0 => '免费',
        1 => '付费',
        2 => '畅销',
    ),
    'appstore_country' =>array(
        'CN'=>'中国',
        'HK'=>'香港',
        'TW'=>'台湾',
        'US'=>'美国',
        'JP'=>'日本',
        'KR'=>'韩国',
    ),
    'appstore_device' =>array(
        'iphone'=>'iphone',
        'ipad'=>'ipad',
    ),
    'appstore_category' =>array(
        36=>'总榜',
        6000=>'商务',
        6001=>'天气',
        6002=>'工具',
        6003=>'旅游',
        6004=>'体育',
        6005=>'社交',
        6006=>'参考',
        6007=>'效率',
        6008=>'摄影与录像',
        6009=>'新闻',
        6010=>'导航',
        6011=>'音乐',
        6012=>'生活',
        6013=>'健康健美',
        6014=>'游戏',
        7001=>'动作游戏',
        7002=>'探险游戏',
        7003=>'街机游戏',
        7004=>'桌面游戏',
        7005=>'扑克牌游戏',
        7006=>'娱乐场游戏',
        7007=>'骰子游戏',
        7008=>'教育游戏',
        7009=>'家庭游戏',
        7011=>'音乐',
        7012=>'智力游戏',
        7013=>'赛车游戏',
        7014=>'角色扮演游戏',
        7015=>'模拟游戏',
        7016=>'体育',
        7017=>'策略游戏',
        7018=>'小游戏',
        7019=>'文字游戏',
        6015=>'财务',
        6016=>'娱乐',
        6017=>'教育',
        6018=>'图书',
        6020=>'医疗',
        6021=>'报刊杂志',
        13001=>'新闻与政治',
        13002=>'流行与时尚',
        13003=>'家居与园艺',
        13004=>'户外与自然',
        13005=>'运动与休闲',
        13006=>'汽车',
        13007=>'艺术与摄影',
        13008=>'新娘与婚礼',
        13009=>'商务与投资',
        13010=>'儿童杂志',
        13011=>'电脑与网络',
        13012=>'烹饪与饮食',
        13013=>'手工艺与爱好',
        13014=>'电子产品与音响',
        13015=>'娱乐',
        13017=>'心理与生理',
        13018=>'历史',
        13019=>'文学杂志与期刊',
        13020=>'男士兴趣',
        13021=>'电影与音乐',
        13023=>'子女教养与家庭',
        13024=>'宠物',
        13025=>'职业与技能',
        13026=>'地方新闻',
        13027=>'科学',
        13028=>'青少年',
        13029=>'旅游与地域',
        13030=>'女士兴趣',
        6022=>'商品指南',
        6023=>'美食佳饮',
        6024=>'购物',
        6025=>'贴纸',
    ),
    'banner_rank' => array(
        9=>'置顶',
        8=>2,
        7=>3,
        6=>4,
        5=>5,
        4=>6,
        3=>7,
        2=>8,
        1=>9
    ),
    //go导出Excel端口
    'go_excel_url' => "http://127.0.0.1:9010/",
    //首页水平滚动栏排序
    'ho_rank' => array(
        14=>1 ,
        13=>2,
        12=>3,
        11=>4,
        10=>5,
        9=>6,
        8=>7,
        7=>8,
        6=>9,
        5=>10,
        4=>11,
        3=>12,
        2=>13,
        1=>14,
        0=>15,
    ),
    //装机必备 售卖位置排序
    'fank' => array(
        100=>1,
        99=>2,
        98=>3,
        97=>4,
        96=>5,
        95=>6,
        94=>7,
        93=>8,
        92=>9,
        91=>10,
        90=>11,
        89=>12,
        88=>13,
        87=>14,
        86=>15,
        85=>16,
        84=>17,
        83=>18,
        82=>19,
        81=>20,
        80=>21,
        79=>22,
        78=>23,
        77=>24,
        76=>25,
        75=>26,
        74=>27,
        73=>28,
        72=>29,
        71=>30,
        70=>31,
        69=>32,
        68=>33,
        67=>34,
        66=>35,
        65=>36,
        64=>37,
        63=>38,
        62=>39,
        61=>40,
        60=>41,
        59=>42,
        58=>43,
        57=>44,
        56=>45,
        55=>46,
        54=>47,
        53=>48,
        52=>49,
        51=>50,
        50=>51,
        49=>52,
        48=>53,
        47=>54,
        46=>55,
        45=>56,
        44=>57,
        43=>58,
        42=>59,
        41=>50,
        40=>61,
        39=>62,
        38=>63,
        37=>64,
        36=>65,
        35=>66,
        34=>67,
        33=>68,
        32=>69,
        31=>70,
        30=>71,
        29=>72,
        28=>73,
        27=>74,
        26=>75,
        25=>76,
        24=>77,
        23=>78,
        22=>79,
        21=>80,
        20=>81,
        19=>82,
        18=>83,
        17=>84,
        16=>85,
        15=>86,
        14=>87,
        13=>88,
        12=>89,
        11=>90,
        10=>91,
        9=>92,
        8=>93,
        7=>94,
        6=>95,
        5=>96,
        4=>97,
        3=>98,
        2=>99,
        1=>100,
        0=>'非售卖(默认)',
    ),
    //country
    'country_code'=>array(
        'AD'=>'AD',
        'AE'=>'AE',
        'AF'=>'AF',
        'AG'=>'AG',
        'AI'=>'AI',
        'AL'=>'AL',
        'AM'=>'AM',
        'AN'=>'AN',
        'AO'=>'AO',
        'AQ'=>'AQ',
        'AR'=>'AR',
        'AT'=>'AT',
        'AU'=>'AU',
        'AW'=>'AW',
        'AZ'=>'AZ',
        'BA'=>'BA',
        'BB'=>'BB',
        'BD'=>'BD',
        'BE'=>'BE',
        'BF'=>'BF',
        'BG'=>'BG',
        'BH'=>'BH',
        'BI'=>'BI',
        'BJ'=>'BJ',
        'BM'=>'BM',
        'BN'=>'BN',
        'BO'=>'BO',
        'BR'=>'BR',
        'BS'=>'BS',
        'BT'=>'BT',
        'BV'=>'BV',
        'BW'=>'BW',
        'BY'=>'BY',
        'BZ'=>'BZ',
        'CA'=>'CA',
        'CC'=>'CC',
        'CD'=>'CD',
        'CF'=>'CF',
        'CG'=>'CG',
        'CH'=>'CH',
        'CI'=>'CI',
        'CK'=>'CK',
        'CL'=>'CL',
        'CM'=>'CM',
        'CN'=>'CN',
        'CO'=>'CO',
        'CR'=>'CR',
        'CU'=>'CU',
        'CV'=>'CV',
        'CY'=>'CY',
        'CZ'=>'CZ',
        'DE'=>'DE',
        'DJ'=>'DJ',
        'DK'=>'DK',
        'DM'=>'DM',
        'DO'=>'DO',
        'DZ'=>'DZ',
        'EC'=>'EC',
        'EE'=>'EE',
        'EG'=>'EG',
        'EH'=>'EH',
        'ER'=>'ER',
        'ES'=>'ES',
        'ET'=>'ET',
        'FI'=>'FI',
        'FJ'=>'FJ',
        'FK'=>'FK',
        'FM'=>'FM',
        'FO'=>'FO',
        'FR'=>'FR',
        'GA'=>'GA',
        'GB'=>'GB',
        'GD'=>'GD',
        'GE'=>'GE',
        'GH'=>'GH',
        'GI'=>'GI',
        'GL'=>'GL',
        'GM'=>'GM',
        'GN'=>'GN',
        'GQ'=>'GQ',
        'GR'=>'GR',
        'GS'=>'GS',
        'GT'=>'GT',
        'GU'=>'GU',
        'GW'=>'GW',
        'GY'=>'GY',
        'HK'=>'HK',
        'HM'=>'HM',
        'HN'=>'HN',
        'HR'=>'HR',
        'HT'=>'HT',
        'HU'=>'HU',
        'ID'=>'ID',
        'IE'=>'IE',
        'IL'=>'IL',
        'IN'=>'IN',
        'IO'=>'IO',
        'IQ'=>'IQ',
        'IR'=>'IR',
        'IS'=>'IS',
        'IT'=>'IT',
        'JM'=>'JM',
        'JO'=>'JO',
        'JP'=>'JP',
        'KE'=>'KE',
        'KG'=>'KG',
        'KH'=>'KH',
        'KI'=>'KI',
        'KM'=>'KM',
        'KN'=>'KN',
        'KP'=>'KP',
        'KR'=>'KR',
        'KW'=>'KW',
        'KY'=>'KY',
        'KZ'=>'KZ',
        'LA'=>'LA',
        'LB'=>'LB',
        'LC'=>'LC',
        'LI'=>'LI',
        'LK'=>'LK',
        'LR'=>'LR',
        'LS'=>'LS',
        'LT'=>'LT',
        'LU'=>'LU',
        'LV'=>'LV',
        'LY'=>'LY',
        'MA'=>'MA',
        'MC'=>'MC',
        'MD'=>'MD',
        'ME'=>'ME',
        'MG'=>'MG',
        'MH'=>'MH',
        'MK'=>'MK',
        'ML'=>'ML',
        'MM'=>'MM',
        'MN'=>'MN',
        'MO'=>'MO',
        'MP'=>'MP',
        'MR'=>'MR',
        'MS'=>'MS',
        'MT'=>'MT',
        'MU'=>'MU',
        'MV'=>'MV',
        'MW'=>'MW',
        'MX'=>'MX',
        'MY'=>'MY',
        'MZ'=>'MZ',
        'NA'=>'NA',
        'NC'=>'NC',
        'NE'=>'NE',
        'NF'=>'NF',
        'NG'=>'NG',
        'NI'=>'NI',
        'NL'=>'NL',
        'NO'=>'NO',
        'NP'=>'NP',
        'NR'=>'NR',
        'NU'=>'NU',
        'NZ'=>'NZ',
        'OM'=>'OM',
        'OT'=>'OT',
        'PA'=>'PA',
        'PE'=>'PE',
        'PG'=>'PG',
        'PH'=>'PH',
        'PK'=>'PK',
        'PL'=>'PL',
        'PM'=>'PM',
        'PN'=>'PN',
        'PR'=>'PR',
        'PS'=>'PS',
        'PT'=>'PT',
        'PW'=>'PW',
        'PY'=>'PY',
        'QA'=>'QA',
        'RO'=>'RO',
        'RS'=>'RS',
        'RU'=>'RU',
        'RW'=>'RW',
        'SA'=>'SA',
        'SB'=>'SB',
        'SC'=>'SC',
        'SD'=>'SD',
        'SE'=>'SE',
        'SG'=>'SG',
        'SH'=>'SH',
        'SI'=>'SI',
        'SJ'=>'SJ',
        'SK'=>'SK',
        'SL'=>'SL',
        'SM'=>'SM',
        'SN'=>'SN',
        'SO'=>'SO',
        'SR'=>'SR',
        'ST'=>'ST',
        'SV'=>'SV',
        'SY'=>'SY',
        'SZ'=>'SZ',
        'TC'=>'TC',
        'TD'=>'TD',
        'TF'=>'TF',
        'TG'=>'TG',
        'TH'=>'TH',
        'TJ'=>'TJ',
        'TK'=>'TK',
        'TM'=>'TM',
        'TN'=>'TN',
        'TO'=>'TO',
        'TP'=>'TP',
        'TR'=>'TR',
        'TT'=>'TT',
        'TV'=>'TV',
        'TW'=>'TW',
        'TZ'=>'TZ',
        'UA'=>'UA',
        'UG'=>'UG',
        'UK'=>'UK',
        'US'=>'US',
        'UY'=>'UY',
        'UZ'=>'UZ',
        'VA'=>'VA',
        'VC'=>'VC',
        'VE'=>'VE',
        'VG'=>'VG',
        'VN'=>'VN',
        'VU'=>'VU',
        'WF'=>'WF',
        'WS'=>'WS',
        'YE'=>'YE',
        'YT'=>'YT',
        'YU'=>'YU',
        'ZA'=>'ZA',
        'ZM'=>'ZM',
        'ZW'=>'ZW',

    ),
    //国家翻译
    'country_list_cn'=>array(
        'AD'=>'安道尔',
        'AE'=>'阿联酋',
        'AF'=>'阿富汗',
        'AG'=>'安提瓜和巴布达',
        'AI'=>'安圭拉岛',
        'AL'=>'阿尔巴尼亚',
        'AM'=>'亚美尼亚',
        'AN'=>'荷属安的列斯群岛',
        'AO'=>'安哥拉',
        'AQ'=>'南极洲',
        'AR'=>'阿根廷',
        'AT'=>'奥地利',
        'AU'=>'澳大利亚',
        'AW'=>'阿鲁巴',
        'AZ'=>'阿塞拜疆',
        'BA'=>'波斯尼亚和黑塞哥维那',
        'BB'=>'孟加拉',
        'BD'=>'孟加拉国',
        'BE'=>'比利时',
        'BF'=>'布基纳法索',
        'BG'=>'保加利亚',
        'BH'=>'巴林',
        'BI'=>'布隆迪',
        'BJ'=>'贝宁',
        'BL'=>'巴勒斯坦',
        'BM'=>'百慕大',
        'BN'=>'文莱',
        'BO'=>'玻利维亚',
        'BR'=>'巴西',
        'BS'=>'巴哈马',
        'BT'=>'不丹',
        'BV'=>'布韦岛',
        'BW'=>'博茨瓦纳',
        'BY'=>'白俄罗斯',
        'BZ'=>'伯利兹',
        'CA'=>'加拿大',
        'CC'=>'科科斯（基林）群岛',
        'CD'=>'刚果（金）',
        'CF'=>'中非',
        'CG'=>'刚果（布）',
        'CH'=>'瑞士',
        'CI'=>'智利',
        'CK'=>'库克群岛',
        'CL'=>'智利',
        'CM'=>'喀麦隆',
        'CN'=>'中国',
        'CO'=>'哥伦比亚',
        'CR'=>'哥斯达黎加',
        'CS'=>'捷克',
        'CU'=>'古巴',
        'CV'=>'佛得角',
        'CY'=>'塞浦路斯',
        'CZ'=>'捷克',
        'DE'=>'德 国',
        'DJ'=>'吉布提',
        'DK'=>'丹麦',
        'DM'=>'多米尼克',
        'DO'=>'多米尼加共和国',
        'DZ'=>'阿尔及利亚',
        'EC'=>'厄瓜多尔',
        'EE'=>'爱沙尼亚',
        'ET'=>'埃塞俄比亚',
        'EG'=>'埃及',
        'EH'=>'阿拉伯撒哈拉民主共和国',
        'ER'=>'厄立特里亚',
        'ES'=>'西班牙',
        'FI'=>'芬兰',
        'FJ'=>'斐济',
        'FK'=>'福克兰群岛',
        'FM'=>'密克罗尼西亚联邦',
        'FO'=>'法罗群岛',
        'FR'=>'法国',
        'GA'=>'加蓬',
        'GB'=>'英国(GB)',
        'GD'=>'格林纳达',
        'GE'=>'格鲁吉亚',
        'GH'=>'加纳',
        'GI'=>'直布罗陀',
        'GL'=>'格陵兰',
        'GM'=>'冈比亚',
        'GN'=>'几内亚',
        'GQ'=>'赤道几内亚',
        'GR'=>'希腊',
        'GS'=>'南乔治亚和南桑威奇群岛',
        'GT'=>'危地马拉',
        'GU'=>'关岛',
        'GW'=>'关岛',
        'GY'=>'圭亚那',
        'HK'=>'香港',
        'HM'=>'赫德岛和麦克唐纳群岛',
        'HN'=>'洪都拉斯',
        'HR'=>'克罗地亚',
        'HT'=>'海地',
        'HU'=>'匈牙利',
        'ID'=>'印度尼西亚',
        'IE'=>'爱尔兰',
        'IL'=>'以色列',
        'IN'=>'印度',
        'IO'=>'英属印度洋领地',
        'IQ'=>'伊拉克',
        'IR'=>'伊朗',
        'IS'=>'冰岛',
        'IT'=>'意大利',
        'JM'=>'牙买加',
        'JO'=>'约旦',
        'JP'=>'日本',
        'KE'=>'肯尼亚',
        'KG'=>'吉尔吉斯坦',
        'KH'=>'柬埔寨',
        'KI'=>'基里巴斯',
        'KM'=>'科摩罗',
        'KN'=>'圣基茨和尼维斯',
        'KP'=>'北朝鲜',
        'KR'=>'韩国',
        'KT'=>'科特迪瓦共和国',
        'KW'=>'科威特',
        'KY'=>'开曼群岛',
        'KZ'=>'哈萨克',
        'LA'=>'老挝',
        'LB'=>'黎巴嫩',
        'LC'=>'圣卢西亚',
        'LI'=>'列支敦士登',
        'LK'=>'斯里兰卡',
        'LR'=>'利比里亚',
        'LS'=>'莱索托',
        'LT'=>'立陶宛',
        'LU'=>'卢森堡',
        'LV'=>'拉脱维亚',
        'LY'=>'利比亚',
        'MA'=>'摩洛哥',
        'MC'=>'摩纳哥',
        'MD'=>'摩尔多瓦',
        'ME'=>'黑山',
        'MG'=>'马达加斯加',
        'MH'=>'马绍尔群岛',
        'MK'=>'马其顿',
        'ML'=>'马里',
        'MM'=>'缅甸',
        'MN'=>'蒙古',
        'MO'=>'澳门地区',
        'MP'=>'北马里亚纳群岛',
        'MR'=>'马提尼克',
        'MS'=>'蒙特塞拉特岛',
        'MT'=>'马耳他',
        'MU'=>'毛里求斯',
        'MV'=>'马拉维',
        'MW'=>'马拉维',
        'MX'=>'墨西哥',
        'MY'=>'马来西亚',
        'MZ'=>'莫桑比克',
        'NA'=>'纳米比亚',
        'NC'=>'新喀里多尼亚',
        'NE'=>'尼日尔',
        'NF'=>'诺福克岛',
        'NG'=>'尼日利亚',
        'NI'=>'尼加拉瓜',
        'NL'=>'荷兰',
        'NO'=>'挪威',
        'NP'=>'尼泊尔',
        'NR'=>'瑙鲁',
        'NU'=>'纽埃',
        'NZ'=>'新西兰',
        'OM'=>'阿曼',
        'OT'=>'卡塔尔',
        'PA'=>'巴拿马',
        'PE'=>'秘鲁',
        'PG'=>'巴布亚新几内亚',
        'PH'=>'菲律宾',
        'PK'=>'巴基斯坦',
        'PL'=>'波兰',
        'PM'=>'圣皮埃尔和密克隆',
        'PN'=>'皮特凯恩群岛',
        'PR'=>'波多黎各',
        'PS'=>'巴勒斯坦',
        'PT'=>'葡萄牙',
        'PW'=>'帕劳',
        'PY'=>'巴拉圭',
        'QA'=>'卡塔尔',
        'RO'=>'罗马尼亚',
        'RS'=>'塞尔维亚',
        'RU'=>'俄罗斯',
        'RW'=>'卢旺达',
        'SA'=>'沙特阿拉伯',
        'SB'=>'所罗门群岛',
        'SC'=>'塞舌尔',
        'SD'=>'苏丹',
        'SE'=>'瑞典',
        'SG'=>'新加坡',
        'SH'=>'圣赫勒拿',
        'SI'=>'斯洛文尼亚',
        'SJ'=>'斯瓦尔巴群岛和扬马延岛',
        'SK'=>'斯洛伐克',
        'SL'=>'塞拉利昂',
        'SM'=>'圣马力诺',
        'SN'=>'塞内加尔',
        'SO'=>'索马里',
        'SR'=>'苏里南',
        'ST'=>'圣多美和普林西比',
        'SV'=>'萨尔瓦多',
        'SY'=>'叙利亚',
        'SZ'=>'斯威士兰',
        'TC'=>'特克斯和凯科斯群岛',
        'TD'=>'乍得',
        'TF'=>'法属南部领地',
        'TG'=>'多哥',
        'TH'=>'泰国',
        'TJ'=>'塔吉克斯坦',
        'TK'=>'托克劳',
        'TM'=>'土库曼',
        'TN'=>'突尼斯',
        'TO'=>'汤加',
        'TP'=>'东帝汶',
        'TR'=>'土耳其',
        'TT'=>'特立尼达和多巴哥',
        'TV'=>'图瓦卢',
        'TW'=>'台湾省',
        'TZ'=>'坦桑尼亚',
        'UA'=>'乌克兰',
        'UG'=>'乌干达',
        'UK'=>'大不列颠及爱尔兰联合王国(UK)',
        'US'=>'美国',
        'UY'=>'乌拉圭',
        'UZ'=>'乌兹别克',
        'VA'=>'梵蒂冈',
        'VC'=>'圣文森特岛',
        'VE'=>'委内瑞拉',
        'VG'=>'英属维尔京群岛',
        'VN'=>'越南',
        'VU'=>'瓦努阿图',
        'WF'=>'瓦利斯和富图纳',
        'WS'=>'萨摩亚',
        'YE'=>'也门',
        'YT'=>'马约特',
        'YU'=>'南斯拉夫联盟',
        'ZA'=>'南非',
        'ZM'=>'赞比亚',
        'ZR'=>'扎伊尔',
        'ZW'=>'津巴布韦',

    ),

);
return $params;