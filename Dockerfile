FROM mysql:5.6


ENV MYSQL_DATABASE=cash-transactions \
    MYSQL_ROOT_PASSWORD=secret

EXPOSE 3306