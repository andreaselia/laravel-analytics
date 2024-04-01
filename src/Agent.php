<?php

namespace AndreasElia\Analytics;

use Closure;
use Detection\MobileDetect;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

/**
 * @copyright Originally created by Jens Segers: https://github.com/jenssegers/agent
 */
class Agent extends MobileDetect
{
    /** @var array<string, string> */
    protected static $additionalOperatingSystems = [
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

    /** @var array<string, string> */
    protected static $additionalBrowsers = [
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

    /** @var CrawlerDetect */
    protected static $crawlerDetect;

    /** @var array<string, mixed> */
    protected $store = [];

    /** @return string|null */
    public function platform()
    {
        return $this->retrieveUsingCacheOrResolve('analytics.platform', function () {
            return $this->findDetectionRulesAgainstUserAgent(
                $this->mergeRules(MobileDetect::getOperatingSystems(), static::$additionalOperatingSystems)
            );
        });
    }

    /** @return string|null */
    public function browser()
    {
        return $this->retrieveUsingCacheOrResolve('analytics.browser', function () {
            return $this->findDetectionRulesAgainstUserAgent(
                $this->mergeRules(static::$additionalBrowsers, MobileDetect::getBrowsers())
            );
        });
    }

    /** @return bool */
    public function isDesktop()
    {
        return $this->retrieveUsingCacheOrResolve('analytics.desktop', function () {
            // Check specifically for cloudfront headers if the useragent === 'Amazon CloudFront'
            if (
                $this->getUserAgent() === static::$cloudFrontUA
                && $this->getHttpHeader('HTTP_CLOUDFRONT_IS_DESKTOP_VIEWER') === 'true'
            ) {
                return true;
            }

            return ! $this->isMobile() && ! $this->isTablet();
        });
    }

    /** @return string|null */
    protected function findDetectionRulesAgainstUserAgent(array $rules)
    {
        $userAgent = $this->getUserAgent();

        foreach ($rules as $key => $regex) {
            if (empty($regex)) {
                continue;
            }

            if ($this->match($regex, $userAgent)) {
                return $key ?: reset($this->matchesArray);
            }
        }

        return null;
    }

    /** @return mixed */
    protected function retrieveUsingCacheOrResolve(string $key, Closure $callback)
    {
        $cacheKey = $this->createCacheKey($key);

        if (! is_null($cacheItem = $this->store[$cacheKey] ?? null)) {
            return $cacheItem;
        }

        return tap(call_user_func($callback), function ($result) use ($cacheKey) {
            $this->store[$cacheKey] = $result;
        });
    }

    /** @return array<string, string> */
    protected function mergeRules(...$all)
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

    public function getCrawlerDetect(): CrawlerDetect
    {
        if (static::$crawlerDetect === null) {
            static::$crawlerDetect = new CrawlerDetect();
        }

        return static::$crawlerDetect;
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
}
