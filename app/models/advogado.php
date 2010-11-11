<?php
/**
 * CPWeb - Controle Virtual de Processos
 * Versão 3.0 - Novembro de 2010
 *
 * app/model/advogado.php
 *
 * A reprodução de qualquer parte desse arquivo sem a prévia autorização
 * do detentor dos direitos autorais constitui crime de acordo com
 * a legislação brasileira.
 *
 * This product is protected by copyright and distributed under licenses restricting
 * copying, distribution, and non-allowed selling/trading
 *
 * @copyright   Copyright 2010, Valéria Esteves Advogados Associados ( www.veadvogados.com.br )
 * @copyright   Copyright 2010, Gustavo Dias Duarte Ramos ( gustavo at gustavo-ramos dot com )
 * @link http://cpweb.veadvogados.adv.br
 * @package cpweb
 * @subpackage cpweb.v3
 * @since CPWeb V3
 */
    class Advogado extends AppModel {

        var $name = 'Advogado';

        var $hasMany = array( 'Audiencia', 'Processo');

        var $validate = array(
            'oab' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'É necessário informar a OAB do Advogado!'
            ),
            'nome' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'É necessário informar o nome do Advogado!'
            )
        );
    }