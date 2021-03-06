<?php
namespace Perspective;

/**
 * View.php - the "view"portion of Celestial's MVC framework
 */

class View
{
    public $subTitle = 'Home';
    public $content = '';

    public function __construct($content, $subTitle = 'Home')
    {
        $this->subTitle = $subTitle;
        $this->content = $content;
    }

    // OTHER FUNCTIONS
    public static function setHTTPHeaders($contentType = '', $cacheTime = 3600)
    {
        /*
         *  Purpose: set necessary HTTP headers; usually includes security and cache headers
         *
         *  Params:
         * 		* $contentType :: string :: content-type header value to be used to specify what type of content is
         *          being served
         * 		* $cacheTime :: int :: amount of time to store data for, in seconds (default = 3600s = 1 hour)
         *
         *  Returns: NONE
         */

        // normalize cache time to integer and validate it
        $cacheTime = (int) $cacheTime;
        if($cacheTime < 0)
        {
            throw new \Exception('invalid cache time supplied');
        }

        // SECURITY
        // HSTS
        header('strict-transport-security: max-age=86400');

        // X-Frame-Options
        header('X-Frame-Options: sameorigin');

        // Browser XSS Protection
        header('X-XSS-Protection: 1');

        // Disable Content Sniffing (why IE...)
        header('X-Content-Type-Options: nosniff');

        // CSP
        header('Content-Security-Policy: default-src https:; style-src https: \'unsafe-inline\'');

        // Cross-Domain Policies
        header('X-Permitted-Cross-Domain-Policies: none');

        // CACHING
        // Cache-Control
        header('Cache-Control: max-age='.$cacheTime);

        // OTHER
        // Content-Type
        if(!empty($contentType))
        {
            header($contentType);
        }
    }

    public function generateHTML()
    {
        /*
         *  Purpose: generate string of HTML to be displayed to the user
         *
         *  Params: NONE
         *
         *  Returns: string
         */

        return '
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name=viewport content="width=device-width, initial-scale=1">
        
        <link rel="shortcut icon" href="favicon.png" type="image/x-icon">
        
        <!-- css -->
        <link rel="stylesheet" href="static/css/main.css" media="all">
        
        <title>'.$this->subTitle.' - Inquisition</title>
    </head>
    <body id="'.strtolower($this->subTitle).'">
        <div id="mainLoadingProgressBar"></div>
        <header>
            <div class="headerWrapper">
                <div id="titleLogoWrapper">
                    <a href="./" title="Go to Inquisition homepage">
                        <img src="static/imgs/title_logo.png" alt="Welcome to Inquisition">
                    </a>
                </div>
                <!--<div id="loginModule">-->
                    <!--Josh Carlson | logout-->
                <!--</div>-->
            </div>
        </header>
        <main>
            <div id="mainWrapper">
                <nav>
                    <div id="navContainer">
                        <div class="navOption selected">
                            <img src="static/imgs/icons/alerts.svg" alt="View Current Alerts">
                            <span>Alerts</span>
                        </div>
                        <div class="navOption">
                            <img src="static/imgs/icons/stats.svg" alt="View Current Alerts">
                            <span>Stats</span>
                        </div>
                        <div class="navOption">
                            <img src="static/imgs/icons/tuning.svg" alt="View Current Alerts">
                            <span>Tuning</span>
                        </div>
                    </div>
                </nav>
                <div id="contentWrapper">
                    '.$this->content.'
                </div>
            </div>
        </main>
        <footer>
            <div>
                <a href="https://opensource.org/licenses/MIT">MIT License</a> // <a href="https://github.com/magneticstain/Inquisition">Project on GitHub</a>
            </div>
        </footer>
        <script src="static/js/jquery-3.2.1.min.js"></script>
        <script src="static/js/pace.min.js"></script>
        <script src="static/js/nav.js"></script>
        <script src="static/js/main.js"></script>
    </body>
</html>';
    }

    public function __toString()
    {
        // Overload of toString function in order to generate HTML when this class as an obj is treated as a string
        return $this->generateHTML();
    }
}