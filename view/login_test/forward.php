<?php
// vim: set expandtab tabstop=4 shiftwidth=4 fdm=marker:
// +----------------------------------------------------------------------+
// | The Club Portal v1.0                                                 |
// +----------------------------------------------------------------------+ 
// | Copyright (c) 2011, Tencent Inc. All rights reserved.                |
// +----------------------------------------------------------------------+
// | Authors: The Club Dev Team, ISRD, Tencent Inc.                       |
// |          tenfyguo <tenfyguo@tencent.com>                             |                  
// +----------------------------------------------------------------------+


//定义编码
define("EC_GBK",    100);
define("EC_GB2312", 200);
define("EC_UTF8",   300);


//商户的配置数组
$MALL_CONNECT_CONFIG = array (
    //这里以elong这个商户为demo例子进行说明
    10069 => array(      
        'showmsgVersion' => 1,
		'cnName'         => '艺龙旅行网',   //商家中文名
        'enId'           => 'elong',        //商家英文Id
        'domain'         => 'elong',        //商家站点域名列表，中间通过'|'分割
        'index'          => 'http://www.tieyou.com/',       //商家首页地址
        //'loginURL'       => 'http://www.elong.com/redirect/qqlogin.aspx',//商家的联合登录跟单中转cgi URL  
		'loginURL'       => 'http://caibei.tieyou.com/QQCaiBei/login.html',//商家的联合登录跟单中转cgi URL  

        'key1'           => 'w8U7o0Q@6Y7eppgif6qTx6Y5km!!u6YY',  //TODO:这里先填写测试的key,后续上线前需要QQ彩贝给到正式的key替换这里
        'key2'           => 'QaI6sk6uIOay2Tgq4w!*n1w2n9ee%kWn',  //TODO:这里先填写测试的key,后续上线前需要QQ彩贝给到正式的key替换这里  
        'charEncoding'   => EC_GBK            //商家的站点编码
        
    )
    

);


//测试的QQ号码数组，这里配置五个测试号码，用于测试，这里的昵称，需要跳转后到商户的站点能够正常的显示
$UIN_CONNECT_CONFIG = array(
	1002000201 => array(
		'Acct'     => '0000000000000000000000000000A4D9',
		'OpenId'   => '0000000000000000000000000000A4D9',
		'NickName' => "ぐ.'HHH \   m",
		'ClubInfo' => 1,
		'CBPoints' => 1000
	),

    1002000202 => array(
		'Acct'     => '0000000000000000000000000000B47A',
		'OpenId'   => '0000000000000000000000000000B47A',
		'NickName' => "<script>alert()</script>",
		'ClubInfo' => 0,
		'CBPoints' => 100
	),

    1002000203 => array(
		'Acct'     => '00000000000000000000000000009D0B',
		'OpenId'   => '00000000000000000000000000009D0B',
		'NickName' => "①紴缡頩啲嗳鲭┕'Idiot…",
		'ClubInfo' => 1,
		'CBPoints' => 100
	),
 
    1002000204 => array(
		'Acct'     => '000000000000000000000000000081B4',
		'OpenId'   => '000000000000000000000000000081B4',
		'NickName' => "，！@#￥%&*哈哈?><u>o</u>",
		'ClubInfo' => 1,
		'CBPoints' => 190
	)
);

/////////////////////////////////////////////////函数定义区开始///////////////////////////////////////////////////////
//设置不cache
function cbSetNoCache() {
    // HTTP/1.1
    header("Cache-Control: no-store, no-cache, must-revalidate");
    header("Cache-Control: post-check=0, pre-check=0", false);
    // HTTP/1.0
    header("Pragma: no-cache");
}

//昵称最多输出12个字符
function nick_filter( $nick, $encodeHTML=true ) {
    if(strlen( trim($nick) ) > 20) {
        $nick = substr( $nick, 0, 20); 
    }

    $nick = str_replace( "\n" , '' , $nick );
    $nick = str_replace( "\r" , '' , $nick );
    
    return $encodeHTML? str_replace(' ','&nbsp;',htmlspecialchars($nick, ENT_QUOTES)) : $nick; 
}


//验证目标url是否合法
function make_safe_url($mall_id, $to_url='') 
{
    global $MALL_CONNECT_CONFIG;
    $domain = @$MALL_CONNECT_CONFIG[$mall_id]['domain'];
    if( $domain == '' ) {
        return '';
    }

    if($to_url == '' || strlen($to_url) > 500) 
    {
        return ''; //若超过一定长度的url，则默认为空
    }
      
    $url_arr  = parse_url($to_url);
    $url_host = @$url_arr['host'];

    //跳转域名匹配
    if(preg_match ('/^(\w+\.)?'.$domain.'\./i', $url_host)) 
    {
        return $to_url;
    }
    else
    {
        return '';
    }
}

//GBK转换成UTF-8编码
function gbk2utf8($str) {
    return @iconv( "GBK", "UTF-8//IGNORE", $str ) ;
}


function getViewinfo($uin , $mId , $clubinfo)
{
    global $MALL_CONNECT_CONFIG, $UIN_CONNECT_CONFIG;
    //编码
    $encode = @$MALL_CONNECT_CONFIG[$mId]['charEncoding'];

    //昵称
    $nickname = nick_filter( $UIN_CONNECT_CONFIG[$uin]['NickName']);
    if( $encode == EC_UTF8 ) {
        $nickname = gbk2utf8($nickname);
    }

    $point = $UIN_CONNECT_CONFIG[$uin]['CBPoints'];

    $bonus = '5%';

    $viewinfoArr = array(
        'ShowMsg'  => getShowmsg($nickname , $clubinfo , $point , $bonus , $mId) ,
        'NickName' => $nickname ,
        'CBPoints'    => $point ,
        'CBBonus'    => $bonus 
    );

    return http_build_query($viewinfoArr);
}


function getShowmsg($nickname , $clubinfo , $point , $bonus , $mId)
{
    global $MALL_CONNECT_CONFIG;
    $showmsgVersion = @$MALL_CONNECT_CONFIG[$mId]['showmsgVersion'];
    $encode = @$MALL_CONNECT_CONFIG[$mId]['charEncoding'];

    $club = ($clubinfo==1 ? 'QQ会员' : 'QQ用户') ;
    
    switch($showmsgVersion) {
        case 1 :
            $msg = $club . '，';

            if( $encode == EC_UTF8 ) {
                return  gbk2utf8($msg) . $nickname;
            } else {
                return  $msg . $nickname;
            }

            break;       

        case 2:
            $msg = '';
            if($clubinfo == 1) {
                 $msg = "！QQ用户团购得彩贝积分，您是<font color=\"red\">QQ会员</font>更独享团购成功获订单金额<font color=\"red\">10%</font>的双倍积分返还！<a href=\"http://cb.qq.com/my/my_jifen.html\" target=\"_blank\">查看我的彩贝积分</a>";
            } else {
                 $msg = "！QQ用户团购得彩贝积分，团购成功可获订单金额<font color=\"red\">5%</font>的彩贝积分返还！<a href=\"http://cb.qq.com/my/my_jifen.html\" target=\"_blank\">查看我的彩贝积分</a>&nbsp;&nbsp;&nbsp;<a href=\"http://pay.qq.com/qqvip/index.shtml?aid=vip.caibei.gaopeng.index.navbar.kaitong\" target=\"_blank\" title=\"QQ会员用户团购成功，可获得订单金额10%的双倍彩贝积分返还！\"><font color=\"red\">开通QQ会员</font></a>";
            }

            if( $encode == EC_UTF8 ) {
                 return  gbk2utf8('您好，') .  $nickname . gbk2utf8($msg);
            } else {
                 return  '您好，' . $nickname . $msg;
            }
           
			break;

        default :
            return $nickname;
            break;
    }
}


function getVKey($mId , $param)
{
    global $MALL_CONNECT_CONFIG;
    $key1 = $MALL_CONNECT_CONFIG[$mId]['key1'];
    $key2 = $MALL_CONNECT_CONFIG[$mId]['key2'];

    ksort($param);
    $vkey  = implode('', $param);
    $md5_1 = strtolower(md5($vkey.$key1));
    $vkey  = strtolower(md5($md5_1.$key2));

    return $vkey;
}


function getOutputHtml($mId , $param , $vkey)
{
    global $MALL_CONNECT_CONFIG;
    $handleUrl = @$MALL_CONNECT_CONFIG[$mId]['loginURL'];

    $loginFrom = htmlspecialchars($param['LoginFrom']);
    $viewinfo  = htmlspecialchars($param['ViewInfo']);
    $toUrl     = htmlspecialchars($param['Url']);
    $attach    = htmlspecialchars($param['Attach']);

    return <<<EOD
        <html>
        <body>
            <form id="jumpForm" action="{$handleUrl}" method="post">
                <input type="hidden" name="Acct" value="{$param['Acct']}" />
                <input type="hidden" name="OpenId" value="{$param['OpenId']}" />
                <input type="hidden" name="LoginFrom" value="{$loginFrom}" />
                <input type="hidden" name="ClubInfo" value="{$param['ClubInfo']}" />
                <input type="hidden" name="ViewInfo" value="{$viewinfo}" />
                <input type="hidden" name="Url" value="{$toUrl}" />
                <input type="hidden" name="Ts" value="{$param['Ts']}" />
                <input type="hidden" name="Attach" value="{$attach}" />
                <input type="hidden" name="Vkey" value="{$vkey}" />
            </form>
        </body>

        <script language="JavaScript">
            document.getElementById('jumpForm').submit();
        </script>
        </html>
EOD;
}


////////////////////////////////////////////////////////函数定义区结束///////////////////////////////////////
cbSetNoCache();

//第1步，获取所有的参数列表并判断参数有效性
$toUrl      = @$_GET['to_url'];
$mId        = @intval($_GET['m_id']);
$attach     = @$_GET['attach'];
$loginFrom  = @$_GET['login_from'];
$uin        = @intval($_GET['uin']);



//第2步，参数验证合法性
if( !array_key_exists($mId , $MALL_CONNECT_CONFIG) ) {
    echo "非法的商户id，对应的商户id必须是在对应的商户配置数组中配置";
    exit();
}

if( !array_key_exists($uin , $UIN_CONNECT_CONFIG) ) {
    echo "非测试QQ号码";
    exit();
}

//if($toUrl != '') {
//   $toUrl = make_safe_url($mId, $toUrl);
//}

if( strlen($attach) > 1024 ) {
    $attach = '';
}

if( $loginFrom == '' ) {
    $loginFrom = 'caibei';
}


//第4步，关键的处理方法:联合登陆逻辑开始
$openId     = $UIN_CONNECT_CONFIG[$uin]['OpenId'];
$acct       = $UIN_CONNECT_CONFIG[$uin]['Acct'];
$clubinfo   = $UIN_CONNECT_CONFIG[$uin]['ClubInfo'];;
$viewinfo   = getViewinfo($uin , $mId , $clubinfo);
$ts         = date('YmdHis');

//传给商家的参数
$postParam = array(
    'Acct'      => $acct , 
    'OpenId'    => $openId ,
    'LoginFrom' => $loginFrom , 
    'ClubInfo'  => $clubinfo ,
    'ViewInfo'  => $viewinfo ,
    'Url'       => $toUrl ,
    'Ts'        => $ts ,
    'Attach'    => $attach
);

$vkey = getVKey($mId , $postParam);

//第5步，输出跳转代码
echo getOutputHtml($mId , $postParam , $vkey);

?>