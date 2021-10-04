# Gerência de extintores

Projeto desenvolvido para disciplina de estágio com o objetivo de melhor o
controle dos extintores de incêndio da empresa.

Estas melhorias consistem em permitir de forma rápida a visualização dos
equipamentos já vencidos e quais locais não possuem equipamentos.

## Como executar

1. Clone o repositório

```bash
git clone git@github.com:NathanReis/GerenciaExtintor.git
```

2. Acesse a pasta referente ao clone

```bash
cd GerenciaExtintor
```

3. Suba os containers

```bash
docker-compose up -d
```

---

Pronto, a aplicação já está rodando.

## Acessar

No navegador acessar a URL `http://localhost:3000`.

Pra API basta importar o arquivo
[InsomniaApiCollection.json](https://github.com/NathanReis/GerenciaExtintor/blob/main/InsomniaApiCollection.json)
em algum API Client, como Postman ou Insomnia.
