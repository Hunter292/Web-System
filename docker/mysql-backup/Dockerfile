FROM alpine:3.20

RUN apk add --no-cache mysql-client bash tzdata dcron

COPY backup.sh /usr/local/bin/backup.sh
COPY entrypoint.sh /entrypoint.sh

# Windows checkouts can reintroduce CRLF; a CRLF shebang breaks exec ("no such
# file or directory", exit 127/255) and the container restart-loops.
RUN tr -d '\r' < /usr/local/bin/backup.sh > /tmp/backup.sh \
 && mv /tmp/backup.sh /usr/local/bin/backup.sh \
 && tr -d '\r' < /entrypoint.sh > /tmp/entrypoint.sh \
 && mv /tmp/entrypoint.sh /entrypoint.sh \
 && chmod +x /usr/local/bin/backup.sh /entrypoint.sh

VOLUME ["/backups"]
ENTRYPOINT ["/entrypoint.sh"]

