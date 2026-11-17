#!/bin/bash
# Take the swbo site down and put the VPS back the way it was before 2026-11-17.
# Run on the VPS:  sudo bash /opt/swbo/undo.sh            (keeps the DB volume and images)
#                  sudo bash /opt/swbo/undo.sh --purge    (also deletes the DB, images and /opt/swbo)
set -uo pipefail

# 1. The deploy key stops working at once.
rm -f /home/swbo-deploy/.ssh/authorized_keys
f=/etc/ssh/sshd_config.d/10-jbot.conf
[ -f "$f.pre-swbo" ] && mv "$f.pre-swbo" "$f"
sshd -t && systemctl reload ssh

# 2. Stop the site.
if [ -f /opt/swbo/compose.yml ]; then
  (cd /opt/swbo && docker compose -f compose.yml --env-file .env down $([ "${1:-}" = --purge ] && echo -v --rmi all))
fi

# 3. Firewall back to SSH only (this also wipes Docker's own rules, so Docker is stopped after it).
[ -f /etc/nftables.conf.pre-swbo ] && mv /etc/nftables.conf.pre-swbo /etc/nftables.conf
rm -f /etc/nftables.conf.swbo
nft -f /etc/nftables.conf
systemctl disable --now docker.service docker.socket

if [ "${1:-}" = --purge ]; then
  userdel -r swbo-deploy 2>/dev/null
  rm -rf /opt/swbo
fi
echo "undone. Docker is installed but stopped and disabled; remove it with: apt-get purge docker.io docker-compose-v2 docker-buildx"
