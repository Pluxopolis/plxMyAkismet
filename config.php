<?php if(!defined('PLX_ROOT')) exit; ?>
<?php

# Control du token du formulaire
plxToken::validateFormToken($_POST);

if(!empty($_POST)) {

	# sauvegarde des paramètres
	$plxPlugin->setParam('api_key', $_POST['api_key'], 'string');
	$plxPlugin->saveParams();

	# redirection sur la page de configuration du plugin
	header('Location: parametres_plugin.php?p=plxMyAkismet');
	exit;
}

# initialisation des paramètres par défaut
$params = $plxPlugin->getParams();
$api_key = empty($params['api_key']) ? '' : $params['api_key']['value'];

?>

<h2><?php echo $plxPlugin->getInfo('title') ?></h2>
<h3><?php echo $plxPlugin->getInfo('description') ?></h3>

<form action="parametres_plugin.php?p=plxMyAkismet" method="post" id="form_loremipsum">
	<fieldset>
		<p class="field"><label for="api_key">Akismet API key</label></p>
		<?php plxUtils::printInput('api_key', $api_key, 'text', '15-50'); ?>
		<p>
			<?php echo plxToken::getTokenPostMethod() ?>
			<input type="submit" name="save" value="<?php $plxPlugin->lang('L_SAVE') ?>" />
		</p>
	</fieldset>
</form>

