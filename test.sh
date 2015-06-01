#!/bin/sh
FAILED=0
for i in xml soap_asendia_web_api_client
do
  echo Running $i tests
  echo
  php tests/test_$i.php || FAILED=1
  echo
done
exit $FAILED
