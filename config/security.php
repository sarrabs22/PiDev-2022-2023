<?php
use Symfony\Config\SecurityConfig;

return static function (SecurityConfig $security) {
    $security->enableAuthenticatorManager(true);

    $mainFirewall = $security->firewall('main');

    // by default, the feature allows 5 login attempts per minute
    $mainFirewall->loginThrottling()
        // ->maxAttempts(3)         // Optional: You can configure the maximum attempts ...
        // ->interval('15 minutes') // ... and the period of time.
    ;
};


?>