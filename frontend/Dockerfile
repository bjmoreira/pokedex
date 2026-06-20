# EN: Multi-stage build - compile the Vue SPA, then serve it with nginx.
# PT: Build multi-stage - compila o SPA Vue e o serve com nginx.

# --- Build stage / Etapa de build ---
FROM node:22-alpine AS build

WORKDIR /app

# EN: Install dependencies first for better layer caching.
# PT: Instala dependências primeiro para melhor cache de camadas.
COPY package.json package-lock.json* ./
RUN npm install

# EN: Build the production bundle. / PT: Gera o bundle de produção.
COPY . .
RUN npm run build

# --- Runtime stage / Etapa de execução ---
FROM nginx:1.27-alpine AS runtime

# EN: Replace the default site config with our SPA + API proxy config.
# PT: Substitui a config padrão pela nossa config de SPA + proxy de API.
COPY nginx.conf /etc/nginx/conf.d/default.conf
COPY --from=build /app/dist /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
