
FROM nginx:stable-alpine
COPY ./default.conf /etc/nginx/conf.d/default.conf
COPY ./hosts /etc/hosts
EXPOSE 80