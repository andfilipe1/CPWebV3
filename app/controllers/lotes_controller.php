<?php
/**
 * CPWeb - Controle Virtual de Processos
 * Versão 3.0 - Novembro de 2010
 *
 * app/controllers/lotes_controller.php
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
class LotesController extends AppController {

	/**
	 * Nome
	 * 
	 * @var string
	 * @access public
	 */
	public $name = 'Lotes';
	
	/**
	 * Modelo
	 * 
	 * @var string
	 * @access public
	 */
	public $uses = 'Lote';

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
	 * Método chamado antes de qualquer outro método
	 * 
	 * @access 	public
	 * @return 	void
	 */
	public function beforeFilter()
	{
		$this->set('arqListaMenu','menu_modulos');
		parent::beforeFilter();
	}
 
	/**
	 * método start
	 * 
	 * @return void
	 */
	public function index()
	{
		$this->redirect('listar');
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
	 * Exibe formulário de edição para o model
	 * 
	 * @parameter	integer 	$id 	Chave única do registro da model
	 * @return 		void
	 */
	public function editar($id=null)
	{
		$this->redirect('listar');
	}

	/**
	 * Exibe formulário de inclusão para o model
	 * 
	 * @return 		void
	 */
	public function novo()
	{
		$this->set('usuario_id',$this->Session->read('Auth.Usuario.id'));
		$this->set('solicitacao_id',6);
		$this->loadModel('Solicitacao');
		$solicitacoes = $this->Solicitacao->find('list');
		$this->set(compact('solicitacoes'));
		$this->loadModel('ProcessoSolicitacao');
		$this->CpwebCrud->novo();
		if (isset($this->data))
		{
			// recupeando processos e solicitações
			$this->loadModel('ProcessoSolicitacao');
			$this->ProcessoSolicitacao->recursive = -1;
			$condicoes['ProcessoSolicitacao.finalizada'] 		= 0;
			$condicoes['ProcessoSolicitacao.usuario_atribuido'] = 0;
			$condicoes['ProcessoSolicitacao.solicitacao_id'] 	= $this->data['Lote']['solicitacao_id'];
			$idsPS = array();
			$PS = $this->ProcessoSolicitacao->find('all',array('conditions'=>$condicoes));
			foreach($PS as $_linha => $_arrModel)
			{
				array_push($idsPS, $_arrModel['ProcessoSolicitacao']['id']);
			}

			// atribuindo processo e solicitações
			$dataPS['usuario_atribuido'] 		= $this->data['Lote']['usuario_id'];
			$condPS['ProcessoSolicitacao.id']	= $idsPS;
			$this->ProcessoSolicitacao->updateAll($dataPS,$condPS);

			// incluindo novos lotes-processos-solicitacoes
			$this->loadModel('LoteProcessoSolicitacao');
			$idLote 	= $this->Lote->getLastInsertID();
			$dataLPS 	= array();
			$l			= 0;
			foreach($idsPS as $_id)
			{
				$dataLPS[$l]['lote_id'] = $idLote;
				$dataLPS[$l]['processo_solicitacao_id'] = $_id;
				$l++;
			}
			$this->LoteProcessoSolicitacao->saveAll($dataLPS);
		}
	}

	/**
	 * Exibe formulário de exclusão para o model
	 * 
	 * @return 		void
	 */
	public function excluir($id=null)
	{
		$this->CpwebCrud->excluir($id);
	}

	/**
	 * Exclui a cidade do banco de dados
	 * 
	 * @return 		void
	 */
	public function delete($id=null)
	{
		$this->CpwebCrud->delete($id);
	}
}
