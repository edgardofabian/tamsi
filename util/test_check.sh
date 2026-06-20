if [ 'curl -s "https://school.gahum.tech/?command=isPassTaskResult&object=User&user_id=11&key=AgtangSaKabayo1974_kabaw" | jq -r ".status"' = 'false' ]
then
echo 'false result'
else
echo 'true result';
fi
