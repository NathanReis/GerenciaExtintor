# !/bin/bash

dir_root="/api/app"

dir_database=$dir_root"/src/database"

file_cleandatabase="cleandatabase.sqlite"
file_database="database.sqlite"

cd $dir_root

# Install dependencies
composer install

if [ ! -f ".env" ]
then
    # Create .env file if it doesn't exist
    cp .env.example .env
fi

cd $dir_database

if [ ! -f $file_database ]
then
    # Create database file if it doesn't exist
    cp $file_cleandatabase $file_database

    # Grant permission for all users read and write
    chmod a=rw $file_database
fi

# Start Apache Server
apache2-foreground
