#!/bin/bash
# Server side of the deploy. Installed at /opt/swbo/deploy.sh (root-owned) and bound to the
# deploy key in ~swbo-deploy/.ssh/authorized_keys as a forced command, so that key can run
# nothing else. It reads a .tar.gz of the repo on stdin, unpacks it as a new release,
# points /opt/swbo/releases/current at it and rebuilds the stack from the server's own compose file.
set -euo pipefail
umask 022
BASE=/opt/swbo
exec 9>"$BASE/releases/.lock"
flock -w 600 9 || { echo "another deploy is still running" >&2; exit 1; }

rel="$BASE/releases/$(date +%Y%m%d-%H%M%S)"
mkdir "$rel"
# Cap the upload at 300 MB; the repo itself is a few MB.
head -c 300000000 | tar -xz -C "$rel" --no-same-owner --no-same-permissions
[ -f "$rel/frontend/Dockerfile" ] && [ -f "$rel/backend/Dockerfile" ] \
  || { echo "upload has no frontend/ and backend/ Dockerfiles; refusing" >&2; rm -rf "$rel"; exit 1; }

ln -sfn "$rel" "$BASE/releases/current.new" && mv -T "$BASE/releases/current.new" "$BASE/releases/current"
echo "release: $rel"

cd "$BASE"
docker compose -f compose.yml --env-file .env up -d --build --remove-orphans
docker image prune -f >/dev/null

# Keep the last 3 releases.
ls -1d "$BASE"/releases/2* | head -n -3 | xargs -r rm -rf
docker compose -f compose.yml --env-file .env ps --format 'table {{.Service}}\t{{.Status}}'
