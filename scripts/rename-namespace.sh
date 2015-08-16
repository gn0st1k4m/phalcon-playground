#!/bin/sh

read -p "Enter the namespace to use: " GLOBALNAMESPACE
find . -name "*.php" -print0 | xargs -0 sed -i -e "s/Phpg/$GLOBALNAMESPACE/g"
sed -i -e "s/Phpg/$GLOBALNAMESPACE/g" ./composer.json
