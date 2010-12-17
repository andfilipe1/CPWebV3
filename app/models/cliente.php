<?php
/**
 * CPWeb - Controle Virtual de Processos
 * Versão 3.0 - Novembro de 2010
 *
 * app/model/cliente.php
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
class Cliente extends AppModel {

        public $nome 			= 'Cliente';
        public $displayField	= 'nome';
        public $order		 	= 'Cliente.nome';

        public $validate = array(
            'nome' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'É necessário informar o nome do Cliente!'
            ),
            'cnpj' => array(

            ),
            'cpf' => array(

            ),
            'endereco' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'É necessário informar o endereço do Cliente!'
            ),
            'cidade_id' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'É necessário informar a Cidade de domicílio do Cliente!'
            )
        );
        
	public $belongsTo = array(
		'Cidade' => array(
			'className' => 'Cidade',
			'foreignKey' => 'cidade_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

	public $hasMany = array(
		'Processo' => array(
			'className' => 'Processo',
			'foreignKey' => 'cliente_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Telefone' => array(
			'className' => 'Telefone',
			'foreignKey' => 'cliente_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	/**
	 * 
	 */
	public function beforeSave()
	{
		if (isset($this->data['Cliente']['cnpj']))
		{
			$this->data['Cliente']['cnpj'] = str_replace('.','',$this->data['Cliente']['cnpj']);
			$this->data['Cliente']['cnpj'] = str_replace('/','',$this->data['Cliente']['cnpj']);
			$this->data['Cliente']['cnpj'] = str_replace('-','',$this->data['Cliente']['cnpj']);
		}

		if (isset($this->data['Cliente']['cpf']))
		{
			$this->data['Cliente']['cpf'] = str_replace('.','',$this->data['Cliente']['cpf']);
			$this->data['Cliente']['cpf'] = str_replace('-','',$this->data['Cliente']['cpf']);
		}
		
		// salvando o subFormulário
		$this->Telefone->belongsTo = array();
		if (!$this->setSubForm('cliente_id',$this->id,'Telefone')) return false;

		return true;
	}
	
	/**
	 * Apaga os relacionamento do usuário 
	 */
	public function beforeDelete()
	{
		// apagando os relacionamentos com telefone
		if ($this->Telefone->deleteAll(array('cliente_id'=>$this->id))) return true; else return false;
	}
	
	/**
	 * Atualiza o subformulário
	 * obs: 
	 * A tabela de subFormalário deve ser hasMany. 
	 * os ids do modelo que não foram enviados serão deletados.
	 * campos sem id serão incluídos
	 * 
	 * @parameter array 	$idPai 	Matriz dizendo o nome do campo id e o seu valor
	 * @parameter string 	$modelo	Nome do modelo que será atualizado
	 */
	public function setSubForm($nomeIdPai,$valorIdPai,$modelo)
	{
		$dataModelo	= array();
		$arrIdSalvos	= array();

		// recuperando os ids que serão atualizados
		foreach($this->data[$this->name] as $campo => $valor)
		{
			if (substr($campo,0,8)=='subForm_')
			{
				$arrCampo 	= explode('_',$campo);
				$id			= $arrCampo[1];
				$dataModelo[$modelo][$id][$this->$modelo->primaryKey] = $id;
				$dataModelo[$modelo][$id][$arrCampo[2]] = $valor;
				if(!in_array($arrCampo[1],$arrIdSalvos)) array_unshift($arrIdSalvos,$arrCampo[1]);
			}
		}

		// deletando
		$delCondicao[$nomeIdPai] = $valorIdPai;
		if (count($arrIdSalvos)) $delCondicao['NOT'][$this->$modelo->primaryKey] = $arrIdSalvos;
		if (!$this->$modelo->deleteAll($delCondicao)) return false;

		// atualizando
		if (count($arrIdSalvos)) if (!$this->$modelo->saveAll($dataModelo[$modelo])) return false;
		
		// incluindo
		$dataModelo	= array();
		foreach($this->data[$this->name] as $campo => $valor)
		{
			if (substr($campo,0,12)=='subNovoForm_')
			{
				$arrCampo 	= explode('_',$campo);
				if ($valor) $dataModelo[$modelo][$arrCampo[1]] = $valor;
			}
		}
		if (count($dataModelo))
		{
			$dataModelo[$modelo][$this->$modelo->primaryKey] = null;
			$dataModelo[$modelo][$nomeIdPai] = $valorIdPai;
			if (!$this->$modelo->save($dataModelo[$modelo])) return false;
		}

		return true;
	}
 }
