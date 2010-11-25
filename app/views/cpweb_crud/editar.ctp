<?php $this->Html->css('editar.css', null, array('inline' => false)); ?>
<?php $this->Html->script('editar.js', array('inline' => false)); ?>
<?php $arq = '../views/'.$pluralVar.'/config_'.$pluralVar.'.ctp'; if (file_exists($arq)) include_once($arq); ?>

<div id='edicao'>

<?php echo $this->element('cpweb_cab'); ?>

<?php echo $this->Form->create($modelClass)."\n"; ?>
<?php echo $this->Form->input($primaryKey)."\n"; ?>

<div id="formFerramentas">
	<div id="botoesEdicao">
	<?php if (isset($edicaoCampos)) foreach($botoesEdicao as $_label => $_arrOpcoes) if (count($_arrOpcoes)) echo "\t".$form->button($_label,$_arrOpcoes)."\n"; ?>
	</div>
	<div id="msgEdicao"><?php if (isset($msgEdicao)) echo $msgEdicao; ?></div>
</div>

<div id="formEdicao" class="camposEdicao">
<?php
	// campo a campo
	if (isset($edicaoCampos))
	{
		foreach($edicaoCampos as $_field)
		{
			if ($_field=='#') 
			{
				echo "<br class='quebraLinha' />\n";
			} else
			{
				$_arrField 					= explode('.',$_field);
				$opcoes 					= isset($campos[$_arrField[0]][$_arrField[1]]['options']) ? $campos[$_arrField[0]][$_arrField[1]]['options'] : array();
				$opcoes['div'] 				= isset($opcoes['div']) ? $opcoes['div'] : null;
				$opcoes['label']['class']	= isset($opcoes['label']['class']) ? $opcoes['label']['class'] : 'inEdicao';
				$tipo 						= isset($opcoes['tipo']) ? $opcoes['tipo'] : 'text';
				switch($tipo)
				{
					case 'leitura':
						echo '<div id="div'.$this->Form->domId($_field).'" class="edicaoDiv">'.$this->data[$singularVar][$_field].'</div>'."\n";
						break;
					default:
						echo '<div id="div'.$this->Form->domId($_field).'" class="edicaoDiv">'.$this->Form->input($_field,$opcoes).'</div>'."\n";
						break;
				}
			}
		}
	} else
	{
		echo '<center>É preciso definir quais campos serão editados !!!<br /><br />Clique <a href="javascript:history.back(-1);">aqui</a> para voltar.</center>';
		$on_read_view .= 'setTimeout(function(){ $("#formFerramentas").fadeOut(1000); },1000);';
	}
?>
<?php echo $this->Form->end(); ?>

</div>
</div>
<?php include_once('../views/cpweb_crud/rodape.ctp'); ?>
