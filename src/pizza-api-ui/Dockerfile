FROM node:11.13.0-alpine
WORKDIR /app
COPY . /app/

RUN yarn install
RUN yarn build

EXPOSE 3000

ENV HOST 0.0.0.0
ENV PORT 3000

CMD [ "yarn", "start" ]
