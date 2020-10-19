#!/usr/bin/env bash

# https://github.com/travis-ci/travis-build/blob/master/lib/travis/build/bash/travis_retry.bash
retry()
{
    local -i result="0"
    local -i count="1"

    while [ "${count}" -le 3 ]; do
        if [ "${result}" -ne 0 ]; then
            echo
            echo "The command \"${*}\" failed. Retrying, ${count} of 3." 1>&2
            echo
        fi
        # Run the command in a way that doesn't disable setting `errexit`
        if "${@}"; then
            break
        else
            result="${?}"
        fi
        count+="1"
        sleep 1
    done

    if [ "${count}" -gt 3 ]; then
        echo
        echo "The command \"${*}\" failed 3 times." 1>&2
        echo
    fi

    return "${result}"
}

set -e

if [ ! -r "${PHIVE_DOT_PATH}/phive.phar" ]; then
    mkdir -p "${PHIVE_DOT_PATH}"

    if php -r 'exit(version_compare(PHP_VERSION, "7.2.0", "<") ? 0 : 1);'; then
        # Last phive version supporting PHP 7.1: v0.13.3
        retry wget --tries=1 --output-document="${PHIVE_DOT_PATH}/phive.phar" "https://github.com/phar-io/phive/releases/download/0.13.3/phive-0.13.3.phar"
        retry wget --tries=1 --output-document="${PHIVE_DOT_PATH}/phive.phar.asc" "https://github.com/phar-io/phive/releases/download/0.13.3/phive-0.13.3.phar.asc"
    else
        retry wget --tries=1 --output-document="${PHIVE_DOT_PATH}/phive.phar" "https://phar.io/releases/phive.phar"
        retry wget --tries=1 --output-document="${PHIVE_DOT_PATH}/phive.phar.asc" "https://phar.io/releases/phive.phar.asc"
    fi
    retry gpg --batch --keyserver ha.pool.sks-keyservers.net --keyserver-options timeout=30 --recv-keys "${PHIVE_SIGNING_KEY}"
    if ! gpg --batch --verify "${PHIVE_DOT_PATH}/phive.phar.asc" "${PHIVE_DOT_PATH}/phive.phar"; then
        echo "Invalid phive signature" 1>&2
        rm -f "${PHIVE_DOT_PATH}/phive.phar"
        exit 11
    fi
    rm "${PHIVE_DOT_PATH}/phive.phar.asc"
fi

install --verbose --mode=0755 --no-target-directory -D "${PHIVE_DOT_PATH}/phive.phar" "${PHIVE_BIN_PATH}"
