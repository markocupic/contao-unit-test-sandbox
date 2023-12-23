:: Run easy-coding-standard (ecs) via this batch file inside your IDE e.g. PhpStorm (Windows only)
:: Install inside PhpStorm the  "Batch Script Support" plugin
cd..
cd..
cd..
cd..
cd..
cd..
php vendor\bin\ecs check vendor/markocupic/contao-unit-test-sandbox/src --fix --config vendor/markocupic/contao-unit-test-sandbox/tools/ecs/config.php
php vendor\bin\ecs check vendor/markocupic/contao-unit-test-sandbox/contao --fix --config vendor/markocupic/contao-unit-test-sandbox/tools/ecs/config.php
php vendor\bin\ecs check vendor/markocupic/contao-unit-test-sandbox/config --fix --config vendor/markocupic/contao-unit-test-sandbox/tools/ecs/config.php
php vendor\bin\ecs check vendor/markocupic/contao-unit-test-sandbox/templates --fix --config vendor/markocupic/contao-unit-test-sandbox/tools/ecs/config.php
php vendor\bin\ecs check vendor/markocupic/contao-unit-test-sandbox/tests --fix --config vendor/markocupic/contao-unit-test-sandbox/tools/ecs/config.php
