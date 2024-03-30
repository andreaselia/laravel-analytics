<?php

namespace AndreasElia\Analytics;

use Detection\MobileDetect;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class Agent extends MobileDetect
{
    private const VER = '([\w._\+]+)';

    private const VERSION_TYPE_STRING = 'text';

    private const VERSION_TYPE_FLOAT = 'float';

    protected static array $desktopDevices = [
        'Macintosh' => 'Macintosh',
    ];

    protected static array $additionalOperatingSystems = [
        'Windows' => 'Windows',
        'Windows NT' => 'Windows NT',
        'OS X' => 'Mac OS X',
        'Debian' => 'Debian',
        'Ubuntu' => 'Ubuntu',
        'Macintosh' => 'PPC',
        'OpenBSD' => 'OpenBSD',
        'Linux' => 'Linux',
        'ChromeOS' => 'CrOS',
    ];

    protected static array $additionalBrowsers = [
        'Opera Mini' => 'Opera Mini',
        'Opera' => 'Opera|OPR',
        'Edge' => 'Edge|Edg',
        'Coc Coc' => 'coc_coc_browser',
        'UCBrowser' => 'UCBrowser',
        'Vivaldi' => 'Vivaldi',
        'Chrome' => 'Chrome',
        'Firefox' => 'Firefox',
        'Safari' => 'Safari',
        'IE' => 'MSIE|IEMobile|MSIEMobile|Trident/[.0-9]+',
        'Netscape' => 'Netscape',
        'Mozilla' => 'Mozilla',
        'WeChat' => 'MicroMessenger',
    ];

    protected static array $additionalProperties = [
        // Operating systems
        'Windows' => 'Windows NT [VER]',
        'Windows NT' => 'Windows NT [VER]',
        'OS X' => 'OS X [VER]',
        'BlackBerryOS' => ['BlackBerry[\w]+/[VER]', 'BlackBerry.*Version/[VER]', 'Version/[VER]'],
        'AndroidOS' => 'Android [VER]',
        'ChromeOS' => 'CrOS x86_64 [VER]',

        // Browsers
        'Opera Mini' => 'Opera Mini/[VER]',
        'Opera' => [' OPR/[VER]', 'Opera Mini/[VER]', 'Version/[VER]', 'Opera [VER]'],
        'Netscape' => 'Netscape/[VER]',
        'Mozilla' => 'rv:[VER]',
        'IE' => ['IEMobile/[VER];', 'IEMobile [VER]', 'MSIE [VER];', 'rv:[VER]'],
        'Edge' => ['Edge/[VER]', 'Edg/[VER]'],
        'Vivaldi' => 'Vivaldi/[VER]',
        'Coc Coc' => 'coc_coc_browser/[VER]',
    ];

    /** @var CrawlerDetect */
    protected static $crawlerDetect;

    public function getRules(): array
    {
        static $rules;

        if (! $rules) {
            $rules = static::mergeRules(
                static::$desktopDevices, // NEW
                static::$phoneDevices,
                static::$tabletDevices,
                static::$operatingSystems,
                static::$additionalOperatingSystems, // NEW
                static::$browsers,
                static::$additionalBrowsers, // NEW
                // static::$utilities,
            );
        }

        return $rules;
    }

    public function getCrawlerDetect(): CrawlerDetect
    {
        if (static::$crawlerDetect === null) {
            static::$crawlerDetect = new CrawlerDetect();
        }

        return static::$crawlerDetect;
    }

    public static function getBrowsers(): array
    {
        return static::mergeRules(
            static::$additionalBrowsers,
            static::$browsers
        );
    }

    public static function getOperatingSystems(): array
    {
        return static::mergeRules(
            static::$operatingSystems,
            static::$additionalOperatingSystems
        );
    }

    public static function getPlatforms(): array
    {
        return static::mergeRules(
            static::$operatingSystems,
            static::$additionalOperatingSystems
        );
    }

    public static function getDesktopDevices(): array
    {
        return static::$desktopDevices;
    }

    public static function getProperties(): array
    {
        return static::mergeRules(
            static::$additionalProperties,
            static::$properties
        );
    }

    protected function findDetectionRulesAgainstUA(array $rules)
    {
        if (! $this->hasUserAgent()) {
            throw new MobileDetectException('No valid user-agent has been set.');
        }

        if ($this->isUserAgentEmpty()) {
            return false;
        }

        foreach ($rules as $key => $regex) {
            if (empty($regex)) {
                continue;
            }

            if (is_array($regex)) {
                $regex = implode('|', $regex);
            }

            if ($this->match($regex, $this->getUserAgent())) {
                return $key ?: reset($this->matchesArray);
            }
        }

        return false;
    }

    public function browser()
    {
        return $this->findDetectionRulesAgainstUA(static::getBrowsers(),);
    }

    public function platform()
    {
        return $this->findDetectionRulesAgainstUA(static::getPlatforms());
    }

    public function device()
    {
        $rules = static::mergeRules(
            static::getDesktopDevices(),
            static::getPhoneDevices(),
            static::getTabletDevices(),
        );

        return $this->findDetectionRulesAgainstUA($rules);
    }

    public function isDesktop(): bool
    {
        if (! $this->hasUserAgent()) {
            throw new MobileDetectException('No valid user-agent has been set.');
        }

        if ($this->isUserAgentEmpty()) {
            return false;
        }

        // Check specifically for cloudfront headers if the useragent === 'Amazon CloudFront'
        if ($this->getUserAgent() === 'Amazon CloudFront') {
            $cfHeaders = $this->getCfHeaders();

            if (array_key_exists('HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER', $cfHeaders)) {
                return $cfHeaders['HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER'] === 'true';
            }
        }

        return ! $this->isMobile() && ! $this->isTablet() && ! $this->isRobot();
    }

    public function robot()
    {
        if ($this->getCrawlerDetect()->isCrawler($this->getUserAgent())) {
            return ucfirst($this->getCrawlerDetect()->getMatches());
        }

        return false;
    }

    public function isRobot(): bool
    {
        return $this->getCrawlerDetect()->isCrawler($this->getUserAgent());
    }

    public function deviceType()
    {
        if ($this->isRobot()) {
            return 'robot';
        } elseif ($this->isDesktop()) {
            return 'desktop';
        } elseif ($this->isPhone()) {
            return 'phone';
        } elseif ($this->isTablet()) {
            return 'tablet';
        }

        return 'other';
    }

    public function isPhone(): bool
    {
        return $this->isMobile() && ! $this->isTablet();
    }

    public function version(string $propertyName, string $type = self::VERSION_TYPE_STRING): float|bool|string
    {
        if (empty($propertyName)) {
            return false;
        }

        // Set the $type to the default if we don't recognize the type
        if ($type !== self::VERSION_TYPE_STRING && $type !== self::VERSION_TYPE_FLOAT) {
            $type = self::VERSION_TYPE_STRING;
        }

        $properties = self::getProperties();

        // Check if the property exists in the properties array.
        if (true === isset($properties[$propertyName])) {
            // Prepare the pattern to be matched.
            // Make sure we always deal with an array (string is converted).
            $properties[$propertyName] = (array) $properties[$propertyName];

            foreach ($properties[$propertyName] as $propertyMatchString) {
                if (is_array($propertyMatchString)) {
                    $propertyMatchString = implode('|', $propertyMatchString);
                }

                $propertyPattern = str_replace('[VER]', self::VER, $propertyMatchString);

                // Identify and extract the version.
                preg_match(sprintf('#%s#is', $propertyPattern), $this->userAgent, $match);

                if (false === empty($match[1])) {
                    $version = ($type === self::VERSION_TYPE_FLOAT ? $this->prepareVersionNo($match[1]) : $match[1]);

                    return $version;
                }
            }
        }

        return false;
    }

    protected static function mergeRules(...$all)
    {
        $merged = [];

        foreach ($all as $rules) {
            foreach ($rules as $key => $value) {
                if (empty($merged[$key])) {
                    $merged[$key] = $value;
                } elseif (is_array($merged[$key])) {
                    $merged[$key][] = $value;
                } else {
                    $merged[$key] .= '|'.$value;
                }
            }
        }

        return $merged;
    }

    public function is(string $ruleName): bool
    {
        if (! $this->hasUserAgent()) {
            throw new MobileDetectException('No user-agent has been set.');
        }

        if ($this->isUserAgentEmpty()) {
            return false;
        }

        try {
            $cacheKey = $this->createCacheKey($ruleName);
            $cacheItem = $this->cache->get($cacheKey);

            if (! is_null($cacheItem)) {
                return $cacheItem->get();
            }

            $result = $this->matchUserAgentWithRule($ruleName);

            $this->cache->set($cacheKey, $result);
            return $result;
        } catch (CacheException $e) {
            throw new MobileDetectException("Cache problem in is(): {$e->getMessage()}");
        }
    }

    public function languages(string $acceptLanguage = null): array
    {
        if ($acceptLanguage === null) {
            $acceptLanguage = $this->getHttpHeader('HTTP_ACCEPT_LANGUAGE');
        }

        if (! $acceptLanguage) {
            return [];
        }

        $languages = [];

        // Parse accept language string.
        foreach (explode(',', $acceptLanguage) as $piece) {
            $parts = explode(';', $piece);
            $language = strtolower($parts[0]);
            $priority = empty($parts[1]) ? 1. : floatval(str_replace('q=', '', $parts[1]));

            $languages[$language] = $priority;
        }

        // Sort languages by priority.
        arsort($languages);

        return array_keys($languages);
    }
}
