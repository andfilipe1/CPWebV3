<?php
/**
 * CPWeb - Controle Virtual de Processos
 * Versão 3.0 - Novembro de 2010
 *
 * app/controllers/relatorios_controller.php
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
class RelatoriosController extends AppController {

	/**
	 * Nome
	 * 
	 * @var string
	 * @access public
	 */
	public $name = 'Relatorios';
	
	/**
	 * Modelo
	 * 
	 * @var string
	 * @access public
	 */
	public $uses = 'Relatorio';

	/**
	 * Ajudantes 
	 * 
	 * @var array
	 * @access public
	 */
	public $helpers = array('CakePtbr.Formatacao');
	
	/**
	 * Componentes
	 * 
	 * @var array Componentes
	 * @access public
	 */
	public $components	= array('CpwebCrud','Session');
	
	/**
	 * 
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
	}
 
	/**
	 * método start
	 * 
	 * @return void
	 */
	public function index()
	{
	}

	/**
	 * Lista os dados em paginação
	 * 
	 * @parameter integer 	$pag 		Número da página
	 * @parameter string 	$ordem 		Campo usado no order by da sql
	 * @parameter string 	$direcao 	Direção ASC ou DESC
	 * @return void
	 */
	public function listar($pag=1,$ordem=null,$direcao='DESC')
	{
		$this->CpwebCrud->listar($pag,$ordem,$direcao);
	}

	/**
	 * 
	 */
	public function processos1()
	{
		// filtro funcionários
		$this->loadModel('Usuario');
		$data['funcionario']['options']['label']['text'] 	= 'Funcionário';
		$data['funcionario']['options']['default'] 			= 0;
		$data['funcionario']['options']['empty'] 			= '-- escolha uma opção --';
		$data['funcionario']['options']['options'] 			= $this->Usuario->find('list');
		
		// filtro cliente
		$this->loadModel('Cliente');
		$data['cliente']['options']['default'] 				= 0;
		$data['cliente']['options']['empty'] 				= '-- escolha uma opção --';
		$data['cliente']['options']['options'] 				= $this->Cliente->find('list');
		
		// filtro cliente
		$this->loadModel('Departamento');
		$data['departamento']['options']['label']['text'] 	= 'Departamento';
		$data['departamento']['options']['default'] 		= 0;
		$data['departamento']['options']['empty'] 			= '-- escolha uma opção --';
		$data['departamento']['options']['options'] 		= $this->Departamento->find('list');

		$data['data_ini']['options']['label']['text']		= 'data Inicio';
		$data['data_ini']['options']['div'] 				= null;
		$data['data_ini']['options']['dateFormat'] 			= 'DMY';
		$data['data_ini']['options']['monthNames'] 			= false;
		$data['data_ini']['options']['interval']			= 3;
		$data['data_ini']['options']['type'] 				= 'date';

		$data['data_fim']['options']['label']['text']		= 'data Fim';
		$data['data_fim']['options']['div'] 				= null;
		$data['data_fim']['options']['dateFormat'] 			= 'DMY';
		$data['data_fim']['options']['monthNames'] 			= false;
		$data['data_fim']['options']['year'] 				= 2012;
		$data['data_fim']['options']['type'] 				= 'date';
		$data['data_fim']['options']['value'] 				= strtotime('+30 days');

		if (isset($this->data['processos1']) && !empty($this->data['processos1']))
		{
			$condicoes = array();

			// filtro usuario
			if (isset($this->data['processos1']['funcionario']) && !(empty($this->data['processos1']['funcionario'])))
			{
				$condicoes['ProcessoSolicitacao.usuario_atribuido']	= $this->data['processos1']['funcionario'];
			}

			// filtro departamento
			if (isset($this->data['processos1']['departamento']) && !(empty($this->data['processos1']['departamento'])))
			{
				$dataDepartamento = $this->Usuario->find('all',array('conditions'=>array('Usuario.departamento_id'=>$this->data['processos1']['departamento'])));
				foreach($dataDepartamento as $_modelo => $_arrCampos)
				{
					foreach($_arrCampos as $_campo => $_valor)
					{
						$condicoes['ProcessoSolicitacao.usuario_atribuido'][$_valor];
					}
				}
			}
			
			// filtro cliente
			if (isset($this->data['processos1']['cliente']) && !(empty($this->data['processos1']['cliente'])))
			{
				$condicoes['ProcessoSolicitacao.processo_id'] = array();
				$this->loadModel('Processo');
				$dataProcesso = $this->Processo->find('all',array('conditions'=>array('cliente_id'=>$this->data['processos1']['cliente'])));
				foreach($dataProcesso as $_modelo => $_arrCampos)
				{
					foreach($_arrCampos as $_campo => $_valor)
					{
						$condicoes['ProcessoSolicitacao.processo_id'][$_valor];
					}
				}
			}
			
			// filtro data
			if (	isset($this->data['processos1']['data_ini']) && !(empty($this->data['data_ini'])) &&
					isset($this->data['processos1']['data_fim']) && !(empty($this->data['data_fim']))
				)
			{
				$condicoes['ProcessoSolicitacao.created BETWEEN ? AND ?'] = array($this->data['processos1']['data_inicio'],$this->data['processos1']['data_fim']);
			}

			// carregar ProcessosSolicitações
			$this->loadModel('ProcessoSolicitacao');
			$data = $this->paginate('ProcessoSolicitacao',$condicoes);

			// enviar data para a visão lista_relatorio
			$this->set(compact('data'));

			// exibindo 
			$this->render('listar');
		} else
		{
		
			$this->set(compact('data'));
			$this->set('titulo','Filtro para Processos e Solicitações');
		}
	}
}