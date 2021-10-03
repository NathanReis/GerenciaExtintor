# !/bin/bash

dir_root="/api"

dir_app=$dir_root"/app"
dir_docs=$dir_root"/docs"

file_phpdoc="phpdoc.phar"
fullpath_phpdoc=$dir_docs"/"$file_phpdoc

cd $dir_root

if [ ! -d $dir_docs ]
then
    # Create docs directory if it doesn't exist
    mkdir $dir_docs

    # Grant permission for all users read, write and execute
    chmod a=rwx $dir_docs
fi

cd $dir_docs

if [ ! -f $fullpath_phpdoc ]
then
    # Download PHP Documentor if it doesn't exist
    # -P is where puts the file
    # -O is what will be the file name
    wget -P $dir_docs -O $file_phpdoc https://phpdoc.org/phpDocumentor.phar

    # Grant permission for all users read and execute
    chmod a=rx $fullpath_phpdoc
fi

# Create API docs
# -d is where is the code
# -t is where puts the generated doc
# --ignore is whats doesn't create the doc
php $fullpath_phpdoc -d $dir_app -t $dir_docs --ignore "vendor/"

# Delete PHP Documentor's trash
rm -fr $dir_docs"/.phpdoc"

# Delete PHP Documentor
rm -f $fullpath_phpdoc
