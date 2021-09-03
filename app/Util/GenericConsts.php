<?php

namespace Util;

abstract class GenericConsts
{
    /* REQUESTS */
    public const TIPO_REQUEST = ['GET', 'POST', 'DELETE', 'PUT'];
    public const TIPO_GET = ['USUARIOS', 'PACIENTES', 'MEDICOS', 'AGENDAS', 'AGENDAMENTOS'];
    public const TIPO_POST = ['USUARIOS', 'PACIENTES', 'MEDICOS', 'AGENDAS', 'AGENDAMENTOS'];
    public const TIPO_DELETE = ['USUARIOS', 'PACIENTES', 'MEDICOS', 'AGENDAS', 'AGENDAMENTOS'];
    public const TIPO_PUT = ['USUARIOS', 'PACIENTES', 'MEDICOS', 'AGENDAS', 'AGENDAMENTOS'];

    /* ERROS */
    public const MSG_ERRO_TIPO_ROTA = 'Rota não permitida!';
    public const MSG_ERRO_RECURSO_INEXISTENTE = 'Recurso inexistente!';
    public const MSG_ERRO_GENERICO = 'Algum erro ocorreu na requisição!';
    public const MSG_ERRO_SEM_RETORNO = 'Nenhum registro encontrado!';
    public const MSG_ERRO_NAO_AFETADO = 'Nenhum registro afetado!';
    public const MSG_ERRO_TOKEN_VAZIO = 'É necessário informar um Token!';
    public const MSG_ERRO_TOKEN_NAO_AUTORIZADO = 'Token não autorizado!';
    public const MSG_ERR0_JSON_VAZIO = 'O Corpo da requisição não pode ser vazio!';
    public const MSG_ERRO_HORARIO_AGENDADO = 'Este horário ja está agendado';

    /* SUCESSO */
    public const MSG_DELETADO_SUCESSO = 'Registro deletado com Sucesso!';
    public const MSG_ATUALIZADO_SUCESSO = 'Registro atualizado com Sucesso!';

    /* RECURSO USUARIOS */
    public const MSG_ERRO_ID_OBRIGATORIO = 'ID é obrigatório!';
    public const MSG_ERRO_LOGIN_EXISTENTE = 'Login já existente!';
    public const MSG_ERRO_LOGIN_SENHA_OBRIGATORIO = 'Login e Senha são obrigatórios!';
    public const MSG_ERRO_TODOS_CAMPOS_OBRIGATORIOS = 'Todos os campos são obrigatórios';

    /* RETORNO JSON */
    const TIPO_SUCESSO = 'sucesso';
    const TIPO_ERRO = 'erro';

    /* OUTRAS */
    public const SIM = 'S';
    public const TIPO = 'tipo';
    public const RESPOSTA = 'resposta';
}