<?php
class Config {
    // db
    public $dbAdaptor = ''; // mysql, oracle, postgresql
    public $dbHost = '';
    public $dbPort = '';
    public $dbUser = '';
    public $dbPass = '';
    public $dbName = '';
    public $dbPrefix = '';

    // app
    public $offline = false; // true false
    public $dev = true; // true false
    public $timezone = '';
    public $siteName = '';
    public $siteNameLocation = 1; //0,1,2
    public $baseUri = '/';
    public $cryptKey = '721a79281f408416';
    public $environment = ''; //get by file config name
    public $aclAdapter = 'memory'; // memory, database
    public $cacheAdapter = 'file'; // file, memcache, memcached
    public $cacheHost = '';
    public $cachePort = '';
    public $metaDesc = '';
    public $metaKeys = '';
    public $metaRobots = ''; // 'Index, Follow', 'No index, follow', 'Index, no follow', 'No index, no follow'
    public $metaRights = '';
    public $metaAuthor = false; // true false
    public $cookieName = 'mgRm';
    public $cookiePath = '/';
    public $cookieDomain = '';
    public $cookieSecure = false; // true false
    public $cookieExpire = 2592000;

    // api
    public $fbId = '';
    public $fbSecret = '';
    public $gOAuthId = '';
    public $gOAuthSecret = '';
    public $gOAuthKey = '';
    public $gCaptchaKey = '';
    public $gCaptchaSecret = '';

    // mail
    public $mailer = 'mail'; // smtp, mail, sendmail
    public $mailFrom = '';
    public $fromName = '';
    public $sendmail = '/usr/sbin/sendmail -bs';
    public $smtpHost = '';
    public $smtpPort = '';
    public $smtpSecure = false; // true false
    public $smtpUser = '';
    public $smtpPass = '';
}