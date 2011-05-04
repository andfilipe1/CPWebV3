<?php
	// menu da lista cidades
	$listaMenu = array();

	// módulo contatos
	if (!in_array('contatos',$this->Session->read('urlsNao')))
	{
		$listaMenu['contatos']['text'] 	= 'Cadastro de Contatos';
		$listaMenu['contatos']['url'] 	= Router::url('/',true).'contatos';
	}

	// módulo processos
	if (!in_array('processos',$this->Session->read('urlsNao')))
	{
		$listaMenu['processos']['text'] = 'Controle de Processos';
		$listaMenu['processos']['url'] 	= Router::url('/',true).'processos';
	}

	// destacando a opção ativa
	if (!isset($listaMenu[$name]['text'])) $listaMenu[$name]['text'] = $name;
	$listaMenu[$name]['url'] = '#';

	asort($listaMenu);
?>
