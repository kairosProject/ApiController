#! /usr/bin/env bash

function usage {
  echo "Usage: $0 [-p path/to/php] [-c path/to/composer] [-h] [-d] [-m]" 1>&2
  echo "-d : development mode" 1>&2
  echo "-m : no metrics mode" 1>&2
  exit 1
}

SOURCE_PATH="KairosProject/"
PHP_PATH="/usr/bin/env php"
COMPOSER_PATH="/usr/bin/env composer"

INFECTION="vendor/bin/infection --threads=4 --min-msi=100 --log-verbosity=1"
METRICS="vendor/bin/phpmetrics --report-html=doc/metrics --junit=doc/phpunit_logfile.xml ."
TESTSUITE=""

while getopts "udmp:c:h:" opt; do
  case $opt in
    d)
      INFECTION="vendor/bin/infection --threads=4 --min-msi=100 --only-covered --log-verbosity=1"
      ;;
    p)
      PHP_PATH=$OPTARG
      ;;
    c)
      COMPOSER_PATH=$OPTARG
      ;;
    h)
      usage
      exit 0
      ;;
    m)
      METRICS=""
      ;;
    u)
      TESTSUITE=" --testsuite unit "
      ;;
    \?)
      echo "Invalid option: -$OPTARG" >&2
      usage
      exit 1
      ;;
    :)
      echo "Option -$OPTARG requires an argument." >&2
      usage
      exit 1
      ;;
  esac
done

STATUS=0
TEST_RES=""

function runner {
    TEST_RES=`$1`
    local TEST_RET=$?
}

function test {
	echo -e "\e[43m\e[30mRunning $1\e[0m\n\e[49m"
    TEST_RES=`$1`
    local TEST_RET=$?
    
    if [ $TEST_RET != 0 ]
    then
        echo -e "\e[31m$2 FAILED\e[0m"
        
        echo "$TEST_RES"
        
        STATUS=$((STATUS + $3))
    else
        echo -e "\e[32m$2 SUCCESS\e[0m"
    fi
}

if [ ! -d "doc" ]
then
    mkdir -p "doc"
fi

test "$COMPOSER_PATH install" INSTALL 100
echo "$TEST_RES" >> doc/composer.txt

runner "$PHP_PATH vendor/bin/phpcbf --standard=./csruleset.xml $SOURCE_PATH"
echo "$TEST_RES" >> doc/phpcbf.txt

test "$PHP_PATH vendor/bin/phpunit $TESTSUITE" PHPUnit 100
echo "$TEST_RES" > doc/phpunit.txt

test "$PHP_PATH $INFECTION" Infection 100
echo "$TEST_RES" > doc/infection.txt

test "$PHP_PATH vendor/bin/phpcs --standard=./csruleset.xml $SOURCE_PATH" PHPCS 100
echo "$TEST_RES" > doc/phpcs.txt

test "$COMPOSER_PATH validate" COMPOSER 100
echo "$TEST_RES" > doc/composer.txt

test "$PHP_PATH vendor/bin/phpmd $SOURCE_PATH text ./phpmd.xml" PHPMD 100
echo "$TEST_RES" > doc/phpmd.txt

test "$PHP_PATH vendor/bin/phpcpd $SOURCE_PATH" PHPCPD 1
echo "$TEST_RES" > doc/phpcpd.txt

if [ -n "$METRICS" ]
then
	test "$PHP_PATH $METRICS" PHPMetrics 1
	echo "$TEST_RES" > doc/phpmetrics.txt
fi

if [ "$STATUS" -eq 0 ]
then
    echo -e "\n\e[42m"
    echo -e "\e[30mTHE STATUS IS STABLE\n\e[0m\n\e[49m"
elif [ "$STATUS" -lt 100 ]
then
    echo -e "\n\e[43m"
    echo -e "\e[30mTHE STATUS IS UNSTABLE\n\e[0m\n\e[49m"
else
    echo -e "\n\e[41m"
    echo -e "\e[30mTHE STATUS IS FAILURE\n\e[0m\n\e[49m"
fi

exit $STATUS
