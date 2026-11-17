#!/bin/sh
set -e

cat <<EOF > /usr/share/nginx/html/config.json
{
  "apiUrl": "${API_URL}"
}
EOF
