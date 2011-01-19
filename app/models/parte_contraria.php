<?php
/**
 * CPWeb - Controle Virtual de Processos
 * Versão 3.0 - Novembro de 2010
 *
 * app/model/parte_contraria.php
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
class ParteContraria extends AppModel {

        public $nome 			= 'ParteContraria';
        public $useTable		= 'partes_contrarias';
        public $displayField	= 'nome';
        public $order		 	= 'ParteContraria.nome';

        public $validate = array(
            'nome' => array
            (
				1	=> array
				(
					'rule' 		=> 'notEmpty',
					'required' 	=> true,
					'message' 	=> 'É necessário informar o nome da Parte Contrária!'
                )
            ),

            'cpf' => array
            (
				1	=> array
				(
					'rule'		=> 'isUnique',
					'message'	=> 'Este CPF já foi cadastrado !!!',
					'allowEmpty'=> true,
					'required'	=> true
				),
				2	=> array
				(
					'rule'		=> 'validaCPF',
					'message'	=> 'Cpf inválido !!!',
				)
			),
			
			'cnpj' => array
            (
				1	=> array
				(
					'rule'		=> 'isUnique',
					'message'	=> 'Este CNPJ já foi cadastrado !!!',
					'allowEmpty'=> true,
					'required'	=> true
				),
				2	=> array
				(
					'rule'		=> 'validaCNPJ',
					'message'	=> 'CNPJ inválido !!!',
				)
			),

            'endereco' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'É necessário informar o endereço da Parte Contrária!'
            ),

            'cidade_id' => array(
                'rule' => 'notEmpty',
                'required' => true,
                'message' => 'É necessário informar a Cidade de domicílio da Parte Contrária!'
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

	/**
	 * Antes de validar, lima a máscara do cpf e cnpj
	 * 
	 * @return void
	 */
	public function beforeValidate()
	{
		// se não postou cpf ou cnpj, remove a validação unique dos mesmos
		if (!isset($this->data[$this->name]['cpf']))	$this->validate['cpf'][1] = array();
		if (!isset($this->data[$this->name]['cnpj']))	$this->validate['cnpj'][1] = array();

		// atualizando cpf e cnpj
		$this->setCpf();
		
		parent::beforeValidate();
	}

	/**
	 * Limpa cpf e cnpj 
	 * 
	 * @return void
	 */
	private function setCpf()
	{
		// limpando cnpj e cpf
		if (isset($this->data['Cliente']['cnpj'])) 	$this->data['Cliente']['cnpj'] = ereg_replace('[./-]','',$this->data['Cliente']['cnpj']);	
		if (isset($this->data['Cliente']['cpf']))	$this->data['Cliente']['cpf'] = ereg_replace('[./-]','',$this->data['Cliente']['cpf']);
	}
}

?>
