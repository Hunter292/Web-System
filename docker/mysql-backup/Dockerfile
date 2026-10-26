FROM alpine:3.20

RUN apk add --no-cache mysql-client bash tzdata dcron

COPY backup.sh /usr/local/bin/backup.sh
RUN chmod +x /usr/local/bin/backup.sh

COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

VOLUME ["/backups"]
ENTRYPOINT ["/entrypoint.sh"]

