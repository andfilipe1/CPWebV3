<?php

	$campos[$modelClass]['data_atendimento']['options']['label']['text'] 		= 'dtAtendimento';
	$campos[$modelClass]['data_atendimento']['options']['dateFormat'] 			= 'DMY';
	$campos[$modelClass]['data_atendimento']['options']['timeFormat'] 			= '24';
	$campos[$modelClass]['data_atendimento']['mascara'] 						= 'datahora';
	$campos[$modelClass]['data_atendimento']['estilo_th'] 						= 'width="200px"';
	$campos[$modelClass]['data_atendimento']['estilo_td'] 						= 'style="text-align: center; "';
	
	$campos[$modelClass]['data_fechamento']['options']['label']['text'] 		= 'dtFechamento';
	$campos[$modelClass]['data_fechamento']['options']['dateFormat'] 			= 'DMY';
	$campos[$modelClass]['data_fechamento']['options']['timeFormat'] 			= '24';
	$campos[$modelClass]['data_fechamento']['mascara'] 							= 'datahora';
	$campos[$modelClass]['data_fechamento']['estilo_th'] 						= 'width="200px"';
	$campos[$modelClass]['data_fechamento']['estilo_td'] 						= 'style="text-align: center; "';
	
	$campos[$modelClass]['finalizada']['options']['label']['text'] 				= 'Finalizada';
	$campos[$modelClass]['finalizada']['options']['options'] 					= array(1=>'Sim', 0=>'Não');
	
	$campos[$modelClass]['ispeticao']['options']['label']['text'] 				= 'Petição';
	$campos[$modelClass]['ispeticao']['options']['options'] 					= array(1=>'Sim', 0=>'Não');
	
	$campos[$modelClass]['isparecer']['options']['label']['text'] 				= 'Parecer';
	$campos[$modelClass]['isparecer']['options']['options'] 					= array(1=>'Sim', 0=>'Não');
	
	$campos[$modelClass]['obs']['options']['label']['text'] 					= 'Obs';
	
	$campos[$modelClass]['solicitacao_id']['options']['label']['text'] 			= 'Solicitação';
	$campos[$modelClass]['solicitacao_id']['options']['style'] 					= 'width:300px';
	if (isset($solicitacoes)) $campos[$modelClass]['solicitacao_id']['options']['options'] 			= $solicitacoes;

	$campos[$modelClass]['processo_id']['options']['label']['text'] 			= 'Processo';
	$campos[$modelClass]['processo_id']['options']['style'] 					= 'width:300px';
	if (isset($processos)) $campos[$modelClass]['processo_id']['options']['options'] 				= $processos;
	
	$campos[$modelClass]['destino_id']['options']['label']['text'] 				= 'Destino';
	$campos[$modelClass]['destino_id']['options']['style'] 						= 'width:300px';
	if (isset($destinos)) $campos[$modelClass]['destino_id']['options']['options'] 					= $destinos;
	
	$campos[$modelClass]['tipo_peticao_id']['options']['label']['text'] 		= 'TipoPetição';
	$campos[$modelClass]['tipo_peticao_id']['options']['style'] 				= 'width:300px';
	if (isset($tipopeticoes)) $campos[$modelClass]['tipo_peticao_id']['options']['options'] 		= $tipopeticoes;
	
	$campos[$modelClass]['tipo_parecer_id']['options']['label']['text'] 		= 'TipoParecer';
	$campos[$modelClass]['tipo_parecer_id']['options']['style'] 				= 'width:300px';
	if (isset($tipopareceres)) $campos[$modelClass]['tipo_parecer_id']['options']['options'] 		= $tipopareceres;
	
	$campos[$modelClass]['complexidade_id']['options']['label']['text'] 		= 'Complexidade';
	$campos[$modelClass]['complexidade_id']['options']['style'] 				= 'width:300px';
	if (isset($complexidades)) $campos[$modelClass]['complexidade_id']['options']['options'] 		= $complexidades;

	if ($action=='editar' || $action=='excluir')
	{
		$edicaoCampos = array($modelClass.'.solicitacao_id','#',$modelClass.'.processo_id','#',$modelClass.'.data_atendimento','#',$modelClass.'.data_fechamento','#',$modelClass.'.finalizada','#',$modelClass.'.destino_id','#',$modelClass.'.tipo_peticao_id','#',$modelClass.'.tipo_parecer_id','#',$modelClass.'.complexidade_id','#',$modelClass.'.ispeticao','#',$modelClass.'.isparecer','#',$modelClass.'.obs','#',$modelClass.'.modified','#',$modelClass.'.created');
	}

	if ($action=='imprimir')
	{
		$edicaoCampos = array($modelClass.'.solicitacao_id',$modelClass.'.processo_id',$modelClass.'.data_atendimento',$modelClass.'.data_fechamento',$modelClass.'.finalizada',$modelClass.'.destino_id',$modelClass.'.tipo_peticao_id',$modelClass.'.tipo_parecer_id','#',$modelClass.'.complexidade_id',$modelClass.'.ispeticao',$modelClass.'.isparecer',$modelClass.'.obs','#',$modelClass.'.modified',$modelClass.'.created');
	}

	if ($action=='novo')
	{
		$edicaoCampos = array($modelClass.'.solicitacao_id','#',$modelClass.'.processo_id','#',$modelClass.'.data_atendimento','#',$modelClass.'.data_fechamento','#',$modelClass.'.finalizada','#',$modelClass.'.destino_id','#',$modelClass.'.tipo_peticao_id','#',$modelClass.'.tipo_parecer_id','#',$modelClass.'.complexidade_id','#',$modelClass.'.ispeticao','#',$modelClass.'.isparecer','#',$modelClass.'.obs');
	}
	
	if ($action=='editar' || $action=='novo')
	{
		$on_read_view .= "\n".'$("#'.$modelClass.'SolicitacaoId").focus();';
	}

	if ($action=='editar' || $action=='listar')
	{
		$camposPesquisa['obs'] 	= 'Obs';
		$this->set('camposPesquisa',$camposPesquisa);
	}

	if ($action=='listar')	
	{
		$listaCampos = array($modelClass.'.data_atendimento',$modelClass.'.data_fechamento',$modelClass.'.finalizada',$modelClass.'.ispeticao',$modelClass.'.isparecer',$modelClass.'.modified',$modelClass.'.created');
	}
?>