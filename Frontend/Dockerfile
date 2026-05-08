FROM node:20

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .

EXPOSE 3000

# Dùng cách này ổn định hơn trong Docker
CMD ["npx", "vite", "--host", "0.0.0.0", "--port", "3000"]