-- コンテナを初回起動したあとに手動実行する
CREATE DATABASE event_eagle;
CREATE USER event_eagle IDENTIFIED BY 'event_eagle';
GRANT ALL PRIVILEGES ON event_eagle.* TO 'event_eagle';
