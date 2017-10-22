#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
regex=": (.*)"

regex=": (.*)"
password_yml=$(grep "database_password" "$DIR"/../app/config/parameters.yml)
user_yml=$(grep "database_user" "$DIR"/../app/config/parameters.yml)
name_yml=$(grep "database_name" "$DIR"/../app/config/parameters.yml)

[[ $password_yml =~ $regex ]]
database_password="${BASH_REMATCH[1]}"

[[ $user_yml =~ $regex ]]
database_user="${BASH_REMATCH[1]}"

[[ $name_yml =~ $regex ]]
database_name="${BASH_REMATCH[1]}"

echo "use duolingros;" > db_temp.sql
mysqldump -u $database_user -p$database_password $database_name fos_user learning >> db_temp.sql

yes | "$DIR"/../bin/console doctrine:fixture:load -vvv
cat db_temp.sql | mysql -u $database_user -p$database_password

echo "Ouki"
