# Official images are cool.
FROM jenkins/jenkins:lts

# Jenkins is using jenkins user, we need root to install things.
USER root
# Install php packages.
RUN apt-get update

RUN apt -y install apt-transport-https lsb-release ca-certificates
RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg
RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" > /etc/apt/sources.list.d/php.list
RUN apt update
RUN apt-get -y -f install php7.2 php7.2-cli php7.2-common
RUN apt-get -y -f install php7.2-curl php7.2-gd php7.2-json php7.2-mbstring php7.2-intl php7.2-mysql php7.2-xml php7.2-zip


# Install docker
RUN apt-get -y -f install \
    apt-transport-https \
    ca-certificates \
    curl \
    software-properties-common

RUN curl -fsSL https://download.docker.com/linux/debian/gpg | apt-key add -
RUN add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable"
RUN apt-get update
RUN apt-get -y -f install docker-ce
RUN usermod -aG docker root

# Create a jenkins "HOME" for composer files.
RUN mkdir /home/jenkins
RUN chown jenkins:jenkins /home/jenkins

# Install composer, yes we can't install it in $JENKINS_HOME :(
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/home/jenkins

# Install required php tools.
RUN /home/jenkins/composer.phar --working-dir="/home/jenkins" -n require phpunit/phpunit:~7.0

USER root
RUN apt-get clean -y