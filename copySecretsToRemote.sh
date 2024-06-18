ENV_FILE_NAME=./.env

# case: file does not exist
if [ ! -f $ENV_FILE_NAME ]; then 
    touch $ENV_FILE_NAME;
fi

# arg expected to be formatted like VARIABLE=someValue
for arg in "$@"; do
    VARIABLE_NAME=$(echo $arg | grep -o '.*=');

    # case: VARIABLE_NAME not present in file
    if ! grep -xq "^"$VARIABLE_NAME".*$" $ENV_FILE_NAME; then 
        # add var on new line
        printf "\n"$arg >> $ENV_FILE_NAME; 

    # case: var present in file
    else 
        # replace var
        sed -i -e "s/^"$VARIABLE_NAME".*$/"$arg"/" $ENV_FILE_NAME; 
    fi
done