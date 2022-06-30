FROM php:7.4-cli
COPY . /Sonnen Batterie Data Retriver.php
WORKDIR /usr/src/
CMD [ "php", "./Sonnen Batterie Data Retriver.php" ]