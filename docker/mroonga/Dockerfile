FROM groonga/mroonga:mysql8019_mroonga912

RUN mkdir -p /var/log/mysql
RUN chmod -R 777 /var/log/mysql
# execute init.sql manually in container to initialize database
COPY init.sql /var/lib/mysql-files/init.sql
