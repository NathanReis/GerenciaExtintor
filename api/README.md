# Back-end - Gerencia de extintores

## Comandos com PHP instalado local

- Iniciar servidor

```bash
php -S localhost:666 public/index.php
```

## Comandos com usando Docker


- Criar container

```bash
docker build -t php .
```

- Iniciar container

```bash
docker run -dp 666:666 php
```

- Listar IDs do containers rodando

```bash
docker ps
```

- Acessar container via terminal

```bash
docker exec -it <container id> /bin/sh
```

- Apagar container

```bash
docker rm -f <container id>
```

- Direto via docker run

```bash
docker run `
    -dp 666:666 `
    -w /var/www -v "$(pwd):/var/www" `
    php:8.0-alpine `
    sh -c "php composer.phar install && php -S localhost:666 public/index.php"
```
