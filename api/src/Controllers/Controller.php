<?php

namespace App\Controllers;

use App\DAOs\DBConnection;
use App\Helpers\ResponseHelper;
use Psr\Http\Message\ResponseInterface as IResponse;
use Psr\Http\Message\ServerRequestInterface as IRequest;

abstract class Controller
{
    /** @var mixed Objeto de acesso a dados. */
    protected $dao;

    /** @var mixed Objeto para validações. */
    protected $validation;

    /**
     * Recebe os dados da requisição e retorna um objeto modelo preenchido.
     *
     * @param array $requestData Dados recebidos da requisição.
     *
     * @return mixed Um dos objetos modelo da aplicação.
     */
    abstract protected function fillModel(array $requestData): mixed;

    /**
     * Salva o registro de um modelo.
     *
     * Pega os dados recebidos pela requisição, preenche um modelo com eles, valida e salva.
     *
     * Caso tenha sido encontrado algum erro durante a validação deverá retornar-los.
     *
     * Caso seja lançado alguma exceção durante a execução do método, fazer
     * rollback na transação ativa a lançar a exceção para frente.
     *
     * @api
     *
     * @param Psr\Http\Message\ServerRequestInterface $request A requisição recebida.
     * @param Psr\Http\Message\ResponseInterface $response A resposta a ser preenchida.
     *
     * @return Psr\Http\Message\ResponseInterface A resposta a ser enviada.
     *
     * @throws \Exception Qualquer excessão.
     */
    public function create(IRequest $request, IResponse $response): IResponse
    {
        try {
            $model = $this->fillModel($request->getParsedBody());

            $errors = $this->validation->checkCreate($model);

            if ($errors) {
                return ResponseHelper::getNewFailResponseWithJSON(
                    response: $response,
                    data: $errors
                );
            }

            DBConnection::getInstance()->beginTransaction();

            $this->dao->create($model);

            DBConnection::getInstance()->commit();

            return ResponseHelper::getNewSuccessResponseWithJSON(
                response: $response,
                data: ["id" => $model->getId()],
                statusCode: 201
            );
        } catch (\Exception $exception) {
            if (DBConnection::getInstance()->inTransaction()) {
                DBConnection::getInstance()->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Apaga o registro de um modelo.
     *
     * Pega o ID recebido pela requisição, valida o modelo que ele pertence e o apaga.
     *
     * Caso tenha sido encontrado algum erro durante a validação deverá retorna-los.
     *
     * Caso seja lançado alguma exceção durante a execução do método, fazer
     * rollback na transação ativa a lançar a exceção para frente.
     *
     * @api
     *
     * @param Psr\Http\Message\ServerRequestInterface $request A requisição recebida.
     * @param Psr\Http\Message\ResponseInterface $response A resposta a ser preenchida.
     * @param array $args Dados recebidos atrvés da rota.
     *
     * @return Psr\Http\Message\ResponseInterface A resposta a ser enviada.
     *
     * @throws \Exception Qualquer excessão.
     */
    public function delete(IRequest $request, IResponse $response, array $args): IResponse
    {
        try {
            $id = (int)$args["id"];

            $errors = $this->validation->checkDelete($id);

            if ($errors) {
                return ResponseHelper::getNewFailResponseWithJSON(
                    response: $response,
                    data: $errors
                );
            }

            DBConnection::getInstance()->beginTransaction();

            $this->dao->delete($id);

            DBConnection::getInstance()->commit();

            return ResponseHelper::getNewSuccessResponseWithJSON(
                response: $response,
                data: "ID {$id} deleted"
            );
        } catch (\Exception $exception) {
            if (DBConnection::getInstance()->inTransaction()) {
                DBConnection::getInstance()->rollBack();
            }

            throw $exception;
        }
    }

    /**
     * Busca todos registros de um modelo específico.
     *
     * Faz a busca pelos registros e os converte para array antes de retornar.
     *
     * @api
     *
     * @param Psr\Http\Message\ServerRequestInterface $request A requisição recebida.
     * @param Psr\Http\Message\ResponseInterface $response A resposta a ser preenchida.
     *
     * @return Psr\Http\Message\ResponseInterface A resposta a ser enviada.
     */
    public function findAll(IRequest $request, IResponse $response): IResponse
    {
        return ResponseHelper::getNewSuccessResponseWithJSON(
            response: $response,
            data: array_map(
                function ($model) {
                    return $model->toArray();
                },
                $this->dao->findAll()
            )
        );
    }

    /**
     * Busca o registro de um modelo específico.
     *
     * Faz a busca pelo registro e o converte para array antes de retornar.
     *
     * Caso não tenha encontrado o modelo, retornar erro 404.
     *
     * @api
     *
     * @param Psr\Http\Message\ServerRequestInterface $request A requisição recebida.
     * @param Psr\Http\Message\ResponseInterface $response A resposta a ser preenchida.
     * @param array $args Dados recebidos atrvés da rota.
     *
     * @return Psr\Http\Message\ResponseInterface A resposta a ser enviada.
     */
    public function findFirstById(IRequest $request, IResponse $response, array $args): IResponse
    {
        $id = (int)$args["id"];

        $model = $this->dao->findFirst("id", $id);

        if (empty($model)) {
            return ResponseHelper::getNewFailResponseWithJSON(
                response: $response,
                data: [],
                statusCode: 404
            );
        }

        return ResponseHelper::getNewSuccessResponseWithJSON(
            response: $response,
            data: $model->toArray()
        );
    }

    /**
     * Atualiza o registro de um modelo.
     *
     * Pega os dados recebidos pela requisição, preenche um modelo com eles, valida e salva.
     *
     * Caso tenha sido encontrado algo erro durante a validação deverá retorna-los.
     *
     * Caso seja lançado alguma exceção durante a execução do método, fazer
     * rollback na transação ativa a lançar a exceção para frente.
     *
     * @api
     *
     * @param Psr\Http\Message\ServerRequestInterface $request A requisição recebida.
     * @param Psr\Http\Message\ResponseInterface $response A resposta a ser preenchida.
     * @param array $args Dados recebidos atrvés da rota.
     *
     * @return Psr\Http\Message\ResponseInterface A resposta a ser enviada.
     *
     * @throws \Exception Qualquer excessão.
     */
    public function update(IRequest $request, IResponse $response, array $args): IResponse
    {
        try {
            $requestData = $request->getParsedBody() + $args;

            $model = $this->fillModel($requestData);

            $errors = $this->validation->checkUpdate($model);

            if ($errors) {
                return ResponseHelper::getNewFailResponseWithJSON(
                    response: $response,
                    data: $errors
                );
            }

            DBConnection::getInstance()->beginTransaction();

            $this->dao->update($model);

            DBConnection::getInstance()->commit();

            return ResponseHelper::getNewSuccessResponseWithJSON(
                response: $response,
                data: "ID {$model->getId()} updated"
            );
        } catch (\Exception $exception) {
            if (DBConnection::getInstance()->inTransaction()) {
                DBConnection::getInstance()->rollBack();
            }

            throw $exception;
        }
    }
}
