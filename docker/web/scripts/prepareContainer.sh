# !/bin/bash

dir_root="/web/app"

tempdir=$dir_root"/temp"

cd $dir_root

# Install dependencies
composer install

if [ ! -f ".env" ]
then
    # Create .env file if it doesn't exist
    cp .env.example .env
fi

if [ -d $tempdir ]
then
    # Delete temporary directory
    rm -fr $tempdir
fi

# Create temporary directory
mkdir $tempdir

# Grant permission for all users read, write and execute
chmod a=rwx $tempdir

# Start Apache Server
apache2-foreground
