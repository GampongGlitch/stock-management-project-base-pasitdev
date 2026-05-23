<?php 

class Core {
    public static function isMobileDevice() {
        $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $mobileAgents = ['iphone', 'ipod', 'android', 'blackberry', 'windows phone', 'opera mini', 'mobile'];

        foreach ($mobileAgents as $agent) {
            if (strpos($userAgent, $agent) !== false) {
                return true; // It's a mobile device
            }
        }
        return false; // It's not a mobile device
    }
}

?> 