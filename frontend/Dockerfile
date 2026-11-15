# ---------- Build stage ----------
FROM node:22-alpine AS build
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build -- --configuration production

# ---------- Runtime stage ----------
FROM nginx:1.27-alpine AS runtime

COPY --from=build /app/dist/*/browser /usr/share/nginx/html

COPY docker/nginx.conf /etc/nginx/conf.d/default.conf
COPY docker/entrypoint.sh /docker-entrypoint.d/30-runtime-config.sh
RUN chmod +x /docker-entrypoint.d/30-runtime-config.sh

ENV API_URL=/api

EXPOSE 80
