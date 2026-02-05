FROM node:20

WORKDIR /app

# package.json をコピーして依存関係をインストール
COPY package*.json ./
RUN npm install

# アプリケーションファイルをコピー
COPY . .

# Vite のデフォルトポート
EXPOSE 5173

# 開発サーバーを起動
CMD ["npm", "run", "dev", "--", "--host"]