#!/bin/sh

read -e -p "Enter the global namespace to use: " -i "MyFancyProject" GLOBALNAMESPACE
find ./src -type f -print0 | xargs sed -i -e "s/Phpg\\/$GLOBALNAMESPACE\\/g"
find ./config -type f -print0 | xargs sed -i -e "s/Phpg\\/$GLOBALNAMESPACE\\/g"
sed -i -e "s/Phpg/$GLOBALNAMESPACE/g" ./README.md
sed -i -e "s/Phpg/$GLOBALNAMESPACE/g" ./composer.json

read -e -p "Enter the composer name: " -i "myfancy\/project" COMPOSERNAME
sed -i -e "s/mamuz/phalcon-playground/$COMPOSERNAME/g" ./composer.json

read -e -p "Enter the project description: " -i "my project description" DESCRIPTION
sed -i -e "s/project description/$DESCRIPTION/g" ./composer.json

composer update --lock
composer dumpautoload
