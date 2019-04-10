FROM mongo:3.4
COPY ["start_script.sh", "/"]
COPY ["data_json/*", "/data/db_import/"]

RUN chmod -R 777 /data/db_import/

RUN chmod +x /start_script.sh

RUN echo "deb http://archive.ubuntu.com/ubuntu precise main universe multiverse" > /etc/apt/sources.list
RUN apt-get update
RUN apt-get install -y --force-yes netcat           # nc

CMD ["mongod"]
